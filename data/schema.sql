-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: alchemis
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.12.04.1-log

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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_action_resources`
--

DROP TABLE IF EXISTS `tbl_action_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_action_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `action_id` (`action_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `tbl_action_resources_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `tbl_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_action_resources_ibfk_3` FOREIGN KEY (`resource_id`) REFERENCES `tbl_lkp_action_resource_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39910 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_actions`
--

DROP TABLE IF EXISTS `tbl_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) DEFAULT '',
  `notes` text,
  `due_date` datetime DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `actioned_by_client` tinyint(1) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `information_request_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `communication_type_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `communication_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_actions_user_id` (`user_id`),
  KEY `ix_tbl_actions_meeting_id` (`meeting_id`),
  KEY `ix_tbl_actions_information_request_id` (`information_request_id`),
  KEY `ix_tbl_actions_type_id` (`type_id`),
  KEY `ix_tbl_actions_communication_type_id` (`communication_type_id`),
  KEY `ix_tbl_actions_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `ix_tbl_actions_ibfk1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_actions_ibfk_2` FOREIGN KEY (`meeting_id`) REFERENCES `tbl_meetings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_actions_ibfk_3` FOREIGN KEY (`information_request_id`) REFERENCES `tbl_information_requests` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_actions_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_action_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_actions_ibfk_5` FOREIGN KEY (`communication_type_id`) REFERENCES `tbl_lkp_action_communication_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_actions_ibfk_6` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38628 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_actions_seq`
--

DROP TABLE IF EXISTS `tbl_actions_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_actions_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=38628 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_bank_holidays`
--

DROP TABLE IF EXISTS `tbl_bank_holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_bank_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_batch_run`
--

DROP TABLE IF EXISTS `tbl_batch_run`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_batch_run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_batch_run_type` (`type`),
  KEY `ix_tbl_batch_run_start` (`start`),
  KEY `ix_tbl_batch_run_end` (`end`)
) ENGINE=InnoDB AUTO_INCREMENT=2510 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_batch_run_seq`
--

DROP TABLE IF EXISTS `tbl_batch_run_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_batch_run_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2510 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_characteristics`
--

DROP TABLE IF EXISTS `tbl_campaign_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_characteristics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `characteristic_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_companies_do_not_call`
--

DROP TABLE IF EXISTS `tbl_campaign_companies_do_not_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_companies_do_not_call` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_companies_do_not_call_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_companies_do_not_call_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_campaign_companies_do_not_call_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_companies_do_not_call_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23600 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_companies_do_not_call_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_companies_do_not_call_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_companies_do_not_call_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=23600 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_companies_do_not_call_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_companies_do_not_call_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_companies_do_not_call_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_disciplines`
--

DROP TABLE IF EXISTS `tbl_campaign_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_disciplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `tiered_characteristic_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_disciplines_campaign_id` (`campaign_id`),
  KEY `ix_tbl_tbl_campaign_disciplines_tiered_characteristic_id` (`tiered_characteristic_id`),
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1129 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_disciplines_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_disciplines_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_disciplines_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=1129 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_disciplines_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_disciplines_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_disciplines_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `tiered_characteristic_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbm_targets`
--

DROP TABLE IF EXISTS `tbl_campaign_nbm_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbm_targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `planned_days` int(11) NOT NULL DEFAULT '0',
  `project_management_days` decimal(3,2) NOT NULL DEFAULT '0.00',
  `effectives` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(11) NOT NULL DEFAULT '0',
  `meetings_set_imperative` int(11) NOT NULL DEFAULT '0',
  `meetings_attended` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_nbm_targets_user_id` (`user_id`),
  KEY `ix_tbl_campaign_nbm_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_nbm_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbm_targets_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86932 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbm_targets_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_nbm_targets_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbm_targets_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=86932 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbm_targets_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_nbm_targets_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbm_targets_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `planned_days` int(11) NOT NULL DEFAULT '0',
  `project_management_days` int(11) NOT NULL DEFAULT '0',
  `effectives` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(11) NOT NULL DEFAULT '0',
  `meetings_set_imperative` int(11) NOT NULL DEFAULT '0',
  `meetings_attended` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbms`
--

DROP TABLE IF EXISTS `tbl_campaign_nbms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_alias` varchar(255) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `is_lead_nbm` tinyint(1) NOT NULL DEFAULT '0',
  `deactivated_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_nbms_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9564 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbms_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_nbms_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbms_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=9564 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_nbms_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_nbms_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_nbms_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_alias` varchar(255) NOT NULL,
  `is_lead_nbm` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_regions`
--

DROP TABLE IF EXISTS `tbl_campaign_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `region_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_regions_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_regions_region_id` (`region_id`),
  CONSTRAINT `ix_tbl_campaign_regions_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_regions_ibfk2` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_regions_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_regions_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_regions_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_regions_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_regions_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_regions_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `region_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_report_summaries`
--

DROP TABLE IF EXISTS `tbl_campaign_report_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_report_summaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_report_summaries_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_report_summaries_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_report_summaries_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_report_summaries_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_report_summaries_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_report_summaries_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_report_summaries_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_sectors`
--

DROP TABLE IF EXISTS `tbl_campaign_sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_sectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `tiered_characteristic_id` int(11) NOT NULL,
  `weighting` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_sectors_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_sectors_tiered_characteristic_id` (`tiered_characteristic_id`),
  CONSTRAINT `tbl_campaign_sectors_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_campaign_sectors_ibfk_2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3290 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_sectors_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_sectors_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_sectors_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3290 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_sectors_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_sectors_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_sectors_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL,
  `tiered_characteristic_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_setting`
--

DROP TABLE IF EXISTS `tbl_campaign_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_targets`
--

DROP TABLE IF EXISTS `tbl_campaign_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `calls` int(3) NOT NULL DEFAULT '0',
  `effectives` int(3) NOT NULL DEFAULT '0',
  `fee` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(3) NOT NULL DEFAULT '0',
  `meetings_attended` int(3) NOT NULL DEFAULT '0',
  `opportunities` int(3) NOT NULL DEFAULT '0',
  `wins` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14994 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_targets_seq`
--

DROP TABLE IF EXISTS `tbl_campaign_targets_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_targets_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=14994 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaign_targets_shadow`
--

DROP TABLE IF EXISTS `tbl_campaign_targets_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaign_targets_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `calls` int(3) NOT NULL DEFAULT '0',
  `effectives` int(3) NOT NULL DEFAULT '0',
  `fee` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(3) NOT NULL DEFAULT '0',
  `meetings_attended` int(3) NOT NULL DEFAULT '0',
  `opportunities` int(3) NOT NULL DEFAULT '0',
  `wins` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10567 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaigns`
--

DROP TABLE IF EXISTS `tbl_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `start_year_month` char(6) DEFAULT NULL,
  `end_year_month` char(6) DEFAULT NULL,
  `initial_fee` int(11) DEFAULT NULL,
  `current_fee` int(11) DEFAULT NULL,
  `contract_sent_date` datetime DEFAULT NULL,
  `contract_received_date` datetime DEFAULT NULL,
  `so_form_received_date` datetime DEFAULT NULL,
  `billing_terms_id` int(11) DEFAULT NULL,
  `payment_terms_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `minimum_duration` tinyint(2) unsigned DEFAULT '0',
  `notice_period` tinyint(2) unsigned DEFAULT '0',
  `notice_date` datetime DEFAULT NULL,
  `additional_terms_exist` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_campaign_client_id` (`client_id`),
  CONSTRAINT `ix_tbl_campaigns_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=906 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaigns_companies_do_not_call_seq`
--

DROP TABLE IF EXISTS `tbl_campaigns_companies_do_not_call_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaigns_companies_do_not_call_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaigns_seq`
--

DROP TABLE IF EXISTS `tbl_campaigns_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaigns_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=906 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_campaigns_shadow`
--

DROP TABLE IF EXISTS `tbl_campaigns_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_campaigns_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `start_year_month` char(6) NOT NULL DEFAULT '',
  `end_year_month` char(6) NOT NULL DEFAULT '',
  `initial_fee` int(11) DEFAULT NULL,
  `current_fee` int(11) DEFAULT NULL,
  `contract_sent_date` datetime DEFAULT NULL,
  `contract_received_date` datetime DEFAULT NULL,
  `so_form_received_date` datetime DEFAULT NULL,
  `billing_terms_id` int(11) DEFAULT NULL,
  `payment_terms_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `minimum_duration` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `notice_period` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `notice_date` datetime DEFAULT NULL,
  `additional_terms_exist` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristic_elements`
--

DROP TABLE IF EXISTS `tbl_characteristic_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristic_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `data_type` enum('boolean','date','text') NOT NULL DEFAULT 'text',
  `name` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `characteristic_id` (`characteristic_id`,`name`),
  KEY `ix_tbl_characteristic_elements_characteristic_id` (`characteristic_id`),
  CONSTRAINT `tbl_characteristic_elements_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristic_elements_seq`
--

DROP TABLE IF EXISTS `tbl_characteristic_elements_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristic_elements_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristic_elements_shadow`
--

DROP TABLE IF EXISTS `tbl_characteristic_elements_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristic_elements_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `data_type` enum('boolean','date','text') NOT NULL DEFAULT 'text',
  `name` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristics`
--

DROP TABLE IF EXISTS `tbl_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('company','post','post initiative') NOT NULL DEFAULT 'company',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `attributes` tinyint(1) NOT NULL DEFAULT '0',
  `options` tinyint(1) NOT NULL DEFAULT '0',
  `multiple_select` tinyint(1) NOT NULL DEFAULT '0',
  `data_type` enum('boolean','date','text') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_characteristics` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristics_seq`
--

DROP TABLE IF EXISTS `tbl_characteristics_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristics_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_characteristics_shadow`
--

DROP TABLE IF EXISTS `tbl_characteristics_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `type` enum('company','post','post initiative') NOT NULL DEFAULT 'company',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `attributes` tinyint(1) NOT NULL DEFAULT '0',
  `options` tinyint(1) NOT NULL DEFAULT '0',
  `multiple_select` tinyint(1) NOT NULL DEFAULT '0',
  `data_type` enum('boolean','date','text') DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_client_contacts`
--

DROP TABLE IF EXISTS `tbl_client_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_client_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `job_title` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_client_contacts_client_id` (`client_id`),
  CONSTRAINT `ix_tbl_client_contacts_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_client_contacts_seq`
--

DROP TABLE IF EXISTS `tbl_client_contacts_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_client_contacts_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_client_contacts_shadow`
--

DROP TABLE IF EXISTS `tbl_client_contacts_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_client_contacts_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `job_title` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_clients`
--

DROP TABLE IF EXISTS `tbl_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `address_1` varchar(50) DEFAULT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `address_3` varchar(50) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `postcode` varchar(25) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `financial_year_start` date DEFAULT NULL,
  `primary_contact_name` varchar(100) DEFAULT NULL,
  `primary_contact_job_title` varchar(100) DEFAULT NULL,
  `primary_contact_telephone` varchar(50) DEFAULT NULL,
  `primary_contact_email` varchar(255) DEFAULT NULL,
  `secondary_contact_name` varchar(100) DEFAULT NULL,
  `secondary_contact_job_title` varchar(100) DEFAULT NULL,
  `secondary_contact_telephone` varchar(50) DEFAULT NULL,
  `secondary_contact_email` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `telephone` varchar(50) DEFAULT NULL,
  `publish_diary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `tbl_clients_ibfk2` (`county_id`),
  KEY `tbl_clients_ibfk3` (`country_id`),
  KEY `is_current` (`is_current`)
) ENGINE=InnoDB AUTO_INCREMENT=906 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_clients_seq`
--

DROP TABLE IF EXISTS `tbl_clients_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_clients_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=906 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_clients_shadow`
--

DROP TABLE IF EXISTS `tbl_clients_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_clients_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `address_1` varchar(50) DEFAULT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `address_3` varchar(50) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `postcode` varchar(25) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `financial_year_start` date DEFAULT NULL,
  `primary_contact_name` varchar(100) DEFAULT NULL,
  `primary_contact_job_title` varchar(100) DEFAULT NULL,
  `primary_contact_telephone` varchar(50) DEFAULT NULL,
  `primary_contact_email` varchar(255) DEFAULT NULL,
  `secondary_contact_name` varchar(100) DEFAULT NULL,
  `secondary_contact_job_title` varchar(100) DEFAULT NULL,
  `secondary_contact_telephone` varchar(50) DEFAULT NULL,
  `secondary_contact_email` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `telephone` varchar(50) DEFAULT NULL,
  `publish_diary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_cm_campaign`
--

DROP TABLE IF EXISTS `tbl_cm_campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_cm_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `cm_name` varchar(255) NOT NULL,
  `cm_id` varchar(255) NOT NULL,
  `total_recipients` int(11) DEFAULT NULL,
  `processed` tinyint(4) DEFAULT '0',
  `last_stats_import` datetime DEFAULT NULL,
  `stats_updated_opens` int(11) DEFAULT NULL,
  `stats_updated_subscriberClick` int(11) DEFAULT NULL,
  `tag_open_id` int(11) DEFAULT NULL,
  `tag_click_id` int(11) DEFAULT NULL,
  `filter_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_cm_campaign_seq`
--

DROP TABLE IF EXISTS `tbl_cm_campaign_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_cm_campaign_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_cm_campaign_shadow`
--

DROP TABLE IF EXISTS `tbl_cm_campaign_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_cm_campaign_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `cm_name` varchar(255) NOT NULL,
  `cm_id` varchar(255) NOT NULL,
  `total_recipients` int(11) DEFAULT NULL,
  `processed` tinyint(4) DEFAULT '0',
  `last_stats_import` datetime DEFAULT NULL,
  `stats_updated_opens` int(11) DEFAULT NULL,
  `stats_updated_subscriberClick` int(11) DEFAULT NULL,
  `tag_open_id` int(11) DEFAULT NULL,
  `tag_click_id` int(11) DEFAULT NULL,
  `filter_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_communication_attachments`
--

DROP TABLE IF EXISTS `tbl_communication_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_communication_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `communication_id` int(11) NOT NULL DEFAULT '0',
  `document_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_communication_attachments_comunication_id` (`communication_id`),
  KEY `ix_tbl_communication_attachments_document_id` (`document_id`),
  CONSTRAINT `ix_tbl_communication_attachments_ibfk1` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communication_attachments_ibfk2` FOREIGN KEY (`document_id`) REFERENCES `tbl_documents` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_communication_attachments_seq`
--

DROP TABLE IF EXISTS `tbl_communication_attachments_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_communication_attachments_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_communications`
--

DROP TABLE IF EXISTS `tbl_communications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_communications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lead_source_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `next_action_by` int(11) NOT NULL DEFAULT '1',
  `old_status` varchar(50) DEFAULT NULL,
  `communication_date` datetime NOT NULL,
  `direction` enum('out','in') NOT NULL DEFAULT 'out',
  `effective` enum('effective','non-effective') NOT NULL DEFAULT 'non-effective',
  `targeting_id` int(11) DEFAULT NULL,
  `receptiveness_id` int(11) DEFAULT NULL,
  `decision_maker_type_id` int(11) DEFAULT NULL,
  `next_communication_date` datetime DEFAULT NULL,
  `next_communication_date_reason_id` int(11) DEFAULT NULL,
  `comments` text,
  `note_id` int(11) DEFAULT NULL,
  `ote` tinyint(1) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL,
  `is_effective` tinyint(1) NOT NULL DEFAULT '0',
  `has_attachment` tinyint(1) DEFAULT '0',
  `priority_callback` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
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
  KEY `ix_tbl_communications_ote` (`ote`),
  CONSTRAINT `ix_tbl_communications_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_communication_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk3` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk4` FOREIGN KEY (`targeting_id`) REFERENCES `tbl_lkp_communication_targeting` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk5` FOREIGN KEY (`receptiveness_id`) REFERENCES `tbl_lkp_communication_receptiveness` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk6` FOREIGN KEY (`decision_maker_type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk7` FOREIGN KEY (`note_id`) REFERENCES `tbl_post_initiative_notes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk8` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2244632 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_communications_seq`
--

DROP TABLE IF EXISTS `tbl_communications_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_communications_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2244633 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_communications_shadow`
--

