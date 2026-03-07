DROP PROCEDURE IF EXISTS alchemis.sp_report_5_detail_data;

DELIMITER | 

CREATE PROCEDURE alchemis.sp_report_5_detail_data (	var_in_start datetime, 
													var_in_end datetime, 
													var_in_client_id int(11), 
													var_in_project_ref text, 
													var_in_effectives_filter int(11))

BEGIN

	--
	-- Create temporary table
	--
	
	CASE var_in_effectives_filter WHEN 1 THEN

		-- Effectives

		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 1 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 2 THEN
	
		-- Non-Effectives
	
		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 0 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 3 THEN
	
		-- Effectives and Non-Effectives
	
		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	END CASE;

	--
	-- Main query
	--

	CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN

		CASE var_in_effectives_filter WHEN 1 THEN
	
			-- Effectives
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		WHEN 2 THEN
		
			-- Non-Effectives
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		WHEN 3 THEN
		
			-- Effectives and Non-Effectives
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		END CASE;

	ELSE

		CASE var_in_effectives_filter WHEN 1 THEN
	
			-- Effectives
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

		WHEN 2 THEN
		
			-- Non-Effectives
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

		WHEN 3 THEN
		
			-- Effectives and Non-Effectives
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

		END CASE;

	END CASE;


	--
	-- Drop the temporary table(s)
	--
	
	DROP TEMPORARY table t1;

END |

DELIMITER ;