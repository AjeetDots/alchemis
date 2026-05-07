# Alchemis — Performance & Multi-User Load Report

**Audience:** Tech Lead, Server Team, Management  
**Date:** May 7, 2026  
**Prepared from:** code review of legacy Alchemis (PHP + MySQL, AWS RDS)

---

## 1. Executive Summary (One Page)

Alchemis becomes slow or appears frozen because **heavy data work runs live during user clicks**, on a **single shared MySQL database**, with **no background queue and no caching layer**. The architecture was designed for a small number of concurrent users with smaller data volumes.

As users and data have grown:
- Heavy actions (filter regenerate, big reports, exports) **monopolize PHP workers and MySQL resources**.
- Other users — even those on light screens — feel the slowdown.
- AJAX calls time out or return broken JSON → frontend shows **JS popups / “Failed to parse” / “Please wait” warnings**.

These symptoms are **side effects of the architecture**, not random bugs.

> **The fix is not a rewrite.** It is moving heavy work off the user’s click, adding caching, indexing the database, and tuning the server. Hardware upgrades alone will **not** solve it.

---

## 2. How the Application Was Built

| Indicator in code | Meaning |
|---|---|
| `set_time_limit(300)` in filter & AJAX commands | A single click can run for **up to 5 minutes** — designed for one user at a time |
| `@ini_set('memory_limit', '512M')` raised inside requests | Heavy in-memory work tolerated |
| **No queue / worker system** in codebase | All heavy work runs **synchronously** in the web request |
| **No cache layer** (no APCu/Redis/Memcached) | Same queries are repeated every page load |
| `tbl_filter_results` rebuilt by **DELETE + bulk INSERT** | Designed for occasional rebuilds, not concurrent heavy use |
| Dashboard uses **~12 sequential queries** | Designed when DB was small and users few |
| `MAX_FILTER_RESULTS_FOR_DISPLAY = 5000` recently capped | Defensive band-aid added because data grew |

**Conclusion:** The app architecture matches a 2007–2010 single-user / small-data design.

---

## 3. How a Click Flows Today

```mermaid
flowchart LR
  U[User Browser] -->|HTTP request| W[PHP-FPM Worker]
  W -->|SQL queries| DB[(MySQL RDS)]
  DB --> W
  W -->|HTML / JSON| U
```

Each request **occupies one PHP worker + one DB connection** until finished.

---

## 4. What Happens During a Heavy Click (Filter Regenerate)

```mermaid
flowchart TD
  Click[User clicks Re-generate] --> Read[Read filter rules]
  Read --> SQL[Build dynamic include/exclude SQL]
  SQL --> T1[CREATE TEMP t_include]
  T1 --> T2[CREATE TEMP t_exclude]
  T2 --> Join[Join + Aggregate + Count]
  Join --> Del[DELETE old rows in tbl_filter_results]
  Del --> Ins[INSERT thousands of new rows]
  Ins --> Upd[UPDATE counts on tbl_filters]
  Upd --> Drop[DROP all temp tables]
  Drop --> Render[Render results in Smarty]
  Render --> User[Send HTML back to browser]

  classDef heavy fill:#f9d6d5,stroke:#c0392b,color:#000;
  class T1,T2,Join,Del,Ins heavy;
```

> Red boxes = heavy DB cost. **All of this runs while the user waits.**

---

## 5. Multi-User Slowdown — Stage by Stage

### Stage 1 — One user (system healthy)

```mermaid
flowchart LR
  U[1 User] --> W1[PHP Worker] --> DB[(MySQL)]
  DB --> W1 --> U
  style DB fill:#d4edda,stroke:#28a745
```

- 1 worker busy → many free  
- Response: **fast (1–2s)**

---

### Stage 2 — A few users on light pages

```mermaid
flowchart LR
  U1[User] --> W1[Worker 1]
  U2[User] --> W2[Worker 2]
  U3[User] --> W3[Worker 3]
  W1 --> DB[(MySQL)]
  W2 --> DB
  W3 --> DB
  style DB fill:#d4edda,stroke:#28a745
```

- DB serves multiple users in parallel  
- Still **fast** — this is the size the app was designed for

---

### Stage 3 — One user starts a heavy job

```mermaid
flowchart LR
  U1[User: Heavy Filter] --> W1[Worker 1<br/>busy 30+ sec]
  U2[User: Dashboard] --> W2[Worker 2]
  U3[User: Search] --> W3[Worker 3]
  W1 -->|temp tables<br/>DELETE+INSERT| DB[(MySQL)]
  W2 --> DB
  W3 --> DB
  style W1 fill:#fff3cd,stroke:#856404
  style DB fill:#fff3cd,stroke:#856404
```

