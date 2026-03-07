SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_post_notes`
--

CREATE TABLE `tbl_post_notes` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_notes_post_id` (`post_id`),
  KEY `ix_tbl_post_notes_created_at` (`created_at`),
  KEY `ix_tbl_post_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_post_notes_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `ix_tbl_post_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_notes_seq`
--

CREATE TABLE `tbl_post_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_notes_shadow`
--

CREATE TABLE `tbl_post_notes_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;	

--
-- Table structure for table `tbl_campaign_nbms'
--

DROP TABLE IF EXISTS tbl_campaign_nbms;
CREATE TABLE `tbl_campaign_nbms` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `user_alias` varchar(255) NOT NULL,
  `is_lead_nbm` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_nbms_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbms_seq`
--

DROP TABLE IF EXISTS tbl_campaign_nbms_seq;
CREATE TABLE `tbl_campaign_nbms_seq` (
`sequence` int(11) NOT NULL auto_increment,
PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbms_shadow`
--

DROP TABLE IF EXISTS tbl_campaign_nbms_shadow;
CREATE TABLE `tbl_campaign_nbms_shadow` (
`shadow_id` int(11) NOT NULL auto_increment,
`shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
`shadow_updated_by` int(11) NOT NULL,
`shadow_type` char(1) default NULL,
`id` int(11) NOT NULL default '0',
`campaign_id` int(11) NOT NULL default '0',
`user_id` int(11) NOT NULL default '0',
`user_alias` varchar(255) NOT NULL,
`is_lead_nbm` tinyint(1) NOT NULL default '0',
`is_active` tinyint(1) NOT NULL default '0',
PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Populate `tbl_campaign_nbms`
--

INSERT INTO tbl_campaign_nbms (id, campaign_id, user_id, user_alias)
SELECT id, client_id, user_id, alias
FROM tbl_user_client_aliases;

--
-- Make the oldest user_id the lead nbm for each campaign
--

CREATE TEMPORARY TABLE t 
SELECT min(user_id) AS user_id, campaign_id
FROM tbl_campaign_nbms
GROUP BY campaign_id;

UPDATE tbl_campaign_nbms cn
JOIN t ON cn.campaign_id = t.campaign_id AND cn.user_id = t.user_id
SET cn.is_lead_nbm = 1;

UPDATE tbl_campaign_nbms SET is_active = 1;

DROP TEMPORARY TABLE t;


--
-- Table structure for table `tbl_campaign_disciplines'
--

CREATE TABLE `tbl_campaign_disciplines` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_disciplines` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_tbl_campaign_disciplines_ibfk2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_disciplines_seq`
--

CREATE TABLE `tbl_campaign_disciplines_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_disciplines_shadow`
--

CREATE TABLE `tbl_campaign_disciplines_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
 `campaign_id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Campaign target info
--
UPDATE tbl_campaigns SET start_year_month = '200701', minimum_duration = 12;

--
-- Table structure for table `tbl_lkp_event_types`
--

CREATE TABLE `tbl_lkp_event_types` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `name` varchar(100) NOT NULL UNIQUE DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE tbl_lkp_event_types DISABLE KEYS */;
LOCK TABLES tbl_lkp_event_types WRITE;
#--------------------------------------|----|---------------|
# -- Columns --                        | id | name          |
#--------------------------------------|----|---------------|
INSERT INTO tbl_lkp_event_types VALUES (  1 , 'Calling Day' );
INSERT INTO tbl_lkp_event_types VALUES (  2 , 'Holiday'     );
INSERT INTO tbl_lkp_event_types VALUES (  3 , 'Incentive'   );
INSERT INTO tbl_lkp_event_types VALUES (  4 , 'Internal'    );
INSERT INTO tbl_lkp_event_types VALUES (  5 , 'Sick'        );
#--------------------------------------|----|---------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_lkp_event_types ENABLE KEYS */;


--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `date` date,
  `reminder_date` datetime,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_events_user_id` (`user_id`),
  KEY `ix_tbl_events_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_events_ibfk1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_events_ibfk2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_event_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_events_seq`
--

CREATE TABLE `tbl_events_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_events_shadow`
--

CREATE TABLE `tbl_events_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `date` date,
  `reminder_date` datetime,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` varchar(100) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `message` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_messages_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_messages_ibfk1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_messages_seq`
--

CREATE TABLE `tbl_messages_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_users`
--

ALTER TABLE `tbl_rbac_users` MODIFY COLUMN `password` varchar(32) NOT NULL default '';

--
-- Table structure for table `tbl_teams`
--

CREATE TABLE `tbl_teams` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_teams_seq`
--

CREATE TABLE `tbl_teams_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_team_nbms`
--

CREATE TABLE `tbl_team_nbms` (
  `id` int(11) NOT NULL auto_increment,
  `team_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_team_nbms_team_id` (`team_id`),
  KEY `ix_tbl_team_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_team_nbms_ibfk1` FOREIGN KEY (`team_id`) REFERENCES `tbl_teams` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `ix_tbl_team_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_team_nbms_seq`
--

CREATE TABLE `tbl_team_nbms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




--
-- Drop `tbl_user_client_aliases` tables that are no longer needed
--
DROP TABLE IF EXISTS tbl_user_client_aliases;
DROP TABLE IF EXISTS tbl_user_client_aliases_seq;
DROP TABLE IF EXISTS tbl_user_client_aliases_shadow;

--
-- Drop `tbl_user_campaign_aliases` tables that are no longer needed
--
DROP TABLE IF EXISTS tbl_user_campaign_aliases;
DROP TABLE IF EXISTS tbl_user_campaign_aliases_seq;
DROP TABLE IF EXISTS tbl_user_campaign_aliases_shadow;




--
-- Final view structure for `vw_calendar_information_requests`
--

DROP VIEW IF EXISTS vw_calendar_information_requests;
CREATE VIEW vw_calendar_information_requests AS 
SELECT ir.*, cli.id AS client_id, cli.name AS client, c.id AS company_id, c.name AS company 
FROM tbl_information_requests AS ir 
INNER JOIN tbl_post_initiatives AS pi ON ir.post_initiative_id = pi.id 
INNER JOIN tbl_posts AS p ON pi.post_id = p.id
INNER JOIN tbl_companies AS c ON p.company_id = c.id
INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id;

--
-- Final view structure for view `vw_calendar_meetings`
--

DROP VIEW IF EXISTS vw_calendar_meetings;
CREATE VIEW vw_calendar_meetings AS 
SELECT m.*, cli.id AS client_id, cli.name AS client, c.id AS company_id, c.name AS company, pi.post_id 
FROM tbl_meetings AS m 
INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id
INNER JOIN tbl_posts AS p ON pi.post_id = p.id
INNER JOIN tbl_companies AS c ON p.company_id = c.id
INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id;

--
-- Final view structure for view `vw_client_initiatives`
--

DROP VIEW IF EXISTS vw_client_initiatives;
CREATE VIEW `vw_client_initiatives` AS 
select `c`.`id` AS `client_id`,`c`.`name` AS `client_name`,`cm`.`id` AS `campaign_id`,`i`.`id` AS `initiative_id`,`i`.`name` AS `initiative_name` 
from ((`tbl_clients` `c` join `tbl_campaigns` `cm` on((`c`.`id` = `cm`.`client_id`))) left join `tbl_initiatives` `i` on((`cm`.`id` = `i`.`campaign_id`)));

--
-- Final view structure for `vw_events`
--

DROP VIEW IF EXISTS vw_events;
CREATE VIEW vw_events AS 
SELECT e.*, t.name AS type 
FROM tbl_events AS e 
INNER JOIN tbl_lkp_event_types AS t ON e.type_id = t.id;

--
-- Final view structure for view `vw_post_communication_stats_base`
--

--DROP VIEW IF EXISTS vw_post_communication_stats_base;
--CREATE VIEW `vw_post_communication_stats_base` AS 
--select `p`.`id` AS `post_id`,1 AS `comm_count`,(case `comm`.`effective` when _latin1'effective' then 1 else 0 end) AS `eff_count` 
--from ((`tbl_communications` `comm` join `tbl_post_initiatives` `pi` on((`comm`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`)));

