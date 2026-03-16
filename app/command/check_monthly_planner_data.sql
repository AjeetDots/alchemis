-- ============================================================
-- Monthly Planner data check
-- Replace @user_id (e.g. 4585) and @year_month (e.g. 202603) with your values
-- ============================================================

SET @user_id = 4585;
SET @year_month = 202603;   -- e.g. 202603 = March 2026

-- 1) Does tbl_data_statistics have any rows for this user and month?
--    (This drives Call Summary: Calls, Effectives, Conversion)
SELECT
    ds.user_id,
    ds.`year_month`,
    SUM(ds.call_count) AS call_count,
    SUM(ds.call_effective_count) AS call_effective_count,
    SUM(ds.meeting_set_count) AS meeting_set_count
FROM tbl_data_statistics AS ds
WHERE ds.user_id = @user_id
  AND ds.`year_month` = @year_month
GROUP BY ds.user_id, ds.`year_month`;
-- If this returns no rows → Call Summary will show zeros.

-- 2) What year_months exist in tbl_data_statistics for this user?
SELECT ds.`year_month`, COUNT(*) AS row_count,
       SUM(ds.call_count) AS calls, SUM(ds.call_effective_count) AS effectives
FROM tbl_data_statistics AS ds
WHERE ds.user_id = @user_id
GROUP BY ds.`year_month`
ORDER BY ds.`year_month` DESC
LIMIT 24;
-- Use this to see which months have data (e.g. try one of these in the planner).

-- 3) Does this user have campaign NBM targets for this month?
--    (Required for the main planner table rows)
SELECT cnt.user_id, cnt.`year_month`, cnt.campaign_id, c.name AS campaign_name,
       cnt.effectives, cnt.meetings_set
FROM tbl_campaign_nbm_targets AS cnt
JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id
WHERE cnt.user_id = @user_id
  AND cnt.`year_month` = @year_month
ORDER BY c.name;
-- If this returns no rows → main table may be empty or only show zeros.

-- 4) Campaign NBM link (deactivated_date filter used by planner)
SELECT cn.user_id, cn.campaign_id, cn.deactivated_date,
       CONCAT(SUBSTR(@year_month, 1, 4), '-', SUBSTR(@year_month, 5, 2), '-01') AS month_start
FROM tbl_campaign_nbms AS cn
WHERE cn.user_id = @user_id;
-- Planner only includes rows where deactivated_date >= month_start OR deactivated_date = '0000-00-00'

-- 5) Combined: stats + targets for this user/month (same logic as planner totals)
SELECT
    IFNULL(SUM(ds.call_count), 0) AS call_count,
    IFNULL(SUM(ds.call_effective_count), 0) AS effectives,
    IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set
FROM tbl_campaign_nbm_targets AS cnt
JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = cnt.campaign_id AND cn.user_id = cnt.user_id
LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id AND cnt.`year_month` = ds.`year_month`
WHERE cnt.user_id = @user_id
  AND cnt.`year_month` = @year_month
  AND (cn.deactivated_date >= CONCAT(SUBSTR(@year_month, 1, 4), '-', SUBSTR(@year_month, 5, 2), '-01') OR cn.deactivated_date = '0000-00-00');
-- This mirrors findTotalStatisticsByUserIdAndYearMonth; if call_count/effectives are 0 here, planner will show 0.