DROP TABLE IF EXISTS `tbl_communications_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_communications_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `communication_date` datetime NOT NULL,
  `direction` enum('out','in') NOT NULL DEFAULT 'out',
  `type` enum('effective','non-effective') NOT NULL DEFAULT 'non-effective',
  `targeting` int(11) NOT NULL DEFAULT '0',
  `receptiveness` int(11) NOT NULL DEFAULT '0',
  `decision_maker_type_id` int(11) DEFAULT NULL,
  `next_communication_date` datetime DEFAULT NULL,
  `next_communication_date_reason_id` int(11) DEFAULT NULL,
  `comments` text,
  `notes` text,
  `lead_source_id` int(11) DEFAULT NULL,
  `next_action_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_companies`
--

DROP TABLE IF EXISTS `tbl_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_companies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `website` varchar(255) DEFAULT '',
  `telephone` varchar(50) DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `telephone_tps` tinyint(1) DEFAULT '0',
  `dedupe_company_name` varchar(150) DEFAULT NULL,
  `dedupe_company_match_1` varchar(150) DEFAULT NULL,
  `dedupe_company_match_2` varchar(150) DEFAULT NULL,
  `dedupe_company_match_3` varchar(150) DEFAULT NULL,
  `parent_company_id` int(11) DEFAULT NULL,
  `soft_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_companies_deleted` (`deleted`),
  KEY `ix_tbl_companies_name` (`name`),
  KEY `ix_tbl_companies_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_companies_telephone` (`telephone`),
  KEY `ix_tbl_companies_website` (`website`),
  KEY `ix_tbl_companies_dedupe_company_name` (`dedupe_company_name`),
  KEY `ix_tbl_companies_dedupe_company_match_1` (`dedupe_company_match_1`),
  KEY `ix_tbl_companies_dedupe_company_match_2` (`dedupe_company_match_2`),
  KEY `ix_tbl_companies_dedupe_company_match_3` (`dedupe_company_match_3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_companies_seq`
--

DROP TABLE IF EXISTS `tbl_companies_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_companies_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=92095 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_companies_shadow`
--

DROP TABLE IF EXISTS `tbl_companies_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_companies_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `website` varchar(255) DEFAULT '',
  `telephone` varchar(50) DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `telephone_tps` tinyint(1) DEFAULT '0',
  `dedupe_company_name` varchar(150) DEFAULT NULL,
  `dedupe_company_match_1` varchar(150) DEFAULT NULL,
  `dedupe_company_match_2` varchar(150) DEFAULT NULL,
  `dedupe_company_match_3` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45698 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_duplicates`
--

DROP TABLE IF EXISTS `tbl_company_duplicates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_duplicates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `merge_into` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_notes`
--

DROP TABLE IF EXISTS `tbl_company_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_company_notes_company_id` (`company_id`),
  KEY `ix_tbl_company_notes_created_at` (`created_at`),
  KEY `ix_tbl_company_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_company_notes_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6541 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_notes_seq`
--

DROP TABLE IF EXISTS `tbl_company_notes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_notes_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6541 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_notes_shadow`
--

DROP TABLE IF EXISTS `tbl_company_notes_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_notes_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tags`
--

DROP TABLE IF EXISTS `tbl_company_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`company_id`,`tag_id`),
  KEY `ix_tbl_company_tags_company_id` (`company_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_company_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_tags_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=107302 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tags_seq`
--

DROP TABLE IF EXISTS `tbl_company_tags_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tags_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=107302 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tags_shadow`
--

DROP TABLE IF EXISTS `tbl_company_tags_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tags_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tiered_characteristics`
--

DROP TABLE IF EXISTS `tbl_company_tiered_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tiered_characteristics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`company_id`,`tiered_characteristic_id`),
  KEY `ix_tbl_company_tiered_characteristics_company_id` (`company_id`),
  KEY `ix_tbl_company_tiered_characteristics_tiered_characteristic_id` (`tiered_characteristic_id`),
  KEY `ix_tbl_company_tiered_characteristics_tier` (`tier`),
  CONSTRAINT `ix_tbl_company_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tiered_characteristics_seq`
--

DROP TABLE IF EXISTS `tbl_company_tiered_characteristics_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tiered_characteristics_shadow`
--

DROP TABLE IF EXISTS `tbl_company_tiered_characteristics_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_company_tokens`
--

DROP TABLE IF EXISTS `tbl_company_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_company_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `tokens` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `now` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `previous` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70190 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_configuration`
--

DROP TABLE IF EXISTS `tbl_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_configuration` (
  `property` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`property`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_contacts`
--

DROP TABLE IF EXISTS `tbl_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_contacts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(25) DEFAULT '',
  `first_name` varchar(50) DEFAULT '',
  `surname` varchar(50) DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT '',
  `telephone_mobile` varchar(100) DEFAULT '',
  `linked_in` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `dedupe_contact_first_name` varchar(50) DEFAULT NULL,
  `dedupe_contact_surname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_contacts_post_id` (`post_id`),
  KEY `ix_tbl_contacts_deleted` (`deleted`),
  KEY `ix_tbl_contacts_first_name` (`first_name`),
  KEY `ix_tbl_contacts_surname` (`surname`),
  KEY `ix_tbl_contacts_full_name` (`full_name`),
  KEY `ix_tbl_contacts_dedupe_contact_first_name` (`dedupe_contact_first_name`),
  KEY `ix_tbl_contacts_dedupe_contact_surname` (`dedupe_contact_surname`),
  CONSTRAINT `tbl_posts_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_contacts_seq`
--

DROP TABLE IF EXISTS `tbl_contacts_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_contacts_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=378659 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_contacts_shadow`
--

DROP TABLE IF EXISTS `tbl_contacts_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_contacts_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(25) DEFAULT '',
  `first_name` varchar(50) DEFAULT '',
  `surname` varchar(50) DEFAULT '',
  `telephone` varchar(50) DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT '',
  `telephone_mobile` varchar(100) DEFAULT '',
  `full_name` varchar(100) DEFAULT NULL,
  `dedupe_contact_first_name` varchar(50) DEFAULT NULL,
  `dedupe_contact_surname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_data_statistics`
--

DROP TABLE IF EXISTS `tbl_data_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_data_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) DEFAULT '',
  `period_year` int(11) DEFAULT '0',
  `period_quarter` int(11) DEFAULT '0',
  `campaign_current_month` int(11) NOT NULL DEFAULT '0',
  `campaign_monthly_fee` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_set_target` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_set_target_to_date` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_set_imperative` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_set_imperative_to_date` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_set_count_to_date` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_category_attended_target` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_category_attended_target_to_date` int(11) NOT NULL DEFAULT '0',
  `campaign_meeting_category_attended_count_to_date` int(11) NOT NULL DEFAULT '0',
  `call_count` int(11) NOT NULL DEFAULT '0',
  `call_effective_count` int(11) NOT NULL DEFAULT '0',
  `call_effective_target` int(11) NOT NULL DEFAULT '0',
  `call_effective_target_to_date` int(11) NOT NULL DEFAULT '0',
  `call_effective_count_to_date` int(11) NOT NULL DEFAULT '0',
  `call_fresh_effective_count` int(11) NOT NULL DEFAULT '0',
  `call_fresh_effective_converted_count` int(11) NOT NULL DEFAULT '0',
  `call_back_effective_count` int(11) NOT NULL DEFAULT '0',
  `call_back_effective_converted_count` int(11) NOT NULL DEFAULT '0',
  `call_ote_count` int(11) NOT NULL DEFAULT '0',
  `call_access_rate` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `meeting_set_count` int(11) NOT NULL DEFAULT '0',
  `meeting_time_lag_0_3` int(11) NOT NULL DEFAULT '0',
  `meeting_time_lag_3_5` int(11) NOT NULL DEFAULT '0',
  `meeting_time_lag_5_7` int(11) NOT NULL DEFAULT '0',
  `meeting_time_lag_7_` int(11) NOT NULL DEFAULT '0',
  `meeting_in_diary_this_month_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_unknown_count` int(11) NOT NULL DEFAULT '0',
  `meeting_attended_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_attended_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_attended_rate` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `win_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_cancelled_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_tbr_count` int(11) NOT NULL DEFAULT '0',
  `information_request_count` int(11) NOT NULL DEFAULT '0',
  `information_request_pending_count` int(11) NOT NULL DEFAULT '0',
  `information_request_failed_count` int(11) NOT NULL DEFAULT '0',
  `information_request_converted_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_data_statistics_campaign_id` (`campaign_id`),
  KEY `ix_tbl_data_statistics_user_id` (`user_id`),
  KEY `ix_tbl_data_statistics_year_month` (`year_month`)
) ENGINE=InnoDB AUTO_INCREMENT=79390 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_data_statistics_daily`
--

DROP TABLE IF EXISTS `tbl_data_statistics_daily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_data_statistics_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_key` varchar(50) NOT NULL DEFAULT '',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `call_count` int(11) NOT NULL DEFAULT '0',
  `call_effective_count` int(11) NOT NULL DEFAULT '0',
  `call_ote_count` int(11) NOT NULL DEFAULT '0',
  `meeting_set_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_unknown_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_attended_count_by_meeting_date` int(11) NOT NULL DEFAULT '0',
  `meeting_attended_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_attended_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_cancelled_count` int(11) NOT NULL DEFAULT '0',
  `meeting_category_tbr_count` int(11) NOT NULL DEFAULT '0',
  `information_request_count` int(11) NOT NULL DEFAULT '0',
  `information_request_converted_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_data_statistics_daily_row_key` (`row_key`),
  KEY `ix_tbl_data_statistics_daily_campaign_id` (`campaign_id`),
  KEY `ix_tbl_data_statistics_daily_user_id` (`user_id`),
  KEY `ix_tbl_data_statistics_daily_date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=11491013 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_data_statistics_run`
--

DROP TABLE IF EXISTS `tbl_data_statistics_run`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_data_statistics_run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_data_statistics_run_start` (`start`),
  KEY `ix_tbl_data_statistics_run_end` (`end`)
) ENGINE=InnoDB AUTO_INCREMENT=2591 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_data_statistics_run_seq`
--

DROP TABLE IF EXISTS `tbl_data_statistics_run_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_data_statistics_run_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2590 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dedupe_additions`
--

DROP TABLE IF EXISTS `tbl_dedupe_additions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dedupe_additions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dedupe_match`
--

DROP TABLE IF EXISTS `tbl_dedupe_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dedupe_match` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4013 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dedupe_mismatch`
--

DROP TABLE IF EXISTS `tbl_dedupe_mismatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dedupe_mismatch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `companies` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23096 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dedupe_parent_companies`
--

DROP TABLE IF EXISTS `tbl_dedupe_parent_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dedupe_parent_companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_ids` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_dedupe_status`
--

DROP TABLE IF EXISTS `tbl_dedupe_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dedupe_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `runtime` float(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_documents`
--

