SET FOREIGN_KEY_CHECKS = 0;
--
-- Table structure for table `tbl_meetings_shadow`
--

ALTER TABLE tbl_meetings_shadow ADD KEY `ix_tbl_meetings_shadow_date` (`date`);
ALTER TABLE tbl_meetings_shadow ADD KEY `ix_tbl_meetings_shadow_created_by` (`created_by`);
ALTER TABLE tbl_meetings_shadow ADD KEY `ix_tbl_meetings_shadow_created_at` (`created_at`);
  
--
-- Table structure for table `tbl_clients`
--
DROP TABLE IF EXISTS `tbl_clients`;
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
  CONSTRAINT `tbl_clients_ibfk2` FOREIGN KEY (`county_id`) REFERENCES `tbl_lkp_counties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_clients_ibfk3` FOREIGN KEY (`country_id`) REFERENCES `tbl_lkp_countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_clients (name, address_1, address_2, city, postcode, telephone) 
values ('Alchemis', 'Flitcroft House', '114-116 Charing Cross Road', 'London', 'WC2H 0JR','020 7836 3678');

--
-- Table structure for table `tbl_clients_seq`
--
DROP TABLE IF EXISTS `tbl_clients_seq`;
CREATE TABLE `tbl_clients_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_clients_seq (sequence) values (1);
ALTER TABLE tbl_clients_seq auto_increment = 2;

--
-- Table structure for table `tbl_clients_shadow`
--
DROP TABLE IF EXISTS `tbl_clients_shadow`;
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

INSERT INTO tbl_clients_shadow (shadow_updated_by, shadow_type, name, address_1, address_2, city, postcode, telephone) 
values (4, 'i', 'Alchemis', 'Flitcroft House', '114-116 Charing Cross Road', 'London', 'WC2H 0JR','020 7836 3678');


--
-- Table structure for table `tbl_campaigns`
--
DROP TABLE IF EXISTS `tbl_campaigns`;
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
  `minimum_duration` tinyint(2) unsigned NOT NULL default '0', -- in months
  `notice_period` tinyint(2) unsigned NOT NULL default '0', -- in months
  `notice_date` datetime default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_client_id` (`client_id`),
  CONSTRAINT `ix_tbl_campaigns_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaigns (client_id, type_id, start_year_month, created_at, created_by)
VALUES (1,1,'200701','2007-01-01 12:00:00',4);

--
-- Table structure for table `tbl_campaigns_seq`
--
DROP TABLE IF EXISTS `tbl_campaigns_seq`;
CREATE TABLE `tbl_campaigns_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaigns_seq (sequence) values (1);
ALTER TABLE tbl_campaigns_seq auto_increment = 2;

--
-- Table structure for table `tbl_campaigns_shadow`
--
DROP TABLE IF EXISTS `tbl_campaigns_shadow`;
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
  `minimum_duration` tinyint(2) unsigned NOT NULL default '0', -- in months
  `notice_period` tinyint(2) unsigned NOT NULL default '0', -- in months
  `notice_date` datetime default NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaigns_shadow (shadow_updated_by, shadow_type, id, client_id, type_id, start_year_month, created_at, created_by)
VALUES (4,'i',1, 1,1,'200701','2007-01-01 12:00:00',4);

--
-- Deletion for table `tbl_campaign_details`
--
DROP TABLE IF EXISTS `tbl_campaign_details`;
DROP TABLE IF EXISTS `tbl_campaign_details_seq`;
DROP TABLE IF EXISTS `tbl_campaign_details_shadow`;

--
-- view `vw_client_initiatives`
--
DROP VIEW IF EXISTS vw_client_initiatives;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_client_initiatives` AS select `c`.`id` AS `client_id`,`c`.`name` AS `client_name`,`cm`.`id` AS `campaign_id`,`i`.`id` AS `initiative_id`,`i`.`name` AS `initiative_name` from ((`tbl_clients` `c` join `tbl_campaigns` `cm` on((`c`.`id` = `cm`.`client_id`))) left join `tbl_initiatives` `i` on((`cm`.`id` = `i`.`campaign_id`))) */;


