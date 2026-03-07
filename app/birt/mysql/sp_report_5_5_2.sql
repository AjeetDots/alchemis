DROP PROCEDURE IF EXISTS alchemis.sp_report_5_5_2;

delimiter |

create procedure alchemis.sp_report_5_5_2 (var_in_client_id int(11))

begin

DECLARE var_start_year_month varchar(6);
DECLARE start_date datetime;

-- get start date of campaign
select start_year_month INTO var_start_year_month
from tbl_campaigns
where id = var_in_client_id;

SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');

CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));

insert into t
select count(com.id), tc.id, tc.value 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;

CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));

insert into t1
select count(m.id), tc.id, tc.value 
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and m.created_at >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;

CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));

insert into t2
select sum(t.item_count)
from t;

select t.tc_value as sector, 
t1.item_count as meeting_count, 
t.item_count/t2.item_count as activity_percentage, 
t1.item_count/t.item_count as conversion_percentage
from t2, t
left join t1 on t.tc_id = t1.tc_id
order by activity_percentage desc, meeting_count desc;

DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;

end |

delimiter ;