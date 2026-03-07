DROP PROCEDURE IF EXISTS `sp_report_8_period_results_calls`;

delimiter |

CREATE PROCEDURE `sp_report_8_period_results_calls`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin

DROP TABLE IF EXISTS `t_meetings`;
CREATE TEMPORARY TABLE `t_meetings` ( 
`meeting_id` int(11),
INDEX `ix_meeting_id` (meeting_id));

insert into t_meetings
select max(id)
from tbl_meetings
where status_id >= 12
group by post_initiative_id;

DROP TABLE IF EXISTS `t_meetings_1`;
CREATE TEMPORARY TABLE `t_meetings_1` ( 
`post_initiative_id` int(11), 
`meeting_id` int(11),
`meeting_date` datetime, 
INDEX `ix_post_initiative_id` (post_initiative_id),
INDEX `ix_meeting_id` (meeting_id));

insert into t_meetings_1
select post_initiative_id, id, date
from tbl_meetings
join t_meetings on tbl_meetings.id = t_meetings.meeting_id;

--where is_current = 1
-- added following line to also show meetings where the status is cancelled
--or status_id in (20,21,22,23);


DROP TABLE IF EXISTS `t_post_initiatives`;
CREATE TEMPORARY TABLE `t_post_initiatives` ( 
`post_initiative_id` int(11), 
INDEX `ix_post_initiative_id` (post_initiative_id));

insert into t_post_initiatives
select pi.id
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
where 
com.type_id = 1
and com.is_effective = 1
and com.communication_date >= var_in_date_start
and com.communication_date <= var_in_date_end
and cam.client_id = var_in_client_id
group by pi.id;

select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
from tbl_post_initiatives pi
join t_post_initiatives t on t.post_initiative_id = pi.id
join vw_posts p on p.id = pi.post_id
join tbl_companies c on p.company_id = c.id
join vw_contacts con on con.post_id = p.id
join tbl_lkp_communication_status cs on cs.id = pi.status_id
left join t_meetings_1 m on m.post_initiative_id = pi.id
join tbl_clients cl on cl.id = pi.next_action_by
order by cs.report_sort_order, c.name;

drop table if exists `t_meetings`;
drop table if exists `t_meetings_1`;
drop table if exists `t_post_initiatives`;

end |

delimiter ;

