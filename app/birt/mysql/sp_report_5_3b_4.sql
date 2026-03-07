DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3b_4;

delimiter |

create procedure alchemis.sp_report_5_3b_4(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

--(Set in Period) - meetings set in period (where meeting set date is in period)
SELECT CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, 
lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.created_at >= var_in_date_start
AND m.created_at <= var_in_date_end
AND c.client_id = var_in_client_id
--AND m.status_id IN (12, 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32)

ORDER BY category DESC, `year_month`, date;

end |

delimiter ;