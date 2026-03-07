SET FOREIGN_KEY_CHECKS = 0;

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


SET FOREIGN_KEY_CHECKS = 1;