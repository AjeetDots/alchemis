DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Period`;

delimiter |

create procedure sp_report_8_Summary_Figures_For_Period (var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

DECLARE meets_set INT;
DECLARE meets_in_diary INT;
DECLARE strong_callbacks INT;
DECLARE meets_attended INT;
DECLARE meets_awaiting_feedback INT;
DECLARE meets_tbr INT;
DECLARE meets_cancelled INT;
DECLARE tmp INT;


--Strong Callbacks: pipeline

-- initialise the strong_callbacks variable - otherwise it will be set to null. Any number plus null is therefore always shown as null
select 0 into strong_callbacks;

select count(pi.id) INTO tmp 
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and status_id in (4,5,6);

select strong_callbacks + tmp INTO strong_callbacks;

--ALL STATUSES (except “Very receptive” OR “Hot” OR “Meet Set” OR “Do Not Call” OR “Not Worthwhile Prospect”/Company) 
--with a recall reason of “Pitch/Brief” AND have a next communication date in the future

-- create temp table to hold max communication_id for all client initiative records
-- NOTE: when creating this table we only look for communications where the communication date is less than (or equal to)
-- the end date of the report period. This is because the report may be run for periods in the past so we cannot just use the very 
-- latest communication
drop temporary table if exists t_comm_max;

create temporary table t_comm_max (
post_initiative_id int(11),
comm_id int(11),
key `post_initiative_id`(`post_initiative_id`),
key `comm_id`(`comm_id`)
);

insert into t_comm_max
select pi.id as post_initiative_id, max(comm.id)
from tbl_communications comm
join tbl_post_initiatives pi on comm.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and comm.communication_date <= var_in_date_end
and pi.status_id not in (4,5,6,12,8,9,10)
group by pi.id;

/*
 * debug
 */
/*
select t.post_initiative_id, next_communication_date, next_communication_date_reason_id 
from tbl_communications comm 
join t_comm_max t on t.comm_id = comm.id
order by t.post_initiative_id; 

--next_communication_date, next_communication_date_reason_id;
*/

-- NOTE: need to split this into two parts as only want to query the 'last' communication for reason_id and next_comm_date.
-- If we added next_communication_date_reason_id and next_communication_date params to the previous query we would get the wrong
-- result
drop temporary table if exists t_strong_callbacks;

create temporary table t_strong_callbacks (
post_initiative_id int(11),
key `post_initiative_id`(`post_initiative_id`)
);

insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > var_in_date_end;

-- get the record count 
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;

select strong_callbacks + tmp INTO strong_callbacks;

-- ALL STATUSES (except Very receptive or Hot or Meet Set or Do Not Call or Not Worthwhile Prospect/Company) 
-- AND recall reason of “Pitch/Brief” AND have a next communication date before the period first_date up to 2 months
delete from t_strong_callbacks;

insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);

-- get the record count 
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;

select strong_callbacks + tmp INTO strong_callbacks;

-- MEETS SET

select count(m.id) 
INTO meets_set
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end;

-- MEETS ATTENDED

select count(m.id) 
INTO meets_attended
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m.status_id >= 24;

-- MEETS AWAITING FEEDBACK

select count(m.id)
INTO meets_awaiting_feedback
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where
c.client_id = var_in_client_id
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m.status_id in (12,13,18,19);

-- MEETS IN DIARY

select count(m.id) INTO meets_in_diary
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
WHERE 
m.date > var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);

-- MEETS TBR (meetings which currently have a status of tbr, where the meeting date is within or beyond the period, regardless of when the meeting was set)

select count(m.id)
INTO meets_tbr
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date > var_in_date_start
and m.status_id in (14,15,16,17);

-- MEETS CANCELLED

select count(m.id)
INTO meets_cancelled
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start
and m.date <= var_in_date_end
and m.status_id in (20,21,22,23);

drop temporary table if exists t;

create temporary table t (
meets_set_to_date int(11), 
meets_attended_to_date int(11),
meets_potential_attended int(11), 
meets_in_diary int(11),
meets_lapsed_tbr int(11), 
meets_lapsed_cancelled int(11),
strong_callbacks_generated int(11)
);

insert into t
select 
meets_set,
meets_attended,
meets_awaiting_feedback,
meets_in_diary as meets_in_diary,
meets_tbr,
meets_cancelled,
strong_callbacks;

/*
from 
tbl_data_statistics_daily ds
join tbl_campaigns cam on ds.campaign_id = cam.id
WHERE date >= var_in_date_start
AND date <= var_in_date_end
AND cam.client_id = var_in_client_id;
*/

select * from t;

drop temporary table if exists t;
drop temporary table if exists t_comm_max;
drop temporary table if exists t_strong_callbacks;

end |

delimiter ;