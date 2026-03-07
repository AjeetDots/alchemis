-- MySQL dump 10.11
--
-- Host: localhost    Database: alchemis_uat
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_actions`
--

CREATE TABLE `tbl_actions` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `notes` text NOT NULL,
  `due_date` datetime default NULL,
  `reminder_date` datetime default NULL,
  `user_id` int(11) NOT NULL default '0',
  `completed` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_actions_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_actions_ibfk1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_actions_seq`
--

CREATE TABLE `tbl_actions_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_actions_shadow`
--

CREATE TABLE `tbl_actions_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `subject` varchar(100) NOT NULL default '',
  `notes` text NOT NULL,
  `due_date` datetime default NULL,
  `reminder_date` datetime default NULL,
  `user_id` int(11) NOT NULL default '0',
  `completed` datetime default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_bank_holidays`
--

CREATE TABLE `tbl_bank_holidays` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_bank_holidays_seq`
--

CREATE TABLE `tbl_bank_holidays_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_disciplines`
--

CREATE TABLE `tbl_campaign_disciplines` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_disciplines` (`campaign_id`),
  KEY `ix_tbl_tbl_campaign_disciplines_ibfk2` (`tiered_characteristic_id`),
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_tbl_campaign_disciplines_ibfk2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_campaign_nbm_targets`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=11221 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbm_targets_seq`
--

CREATE TABLE `tbl_campaign_nbm_targets_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbm_targets_shadow`
--

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

--
-- Table structure for table `tbl_campaign_nbms`
--

CREATE TABLE `tbl_campaign_nbms` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `user_alias` varchar(255) NOT NULL,
  `is_lead_nbm` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_nbms_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=935 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbms_seq`
--

CREATE TABLE `tbl_campaign_nbms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=937 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_nbms_shadow`
--

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
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_regions`
--

CREATE TABLE `tbl_campaign_regions` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `region_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_regions_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_regions_region_id` (`region_id`),
  CONSTRAINT `ix_tbl_campaign_regions_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_regions_ibfk2` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_regions_seq`
--

CREATE TABLE `tbl_campaign_regions_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_regions_shadow`
--

CREATE TABLE `tbl_campaign_regions_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `region_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_targets`
--

CREATE TABLE `tbl_campaign_targets` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `calls` int(3) NOT NULL default '0',
  `effectives` int(3) NOT NULL default '0',
  `meetings_set` int(3) NOT NULL default '0',
  `meetings_attended` int(3) NOT NULL default '0',
  `opportunities` int(3) NOT NULL default '0',
  `wins` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6505 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_targets_seq`
--

CREATE TABLE `tbl_campaign_targets_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_targets_shadow`
--

CREATE TABLE `tbl_campaign_targets_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `calls` int(3) NOT NULL default '0',
  `effectives` int(3) NOT NULL default '0',
  `meetings_set` int(3) NOT NULL default '0',
  `meetings_attended` int(3) NOT NULL default '0',
  `opportunities` int(3) NOT NULL default '0',
  `wins` int(3) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaigns`
--

CREATE TABLE `tbl_campaigns` (
  `id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `start_year_month` char(6) NOT NULL default '',
  `end_year_month` char(6) NOT NULL default '',
  `contract_sent_date` datetime default NULL,
  `contract_received_date` datetime default NULL,
  `so_form_received` tinyint(1) NOT NULL default '0',
  `billing_terms` varchar(50) default NULL,
  `payment_terms` varchar(50) default NULL,
  `payment_method` varchar(50) default NULL,
  `minimum_duration` tinyint(2) unsigned NOT NULL default '0',
  `notice_period` tinyint(2) unsigned NOT NULL default '0',
  `notice_date` datetime default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_client_id` (`client_id`),
  CONSTRAINT `ix_tbl_campaigns_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaigns_seq`
--

CREATE TABLE `tbl_campaigns_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaigns_shadow`
--

CREATE TABLE `tbl_campaigns_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `client_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `start_year_month` char(6) NOT NULL default '',
  `end_year_month` char(6) NOT NULL default '',
  `contract_sent_date` datetime default NULL,
  `contract_received_date` datetime default NULL,
  `so_form_received` tinyint(1) NOT NULL default '0',
  `billing_terms` varchar(50) default NULL,
  `payment_terms` varchar(50) default NULL,
  `payment_method` varchar(50) default NULL,
  `minimum_duration` tinyint(2) unsigned NOT NULL default '0',
  `notice_period` tinyint(2) unsigned NOT NULL default '0',
  `notice_date` datetime default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristic_elements`
--