--
-- Table structure for table `tbl_campaign_targets`
--
DROP TABLE IF EXISTS `tbl_campaign_targets`;
CREATE TABLE `tbl_campaign_targets` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `fee` int(11) NOT NULL default '0',
  `meetings_set` int(3) NOT NULL default '0',
  `meetings_attended` int(3) NOT NULL default '0',
  `opportunities` int(3) NOT NULL default '0',
  `wins` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_campaign_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200701', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200702', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200703', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200704', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200705', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200706', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200707', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200708', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200709', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200710', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200711', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets (campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (1,'200712', 7, 7, 3, 1);

--
-- Table structure for table `tbl_campaign_targets_seq`
--
DROP TABLE IF EXISTS `tbl_campaign_targets_seq`;
CREATE TABLE `tbl_campaign_targets_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaign_targets_seq (sequence) values (12);
ALTER TABLE tbl_campaigns_seq auto_increment = 13;

--
-- Table structure for table `tbl_campaign_targets_shadow`
--
DROP TABLE IF EXISTS `tbl_campaign_targets_shadow`;
CREATE TABLE `tbl_campaign_targets_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `year_month` char(6) NOT NULL default '',
  `fee` int(11) NOT NULL default '0',
  `meetings_set` int(3) NOT NULL default '0',
  `meetings_attended` int(3) NOT NULL default '0',
  `opportunities` int(3) NOT NULL default '0',
  `wins` int(3) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',1, 1,'200701', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',2, 1,'200702', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',3, 1,'200703', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',4, 1,'200704', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',5, 1,'200705', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',6, 1,'200706', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',7, 1,'200707', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',8, 1,'200708', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',9, 1,'200709', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',10, 1,'200710', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',11, 1,'200711', 7, 7, 3, 1);
INSERT INTO tbl_campaign_targets_shadow (shadow_updated_by, shadow_type, id, campaign_id, `year_month`, meetings_set, meetings_attended, opportunities, wins) VALUES (4,'i',12, 1,'200712', 7, 7, 3, 1);

--
-- Table structure for table `tbl_campaign_nbms`
--
DROP TABLE IF EXISTS `tbl_campaign_nbms`;
CREATE TABLE `tbl_campaign_nbms` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `is_lead_nbm` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_nbms_campaign_id` (`campaign_id`),
  KEY `ix_tbl_campaign_nbms_user_id` (`user_id`),
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 22, 1, 1);
--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 47, 0, 1);
--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 58, 0, 1);
--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 57, 0, 1);
--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 56, 0, 1);
--INSERT INTO tbl_campaign_nbms (campaign_id, user_id, is_lead_nbm, is_active) VALUES (1, 54, 0, 1);

--
-- Table structure for table `tbl_campaign_nbms_seq`
--
DROP TABLE IF EXISTS `tbl_campaign_nbms_seq`;
CREATE TABLE `tbl_campaign_nbms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaign_nbms_seq (sequence) values (6);
ALTER TABLE tbl_campaign_nbms_seq auto_increment = 7;

--
-- Table structure for table `tbl_campaign_nbms_shadow`
--
DROP TABLE IF EXISTS `tbl_campaign_nbms_shadow`;
CREATE TABLE `tbl_campaign_nbms_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `campaign_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `is_lead_nbm` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 4, 1, 1);
--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 47, 0, 1);
--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 58, 0, 1);
--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 57, 0, 1);
--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 56, 0, 1);
--INSERT INTO tbl_campaign_nbms_shadow (shadow_updated_by, shadow_type,campaign_id, user_id, is_lead_nbm, is_active) VALUES (4,'i',1, 54, 0, 1);

