SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_filters`
--

ALTER TABLE tbl_filters ADD COLUMN `campaign_id` int(11) DEFAULT NULL;
ALTER TABLE tbl_filters ADD INDEX `ix_tbl_filters_campaign_id` (`campaign_id`);
ALTER TABLE tbl_filters ADD CONSTRAINT `ix_tbl_filters_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Table structure for table `tbl_lkp_filter_types`
--

CREATE TABLE `tbl_lkp_filter_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_filter_types_seq`
--

CREATE TABLE `tbl_lkp_filter_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_filter_types (id, description, sort_order) VALUES (1, 'Personal', 1);
INSERT INTO tbl_lkp_filter_types (id, description, sort_order) VALUES (2, 'Client', 2);
INSERT INTO tbl_lkp_filter_types (id, description, sort_order) VALUES (3, 'Global', 3);

UPDATE tbl_lkp_filter_types_seq SET sequence = 3;
ALTER TABLE tbl_lkp_filter_types_seq AUTO_INCREMENT = 3;

--
-- Table structure for table `tbl_post_decision_makers`
--

alter table tbl_post_decision_makers add column communication_id int(11) default null after discipline_id;
alter table tbl_post_decision_makers add KEY `ix_tbl_post_decision_makers_communication_id` (`communication_id`);
alter table tbl_post_decision_makers add CONSTRAINT `ix_tbl_post_decision_makers_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_post_decision_makers_shadow`
--

alter table tbl_post_decision_makers_shadow add column communication_id int(11) default null after discipline_id;
alter table tbl_post_decision_makers_shadow add KEY `ix_tbl_post_decision_makers_shadow_communication_id` (`communication_id`);

--
-- Table structure for table `tbl_post_agency_users`
--

alter table tbl_post_agency_users add column communication_id int(11) default null after discipline_id;
alter table tbl_post_agency_users add KEY `ix_tbl_post_agency_users_communication_id` (`communication_id`);
alter table tbl_post_agency_users add CONSTRAINT `ix_tbl_post_agency_users_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_post_agency_users_shadow`
--

alter table tbl_post_agency_users_shadow add column communication_id int(11) default null after discipline_id;
alter table tbl_post_agency_users_shadow add KEY `ix_tbl_post_agency_users_shadow_communication_id` (`communication_id`);


--
-- Table structure for table `tbl_post_discipline_review_dates`
--

alter table tbl_post_discipline_review_dates add column communication_id int(11) default null after `year_month`;
alter table tbl_post_discipline_review_dates add KEY `ix_tbl_post_discipline_review_dates_communication_id` (`communication_id`);
alter table tbl_post_discipline_review_dates add CONSTRAINT `ix_tbl_post_discipline_review_dates_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_post_discipline_review_dates_shadow`
--

alter table tbl_post_discipline_review_dates_shadow add column communication_id int(11) default null after `year_month`;
alter table tbl_post_discipline_review_dates_shadow add KEY `ix_tbl_post_discipline_review_dates_shadow_communication_id` (`communication_id`);

--
-- Table structure for table `tbl_post_incumbent_agencies`
--

alter table tbl_post_incumbent_agencies add column communication_id int(11) default null after discipline_id;
alter table tbl_post_incumbent_agencies add KEY `ix_tbl_post_agency_incumbents_communication_id` (`communication_id`);
alter table tbl_post_incumbent_agencies add CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk4` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_post_incumbent_agencies_shadow`
--

alter table tbl_post_incumbent_agencies_shadow add column communication_id int(11) default null after discipline_id;
alter table tbl_post_incumbent_agencies_shadow add KEY `ix_tbl_post_incumbent_agencies_shadow_communication_id` (`communication_id`);


SET FOREIGN_KEY_CHECKS = 1;