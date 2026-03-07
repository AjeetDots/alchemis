DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3a_3;

delimiter |

create procedure alchemis.sp_report_5_3a_3(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

DECLARE end_year_month varchar(6);
DECLARE meets_set_target_to_date INT;
DECLARE meets_attended_target_to_date INT;
DECLARE meets_set_to_date INT;
DECLARE meets_attended_to_date INT;
DECLARE meets_potential_attended INT;
DECLARE meets_live INT;
DECLARE meets_lapsed_tbr INT;
DECLARE meets_lapsed_cancelled INT;

SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);

-- campaign meeting_set target to date
select sum(meetings_set) INTO meets_set_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- campaign meeting_attended target to meets_attended_target_to_date
select sum(meetings_attended) INTO meets_attended_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- meetings set to date
select sum(meeting_set_count) INTO meets_set_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- meetings attended to date
select sum(meeting_category_attended_count) INTO meets_attended_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- meeting status is unknown - ie the meeting date has passed but status is still showing as meeting set
select sum(meeting_category_unknown_count) INTO meets_potential_attended
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- live (meetings due to be attended
select count(m.id) INTO meets_live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);

-- lapsed (tbr)
select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

-- lapsed (cancelled)
select sum(meeting_category_cancelled_count) INTO meets_lapsed_cancelled
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;

create temporary table t (
meets_set_target_to_date int(11), 
meets_attended_target_to_date int(11), 
meets_set_to_date int(11), 
meets_attended_to_date int(11),
meets_potential_attended int(11), 
meets_live int(11),
meets_lapsed_tbr int(11), 
meets_lapsed_cancelled int(11)
);

insert into t (
meets_set_target_to_date, 
meets_attended_target_to_date, 
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled)
values (
meets_set_target_to_date, 
meets_attended_target_to_date,
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled);

select * from t;

drop temporary table t;

end |

delimiter ;