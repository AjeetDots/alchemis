DROP PROCEDURE IF EXISTS alchemis.sp_report_5_summary_data;

DELIMITER | 

CREATE PROCEDURE alchemis.sp_report_5_summary_data (	var_in_start datetime, 
														var_in_end datetime, 
														var_in_client_id int(11),
														var_in_project_ref text)

BEGIN

CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN

	-- Find Effectives
	CREATE TEMPORARY TABLE t1_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id 
	INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	WHERE cam.client_id = var_in_client_id 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
	
	
	-- Find Non-Effectives
	CREATE TEMPORARY TABLE t2_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS non_effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id 
	LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	WHERE comm.is_effective = 0 
	AND cam.client_id = var_in_client_id 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;

ELSE

	-- Find Effectives
	CREATE TEMPORARY TABLE t1_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id 
	INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id 
	LEFT JOIN tbl_tags t on pit.tag_id = t.id 
	WHERE cam.client_id = var_in_client_id 
	AND t.value = var_in_project_ref 
	AND t.category_id = 3 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
			
	-- Find Non-Effectives
	CREATE TEMPORARY TABLE t2_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS non_effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id 
	LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id 
	LEFT JOIN tbl_tags t on pit.tag_id = t.id 
	WHERE comm.is_effective = 0 
	AND cam.client_id = var_in_client_id 
	AND t.value = var_in_project_ref 
	AND t.category_id = 3 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;

END CASE;


-- Join
SELECT cs.id AS status_id, cs.description, cs.full_description, 
IFNULL(t1.effectives, 0) AS effectives, 
IFNULL(t2.non_effectives, 0) AS non_effectives 
FROM tbl_lkp_communication_status AS cs 
LEFT JOIN t1_rpt5 AS t1 ON cs.id = t1.status_id 
LEFT JOIN t2_rpt5 AS t2 ON cs.id = t2.status_id 
ORDER BY cs.sort_order DESC;


DROP TEMPORARY table t1_rpt5;
DROP TEMPORARY table t2_rpt5;


END |

DELIMITER ;