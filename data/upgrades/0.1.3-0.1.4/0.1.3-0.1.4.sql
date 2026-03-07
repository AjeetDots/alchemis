SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;