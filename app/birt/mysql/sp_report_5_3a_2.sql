DROP PROCEDURE IF EXISTS alchemis.sp_report_5_3a_2;

delimiter |

create procedure alchemis.sp_report_5_3a_2(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))

begin

DECLARE meet_set INT;
DECLARE to_attend INT;
DECLARE live INT;
DECLARE lapsed INT;
DECLARE call_backs INT;

DECLARE start_year_month varchar(6);
DECLARE end_year_month varchar(6);

SET start_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_start);
SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);

-- count of meet (where meeting was set in period)
select sum(meeting_set_count) INTO meet_set
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` >= start_year_month
and `year_month` <= end_year_month;

-- (to attend count) count of to attend in period (where meeting date is in period and status is meet set, meet rearranged or meet attended)
select count(m.id) into to_attend
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date >= var_in_date_start
and m.date <=var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12 , 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32);

-- (live count) count of attend beyond period (where meeting date is beyond period and status is meet set or meet rearranged)
select count(m.id) into live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12 , 13, 18, 19);

-- (lapsed count) - lapsed (where the meeting date is in period or beyond and status is meet tbr or meet cancelled)
select count(m.id) into lapsed
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date >= var_in_date_start
and c.client_id = var_in_client_id
AND m.status_id in (14,15,16,17,20,21,22,23);

-- Strong call backs
select count(pi.id) into call_backs
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
AND pi.status_id in (5, 6);


create temporary table t (meet_set int(11),  to_attend int(11), live int(11), lapsed int(11), call_backs int(11));

insert into t (meet_set, to_attend, live, lapsed, call_backs) values (meet_set, to_attend, live, lapsed, call_backs);

select * from t;

drop temporary table t;

end |

delimiter ;