SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_campaign_report_summaries`
--
drop table if exists `tbl_campaign_report_summaries`;
CREATE TABLE `tbl_campaign_report_summaries` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL default '',
  `note` text NOT NULL default '',
  `updated_at` datetime,
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_report_summaries_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_report_summaries_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_report_summaries_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_report_summaries_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_campaign_report_summaries_seq`
--
drop table if exists `tbl_campaign_report_summaries_seq`;
CREATE TABLE `tbl_campaign_report_summaries_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


SET FOREIGN_KEY_CHECKS = 1;