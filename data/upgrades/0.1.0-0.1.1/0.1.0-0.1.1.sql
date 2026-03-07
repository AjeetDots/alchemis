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