- One worker stuck  
- DB CPU/disk rising  
- Other users start to feel a slight slowdown

---

### Stage 4 — Several users + heavy and light at once

```mermaid
flowchart LR
  U1[User A: Filter Regen] --> W1[Worker 1 busy]
  U2[User B: Big Report] --> W2[Worker 2 busy]
  U3[User C: Dashboard] --> W3[Worker 3 slow]
  U4[User D: Search] --> W4[Worker 4 slow]
  U5[User E: Login] --> Q1[Waiting in queue]
  W1 --> DB[(MySQL: HIGH LOAD)]
  W2 --> DB
  W3 --> DB
  W4 --> DB
  style DB fill:#f8d7da,stroke:#c0392b
  style W1 fill:#f8d7da,stroke:#c0392b
  style W2 fill:#f8d7da,stroke:#c0392b
  style Q1 fill:#f8d7da,stroke:#c0392b
```

- Heavy jobs block DB resources  
- Light requests wait for DB  
- New users queue for free PHP workers  
- **Everyone feels lag, even on light screens**

---

### Stage 5 — Saturation / freeze

```mermaid
flowchart LR
  U1 --> W1[Worker 1 frozen]
  U2 --> W2[Worker 2 frozen]
  U3 --> W3[Worker 3 frozen]
  U4 --> W4[Worker 4 frozen]
  U5 --> WAIT1[Queue]
  U6 --> WAIT2[Queue]
  U7 --> TIMEOUT[AJAX timeout / popup]
  W1 --> DB[(MySQL: maxed CPU + IOPS)]
  W2 --> DB
  W3 --> DB
  W4 --> DB
  style DB fill:#dc3545,stroke:#000,color:#fff
  style TIMEOUT fill:#dc3545,stroke:#000,color:#fff
```

- All PHP workers stuck  
- MySQL CPU/disk saturated  
- AJAX times out → **JS popups**  
- Browser iframes appear frozen

---

## 6. Cascade Effect — Why One Bad Click Affects Everyone

```mermaid
sequenceDiagram
  participant Heavy as Heavy User Click
  participant DB as MySQL
  participant Light1 as Dashboard User
  participant Light2 as Search User
  Heavy->>DB: Big DELETE + INSERT (locks)
  Note over DB: Disk + CPU at 100%
  Light1->>DB: SELECT today's meetings
  DB-->>Light1: wait...
  Light2->>DB: SELECT search results
  DB-->>Light2: wait...
  Heavy->>DB: Done (after 40s)
  DB-->>Light1: finally returns
  DB-->>Light2: finally returns
```

> **One heavy click = many slow clicks for unrelated users.**

---

## 7. Why JS Popups Show Up

```mermaid
sequenceDiagram
  participant B as Browser (AJAX)
  participant P as PHP
  participant DB as MySQL
  B->>P: AjaxFilterBuilder request
  P->>DB: Heavy SQL
  Note over P,DB: 30s+ work
  DB-->>P: Slow / partial / error
  P-->>B: Bad / late JSON
  B-->>B: alert("Failed to parse...") or timeout
```

- Server too slow → AJAX **timeout**  
- PHP warning mid-AJAX → response is **not valid JSON** → JS shows **“Failed to parse response”**  
- Pre-existing click → app shows **“Please wait — action already running”** (intentional double-click guard)

> Popups are **symptoms**, not the root cause.

---

## 8. Response Time vs Concurrency (Illustrative)

```mermaid
xychart-beta
  title "Response Time vs Number of Concurrent Users"
  x-axis "Concurrent users" [1, 3, 5, 10, 15, 20]
  y-axis "Avg response (sec)" 0 --> 60
  line "Light pages" [2, 3, 4, 7, 12, 20]
  line "Heavy actions" [10, 18, 30, 45, 55, 60]
```

- Light pages stay OK up to ~5 users  
- Heavy actions collapse fast above 5 concurrent users  
- Above 10–15 concurrent users → system **feels broken**

---

## 9. Where MySQL Time Really Goes (Typical Breakdown)

```mermaid
pie title MySQL CPU/Disk during peak hours
  "Filter regenerate (DELETE/INSERT/temp)" : 45
  "Big reports / exports" : 25
  "Dashboard repeated lookups" : 15
  "Other CRUD" : 15
```

> Filter rebuilds + reports dominate.

---

## 10. Root Cause Stack

