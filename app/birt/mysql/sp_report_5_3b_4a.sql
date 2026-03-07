DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3b_4a;

delimiter |

create procedure alchemis.sp_report_5_3b_4a(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

-- Feedback -- meeting status is unknown - ie the meeting date has passed but status is still showing as meeting set
SELECT 'Feedback' AS category, 
EXTRACT(YEAR_MONTH FROM m.date) AS `year_month`, 
lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where c.client_id = var_in_client_id
and m.date <= var_in_date_end
and m.status_id in (12, 13, 18, 19)
ORDER BY category DESC, `year_month`, date;

end |

delimiter ;
