DROP PROCEDURE IF EXISTS `sp_report_8_Key_To_Terms_All`;

delimiter |

create procedure sp_report_8_Key_To_Terms_All (var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin
	
drop table if exists t;

create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));

insert into t

select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
group by com.post_initiative_id;

update t set effective_count = 1 where effective_count > 1;

drop table if exists t1;

create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));

insert into t1
select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
from tbl_communications com
join t on t.max_com_id = com.id
group by com.status_id;
select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
from tbl_lkp_communication_status cs
left join t1 on t1.status_id = cs.id
order by cs.report_sort_order;

drop temporary table t;
drop temporary table t1;

end |

delimiter ;