--
-- Table structure for table `tbl_object_tiered_characteristics`
--

CREATE TABLE `tbl_object_tiered_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tiered_characteristic_id`, `company_id`),
  KEY `ix_tbl_object_tiered_characteristics_tiered_characteristic_id` (`tiered_characteristic_id`),
  KEY `ix_tbl_object_tiered_characteristics_tier` (`tier`),
  KEY `ix_tbl_object_tiered_characteristics_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics_seq`
--

CREATE TABLE `tbl_object_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics_shadow`
--

CREATE TABLE `tbl_object_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Copy the data across
--
INSERT INTO tbl_object_tiered_characteristics SELECT id, tiered_characteristic_id, tier, company_id FROM tbl_company_tiered_characteristics;

--
-- Set up the sequence table
--
SELECT MAX(id) FROM tbl_object_tiered_characteristics;
ALTER TABLE tbl_object_tiered_characteristics_seq AUTO_INCREMENT = 15863;
DELETE FROM tbl_object_tiered_characteristics_seq;
INSERT INTO tbl_object_tiered_characteristics_seq VALUES (15863);


DROP TABLE IF EXISTS tbl_company_tiered_characteristics;
DROP TABLE IF EXISTS tbl_company_tiered_characteristics_seq;
DROP TABLE IF EXISTS tbl_company_tiered_characteristics_shadow;

--
-- Table structure for table `tbl_campaign_disciplines'
--
ALTER TABLE `tbl_campaign_disciplines` DROP FOREIGN KEY `ix_tbl_tbl_campaign_disciplines_ibfk2`;
ALTER TABLE `tbl_campaign_disciplines` ADD CONSTRAINT `ix_tbl_campaign_disciplines_ibfk2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE;

CREATE TABLE `tbl_communications_backup` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lead_source_id` int(11),
  `status_id` int(11) NOT NULL default '0',
  `next_action_by` int(11) NOT NULL default '1',
  `old_status` varchar(50) default NULL,
  `communication_date` datetime NOT NULL,
  `direction` enum('out','in') NOT NULL default 'out',
  `effective` enum('effective','non-effective') NOT NULL default 'non-effective',
  `targeting_id` int(11) default NULL,
  `receptiveness_id` int(11) default NULL,
  `decision_maker_type_id` int(11) default NULL,
  `next_communication_date` datetime default NULL,
  `next_communication_date_reason_id` int(11) default NULL,
  `comments` text,
  `note_id` int(11) default NULL,
  `ote` tinyint(1) NOT NULL default '0',
  `type_id` int(11) NOT NULL,
  `is_effective` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_communications_backup (
`id`,
  `post_initiative_id`,
  `user_id`,
  `lead_source_id` ,
  `status_id`,
  `old_status` ,
  `communication_date` ,
  `direction` ,
  `effective` ,
  `targeting_id` ,
  `receptiveness_id` ,
  `decision_maker_type_id` ,
  `next_communication_date` ,
  `next_communication_date_reason_id` ,
  `comments` ,
  `note_id` ,
  `ote` ,
  `type_id` ,
  `is_effective` 
) SELECT 
  `id`,
  `post_initiative_id`,
  `user_id`,
  `lead_source_id` ,
  `status_id`,
  `old_status` ,
  `communication_date` ,
  `direction` ,
  `effective` ,
  `targeting_id` ,
  `receptiveness_id` ,
  `decision_maker_type_id` ,
  `next_communication_date` ,
  `next_communication_date_reason_id` ,
  `comments` ,
  `note_id` ,
  `ote` ,
  `type_id` ,
  `is_effective` 
FROM tbl_communications ORDER BY id;

DROP TABLE IF EXISTS tbl_communications;



--
-- Table structure for table `tbl_lkp_agency_user_types`
--
DROP TABLE IF EXISTS tbl_lkp_agency_user_types;
CREATE TABLE `tbl_lkp_agency_user_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_agency_user_types_seq`
--
DROP TABLE IF EXISTS tbl_lkp_agency_user_types_seq;
CREATE TABLE `tbl_lkp_agency_user_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data for `tbl_lkp_agency_user_types`
--
#------------------------------------------
select 'tbl_lkp_agency_user_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types disable keys */;
lock tables tbl_lkp_agency_user_types write;
#---------------------------------------------|----|---------------------|------------|
# -- Columns --                               | id | description (50)    | sort_order |
#---------------------------------------------|----|---------------------|------------|
insert into tbl_lkp_agency_user_types values (   1, 'Yes',                        0);
insert into tbl_lkp_agency_user_types values (   2, 'No',                         1);
insert into tbl_lkp_agency_user_types values (   3, 'Don\'t know',                2);
insert into tbl_lkp_agency_user_types values (   4, 'Project frequent',           3);
insert into tbl_lkp_agency_user_types values (   5, 'Project infrequent',         4);
insert into tbl_lkp_agency_user_types values (   6, 'Retained frequent',          5);
#---------------------------------------------|----|---------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types enable keys */;

--
-- Data for `tbl_lkp_agency_user_types_seq`
--
#------------------------------------
select 'tbl_lkp_agency_user_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types_seq disable keys */;
lock tables tbl_lkp_agency_user_types_seq write;
update tbl_lkp_agency_user_types_seq set sequence = 6;
alter table tbl_lkp_agency_user_types_seq auto_increment = 7;
unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types_seq enable keys */;
#---------------------------------------------------------------------------------------


--
-- Table structure for table `tbl_lkp_communication_receptiveness`
--

DROP TABLE IF EXISTS tbl_lkp_communication_receptiveness;
CREATE TABLE `tbl_lkp_communication_receptiveness` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_seq`
--

DROP TABLE IF EXISTS tbl_lkp_communication_receptiveness_seq;
CREATE TABLE `tbl_lkp_communication_receptiveness_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_receptiveness_shadow`;


--
-- Data for `tbl_lkp_communication_receptiveness`
--

#------------------------------------------
--select 'tbl_lkp_communication_receptiveness';
#------------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness disable keys */;
lock tables tbl_lkp_communication_receptiveness write;
#------------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                        | id | description (50) | status_score | sort_order |
#------------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_receptiveness values (   1, 'V. receptive',    6,             0          );
insert into tbl_lkp_communication_receptiveness values (   2, 'Receptive',       4,             1          );
insert into tbl_lkp_communication_receptiveness values (   3, 'Tepid',           2,             2          );
insert into tbl_lkp_communication_receptiveness values (   4, 'Not receptive',   0,             3          );
#------------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness enable keys */;

--
-- Data for `tbl_lkp_communication_receptiveness_seq`
--

