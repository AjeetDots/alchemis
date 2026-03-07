DROP PROCEDURE IF EXISTS `sp_report_8_Meetings_Reconciliation`;

delimiter |

create procedure sp_report_8_Meetings_Reconciliation (var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

drop table if exists  t_all_meets;
create temporary table t_all_meets(
meeting_id int(11),
key `meeting_id`(`meeting_id`)
);

insert into t_all_meets 
select m.id 
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end;


select count(*) from t_all_meets;

drop table if exists  t_temp_meets;
create temporary table t_temp_meets(
meeting_id int(11),
key `meeting_id`(`meeting_id`)
);



-- Get meetings in period which have been attended
-- MEETS ATTENDED

insert into t_temp_meets 
select m.id
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id >= 24;

-- Get meetings where the meeting date is overdue and the meeting status is still 'meeting set'
-- MEETS AWAITING FEEDBACK

insert into t_temp_meets 
select m.id
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where
i.campaign_id = 1
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id in (12,13,18,19);

-- Get meetings which are due beyond the end of the period
-- MEETS IN DIARY

insert into t_temp_meets 
select m.id
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
WHERE 
m.date > var_in_date_end
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);

-- Get meetings in period which are tbr
-- MEETS TBR

insert into t_temp_meets 
select m.id
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where
i.campaign_id = 1
and m. date > var_in_date_start
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id in (14,15,16,17);


-- Get meetings in period which have been cancelled
-- MEETS CANCELLED

insert into t_temp_meets 
select m.id
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start
and m.date <= var_in_date_end
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id in (20,21,22,23);

select count(*) from t_temp_meets;

drop table if exists  t_meets_not_in_all_set;
create temporary table t_meets_not_in_all_set(
meeting_id int(11),
key `meeting_id`(`meeting_id`)
);

insert into t_meets_not_in_all_set 
select tm. meeting_id
from t_temp_meets tm left join t_all_meets am on tm. meeting_id = am. meeting_id
where am. meeting_id is null
group by tm.meeting_id;

select m.* from tbl_meetings m join t_meets_not_in_all_set t on t.meeting_id = m.id
order by t.meeting_id;

drop table if exists  t_meets_set_not_in_any_status;
create temporary table t_meets_set_not_in_any_status(
meeting_id int(11),
key `meeting_id`(`meeting_id`)
);

insert into t_meets_set_not_in_any_status
select am. meeting_id
from t_all_meets am left join t_temp_meets tm on tm. meeting_id = am. meeting_id
where tm. meeting_id is null
group by am.meeting_id;

select m.* from tbl_meetings m join t_meets_set_not_in_any_status t on t.meeting_id =  m.id
order by t.meeting_id;

end |

delimiter ;