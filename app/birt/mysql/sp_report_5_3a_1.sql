DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3a_1;

delimiter |

create procedure alchemis.sp_report_5_3a_1(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

--(To attend) - to attend in period (where meeting date is in period and status is meet set, meet rearranged or meet attended)

select CAST(concat('To attend: ', DATE_FORMAT(m.date, '%b'), ' ', YEAR(m.date)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.date) AS `year_month`, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date >= var_in_date_start
AND m.date <= var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id IN (12, 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32)

UNION

-- (Live) - to attend beyond period (where meeting date is beyond period and status is meet set or meet rearranged)
select CAST('Live' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date > var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id IN (12, 13, 18, 19)

UNION

-- (Lapsed) - lapsed (where the meeting date is in period or beyond and status is meet tbr or meet cancelled)
select CAST('Lapsed' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date >= var_in_date_start
AND c.client_id = var_in_client_id
AND m.status_id IN (14, 15, 16, 17, 20, 21, 22, 23)

ORDER BY category DESC, `year_month`, date;

end |

delimiter ;