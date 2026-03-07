SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_campaign_companies_do_not_call`
--

CREATE TABLE `tbl_campaign_companies_do_not_call` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_companies_do_not_call_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_companies_do_not_call_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_campaign_companies_do_not_call_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_companies_do_not_call_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_companies_do_not_call_seq`
--

CREATE TABLE `tbl_campaign_companies_do_not_call_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_companies_do_not_call_shadow`
--

CREATE TABLE `tbl_campaign_companies_do_not_call_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbm_targets`
--
DROP TABLE IF EXISTS `tbl_campaign_nbm_targets`;
CREATE TABLE `tbl_campaign_nbm_targets` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `planned_days` int(11) NOT NULL default '0',
  `project_management_days` int(11) NOT NULL default '0',
  `effectives` int(11) NOT NULL default '0',
  `meetings_set` int(11) NOT NULL default '0',
  `meetings_set_imperative` int(11) NOT NULL default '0',
  `meetings_attended` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_nbm_targets_user_id` (`user_id`),
  KEY `ix_tbl_campaign_nbm_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_nbm_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbm_targets_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbm_targets_seq`
--
DROP TABLE IF EXISTS `tbl_campaign_nbm_targets_seq`;
CREATE TABLE `tbl_campaign_nbm_targets_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--INSERT INTO tbl_campaign_targets_seq (sequence) values (12);
--ALTER TABLE tbl_campaigns_seq auto_increment = 13;

--
-- Table structure for table `tbl_campaign_nbm_targets_shadow`
--
DROP TABLE IF EXISTS `tbl_campaign_nbm_targets_shadow`;
CREATE TABLE `tbl_campaign_nbm_targets_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `planned_days` int(11) NOT NULL default '0',
  `project_management_days` int(11) NOT NULL default '0',
  `effectives` int(11) NOT NULL default '0',
  `meetings_set` int(11) NOT NULL default '0',
  `meetings_set_imperative` int(11) NOT NULL default '0',
  `meetings_attended` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


insert into tbl_campaign_nbm_targets (id, user_id, campaign_id, `year_month`, planned_days, project_management_days, effectives, meetings_set, 
meetings_set_imperative, meetings_attended) select id, user_id, campaign_id, `year_month`, planned_days, project_management_days, effectives, meetings_set, 
meetings_set_imperative, meetings_attended from tbl_nbm_campaign_targets;

-- update tbl_campaign_nbm_targets_seq set sequence = 9197;
-- alter table tbl_campaign_nbm_targets_seq auto_increment = 9197;

drop table if exists tbl_nbm_campaign_targets;
drop table if exists tbl_nbm_campaign_targets_seq;
drop table if exists tbl_nbm_campaign_targets_shadow;


--
-- Table structure for table `tbl_post_initiatives`
--

ALTER TABLE tbl_post_initiatives ADD COLUMN `comment` varchar(255) default NULL AFTER status_id;

UPDATE tbl_post_initiatives pi JOIN tbl_communications com ON pi.last_communication_id = com.id
SET pi.comment = left(com.comments, 255);

--
-- Table structure for table `tbl_post_initiatives_shadow`
--

ALTER TABLE tbl_post_initiatives ADD COLUMN `comment` varchar(255) default NULL AFTER status_id;

--
-- Table structure for table `tbl_filters`
--

update tbl_filters set results_format = 'Company' where results_format = 'company';
update tbl_filters set results_format = 'Company and posts' where results_format = 'company_post';
update tbl_filters set results_format = 'Client initiative' where results_format = 'client_initiative';
update tbl_filters set results_format = 'Mailer' where results_format = 'mailer';


SET FOREIGN_KEY_CHECKS = 1;