DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3a_3_chart;

delimiter |

CREATE PROCEDURE alchemis.sp_report_5_3a_3_chart(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

BEGIN

DECLARE end_year_month varchar(6);

SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);

CREATE TEMPORARY TABLE t (
category varchar(255), 
name varchar(255), 
value int(11));

-- campaign meeting_set target to date
INSERT INTO t 
SELECT 'Set', 'Target', SUM(meetings_set)
FROM tbl_campaign_targets
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;

-- campaign meeting_attended target to meets_attended_target_to_date
INSERT INTO t 
SELECT 'Attended', 'Target', SUM(meetings_attended) 
FROM tbl_campaign_targets 
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;

-- meetings set to date
INSERT INTO t 
SELECT 'Set', 'Actual', SUM(meeting_set_count) 
FROM tbl_data_statistics
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;

-- meetings attended to date
INSERT INTO t 
SELECT 'Attended', 'Actual', SUM(meeting_category_attended_count) 
FROM tbl_data_statistics
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;

SELECT * FROM t;

DROP TEMPORARY TABLE t;

END |

delimiter ;