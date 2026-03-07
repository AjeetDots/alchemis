SET FOREIGN_KEY_CHECKS = 0;
--
-- Table structure for table `tbl_post_agency_users`
--

alter table tbl_post_agency_users add key `ix_tbl_post_agency_users_last_updated_at` (`last_updated_at`);

--
-- Table structure for table `tbl_post_discipline_review_dates`
--

alter table tbl_post_discipline_review_dates add KEY `ix_tbl_post_discipline_review_dates_last_updated_at` (`last_updated_at`);

--
-- Table structure for table `tbl_post_decision_makers`
--

alter table tbl_post_decision_makers add KEY `ix_tbl_post_decision_makers_last_updated_at` (`last_updated_at`);

--
-- Table structure for table `tbl_post_incumbent_agencies`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents;
DROP TABLE IF EXISTS tbl_post_incumbent_agencies;
CREATE TABLE `tbl_post_incumbent_agencies` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `agency_company_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_tbl_post_agency_incumbents_post_id` (`post_id`),
  KEY `ix_tbl_post_agency_incumbents_agency_company_id` (`agency_company_id`),
  KEY `ix_tbl_post_agency_incumbents_agency_discipline_id` (`discipline_id`),
  KEY `ix_tbl_post_agency_incumbents_last_updated_at` (`last_updated_at`),
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk2` FOREIGN KEY (`agency_company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_agency_incumbents_ibfk3` FOREIGN KEY (`discipline_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_incumbent_agencies_seq`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_seq;
DROP TABLE IF EXISTS tbl_post_incumbent_agencies_seq;
CREATE TABLE `tbl_post_incumbent_agencies_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_incumbent_agencies_shadow`
--

DROP TABLE IF EXISTS tbl_post_agency_incumbents_shadow;
DROP TABLE IF EXISTS tbl_post_incumbent_agencies_shadow;
CREATE TABLE `tbl_post_incumbent_agencies_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `post_id` int(11) NOT NULL default '0',
  `agency_company_id` int(11) NOT NULL default '0',
  `discipline_id` int(11) NOT NULL default '0',
  `last_updated_at` datetime NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_meetings`
--

alter table tbl_meetings add column nbm_predicted_rating int(11) default null;
alter table tbl_meetings add column feedback_rating int(11) default null;
alter table tbl_meetings add column feedback_decision_maker boolean default '0';
alter table tbl_meetings add column feedback_agency_user boolean default '0';
alter table tbl_meetings add column feedback_budget_available boolean default '0';
alter table tbl_meetings add column feedback_receptive boolean default '0';
alter table tbl_meetings add column feedback_targeting boolean default '0';
alter table tbl_meetings add column feedback_meeting_length int(11) default '0';
alter table tbl_meetings add column feedback_comments text default NULL;
alter table tbl_meetings add column feedback_next_steps text default NULL;

--
-- Table structure for table `tbl_filters`
--
alter table tbl_filters drop column `post_communication_count`;
alter table tbl_filters drop column `post_effective_count`;
alter table tbl_filters drop column `client_initiative_communication_count`;
alter table tbl_filters drop column `client_initiative_effective_count`;
alter table tbl_filters drop column `company_effective_count`;
alter table tbl_filters drop column `company_communication_count`;
alter table tbl_filters drop column `company_client_initiative_communication_count`;
alter table tbl_filters drop column `company_client_initiative_effective_count`;

--
-- Table structure for table `tbl_filters_shadow`
--
alter table tbl_filters_shadow drop column `post_communication_count`;
alter table tbl_filters_shadow drop column `post_effective_count`;
alter table tbl_filters_shadow drop column `client_initiative_communication_count`;
alter table tbl_filters_shadow drop column `client_initiative_effective_count`;
alter table tbl_filters_shadow drop column `company_effective_count`;
alter table tbl_filters_shadow drop column `company_communication_count`;
alter table tbl_filters_shadow drop column `company_client_initiative_communication_count`;
alter table tbl_filters_shadow drop column `company_client_initiative_effective_count`;

create temporary table t_campaign_disciplines select * from tbl_campaign_disciplines;

--
-- Table structure for table `tbl_campaign_disciplines`
--
create temporary table t_campaign_disciplines select * from tbl_campaign_disciplines;
DROP TABLE tbl_campaign_disciplines;
CREATE TABLE `tbl_campaign_disciplines` (
  `id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_campaign_disciplines_campaign_id` (`campaign_id`),
  KEY `ix_tbl_tbl_campaign_disciplines_tiered_characteristic_id` (`tiered_characteristic_id`),
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_campaign_disciplines_ibfk2` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

insert into tbl_campaign_disciplines (id, campaign_id, tiered_characteristic_id) select id, campaign_id,tiered_characteristic_id from t_campaign_disciplines;


SET FOREIGN_KEY_CHECKS = 1;


