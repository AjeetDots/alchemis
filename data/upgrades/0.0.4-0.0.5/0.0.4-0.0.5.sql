--
-- Table structure for table `tbl_lkp_regions`
--

CREATE TABLE `tbl_lkp_regions` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL default '',
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
-- Table structure for table `tbl_lkp_region_postcodes`
--

CREATE TABLE `tbl_lkp_region_postcodes` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `postcode` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_region_postcodes_region_id` (`region_id`),
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk1` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_region_postcodes_seq`
--

CREATE TABLE `tbl_lkp_region_postcodes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `tbl_meetings`
--
ALTER TABLE tbl_meetings ADD COLUMN `is_current` tinyint(1) NOT NULL default '0';
ALTER TABLE tbl_meetings ADD KEY `ix_tbl_meetings_is_current` (`is_current`);


update tbl_meetings set status_id = 0 where status_id = 3;
update tbl_meetings set status_id = 10 where status_id = 0;
update tbl_meetings set status_id = 13 where status_id = 1;
update tbl_meetings set status_id = 14 where status_id = 2;


ALTER TABLE tbl_meetings DROP FOREIGN KEY `ix_tbl_meetings_ibfk2`;
ALTER TABLE tbl_meetings ADD CONSTRAINT `ix_tbl_meetings_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_communication_status` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Table structure for table `tbl_meetings_shadow`
--
ALTER TABLE tbl_meetings_shadow ADD COLUMN `is_current` tinyint(1) NOT NULL default '0';

--
-- Table structure for table `tbl_lkp_communication_status`
--
ALTER TABLE tbl_lkp_communication_status ADD COLUMN `is_auto_calculate` tinyint(1) NOT NULL default '0';
ALTER TABLE tbl_lkp_communication_status ADD COLUMN `show_auto_calculate_options` tinyint(1) NOT NULL default '0';


--
-- Table structure for table `tbl_rbac_users`
--
ALTER TABLE tbl_rbac_users ADD COLUMN `name` varchar(255) NOT NULL default '';

--
-- Table structure for table `tbl_communications`
--
ALTER TABLE tbl_communications DROP FOREIGN KEY `ix_tbl_communications_ibfk3`;
ALTER TABLE tbl_communications ADD CONSTRAINT `ix_tbl_communications_ibfk3` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_user_client_access`
--
ALTER TABLE tbl_user_client_access DROP FOREIGN KEY `ix_tbl_user_client_access_ibfk2`;
ALTER TABLE tbl_user_client_access ADD CONSTRAINT `ix_tbl_user_client_access_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE;

--
-- Table structure for table `tbl_user_client_aliases`
--
ALTER TABLE tbl_user_client_aliases DROP FOREIGN KEY `ix_tbl_user_client_aliases_ibfk2`;
ALTER TABLE tbl_user_client_aliases ADD CONSTRAINT `ix_tbl_user_client_aliases_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE;


--
-- Table structure for table `tbl_post_initiative_notes`
--

CREATE TABLE `tbl_post_initiative_notes` (
  `id` int(11) NOT NULL auto_increment,
  `post_initiative_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` text NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_post_initiative_notes_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_post_initiative_notes_created_at` (`created_at`),
  KEY `ix_tbl_post_initiative_notes_created_by` (`created_by`),
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_initiative_notes_seq`
--

CREATE TABLE `tbl_post_initiative_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `note` text NOT NULL default '',
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `tbl_post_initiatives`
--
alter table tbl_post_initiatives drop column propensity;
alter table tbl_post_initiatives add column last_mailer_communication_id int(11) default NULL;
alter table tbl_post_initiatives add index ix_tbl_post_initiatives_last_mailer_communication_id (last_mailer_communication_id);
alter table tbl_post_initiatives add CONSTRAINT `ix_tbl_post_initiatives_ibfk5` FOREIGN KEY (`last_mailer_communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE ON DELETE CASCADE

--
-- Table structure for table `tbl_characteristics`
--
update tbl_characteristics set attributes = multiple_elements;
ALTER TABLE `tbl_characteristics` DROP COLUMN `multiple_elements`;
ALTER TABLE `tbl_characteristics` ADD COLUMN `attributes` tinyint(1) NOT NULL default '0';
ALTER TABLE `tbl_characteristics` ADD COLUMN `options` tinyint(1) NOT NULL default '0';

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
-- Table structure for table `tbl_mailer_item_responses`
--

CREATE TABLE `tbl_mailer_item_responses` (
  `id` int(11) NOT NULL auto_increment,
  `mailer_item_id` int(11) NOT NULL,
  `mailer_response_id` int(11) NOT NULL,
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_mailer_item_reponses_mailer_item_id` (`mailer_item_id`),
  KEY `ix_tbl_mailer_item_reponses_mailer_response_id` (`mailer_response_id`),
  CONSTRAINT `tbl_mailer_item_reponses_ibfk1` FOREIGN KEY (`mailer_item_id`) REFERENCES `tbl_mailer_items` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `tbl_mailer_item_reponses_ibfk2` FOREIGN KEY (`mailer_response_id`) REFERENCES `tbl_lkp_mailer_responses` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
  `despatched_date` datetime,
  `response_date` datetime,
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_mailer_items_id` (`mailer_id`),
  KEY `ix_tbl_mailer_items_post_initiative_id` (`post_initiative_id`),
  KEY `ix_tbl_mailer_items_despatched_date` (`despatched_date`),
  CONSTRAINT `tbl_mailer_items_ibfk1` FOREIGN KEY (`mailer_id`) REFERENCES `tbl_mailers` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `tbl_mailer_items_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
  `despatched_date` datetime,
  `response_date` datetime,
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
  CONSTRAINT `tbl_mailers_ibfk1` FOREIGN KEY (`client_initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk2` FOREIGN KEY (`response_group_id`) REFERENCES `tbl_lkp_mailer_response_groups` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `tbl_mailers_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_mailer_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
-- Table structure for table `tbl_lkp_reports`
--

CREATE TABLE `tbl_lkp_reports` (
  `id` int(1) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `design_file` varchar(255) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE tbl_object_characteristic_elements_text MODIFY COLUMN `value` varchar(255);