DROP PROCEDURE IF EXISTS alchemis.sp_report_5_notes_detail;

DELIMITER | 

CREATE PROCEDURE alchemis.sp_report_5_notes_detail (var_in_date datetime, var_in_post_initiative_id int(11), var_in_effectives_filter int(11))

BEGIN

	CASE var_in_effectives_filter WHEN 1 THEN
	
		-- Effectives
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		LEFT JOIN tbl_communications AS comm ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND comm.is_effective = 1 
		AND pin.for_client = 1 
		AND comm.communication_date <= var_in_date 
		ORDER by pin.created_at DESC;

	WHEN 2 THEN
	
		-- Non-Effectives
	
		CREATE TEMPORARY table t1 
		SELECT pin.id, pin.post_initiative_id, pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND pin.for_client = 1 
		AND pin.created_at <= var_in_date;
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_communications AS comm 
		LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		LEFT JOIN t1 ON comm.note_id = t1.id 
		WHERE comm.post_initiative_id = var_in_post_initiative_id 
		AND comm.communication_date <= var_in_date 
		AND comm.is_effective = 0 
		ORDER by comm.communication_date DESC;

		DROP TEMPORARY table t1;
	
	
	WHEN 3 THEN
	
		-- Effectives and Non-Effectives
		
		CREATE TEMPORARY table t1 
		SELECT pin.id, pin.post_initiative_id, pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND pin.for_client = 1 
		AND pin.created_at <= var_in_date;
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_communications AS comm 
		LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		LEFT JOIN t1 ON comm.note_id = t1.id 
		WHERE comm.post_initiative_id = var_in_post_initiative_id 
		AND comm.communication_date <= var_in_date 
		ORDER by comm.communication_date DESC;

		DROP TEMPORARY table t1;

	END CASE;

END |

DELIMITER ;