DROP TABLE IF EXISTS `tbl_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `filename` mediumtext NOT NULL,
  `description` text,
  `size` bigint(20) NOT NULL DEFAULT '0',
  `mime_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_documents_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_documents_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1444 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_documents_seq`
--

DROP TABLE IF EXISTS `tbl_documents_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_documents_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=1444 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_events`
--

DROP TABLE IF EXISTS `tbl_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `date` date DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) DEFAULT NULL,
  `day_part` decimal(3,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_events_user_id` (`user_id`),
  KEY `ix_tbl_events_client_id` (`client_id`),
  KEY `ix_tbl_events_type_id` (`type_id`),
  CONSTRAINT `ix_tbl_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_events_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_event_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_events_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4054 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_events_seq`
--

DROP TABLE IF EXISTS `tbl_events_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_events_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4054 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_events_shadow`
--

DROP TABLE IF EXISTS `tbl_events_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_events_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `date` date DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_exclude`
--

DROP TABLE IF EXISTS `tbl_exclude`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_exclude` (
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `row_key` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_lines`
--

DROP TABLE IF EXISTS `tbl_filter_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `params_display` text NOT NULL,
  `operator` varchar(50) DEFAULT NULL,
  `concatenator` varchar(10) DEFAULT NULL,
  `bracket_open` varchar(25) DEFAULT NULL,
  `bracket_close` varchar(25) DEFAULT NULL,
  `direction` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_filter_lines_filter_id` (`filter_id`),
  KEY `ix_tbl_filter_lines_direction` (`direction`)
) ENGINE=InnoDB AUTO_INCREMENT=242796 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_lines_seq`
--

DROP TABLE IF EXISTS `tbl_filter_lines_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_lines_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=242796 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_lines_shadow`
--

DROP TABLE IF EXISTS `tbl_filter_lines_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_lines_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `filter_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `field_name` varchar(50) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `params_display` text NOT NULL,
  `operator` varchar(50) DEFAULT NULL,
  `concatenator` varchar(10) DEFAULT NULL,
  `bracket_open` varchar(25) DEFAULT NULL,
  `bracket_close` varchar(25) DEFAULT NULL,
  `direction` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_results`
--

DROP TABLE IF EXISTS `tbl_filter_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `propensity_max` int(11) DEFAULT NULL,
  `propensity_avg` int(11) DEFAULT NULL,
  `propensity_min` int(11) DEFAULT NULL,
  `propensity_sum` int(11) DEFAULT NULL,
  `post_count` int(11) DEFAULT '0',
  `post_communication_count` int(11) DEFAULT '0',
  `post_effective_count` int(11) DEFAULT '0',
  `client_initiative_communication_count` int(11) DEFAULT '0',
  `client_initiative_effective_count` int(11) DEFAULT '0',
  `company_effective_count` int(11) DEFAULT '0',
  `company_communication_count` int(11) DEFAULT '0',
  `company_client_initiative_communication_count` int(11) DEFAULT '0',
  `company_client_initiative_effective_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_filters_results_filter_id` (`filter_id`),
  KEY `ix_tbl_filters_results_company_id` (`company_id`),
  KEY `ix_tbl_filters_results_post_id` (`post_id`),
  KEY `ix_tbl_filters_results_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `ix_tbl_filter_results_ibfk1` FOREIGN KEY (`filter_id`) REFERENCES `tbl_filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_filter_results_ibfk4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68321639 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_results_seq`
--

DROP TABLE IF EXISTS `tbl_filter_results_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_results_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=68147882 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filter_results_shadow`
--

DROP TABLE IF EXISTS `tbl_filter_results_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filter_results_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `filter_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `propensity_max` int(11) DEFAULT NULL,
  `propensity_avg` int(11) DEFAULT NULL,
  `propensity_min` int(11) DEFAULT NULL,
  `propensity_sum` int(11) DEFAULT NULL,
  `post_count` int(11) DEFAULT NULL,
  `post_communication_count` int(11) DEFAULT NULL,
  `post_effective_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filters`
--

DROP TABLE IF EXISTS `tbl_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `type_id` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `results_format` varchar(50) NOT NULL DEFAULT 'company',
  `company_count` int(11) DEFAULT '0',
  `post_count` int(11) DEFAULT '0',
  `communication_count` int(11) DEFAULT '0',
  `effective_count` int(11) DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `client_initiative_id` int(11) DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `is_report_source` tinyint(1) DEFAULT '0',
  `report_parameter_description` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_filters_name` (`name`),
  KEY `ix_tbl_filters_type_id` (`type_id`),
  KEY `ix_tbl_filters_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_filters_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15350 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filters_seq`
--

DROP TABLE IF EXISTS `tbl_filters_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filters_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=15350 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_filters_shadow`
--

DROP TABLE IF EXISTS `tbl_filters_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_filters_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `type_id` int(11) NOT NULL DEFAULT '1',
  `results_format` varchar(50) NOT NULL DEFAULT 'company',
  `company_count` int(11) DEFAULT '0',
  `post_count` int(11) DEFAULT '0',
  `communication_count` int(11) DEFAULT '0',
  `effective_count` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_import_company_matches`
--

DROP TABLE IF EXISTS `tbl_import_company_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_import_company_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `import_row_id` int(11) NOT NULL DEFAULT '0',
  `alchemis_company_id` int(11) NOT NULL DEFAULT '0',
  `match_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_import_company_matches_import_row_id` (`import_row_id`),
  KEY `ix_tbl_import_company_matches_alchemis_company_id` (`alchemis_company_id`),
  KEY `ix_tbl_import_company_matches_match_type` (`match_type`)
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_import_lines`
--

DROP TABLE IF EXISTS `tbl_import_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_import_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL DEFAULT '0',
  `alchemis_company_id` int(11) DEFAULT '0',
  `company_name` varchar(255) DEFAULT NULL,
  `company_telephone` varchar(50) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `site_address_1` varchar(255) DEFAULT NULL,
  `site_address_2` varchar(255) DEFAULT NULL,
  `site_town` varchar(50) DEFAULT NULL,
  `site_city` varchar(50) DEFAULT NULL,
  `site_postcode` varchar(25) DEFAULT NULL,
  `site_county` varchar(50) DEFAULT NULL,
  `site_county_id` int(11) DEFAULT '0',
  `site_country` varchar(50) DEFAULT NULL,
  `site_country_id` int(11) DEFAULT '0',
  `alchemis_post_id` int(11) DEFAULT '0',
  `post_job_title` varchar(255) DEFAULT NULL,
  `post_telephone` varchar(50) DEFAULT NULL,
  `contact_title` varchar(25) DEFAULT NULL,
  `contact_first_name` varchar(50) DEFAULT NULL,
  `contact_surname` varchar(50) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT '0',
  `sub_category_1` varchar(50) DEFAULT NULL,
  `sub_category_1_id` int(11) DEFAULT '0',
  `company_tag` varchar(50) DEFAULT NULL,
  `post_tag` varchar(50) DEFAULT NULL,
  `project_ref` varchar(50) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `client_note` text,
  `client_initiative_id` int(11) DEFAULT '0',
  `dedupe_company_name` varchar(255) DEFAULT NULL,
  `dedupe_company_match_1` varchar(150) DEFAULT NULL,
  `dedupe_company_match_2` varchar(150) DEFAULT NULL,
  `dedupe_company_match_3` varchar(150) DEFAULT NULL,
  `dedupe_contact_first_name` varchar(50) DEFAULT NULL,
  `dedupe_contact_surname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_import_lines_dedupe_company_name` (`dedupe_company_name`),
  KEY `ix_tbl_import_lines_dedupe_company_match_1` (`dedupe_company_match_1`),
  KEY `ix_tbl_import_lines_dedupe_company_match_2` (`dedupe_company_match_2`),
  KEY `ix_tbl_import_lines_dedupe_company_match_3` (`dedupe_company_match_3`),
  KEY `ix_tbl_import_lines_dedupe_contact_first_name` (`dedupe_contact_first_name`),
  KEY `ix_tbl_import_lines_dedupe_contact_surname` (`dedupe_contact_surname`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_import_lines_archive`
--

DROP TABLE IF EXISTS `tbl_import_lines_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_import_lines_archive` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL DEFAULT '0',
  `row_id` int(11) NOT NULL DEFAULT '0',
  `alchemis_company_id` int(11) DEFAULT '0',
  `company_name` varchar(255) DEFAULT NULL,
  `company_telephone` varchar(50) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `site_address_1` varchar(255) DEFAULT NULL,
  `site_address_2` varchar(255) DEFAULT NULL,
  `site_town` varchar(50) DEFAULT NULL,
  `site_city` varchar(50) DEFAULT NULL,
  `site_postcode` varchar(25) DEFAULT NULL,
  `site_county` varchar(50) DEFAULT NULL,
  `site_county_id` int(11) DEFAULT '0',
  `site_country` varchar(50) DEFAULT NULL,
  `site_country_id` int(11) DEFAULT '0',
  `alchemis_post_id` int(11) DEFAULT '0',
  `post_job_title` varchar(255) DEFAULT NULL,
  `post_telephone` varchar(50) DEFAULT NULL,
  `contact_title` varchar(25) DEFAULT NULL,
  `contact_first_name` varchar(50) DEFAULT NULL,
  `contact_surname` varchar(50) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT '0',
  `sub_category_1` varchar(150) DEFAULT NULL,
  `sub_category_1_id` int(11) DEFAULT '0',
  `company_tag` varchar(100) DEFAULT NULL,
  `post_tag` varchar(150) DEFAULT NULL,
  `project_ref` varchar(50) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `client_note` text,
  `client_initiative_id` int(11) DEFAULT '0',
  `dedupe_company_name` varchar(255) DEFAULT NULL,
  `dedupe_company_match_1` varchar(150) DEFAULT NULL,
  `dedupe_company_match_2` varchar(150) DEFAULT NULL,
  `dedupe_company_match_3` varchar(150) DEFAULT NULL,
  `dedupe_contact_first_name` varchar(50) DEFAULT NULL,
  `dedupe_contact_surname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8194 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_import_post_matches`
--

DROP TABLE IF EXISTS `tbl_import_post_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_import_post_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `import_id` int(11) NOT NULL DEFAULT '0',
  `alchemis_post_id` int(11) NOT NULL DEFAULT '0',
  `match_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_import_post_matches_import_id` (`import_id`),
  KEY `ix_tbl_import_post_matches_alchemis_post_id` (`alchemis_post_id`),
  KEY `ix_tbl_import_post_matches_match_type` (`match_type`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_include`
--

DROP TABLE IF EXISTS `tbl_include`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_include` (
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `row_key` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_information_requests`
--

DROP TABLE IF EXISTS `tbl_information_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_information_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `comm_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
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
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_information_requests_seq`
--

DROP TABLE IF EXISTS `tbl_information_requests_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_information_requests_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_information_requests_shadow`
--

DROP TABLE IF EXISTS `tbl_information_requests_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_information_requests_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_initiatives`
--

DROP TABLE IF EXISTS `tbl_initiatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_initiatives` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_initiatives_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_initiatives_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_initiatives_seq`
--

DROP TABLE IF EXISTS `tbl_initiatives_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_initiatives_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=885 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_initiatives_shadow`
--

DROP TABLE IF EXISTS `tbl_initiatives_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_initiatives_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_action_communication_types`
--

DROP TABLE IF EXISTS `tbl_lkp_action_communication_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_action_communication_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_action_resource_types`
--

DROP TABLE IF EXISTS `tbl_lkp_action_resource_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_action_resource_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_action_types`
--

DROP TABLE IF EXISTS `tbl_lkp_action_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_action_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_agency_user_types`
--

DROP TABLE IF EXISTS `tbl_lkp_agency_user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_agency_user_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_agency_user_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_agency_user_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_agency_user_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_billing_terms`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_billing_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_billing_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_billing_terms_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_billing_terms_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_billing_terms_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_payment_methods`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_payment_methods_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_payment_methods_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_payment_methods_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_payment_terms`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_payment_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_payment_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_payment_terms_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_payment_terms_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_payment_terms_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_types`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_campaign_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_campaign_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_campaign_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_receptiveness`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_receptiveness`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_receptiveness` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `status_score` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_receptiveness_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_receptiveness_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_receptiveness_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status` (
  `id` int(11) NOT NULL DEFAULT '0',
  `lower_value` int(11) NOT NULL DEFAULT '0',
  `upper_value` int(11) NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL DEFAULT '',
  `full_description` varchar(255) NOT NULL DEFAULT '',
  `is_auto_calculate` tinyint(1) NOT NULL DEFAULT '0',
  `show_auto_calculate_options` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `report_description` varchar(255) DEFAULT NULL,
  `report_sort_order` int(11) DEFAULT NULL,
  `report_break_after_line` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`description`),
  KEY `ix_tbl_lkp_communication_status_lower_value` (`lower_value`),
  KEY `ix_tbl_lkp_communication_status_upper_value` (`upper_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status_rules`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status_rules` (
  `id` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '0',
  `child_status_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_lkp_communication_status_rules_status_id` (`status_id`),
  KEY `ix_tbl_lkp_communication_status_rules_child_status_id` (`child_status_id`),
  KEY `ix_tbl_lkp_communication_status_rules_status_id_child_status_id` (`status_id`,`child_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status_rules_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status_rules_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status_rules_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status_rules_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '0',
  `child_status_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_status_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_status_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_status_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `lower_value` int(11) NOT NULL DEFAULT '0',
  `upper_value` int(11) NOT NULL DEFAULT '0',
  `is_auto_calculate` tinyint(1) NOT NULL DEFAULT '0',
  `show_auto_calculate_options` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_targeting`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_targeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_targeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `status_score` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_targeting_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_targeting_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_targeting_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_types`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_lkp_communication_types_is_active` (`is_active`),
  KEY `ix_tbl_lkp_communication_types_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_communication_types_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_communication_types_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_communication_types_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_counties`
--

DROP TABLE IF EXISTS `tbl_lkp_counties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_counties` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_counties_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_counties_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_counties_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_counties_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_counties_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_counties_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_countries`
--

DROP TABLE IF EXISTS `tbl_lkp_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_countries` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_countries_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_countries_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_countries_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_countries_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_countries_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_countries_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_decision_maker_types`
--

DROP TABLE IF EXISTS `tbl_lkp_decision_maker_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_decision_maker_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_decision_maker_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_decision_maker_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_decision_maker_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_event_types`
--

DROP TABLE IF EXISTS `tbl_lkp_event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_filter_types`
--

DROP TABLE IF EXISTS `tbl_lkp_filter_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_filter_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_filter_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_filter_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_filter_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_comm_types`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_comm_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_comm_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_comm_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_comm_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_comm_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_comm_types_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_comm_types_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_comm_types_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_status`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_status_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_status_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_status_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_status_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_status_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_status_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_types`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_information_request_types_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_information_request_types_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_information_request_types_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_lead_source`
--

DROP TABLE IF EXISTS `tbl_lkp_lead_source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_lead_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_lead_source_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_lead_source_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_lead_source_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_lead_source_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_lead_source_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_lead_source_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_response_groups`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_response_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_response_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_response_groups_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_response_groups_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_response_groups_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_response_groups_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_response_groups_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_response_groups_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_responses`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `response_group_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_responses_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_responses_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_responses_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_responses_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_responses_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_responses_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `response_group_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_types`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_mailer_types_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_mailer_types_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_mailer_types_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_locations`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_locations_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_locations_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_locations_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_status`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_status_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_status_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_status_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_status_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_status_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_status_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_types`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_meeting_types_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_meeting_types_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_meeting_types_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_next_communication_reasons`
--

DROP TABLE IF EXISTS `tbl_lkp_next_communication_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_next_communication_reasons` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `status_score` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_next_communication_reasons_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_next_communication_reasons_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_next_communication_reasons_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_next_communication_reasons_shadow`
--

DROP TABLE IF EXISTS `tbl_lkp_next_communication_reasons_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_next_communication_reasons_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `status_score` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_post_initiative_note_types`
--

DROP TABLE IF EXISTS `tbl_lkp_post_initiative_note_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_post_initiative_note_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_lkp_post_initiative_note_types_sort_order` (`sort_order`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_post_no_access_types`
--

DROP TABLE IF EXISTS `tbl_lkp_post_no_access_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_post_no_access_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_post_no_access_types_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_post_no_access_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_post_no_access_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_postcodes`
--

DROP TABLE IF EXISTS `tbl_lkp_postcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_postcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postcode` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_lkp_postcodes_postcode` (`postcode`)
) ENGINE=InnoDB AUTO_INCREMENT=2879 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_postcodes_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_postcodes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_postcodes_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2879 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_region_postcodes`
--

DROP TABLE IF EXISTS `tbl_lkp_region_postcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_region_postcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `postcode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_lkp_region_postcodes_region_id` (`region_id`),
  KEY `ix_tbl_lkp_region_postcodes_postcode_id` (`postcode_id`),
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk1` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk2` FOREIGN KEY (`postcode_id`) REFERENCES `tbl_lkp_postcodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4181 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_region_postcodes_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_region_postcodes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_region_postcodes_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4181 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_regions`
--

DROP TABLE IF EXISTS `tbl_lkp_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_regions_seq`
--

DROP TABLE IF EXISTS `tbl_lkp_regions_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_regions_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_lkp_reports`
--

DROP TABLE IF EXISTS `tbl_lkp_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_lkp_reports` (
  `id` int(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `design_file` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_log`
--

DROP TABLE IF EXISTS `tbl_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_log` (
  `summary` varchar(255) DEFAULT NULL,
  `transaction` varchar(255) DEFAULT NULL,
  `log_entry` blob,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_item_responses`
--

DROP TABLE IF EXISTS `tbl_mailer_item_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_item_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mailer_item_id` int(11) NOT NULL,
  `mailer_response_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_mailer_item_responses_mailer_item_id` (`mailer_item_id`),
  KEY `ix_tbl_mailer_item_responses_mailer_response_id` (`mailer_response_id`),
  CONSTRAINT `tbl_mailer_item_responses_ibfk1` FOREIGN KEY (`mailer_item_id`) REFERENCES `tbl_mailer_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_item_responses_ibfk2` FOREIGN KEY (`mailer_response_id`) REFERENCES `tbl_lkp_mailer_responses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1860 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_item_responses_seq`
--

DROP TABLE IF EXISTS `tbl_mailer_item_responses_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_item_responses_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=1860 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_item_responses_shadow`
--

DROP TABLE IF EXISTS `tbl_mailer_item_responses_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_item_responses_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `mailer_item_id` int(11) NOT NULL,
  `mailer_response_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_items`
--

DROP TABLE IF EXISTS `tbl_mailer_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mailer_id` int(11) NOT NULL,
  `post_initiative_id` int(11) NOT NULL,
  `despatched_date` datetime DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `despatched_communication_id` int(11) DEFAULT NULL,
  `response_communication_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_mailer_items_id` (`mailer_id`),
  KEY `ix_tbl_mailer_items_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_mailer_items_despatched_date` (`despatched_date`),
  KEY `ix_tbl_mailer_items_despatched_communication_id` (`despatched_communication_id`),
  KEY `ix_tbl_mailer_items_response_communication_id` (`response_communication_id`),
  CONSTRAINT `tbl_mailer_items_ibfk1` FOREIGN KEY (`mailer_id`) REFERENCES `tbl_mailers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk3` FOREIGN KEY (`despatched_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk4` FOREIGN KEY (`response_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=281433 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_items_seq`
--

DROP TABLE IF EXISTS `tbl_mailer_items_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_items_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=281433 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailer_items_shadow`
--

DROP TABLE IF EXISTS `tbl_mailer_items_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailer_items_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `mailer_id` int(11) NOT NULL,
  `post_initiative_id` int(11) NOT NULL,
  `despatched_date` datetime DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailers`
--

DROP TABLE IF EXISTS `tbl_mailers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_initiative_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `response_group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_mailers_client_initiative_id` (`client_initiative_id`),
  KEY `ix_tbl_mailers_response_group_id` (`response_group_id`),
  KEY `ix_tbl_mailers_type_id` (`type_id`),
  CONSTRAINT `tbl_mailers_ibfk1` FOREIGN KEY (`client_initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk2` FOREIGN KEY (`response_group_id`) REFERENCES `tbl_lkp_mailer_response_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_mailer_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailers_seq`
--

DROP TABLE IF EXISTS `tbl_mailers_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailers_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mailers_shadow`
--

DROP TABLE IF EXISTS `tbl_mailers_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mailers_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `client_initiative_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `response_group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_meetings`
--

DROP TABLE IF EXISTS `tbl_meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meetings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `attended_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `nbm_predicted_rating` int(11) DEFAULT NULL,
  `feedback_rating` int(11) DEFAULT NULL,
  `feedback_decision_maker` tinyint(1) DEFAULT '0',
  `feedback_agency_user` tinyint(1) DEFAULT '0',
  `feedback_budget_available` tinyint(1) DEFAULT '0',
  `feedback_receptive` tinyint(1) DEFAULT '0',
  `feedback_targeting` tinyint(1) DEFAULT '0',
  `feedback_meeting_length` int(11) DEFAULT '0',
  `feedback_comments` text,
  `feedback_next_steps` text,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_meetings_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_meetings_status_id` (`status_id`),
  KEY `ix_tbl_meetings_type_id` (`type_id`),
  KEY `ix_tbl_meetings_communication_id` (`communication_id`),
  KEY `ix_tbl_meetings_date` (`date`),
  KEY `ix_tbl_meetings_created_by` (`created_by`),
  KEY `ix_tbl_meetings_is_current` (`is_current`),
  KEY `ix_tbl_meetings` (`location_id`),
  KEY `ix_tbl_meetings_attended_date` (`attended_date`),
  KEY `ix_tbl_meetings_created_at` (`created_at`),
  KEY `ix_tbl_meetings_modified_at` (`modified_at`),
  KEY `ix_tbl_meetings_modified_by` (`modified_by`),
  CONSTRAINT `tbl_meetinfs_ibfk5` FOREIGN KEY (`location_id`) REFERENCES `tbl_lkp_meeting_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_communication_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_meeting_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_meetings_old`
--

DROP TABLE IF EXISTS `tbl_meetings_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meetings_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `nbm_predicted_rating` int(11) DEFAULT NULL,
  `feedback_rating` int(11) DEFAULT NULL,
  `feedback_decision_maker` tinyint(1) DEFAULT '0',
  `feedback_agency_user` tinyint(1) DEFAULT '0',
  `feedback_budget_available` tinyint(1) DEFAULT '0',
  `feedback_receptive` tinyint(1) DEFAULT '0',
  `feedback_targeting` tinyint(1) DEFAULT '0',
  `feedback_meeting_length` int(11) DEFAULT '0',
  `feedback_comments` text,
  `feedback_next_steps` text,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_meetings_old_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_meetings_old_status_id` (`status_id`),
  KEY `ix_tbl_meetings_old_type_id` (`type_id`),
  KEY `ix_tbl_meetings_old_communication_id` (`communication_id`),
  KEY `ix_tbl_meetings_old_date` (`date`),
  KEY `ix_tbl_meetings_old_created_by` (`created_by`),
  KEY `ix_tbl_meetings_old_is_current` (`is_current`),
  KEY `ix_tbl_meetings_old_` (`location_id`),
  CONSTRAINT `tbl_meetings_old_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_old_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_communication_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_old_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_meeting_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_old_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_meetings_old_ibfk5` FOREIGN KEY (`location_id`) REFERENCES `tbl_lkp_meeting_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20202 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_meetings_seq`
--

DROP TABLE IF EXISTS `tbl_meetings_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meetings_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=32132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_meetings_shadow`
--

DROP TABLE IF EXISTS `tbl_meetings_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meetings_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `attended_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT '',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `nbm_predicted_rating` int(11) DEFAULT NULL,
  `feedback_rating` int(11) DEFAULT NULL,
  `feedback_decision_maker` tinyint(1) DEFAULT '0',
  `feedback_agency_user` tinyint(1) DEFAULT '0',
  `feedback_budget_available` tinyint(1) DEFAULT '0',
  `feedback_receptive` tinyint(1) DEFAULT '0',
  `feedback_targeting` tinyint(1) DEFAULT '0',
  `feedback_meeting_length` int(11) DEFAULT '0',
  `feedback_comments` text,
  `feedback_next_steps` text,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_meetings_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_shadow_shadow_timestamp` (`shadow_timestamp`),
  KEY `ix_tbl_meetings_shadow_date` (`date`),
  KEY `ix_tbl_meetings_shadow_created_by` (`created_by`),
  KEY `ix_tbl_meetings_shadow_created_at` (`created_at`),
  KEY `ix_tbl_meetings_shadow_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88564 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_meetings_shadow_temp`
--

DROP TABLE IF EXISTS `tbl_meetings_shadow_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meetings_shadow_temp` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `communication_id` int(11) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT '',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `nbm_predicted_rating` int(11) DEFAULT NULL,
  `feedback_rating` int(11) DEFAULT NULL,
  `feedback_decision_maker` tinyint(1) DEFAULT '0',
  `feedback_agency_user` tinyint(1) DEFAULT '0',
  `feedback_budget_available` tinyint(1) DEFAULT '0',
  `feedback_receptive` tinyint(1) DEFAULT '0',
  `feedback_targeting` tinyint(1) DEFAULT '0',
  `feedback_meeting_length` int(11) DEFAULT '0',
  `feedback_comments` text,
  `feedback_next_steps` text,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_meetings_temp_shadow_shadow_type` (`shadow_type`),
  KEY `ix_tbl_meetings_temp_shadow_shadow_updated_by` (`shadow_updated_by`),
  KEY `ix_tbl_meetings_temp_shadow_shadow_timestamp` (`shadow_timestamp`),
  KEY `ix_tbl_meetings_temp_shadow_date` (`date`),
  KEY `ix_tbl_meetings_temp_shadow_created_by` (`created_by`),
  KEY `ix_tbl_meetings_temp_shadow_created_at` (`created_at`),
  KEY `ix_tbl_meetings_temp_shadow_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88564 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_messages`
--

DROP TABLE IF EXISTS `tbl_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` varchar(100) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_messages_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_messages_ibfk1` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_messages_seq`
--

DROP TABLE IF EXISTS `tbl_messages_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_messages_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mime_types`
--

DROP TABLE IF EXISTS `tbl_mime_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mime_types` (
  `id` int(11) NOT NULL DEFAULT '0',
  `file_type` char(100) NOT NULL DEFAULT '',
  `mime_type` char(100) NOT NULL DEFAULT '',
  `icon_path` char(255) DEFAULT NULL,
  `friendly_name` char(255) DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_mime_types_seq`
--

DROP TABLE IF EXISTS `tbl_mime_types_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mime_types_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_nbm_campaign_targets`
--

DROP TABLE IF EXISTS `tbl_nbm_campaign_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_nbm_campaign_targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `planned_days` int(11) NOT NULL DEFAULT '0',
  `project_management_days` int(11) NOT NULL DEFAULT '0',
  `effectives` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(11) NOT NULL DEFAULT '0',
  `meetings_set_imperative` int(11) NOT NULL DEFAULT '0',
  `meetings_attended` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_nbm_campaign_targets_user_id` (`user_id`),
  KEY `ix_tbl_nbm_campaign_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_nbm_campaign_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_nbm_campaign_targets_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_nbm_campaign_targets_seq`
--

DROP TABLE IF EXISTS `tbl_nbm_campaign_targets_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_nbm_campaign_targets_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_nbm_campaign_targets_shadow`
--

DROP TABLE IF EXISTS `tbl_nbm_campaign_targets_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_nbm_campaign_targets_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `planned_days` int(11) NOT NULL DEFAULT '0',
  `project_management_days` int(11) NOT NULL DEFAULT '0',
  `effectives` int(11) NOT NULL DEFAULT '0',
  `meetings_set` int(11) NOT NULL DEFAULT '0',
  `meetings_set_imperative` int(11) NOT NULL DEFAULT '0',
  `meetings_attended` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_boolean`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_boolean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_boolean_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_boolean_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_boolean_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_boolean_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63107 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_boolean_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_boolean_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=63032 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_boolean_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_boolean_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_boolean_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_date`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_date_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_date_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_date_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_date_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_date_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_date_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_date_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_date_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_date_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_date_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_text`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_characteristic_id` (`object_characteristic_id`,`characteristic_element_id`),
  KEY `ix_tbl_object_characteristic_elements_text_oc_id` (`object_characteristic_id`),
  KEY `ix_tbl_object_characteristic_elements_text_ce_id` (`characteristic_element_id`),
  CONSTRAINT `tbl_object_characteristic_elements_text_ibfk_1` FOREIGN KEY (`object_characteristic_id`) REFERENCES `tbl_object_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristic_elements_text_ibfk_2` FOREIGN KEY (`characteristic_element_id`) REFERENCES `tbl_characteristic_elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32791 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_text_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_text_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_text_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=32796 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristic_elements_text_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristic_elements_text_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristic_elements_text_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `object_characteristic_id` int(11) NOT NULL DEFAULT '0',
  `characteristic_element_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics`
--

DROP TABLE IF EXISTS `tbl_object_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_object_characteristics_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72650 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_boolean`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_boolean`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_boolean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_object_characteristics_boolean_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_boolean_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_boolean_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_boolean_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_boolean_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6107 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_boolean_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_boolean_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_boolean_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6060 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_boolean_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_boolean_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_boolean_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` int(1) DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_date`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_object_characteristics_date_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_date_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_date_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_date_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_object_characteristics_date_value` (`value`),
  KEY `ix_tbl_object_characteristics_date_company_id_value` (`company_id`,`value`),
  KEY `ix_tbl_object_characteristics_date_char_id_company_id_value` (`characteristic_id`,`company_id`,`value`),
  KEY `ix_tbl_object_characteristics_date_post_id_value` (`post_id`,`value`),
  KEY `ix_tbl_object_characteristics_date_char_id_post_id_value` (`characteristic_id`,`post_id`,`value`),
  CONSTRAINT `tbl_object_characteristics_date_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_date_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28657 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_date_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_date_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_date_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=28657 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_date_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_date_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_date_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=72587 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_text`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_object_characteristics_text_characteristic_id` (`characteristic_id`),
  KEY `ix_tbl_object_characteristics_text_company_id` (`company_id`),
  KEY `ix_tbl_object_characteristics_text_post_id` (`post_id`),
  KEY `ix_tbl_object_characteristics_text_post_initiative_id` (`post_initiative_id`),
  CONSTRAINT `tbl_object_characteristics_text_ibfk_1` FOREIGN KEY (`characteristic_id`) REFERENCES `tbl_characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_object_characteristics_text_ibfk_4` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_text_seq`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_text_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_text_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_characteristics_text_shadow`
--

DROP TABLE IF EXISTS `tbl_object_characteristics_text_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_characteristics_text_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `characteristic_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_tiered_characteristics`
--

DROP TABLE IF EXISTS `tbl_object_tiered_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_tiered_characteristics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `parent_company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tiered_characteristic_id`,`company_id`),
  KEY `ix_tbl_object_tiered_characteristics_tiered_characteristic_id` (`tiered_characteristic_id`),
  KEY `ix_tbl_object_tiered_characteristics_tier` (`tier`),
  KEY `ix_tbl_object_tiered_characteristics_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=214495 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_tiered_characteristics_seq`
--

DROP TABLE IF EXISTS `tbl_object_tiered_characteristics_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=214470 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_object_tiered_characteristics_shadow`
--

DROP TABLE IF EXISTS `tbl_object_tiered_characteristics_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_object_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5151 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_parent_company`
--

DROP TABLE IF EXISTS `tbl_parent_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_parent_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7074 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_parent_company_address`
--

DROP TABLE IF EXISTS `tbl_parent_company_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_parent_company_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_company_id` int(11) DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_review_dates`
--

DROP TABLE IF EXISTS `tbl_post_agency_review_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_review_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_agency_user_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_review_dates_post_agency_user_id` (`post_agency_user_id`),
  CONSTRAINT `ix_tbl_post_agency_review_dates_ibfk1` FOREIGN KEY (`post_agency_user_id`) REFERENCES `tbl_post_agency_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_review_dates_seq`
--

DROP TABLE IF EXISTS `tbl_post_agency_review_dates_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_review_dates_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_review_dates_shadow`
--

DROP TABLE IF EXISTS `tbl_post_agency_review_dates_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_review_dates_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_agency_user_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_users`
--

DROP TABLE IF EXISTS `tbl_post_agency_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_users_id` (`post_id`),
  KEY `ix_tbl_post_agency_users_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_agency_users_type_id` (`type_id`),
  KEY `ix_tbl_post_agency_users_last_updated_at` (`last_updated_at`),
  KEY `ix_tbl_post_agency_users_communication_id` (`communication_id`),
  CONSTRAINT `ix_tbl_post_agency_users_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_agency_user_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_users_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68938 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_users_seq`
--

DROP TABLE IF EXISTS `tbl_post_agency_users_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_users_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=68938 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_agency_users_shadow`
--

DROP TABLE IF EXISTS `tbl_post_agency_users_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_agency_users_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_post_agency_users_shadow_communication_id` (`communication_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70223 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_decision_makers`
--

DROP TABLE IF EXISTS `tbl_post_decision_makers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_decision_makers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_decision_makers_post_id` (`post_id`),
  KEY `ix_tbl_post_decision_makers_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_decision_makers_type_id` (`type_id`),
  KEY `ix_tbl_post_decision_makers_last_updated_at` (`last_updated_at`),
  KEY `ix_tbl_post_decision_makers_communication_id` (`communication_id`),
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_decision_maker_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_decision_makers_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_decision_makers_seq`
--

DROP TABLE IF EXISTS `tbl_post_decision_makers_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_decision_makers_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=51134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_decision_makers_shadow`
--

DROP TABLE IF EXISTS `tbl_post_decision_makers_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_decision_makers_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_post_decision_makers_shadow_communication_id` (`communication_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52135 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_discipline_review_dates`
--

DROP TABLE IF EXISTS `tbl_post_discipline_review_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_discipline_review_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `year_month` char(6) NOT NULL DEFAULT '',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_discipline_review_dates_post_id` (`post_id`),
  KEY `ix_tbl_post_discipline_review_dates_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_discipline_review_dates_last_updated_at` (`last_updated_at`),
  KEY `ix_tbl_post_discipline_review_dates_communication_id` (`communication_id`),
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk2` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=559 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_discipline_review_dates_seq`
--

DROP TABLE IF EXISTS `tbl_post_discipline_review_dates_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_discipline_review_dates_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=559 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_discipline_review_dates_shadow`
--

DROP TABLE IF EXISTS `tbl_post_discipline_review_dates_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_discipline_review_dates_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `year_month` char(6) NOT NULL DEFAULT '',
  `communication_id` int(11) DEFAULT NULL,
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_post_discipline_review_dates_shadow_communication_id` (`communication_id`)
) ENGINE=InnoDB AUTO_INCREMENT=577 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_incumbent_agencies`
--

DROP TABLE IF EXISTS `tbl_post_incumbent_agencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_incumbent_agencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `agency_company_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_incumbent_agencies_post_id` (`post_id`),
  KEY `ix_tbl_post_incumbent_agencies_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_incumbent_agencies_last_updated_at` (`last_updated_at`),
  KEY `ix_tbl_post_incumbent_agencies_communication_id` (`communication_id`),
  KEY `ix_tbl_post_incumbent_agencies_company_id` (`agency_company_id`),
  CONSTRAINT `ix_tbl_post_incumbent_agencies_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_incumbent_agencies_ibfk2` FOREIGN KEY (`agency_company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_incumbent_agencies_ibfk3` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_incumbent_agencies_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_incumbent_agencies_seq`
--

DROP TABLE IF EXISTS `tbl_post_incumbent_agencies_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_incumbent_agencies_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_incumbent_agencies_shadow`
--

DROP TABLE IF EXISTS `tbl_post_incumbent_agencies_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_incumbent_agencies_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `agency_company_id` int(11) NOT NULL DEFAULT '0',
  `discipline_id` int(11) NOT NULL DEFAULT '0',
  `communication_id` int(11) DEFAULT NULL,
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`),
  KEY `ix_tbl_post_incumbent_agencies_shadow_communication_id` (`communication_id`)
) ENGINE=InnoDB AUTO_INCREMENT=749 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_note_documents`
--

DROP TABLE IF EXISTS `tbl_post_initiative_note_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_note_documents` (
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_note_id` int(11) NOT NULL DEFAULT '0',
  `document_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_initiative_note_documents_post_initiative_note_id` (`post_initiative_note_id`) USING BTREE,
  KEY `ix_tbl_post_initiative_note_documents_document_id` (`document_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_note_documents_seq`
--

DROP TABLE IF EXISTS `tbl_post_initiative_note_documents_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_note_documents_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=25066 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_notes`
--

DROP TABLE IF EXISTS `tbl_post_initiative_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `for_client` tinyint(1) DEFAULT '1',
  `note_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_initiative_notes_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_post_initiative_notes_created_at` (`created_at`),
  KEY `ix_tbl_post_initiative_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2088596 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_notes_seq`
--

DROP TABLE IF EXISTS `tbl_post_initiative_notes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_notes_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2088596 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_notes_shadow`
--

DROP TABLE IF EXISTS `tbl_post_initiative_notes_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_notes_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_tags`
--

DROP TABLE IF EXISTS `tbl_post_initiative_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_initiative_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`post_initiative_id`,`tag_id`),
  KEY `ix_tbl_company_tags_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_initiative_tags_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=534328 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_tags_seq`
--

DROP TABLE IF EXISTS `tbl_post_initiative_tags_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_tags_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=534324 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiative_tags_shadow`
--

DROP TABLE IF EXISTS `tbl_post_initiative_tags_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiative_tags_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_initiative_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiatives`
--

DROP TABLE IF EXISTS `tbl_post_initiatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiatives` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `initiative_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `last_effective_communication_id` int(11) DEFAULT NULL,
  `last_communication_id` int(11) DEFAULT NULL,
  `next_communication_date` datetime DEFAULT NULL,
  `last_mailer_communication_id` int(11) DEFAULT NULL,
  `lead_source_id` int(11) DEFAULT NULL,
  `next_action_by` int(11) NOT NULL DEFAULT '1',
  `priority_callback` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
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
  CONSTRAINT `ix_tbl_post_initiatives_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk2` FOREIGN KEY (`initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk3` FOREIGN KEY (`last_effective_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk4` FOREIGN KEY (`last_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk5` FOREIGN KEY (`last_mailer_communication_id`) REFERENCES `tbl_communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk6` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk7` FOREIGN KEY (`next_action_by`) REFERENCES `tbl_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiatives_seq`
--

DROP TABLE IF EXISTS `tbl_post_initiatives_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiatives_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=756259 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_initiatives_shadow`
--

DROP TABLE IF EXISTS `tbl_post_initiatives_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_initiatives_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `initiative_id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `last_effective_communication_id` int(11) DEFAULT NULL,
  `last_communication_id` int(11) DEFAULT NULL,
  `next_communication_date` datetime DEFAULT NULL,
  `last_mailer_communication_id` int(11) DEFAULT NULL,
  `lead_source_id` int(11) DEFAULT NULL,
  `next_action_by` int(11) NOT NULL DEFAULT '1',
  `priority_callback` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`shadow_id`),
  KEY `idx_shadow_timestamp` (`shadow_timestamp`),
  KEY `idx_shadow_id` (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1522699 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_notes`
--

DROP TABLE IF EXISTS `tbl_post_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_notes_post_id` (`post_id`),
  KEY `ix_tbl_post_notes_created_at` (`created_at`),
  KEY `ix_tbl_post_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_post_notes_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_notes_seq`
--

DROP TABLE IF EXISTS `tbl_post_notes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_notes_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=34117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_notes_shadow`
--

DROP TABLE IF EXISTS `tbl_post_notes_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_notes_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_site`
--

DROP TABLE IF EXISTS `tbl_post_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_site` (
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_tbl_post_unique1` (`post_id`,`site_id`),
  KEY `ix_tbl_post_site_post_id` (`post_id`),
  KEY `ix_tbl_post_site_site_id` (`site_id`),
  CONSTRAINT `tbl_post_site_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_post_site_ibfk2` FOREIGN KEY (`site_id`) REFERENCES `tbl_sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_site_seq`
--

DROP TABLE IF EXISTS `tbl_post_site_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_site_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=133480 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_site_shadow`
--

DROP TABLE IF EXISTS `tbl_post_site_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_site_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_tags`
--

DROP TABLE IF EXISTS `tbl_post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`post_id`,`tag_id`),
  KEY `ix_tbl_company_tags_post_id` (`post_id`),
  KEY `ix_tbl_company_tags_tag_id` (`tag_id`),
  CONSTRAINT `ix_tbl_post_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_tags_ibfk2` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76878 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_tags_seq`
--

DROP TABLE IF EXISTS `tbl_post_tags_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_tags_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=76878 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_post_tags_shadow`
--

DROP TABLE IF EXISTS `tbl_post_tags_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_tags_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_postmark_data`
--

DROP TABLE IF EXISTS `tbl_postmark_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_postmark_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_posts`
--

DROP TABLE IF EXISTS `tbl_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_posts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `job_title` varchar(255) NOT NULL DEFAULT '',
  `propensity` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `telephone_1` varchar(50) DEFAULT '',
  `telephone_2` varchar(50) DEFAULT '',
  `telephone_switchboard` varchar(50) DEFAULT '',
  `telephone_fax` varchar(50) DEFAULT '',
  `spend` varchar(25) DEFAULT NULL,
  `incumbent_change_date` datetime DEFAULT NULL,
  `not_available_until` datetime DEFAULT NULL,
  `no_access_type_id` int(11) DEFAULT NULL,
  `no_access_date` datetime DEFAULT NULL,
  `call_barred` tinyint(1) DEFAULT '0',
  `access_rate` int(11) DEFAULT NULL,
  `access_figures` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_posts_company_id` (`company_id`),
  KEY `ix_tbl_posts_job_title` (`job_title`),
  KEY `ix_tbl_posts_deleted` (`deleted`),
  KEY `ix_tbl_posts_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_posts_propensity` (`propensity`),
  CONSTRAINT `ix_tbl_posts_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_posts_seq`
--

DROP TABLE IF EXISTS `tbl_posts_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_posts_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=237628 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_posts_shadow`
--

DROP TABLE IF EXISTS `tbl_posts_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_posts_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `job_title` varchar(255) NOT NULL DEFAULT '',
  `propensity` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `telephone_1` varchar(50) DEFAULT '',
  `telephone_2` varchar(50) DEFAULT '',
  `telephone_switchboard` varchar(50) DEFAULT '',
  `telephone_fax` varchar(50) DEFAULT '',
  `spend` varchar(25) DEFAULT NULL,
  `incumbent_change_date` datetime DEFAULT NULL,
  `not_available_until` datetime DEFAULT NULL,
  `no_access_type_id` int(11) DEFAULT NULL,
  `no_access_date` datetime DEFAULT NULL,
  `call_barred` tinyint(1) DEFAULT '0',
  `access_rate` int(11) DEFAULT NULL,
  `access_figures` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84246 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_ra_incredibull_mailer`
--

DROP TABLE IF EXISTS `tbl_ra_incredibull_mailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_ra_incredibull_mailer` (
  `post_id` int(11) DEFAULT NULL,
  KEY `ix_post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_commands`
--

DROP TABLE IF EXISTS `tbl_rbac_commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_commands` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_permissions`
--

DROP TABLE IF EXISTS `tbl_rbac_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_permissions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `command_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_role_permissions`
--

DROP TABLE IF EXISTS `tbl_rbac_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_role_permissions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  `permission_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_tbl_rbac_role_permissions_1` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_roles`
--

DROP TABLE IF EXISTS `tbl_rbac_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_roles` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_roles_seq`
--

DROP TABLE IF EXISTS `tbl_rbac_roles_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_roles_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_sessions`
--

DROP TABLE IF EXISTS `tbl_rbac_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_sessions` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `expiration` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_rbac_sessions_expiration` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_user_roles`
--

DROP TABLE IF EXISTS `tbl_rbac_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_user_roles` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_tbl_rbac_user_roles_1` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_users`
--

DROP TABLE IF EXISTS `tbl_rbac_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_users` (
  `id` int(11) NOT NULL DEFAULT '0',
  `handle` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) DEFAULT '1',
  `permission_add_call_name` tinyint(1) NOT NULL DEFAULT '0',
  `permission_add_client_record` tinyint(1) NOT NULL DEFAULT '0',
  `permission_add_company_site` tinyint(1) NOT NULL DEFAULT '0',
  `permission_add_notes` tinyint(1) NOT NULL DEFAULT '0',
  `permission_change_location` tinyint(1) NOT NULL DEFAULT '0',
  `permission_change_occupier` tinyint(1) NOT NULL DEFAULT '0',
  `permission_create_post` tinyint(1) NOT NULL DEFAULT '1',
  `permission_dedupe_posts` tinyint(1) NOT NULL DEFAULT '0',
  `permission_undelete_posts` tinyint(1) NOT NULL DEFAULT '0',
  `permission_delete_client` tinyint(1) NOT NULL DEFAULT '0',
  `permission_delete_company` tinyint(1) NOT NULL DEFAULT '0',
  `permission_delete_last_call` tinyint(1) NOT NULL DEFAULT '0',
  `permission_delete_post` tinyint(1) NOT NULL DEFAULT '0',
  `permission_edit_company_site` tinyint(1) NOT NULL DEFAULT '0',
  `permission_edit_client_record` tinyint(1) NOT NULL DEFAULT '0',
  `permission_edit_company_record` tinyint(1) NOT NULL DEFAULT '0',
  `permission_edit_post_record` tinyint(1) NOT NULL DEFAULT '0',
  `permission_maintain_agencies` tinyint(1) NOT NULL DEFAULT '0',
  `permission_maintain_review_dates` tinyint(1) NOT NULL DEFAULT '0',
  `permission_move_client` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_users` tinyint(1) NOT NULL DEFAULT '0',
  `permission_view_global_calendar` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_messages` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_nbm_teams` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_teams` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_client_campaigns` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_clients_nbm_admin` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_nbm_monthly_planner` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_characteristics` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_regions` tinyint(1) NOT NULL DEFAULT '0',
  `permission_admin_reports` tinyint(1) NOT NULL DEFAULT '0',
  `permission_add_bulk_ref` tinyint(1) NOT NULL DEFAULT '0',
  `permission_email_to_prospect` tinyint(1) NOT NULL DEFAULT '0',
  `permission_deleted_restored_filters` tinyint(1) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_rbac_users_seq`
--

DROP TABLE IF EXISTS `tbl_rbac_users_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_rbac_users_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=MyISAM AUTO_INCREMENT=885 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_sites`
--

DROP TABLE IF EXISTS `tbl_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sites` (
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postcode` varchar(25) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `region_postcode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_sites_company_id` (`company_id`),
  KEY `ix_tbl_sites_deleted` (`deleted`),
  KEY `tbl_sites_ibfk2` (`county_id`),
  KEY `tbl_sites_ibfk3` (`country_id`),
  KEY `ix_tbl_sites_id_and_deleted` (`id`,`deleted`),
  KEY `ix_tbl_sites_postcode` (`postcode`),
  KEY `ix_tbl_sites_region_postcode` (`region_postcode`),
  KEY `ix_tbl_sites_city` (`city`),
  KEY `ix_tbl_sites_town` (`town`),
  CONSTRAINT `tbl_sites_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk2` FOREIGN KEY (`county_id`) REFERENCES `tbl_lkp_counties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk3` FOREIGN KEY (`country_id`) REFERENCES `tbl_lkp_countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_sites_seq`
--

DROP TABLE IF EXISTS `tbl_sites_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sites_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=86366 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_sites_shadow`
--

DROP TABLE IF EXISTS `tbl_sites_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sites_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `address_1` varchar(255) NOT NULL DEFAULT '',
  `address_2` varchar(255) NOT NULL DEFAULT '',
  `town` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `postcode` varchar(25) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `region_postcode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48025 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tag_categories`
--

DROP TABLE IF EXISTS `tbl_tag_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tag_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tag_categories_seq`
--

DROP TABLE IF EXISTS `tbl_tag_categories_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tag_categories_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tag_categories_shadow`
--

DROP TABLE IF EXISTS `tbl_tag_categories_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tag_categories_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tags`
--

DROP TABLE IF EXISTS `tbl_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_tags_value` (`value`),
  KEY `ix_tbl_tags_category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=484148 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tags_seq`
--

DROP TABLE IF EXISTS `tbl_tags_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tags_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=484148 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tags_shadow`
--

DROP TABLE IF EXISTS `tbl_tags_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tags_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_team_nbms`
--

DROP TABLE IF EXISTS `tbl_team_nbms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_team_nbms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_tbl_team_nbms_team_id` (`team_id`),
  KEY `ix_tbl_team_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_team_nbms_ibfk1` FOREIGN KEY (`team_id`) REFERENCES `tbl_teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_team_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_team_nbms_seq`
--

DROP TABLE IF EXISTS `tbl_team_nbms_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_team_nbms_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_teams`
--

DROP TABLE IF EXISTS `tbl_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_teams_seq`
--

DROP TABLE IF EXISTS `tbl_teams_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_teams_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristic_categories`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristic_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristic_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristic_categories_seq`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristic_categories_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristic_categories_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristic_categories_shadow`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristic_categories_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristic_categories_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristics`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_tiered_characteristics_value` (`value`),
  KEY `ix_tbl_tiered_characteristics_category_id` (`category_id`),
  KEY `ix_tbl_tiered_characteristics_parent_id` (`parent_id`),
  CONSTRAINT `ix_tbl_tiered_characteristics_ibfk1` FOREIGN KEY (`category_id`) REFERENCES `tbl_tiered_characteristic_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1021 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristics_seq`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristics_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3810 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tiered_characteristics_shadow`
--

DROP TABLE IF EXISTS `tbl_tiered_characteristics_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tmp_20080131_comm_ids_for_meeting_related_non_effs_to_effs`
--

DROP TABLE IF EXISTS `tbl_tmp_20080131_comm_ids_for_meeting_related_non_effs_to_effs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tmp_20080131_comm_ids_for_meeting_related_non_effs_to_effs` (
  `communication_id` int(11) DEFAULT NULL,
  `post_initiative_id` int(11) DEFAULT NULL,
  KEY `ix_id` (`communication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_tmp_20080131_previous_last_effective_comm_id`
--

DROP TABLE IF EXISTS `tbl_tmp_20080131_previous_last_effective_comm_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tmp_20080131_previous_last_effective_comm_id` (
  `post_initiative_id` int(11) DEFAULT NULL,
  `previous_last_effective_communication_id` int(11) DEFAULT NULL,
  KEY `ix_id` (`previous_last_effective_communication_id`),
  KEY `ix_post_initiative_id` (`post_initiative_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_user_client_access`
--

DROP TABLE IF EXISTS `tbl_user_client_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user_client_access` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_user_client_access_client_id` (`client_id`),
  KEY `ix_tbl_user_client_access_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_user_client_access_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_user_client_access_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_user_client_access_seq`
--

DROP TABLE IF EXISTS `tbl_user_client_access_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user_client_access_seq` (
  `sequence` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_user_client_access_shadow`
--

DROP TABLE IF EXISTS `tbl_user_client_access_shadow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user_client_access_shadow` (
  `shadow_id` int(11) NOT NULL AUTO_INCREMENT,
  `shadow_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `vw_calendar_information_requests`
--

DROP TABLE IF EXISTS `vw_calendar_information_requests`;
/*!50001 DROP VIEW IF EXISTS `vw_calendar_information_requests`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_calendar_information_requests` (
  `id` tinyint NOT NULL,
  `post_initiative_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `reminder_date` tinyint NOT NULL,
  `notes` tinyint NOT NULL,
  `created_at` tinyint NOT NULL,
  `created_by` tinyint NOT NULL,
  `communication_id` tinyint NOT NULL,
  `type_id` tinyint NOT NULL,
  `comm_type_id` tinyint NOT NULL,
  `client_id` tinyint NOT NULL,
  `client` tinyint NOT NULL,
  `company_id` tinyint NOT NULL,
  `company` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_calendar_meetings`
--

DROP TABLE IF EXISTS `vw_calendar_meetings`;
/*!50001 DROP VIEW IF EXISTS `vw_calendar_meetings`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_calendar_meetings` (
  `id` tinyint NOT NULL,
  `post_initiative_id` tinyint NOT NULL,
  `communication_id` tinyint NOT NULL,
  `is_current` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `type_id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `reminder_date` tinyint NOT NULL,
  `notes` tinyint NOT NULL,
  `created_at` tinyint NOT NULL,
  `created_by` tinyint NOT NULL,
  `modified_by` tinyint NOT NULL,
  `modified_at` tinyint NOT NULL,
  `location_id` tinyint NOT NULL,
  `nbm_predicted_rating` tinyint NOT NULL,
  `feedback_rating` tinyint NOT NULL,
  `feedback_decision_maker` tinyint NOT NULL,
  `feedback_agency_user` tinyint NOT NULL,
  `feedback_budget_available` tinyint NOT NULL,
  `feedback_receptive` tinyint NOT NULL,
  `feedback_targeting` tinyint NOT NULL,
  `feedback_meeting_length` tinyint NOT NULL,
  `feedback_comments` tinyint NOT NULL,
  `feedback_next_steps` tinyint NOT NULL,
  `client_id` tinyint NOT NULL,
  `client` tinyint NOT NULL,
  `company_id` tinyint NOT NULL,
  `company` tinyint NOT NULL,
  `post_id` tinyint NOT NULL,
  `initiative_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_client_initiatives`
--

DROP TABLE IF EXISTS `vw_client_initiatives`;
/*!50001 DROP VIEW IF EXISTS `vw_client_initiatives`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_client_initiatives` (
  `client_id` tinyint NOT NULL,
  `client_name` tinyint NOT NULL,
  `campaign_id` tinyint NOT NULL,
  `initiative_id` tinyint NOT NULL,
  `initiative_name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_communication_max_date_by_post_initiative_id`
--

DROP TABLE IF EXISTS `vw_communication_max_date_by_post_initiative_id`;
/*!50001 DROP VIEW IF EXISTS `vw_communication_max_date_by_post_initiative_id`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_communication_max_date_by_post_initiative_id` (
  `max(communication_date)` tinyint NOT NULL,
  `post_initiative_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_communication_max_id_by_post_initiative_id`
--

DROP TABLE IF EXISTS `vw_communication_max_id_by_post_initiative_id`;
/*!50001 DROP VIEW IF EXISTS `vw_communication_max_id_by_post_initiative_id`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_communication_max_id_by_post_initiative_id` (
  `max(id)` tinyint NOT NULL,
  `max(communication_date)` tinyint NOT NULL,
  `post_initiative_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_companies`
--

DROP TABLE IF EXISTS `vw_companies`;
/*!50001 DROP VIEW IF EXISTS `vw_companies`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_companies` (
  `id` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `website` tinyint NOT NULL,
  `telephone` tinyint NOT NULL,
  `telephone_tps` tinyint NOT NULL,
  `deleted` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_companies_sites`
--

DROP TABLE IF EXISTS `vw_companies_sites`;
/*!50001 DROP VIEW IF EXISTS `vw_companies_sites`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_companies_sites` (
  `id` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `website` tinyint NOT NULL,
  `telephone` tinyint NOT NULL,
  `telephone_tps` tinyint NOT NULL,
  `deleted` tinyint NOT NULL,
  `site_id` tinyint NOT NULL,
  `site_name` tinyint NOT NULL,
  `address_1` tinyint NOT NULL,
  `address_2` tinyint NOT NULL,
  `town` tinyint NOT NULL,
  `city` tinyint NOT NULL,
  `postcode` tinyint NOT NULL,
  `site_telephone` tinyint NOT NULL,
  `county_id` tinyint NOT NULL,
  `county` tinyint NOT NULL,
  `country_id` tinyint NOT NULL,
  `country` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_contacts`
--

DROP TABLE IF EXISTS `vw_contacts`;
/*!50001 DROP VIEW IF EXISTS `vw_contacts`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_contacts` (
  `id` tinyint NOT NULL,
  `post_id` tinyint NOT NULL,
  `title` tinyint NOT NULL,
  `first_name` tinyint NOT NULL,
  `surname` tinyint NOT NULL,
  `deleted` tinyint NOT NULL,
  `email` tinyint NOT NULL,
  `linked_in` tinyint NOT NULL,
  `telephone_mobile` tinyint NOT NULL,
  `full_name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_events`
--

DROP TABLE IF EXISTS `vw_events`;
/*!50001 DROP VIEW IF EXISTS `vw_events`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_events` (
  `id` tinyint NOT NULL,
  `subject` tinyint NOT NULL,
  `notes` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `reminder_date` tinyint NOT NULL,
  `user_id` tinyint NOT NULL,
  `type_id` tinyint NOT NULL,
  `client_id` tinyint NOT NULL,
  `type` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_nbm_meeting_count_between_dates`
--

DROP TABLE IF EXISTS `vw_nbm_meeting_count_between_dates`;
/*!50001 DROP VIEW IF EXISTS `vw_nbm_meeting_count_between_dates`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_nbm_meeting_count_between_dates` (
  `max(id)` tinyint NOT NULL,
  `post_initiative_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_post_communication_stats_1`
--

DROP TABLE IF EXISTS `vw_post_communication_stats_1`;
/*!50001 DROP VIEW IF EXISTS `vw_post_communication_stats_1`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_post_communication_stats_1` (
  `post_id` tinyint NOT NULL,
  `communication_count` tinyint NOT NULL,
  `effective_count` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_post_communication_stats_base`
--

DROP TABLE IF EXISTS `vw_post_communication_stats_base`;
/*!50001 DROP VIEW IF EXISTS `vw_post_communication_stats_base`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_post_communication_stats_base` (
  `post_id` tinyint NOT NULL,
  `comm_count` tinyint NOT NULL,
  `eff_count` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_posts`
--

DROP TABLE IF EXISTS `vw_posts`;
/*!50001 DROP VIEW IF EXISTS `vw_posts`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_posts` (
  `id` tinyint NOT NULL,
  `company_id` tinyint NOT NULL,
  `job_title` tinyint NOT NULL,
  `propensity` tinyint NOT NULL,
  `notes` tinyint NOT NULL,
  `deleted` tinyint NOT NULL,
  `telephone_1` tinyint NOT NULL,
  `telephone_2` tinyint NOT NULL,
  `telephone_switchboard` tinyint NOT NULL,
  `telephone_fax` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_posts_contacts`
--

DROP TABLE IF EXISTS `vw_posts_contacts`;
/*!50001 DROP VIEW IF EXISTS `vw_posts_contacts`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_posts_contacts` (
  `id` tinyint NOT NULL,
  `company_id` tinyint NOT NULL,
  `job_title` tinyint NOT NULL,
  `propensity` tinyint NOT NULL,
  `telephone_1` tinyint NOT NULL,
  `telephone_2` tinyint NOT NULL,
  `telephone_switchboard` tinyint NOT NULL,
  `telephone_fax` tinyint NOT NULL,
  `title` tinyint NOT NULL,
  `first_name` tinyint NOT NULL,
  `surname` tinyint NOT NULL,
  `full_name` tinyint NOT NULL,
  `telephone_mobile` tinyint NOT NULL,
  `email` tinyint NOT NULL,
  `linked_in` tinyint NOT NULL,
  `notes` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_sites`
--

DROP TABLE IF EXISTS `vw_sites`;
/*!50001 DROP VIEW IF EXISTS `vw_sites`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_sites` (
  `id` tinyint NOT NULL,
  `company_id` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `address_1` tinyint NOT NULL,
  `address_2` tinyint NOT NULL,
  `town` tinyint NOT NULL,
  `city` tinyint NOT NULL,
  `postcode` tinyint NOT NULL,
  `telephone` tinyint NOT NULL,
  `county_id` tinyint NOT NULL,
  `country_id` tinyint NOT NULL,
  `deleted` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_tags_project_ref`
--

DROP TABLE IF EXISTS `vw_tags_project_ref`;
/*!50001 DROP VIEW IF EXISTS `vw_tags_project_ref`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_tags_project_ref` (
  `post_initiative_id` tinyint NOT NULL,
  `value` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `yii_migration`
--

DROP TABLE IF EXISTS `yii_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `yii_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'alchemis'
--
/*!50003 DROP FUNCTION IF EXISTS `f_get_post_meeting_count` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_get_post_meeting_count`(var_post_id INT(11)) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
DECLARE my_result int(11) DEFAULT 0;
select count(*) into my_result FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id and m.is_current= 1;
RETURN my_result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `f_get_site_address` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_get_site_address`(var_in_company_id INT(11)) RETURNS char(255) CHARSET latin1
    READS SQL DATA
    DETERMINISTIC
BEGIN 
DECLARE var_out_address char(255) DEFAULT '';
SELECT CONCAT(	s.address_1, 
				IF( LENGTH(s.address_2) > 0, 
					CONCAT( IF( LENGTH(s.address_1) > 0, ', ', '' ), s.address_2 ), 
					''),
				IF( LENGTH(s.town) > 0, 
					CONCAT( IF( LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.town ), 
					''),
				IF( LENGTH(s.city) > 0, 
					CONCAT( IF( LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.city ), 
					''),
				IF( LENGTH(county.name) > 0, 
					CONCAT( IF( LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), county.name ), 
					''),
				IF( LENGTH(s.postcode) > 0, 
					CONCAT( IF( LENGTH(county.name) > 0 OR LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.postcode ), 
					''),
				IF( LENGTH(country.name) > 0, 
					CONCAT( IF( LENGTH(county.name) > 0 OR LENGTH(s.postcode) > 0 OR LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), country.name ), 
					'')
				
				) INTO var_out_address 
				FROM tbl_sites AS s 
				LEFT JOIN tbl_lkp_counties AS county ON s.county_id = county.id 
				LEFT JOIN tbl_lkp_countries AS country ON s.country_id = country.id 
				WHERE s.company_id = var_in_company_id;
RETURN var_out_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `f_next_comm_date_period` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `f_next_comm_date_period`(future_date DATETIME, no_of_months INT(11)) RETURNS char(100) CHARSET latin1
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_3a_1` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_3a_1`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
select CAST(concat('To attend: ', DATE_FORMAT(m.date, '%b'), ' ', YEAR(m.date)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.date) AS `year_month`, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date >= var_in_date_start
AND m.date <= var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id IN (12, 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32)
UNION
select CAST('Live' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date > var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id IN (12, 13, 18, 19)
UNION
select CAST('Lapsed' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.date >= var_in_date_start
AND c.client_id = var_in_client_id
AND m.status_id IN (14, 15, 16, 17, 20, 21, 22, 23)
ORDER BY category DESC, `year_month`, date;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_3a_2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_3a_2`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
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
select sum(meeting_set_count) INTO meet_set
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` >= start_year_month
and `year_month` <= end_year_month;
select count(m.id) into to_attend
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date >= var_in_date_start
and m.date <=var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12 , 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32);
select count(m.id) into live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12 , 13, 18, 19);
select count(m.id) into lapsed
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date >= var_in_date_start
and c.client_id = var_in_client_id
AND m.status_id in (14,15,16,17,20,21,22,23);
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
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_3a_3` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_3a_3`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
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
select sum(meetings_set) INTO meets_set_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meetings_attended) INTO meets_attended_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_set_count) INTO meets_set_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_attended_count) INTO meets_attended_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_unknown_count) INTO meets_potential_attended
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(m.id) INTO meets_live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);
select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
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
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_3a_3_chart` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_3a_3_chart`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
BEGIN
DECLARE end_year_month varchar(6);
SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
CREATE TEMPORARY TABLE t (
category varchar(255), 
name varchar(255), 
value int(11));
INSERT INTO t 
SELECT 'Set', 'Target', SUM(meetings_set)
FROM tbl_campaign_targets
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;
INSERT INTO t 
SELECT 'Attended', 'Target', SUM(meetings_attended) 
FROM tbl_campaign_targets 
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;
INSERT INTO t 
SELECT 'Set', 'Actual', SUM(meeting_set_count) 
FROM tbl_data_statistics
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;
INSERT INTO t 
SELECT 'Attended', 'Actual', SUM(meeting_category_attended_count) 
FROM tbl_data_statistics
WHERE campaign_id = var_in_client_id
AND `year_month` <= end_year_month;
SELECT * FROM t;
DROP TEMPORARY TABLE t;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_3b_4` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_3b_4`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
SELECT CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, 
lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
where m.created_at >= var_in_date_start
AND m.created_at <= var_in_date_end
AND c.client_id = var_in_client_id
ORDER BY category DESC, `year_month`, date;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_5_1` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_5_1`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
insert into t
select count(com.id), tc.id, tc.value 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= var_in_date_start 
and com.communication_date <= var_in_date_end
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
insert into t1
select count(m.id), tc.id, tc.value 
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and m.created_at >= var_in_date_start 
and m.created_at <= var_in_date_end
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
insert into t2
select sum(t.item_count)
from t;
select t.tc_value as sector, 
t1.item_count as meeting_count, 
t.item_count/t2.item_count as activity_percentage, 
t1.item_count/t.item_count as conversion_percentage
from t2, t
left join t1 on t.tc_id = t1.tc_id
order by activity_percentage desc, meeting_count desc;
DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_5_2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_5_2`(var_in_client_id int(11))
begin
DECLARE var_start_year_month varchar(6);
DECLARE start_date datetime;
select start_year_month INTO var_start_year_month
from tbl_campaigns
where id = var_in_client_id;
SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');
CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
insert into t
select count(com.id), tc.id, tc.value 
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
insert into t1
select count(m.id), tc.id, tc.value 
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and m.created_at >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
insert into t2
select sum(t.item_count)
from t;
select t.tc_value as sector, 
t1.item_count as meeting_count, 
t.item_count/t2.item_count as activity_percentage, 
t1.item_count/t.item_count as conversion_percentage
from t2, t
left join t1 on t.tc_id = t1.tc_id
order by activity_percentage desc, meeting_count desc;
DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_5_3` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_5_3`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
insert into t
select count(com.id), tc.id, tc.value, tc_parent.value
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id 
where tc.category_id = 1 
and tc.parent_id > 0
and com.communication_date >= var_in_date_start 
and com.communication_date <= var_in_date_end
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
insert into t1
select count(m.id), tc.id, tc.value, tc_parent.value
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id 
join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id 
where tc.category_id = 1 
and tc.parent_id = 0 
and m.created_at >= var_in_date_start 
and m.created_at <= var_in_date_end
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
insert into t2
select sum(t.item_count)
from t;
select concat('(', t.tc_parent, ') ', t.tc_value) as sector, 
t1.item_count as meeting_count, 
t.item_count/t2.item_count as activity_percentage, 
t1.item_count/t.item_count as conversion_percentage
from t2, t
left join t1 on t.tc_id = t1.tc_id
where round((t.item_count/t2.item_count)*100) > 0
order by activity_percentage desc;
DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_5_4` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_5_4`(var_in_client_id int(11))
begin
DECLARE var_start_year_month varchar(6);
DECLARE start_date datetime;
select start_year_month INTO var_start_year_month
from tbl_campaigns
where id = var_in_client_id;
SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');
CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
insert into t
select count(com.id), tc.id, tc.value, tc_parent.value
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id 
where tc.category_id = 1 
and tc.parent_id > 0
and com.communication_date >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
insert into t1
select count(m.id), tc.id, tc.value, tc_parent.value
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_companies c on p.company_id = c.id 
join tbl_object_tiered_characteristics otc on c.id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id 
where tc.category_id = 1 
and tc.parent_id > 0 
and m.created_at >= start_date 
and cam.client_id = var_in_client_id
group by tc.id, tc.value;
CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
insert into t2
select sum(t.item_count)
from t;
select concat('(', t.tc_parent, ') ', t.tc_value) as sector, 
t1.item_count as meeting_count, 
t.item_count/t2.item_count as activity_percentage, 
t1.item_count/t.item_count as conversion_percentage
from t2, t
left join t1 on t.tc_id = t1.tc_id
where round((t.item_count/t2.item_count)*100) > 0
order by activity_percentage desc;
DROP TABLE IF EXISTS `t`;
DROP TABLE IF EXISTS `t1`;
DROP TABLE IF EXISTS `t2`;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_detail_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_detail_data`(	var_in_start datetime, 
													var_in_end datetime, 
													var_in_client_id int(11), 
													var_in_project_ref text, 
													var_in_effectives_filter int(11))
BEGIN
	
	
	
	
	CASE var_in_effectives_filter WHEN 1 THEN
		
		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 1 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 2 THEN
	
		
	
		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 0 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 3 THEN
	
		
	
		CREATE TEMPORARY table t1 
		SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications 
		WHERE communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	END CASE;
	
	
	
	CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN
		CASE var_in_effectives_filter WHEN 1 THEN
	
			
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		WHEN 2 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		WHEN 3 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		
		END CASE;
	ELSE
		CASE var_in_effectives_filter WHEN 1 THEN
	
			
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		WHEN 2 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		WHEN 3 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
		END CASE;
	END CASE;
	
	
	
	
	DROP TEMPORARY table t1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_detail_data_order_by_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_detail_data_order_by_company`(	var_in_start datetime, 
																		var_in_end datetime, 
																		var_in_client_id int(11), 
																		var_in_project_ref text, 
																		var_in_effectives_filter int(11))
BEGIN
	
	
	
	
	CASE var_in_effectives_filter WHEN 1 THEN
		
		CREATE TEMPORARY table t1 
		SELECT id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 1 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 2 THEN
	
		
	
		CREATE TEMPORARY table t1 
		SELECT id, communication_date, note_id FROM tbl_communications 
		WHERE is_effective = 0 
		AND communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	WHEN 3 THEN
	
		
	
		CREATE TEMPORARY table t1 
		SELECT id, communication_date, note_id FROM tbl_communications 
		WHERE communication_date >= var_in_start 
		AND communication_date <= var_in_end;
	
	END CASE;
	
	
	
	CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN
		CASE var_in_effectives_filter WHEN 1 THEN
	
			
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY comp.name, comm.communication_date;
		
		WHEN 2 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY comp.name, comm.communication_date;
		
		WHEN 3 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id
			ORDER BY comp.name, comm.communication_date;
		
		END CASE;
	ELSE
		CASE var_in_effectives_filter WHEN 1 THEN
	
			
	
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id 
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY comp.name, comm.communication_date;
		WHEN 2 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id 
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY comp.name, comm.communication_date;
		WHEN 3 THEN
		
			
		
			SELECT pi.id AS post_initiative_id, 
			comp.id AS company_id, 
			comp.name AS company, 
			cs1.sort_order, 
			cs1.id AS status_id, 
			cs1.description AS status, 
			comm.communication_date AS date, 
			pin.note AS note, 
			cli.name AS client, 
			post.id AS post_id, 
			post.job_title, 
			IFNULL(post.full_name, '') AS full_name,
			f_get_site_address(comp.id) AS address
			FROM tbl_post_initiatives as pi 
			INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
			INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
			INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id 
			INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id 
			INNER JOIN tbl_companies AS comp ON post.company_id = comp.id 
			INNER JOIN tbl_sites AS site ON comp.id = site.company_id 
			INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id 
			INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id 
			INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id 
			INNER JOIN tbl_tags AS t ON pit.tag_id = t.id 
			LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
			WHERE cli.id = var_in_client_id 
			AND t.value = var_in_project_ref 
			AND t.category_id = 3 
			ORDER BY comp.name, comm.communication_date;
		END CASE;
	END CASE;
	
	
	
	
	DROP TEMPORARY table t1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_notes_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_notes_detail`(var_in_date datetime, var_in_post_initiative_id int(11), var_in_effectives_filter int(11))
BEGIN
	CASE var_in_effectives_filter WHEN 1 THEN
	
		
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		LEFT JOIN tbl_communications AS comm ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND comm.is_effective = 1 
		AND pin.for_client = 1 
		AND comm.communication_date <= var_in_date 
		ORDER by pin.created_at DESC;
	WHEN 2 THEN
	
		
	
		CREATE TEMPORARY table t1 
		SELECT pin.id, pin.post_initiative_id, pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND pin.for_client = 1 
		AND pin.created_at <= var_in_date;
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_communications AS comm 
		LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		LEFT JOIN t1 ON comm.note_id = t1.id 
		WHERE comm.post_initiative_id = var_in_post_initiative_id 
		AND comm.communication_date <= var_in_date 
		AND comm.is_effective = 0 
		ORDER by comm.communication_date DESC;
		DROP TEMPORARY table t1;
	
	
	WHEN 3 THEN
	
		
		
		CREATE TEMPORARY table t1 
		SELECT pin.id, pin.post_initiative_id, pin.note AS note 
		FROM tbl_post_initiative_notes AS pin 
		WHERE pin.post_initiative_id = var_in_post_initiative_id 
		AND pin.for_client = 1 
		AND pin.created_at <= var_in_date;
		
		SELECT cs.description AS status, 
		comm.communication_date AS date, 
		pin.note AS note 
		FROM tbl_communications AS comm 
		LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id 
		LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id 
		LEFT JOIN t1 ON comm.note_id = t1.id 
		WHERE comm.post_initiative_id = var_in_post_initiative_id 
		AND comm.communication_date <= var_in_date 
		ORDER by comm.communication_date DESC;
		DROP TEMPORARY table t1;
	END CASE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_5_summary_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_5_summary_data`(	var_in_start datetime, 
														var_in_end datetime, 
														var_in_client_id int(11),
														var_in_project_ref text)
BEGIN
CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN
	
	CREATE TEMPORARY TABLE t1_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id 
	INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	WHERE cam.client_id = var_in_client_id 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
	
	
	
	CREATE TEMPORARY TABLE t2_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS non_effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id 
	LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	WHERE comm.is_effective = 0 
	AND cam.client_id = var_in_client_id 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
ELSE
	
	CREATE TEMPORARY TABLE t1_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id 
	INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id 
	LEFT JOIN tbl_tags t on pit.tag_id = t.id 
	WHERE cam.client_id = var_in_client_id 
	AND t.value = var_in_project_ref 
	AND t.category_id = 3 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
			
	
	CREATE TEMPORARY TABLE t2_rpt5 
	SELECT cs.id AS status_id, 
	COUNT(comm.id) AS non_effectives 
	FROM tbl_lkp_communication_status AS cs 
	LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id 
	LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id 
	LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
	LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
	LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id 
	LEFT JOIN tbl_tags t on pit.tag_id = t.id 
	WHERE comm.is_effective = 0 
	AND cam.client_id = var_in_client_id 
	AND t.value = var_in_project_ref 
	AND t.category_id = 3 
	AND comm.communication_date >= var_in_start 
	AND comm.communication_date <= var_in_end 
	GROUP BY cs.id;
END CASE;
SELECT cs.id AS status_id, cs.description, cs.full_description, 
IFNULL(t1.effectives, 0) AS effectives, 
IFNULL(t2.non_effectives, 0) AS non_effectives 
FROM tbl_lkp_communication_status AS cs 
LEFT JOIN t1_rpt5 AS t1 ON cs.id = t1.status_id 
LEFT JOIN t2_rpt5 AS t2 ON cs.id = t2.status_id 
ORDER BY cs.sort_order DESC;
DROP TEMPORARY table t1_rpt5;
DROP TEMPORARY table t2_rpt5;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_7_rearranged_meetings` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_7_rearranged_meetings`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
drop table if exists t1;
create temporary table t1
select ms.id
from tbl_meetings_shadow ms
join tbl_post_initiatives pi on pi.id = ms.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where ms.created_at < var_in_date_start
and ms.shadow_timestamp >= var_in_date_start
and ms.shadow_timestamp <= var_in_date_end
AND c.client_id = var_in_client_id
AND ms.status_id IN (18, 19)
GROUP BY ms.id;
SELECT m.id, CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, 
EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, 
lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
from t1
join tbl_meetings m on t1.id = m.id
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
join vw_posts_contacts p on pi.post_id = p.id
join tbl_companies co on co.id = p.company_id
ORDER BY category DESC, `year_month`, date;
drop table if exists t1;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Key_To_Terms` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Key_To_Terms`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
drop table if exists t;
create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
insert into t
select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
group by com.post_initiative_id;
update t set effective_count = 1 where effective_count > 1;
drop table if exists t1;
create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
insert into t1
select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
from tbl_communications com
join t on t.max_com_id = com.id
group by com.status_id;
select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
from tbl_lkp_communication_status cs
left join t1 on t1.status_id = cs.id
order by cs.report_sort_order;
drop temporary table t;
drop temporary table t1;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Key_To_Terms_All` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Key_To_Terms_All`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
begin
drop table if exists t;
create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
if var_in_filter_id > 0 then
insert into t
select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
join tbl_filter_results fr on pi.id = fr.post_initiative_id 
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
and fr.filter_id = var_in_filter_id
group by com.post_initiative_id;
else
insert into t
select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
group by com.post_initiative_id;
end if;
update t set effective_count = 1 where effective_count > 1;
drop table if exists t1;
create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
insert into t1
select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
from tbl_communications com
join t on t.max_com_id = com.id
group by com.status_id;
select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
from tbl_lkp_communication_status cs
left join t1 on t1.status_id = cs.id
order by cs.report_sort_order;
drop temporary table t;
drop temporary table t1;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Key_To_Terms_Only_Communications` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Key_To_Terms_Only_Communications`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
begin
	
drop table if exists t;
create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
if var_in_filter_id > 0 then
insert into t
select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
join tbl_filter_results fr on pi.id = fr.post_initiative_id 
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
and fr.filter_id = var_in_filter_id
group by com.post_initiative_id;
else
insert into t
select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
from tbl_communications com
left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
left join tbl_initiatives i on i.id = pi.initiative_id
left join tbl_campaigns cam on i.campaign_id = cam.id
left join tbl_clients cl on cam.client_id = cl.id
where communication_date >= var_in_date_start
and communication_date <= var_in_date_end
and cl.id = var_in_client_id
and com.type_id = 1
group by com.post_initiative_id;
end if;
update t set effective_count = 1 where effective_count > 1;
drop table if exists t1;
create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
insert into t1
select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
from tbl_communications com
join t on t.max_com_id = com.id
group by com.status_id;
select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
from tbl_lkp_communication_status cs
join t1 on t1.status_id = cs.id
where effective_count > 0
order by cs.report_sort_order;
drop temporary table t;
drop temporary table t1;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Meetings_Reconciliation` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Meetings_Reconciliation`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
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
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_period_results_calls` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_period_results_calls`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
begin
DECLARE filter_format varchar(100);
DROP TABLE IF EXISTS `t_meetings`;
CREATE TEMPORARY TABLE `t_meetings` ( 
`meeting_id` int(11),
INDEX `ix_meeting_id` (meeting_id));
insert into t_meetings
select max(id)
from tbl_meetings
where status_id >= 12
group by post_initiative_id;
DROP TABLE IF EXISTS `t_meetings_1`;
CREATE TEMPORARY TABLE `t_meetings_1` ( 
`post_initiative_id` int(11), 
`meeting_id` int(11),
`meeting_date` datetime, 
INDEX `ix_post_initiative_id` (post_initiative_id),
INDEX `ix_meeting_id` (meeting_id));
insert into t_meetings_1
select post_initiative_id, id, date
from tbl_meetings
join t_meetings on tbl_meetings.id = t_meetings.meeting_id;
DROP TABLE IF EXISTS `t_post_initiatives`;
CREATE TEMPORARY TABLE `t_post_initiatives` ( 
`post_initiative_id` int(11), 
INDEX `ix_post_initiative_id` (post_initiative_id));
if var_in_filter_id > 0 then
BEGIN
	insert into t_post_initiatives
	select pi.id
	from tbl_communications com 
	join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
	join tbl_initiatives i on pi.initiative_id = i.id
	join tbl_campaigns cam on i.campaign_id = cam.id
	where 
	com.type_id = 1
	and com.is_effective = 1
	and com.communication_date >= var_in_date_start
	and com.communication_date <= var_in_date_end
	and cam.client_id = var_in_client_id
	group by pi.id;
	SELECT results_format INTO filter_format 
	FROM tbl_filters 
	WHERE id = var_in_filter_id;
	CASE filter_format
		WHEN 'Company' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on c.id = fr.company_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
	
		WHEN 'Client initiative with last note' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on pi.id = fr.post_initiative_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
		WHEN 'Client initiative' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on pi.id = fr.post_initiative_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
		WHEN 'Company and posts' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on p.id = fr.post_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
		WHEN 'Mailer' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on pi.id = fr.post_initiative_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
		WHEN 'Meeting' THEN 
		BEGIN
		select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
		pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
		from tbl_post_initiatives pi
		join t_post_initiatives t on t.post_initiative_id = pi.id
		join vw_posts p on p.id = pi.post_id
		join tbl_companies c on p.company_id = c.id
		join vw_contacts con on con.post_id = p.id
		join tbl_lkp_communication_status cs on cs.id = pi.status_id
		left join t_meetings_1 m on m.post_initiative_id = pi.id
		join tbl_clients cl on cl.id = pi.next_action_by
		join tbl_filter_results fr on pi.id = fr.post_initiative_id 
		where fr.filter_id = var_in_filter_id
		order by cs.report_sort_order, c.name;
		END;
	      ELSE
        		BEGIN
	        	END;
    	END CASE;
END;
else
BEGIN
	insert into t_post_initiatives
	select pi.id
	from tbl_communications com 
	join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
	join tbl_initiatives i on pi.initiative_id = i.id
	join tbl_campaigns cam on i.campaign_id = cam.id
	where 
	com.type_id = 1
	and com.is_effective = 1
	and com.communication_date >= var_in_date_start
	and com.communication_date <= var_in_date_end
	and cam.client_id = var_in_client_id
	group by pi.id;
	select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status, 
	pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
	from tbl_post_initiatives pi
	join t_post_initiatives t on t.post_initiative_id = pi.id
	join vw_posts p on p.id = pi.post_id
	join tbl_companies c on p.company_id = c.id
	join vw_contacts con on con.post_id = p.id
	join tbl_lkp_communication_status cs on cs.id = pi.status_id
	left join t_meetings_1 m on m.post_initiative_id = pi.id
	join tbl_clients cl on cl.id = pi.next_action_by
	order by cs.report_sort_order, c.name;
	END;
end if;
drop table if exists `t_meetings`;
drop table if exists `t_meetings_1`;
drop table if exists `t_post_initiatives`;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_sector_penetration` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_sector_penetration`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
declare tmp int(11);
DROP TABLE IF EXISTS `t_companies_with_calls`;
CREATE TEMPORARY TABLE `t_companies_with_calls` ( 
`company_id` int(11), 
`sector_id` int(11), 
`calls` int(11), 
`effectives` int(11), 
INDEX `ix_company_id` (`company_id`), 
INDEX `ix_sector_id` (sector_id));
insert into t_companies_with_calls
select p.company_id, tc.id, count(distinct com.id) as calls, sum(com.is_effective) as effectives
from tbl_communications com 
join tbl_post_initiatives pi on com.post_initiative_id = pi.id 
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
join tbl_posts p on pi.post_id = p.id 
join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id 
join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
where 
com.type_id = 1
and tc.category_id = 1 
and tc.parent_id = 0 
and com.communication_date >= var_in_date_start
and com.communication_date <= var_in_date_end
and cam.client_id = var_in_client_id
group by p.company_id, tc.id;
DROP TABLE IF EXISTS `t_company_meetings`;
CREATE TEMPORARY TABLE `t_company_meetings` ( 
`company_id` int(11), 
`meetings_set` int(11), 
INDEX `ix_company_id` (`company_id`));
insert into t_company_meetings
select p.company_id, count(distinct m.id) as meetings
from tbl_meetings m 
join tbl_post_initiatives pi on m.post_initiative_id = pi.id 
join tbl_posts p on p.id = pi.post_id
join t_companies_with_calls cwc on cwc.company_id = p.company_id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns cam on i.campaign_id = cam.id
where m.created_at >= var_in_date_start
and m.created_at <= var_in_date_end
and cam.client_id = var_in_client_id
group by p.company_id;
DROP TABLE IF EXISTS `t_companies_with_effectives_and_weighting`;
CREATE TEMPORARY TABLE `t_companies_with_effectives_and_weighting` (
`company_id` int(11), 
`sector_id` int(11), 
`weighting` int(11), 
`calls` int(11), 
`effectives` int(11), 
`meetings_set` int(11), 
INDEX 	`ix_company_id` (`company_id`), 
INDEX `ix_sector_id` (sector_id),
INDEX `ix_weighting` (weighting),
INDEX `ix_calls` (calls),
INDEX `ix_effectives` (effectives),
INDEX `ix_meetings_set` (meetings_set)
);
insert into t_companies_with_effectives_and_weighting
select t_cwe.company_id, t_cwe.sector_id, cs.weighting, t_cwe.calls, t_cwe.effectives, t_cm.meetings_set
from t_companies_with_calls t_cwe
left join t_company_meetings t_cm on t_cm.company_id = t_cwe.company_id
left join tbl_campaign_sectors cs on cs.tiered_characteristic_id = t_cwe.sector_id  and cs.campaign_id = var_in_client_id;
DROP TABLE IF EXISTS `t_company_max_sector_id`;
CREATE TEMPORARY TABLE `t_company_max_sector_id` (
`company_id` int(11), 
`sector_id` int(11), 
`weighting` int(11), 
INDEX 	`ix_company_id` (`company_id`), 
INDEX `ix_sector_id` (sector_id),
INDEX `ix_weighting` (weighting)
);
set @num := 0, @company_id := '';
insert into t_company_max_sector_id
select company_id, sector_id, weighting
from (
   select company_id, sector_id, weighting,
      @num := if(@company_id = company_id, @num + 1, 1) as row_number,
      @company_id := company_id as dummy
  from t_companies_with_effectives_and_weighting
  order by company_id, weighting desc, sector_id 
) as x where x.row_number <= 1 
order by company_id;
DROP TABLE IF EXISTS `t_target_sector_stats`;
CREATE TEMPORARY TABLE `t_target_sector_stats` (
`sector_id` int(11), 
`calls` int(11), 
`effectives` int(11), 
`meetings_set` int(11), 
`weighting` int(11), 
INDEX `ix_sector_id` (sector_id),
INDEX `ix_calls` (calls),
INDEX `ix_effectives` (effectives),
INDEX `ix_meetings_set` (meetings_set),
INDEX `ix_weighting` (weighting)
);
insert into t_target_sector_stats
select t_cwew.sector_id, sum(t_cwew.calls),  sum(t_cwew.effectives), sum(t_cwew.meetings_set), min(t_cwew.weighting)
from t_company_max_sector_id t_cms
join t_companies_with_effectives_and_weighting t_cwew on t_cwew.company_id = t_cms.company_id and t_cwew.sector_id = t_cms.sector_id
where t_cwew.weighting is not null
group by t_cwew.sector_id
limit 10;
insert into t_target_sector_stats
select null, sum(t_cwew.calls),  sum(t_cwew.effectives), sum(t_cwew.meetings_set), min(t_cwew.weighting)
from t_company_max_sector_id t_cms
join t_companies_with_effectives_and_weighting t_cwew on t_cwew.company_id = t_cms.company_id and t_cwew.sector_id = t_cms.sector_id
where t_cwew.weighting is null
group by t_cwew.weighting;
set @total_calls := 0;
select sum(calls) INTO @total_calls
from t_target_sector_stats;
select tc.value as sector, t_ts.calls,  ROUND((t_ts.calls/@total_calls * 100),1) as call_percentage, t_ts.meetings_set, ROUND((t_ts.effectives / t_ts.calls * 100),1) as access, ROUND((t_ts.meetings_set / t_ts.effectives * 100),1) as conversion
from t_target_sector_stats t_ts
left join tbl_tiered_characteristics tc on tc.id = t_ts.sector_id
order by t_ts.weighting desc;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Campaign` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Summary_Figures_For_Campaign`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
	
DECLARE end_year_month varchar(6);
DECLARE meets_set_target_to_date INT;
DECLARE meets_attended_target_to_date INT;
DECLARE meets_set_to_date INT;
DECLARE meets_attended_to_date INT;
DECLARE meets_potential_attended INT;
DECLARE meets_in_diary INT;
DECLARE meets_lapsed_tbr INT;
DECLARE meets_lapsed_cancelled INT;
DECLARE tmp INT;
DECLARE strong_callbacks INT;
select 0 into strong_callbacks;
select count(pi.id) INTO tmp 
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and status_id in (4,5,6);
select strong_callbacks + tmp INTO strong_callbacks;
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
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
delete from t_strong_callbacks;
insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
drop temporary table if exists t;
select sum(meetings_set) INTO meets_set_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meetings_attended) INTO meets_attended_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(m.id) 
INTO meets_set_to_date
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end;
select count(m.id) 
INTO meets_attended_to_date
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m.date >= var_in_date_start 
and m.date <= var_in_date_end
and m.created_at >= var_in_date_start
and m.created_at <= var_in_date_end
and m.status_id >= 24;
select count(m.id)
INTO meets_potential_attended
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m.date >= var_in_date_start 
and m.date <= var_in_date_end
and m.created_at >= var_in_date_start
and m.created_at <= var_in_date_end
and m.status_id in (12,13,18,19);
select count(m.id) INTO meets_in_diary
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
select count(m.id)
INTO meets_lapsed_tbr
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date > var_in_date_start
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id in (14,15,16,17);
select count(m.id)
INTO meets_lapsed_cancelled
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end
and m.status_id in (20,21,22,23);
create temporary table t (
meets_set_target_to_date int(11), 
meets_attended_target_to_date int(11), 
meets_set_to_date int(11), 
meets_attended_to_date int(11),
meets_potential_attended int(11), 
meets_in_diary int(11),
meets_lapsed_tbr int(11), 
meets_lapsed_cancelled int(11),
strong_callbacks_pipeline int(11)
);
insert into t (
meets_set_target_to_date, 
meets_attended_target_to_date, 
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_in_diary,
meets_lapsed_tbr,
meets_lapsed_cancelled,
strong_callbacks_pipeline)
values (
meets_set_target_to_date, 
meets_attended_target_to_date,
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_in_diary,
meets_lapsed_tbr,
meets_lapsed_cancelled,
strong_callbacks);
select * from t;
drop temporary table t;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Campaign_dev` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Summary_Figures_For_Campaign_dev`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
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
DECLARE tmp INT;
DECLARE strong_callbacks INT;
select 0 into strong_callbacks;
select count(pi.id) INTO tmp 
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and status_id in (4,5,6);
select strong_callbacks + tmp INTO strong_callbacks;
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
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
delete from t_strong_callbacks;
insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
drop temporary table if exists t;
select sum(meetings_set) INTO meets_set_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meetings_attended) INTO meets_attended_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(m.id) 
INTO meets_set_to_date
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end;
select count(m.id) 
INTO meets_attended_to_date
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
select count(m.id)
INTO meets_potential_attended
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
select count(m.id) INTO meets_live
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
select count(m.id)
INTO meets_lapsed_tbr
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
select count(m.id)
INTO meets_lapsed_cancelled
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
create temporary table t (
meets_set_target_to_date int(11), 
meets_attended_target_to_date int(11), 
meets_set_to_date int(11), 
meets_attended_to_date int(11),
meets_potential_attended int(11), 
meets_live int(11),
meets_lapsed_tbr int(11), 
meets_lapsed_cancelled int(11),
strong_callbacks_pipeline int(11)
);
insert into t (
meets_set_target_to_date, 
meets_attended_target_to_date, 
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled,
strong_callbacks_pipeline)
values (
meets_set_target_to_date, 
meets_attended_target_to_date,
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled,
strong_callbacks);
select * from t;
drop temporary table t;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Current_Month` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Summary_Figures_For_Current_Month`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
DECLARE end_year_month varchar(6);
DECLARE meets_set_to_date INT;
DECLARE meets_attended_to_date INT;
DECLARE meets_potential_attended INT;
DECLARE meets_live INT;
DECLARE meets_lapsed_tbr INT;
DECLARE meets_lapsed_cancelled INT;
DECLARE strong_callbacks INT;
SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
select sum(meeting_set_count) INTO meets_set_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_attended_count) INTO meets_attended_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_unknown_count) INTO meets_potential_attended
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(m.id) INTO meets_live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);
select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_cancelled_count) INTO meets_lapsed_cancelled
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(pi.id) INTO strong_callbacks
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where next_communication_date > var_in_date_end
and c.client_id = var_in_client_id
AND pi.status_id in (4,5,6);
create temporary table t (
meets_set_to_date int(11), 
meets_attended_to_date int(11),
meets_potential_attended int(11), 
meets_live int(11),
meets_lapsed_tbr int(11), 
meets_lapsed_cancelled int(11),
strong_callbacks int(11)
);
insert into t (
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled, 
strong_callbacks)
values (
meets_set_to_date,
meets_attended_to_date,
meets_potential_attended,
meets_live,
meets_lapsed_tbr,
meets_lapsed_cancelled, 
strong_callbacks);
select * from t;
drop temporary table t;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Period` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Summary_Figures_For_Period`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
DECLARE meets_set INT;
DECLARE meets_in_diary INT;
DECLARE strong_callbacks INT;
DECLARE meets_attended INT;
DECLARE meets_awaiting_feedback INT;
DECLARE meets_tbr INT;
DECLARE meets_cancelled INT;
DECLARE tmp INT;
select 0 into strong_callbacks;
select count(pi.id) INTO tmp 
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and status_id in (4,5,6);
select strong_callbacks + tmp INTO strong_callbacks;
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
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
delete from t_strong_callbacks;
insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
select count(m.id) 
INTO meets_set
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. created_at >= var_in_date_start
and m. created_at <= var_in_date_end;
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
select count(m.id) INTO meets_in_diary
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
WHERE 
m.date > var_in_date_end
AND c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);
select count(m.id)
INTO meets_tbr
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date > var_in_date_start
and m.status_id in (14,15,16,17);
select count(m.id)
INTO meets_cancelled
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start
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
select * from t;
drop temporary table if exists t;
drop temporary table if exists t_comm_max;
drop temporary table if exists t_strong_callbacks;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Summary_Figures_For_Period_dev` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Summary_Figures_For_Period_dev`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
begin
DECLARE meets_in_diary INT;
DECLARE strong_callbacks INT;
DECLARE meets_attended INT;
DECLARE meets_awaiting_feedback INT;
DECLARE meets_tbr INT;
DECLARE tmp INT;
select count(m.id) INTO meets_in_diary
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
WHERE 
m.date > var_in_date_end
AND c.client_id = var_in_client_id
and is_current = 1
AND m.status_id in (12, 13, 18, 19);
select 0 into strong_callbacks;
select count(pi.id) INTO tmp 
from tbl_post_initiatives pi 
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where c.client_id = var_in_client_id
and status_id in (4,5,6);
select strong_callbacks + tmp INTO strong_callbacks;
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
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
delete from t_strong_callbacks;
insert into t_strong_callbacks
select comm.post_initiative_id as post_initiative_id
from tbl_communications comm
join t_comm_max t_cm on comm.id = t_cm.comm_id 
where comm.next_communication_date_reason_id = 1
and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
select count(post_initiative_id) INTO tmp 
from t_strong_callbacks;
select strong_callbacks + tmp INTO strong_callbacks;
select count(m.id) 
INTO meets_attended
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
join tbl_campaigns c on c.id = i.campaign_id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where c.client_id = var_in_client_id
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m.status_id >= 24;
select count(m.id)
INTO meets_awaiting_feedback
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where
i.campaign_id = 1
and m. date >= var_in_date_start 
and m.date <= var_in_date_end
and m.status_id in (12,13,18,19)
and is_current = 1;
select count(m.id)
INTO meets_tbr
from tbl_meetings m
join tbl_post_initiatives pi on m.post_initiative_id = pi.id
join tbl_initiatives i on pi.initiative_id = i.id
left join tbl_lkp_communication_status cs on m.status_id = cs.id
where
i.campaign_id = 1
and m. date > var_in_date_start
and m.status_id in (14,15,16,17)
and is_current = 1;
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
sum(meeting_set_count) AS meeting_set_count,
meets_attended,
meets_awaiting_feedback,
meets_in_diary as meets_in_diary,
meets_tbr,
sum(meeting_category_cancelled_count) AS meets_lapsed_cancelled,
strong_callbacks
from 
tbl_data_statistics_daily ds
join tbl_campaigns cam on ds.campaign_id = cam.id
WHERE date >= var_in_date_start
AND date <= var_in_date_end
AND cam.client_id = var_in_client_id;
select * from t;
drop temporary table if exists t;
drop temporary table if exists t_comm_max;
drop temporary table if exists t_strong_callbacks;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_report_8_Topline_Summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_8_Topline_Summary`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
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
drop temporary table if exists t;
select sum(meetings_set) INTO meets_set_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meetings_attended) INTO meets_attended_target_to_date
from tbl_campaign_targets
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_set_count) INTO meets_set_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_attended_count) INTO meets_attended_to_date
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select sum(meeting_category_unknown_count) INTO meets_potential_attended
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
select count(m.id) INTO meets_live
from tbl_meetings m 
join tbl_post_initiatives pi on pi.id = m.post_initiative_id
join tbl_initiatives i on pi.initiative_id = i.id 
join tbl_campaigns c on c.id = i.campaign_id
where m.date > var_in_date_end
and c.client_id = var_in_client_id
AND m.status_id in (12, 13, 18, 19);
select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
from tbl_data_statistics
where campaign_id = var_in_client_id
and `year_month` <= end_year_month;
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
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vw_calendar_information_requests`
--

/*!50001 DROP TABLE IF EXISTS `vw_calendar_information_requests`*/;
/*!50001 DROP VIEW IF EXISTS `vw_calendar_information_requests`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_calendar_information_requests` AS select `ir`.`id` AS `id`,`ir`.`post_initiative_id` AS `post_initiative_id`,`ir`.`status_id` AS `status_id`,`ir`.`date` AS `date`,`ir`.`reminder_date` AS `reminder_date`,`ir`.`notes` AS `notes`,`ir`.`created_at` AS `created_at`,`ir`.`created_by` AS `created_by`,`ir`.`communication_id` AS `communication_id`,`ir`.`type_id` AS `type_id`,`ir`.`comm_type_id` AS `comm_type_id`,`cli`.`id` AS `client_id`,`cli`.`name` AS `client`,`c`.`id` AS `company_id`,`c`.`name` AS `company` from ((((((`tbl_information_requests` `ir` join `tbl_post_initiatives` `pi` on((`ir`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) join `tbl_companies` `c` on((`p`.`company_id` = `c`.`id`))) join `tbl_initiatives` `i` on((`pi`.`initiative_id` = `i`.`id`))) join `tbl_campaigns` `cam` on((`i`.`campaign_id` = `cam`.`id`))) join `tbl_clients` `cli` on((`cam`.`client_id` = `cli`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_calendar_meetings`
--

/*!50001 DROP TABLE IF EXISTS `vw_calendar_meetings`*/;
/*!50001 DROP VIEW IF EXISTS `vw_calendar_meetings`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_calendar_meetings` AS select `m`.`id` AS `id`,`m`.`post_initiative_id` AS `post_initiative_id`,`m`.`communication_id` AS `communication_id`,`m`.`is_current` AS `is_current`,`m`.`status_id` AS `status_id`,`m`.`type_id` AS `type_id`,`m`.`date` AS `date`,`m`.`reminder_date` AS `reminder_date`,`m`.`notes` AS `notes`,`m`.`created_at` AS `created_at`,`m`.`created_by` AS `created_by`,`m`.`modified_by` AS `modified_by`,`m`.`modified_at` AS `modified_at`,`m`.`location_id` AS `location_id`,`m`.`nbm_predicted_rating` AS `nbm_predicted_rating`,`m`.`feedback_rating` AS `feedback_rating`,`m`.`feedback_decision_maker` AS `feedback_decision_maker`,`m`.`feedback_agency_user` AS `feedback_agency_user`,`m`.`feedback_budget_available` AS `feedback_budget_available`,`m`.`feedback_receptive` AS `feedback_receptive`,`m`.`feedback_targeting` AS `feedback_targeting`,`m`.`feedback_meeting_length` AS `feedback_meeting_length`,`m`.`feedback_comments` AS `feedback_comments`,`m`.`feedback_next_steps` AS `feedback_next_steps`,`cli`.`id` AS `client_id`,`cli`.`name` AS `client`,`c`.`id` AS `company_id`,`c`.`name` AS `company`,`pi`.`post_id` AS `post_id`,`pi`.`initiative_id` AS `initiative_id` from ((((((`tbl_meetings` `m` join `tbl_post_initiatives` `pi` on((`m`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) join `tbl_companies` `c` on((`p`.`company_id` = `c`.`id`))) join `tbl_initiatives` `i` on((`pi`.`initiative_id` = `i`.`id`))) join `tbl_campaigns` `cam` on((`i`.`campaign_id` = `cam`.`id`))) join `tbl_clients` `cli` on((`cam`.`client_id` = `cli`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_client_initiatives`
--

/*!50001 DROP TABLE IF EXISTS `vw_client_initiatives`*/;
/*!50001 DROP VIEW IF EXISTS `vw_client_initiatives`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_client_initiatives` AS select `c`.`id` AS `client_id`,`c`.`name` AS `client_name`,`cm`.`id` AS `campaign_id`,`i`.`id` AS `initiative_id`,`i`.`name` AS `initiative_name` from ((`tbl_clients` `c` join `tbl_campaigns` `cm` on((`c`.`id` = `cm`.`client_id`))) left join `tbl_initiatives` `i` on((`cm`.`id` = `i`.`campaign_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_communication_max_date_by_post_initiative_id`
--

/*!50001 DROP TABLE IF EXISTS `vw_communication_max_date_by_post_initiative_id`*/;
/*!50001 DROP VIEW IF EXISTS `vw_communication_max_date_by_post_initiative_id`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_communication_max_date_by_post_initiative_id` AS select max(`com`.`communication_date`) AS `max(communication_date)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_communication_max_id_by_post_initiative_id`
--

/*!50001 DROP TABLE IF EXISTS `vw_communication_max_id_by_post_initiative_id`*/;
/*!50001 DROP VIEW IF EXISTS `vw_communication_max_id_by_post_initiative_id`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_communication_max_id_by_post_initiative_id` AS select max(`com`.`id`) AS `max(id)`,max(`com`.`communication_date`) AS `max(communication_date)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_companies`
--

/*!50001 DROP TABLE IF EXISTS `vw_companies`*/;
/*!50001 DROP VIEW IF EXISTS `vw_companies`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_companies` AS select `tbl_companies`.`id` AS `id`,`tbl_companies`.`name` AS `name`,`tbl_companies`.`website` AS `website`,`tbl_companies`.`telephone` AS `telephone`,`tbl_companies`.`telephone_tps` AS `telephone_tps`,`tbl_companies`.`deleted` AS `deleted` from `tbl_companies` where (`tbl_companies`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_companies_sites`
--

/*!50001 DROP TABLE IF EXISTS `vw_companies_sites`*/;
/*!50001 DROP VIEW IF EXISTS `vw_companies_sites`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_companies_sites` AS select `c`.`id` AS `id`,`c`.`name` AS `name`,`c`.`website` AS `website`,`c`.`telephone` AS `telephone`,`c`.`telephone_tps` AS `telephone_tps`,`c`.`deleted` AS `deleted`,`s`.`id` AS `site_id`,`s`.`name` AS `site_name`,`s`.`address_1` AS `address_1`,`s`.`address_2` AS `address_2`,`s`.`town` AS `town`,`s`.`city` AS `city`,`s`.`postcode` AS `postcode`,`s`.`telephone` AS `site_telephone`,`s`.`county_id` AS `county_id`,`lkp_county`.`name` AS `county`,`s`.`country_id` AS `country_id`,`lkp_country`.`name` AS `country` from (((`tbl_companies` `c` left join `tbl_sites` `s` on((`c`.`id` = `s`.`company_id`))) left join `tbl_lkp_counties` `lkp_county` on((`lkp_county`.`id` = `s`.`county_id`))) left join `tbl_lkp_countries` `lkp_country` on((`lkp_country`.`id` = `s`.`country_id`))) where (`c`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_contacts`
--

/*!50001 DROP TABLE IF EXISTS `vw_contacts`*/;
/*!50001 DROP VIEW IF EXISTS `vw_contacts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_contacts` AS select `tbl_contacts`.`id` AS `id`,`tbl_contacts`.`post_id` AS `post_id`,`tbl_contacts`.`title` AS `title`,`tbl_contacts`.`first_name` AS `first_name`,`tbl_contacts`.`surname` AS `surname`,`tbl_contacts`.`deleted` AS `deleted`,`tbl_contacts`.`email` AS `email`,`tbl_contacts`.`linked_in` AS `linked_in`,`tbl_contacts`.`telephone_mobile` AS `telephone_mobile`,`tbl_contacts`.`full_name` AS `full_name` from `tbl_contacts` where (`tbl_contacts`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_events`
--

/*!50001 DROP TABLE IF EXISTS `vw_events`*/;
/*!50001 DROP VIEW IF EXISTS `vw_events`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_events` AS select `e`.`id` AS `id`,`e`.`subject` AS `subject`,`e`.`notes` AS `notes`,`e`.`date` AS `date`,`e`.`reminder_date` AS `reminder_date`,`e`.`user_id` AS `user_id`,`e`.`type_id` AS `type_id`,`e`.`client_id` AS `client_id`,`t`.`name` AS `type` from (`tbl_events` `e` join `tbl_lkp_event_types` `t` on((`e`.`type_id` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_nbm_meeting_count_between_dates`
--

/*!50001 DROP TABLE IF EXISTS `vw_nbm_meeting_count_between_dates`*/;
/*!50001 DROP VIEW IF EXISTS `vw_nbm_meeting_count_between_dates`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_nbm_meeting_count_between_dates` AS select max(`com`.`id`) AS `max(id)`,`com`.`post_initiative_id` AS `post_initiative_id` from `tbl_communications` `com` group by `com`.`post_initiative_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_post_communication_stats_1`
--

/*!50001 DROP TABLE IF EXISTS `vw_post_communication_stats_1`*/;
/*!50001 DROP VIEW IF EXISTS `vw_post_communication_stats_1`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_post_communication_stats_1` AS select `pi`.`post_id` AS `post_id`,count(`comm`.`id`) AS `communication_count`,sum(`comm`.`is_effective`) AS `effective_count` from (`tbl_communications` `comm` join `tbl_post_initiatives` `pi` on((`comm`.`post_initiative_id` = `pi`.`id`))) group by `pi`.`post_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_post_communication_stats_base`
--

/*!50001 DROP TABLE IF EXISTS `vw_post_communication_stats_base`*/;
/*!50001 DROP VIEW IF EXISTS `vw_post_communication_stats_base`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_post_communication_stats_base` AS select `p`.`id` AS `post_id`,1 AS `comm_count`,(case `comm`.`effective` when 'effective' then 1 else 0 end) AS `eff_count` from ((`tbl_communications` `comm` join `tbl_post_initiatives` `pi` on((`comm`.`post_initiative_id` = `pi`.`id`))) join `tbl_posts` `p` on((`pi`.`post_id` = `p`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_posts`
--

/*!50001 DROP TABLE IF EXISTS `vw_posts`*/;
/*!50001 DROP VIEW IF EXISTS `vw_posts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_posts` AS select `tbl_posts`.`id` AS `id`,`tbl_posts`.`company_id` AS `company_id`,`tbl_posts`.`job_title` AS `job_title`,`tbl_posts`.`propensity` AS `propensity`,`tbl_posts`.`notes` AS `notes`,`tbl_posts`.`deleted` AS `deleted`,`tbl_posts`.`telephone_1` AS `telephone_1`,`tbl_posts`.`telephone_2` AS `telephone_2`,`tbl_posts`.`telephone_switchboard` AS `telephone_switchboard`,`tbl_posts`.`telephone_fax` AS `telephone_fax` from `tbl_posts` where (`tbl_posts`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_posts_contacts`
--

/*!50001 DROP TABLE IF EXISTS `vw_posts_contacts`*/;
/*!50001 DROP VIEW IF EXISTS `vw_posts_contacts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_posts_contacts` AS select `p`.`id` AS `id`,`p`.`company_id` AS `company_id`,`p`.`job_title` AS `job_title`,`p`.`propensity` AS `propensity`,`p`.`telephone_1` AS `telephone_1`,`p`.`telephone_2` AS `telephone_2`,`p`.`telephone_switchboard` AS `telephone_switchboard`,`p`.`telephone_fax` AS `telephone_fax`,`c`.`title` AS `title`,`c`.`first_name` AS `first_name`,`c`.`surname` AS `surname`,`c`.`full_name` AS `full_name`,`c`.`telephone_mobile` AS `telephone_mobile`,`c`.`email` AS `email`,`c`.`linked_in` AS `linked_in`,`p`.`notes` AS `notes` from (`vw_posts` `p` left join `vw_contacts` `c` on((`p`.`id` = `c`.`post_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sites`
--

/*!50001 DROP TABLE IF EXISTS `vw_sites`*/;
/*!50001 DROP VIEW IF EXISTS `vw_sites`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sites` AS select `tbl_sites`.`id` AS `id`,`tbl_sites`.`company_id` AS `company_id`,`tbl_sites`.`name` AS `name`,`tbl_sites`.`address_1` AS `address_1`,`tbl_sites`.`address_2` AS `address_2`,`tbl_sites`.`town` AS `town`,`tbl_sites`.`city` AS `city`,`tbl_sites`.`postcode` AS `postcode`,`tbl_sites`.`telephone` AS `telephone`,`tbl_sites`.`county_id` AS `county_id`,`tbl_sites`.`country_id` AS `country_id`,`tbl_sites`.`deleted` AS `deleted` from `tbl_sites` where (`tbl_sites`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_tags_project_ref`
--

/*!50001 DROP TABLE IF EXISTS `vw_tags_project_ref`*/;
/*!50001 DROP VIEW IF EXISTS `vw_tags_project_ref`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_tags_project_ref` AS select `pit`.`post_initiative_id` AS `post_initiative_id`,`t`.`value` AS `value` from ((`tbl_post_initiative_tags` `pit` join `tbl_tags` `t` on((`pit`.`tag_id` = `t`.`id`))) join `tbl_tag_categories` `tc` on((`t`.`category_id` = `tc`.`id`))) where (`tc`.`id` = 3) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-06 15:26:14