#------------------------------------
--select 'tbl_lkp_communication_receptiveness_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness_seq disable keys */;
lock tables tbl_lkp_communication_receptiveness_seq write;
update tbl_lkp_communication_receptiveness_seq set sequence = 4;
unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness_seq enable keys */;
#---------------------------------------------------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_lkp_communication_targeting`
--
DROP TABLE IF EXISTS tbl_lkp_communication_targeting;
CREATE TABLE `tbl_lkp_communication_targeting` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_targeting_seq`
--
DROP TABLE IF EXISTS tbl_lkp_communication_targeting_seq;
CREATE TABLE `tbl_lkp_communication_targeting_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_targeting_shadow`
--
DROP TABLE IF EXISTS tbl_lkp_communication_targeting_shadow;

--
-- Data for table `tbl_lkp_communication_targeting`
--
#---------------------------------------
select 'tbl_lkp_communication_targeting';
#---------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting disable keys */;
lock tables tbl_lkp_communication_targeting write;
#--------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                    | id | description (50) | status_score | sort_order |
#--------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_targeting values (   1, 'Perfect',         3,             0          );
insert into tbl_lkp_communication_targeting values (   2, '80% plus',        2,             1          );
insert into tbl_lkp_communication_targeting values (   3, '50% to 80%',      1,             2          );
insert into tbl_lkp_communication_targeting values (   4, 'less than 50%',   0,             3          );
#--------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting enable keys */;

--
-- Data for table `tbl_lkp_communication_targeting_seq`
--
#------------------------------------
select 'tbl_lkp_communication_targeting_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting_seq disable keys */;
lock tables tbl_lkp_communication_targeting_seq write;
update tbl_lkp_communication_targeting_seq set sequence = 4;
unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting_seq enable keys */;
#---------------------------------------------------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_lkp_decision_maker_types`
--
DROP TABLE IF EXISTS tbl_lkp_decision_maker_types;
CREATE TABLE `tbl_lkp_decision_maker_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_decision_maker_types_seq`
--
DROP TABLE IF EXISTS tbl_lkp_decision_maker_types_seq;
CREATE TABLE `tbl_lkp_decision_maker_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_decision_maker_types_shadow`
--
DROP TABLE IF EXISTS tbl_lkp_decision_maker_types_shadow;

--
-- Data for table `tbl_lkp_decision_maker_types`
--
#------------------------------------------
select 'tbl_lkp_decision_maker_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types disable keys */;
lock tables tbl_lkp_decision_maker_types write;
#------------------------------------------------|----|------------------|------------|
# -- Columns --                                  | id | description (50) | sort_order |
#------------------------------------------------|----|------------------|------------|
insert into tbl_lkp_decision_maker_types values (   1, 'Yes',                        0);
insert into tbl_lkp_decision_maker_types values (   2, 'No',                         1);
insert into tbl_lkp_decision_maker_types values (   3, 'Don\'t know',                2);
#------------------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types enable keys */;

--
-- Data for table `tbl_lkp_decision_maker_types_seq`
--
#------------------------------------
select 'tbl_lkp_decision_maker_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types_seq disable keys */;
lock tables tbl_lkp_decision_maker_types_seq write;
update tbl_lkp_decision_maker_types_seq set sequence = 3;
alter table tbl_lkp_decision_maker_types_seq auto_increment = 4;
unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types_seq enable keys */;
#---------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_communications`
--
DROP TABLE IF EXISTS tbl_communications;

CREATE TABLE `tbl_communications` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lead_source_id` int(11),
  `status_id` int(11) NOT NULL default '0',
  `next_action_by` int(11) NOT NULL default '1',
  `old_status` varchar(50) default NULL,
  `communication_date` datetime NOT NULL,
  `direction` enum('out','in') NOT NULL default 'out',
  `effective` enum('effective','non-effective') NOT NULL default 'non-effective',
  `targeting_id` int(11) default NULL,
  `receptiveness_id` int(11) default NULL,
  `decision_maker_type_id` int(11) default NULL,
  `next_communication_date` datetime default NULL,
  `next_communication_date_reason_id` int(11) default NULL,
  `comments` text,
  `note_id` int(11) default NULL,
  `ote` tinyint(1) NOT NULL default '0',
  `type_id` int(11) NOT NULL,
  `is_effective` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_communications_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_communications_next_communication_date` (`next_communication_date`),
  KEY `ix_tbl_communications_lead_source_id` (`lead_source_id`),
  KEY `ix_tbl_communications_status` (`status_id`),
  KEY `ix_tbl_communications_next_action_by` (`next_action_by`),
  KEY `ix_tbl_communications_user_id` (`user_id`),
  KEY `ix_tbl_communications_effective` (`effective`),
  KEY `ix_tbl_communications_type_id` (`type_id`),
  KEY `ix_tbl_communications_communication_date` (`communication_date`),
  KEY `ix_tbl_communications_id_communication_date_post_initiative_id` (`id`,`communication_date`,`post_initiative_id`),
  KEY `ix_tbl_communications_is_effective` (`is_effective`),
  KEY `ix_tbl_communications_targeting_id` (`targeting_id`),
  KEY `ix_tbl_communications_receptiveness_id` (`receptiveness_id`),
  KEY `ix_tbl_communications_decision_maker_type_id` (`decision_maker_type_id`),
  KEY `ix_tbl_communications_note_id` (`note_id`),
  CONSTRAINT `ix_tbl_communications_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_communication_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk3` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk4` FOREIGN KEY (`targeting_id`) REFERENCES `tbl_lkp_communication_targeting` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `ix_tbl_communications_ibfk5` FOREIGN KEY (`receptiveness_id`) REFERENCES `tbl_lkp_communication_receptiveness` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `ix_tbl_communications_ibfk6` FOREIGN KEY (`decision_maker_type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `ix_tbl_communications_ibfk7` FOREIGN KEY (`note_id`) REFERENCES `tbl_post_initiative_notes` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `ix_tbl_communications_ibfk8` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--  CONSTRAINT `ix_tbl_communications_ibfk9` FOREIGN KEY (`next_action_by`) REFERENCES `tbl_clients` (`id`) ON DELETE SET NULL ON UPDATE CASCADE

INSERT INTO tbl_communications (
`id`,
  `post_initiative_id`,
  `user_id`,
  `lead_source_id` ,
  `status_id`,
  `old_status` ,
  `communication_date` ,
  `direction` ,
  `effective` ,
  `targeting_id` ,
  `receptiveness_id` ,
  `decision_maker_type_id` ,
  `next_communication_date` ,
  `next_communication_date_reason_id` ,
  `comments` ,
  `note_id` ,
  `ote` ,
  `type_id` ,
  `is_effective` 
) SELECT 
  `id`,
  `post_initiative_id`,
  `user_id`,
  `lead_source_id` ,
  `status_id`,
  `old_status` ,
  `communication_date` ,
  `direction` ,
  `effective` ,
  `targeting_id` ,
  `receptiveness_id` ,
  `decision_maker_type_id` ,
  `next_communication_date` ,
  `next_communication_date_reason_id` ,
  `comments` ,
  `note_id` ,
  `ote` ,
  `type_id` ,
  `is_effective` 
FROM tbl_communications_backup ORDER BY id;

DROP TABLE IF EXISTS tbl_communications_backup;

--ALTER TABLE `tbl_communications` MODIFY COLUMN `targeting_id` int(11) default NULL;
--ALTER TABLE `tbl_communications` MODIFY COLUMN `receptiveness_id` int(11) default NULL;
--ALTER TABLE `tbl_communications` MODIFY COLUMN `decision_maker_type_id` int(11) default NULL;

--
-- Table structure for table `tbl_post_agency_incumbents`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents;
CREATE TABLE `tbl_post_agency_incumbents` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_incumbents_post_id` (`post_id`),
  KEY `ix_tbl_post_agency_incumbents_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_seq;
CREATE TABLE `tbl_post_agency_incumbents_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_shadow;
CREATE TABLE `tbl_post_agency_incumbents_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_review_dates`
--

DROP TABLE IF EXISTS tbl_post_agency_review_dates;
CREATE TABLE `tbl_post_agency_review_dates` (
  `id` int(11) NOT NULL auto_increment,
  `post_agency_user_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_review_dates_post_agency_user_id` (`post_agency_user_id`),
  CONSTRAINT `ix_tbl_post_agency_review_dates_ibfk1` FOREIGN KEY (`post_agency_user_id`) REFERENCES `tbl_post_agency_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_review_dates_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_review_dates_seq;
