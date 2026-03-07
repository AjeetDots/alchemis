================================================================================
CHANGES SUMMARY – DB Connect Flow, Live/Dev Setup, Deprecation Fixes
Author: ds | Share with BA team
================================================================================

--- 1. WHY THESE CHANGES ---

• DB connection: Make it easy to switch between LOCAL (Docker) and LIVE (AWS RDS) 
  without editing code – use .env or server env (ALCHEMIS_ENV).
• Live readiness: Default env = 'aws' so live works without setting env; 
  .env used for local (ALCHEMIS_ENV=development).
• PHP 7+ compatibility: Fix deprecated “same name as class” constructors so 
  Ajax JSON response is not corrupted and no deprecation notices in output.
• Docker: Add .dockerignore for faster builds; map DB port to 3307 to avoid 
  conflict with host MySQL.

--------------------------------------------------------------------------------
--- 2. DB CONNECTION FLOW (LIVE / DEV) – WHAT WAS vs WHAT WE DID ---
--------------------------------------------------------------------------------

2.1  index.php
     Path: index.php
     What was: No .env support; default env was 'development'.
     What we did: 
       - Load ALCHEMIS_ENV from .env when not set by server/Docker.
       - Default env = 'aws' so live uses aws block without any config.
       - Comment: "DB/env controlled by .env (ALCHEMIS_ENV). Rest unchanged."

2.2  data/app_options.xml
     Path: data/app_options.xml
     What was: development/aws blocks; url and DB could be mixed per env.
     What we did: 
       - development = local Docker (host=db, database=legacy_db, user=legacy_user, password=secret, url=/).
       - aws = live RDS (host=staging RDS, database=alchemis, url=/alchemis/).
       - Added XML comment: "Updated by ds: development = local Docker; aws = live RDS."

2.3  db_test.php
     Path: db_test.php
     What was: Hardcoded PDO to Docker (host=db, dbname=legacy_db, user/pass).
     What we did: 
       - Read DB from app_options.xml using ALCHEMIS_ENV (same as app).
       - Optional .env load for ALCHEMIS_ENV.
       - Default env = 'aws'.
       - Show "Live AWS DB" or "Local DB" based on host (amazonaws = live).

2.4  .env / .env.example
     Path: .env.example (new), .env (optional, in .gitignore)
     What was: App did not use .env for config.
     What we did: 
       - index.php and db_test.php read ALCHEMIS_ENV from .env if present and not set by server.
       - .env.example added: ALCHEMIS_ENV=development (with short comment).
     Live: Do not deploy .env with ALCHEMIS_ENV=development, or set ALCHEMIS_ENV=aws.

2.5  docker-compose.yml
     Path: docker-compose.yml
     What was: DB port 3306; optional env for app.
     What we did: 
       - DB host port 3307:3306 to avoid conflict with local MySQL.
       - app environment: ALCHEMIS_ENV=aws (or development – change as needed).
       - Comment: "Updated by ds: app env; db port 3307."

2.6  .dockerignore
     Path: .dockerignore (new)
     What was: No .dockerignore; large build context.
     What we did: 
       - Added to speed up Docker build: exclude .git, vendor, node_modules, test, docs, archive, etc.
       - Comment: "Added by ds: reduce Docker build context."

--------------------------------------------------------------------------------
--- 3. DEPRECATION FIXES (PHP 7+ – “same name as class” constructors) ---
--------------------------------------------------------------------------------
Reason: These caused "Failed to parse response text" because PHP printed 
deprecation into the Ajax JSON response.

3.1  app/ajax/domain/Ajax_JSON.class.php
     Path: app/ajax/domain/Ajax_JSON.class.php
     What was: function Services_JSON($use = 0); function Services_JSON_Error(...) (two classes).
     What we did: 
       - Services_JSON: function __construct($use = 0).
       - Services_JSON_Error (both variants): function __construct(...).
       - Old lines kept as comments; "Updated by ds."

3.2  include/jpgraph-2.3/jpgraph_ttf.inc.php
     Path: include/jpgraph-2.3/jpgraph_ttf.inc.php
     What was: function TTF(); function File($family, $style).
     What we did: 
       - function __construct(); function getFontFilePath($family, $style).
       - Old lines commented; "Updated by ds."

3.3  include/jpgraph-2.3/gd_image.inc.php
     Path: include/jpgraph-2.3/gd_image.inc.php
     What was: $this->ttf->File(...).
     What we did: $this->ttf->getFontFilePath(...). Old line commented; "Updated by ds."

3.4  include/jpgraph-2.2/jpgraph_ttf.inc.php
     Path: include/jpgraph-2.2/jpgraph_ttf.inc.php
     What was: function TTF(); function File(...).
     What we did: function __construct(); function getFontFilePath(...). Old lines commented; "Updated by ds."

3.5  include/jpgraph-2.2/jpgraph.php
     Path: include/jpgraph-2.2/jpgraph.php
     What was: $this->ttf->File(...).
     What we did: $this->ttf->getFontFilePath(...). Old line commented; "Updated by ds."

3.6  app/controller/Controller.php
     Path: app/controller/Controller.php
     What was: No suppression of deprecation during Ajax.
     What we did: 
       - In handleAjaxRequest(): suppress E_DEPRECATED, E_STRICT, E_NOTICE for the request.
       - Restore error_reporting before every exit/echo/die so response is pure JSON.
       - Comment: "Updated by ds: suppress deprecation so Ajax response is pure JSON."

--------------------------------------------------------------------------------
--- 4. QUICK REFERENCE – FILES TOUCHED ---
--------------------------------------------------------------------------------

DB / env / live flow:
  index.php
  data/app_options.xml
  db_test.php
  .env.example
  docker-compose.yml
  .dockerignore

Deprecation (PHP 7+):
  app/ajax/domain/Ajax_JSON.class.php
  app/controller/Controller.php
  include/jpgraph-2.3/jpgraph_ttf.inc.php
  include/jpgraph-2.3/gd_image.inc.php
  include/jpgraph-2.2/jpgraph_ttf.inc.php
  include/jpgraph-2.2/jpgraph.php

--------------------------------------------------------------------------------
--- 5. LIVE DEPLOY – WHAT TO DO ---
--------------------------------------------------------------------------------

• Do not set ALCHEMIS_ENV=development on live (or leave unset so default 'aws' is used).
• Do not deploy .env with ALCHEMIS_ENV=development.
• App must be served at /alchemis/ (or change <aws><url> in app_options.xml to your path).
• After deploy, delete data/applicationRegistry_aws.txt (and applicationRegistry_development.txt if present).
• Ensure log_directory in <aws> exists and is writable, or update it in app_options.xml.

--------------------------------------------------------------------------------
--- 6. LOCAL / DOCKER ---
--------------------------------------------------------------------------------

• Use .env with ALCHEMIS_ENV=development for local DB (Docker MySQL).
• Or set ALCHEMIS_ENV=development in docker-compose.yml for the app service.
• db_test.php shows "Local DB" or "Live AWS DB" based on current config.

================================================================================
End of summary
================================================================================