CREATE TABLE `tbl_characteristic_elements` (
  `id` int(11) NOT NULL auto_increment,
  `characteristic_id` int(11) NOT NULL default '0',
  `data_type` enum('boolean','date','text') NOT NULL default 'text',
  `name` varchar(255) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `characteristic_id` (`characteristic_id`,`name`),
  KEY `ix_tbl_characteristic_elements_characteristic_id` (`characteristic_id`),
  CONSTRAINT `tbl_characteristic_elements_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristic_elements_seq`
--

CREATE TABLE `tbl_characteristic_elements_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristic_elements_shadow`
--

CREATE TABLE `tbl_characteristic_elements_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `characteristic_id` int(11) NOT NULL default '0',
  `data_type` enum('boolean','date','text') NOT NULL default 'text',
  `name` varchar(255) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristics`
--

CREATE TABLE `tbl_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('company','post','post initiative') NOT NULL default 'company',
  `name` varchar(255) NOT NULL default '',
  `description` text,
  `attributes` tinyint(1) NOT NULL default '0',
  `options` tinyint(1) NOT NULL default '0',
  `multiple_select` tinyint(1) NOT NULL default '0',
  `data_type` enum('boolean','date','text') default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tbl_characteristics` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristics_seq`
--

CREATE TABLE `tbl_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_characteristics_shadow`
--

CREATE TABLE `tbl_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `type` enum('company','post','post initiative') NOT NULL default 'company',
  `name` varchar(255) NOT NULL default '',
  `description` text,
  `attributes` tinyint(1) NOT NULL default '0',
  `options` tinyint(1) NOT NULL default '0',
  `multiple_select` tinyint(1) NOT NULL default '0',
  `data_type` enum('boolean','date','text') default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_client_contacts`
--

CREATE TABLE `tbl_client_contacts` (
  `id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `job_title` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `telephone` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_client_contacts_client_id` (`client_id`),
  CONSTRAINT `ix_tbl_client_contacts_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_client_contacts_seq`
--

CREATE TABLE `tbl_client_contacts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_client_contacts_shadow`
--

CREATE TABLE `tbl_client_contacts_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `client_id` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `job_title` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `telephone` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `address_1` varchar(50) default NULL,
  `address_2` varchar(50) default NULL,
  `address_3` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `postcode` varchar(25) default NULL,
  `telephone` varchar(50) default NULL,
  `county_id` int(11) default NULL,
  `country_id` int(11) default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `tbl_clients_ibfk2` (`county_id`),
  KEY `tbl_clients_ibfk3` (`country_id`),
  CONSTRAINT `tbl_clients_ibfk2` FOREIGN KEY (`county_id`) REFERENCES `tbl_lkp_counties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_clients_ibfk3` FOREIGN KEY (`country_id`) REFERENCES `tbl_lkp_countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_clients_seq`
--

CREATE TABLE `tbl_clients_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_clients_shadow`
--

CREATE TABLE `tbl_clients_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `address_1` varchar(50) default NULL,
  `address_2` varchar(50) default NULL,
  `address_3` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `postcode` varchar(25) default NULL,
  `telephone` varchar(50) default NULL,
  `county_id` int(11) default NULL,
  `country_id` int(11) default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_communications`
--

CREATE TABLE `tbl_communications` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lead_source_id` int(11) default NULL,
  `status_id` int(11) NOT NULL default '0',
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
  `next_action_by` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_communications_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_communications_next_communication_date` (`next_communication_date`),
  KEY `ix_tbl_communications_lead_source_id` (`lead_source_id`),
  KEY `ix_tbl_communications_status` (`status_id`),
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
  KEY `ix_tbl_communications_next_action_by` (`next_action_by`),
  CONSTRAINT `ix_tbl_communications_ibfk9` FOREIGN KEY (`next_action_by`) REFERENCES `tbl_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_communication_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk3` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk4` FOREIGN KEY (`targeting_id`) REFERENCES `tbl_lkp_communication_targeting` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk5` FOREIGN KEY (`receptiveness_id`) REFERENCES `tbl_lkp_communication_receptiveness` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk6` FOREIGN KEY (`decision_maker_type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk7` FOREIGN KEY (`note_id`) REFERENCES `tbl_post_initiative_notes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk8` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=418928 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_communications_seq`
--

CREATE TABLE `tbl_communications_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=418928 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_communications_shadow`
--

CREATE TABLE `tbl_communications_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lead_source_id` int(11) default NULL,
  `status` int(11) NOT NULL,
  `old_status` varchar(50) default NULL,
  `communication_date` datetime NOT NULL,
  `direction` enum('out','in') NOT NULL default 'out',
  `type` enum('effective','non-effective') NOT NULL default 'non-effective',
  `targeting` int(11) NOT NULL default '0',
  `receptiveness` int(11) NOT NULL default '0',
  `decision_maker_type_id` int(11) default NULL,
  `next_communication_date` datetime default NULL,
  `next_communication_date_reason_id` int(11) default NULL,
  `comments` text,
  `notes` text,
  `next_action_by` int(11) NOT NULL default '1',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_companies`
--

CREATE TABLE `tbl_companies` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `website` varchar(255) default '',
  `telephone` varchar(50) default '',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_companies_deleted` (`deleted`),
  KEY `ix_tbl_companies_name` (`name`),
  KEY `ix_tbl_companies_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_companies_telephone` (`telephone`),
  KEY `ix_tbl_companies_website` (`website`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_companies_seq`
--

CREATE TABLE `tbl_companies_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=31856 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_companies_shadow`
--

CREATE TABLE `tbl_companies_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `website` varchar(255) default '',
  `telephone` varchar(50) default '',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_notes`
--

CREATE TABLE `tbl_company_notes` (
  `id` int(11) NOT NULL auto_increment,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_company_notes_company_id` (`company_id`),
  KEY `ix_tbl_company_notes_created_at` (`created_at`),
  KEY `ix_tbl_company_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_company_notes_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5418 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_notes_seq`
--

CREATE TABLE `tbl_company_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5418 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_notes_shadow`
--

CREATE TABLE `tbl_company_notes_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_tags`
--

CREATE TABLE `tbl_company_tags` (
  `id` int(11) NOT NULL auto_increment,
  `company_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`company_id`,`tag_id`),
  KEY `ix_tbl_company_tags_company_id` (`company_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_company_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_tags_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2997 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_tags_seq`
--

CREATE TABLE `tbl_company_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2997 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_company_tags_shadow`
--

CREATE TABLE `tbl_company_tags_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_configuration`
--

CREATE TABLE `tbl_configuration` (
  `property` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`property`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_contacts`
--

CREATE TABLE `tbl_contacts` (
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `title` varchar(25) default '',
  `first_name` varchar(50) default '',
  `surname` varchar(50) default '',
  `deleted` tinyint(1) NOT NULL default '0',
  `email` varchar(100) default '',
  `telephone_mobile` varchar(100) default '',
  `full_name` varchar(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_contacts_post_id` (`post_id`),
  KEY `ix_tbl_contacts_deleted` (`deleted`),
  KEY `ix_tbl_contacts_first_name` (`first_name`),
  KEY `ix_tbl_contacts_surname` (`surname`),
  KEY `ix_tbl_contacts_full_name` (`full_name`),
  CONSTRAINT `tbl_posts_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_contacts_seq`
--

CREATE TABLE `tbl_contacts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=102783 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_contacts_shadow`
--

CREATE TABLE `tbl_contacts_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `title` varchar(25) default '',
  `first_name` varchar(50) default '',
  `surname` varchar(50) default '',
  `telephone` varchar(50) default '',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_data_statistics`
--

CREATE TABLE `tbl_data_statistics` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `campaign_current_month` int(11) NOT NULL default '0',
  `campaign_monthly_fee` int(11) NOT NULL default '0',
  `campaign_meeting_set_target` int(11) NOT NULL default '0',
  `campaign_meeting_set_target_to_date` int(11) NOT NULL default '0',
  `campaign_meeting_set_count_to_date` int(11) NOT NULL default '0',
  `campaign_meeting_attended_target` int(11) NOT NULL default '0',
  `campaign_meeting_attended_target_to_date` int(11) NOT NULL default '0',
  `campaign_meeting_attended_count_to_date` int(11) NOT NULL default '0',
  `call_count` int(11) NOT NULL default '0',
  `call_effective_count` int(11) NOT NULL default '0',
  `call_ote_count` int(11) NOT NULL default '0',
  `call_access_rate` decimal(10,4) NOT NULL default '0.0000',
  `meeting_set_count` int(11) NOT NULL default '0',
  `meeting_time_lag_0_3` int(11) NOT NULL default '0',
  `meeting_time_lag_3_5` int(11) NOT NULL default '0',
  `meeting_time_lag_5_7` int(11) NOT NULL default '0',
  `meeting_time_lag_7_` int(11) NOT NULL default '0',
  `meeting_in_diary_this_month_count` int(11) NOT NULL default '0',
  `meeting_attended_count` int(11) NOT NULL default '0',
  `meeting_attended_rate` decimal(10,4) NOT NULL default '0.0000',
  `win_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_data_statistics_campaign_id` (`campaign_id`),
  KEY `ix_tbl_data_statistics_user_id` (`user_id`),
  KEY `ix_tbl_data_statistics_year_month` (`year_month`)
) ENGINE=InnoDB AUTO_INCREMENT=27265 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_data_statistics_run`
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

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `notes` text NOT NULL,
  `date` date default NULL,
  `reminder_date` datetime default NULL,
  `user_id` int(11) NOT NULL default '0',
  `type_id` int(11) unsigned NOT NULL default '0',
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
  `subject` varchar(100) NOT NULL default '',
  `notes` text NOT NULL,
  `date` date default NULL,
  `reminder_date` datetime default NULL,
  `user_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_exclude`
--

CREATE TABLE `tbl_exclude` (
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `row_key` varchar(50) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_lines`
--

CREATE TABLE `tbl_filter_lines` (
  `id` int(11) NOT NULL auto_increment,
  `filter_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL default '',
  `field_name` varchar(255) NOT NULL default '',
  `params` text NOT NULL,
  `params_display` text NOT NULL,
  `operator` varchar(50) default NULL,
  `concatenator` varchar(10) default NULL,
  `bracket_open` varchar(25) default NULL,
  `bracket_close` varchar(25) default NULL,
  `direction` varchar(25) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_filter_lines_filter_id` (`filter_id`),
  KEY `ix_tbl_filter_lines_direction` (`direction`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_lines_seq`
--

CREATE TABLE `tbl_filter_lines_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_lines_shadow`
--

CREATE TABLE `tbl_filter_lines_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `filter_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL default '',
  `field_name` varchar(50) NOT NULL default '',
  `params` text NOT NULL,
  `params_display` text NOT NULL,
  `operator` varchar(50) default NULL,
  `concatenator` varchar(10) default NULL,
  `bracket_open` varchar(25) default NULL,
  `bracket_close` varchar(25) default NULL,
  `direction` varchar(25) default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_results`
--

CREATE TABLE `tbl_filter_results` (
  `id` int(11) NOT NULL auto_increment,
  `filter_id` int(11) NOT NULL,
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `propensity_max` int(11) default NULL,
  `propensity_avg` int(11) default NULL,
  `propensity_min` int(11) default NULL,
  `propensity_sum` int(11) default NULL,
  `post_count` int(11) default '0',
  `post_communication_count` int(11) default '0',
  `post_effective_count` int(11) default '0',
  `client_initiative_communication_count` int(11) default '0',
  `client_initiative_effective_count` int(11) default '0',
  `company_effective_count` int(11) default '0',
  `company_communication_count` int(11) default '0',
  `company_client_initiative_communication_count` int(11) default '0',
  `company_client_initiative_effective_count` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_filters_results_filter_id` (`filter_id`),
  KEY `ix_tbl_filters_results_company_id` (`company_id`),
  KEY `ix_tbl_filters_results_post_id` (`post_id`),
  KEY `ix_tbl_filters_results_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `ix_tbl_filter_results_ibfk1` FOREIGN KEY (`filter_id`) REFERENCES `tbl_filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66462 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_results_seq`
--

CREATE TABLE `tbl_filter_results_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=56165 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filter_results_shadow`
--

CREATE TABLE `tbl_filter_results_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `filter_id` int(11) NOT NULL,
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `propensity_max` int(11) default NULL,
  `propensity_avg` int(11) default NULL,
  `propensity_min` int(11) default NULL,
  `propensity_sum` int(11) default NULL,
  `post_count` int(11) default NULL,
  `post_communication_count` int(11) default NULL,
  `post_effective_count` int(11) default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filters`
--

CREATE TABLE `tbl_filters` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `description` text,
  `type_id` int(11) NOT NULL default '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `results_format` varchar(50) NOT NULL default 'company',
  `company_count` int(11) default '0',
  `post_count` int(11) default '0',
  `communication_count` int(11) default '0',
  `effective_count` int(11) default '0',
  `updated_at` datetime default NULL,
  `client_initiative_id` int(11) default NULL,
  `client_initiative_communication_count` int(11) default '0',
  `client_initiative_effective_count` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_filters_name` (`name`),
  KEY `ix_tbl_filters_type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filters_seq`
--

CREATE TABLE `tbl_filters_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_filters_shadow`
--

CREATE TABLE `tbl_filters_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `description` text,
  `type_id` int(11) NOT NULL default '1',
  `results_format` varchar(50) NOT NULL default 'company',
  `company_count` int(11) default '0',
  `post_count` int(11) default '0',
  `communication_count` int(11) default '0',
  `effective_count` int(11) default '0',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_include`
--

CREATE TABLE `tbl_include` (
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `row_key` varchar(50) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_information_requests`
--

CREATE TABLE `tbl_information_requests` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime default NULL,
  `notes` varchar(255) default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `comm_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_information_requests_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_information_requests_date` (`date`),
  KEY `ix_tbl_information_requests_reminder_date` (`reminder_date`),
  KEY `ix_tbl_information_requests_ibfk2` (`status_id`),
  KEY `ix_tbl_information_requests_type_id` (`type_id`),
  KEY `ix_tbl_information_requests_comm_type_id` (`comm_type_id`),
  KEY `ix_tbl_information_requests_communication_id` (`communication_id`),
  CONSTRAINT `ix_tbl_information_requests_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_information_request_status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_information_request_types` (`id`),
  CONSTRAINT `ix_tbl_information_requests_ibfk4` FOREIGN KEY (`comm_type_id`) REFERENCES `tbl_lkp_information_request_comm_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5006 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_information_requests_seq`
--

CREATE TABLE `tbl_information_requests_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5006 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_information_requests_shadow`
--

CREATE TABLE `tbl_information_requests_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime default NULL,
  `notes` varchar(255) default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_initiatives`
--

CREATE TABLE `tbl_initiatives` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_initiatives_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_initiatives_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_initiatives_seq`
--

CREATE TABLE `tbl_initiatives_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_initiatives_shadow`
--

CREATE TABLE `tbl_initiatives_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_agency_user_types`
--

CREATE TABLE `tbl_lkp_agency_user_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_agency_user_types_seq`
--

CREATE TABLE `tbl_lkp_agency_user_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness`
--

CREATE TABLE `tbl_lkp_communication_receptiveness` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_seq`
--

CREATE TABLE `tbl_lkp_communication_receptiveness_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status`
--

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
-- Table structure for table `tbl_lkp_communication_status_rules`
--

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

CREATE TABLE `tbl_lkp_communication_status_rules_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_rules_shadow`
--

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

--
-- Table structure for table `tbl_lkp_communication_status_seq`
--

CREATE TABLE `tbl_lkp_communication_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_status_shadow`
--

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

--
-- Table structure for table `tbl_lkp_communication_targeting`
--

CREATE TABLE `tbl_lkp_communication_targeting` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_targeting_seq`
--

CREATE TABLE `tbl_lkp_communication_targeting_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_types`
--

CREATE TABLE `tbl_lkp_communication_types` (
  `id` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_types_seq`
--

CREATE TABLE `tbl_lkp_communication_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_communication_types_shadow`
--

CREATE TABLE `tbl_lkp_communication_types_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_counties`
--

CREATE TABLE `tbl_lkp_counties` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_counties_seq`
--

CREATE TABLE `tbl_lkp_counties_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=816 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_counties_shadow`
--

CREATE TABLE `tbl_lkp_counties_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_countries`
--

CREATE TABLE `tbl_lkp_countries` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_countries_seq`
--

CREATE TABLE `tbl_lkp_countries_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_countries_shadow`
--

CREATE TABLE `tbl_lkp_countries_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_decision_maker_types`
--

CREATE TABLE `tbl_lkp_decision_maker_types` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_decision_maker_types_seq`
--

CREATE TABLE `tbl_lkp_decision_maker_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_event_types`
--

CREATE TABLE `tbl_lkp_event_types` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_comm_types`
--

CREATE TABLE `tbl_lkp_information_request_comm_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_comm_types_seq`
--

CREATE TABLE `tbl_lkp_information_request_comm_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_comm_types_shadow`
--

CREATE TABLE `tbl_lkp_information_request_comm_types_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_status`
--

CREATE TABLE `tbl_lkp_information_request_status` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_status_seq`
--

CREATE TABLE `tbl_lkp_information_request_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_status_shadow`
--

CREATE TABLE `tbl_lkp_information_request_status_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_types`
--

CREATE TABLE `tbl_lkp_information_request_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_types_seq`
--

CREATE TABLE `tbl_lkp_information_request_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_information_request_types_shadow`
--

CREATE TABLE `tbl_lkp_information_request_types_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_lead_source`
--

CREATE TABLE `tbl_lkp_lead_source` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_lead_source_seq`
--

CREATE TABLE `tbl_lkp_lead_source_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_lead_source_shadow`
--

CREATE TABLE `tbl_lkp_lead_source_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_response_groups`
--

CREATE TABLE `tbl_lkp_mailer_response_groups` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_response_groups_seq`
--

CREATE TABLE `tbl_lkp_mailer_response_groups_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_response_groups_shadow`
--

CREATE TABLE `tbl_lkp_mailer_response_groups_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_responses`
--

CREATE TABLE `tbl_lkp_mailer_responses` (
  `id` int(11) NOT NULL auto_increment,
  `response_group_id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_responses_seq`
--

CREATE TABLE `tbl_lkp_mailer_responses_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_responses_shadow`
--

CREATE TABLE `tbl_lkp_mailer_responses_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `response_group_id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_types`
--

CREATE TABLE `tbl_lkp_mailer_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_types_seq`
--

CREATE TABLE `tbl_lkp_mailer_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_mailer_types_shadow`
--

CREATE TABLE `tbl_lkp_mailer_types_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_status`
--

CREATE TABLE `tbl_lkp_meeting_status` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_status_seq`
--

CREATE TABLE `tbl_lkp_meeting_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_status_shadow`
--

CREATE TABLE `tbl_lkp_meeting_status_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_types`
--

CREATE TABLE `tbl_lkp_meeting_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_types_seq`
--

CREATE TABLE `tbl_lkp_meeting_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_meeting_types_shadow`
--

CREATE TABLE `tbl_lkp_meeting_types_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_next_communication_reasons`
--

CREATE TABLE `tbl_lkp_next_communication_reasons` (
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_next_communication_reasons_seq`
--

CREATE TABLE `tbl_lkp_next_communication_reasons_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_next_communication_reasons_shadow`
--

CREATE TABLE `tbl_lkp_next_communication_reasons_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `status_score` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_postcodes`
--

CREATE TABLE `tbl_lkp_postcodes` (
  `id` int(11) NOT NULL auto_increment,
  `postcode` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_postcodes_postcode` (`postcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_postcodes_seq`
--

CREATE TABLE `tbl_lkp_postcodes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_region_postcodes`
--

CREATE TABLE `tbl_lkp_region_postcodes` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `postcode_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_region_postcodes_region_id` (`region_id`),
  KEY `ix_tbl_lkp_region_postcodes_postcode_id` (`postcode_id`),
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk1` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_region_postcodes_seq`
--

CREATE TABLE `tbl_lkp_region_postcodes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_regions`
--

CREATE TABLE `tbl_lkp_regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  `description` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_regions_seq`
--

CREATE TABLE `tbl_lkp_regions_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_reports`
--

CREATE TABLE `tbl_lkp_reports` (
  `id` int(1) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `design_file` varchar(255) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_item_responses`
--

CREATE TABLE `tbl_mailer_item_responses` (
  `id` int(11) NOT NULL auto_increment,
  `mailer_item_id` int(11) NOT NULL,
  `mailer_response_id` int(11) NOT NULL,
  `note` varchar(255) default NULL,
  `communication_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_mailer_item_responses_mailer_item_id` (`mailer_item_id`),
  KEY `ix_tbl_mailer_item_responses_mailer_response_id` (`mailer_response_id`),
  KEY `ix_tbl_mailer_item_responses_communication_id` (`communication_id`),
  CONSTRAINT `tbl_mailer_item_responses_ibfk1` FOREIGN KEY (`mailer_item_id`) REFERENCES `tbl_mailer_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_item_responses_ibfk2` FOREIGN KEY (`mailer_response_id`) REFERENCES `tbl_lkp_mailer_responses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_item_responses_ibfk3` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_item_responses_seq`
--

CREATE TABLE `tbl_mailer_item_responses_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_item_responses_shadow`
--

CREATE TABLE `tbl_mailer_item_responses_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `mailer_item_id` int(11) NOT NULL,
  `mailer_response_id` int(11) NOT NULL,
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_items`
--

CREATE TABLE `tbl_mailer_items` (
  `id` int(11) NOT NULL auto_increment,
  `mailer_id` int(11) NOT NULL,
  `post_initiative_id` int(11) NOT NULL,
  `despatched_date` datetime default NULL,
  `response_date` datetime default NULL,
  `communication_id` int(11) default NULL,
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_mailer_items_id` (`mailer_id`),
  KEY `ix_tbl_mailer_items_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_mailer_items_despatched_date` (`despatched_date`),
  KEY `ix_tbl_mailer_items_communication_id` (`communication_id`),
  CONSTRAINT `tbl_mailer_items_ibfk1` FOREIGN KEY (`mailer_id`) REFERENCES `tbl_mailers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk3` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_items_seq`
--

CREATE TABLE `tbl_mailer_items_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailer_items_shadow`
--

CREATE TABLE `tbl_mailer_items_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `mailer_id` int(11) NOT NULL,
  `post_initiative_id` int(11) NOT NULL,
  `despatched_date` datetime default NULL,
  `response_date` datetime default NULL,
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailers`
--

CREATE TABLE `tbl_mailers` (
  `id` int(11) NOT NULL auto_increment,
  `client_initiative_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) default NULL,
  `response_group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_mailers_client_initiative_id` (`client_initiative_id`),
  KEY `ix_tbl_mailers_response_group_id` (`response_group_id`),
  KEY `ix_tbl_mailers_type_id` (`type_id`),
  CONSTRAINT `tbl_mailers_ibfk1` FOREIGN KEY (`client_initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk2` FOREIGN KEY (`response_group_id`) REFERENCES `tbl_lkp_mailer_response_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_mailer_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailers_seq`
--

CREATE TABLE `tbl_mailers_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_mailers_shadow`
--

CREATE TABLE `tbl_mailers_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `client_initiative_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) default NULL,
  `response_group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_meetings`
--

CREATE TABLE `tbl_meetings` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `is_current` tinyint(1) NOT NULL default '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime default NULL,
  `notes` varchar(255) default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_meetings_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_meetings_status_id` (`status_id`),
  KEY `ix_tbl_meetings_type_id` (`type_id`),
  KEY `ix_tbl_meetings_communication_id` (`communication_id`),
  KEY `ix_tbl_meetings_date` (`date`),
  KEY `ix_tbl_meetings_created_by` (`created_by`),
  KEY `ix_tbl_meetings_is_current` (`is_current`),
  CONSTRAINT `tbl_meetings_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_communication_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_meeting_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19295 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_meetings_seq`
--

CREATE TABLE `tbl_meetings_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=19295 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_meetings_shadow`
--

CREATE TABLE `tbl_meetings_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `is_current` tinyint(1) NOT NULL default '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime default NULL,
  `notes` varchar(255) default '',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`),
  KEY `ix_tbl_meetings_shadow_date` (`date`),
  KEY `ix_tbl_meetings_shadow_created_by` (`created_by`),
  KEY `ix_tbl_meetings_shadow_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6173 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_messages_seq`
--

CREATE TABLE `tbl_messages_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean`
--

CREATE TABLE `tbl_object_characteristic_elements_boolean` (
  `id` int(11) NOT NULL auto_increment,
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_boolean_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_boolean_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_boolean_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_boolean_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean_seq`
--

CREATE TABLE `tbl_object_characteristic_elements_boolean_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean_shadow`
--

CREATE TABLE `tbl_object_characteristic_elements_boolean_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_date`
--

CREATE TABLE `tbl_object_characteristic_elements_date` (
  `id` int(11) NOT NULL auto_increment,
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_date_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_date_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_date_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_date_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_date_seq`
--

CREATE TABLE `tbl_object_characteristic_elements_date_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_date_shadow`
--

CREATE TABLE `tbl_object_characteristic_elements_date_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_text`
--

CREATE TABLE `tbl_object_characteristic_elements_text` (
  `id` int(11) NOT NULL auto_increment,
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_text_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_text_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_text_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_text_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_text_seq`
--

CREATE TABLE `tbl_object_characteristic_elements_text_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristic_elements_text_shadow`
--

CREATE TABLE `tbl_object_characteristic_elements_text_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `object_characteristic_id` int(11) NOT NULL default '0',
  `characteristic_element_id` int(11) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics`
--

CREATE TABLE `tbl_object_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_object_characteristics_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_boolean`
--

CREATE TABLE `tbl_object_characteristics_boolean` (
  `id` int(11) NOT NULL auto_increment,
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_object_characteristics_boolean_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_boolean_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_boolean_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_boolean_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_boolean_seq`
--

CREATE TABLE `tbl_object_characteristics_boolean_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_boolean_shadow`
--

CREATE TABLE `tbl_object_characteristics_boolean_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` int(1) default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_date`
--

CREATE TABLE `tbl_object_characteristics_date` (
  `id` int(11) NOT NULL auto_increment,
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_object_characteristics_date_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_date_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_date_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_date_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_date_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_date_seq`
--

CREATE TABLE `tbl_object_characteristics_date_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_date_shadow`
--

CREATE TABLE `tbl_object_characteristics_date_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_seq`
--

CREATE TABLE `tbl_object_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_shadow`
--

CREATE TABLE `tbl_object_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_text`
--

CREATE TABLE `tbl_object_characteristics_text` (
  `id` int(11) NOT NULL auto_increment,
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_object_characteristics_text_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_text_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_text_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_text_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_text_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_text_seq`
--

CREATE TABLE `tbl_object_characteristics_text_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_characteristics_text_shadow`
--

CREATE TABLE `tbl_object_characteristics_text_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `characteristic_id` int(11) NOT NULL default '0',
  `company_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `post_initiative_id` int(11) default NULL,
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics`
--

CREATE TABLE `tbl_object_tiered_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tiered_characteristic_id`,`company_id`),
  KEY `ix_tbl_object_tiered_characteristics_tiered_characteristic_id` (`tiered_characteristic_id`),
  KEY `ix_tbl_object_tiered_characteristics_tier` (`tier`),
  KEY `ix_tbl_object_tiered_characteristics_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36990 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics_seq`
--

CREATE TABLE `tbl_object_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=36990 DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_post_agency_incumbents`
--

CREATE TABLE `tbl_post_agency_incumbents` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_agency_incumbents_post_id` (`post_id`),
  KEY `ix_tbl_post_agency_incumbents_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_seq`
--

CREATE TABLE `tbl_post_agency_incumbents_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_incumbents_shadow`
--

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
-- Table structure for table `tbl_post_agency_users`
--

CREATE TABLE `tbl_post_agency_users` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_agency_users_id` (`post_id`),
  KEY `ix_tbl_post_agency_users_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_agency_users_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_agency_users_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_agency_user_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_seq`
--

CREATE TABLE `tbl_post_agency_users_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_agency_users_shadow`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers`
--

CREATE TABLE `tbl_post_decision_makers` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_decision_makers_post_id` (`post_id`),
  KEY `ix_tbl_post_decision_makers_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_decision_makers_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_seq`
--

CREATE TABLE `tbl_post_decision_makers_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_decision_makers_shadow`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates`
--

CREATE TABLE `tbl_post_discipline_review_dates` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_discipline_review_dates_post_id` (`post_id`),
  KEY `ix_tbl_post_discipline_review_dates_discipline_id` (`discipline_id`),
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates_seq`
--

CREATE TABLE `tbl_post_discipline_review_dates_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_discipline_review_dates_shadow`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_notes`
--

CREATE TABLE `tbl_post_initiative_notes` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_initiative_notes_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_post_initiative_notes_created_at` (`created_at`),
  KEY `ix_tbl_post_initiative_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=418140 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_notes_seq`
--

CREATE TABLE `tbl_post_initiative_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=418140 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_notes_shadow`
--

CREATE TABLE `tbl_post_initiative_notes_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_initiative_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_tags`
--

CREATE TABLE `tbl_post_initiative_tags` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`post_initiative_id`,`tag_id`),
  KEY `ix_tbl_company_tags_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_initiative_tags_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122818 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_tags_seq`
--

CREATE TABLE `tbl_post_initiative_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=122806 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_tags_shadow`
--

CREATE TABLE `tbl_post_initiative_tags_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_initiative_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiatives`
--

CREATE TABLE `tbl_post_initiatives` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL default '0',
  `last_effective_communication_id` int(11) default NULL,
  `last_communication_id` int(11) default NULL,
  `next_communication_date` datetime default NULL,
  `last_mailer_communication_id` int(11) default NULL,
  `lead_source_id` int(11) default NULL,
  `next_action_by` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_initiatives_post_id` (`post_id`),
  KEY `ix_tbl_post_initiatives_initiative_id` (`initiative_id`),
  KEY `ix_tbl_post_initiatives_status` (`status_id`),
  KEY `ix_tbl_post_initiatives_last_effective_communication_id` (`last_effective_communication_id`),
  KEY `ix_tbl_post_initiatives_last_communication_id` (`last_communication_id`),
  KEY `ix_tbl_post_initiatives_initiative_id_and_post_id` (`initiative_id`,`post_id`),
  KEY `ix_tbl_post_initiatives_post_id_and_initiaitve_id` (`post_id`,`initiative_id`),
  KEY `ix_tbl_post_initiatives_next_communication_date` (`next_communication_date`),
  KEY `ix_tbl_post_initiatives_last_mailer_communication_id` (`last_mailer_communication_id`),
  KEY `ix_tbl_post_initiatives_lead_source_id` (`lead_source_id`),
  KEY `ix_tbl_post_initiatives_next_action_by` (`next_action_by`),
  CONSTRAINT `ix_tbl_post_initiatives_ibfk6` FOREIGN KEY (`next_action_by`) REFERENCES `tbl_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk2` FOREIGN KEY (`initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk3` FOREIGN KEY (`last_effective_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk4` FOREIGN KEY (`last_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk5` FOREIGN KEY (`last_mailer_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiatives_seq`
--

CREATE TABLE `tbl_post_initiatives_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=122130 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiatives_shadow`
--

CREATE TABLE `tbl_post_initiatives_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL,
  `initiative_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `last_effective_communication_id` int(11) default NULL,
  `last_communication_id` int(11) default NULL,
  `next_communication_date` datetime default NULL,
  `last_mailer_communication_id` int(11) default NULL,
  `lead_source_id` int(11) default NULL,
  `next_action_by` int(11) NOT NULL default '1',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  CONSTRAINT `ix_tbl_post_notes_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32449 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_notes_seq`
--

CREATE TABLE `tbl_post_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=32449 DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_post_site`
--

CREATE TABLE `tbl_post_site` (
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `site_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ix_tbl_post_unique1` (`post_id`,`site_id`),
  KEY `ix_tbl_post_site_post_id` (`post_id`),
  KEY `ix_tbl_post_site_site_id` (`site_id`),
  CONSTRAINT `tbl_post_site_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_post_site_ibfk2` FOREIGN KEY (`site_id`) REFERENCES `tbl_sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_site_seq`
--

CREATE TABLE `tbl_post_site_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=118208 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_site_shadow`
--

CREATE TABLE `tbl_post_site_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `site_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_tags`
--

CREATE TABLE `tbl_post_tags` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`post_id`,`tag_id`),
  KEY `ix_tbl_company_tags_post_id` (`post_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_post_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_tags_ibfk2` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_tags_seq`
--

CREATE TABLE `tbl_post_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_tags_shadow`
--

CREATE TABLE `tbl_post_tags_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `job_title` varchar(255) NOT NULL default '',
  `propensity` int(11) NOT NULL default '0',
  `notes` text,
  `deleted` tinyint(1) NOT NULL default '0',
  `telephone_1` varchar(50) default '',
  `telephone_2` varchar(50) default '',
  `telephone_switchboard` varchar(50) default '',
  `telephone_fax` varchar(50) default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_posts_company_id` (`company_id`),
  KEY `ix_tbl_posts_job_title` (`job_title`),
  KEY `ix_tbl_posts_deleted` (`deleted`),
  KEY `ix_tbl_posts_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_posts_propensity` (`propensity`),
  CONSTRAINT `ix_tbl_posts_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_posts_seq`
--

CREATE TABLE `tbl_posts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=118209 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_posts_shadow`
--

CREATE TABLE `tbl_posts_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `job_title` varchar(255) NOT NULL default '',
  `propensity` int(11) NOT NULL default '0',
  `notes` text,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_commands`
--

CREATE TABLE `tbl_rbac_commands` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_permissions`
--

CREATE TABLE `tbl_rbac_permissions` (
  `id` int(11) NOT NULL default '0',
  `command_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_role_permissions`
--

CREATE TABLE `tbl_rbac_role_permissions` (
  `id` int(11) NOT NULL default '0',
  `role_id` int(11) NOT NULL default '0',
  `permission_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ix_tbl_rbac_role_permissions_1` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_roles`
--

CREATE TABLE `tbl_rbac_roles` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_roles_seq`
--

CREATE TABLE `tbl_rbac_roles_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_sessions`
--

CREATE TABLE `tbl_rbac_sessions` (
  `id` varchar(32) NOT NULL default '',
  `expiration` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_rbac_sessions_expiration` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_user_roles`
--

CREATE TABLE `tbl_rbac_user_roles` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `role_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ix_tbl_rbac_user_roles_1` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rbac_users`
--

CREATE TABLE `tbl_rbac_users` (
  `id` int(11) NOT NULL default '0',
  `handle` varchar(100) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `last_login` datetime default '0000-00-00 00:00:00',
  `is_active` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_sites`
--

CREATE TABLE `tbl_sites` (
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `address_1` varchar(255) default NULL,
  `address_2` varchar(255) default NULL,
  `town` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `postcode` varchar(25) default NULL,
  `telephone` varchar(50) default NULL,
  `county_id` int(11) default NULL,
  `country_id` int(11) default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_sites_company_id` (`company_id`),
  KEY `ix_tbl_sites_deleted` (`deleted`),
  KEY `tbl_sites_ibfk2` (`county_id`),
  KEY `tbl_sites_ibfk3` (`country_id`),
  KEY `ix_tbl_sites_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_sites_postcode` (`postcode`),
  CONSTRAINT `tbl_sites_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk2` FOREIGN KEY (`county_id`) REFERENCES `tbl_lkp_counties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk3` FOREIGN KEY (`country_id`) REFERENCES `tbl_lkp_countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_sites_seq`
--

CREATE TABLE `tbl_sites_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=31858 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_sites_shadow`
--

CREATE TABLE `tbl_sites_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `address_1` varchar(255) NOT NULL default '',
  `address_2` varchar(255) NOT NULL default '',
  `town` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `postcode` varchar(25) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `county_id` int(11) default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  `country_id` int(11) default NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tag_categories`
--

CREATE TABLE `tbl_tag_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tag_categories_seq`
--

CREATE TABLE `tbl_tag_categories_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tag_categories_shadow`
--

CREATE TABLE `tbl_tag_categories_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tags`
--

CREATE TABLE `tbl_tags` (
  `id` int(11) NOT NULL auto_increment,
  `value` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_tags_value` (`value`),
  KEY `ix_tbl_tags_category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5376 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tags_seq`
--

CREATE TABLE `tbl_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5378 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tags_shadow`
--

CREATE TABLE `tbl_tags_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `value` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
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
  CONSTRAINT `ix_tbl_team_nbms_ibfk1` FOREIGN KEY (`team_id`) REFERENCES `tbl_teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_team_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_team_nbms_seq`
--

CREATE TABLE `tbl_team_nbms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_teams`
--

CREATE TABLE `tbl_teams` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_teams_seq`
--

CREATE TABLE `tbl_teams_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristic_categories`
--

CREATE TABLE `tbl_tiered_characteristic_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristic_categories_seq`
--

CREATE TABLE `tbl_tiered_characteristic_categories_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristic_categories_shadow`
--

CREATE TABLE `tbl_tiered_characteristic_categories_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristics`
--

CREATE TABLE `tbl_tiered_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_tiered_characteristics_value` (`value`),
  KEY `ix_tbl_tiered_characteristics_category_id` (`category_id`),
  KEY `ix_tbl_tiered_characteristics_parent_id` (`parent_id`),
  CONSTRAINT `ix_tbl_tiered_characteristics_ibfk1` FOREIGN KEY (`category_id`) REFERENCES `tbl_tiered_characteristic_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=484 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristics_seq`
--

CREATE TABLE `tbl_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=495 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_tiered_characteristics_shadow`
--

CREATE TABLE `tbl_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `category_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_user_client_access`
--

CREATE TABLE `tbl_user_client_access` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_user_client_access_client_id` (`client_id`),
  KEY `ix_tbl_user_client_access_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_user_client_access_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_user_client_access_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_user_client_access_seq`
--

CREATE TABLE `tbl_user_client_access_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_user_client_access_shadow`
--

CREATE TABLE `tbl_user_client_access_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Temporary table structure for view `vw_calendar_information_requests`
--

/*!50001 CREATE TABLE `vw_calendar_information_requests` (
  `id` int(11),
  `post_initiative_id` int(11),
  `status_id` int(11),
  `date` datetime,
  `reminder_date` datetime,
  `notes` varchar(255),
  `created_at` datetime,
  `created_by` int(11),
  `communication_id` int(11),
  `type_id` int(11),
  `comm_type_id` int(11),
  `client_id` int(11),
  `client` varchar(100),
  `company_id` int(11),
  `company` varchar(255)
) */;

--
-- Temporary table structure for view `vw_calendar_meetings`
--

/*!50001 CREATE TABLE `vw_calendar_meetings` (
  `id` int(11),
  `post_initiative_id` int(11),
  `communication_id` int(11),
  `is_current` tinyint(1),
  `status_id` int(11),
  `type_id` int(11),
  `date` datetime,
  `reminder_date` datetime,
  `notes` varchar(255),
  `created_at` datetime,
  `created_by` int(11),
  `client_id` int(11),
  `client` varchar(100),
  `company_id` int(11),
  `company` varchar(255),
  `post_id` int(11)
) */;

--
-- Temporary table structure for view `vw_client_initiatives`
--

/*!50001 CREATE TABLE `vw_client_initiatives` (
  `client_id` int(11),
  `client_name` varchar(100),
  `campaign_id` int(11),
  `initiative_id` int(11),
  `initiative_name` varchar(255)
) */;

--
-- Temporary table structure for view `vw_communication_max_date_by_post_initiative_id`
--

/*!50001 CREATE TABLE `vw_communication_max_date_by_post_initiative_id` (
  `max(communication_date)` datetime,
  `post_initiative_id` int(11)
) */;

--
-- Temporary table structure for view `vw_communication_max_id_by_post_initiative_id`
--

/*!50001 CREATE TABLE `vw_communication_max_id_by_post_initiative_id` (
  `max(id)` int(11),
  `max(communication_date)` datetime,
  `post_initiative_id` int(11)
) */;

--
-- Temporary table structure for view `vw_companies`
--

/*!50001 CREATE TABLE `vw_companies` (
  `id` int(11),
  `name` varchar(255),
  `website` varchar(255),
  `telephone` varchar(50),
  `deleted` tinyint(1)
) */;

--
-- Temporary table structure for view `vw_companies_sites`
--

/*!50001 CREATE TABLE `vw_companies_sites` (
  `id` int(11),
  `name` varchar(255),
  `website` varchar(255),
  `telephone` varchar(50),
  `deleted` tinyint(1),
  `site_id` int(11),
  `site_name` varchar(255),
  `address_1` varchar(255),
  `address_2` varchar(255),
  `town` varchar(50),
  `city` varchar(50),
  `postcode` varchar(25),
  `site_telephone` varchar(50),
  `county_id` int(11),
  `county` varchar(50),
  `country_id` int(11),
  `country` varchar(50)
) */;

--
-- Temporary table structure for view `vw_contacts`
--

/*!50001 CREATE TABLE `vw_contacts` (
  `id` int(11),
  `post_id` int(11),
  `title` varchar(25),
  `first_name` varchar(50),
  `surname` varchar(50),
  `deleted` tinyint(1),
  `email` varchar(100),
  `telephone_mobile` varchar(100),
  `full_name` varchar(100)
) */;

--
-- Temporary table structure for view `vw_events`
--

/*!50001 CREATE TABLE `vw_events` (
  `id` int(11),
  `subject` varchar(100),
  `notes` text,
  `date` date,
  `reminder_date` datetime,
  `user_id` int(11),
  `type_id` int(11) unsigned,
  `type` varchar(100)
) */;

--
-- Temporary table structure for view `vw_nbm_meeting_count_between_dates`
--

/*!50001 CREATE TABLE `vw_nbm_meeting_count_between_dates` (
  `max(id)` int(11),
  `post_initiative_id` int(11)
) */;

--
-- Temporary table structure for view `vw_post_communication_stats`
--

/*!50001 CREATE TABLE `vw_post_communication_stats` (
  `post_id` int(11),
  `communication_count` decimal(32,0),
  `effective_count` decimal(32,0)
) */;

--
-- Temporary table structure for view `vw_post_communication_stats_1`
--

/*!50001 CREATE TABLE `vw_post_communication_stats_1` (
  `post_id` int(11),
  `communication_count` bigint(21),
  `effective_count` decimal(25,0)
) */;

--
-- Temporary table structure for view `vw_post_communication_stats_base`
--

/*!50001 CREATE TABLE `vw_post_communication_stats_base` (
  `post_id` int(11),
  `comm_count` int(1),
  `eff_count` int(1)
) */;

--
-- Temporary table structure for view `vw_posts`
--

/*!50001 CREATE TABLE `vw_posts` (
  `id` int(11),
  `company_id` int(11),
  `job_title` varchar(255),
  `propensity` int(11),
  `notes` text,
  `deleted` tinyint(1),
  `telephone_1` varchar(50),
  `telephone_2` varchar(50),
  `telephone_switchboard` varchar(50),
  `telephone_fax` varchar(50)
) */;

--
-- Temporary table structure for view `vw_posts_contacts`
--

/*!50001 CREATE TABLE `vw_posts_contacts` (
  `id` int(11),
  `company_id` int(11),
  `job_title` varchar(255),
  `propensity` int(11),
  `telephone_1` varchar(50),
  `telephone_2` varchar(50),
  `telephone_switchboard` varchar(50),
  `telephone_fax` varchar(50),
  `title` varchar(25),
  `first_name` varchar(50),
  `surname` varchar(50),
  `full_name` varchar(100),
  `telephone_mobile` varchar(100),
  `email` varchar(100),
  `notes` text
) */;

--
-- Temporary table structure for view `vw_sites`
--

/*!50001 CREATE TABLE `vw_sites` (
  `id` int(11),
  `company_id` int(11),
  `name` varchar(255),
  `address_1` varchar(255),
  `address_2` varchar(255),
  `town` varchar(50),
  `city` varchar(50),
  `postcode` varchar(25),
  `telephone` varchar(50),
  `county_id` int(11),
  `country_id` int(11),
  `deleted` tinyint(1)
) */;

--
-- Temporary table structure for view `vw_tags_project_ref`
--

/*!50001 CREATE TABLE `vw_tags_project_ref` (
  `post_initiative_id` int(11),
  `value` varchar(50)
) */;

--
-- Dumping routines for database 'alchemis_uat'
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO"*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `f_get_post_meeting_count`(var_post_id INT(11)) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
DECLARE my_result int(11) DEFAULT 0;
select count(*) into my_result FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id and m.is_current= 1;
RETURN my_result;
END */;;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO"*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `f_next_comm_date_period`(future_date DATETIME, no_of_months INT(11)) RETURNS char(100) CHARSET latin1
    NO SQL
BEGIN 
DECLARE day_count int(11) DEFAULT 0;
DECLARE day_result char(100) DEFAULT '';
DECLARE month_period int(11);
DECLARE benchmark_date DATETIME;
DECLARE benchmark_period int(11);
SET benchmark_date = DATE_ADD(now(), INTERVAL no_of_months MONTH);
SET benchmark_period = DATEDIFF(benchmark_date, now());
SET day_count = DATEDIFF(future_date, now());
IF day_count > benchmark_period THEN SET day_result = concat('> ' ,no_of_months , ' mth');
    ELSE SET day_result = concat(day_count, ' days') ;
END IF;
RETURN day_result;
END */;;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/;;
DELIMITER ;

--
-- Final view structure for view `vw_calendar_information_requests`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_calendar_information_requests` AS select `ir`.`id` AS `id`,`ir`.`post_initiative_id` AS `post_initiative_id`,`ir`.`status_id` AS `status_id`,`ir`.`date` AS `date`,`ir`.`reminder_date` AS `reminder_date`,`ir`.`notes` AS `notes`,`ir`.`created_at` AS `created_at`,`ir`.`created_by` AS `created_by`,`ir`.`communication_id` AS `communication_id`,`ir`.`type_id` AS `type_id`,`ir`.`comm_type_id` AS `comm_type_id`,`cli`.`id` AS `client_id`,`cli`.`name` AS `client`,`c`.`id` AS `company_id`,`c`.`name` AS `company` from ((((((`tbl_information_requests` `ir` join `tbl_post_initiatives` `pi` on((`ir`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) join `tbl_companies` `c` on((`p`.`company_id` = `c`.`id`))) join `tbl_initiatives` `i` on((`pi`.`initiative_id` = `i`.`id`))) join `tbl_campaigns` `cam` on((`i`.`campaign_id` = `cam`.`id`))) join `tbl_clients` `cli` on((`cam`.`client_id` = `cli`.`id`))) */;

--
-- Final view structure for view `vw_calendar_meetings`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_calendar_meetings` AS select `m`.`id` AS `id`,`m`.`post_initiative_id` AS `post_initiative_id`,`m`.`communication_id` AS `communication_id`,`m`.`is_current` AS `is_current`,`m`.`status_id` AS `status_id`,`m`.`type_id` AS `type_id`,`m`.`date` AS `date`,`m`.`reminder_date` AS `reminder_date`,`m`.`notes` AS `notes`,`m`.`created_at` AS `created_at`,`m`.`created_by` AS `created_by`,`cli`.`id` AS `client_id`,`cli`.`name` AS `client`,`c`.`id` AS `company_id`,`c`.`name` AS `company`,`pi`.`post_id` AS `post_id` from ((((((`tbl_meetings` `m` join `tbl_post_initiatives` `pi` on((`m`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) join `tbl_companies` `c` on((`p`.`company_id` = `c`.`id`))) join `tbl_initiatives` `i` on((`pi`.`initiative_id` = `i`.`id`))) join `tbl_campaigns` `cam` on((`i`.`campaign_id` = `cam`.`id`))) join `tbl_clients` `cli` on((`cam`.`client_id` = `cli`.`id`))) */;

--
-- Final view structure for view `vw_client_initiatives`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_client_initiatives` AS select `c`.`id` AS `client_id`,`c`.`name` AS `client_name`,`cm`.`id` AS `campaign_id`,`i`.`id` AS `initiative_id`,`i`.`name` AS `initiative_name` from ((`tbl_clients` `c` join `tbl_campaigns` `cm` on((`c`.`id` = `cm`.`client_id`))) left join `tbl_initiatives` `i` on((`cm`.`id` = `i`.`campaign_id`))) */;

--
-- Final view structure for view `vw_communication_max_date_by_post_initiative_id`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_communication_max_date_by_post_initiative_id` AS select max(`com`.`communication_date`) AS `max(communication_date)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;

--
-- Final view structure for view `vw_communication_max_id_by_post_initiative_id`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_communication_max_id_by_post_initiative_id` AS select max(`com`.`id`) AS `max(id)`,max(`com`.`communication_date`) AS `max(communication_date)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;

--
-- Final view structure for view `vw_companies`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_companies` AS select `tbl_companies`.`id` AS `id`,`tbl_companies`.`name` AS `name`,`tbl_companies`.`website` AS `website`,`tbl_companies`.`telephone` AS `telephone`,`tbl_companies`.`deleted` AS `deleted` from `tbl_companies` where (`tbl_companies`.`deleted` = 0) */;

--
-- Final view structure for view `vw_companies_sites`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_companies_sites` AS select `c`.`id` AS `id`,`c`.`name` AS `name`,`c`.`website` AS `website`,`c`.`telephone` AS `telephone`,`c`.`deleted` AS `deleted`,`s`.`id` AS `site_id`,`s`.`name` AS `site_name`,`s`.`address_1` AS `address_1`,`s`.`address_2` AS `address_2`,`s`.`town` AS `town`,`s`.`city` AS `city`,`s`.`postcode` AS `postcode`,`s`.`telephone` AS `site_telephone`,`s`.`county_id` AS `county_id`,`lkp_county`.`name` AS `county`,`s`.`country_id` AS `country_id`,`lkp_country`.`name` AS `country` from (((`tbl_companies` `c` left join `tbl_sites` `s` on((`c`.`id` = `s`.`company_id`))) left join `tbl_lkp_counties` `lkp_county` on((`lkp_county`.`id` = `s`.`county_id`))) left join `tbl_lkp_countries` `lkp_country` on((`lkp_country`.`id` = `s`.`country_id`))) where (`c`.`deleted` = 0) */;

--
-- Final view structure for view `vw_contacts`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_contacts` AS select `tbl_contacts`.`id` AS `id`,`tbl_contacts`.`post_id` AS `post_id`,`tbl_contacts`.`title` AS `title`,`tbl_contacts`.`first_name` AS `first_name`,`tbl_contacts`.`surname` AS `surname`,`tbl_contacts`.`deleted` AS `deleted`,`tbl_contacts`.`email` AS `email`,`tbl_contacts`.`telephone_mobile` AS `telephone_mobile`,`tbl_contacts`.`full_name` AS `full_name` from `tbl_contacts` where (`tbl_contacts`.`deleted` = 0) */;

--
-- Final view structure for view `vw_events`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_events` AS select `e`.`id` AS `id`,`e`.`subject` AS `subject`,`e`.`notes` AS `notes`,`e`.`date` AS `date`,`e`.`reminder_date` AS `reminder_date`,`e`.`user_id` AS `user_id`,`e`.`type_id` AS `type_id`,`t`.`name` AS `type` from (`tbl_events` `e` join `tbl_lkp_event_types` `t` on((`e`.`type_id` = `t`.`id`))) */;

--
-- Final view structure for view `vw_nbm_meeting_count_between_dates`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_nbm_meeting_count_between_dates` AS select max(`com`.`id`) AS `max(id)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;

--
-- Final view structure for view `vw_post_communication_stats`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_post_communication_stats` AS select `vw_post_communication_stats_base`.`post_id` AS `post_id`,sum(`vw_post_communication_stats_base`.`comm_count`) AS `communication_count`,sum(`vw_post_communication_stats_base`.`eff_count`) AS `effective_count` from `vw_post_communication_stats_base` group by `vw_post_communication_stats_base`.`post_id` */;

--
-- Final view structure for view `vw_post_communication_stats_1`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_post_communication_stats_1` AS select `pi`.`post_id` AS `post_id`,count(`comm`.`id`) AS `communication_count`,sum(`comm`.`is_effective`) AS `effective_count` from (`tbl_communications` `comm` join `tbl_post_initiatives` `pi` on((`comm`.`post_initiative_id` = `pi`.`id`))) group by `pi`.`post_id` */;

--
-- Final view structure for view `vw_post_communication_stats_base`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_post_communication_stats_base` AS select `p`.`id` AS `post_id`,1 AS `comm_count`,(case `comm`.`effective` when _latin1'effective' then 1 else 0 end) AS `eff_count` from ((`tbl_communications` `comm` join `tbl_post_initiatives` `pi` on((`comm`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) */;

--
-- Final view structure for view `vw_posts`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_posts` AS select `tbl_posts`.`id` AS `id`,`tbl_posts`.`company_id` AS `company_id`,`tbl_posts`.`job_title` AS `job_title`,`tbl_posts`.`propensity` AS `propensity`,`tbl_posts`.`notes` AS `notes`,`tbl_posts`.`deleted` AS `deleted`,`tbl_posts`.`telephone_1` AS `telephone_1`,`tbl_posts`.`telephone_2` AS `telephone_2`,`tbl_posts`.`telephone_switchboard` AS `telephone_switchboard`,`tbl_posts`.`telephone_fax` AS `telephone_fax` from `tbl_posts` where (`tbl_posts`.`deleted` = 0) */;

--
-- Final view structure for view `vw_posts_contacts`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_posts_contacts` AS select `p`.`id` AS `id`,`p`.`company_id` AS `company_id`,`p`.`job_title` AS `job_title`,`p`.`propensity` AS `propensity`,`p`.`telephone_1` AS `telephone_1`,`p`.`telephone_2` AS `telephone_2`,`p`.`telephone_switchboard` AS `telephone_switchboard`,`p`.`telephone_fax` AS `telephone_fax`,`c`.`title` AS `title`,`c`.`first_name` AS `first_name`,`c`.`surname` AS `surname`,`c`.`full_name` AS `full_name`,`c`.`telephone_mobile` AS `telephone_mobile`,`c`.`email` AS `email`,`p`.`notes` AS `notes` from (`vw_posts` `p` left join `vw_contacts` `c` on((`p`.`id` = `c`.`post_id`))) */;

--
-- Final view structure for view `vw_sites`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sites` AS select `tbl_sites`.`id` AS `id`,`tbl_sites`.`company_id` AS `company_id`,`tbl_sites`.`name` AS `name`,`tbl_sites`.`address_1` AS `address_1`,`tbl_sites`.`address_2` AS `address_2`,`tbl_sites`.`town` AS `town`,`tbl_sites`.`city` AS `city`,`tbl_sites`.`postcode` AS `postcode`,`tbl_sites`.`telephone` AS `telephone`,`tbl_sites`.`county_id` AS `county_id`,`tbl_sites`.`country_id` AS `country_id`,`tbl_sites`.`deleted` AS `deleted` from `tbl_sites` where (`tbl_sites`.`deleted` = 0) */;

--
-- Final view structure for view `vw_tags_project_ref`
--

/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_tags_project_ref` AS select `pit`.`post_initiative_id` AS `post_initiative_id`,`t`.`value` AS `value` from ((`tbl_post_initiative_tags` `pit` join `tbl_tags` `t` on((`pit`.`tag_id` = `t`.`id`))) join `tbl_tag_categories` `tc` on((`t`.`category_id` = `tc`.`id`))) where (`tc`.`id` = 3) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-11-01 11:06:41