CREATE TABLE `tbl_post_agency_review_dates_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_review_dates_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_review_dates_shadow;
CREATE TABLE `tbl_post_agency_review_dates_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_agency_user_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users`
--

DROP TABLE IF EXISTS tbl_post_agency_users;
CREATE TABLE `tbl_post_agency_users` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_users_id` (`post_id`),
  KEY `ix_tbl_post_agency_users_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_agency_users_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_agency_users_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_agency_user_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_users_seq;
CREATE TABLE `tbl_post_agency_users_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_users_shadow;
CREATE TABLE `tbl_post_agency_users_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers`
--

DROP TABLE IF EXISTS tbl_post_decision_makers;
CREATE TABLE `tbl_post_decision_makers` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_decision_makers_post_id` (`post_id`),
  KEY `ix_tbl_post_decision_makers_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_decision_makers_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_seq`
--

DROP TABLE IF EXISTS tbl_post_decision_makers_seq;
CREATE TABLE `tbl_post_decision_makers_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_shadow`
--

DROP TABLE IF EXISTS tbl_post_decision_makers_shadow;
CREATE TABLE `tbl_post_decision_makers_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_targets'
--

ALTER TABLE `tbl_campaign_targets` ADD COLUMN `calls` int(3) NOT NULL default '0' AFTER `year_month`;
ALTER TABLE `tbl_campaign_targets` ADD COLUMN `effectives` int(3) NOT NULL default '0' AFTER `calls`;

UPDATE `tbl_campaign_targets` SET `effectives` = 35, `calls` = 245;