--
-- Table structure for table `tbl_campaign_regions`
--
DROP TABLE IF EXISTS `tbl_campaign_regions`;
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
DROP TABLE IF EXISTS `tbl_campaign_regions_seq`;
CREATE TABLE `tbl_campaign_regions_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_campaign_nbms_seq (sequence) values (0);
ALTER TABLE tbl_campaign_nbms_seq auto_increment = 1;

--
-- Table structure for table `tbl_campaign_regions_shadow`
--
DROP TABLE IF EXISTS `tbl_campaign_regions_shadow`;
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
-- Table structure for table `tbl_nbm_campaign_targets`
--
DROP TABLE IF EXISTS `tbl_nbm_campaign_targets`;
CREATE TABLE `tbl_nbm_campaign_targets` (
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
  KEY `ix_tbl_nbm_campaign_targets_user_id` (`user_id`),
  KEY `ix_tbl_nbm_campaign_targets_campaign_id` (`campaign_id`),
  CONSTRAINT `ix_tbl_nbm_campaign_targets_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_nbm_campaign_targets_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_nbm_campaign_targets_seq`
--
DROP TABLE IF EXISTS `tbl_nbm_campaign_targets_seq`;
CREATE TABLE `tbl_nbm_campaign_targets_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--INSERT INTO tbl_campaign_targets_seq (sequence) values (12);
--ALTER TABLE tbl_campaigns_seq auto_increment = 13;

--
-- Table structure for table `tbl_nbm_campaign_targets_shadow`
--
DROP TABLE IF EXISTS `tbl_nbm_campaign_targets_shadow`;
CREATE TABLE `tbl_nbm_campaign_targets_shadow` (
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
-- SQL for populating tiered_characteristics from characteristic 'Marketing Speciality'
--
insert into tbl_tiered_characteristics (category_id, value, parent_id) values (1, 'Marketing Services', 0);

insert into tbl_tiered_characteristics (category_id, value, parent_id) 
select 1, name, 1
from tbl_characteristic_elements 
where characteristic_id = 3;

create temporary table t_characteristics
select oc.company_id, ce.name, tc.id
from tbl_object_characteristics oc
join tbl_object_characteristic_elements_boolean oceb on oceb.object_characteristic_id = oc.id 
join tbl_characteristic_elements ce on ce.id = oceb.characteristic_element_id 
join tbl_tiered_characteristics tc on tc.value = ce.name
where oc.characteristic_id = 3;

--insert 'parent' Marketing Services entry for each company
insert into tbl_company_tiered_characteristics (company_id, tiered_characteristic_id, tier)
select company_id, 1, 1
from t_characteristics tc
group by company_id;

--now insert any children for Marketing Services
insert into tbl_company_tiered_characteristics (company_id, tiered_characteristic_id, tier)
select company_id, tc.id, 1
from t_characteristics tc;

drop temporary table if exists t_characteristics;

delete from tbl_characteristics where id = 3;

--
-- Table structure for table `tbl_lkp_lead_source`
--

CREATE TABLE `tbl_lkp_lead_source` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_lead_source_seq`
--

CREATE TABLE `tbl_lkp_lead_source_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

INSERT INTO tbl_lkp_lead_source (description, sort_order) VALUES ('Outgoing mailer', 1);
INSERT INTO tbl_lkp_lead_source (description, sort_order) VALUES ('Incoming Mailer', 2);
INSERT INTO tbl_lkp_lead_source (description, sort_order) VALUES ('Other Incoming', 3);
INSERT INTO tbl_lkp_lead_source (description, sort_order) VALUES ('Outgoing Tactical', 4);

INSERT INTO tbl_lkp_lead_source_seq (sequence) VALUE (4);
ALTER TABLE tbl_lkp_lead_source_seq AUTO_INCREMENT = 5;

--
-- Table structure for `tbl_post_initiatives`
--
ALTER TABLE tbl_post_initiatives ADD COLUMN lead_source_id int(11);
ALTER TABLE tbl_post_initiatives ADD KEY `ix_tbl_post_initiatives_lead_source_id` (`lead_source_id`);
ALTER TABLE tbl_post_initiatives ADD CONSTRAINT `ix_tbl_post_initiatives_ibfk6` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tbl_post_initiatives_shadow ADD COLUMN lead_source_id int(11);

--
-- Table structure for `tbl_communications`
--
ALTER TABLE tbl_communications ADD COLUMN lead_source_id int(11);
ALTER TABLE tbl_communications ADD KEY `ix_tbl_communications_lead_source_id` (`lead_source_id`);
ALTER TABLE tbl_communications ADD CONSTRAINT `ix_tbl_communications_ibfk8` FOREIGN KEY (`lead_source_id`) REFERENCES `tbl_lkp_lead_source` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tbl_communications_shadow ADD COLUMN lead_source_id int(11);

SET FOREIGN_KEY_CHECKS = 1;