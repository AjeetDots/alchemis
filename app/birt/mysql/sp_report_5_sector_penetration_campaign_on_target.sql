DROP PROCEDURE IF EXISTS alchemis.sp_report_5_sector_penetration_campaign_on_target;

delimiter |

create procedure alchemis.sp_report_5_sector_penetration_campaign_on_target (var_in_client_id int(11))

begin

DECLARE var_start_year_month varchar(6);
DECLARE start_date datetime;

-- get start date of campaign
select start_year_month INTO var_start_year_month
from tbl_campaigns
where id = var_in_client_id;

SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');

-- temp table to get all companies which have had effectives
CREATE TEMPORARY TABLE `t_companies` ( `company_id` int(11), PRIMARY KEY (`company_id`));

insert into t_companies
select p.company_id 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
where com.is_effective = 1
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
group by p.company_id;


-- temp table to get all companies which have had effectives and which are in the target sectors
CREATE TEMPORARY TABLE `t_target_companies` ( `company_id` int(11), PRIMARY KEY (`company_id`));

insert into t_target_companies
select p.company_id 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_campaign_sectors cs on cs.tiered_characteristic_id = tc.id
where com.is_effective = 1
and tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
and cs.campaign_id = var_in_client_id
group by p.company_id;

-- temp table to get all companies which have had effectives and which are NOT in the target sectors
CREATE TEMPORARY TABLE `t_non_target_companies` ( `company_id` int(11), PRIMARY KEY (`company_id`));
insert into t_non_target_companies
select t_companies.company_id
from t_companies c
left join t_target_companies tc on tc.company_id = c.company_id
where t_target_companies is null;

-- temp table to get all effective communications count by sector for companies which are in the target sectors
CREATE TEMPORARY TABLE `t_target_sector_com_count` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));

insert into t_target_sector_com_count
select count(com.id), tc.id, tc.value 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join t_target_companies c on p.company_id = c.company_id 
join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_campaign_sectors cs on cs.tiered_characteristic_id = tc.id
where com.is_effective = 1
and tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
and cs.campaign_id = var_in_client_id
group by tc.id, tc.value;

-- temp table to get all effective communications count by sector for companies which are NOT in the target sectors
CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));

insert into t
select count(com.id), tc.id, tc.value 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join t_non_target_companies c on p.company_id = c.company_id 
join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_campaign_sectors cs on cs.tiered_characteristic_id = tc.id
where com.is_effective = 1
and tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
and cs.campaign_id = var_in_client_id
group by tc.id, tc.value;


-- temp table to get all meetings count by sector for companies which are in the target sectors
CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));

insert into t1
select count(m.id), tc.id, tc.value 
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join t_target_companies c on p.company_id = c.company_id 
join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
join tbl_campaign_sectors cs on cs.tiered_characteristic_id = tc.id
where tc.category_id = 1 
and tc.parent_id = 0 
and m.created_at >= start_date 
and cam.client_id = var_in_client_id
and cs.campaign_id = var_in_client_id
group by tc.id, tc.value;

CREATE TEMPORARY TABLE `t2` ( `effectives_total` int(11), PRIMARY KEY (`effectives_total`));

insert into t2
select sum(t.item_count)
from t;

CREATE TEMPORARY TABLE `t3` ( `meetings_total` int(11), PRIMARY KEY (`meetings_total`));

insert into t3
select sum(t1.item_count)
from t1;

select t.tc_value as sector, t2.effectives_total, t3.meetings_total, t.item_count as effectives_count,  
t1.item_count as meeting_count, 
round((t.item_count/t2.effectives_total)*100) as activity_percentage, 
round((t1.item_count/t3.meetings_total)*100) as meetings_percentage,
round((t1.item_count/t3.meetings_total)*100)/round((t.item_count/t2.effectives_total)*100) as relative_success_ratio,
round((t1.item_count/t.item_count)*100) as conversion_percentage
from t2, t3, t
left join t1 on t.tc_id = t1.tc_id
order by activity_percentage desc;

DROP TABLE IF EXISTS `t_target_companies`;
DROP TABLE IF EXISTS `t_target_sector_com_count`;
DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;
DROP TABLE IF EXISTS `t3`;

end |

delimiter ;