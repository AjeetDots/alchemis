DROP PROCEDURE IF EXISTS alchemis.sp_report_7_rearranged_meetings;

delimiter |

create procedure alchemis.sp_report_7_rearranged_meetings(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

drop table if exists t1;

create temporary table t1
select ms.id
from tbl_meetings_shadow ms
join tbl_post_initiatives pi on pi.id = ms.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where ms.created_at < var_in_date_start
and ms.shadow_timestamp >= var_in_date_start
and ms.shadow_timestamp <= var_in_date_end
AND c.client_id = var_in_client_id
AND ms.status_id IN (18, 19)
GROUP BY ms.id;


--(Set in Period) - meetings set in period (where meeting set date is in period)
SELECT m.id, CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, 
lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from t1
join tbl_meetings m on t1.id = m.id
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
ORDER BY category DESC, `year_month`, date;

drop table if exists t1;

end |

delimiter ;