--
-- Table structure for table `tbl_campaign_targets_shadow'
--

ALTER TABLE `tbl_campaign_targets_shadow` ADD COLUMN `calls` int(3) NOT NULL default '0' AFTER `year_month`;
ALTER TABLE `tbl_campaign_targets_shadow` ADD COLUMN `effectives` int(3) NOT NULL default '0' AFTER `calls`;

--
-- Table structure for table `tbl_configuration`
--

CREATE TABLE `tbl_configuration` (
  `property` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`property`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_data_statistics_run'
--

CREATE TABLE `tbl_data_statistics_run` (
  `id` int(11) NOT NULL auto_increment,
  `start` timestamp NOT NULL default '0000-00-00 00:00:00',
  `end` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_data_statistics_run_start` (`start`),
  KEY `ix_tbl_data_statistics_run_end` (`end`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_data_statistics_run_seq`
--

CREATE TABLE `tbl_data_statistics_run_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


drop table if exists tbl_lkp_communication_receptiveness;
drop table if exists tbl_lkp_communication_targeting;
drop table if exists tbl_lkp_decision_maker_types;

alter table tbl_communications change `targeting_id` `targeting_id` int(11) default NULL;
alter table tbl_communications change `receptiveness_id` `receptiveness_id` int(11) default NULL;
alter table tbl_communications change `decision_maker_type_id` `decision_maker_type_id` int(11) default NULL;

--
-- Table structure for table `tbl_lkp_communication_receptiveness`
--

drop table if exists tbl_lkp_communication_receptiveness;
CREATE TABLE `tbl_lkp_communication_receptiveness` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_seq`
--

drop table if exists tbl_lkp_communication_receptiveness_seq;
CREATE TABLE `tbl_lkp_communication_receptiveness_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_shadow`
--

drop table if exists `tbl_lkp_communication_receptiveness_shadow`;

#------------------------------------------
select 'tbl_lkp_communication_receptiveness';
#------------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness disable keys */;
lock tables tbl_lkp_communication_receptiveness write;
#------------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                        | id | description (50) | status_score | sort_order |
#------------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_receptiveness values (   1, 'V. receptive',    6,             0          );
insert into tbl_lkp_communication_receptiveness values (   2, 'Receptive',       4,             1          );
insert into tbl_lkp_communication_receptiveness values (   3, 'Tepid',           2,             2          );
insert into tbl_lkp_communication_receptiveness values (   4, 'Not receptive',   0,             3          );
#------------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness enable keys */;

#------------------------------------
select 'tbl_lkp_communication_receptiveness_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness_seq disable keys */;
lock tables tbl_lkp_communication_receptiveness_seq write;

update tbl_lkp_communication_receptiveness_seq set sequence = 4;

unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness_seq enable keys */;
#---------------------------------------------------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_lkp_communication_targeting`
--

drop table if exists tbl_lkp_communication_targeting;
CREATE TABLE `tbl_lkp_communication_targeting` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_targeting_seq`
--

drop table if exists tbl_lkp_communication_targeting_seq;
CREATE TABLE `tbl_lkp_communication_targeting_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_targeting_shadow`
--

drop table if exists tbl_lkp_communication_targeting_shadow;

#---------------------------------------
select 'tbl_lkp_communication_targeting';
#---------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting disable keys */;
lock tables tbl_lkp_communication_targeting write;
#--------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                    | id | description (50) | status_score | sort_order |
#--------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_targeting values (   1, 'Perfect',         3,             0          );
insert into tbl_lkp_communication_targeting values (   2, '80% plus',        2,             1          );
insert into tbl_lkp_communication_targeting values (   3, '50% to 80%',      1,             2          );
insert into tbl_lkp_communication_targeting values (   4, 'less than 50%',   0,             3          );
#--------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting enable keys */;

#------------------------------------
select 'tbl_lkp_communication_targeting_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting_seq disable keys */;
lock tables tbl_lkp_communication_targeting_seq write;

update tbl_lkp_communication_targeting_seq set sequence = 4;

unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting_seq enable keys */;
#---------------------------------------------------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_lkp_decision_maker_types`
--

drop table if exists tbl_lkp_decision_maker_types;
CREATE TABLE `tbl_lkp_decision_maker_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_decision_maker_types_seq`
--
drop table if exists tbl_lkp_decision_maker_types_seq;
CREATE TABLE `tbl_lkp_decision_maker_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

drop table if exists tbl_lkp_decision_maker_types_shadow;

#------------------------------------------
select 'tbl_lkp_decision_maker_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types disable keys */;
lock tables tbl_lkp_decision_maker_types write;
#------------------------------------------------|----|------------------|------------|
# -- Columns --                                  | id | description (50) | sort_order |
#------------------------------------------------|----|------------------|------------|
insert into tbl_lkp_decision_maker_types values (   1, 'Yes',                        0);
insert into tbl_lkp_decision_maker_types values (   2, 'No',                         1);
insert into tbl_lkp_decision_maker_types values (   3, 'Don\'t know',                2);
#------------------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types enable keys */;

#------------------------------------
select 'tbl_lkp_decision_maker_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types_seq disable keys */;
lock tables tbl_lkp_decision_maker_types_seq write;

update tbl_lkp_decision_maker_types_seq set sequence = 3;
alter table tbl_lkp_decision_maker_types_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types_seq enable keys */;
#---------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_lkp_agency_user_types`
--

drop table if exists tbl_lkp_agency_user_types;
CREATE TABLE `tbl_lkp_agency_user_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_agency_user_types_seq`
--

drop table if exists tbl_lkp_agency_user_types_seq;
CREATE TABLE `tbl_lkp_agency_user_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#------------------------------------------
select 'tbl_lkp_agency_user_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types disable keys */;
lock tables tbl_lkp_agency_user_types write;
#---------------------------------------------|----|---------------------|------------|
# -- Columns --                               | id | description (50)    | sort_order |
#---------------------------------------------|----|---------------------|------------|
insert into tbl_lkp_agency_user_types values (   1, 'Yes',                        0);
insert into tbl_lkp_agency_user_types values (   2, 'No',                         1);
insert into tbl_lkp_agency_user_types values (   3, 'Don\'t know',                2);
insert into tbl_lkp_agency_user_types values (   4, 'Project frequent',           3);
insert into tbl_lkp_agency_user_types values (   5, 'Project infrequent',         4);
insert into tbl_lkp_agency_user_types values (   6, 'Retained frequent',          5);
#---------------------------------------------|----|---------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types enable keys */;

#------------------------------------
select 'tbl_lkp_agency_user_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types_seq disable keys */;
lock tables tbl_lkp_agency_user_types_seq write;

update tbl_lkp_agency_user_types_seq set sequence = 6;
alter table tbl_lkp_agency_user_types_seq auto_increment = 7;

unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types_seq enable keys */;
#---------------------------------------------------------------------------------------

--
-- Table structure for table `tbl_post_decision_makers`
--

DROP TABLE IF EXISTS tbl_post_decision_makers;
CREATE TABLE `tbl_post_decision_makers` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_decision_makers_post_id` (`post_id`),
  KEY `ix_tbl_post_decision_makers_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_decision_makers_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_seq`
--

DROP TABLE IF EXISTS tbl_post_decision_makers_seq;
CREATE TABLE `tbl_post_decision_makers_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_shadow`
--

DROP TABLE IF EXISTS tbl_post_decision_makers_shadow;
CREATE TABLE `tbl_post_decision_makers_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users`
--

DROP TABLE IF EXISTS tbl_post_agency_users;
CREATE TABLE `tbl_post_agency_users` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_users_id` (`post_id`),
  KEY `ix_tbl_post_agency_users_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_agency_users_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_agency_users_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_agency_user_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_users_seq;
CREATE TABLE `tbl_post_agency_users_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_users_shadow;
CREATE TABLE `tbl_post_agency_users_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates`
--

DROP TABLE IF EXISTS tbl_post_discipline_review_dates;
CREATE TABLE `tbl_post_discipline_review_dates` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_discipline_review_dates_post_id` (`post_id`),
  KEY `ix_tbl_post_discipline_review_dates_discipline_id` (`discipline_id`),
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates_seq`
--

DROP TABLE IF EXISTS tbl_post_discipline_review_dates_seq;
CREATE TABLE `tbl_post_discipline_review_dates_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates_shadow`
--

DROP TABLE IF EXISTS tbl_post_discipline_review_dates_shadow;
CREATE TABLE `tbl_post_discipline_review_dates_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents;
CREATE TABLE `tbl_post_agency_incumbents` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_incumbents_post_id` (`post_id`),
  KEY `ix_tbl_post_agency_incumbents_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_seq;
CREATE TABLE `tbl_post_agency_incumbents_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_shadow;
CREATE TABLE `tbl_post_agency_incumbents_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiatives`
--
alter table tbl_post_initiatives add column `next_action_by` int(11) NOT NULL default '1';
alter table tbl_post_initiatives add index `ix_tbl_post_initiatives_next_action_by` (`next_action_by`);
alter table tbl_post_initiatives add CONSTRAINT `ix_tbl_post_initiatives_ibfk7` FOREIGN KEY (`next_action_by`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Table structure for table `tbl_post_initiatives_shadow`
--
alter table tbl_post_initiatives_shadow add column `next_action_by` int(11) NOT NULL default '1';

--
-- Table structure for table `tbl_communications_shadow`
--
alter table tbl_communications_shadow add column `next_action_by` int(11) NOT NULL default '1';

update tbl_clients set name = 'Alchemis' where id = 1;

--
-- Table structure for table `tbl_posts`
--

ALTER TABLE tbl_posts ADD COLUMN `spend` varchar(25) DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `incumbent_change_date` datetime DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `not_available_until` datetime DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `no_access_type_id` int(11) DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `no_access_date` datetime DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `call_barred` tinyint(1) DEFAULT '0';
ALTER TABLE tbl_posts ADD COLUMN `access_rate` int(11) DEFAULT NULL;
ALTER TABLE tbl_posts ADD COLUMN `access_figures` varchar(10) DEFAULT NULL;

--
-- Table structure for table `tbl_lkp_post_no_access_types`
--

DROP TABLE IF EXISTS tbl_lkp_post_no_access_types;
CREATE TABLE `tbl_lkp_post_no_access_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_post_no_access_types_seq`
--

DROP TABLE IF EXISTS tbl_lkp_post_no_access_types_seq;
CREATE TABLE `tbl_lkp_post_no_access_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (1, 'Voicemail', 1);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (2, 'In Meeting', 2);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (3, 'On Phone', 3);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (4, 'Busy', 4);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (5, 'Not at Desk', 5);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (6, 'Blocked by Scretary', 6);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (7, 'Out until', 7);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (8, 'On Holiday', 8);
INSERT INTO tbl_lkp_post_no_access_types (id, description, sort_order) VALUES (9, 'Not Available', 9);
		

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------------------------------------
-- END OF UPGRADE FILE SECTIONS
-- The following section of this file should be run before the Access VBA file
-- ----------------------------------------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

alter table tbl_meetings_shadow modify column `shadow_timestamp` datetime NOT NULL;
alter table tbl_post_decision_makers_shadow modify column `shadow_timestamp` datetime NOT NULL;
alter table tbl_post_agency_users_shadow modify column `shadow_timestamp` datetime NOT NULL;

UPDATE tbl_rbac_users SET name = 'Haroon Khwaja' WHERE id = 15;
UPDATE tbl_rbac_users SET name = 'Glen Menezes' WHERE id = 45;
UPDATE tbl_rbac_users SET name = 'Nicole McMahon' WHERE id = 54;
UPDATE tbl_rbac_users SET name = 'Jaiye Elias' WHERE id = 55;
UPDATE tbl_rbac_users SET name = 'Christian Jensen' WHERE id = 56;
UPDATE tbl_rbac_users SET name = 'Omar Francis' WHERE id = 57;
UPDATE tbl_rbac_users SET name = 'Dan Shearman' WHERE id = 58;

DELETE FROM tbl_rbac_users WHERE id > 1 AND id < 10;

--
-- Table structure for table `tbl_lkp_communication_status`
--
DROP TABLE `tbl_lkp_communication_status`;
CREATE TABLE `tbl_lkp_communication_status` (
  `id` int(11) NOT NULL default '0',
  `lower_value` int(11) NOT NULL default '0',
  `upper_value` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `is_auto_calculate` tinyint(1) NOT NULL default '0',
  `show_auto_calculate_options` tinyint(1) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`description`),
  KEY `ix_tbl_lkp_communication_status_lower_value` (`lower_value`),
  KEY `ix_tbl_lkp_communication_status_upper_value` (`upper_value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_seq`
--
DROP TABLE `tbl_lkp_communication_status_seq`;
CREATE TABLE `tbl_lkp_communication_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_shadow`
--
DROP TABLE `tbl_lkp_communication_status_shadow`;
CREATE TABLE `tbl_lkp_communication_status_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `lower_value` int(11) NOT NULL default '0',
  `upper_value` int(11) NOT NULL default '0',
  `is_auto_calculate` tinyint(1) NOT NULL default '0',
  `show_auto_calculate_options` tinyint(1) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#-----------------------------------
select 'tbl_lkp_communication_status';
#-----------------------------------
/*!40000 alter table tbl_lkp_communication_status disable keys */;
lock tables tbl_lkp_communication_status write;
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
# -- Columns --                                 | id | lower_value | upper_value | description (50)                      | is_auto_calculate | show_auto_calculate_options | sort_order |
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
insert into tbl_lkp_communication_status values (   1, 0,            13,           'Dormant',  		                        1,                  1,                            1          );
insert into tbl_lkp_communication_status values (   2, 14,           21,           'Receptive long term',                	1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   3, 14,           21,           'Receptive medium term',                 1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   4, 22,           25,           'Very receptive medium term',            1,                  1,                            3          );
insert into tbl_lkp_communication_status values (   5, 22,           25,           'Very receptive near term',              1,                  1,                            3          );
insert into tbl_lkp_communication_status values (   6, 26,           63,           'Hot',                                   1,                  1,                            4          );

insert into tbl_lkp_communication_status values (   7, 64  ,         64,           'Fresh lead',                           0,                  1,                            5          );
insert into tbl_lkp_communication_status values (   8, 128,          128,          'Do not call',                          0,                  1,                            6          );
insert into tbl_lkp_communication_status values (   9, 256,          256,          'Not worthwhile prospect',              0,                  1,                            8          );
insert into tbl_lkp_communication_status values (   10, 512,          512,         'Not worthwhile company',              0,                  1,                            9          );
insert into tbl_lkp_communication_status values (   11, 750,          750,         'Referred to new DM',                  0,                  1,                            9          );

insert into tbl_lkp_communication_status values (  12, 1000,         1000,         'Meeting set',                          0,                  0,                            10         );
insert into tbl_lkp_communication_status values (  13, 2000,         2000,         'Follow-up meeting set',                0,                  0,                            11         );

insert into tbl_lkp_communication_status values (  14, 3000,         3000,         'Meeting to be rearranged: client',     0,                  0,                            12         );
insert into tbl_lkp_communication_status values (  15, 4000,         4000,         'Follow-up meeting to be rearranged: client',   0,                  0,                            13         );

insert into tbl_lkp_communication_status values (  16, 4500,         4500,         'Meeting to be rearranged: Alchemis',     0,                  0,                            12         );
insert into tbl_lkp_communication_status values (  17, 5000,         5000,         'Follow-up meeting to be rearranged: Alchemis',   0,                  0,                            13         );

insert into tbl_lkp_communication_status values (  18, 6000,         6000,         'Meeting rearranged',                   0,                  0,                            14         );
insert into tbl_lkp_communication_status values (  19, 7000,         7000,         'Follow-up meeting rearranged',         0,                  0,                            15         );

insert into tbl_lkp_communication_status values (  20, 8000,         8000,         'Meeting cancelled: prospect',          0,                  0,                            16         );
insert into tbl_lkp_communication_status values (  21, 9000,         9000,         'Follow-up meeting cancelled: prospect',0,                  0,                            17         );

insert into tbl_lkp_communication_status values (  22, 10000,        10000,        'Meeting cancelled: client',            0,                  0,                            16         );
insert into tbl_lkp_communication_status values (  23, 11000,        11000,        'Follow-up meeting cancelled: client',  0,                  0,                            17         );

insert into tbl_lkp_communication_status values (  24, 12000,        12000,        'Meeting attended: client',             0,                  0,                            18         );
insert into tbl_lkp_communication_status values (  25, 13000,        13000,        'Follow-up meeting attended: client',   0,                  0,                            19         );

insert into tbl_lkp_communication_status values (  26, 14000,        14000,        'Meeting attended: Alchemis',           0,                  0,                            20         );
insert into tbl_lkp_communication_status values (  27, 15000,        15000,        'Follow-up meeting attended: Alchemis', 0,                  0,                            21         );

insert into tbl_lkp_communication_status values (  28, 16000,        16000,        'Brief received',                       0,                  0,                            22         );
insert into tbl_lkp_communication_status values (  29, 17000,        17000,        'Proposal',                             0,                  0,                            22         );
insert into tbl_lkp_communication_status values (  30, 18000,        18000,        'Win',                                  0,                  1,                            23         );
insert into tbl_lkp_communication_status values (  31, 19000,        19000,        'Gone cold',                            0,                  1,                            24         );
insert into tbl_lkp_communication_status values (  32, 20000,        20000,        'Follow-up meeting to be arranged',     0,                  0,                            25         );
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status enable keys */;


#------------------------------------
select 'tbl_lkp_communication_status_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_seq disable keys */;
lock tables tbl_lkp_communication_status_seq write;

update tbl_lkp_communication_status_seq set sequence = 32;
alter table tbl_lkp_communication_status_seq auto_increment = 32;

unlock tables;
/*!40000 alter table tbl_lkp_communication_status_seq enable keys */;

--
-- Table structure for table `tbl_lkp_communication_status_rules`
--
DROP TABLE `tbl_lkp_communication_status_rules`;
CREATE TABLE `tbl_lkp_communication_status_rules` (
  `id` int(11) NOT NULL default '0',
  `status_id` int(11) NOT NULL default '0',
  `child_status_id` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_communication_status_rules_status_id` (`status_id`),
  KEY `ix_tbl_lkp_communication_status_rules_child_status_id` (`child_status_id`),
  KEY `ix_tbl_lkp_communication_status_rules_status_id_child_status_id` (`status_id`,`child_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_rules_seq`
--
DROP TABLE `tbl_lkp_communication_status_rules_seq`;
CREATE TABLE `tbl_lkp_communication_status_rules_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_rules_shadow`
--
DROP TABLE `tbl_lkp_communication_status_rules_shadow`;
CREATE TABLE `tbl_lkp_communication_status_rules_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `status_id` int(11) NOT NULL default '0',
  `child_status_id` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#-----------------------------------
select 'tbl_lkp_communication_status_rules';
#-----------------------------------
/*!40000 alter table tbl_lkp_communication_status_rules disable keys */;
lock tables tbl_lkp_communication_status_rules write;
#-----------------------------------------------------|------|-------------|-----------------|-------------|
# -- Columns --                                       | id   | status_id   | child_status_id | sort_order  |
#-----------------------------------------------------|------|-------------|-----------------|-------------|
#Fresh lead (7)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (   1,  7,            7,                   1);
insert into tbl_lkp_communication_status_rules values (   2,  7,            8,                   2);
insert into tbl_lkp_communication_status_rules values (   3,  7,            9,                   3);
insert into tbl_lkp_communication_status_rules values (   4,  7,            10,                   4);
insert into tbl_lkp_communication_status_rules values (   5,  7,            12,                   5);

#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (   6,  7,            1,                   6);
insert into tbl_lkp_communication_status_rules values (   7,  7,            2,                   7);
insert into tbl_lkp_communication_status_rules values (   8,  7,            3,                   8);
insert into tbl_lkp_communication_status_rules values (   9,  7,            4,                   9);
insert into tbl_lkp_communication_status_rules values (   10,  7,            5,                   10);
insert into tbl_lkp_communication_status_rules values (   11,  7,            6,                   11);

#Do not call (8)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  12,  8,            8,                   1);
insert into tbl_lkp_communication_status_rules values (  13,  8,            7,                   2); 
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  14,  8,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  15,  8,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  16,  8,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  17,  8,            4,                   9);
insert into tbl_lkp_communication_status_rules values (   18,  8,            5,                   10);
insert into tbl_lkp_communication_status_rules values (   19,  8,            6,                   11);

#Not worthwhile prospect (9)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  20,  9,            9,                   1);
insert into tbl_lkp_communication_status_rules values (  21,  9,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  22,  9,            11,                   3);
insert into tbl_lkp_communication_status_rules values (  23,  9,            12,                  4);
insert into tbl_lkp_communication_status_rules values (  24,  9,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  25,  9,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  26,  9,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  27,  9,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  28,  9,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  29,  9,            4,                   10);
insert into tbl_lkp_communication_status_rules values (   30,  9,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   31,  9,            6,                   12);

#Not worthwhile company (10)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  32,  10,            10,                 1);
insert into tbl_lkp_communication_status_rules values (  33,  10,            9,                  2);
insert into tbl_lkp_communication_status_rules values (  34,  10,            11,                 3);
insert into tbl_lkp_communication_status_rules values (  35,  10,            12,                   4);
insert into tbl_lkp_communication_status_rules values (  36,  10,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  37,  10,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  38,  10,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  39,  10,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  40,  10,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  41,  10,            4,                   10);
insert into tbl_lkp_communication_status_rules values (   42,  10,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   43,  10,            6,                   12);

#Referred to new decision maker(11)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  44,  11,            11,                   1);
insert into tbl_lkp_communication_status_rules values (  45,  11,            9,                  2);
insert into tbl_lkp_communication_status_rules values (  46,  11,            10,                  3);
insert into tbl_lkp_communication_status_rules values (  47,  11,            12,                   4);
insert into tbl_lkp_communication_status_rules values (  48,  11,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  49,  11,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  50,  11,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  51,  11,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  52,  11,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  53,  11,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  54,  11,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   55,  11,            6,                   12);

#Dormant (1)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  56,  1,            1,                   1);
insert into tbl_lkp_communication_status_rules values (  57,  1,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  58,  1,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  59,  1,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  60,  1,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  61,  1,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  62,  1,            12,                  7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  63,  1,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  64,  1,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  65,  1,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  66,  1,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  67,  1,            6,                   12);

#Receptive long term (2)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  68,  2,            2,                   1);
insert into tbl_lkp_communication_status_rules values (  69,  2,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  70,  2,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  71,  2,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  72,  2,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  73,  2,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  74,  2,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  75,  2,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  76,  2,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  77,  2,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  78,  2,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  79,  2,            6,                   12);

#Receptive medium term (3)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  80,  3,            3,                   1);
insert into tbl_lkp_communication_status_rules values (  81,  3,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  82,  3,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  83,  3,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  84,  3,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  85,  3,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  86,  3,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  87,  3,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  88,  3,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  89,  3,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  90,  3,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  91,  3,            6,                   12);

#Very receptive medium term (4)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  92,  4,            4,                   1);
insert into tbl_lkp_communication_status_rules values (  93,  4,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  94,  4,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  95,  4,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  96,  4,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  97,  4,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  98,  4,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  99,  4,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  100,  4,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  101,  4,            3,                   10);
insert into tbl_lkp_communication_status_rules values (   102  4,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   103,  4,            6,                   12);

#Very receptive near term (5)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  104,  5,            5,                   1);
insert into tbl_lkp_communication_status_rules values (  105,  5,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  106,  5,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  107,  5,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  108,  5,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  109,  5,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  110,  5,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  111,  5,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  112,  5,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  113,  5,            3,                   10);
insert into tbl_lkp_communication_status_rules values (  114,  5,            4,                   11);
insert into tbl_lkp_communication_status_rules values (  115,  5,            6,                   12);

#Hot (6)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  116,  6,            6,                   1);
insert into tbl_lkp_communication_status_rules values (  117,  6,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  118,  6,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  119,  6,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  120,  6,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  121,  6,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  122,  6,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  123,  6,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  124,  6,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  125,  6,            3,                   10);
insert into tbl_lkp_communication_status_rules values (  126,  6,            4,                   11);
insert into tbl_lkp_communication_status_rules values (  127,  6,            5,                   12);

#Meeting set (12)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  128,  12,           12,                  1);
insert into tbl_lkp_communication_status_rules values (  129,  12,           14,                  2);
insert into tbl_lkp_communication_status_rules values (  130,  12,           16,                  3);
insert into tbl_lkp_communication_status_rules values (  131,  12,           18,                  4);
insert into tbl_lkp_communication_status_rules values (  132,  12,           20,                  5);
insert into tbl_lkp_communication_status_rules values (  133,  12,           22,                  6);
insert into tbl_lkp_communication_status_rules values (  134,  12,           24,                  7);
insert into tbl_lkp_communication_status_rules values (  135,  12,           26,                  8);

#Follow-up meeting set (13)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  137,  13,           13,                  1);
insert into tbl_lkp_communication_status_rules values (  138,  13,           15,                  2);
insert into tbl_lkp_communication_status_rules values (  139,  13,           17,                  3);
insert into tbl_lkp_communication_status_rules values (  140,  13,           19,                  4);
insert into tbl_lkp_communication_status_rules values (  141,  13,           21,                  5);
insert into tbl_lkp_communication_status_rules values (  142,  13,           23,                  6);
insert into tbl_lkp_communication_status_rules values (  143,  13,           25,                  7);
insert into tbl_lkp_communication_status_rules values (  144,  13,           27,                  8);

#Meeting to be rearranged: client (14)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  145,  14,           14,                  1);
insert into tbl_lkp_communication_status_rules values (  146,  14,           18,                  2);
insert into tbl_lkp_communication_status_rules values (  147,  14,           20,                  3);
insert into tbl_lkp_communication_status_rules values (  148,  14,           22,                  4);

#Follow-up meeting to be rearranged: client (15)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  149,  15,           15,                  1);
insert into tbl_lkp_communication_status_rules values (  150,  15,           19,                  2);
insert into tbl_lkp_communication_status_rules values (  151,  15,           21,                  3);
insert into tbl_lkp_communication_status_rules values (  152,  15,           23,                  4);

#Meeting to be rearranged: Alchemis (16)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  153,  16,           16,                  1);
insert into tbl_lkp_communication_status_rules values (  154,  16,           18,                  2);
insert into tbl_lkp_communication_status_rules values (  155,  16,           20,                  3);
insert into tbl_lkp_communication_status_rules values (  156,  16,           22,                  4);

#Follow-up meeting to be rearranged: Alchemis (17)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  157,  17,           17,                  1);
insert into tbl_lkp_communication_status_rules values (  158,  17,           19,                  2);
insert into tbl_lkp_communication_status_rules values (  159,  17,           21,                  3);
insert into tbl_lkp_communication_status_rules values (  160,  17,           23,                  4);

#Meeting rearranged (18)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  161,  18,           18,                  1);
insert into tbl_lkp_communication_status_rules values ( 162,  18,           20,                  2);
insert into tbl_lkp_communication_status_rules values ( 163,  18,           22,                  3);
insert into tbl_lkp_communication_status_rules values ( 164,  18,           24,                  4);
insert into tbl_lkp_communication_status_rules values ( 165,  18,           26,                  5);

#Follow-up meeting rearranged (19)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 166,  19,           19,                  1);
insert into tbl_lkp_communication_status_rules values ( 167,  19,           21,                  2);
insert into tbl_lkp_communication_status_rules values ( 168,  19,           23,                  3);
insert into tbl_lkp_communication_status_rules values ( 169,  19,           25,                  4);
insert into tbl_lkp_communication_status_rules values ( 170,  19,           27,                  5);

#Meeting cancelled: prospect (20)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 171,  20,           20,                  1);
insert into tbl_lkp_communication_status_rules values ( 172,  20,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 173,  20,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 174,  20,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 175,  20,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 176,  20,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 177,  20,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 178,  20,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 179,  20,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 180,  20,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 181,  20,            6,              11);

#Follow-up meeting cancelled: prospect (21)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 182,  21,           21,                  1);
insert into tbl_lkp_communication_status_rules values ( 183,  21,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 184,  21,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 185,  21,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 186,  21,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 187,  21,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 188,  21,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 189,  21,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 190,  21,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 191,  21,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 192,  21,            6,               11);

#Meeting cancelled: client (22)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 193,  22,           22,                  1);
insert into tbl_lkp_communication_status_rules values ( 194,  22,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 195,  22,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 196,  22,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 197,  22,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 198,  22,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 199,  22,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 200,  22,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 201,  22,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 202,  22,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 203,  22,            6,               11);

#Follow-up meeting cancelled: client (23)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 204,  23,           21,                  1);
insert into tbl_lkp_communication_status_rules values ( 205,  23,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 206,  23,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 207,  23,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 208,  23,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 209,  23,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 210,  23,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 211,  23,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 212,  23,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 213,  23,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 214,  23,            6,              11);

#Meeting attended: client (24)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 215,  24,           24,                  1);
insert into tbl_lkp_communication_status_rules values ( 216,  24,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 217,  24,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 218,  24,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 219,  24,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 220,  24,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 221,  24,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 223,  24,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 224,  24,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 225,  24,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 226,  24,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 227,  24,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 228,  24,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 229,  24,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 230,  24,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 231,  24,           32,                 16);

#Follow-up meeting attended: client (25)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 232,  25,           25,                  1);
insert into tbl_lkp_communication_status_rules values ( 233,  25,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 234,  25,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 235,  25,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 236,  25,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 237,  25,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 238,  25,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 239,  25,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 240,  25,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 241,  25,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 242,  25,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 243,  25,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 244,  25,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 245,  25,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 246,  25,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 247,  25,           32,                 16);


#Meeting attended: Alchemis (26)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 248,  26,           26,                  1);
insert into tbl_lkp_communication_status_rules values ( 249,  26,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 250,  26,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 251,  26,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 252,  26,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 253,  26,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 254,  26,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 255,  26,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 256,  26,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 257,  26,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 258,  26,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 259,  26,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 260,  26,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 261,  26,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 262,  26,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 263,  26,           32,                 16);

#Follow-up meeting attended: Alchemis (27)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 264,  27,           27,                  1);
insert into tbl_lkp_communication_status_rules values ( 265,  27,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 266,  27,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 267,  27,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 268,  27,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 269,  27,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 270,  27,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 271,  27,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 272,  27,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 273,  27,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 274,  27,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 275,  27,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 276,  27,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 277,  27,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 278,  27,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 279,  27,           32,                 16);

#Brief received (28)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 280,  28,           28,                 1);
insert into tbl_lkp_communication_status_rules values ( 281,  28,           13,                 2);
insert into tbl_lkp_communication_status_rules values ( 282,  28,           29,                 3);
insert into tbl_lkp_communication_status_rules values ( 283,  28,           30,                 4);
insert into tbl_lkp_communication_status_rules values ( 284,  28,           31,                 5);
insert into tbl_lkp_communication_status_rules values ( 285,  28,           32,                 6);

#Proposal (29)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 286,  29,           29,              1);
insert into tbl_lkp_communication_status_rules values ( 287,  29,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 288,  29,           11,                   3);
insert into tbl_lkp_communication_status_rules values ( 289,  29,           13,                   4);
insert into tbl_lkp_communication_status_rules values ( 290,  29,           30,                   5);
insert into tbl_lkp_communication_status_rules values ( 291,  29,           31,                   6);
insert into tbl_lkp_communication_status_rules values ( 292,  29,           32,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 293,  29,            1,               8);
insert into tbl_lkp_communication_status_rules values ( 294,  29,            2,               9);
insert into tbl_lkp_communication_status_rules values ( 295,  29,            3,               10);
insert into tbl_lkp_communication_status_rules values ( 296,  29,            4,               11);
insert into tbl_lkp_communication_status_rules values ( 297,  29,            5,               12);
insert into tbl_lkp_communication_status_rules values ( 298,  29,            6,               13);

#Win (30)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 299,  30,           30,              1);
insert into tbl_lkp_communication_status_rules values ( 300,  30,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 301,  30,           11,                   3);
insert into tbl_lkp_communication_status_rules values ( 302,  30,           13,                   4);
insert into tbl_lkp_communication_status_rules values ( 303,  30,           32,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 304,  30,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 305,  30,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 306,  30,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 307,  30,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 308,  30,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 309,  30,            6,               11);

#Gone cold (31)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 310,  31,           31,                 1);
insert into tbl_lkp_communication_status_rules values ( 311,  31,           8,               2);
insert into tbl_lkp_communication_status_rules values ( 312,  31,           9,               3);
insert into tbl_lkp_communication_status_rules values ( 313,  31,           10,               4);
insert into tbl_lkp_communication_status_rules values ( 314,  31,           11,                   5);
insert into tbl_lkp_communication_status_rules values ( 315,  31,           13,                   6);
insert into tbl_lkp_communication_status_rules values ( 316,  31,           28,                   7);
insert into tbl_lkp_communication_status_rules values ( 317,  31,           29,                   8);
insert into tbl_lkp_communication_status_rules values ( 318,  31,           30,                   9);
insert into tbl_lkp_communication_status_rules values ( 319,  31,           32,                   10);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 320,  31,            1,               11);
insert into tbl_lkp_communication_status_rules values ( 321,  31,            2,               12);
insert into tbl_lkp_communication_status_rules values ( 322,  31,            3,               13);
insert into tbl_lkp_communication_status_rules values ( 323,  31,            4,               14);
insert into tbl_lkp_communication_status_rules values ( 324,  31,            5,               15);
insert into tbl_lkp_communication_status_rules values ( 325,  31,            6,               16);

#Follow-up meeting to be arranged (32)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 326,  32,           32,              1);
insert into tbl_lkp_communication_status_rules values ( 327,  32,           31,              2);
insert into tbl_lkp_communication_status_rules values ( 328,  32,           8,               3);
insert into tbl_lkp_communication_status_rules values ( 329,  32,           9,               4);
insert into tbl_lkp_communication_status_rules values ( 330,  32,           10,               5);
insert into tbl_lkp_communication_status_rules values ( 331,  32,           11,                6);
insert into tbl_lkp_communication_status_rules values ( 332,  32,           13,                 7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 333,  32,            1,               8);
insert into tbl_lkp_communication_status_rules values ( 334,  32,            2,               9);
insert into tbl_lkp_communication_status_rules values ( 335,  32,            3,               10);
insert into tbl_lkp_communication_status_rules values ( 336,  32,            4,               11);
insert into tbl_lkp_communication_status_rules values ( 337,  32,            5,               12);
insert into tbl_lkp_communication_status_rules values ( 338,  32,            6,               13);

#-------------------------------------------------------|------|-------------|-------------|-----------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status_rules enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_rules_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_rules_seq disable keys */;
lock tables tbl_lkp_communication_status_rules_seq write;

update tbl_lkp_communication_status_rules_seq set sequence = 338;
alter table tbl_lkp_communication_status_rules_seq auto_increment = 338;

unlock tables;
/*!40000 alter table tbl_lkp_communication_status_rules_seq enable keys */;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------------------------------------
-- END OF UPGRADE FILE SECTIONS
-- The following section of this file should be run AFTER the Access VBA file
-- ----------------------------------------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

alter table tbl_meetings_shadow modify column `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP;
alter table tbl_post_decision_makers_shadow modify column `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP;
alter table tbl_post_agency_users_shadow modify column `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP;

--UPDATE tbl_rbac_users SET password = md5(password) where last_login is null;
UPDATE tbl_rbac_users SET last_login = null where last_login = '0000-00-00 00:00:00';
--UPDATE tbl_rbac_users SET password = md5(handle);


SET FOREIGN_KEY_CHECKS = 1;


