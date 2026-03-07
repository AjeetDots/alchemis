/*== tbl_meetings ==*/

ALTER TABLE `tbl_meetings` ADD COLUMN `attended_date` datetime default NULL AFTER `reminder_date`;
ALTER TABLE `tbl_meetings` ADD COLUMN `modified_at` datetime default NULL AFTER `created_by`;
ALTER TABLE `tbl_meetings` ADD COLUMN `modified_by` int(11) default NULL AFTER `modified_at`;

ALTER TABLE `tbl_meetings` ADD INDEX `ix_tbl_meetings_attended_date` (`attended_date`);
ALTER TABLE `tbl_meetings` ADD INDEX `ix_tbl_meetings_created_at` (`created_at`);
ALTER TABLE `tbl_meetings` ADD INDEX `ix_tbl_meetings_modified_at` (`modified_at`);
ALTER TABLE `tbl_meetings` ADD INDEX `ix_tbl_meetings_modified_by` (`modified_by`);

UPDATE `tbl_meetings` SET `attended_date` = `date` WHERE status_id >= 24;

create temporary table t 
select id, max(shadow_timestamp) as modified_at 
from tbl_meetings_shadow group by id;

UPDATE `tbl_meetings` m JOIN t on t.id = m.id 
SET m.`modified_at` = t.`modified_at`;

drop temporary table t;

create temporary table t
select id, max(shadow_id) as max_shadow_id 
from tbl_meetings_shadow 
group by id;

create temporary table t1
select ms.id, shadow_updated_by 
from tbl_meetings_shadow ms
join t on max_shadow_id = ms.shadow_id;

UPDATE `tbl_meetings` m JOIN t1 on t1.id = m.id 
SET m.`modified_by` = t1.`shadow_updated_by`;

drop temporary table t;
drop temporary table t1;

/*== tbl_meetings_shadow ==*/

ALTER TABLE `tbl_meetings_shadow` ADD COLUMN `attended_date` datetime default NULL AFTER `reminder_date`;
ALTER TABLE `tbl_meetings_shadow` ADD COLUMN `modified_at` datetime default NULL AFTER `created_by`;
ALTER TABLE `tbl_meetings_shadow` ADD COLUMN `modified_by` int(11) default NULL AFTER `modified_at`;

UPDATE `tbl_meetings_shadow` SET `attended_date` = `date` WHERE status_id >= 24;
UPDATE tbl_meetings_shadow SET modified_at = shadow_timestamp;
UPDATE tbl_meetings_shadow SET modified_by = shadow_updated_by;

/*== tbl_filter_results ==*/

ALTER TABLE `tbl_filter_results` ADD COLUMN `meeting_id` int(11) default NULL AFTER `post_initiative_id`;
ALTER TABLE `tbl_filter_results` ADD INDEX `ix_tbl_filters_results_meeting_id` (`meeting_id`);

/*== tbl_filters ==*/

ALTER TABLE `tbl_filters` ADD COLUMN `is_report_source` tinyint(1) default '0' AFTER `campaign_id`;
ALTER TABLE `tbl_filters` ADD COLUMN `report_parameter_description` text NULL AFTER `is_report_source`;

/*== tbl_lkp_event_type ==*/

insert into tbl_lkp_event_types (id, name) values (6, 'Client Management');

/*== tbl_events ==*/

ALTER TABLE `tbl_events` ADD COLUMN `day_part` decimal(3,2) NULL AFTER `client_id`;

/* == tbl_nbm_campaign_targets ==*/

ALTER TABLE `tbl_campaign_nbm_targets` CHANGE project_management_days project_management_days decimal(3,2) NOT NULL default 0;

/* == vw_calendar_meetings ==*/

DROP VIEW vw_calendar_meetings;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_calendar_meetings` AS 
select 
`m`.`id` AS `id`,`m`.`post_initiative_id` AS `post_initiative_id`,`m`.`communication_id` AS `communication_id`,
`m`.`is_current` AS `is_current`,`m`.`status_id` AS `status_id`,`m`.`type_id` AS `type_id`,`m`.`date` AS `date`,
`m`.`reminder_date` AS `reminder_date`,`m`.`notes` AS `notes`,`m`.`created_at` AS `created_at`,
`m`.`created_by` AS `created_by`,`m`.`modified_by` AS `modified_by`,`m`.`modified_at` AS `modified_at`,
`m`.`location_id` AS `location_id`,`m`.`nbm_predicted_rating` AS `nbm_predicted_rating`,
`m`.`feedback_rating` AS `feedback_rating`,`m`.`feedback_decision_maker` AS `feedback_decision_maker`,
`m`.`feedback_agency_user` AS `feedback_agency_user`,`m`.`feedback_budget_available` AS `feedback_budget_available`,
`m`.`feedback_receptive` AS `feedback_receptive`,`m`.`feedback_targeting` AS `feedback_targeting`,
`m`.`feedback_meeting_length` AS `feedback_meeting_length`,`m`.`feedback_comments` AS `feedback_comments`,
`m`.`feedback_next_steps` AS `feedback_next_steps`,
`cli`.`id` AS `client_id`,`cli`.`name` AS `client`,`c`.`id` AS `company_id`,`c`.`name` AS `company`,
`pi`.`post_id` AS `post_id`,`pi`.`initiative_id` AS `initiative_id` 
from 
((((((`tbl_meetings` `m` join `tbl_post_initiatives` `pi` on((`m`.`post_initiative_id` = `pi`.`id`))) 
join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) 
join `tbl_companies` `c` on((`p`.`company_id` = `c`.`id`))) 
join `tbl_initiatives` `i` on((`pi`.`initiative_id` = `i`.`id`))) 
join `tbl_campaigns` `cam` on((`i`.`campaign_id` = `cam`.`id`))) 
join `tbl_clients` `cli` on((`cam`.`client_id` = `cli`.`id`)));