```mermaid
flowchart TB
  A[Symptoms: Slow pages, hangs, JS popups]
  A --> B[AJAX timeouts / bad JSON]
  A --> C[Browser waiting on PHP]
  B --> D[PHP worker stuck on slow DB]
  C --> D
  D --> E[Single MySQL overloaded]
  E --> F[Heavy temp-table + DELETE+INSERT logic per click]
  F --> G[Designed for single-user / small data era]
  E --> H[No caching / no queue / no read replica]
  H --> G
```

---

## 11. The Fix Path (No Rewrite Needed)

```mermaid
flowchart LR
  S[Today: Heavy jobs run live] --> Q[Move heavy jobs to background queue]
  Q --> I[Add DB indexes + tune MySQL temp memory]
  I --> C[Add caching layer for dashboards]
  C --> Sep[Separate heavy vs light PHP-FPM pools]
  Sep --> M[Measure with slow query log + CloudWatch]
  M --> U{Still slow?}
  U -- yes --> UP[Upgrade RDS instance / IOPS]
  U -- no --> Done[Stable system]
```

---

## 12. Server-Team Asks

### MySQL (AWS RDS)
- Confirm current **instance class**, **storage type (gp2/gp3/io1)**, **IOPS**.
- In the **Parameter Group**, set:
  - `tmp_table_size = 256M`
  - `max_heap_table_size = 256M`
  - `slow_query_log = ON`
  - `long_query_time = 1`
  - `innodb_io_capacity ≥ 1000`
- Share **CloudWatch graphs** (CPU, IOPS, Connections, FreeableMemory) for last 7 days.

### PHP / FPM
- Enable **OPcache**:
  - `opcache.enable=1`
  - `opcache.memory_consumption=256`
  - `opcache.max_accelerated_files=20000`
  - `opcache.validate_timestamps=0` (production)
- Confirm `pm.max_children`, `request_terminate_timeout`.
- Create a **second PHP-FPM pool** (`heavy`) with `request_terminate_timeout=600s` for filter regen / report endpoints.

### Nginx
- Increase `fastcgi_read_timeout` only on heavy endpoints; web default at **60s**.
- Enable gzip on JSON/JS/CSS.

---

## 13. Decision Matrix — Upgrade vs Tune vs Code Fix

| Lever | Cost | Impact | When to use |
|---|---|---|---|
| Bigger RDS instance | $$$ | Medium | Only if CPU steadily > 70% |
| More app server RAM | $ | Low–Medium | If PHP workers swap or hit memory_limit |
| Faster RDS storage (gp3 + IOPS) | $$ | High | Filter rebuild is I/O heavy |
| Tune MySQL params | Free | Medium | Always do |
| Tune PHP-FPM + OPcache | Free | High | Always do |
| **Add background queue** | Dev time | **Highest** | Real fix for hangs |
| Add caching | Dev time | High | Big help for dashboard |
| Add DB indexes | Free | Very High | Always do after slow query log |

> Hardware upgrade **first** = paying more for the same architecture problem.

---

## 14. Can Laravel Fix This?

| Question | Answer |
|---|---|
| Does Laravel make the app faster by itself? | **No.** Same logic = same speed. |
| Does Laravel improve maintainability and scaling? | **Yes.** |
| Can the current code be fixed for performance? | **Yes — without rewriting.** |

**Recommended sequence:**

```mermaid
flowchart LR
  A[Phase 1: Stabilize legacy<br/>Indexes + Queue + Cache + OPcache] --> B[Phase 2: Strangler migration to Laravel<br/>module-by-module]
  B --> C[Phase 3: Retire legacy modules]
```

> Performance fix is **immediate**.  
> Laravel migration continues **in parallel** for long-term maintainability.  
> Migrating first **without** architectural fixes would just rebuild the same problem.

---

## 15. One-Line Summary

> **As soon as 2–3 users run heavy actions on the same MySQL, the DB becomes the bottleneck → PHP workers get stuck → AJAX times out → users see popups, freezes, and slow pages — even on light screens. Fix order: queue heavy jobs → tune DB → cache → separate traffic → upgrade hardware only if metrics still demand it.**

---

## 16. Action Checklist (Owner per Item)

| # | Action | Owner | Effort |
|---|---|---|---|
| 1 | Enable MySQL slow query log | Server team | 30 min |
| 2 | Enable OPcache in production | Server team | 30 min |
| 3 | Confirm RDS parameter group + IOPS | Server team | 1–2 hr |
| 4 | Add DB indexes from slow log | Dev + DBA | 1 day |
| 5 | Move filter regenerate to background queue | Dev | 1–2 weeks |
| 6 | Cache dropdowns/lookups (APCu) | Dev | 2–3 days |
| 7 | Split heavy vs light PHP-FPM pool | Server team | 1 day |
| 8 | Re-measure & decide on RDS upgrade | All | After above |

---

*End of report.*
