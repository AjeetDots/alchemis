SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_campaigns`
--

CREATE TABLE `tbl_campaigns` (
  id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL DEFAULT 0,
  revision varchar(255) NOT NULL DEFAULT '',
  created timestamp NOT NULL DEFAULT NOW(),
  PRIMARY KEY  (id),
  KEY ix_tbl_campaign_client_id (client_id),
  CONSTRAINT `ix_tbl_campaigs_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_campaigns_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_campaigns_seq (sequence) values (0);

CREATE TABLE `tbl_campaigns_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  client_id int(11) NOT NULL DEFAULT 0,
  revision varchar(255) NOT NULL DEFAULT '',
  created timestamp NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_campaign_details`
--

CREATE TABLE `tbl_campaign_details` (
  id int(11) NOT NULL auto_increment,
  campaign_id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),  
  KEY ix_tbl_campaign_details_campaign_id (campaign_id),
  CONSTRAINT `ix_tbl_campaign_details_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_campaign_details_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_campaign_details_seq (sequence) values (0);

CREATE TABLE `tbl_campaign_details_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  campaign_id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_campaign_nbms`
--

CREATE TABLE `tbl_campaign_nbms` (
  id int(11) NOT NULL auto_increment,
  campaign_id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (id),  
  KEY ix_tbl_campaign_nbms_campaign_id (campaign_id),
  CONSTRAINT `ix_tbl_campaign_nbms_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_campaign_nbms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_campaign_nbms_seq (sequence) values (0);

CREATE TABLE `tbl_campaign_nbms_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  campaign_id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (uid)
) TYPE=InnoDB;


--INITIATIVES

--
-- Table structure for table `tbl_initiatives`
--

CREATE TABLE `tbl_initiatives` (
  id int(11) NOT NULL,
  campaign_id int(11) NOT NULL default 0,
  name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),  
  KEY ix_tbl_initiatives_campaign_id (campaign_id),
  CONSTRAINT `ix_tbl_initiatives_ibfk1` FOREIGN KEY (`campaign_id`) REFERENCES `tbl_campaigns` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_initiatives_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_initiatives_seq (sequence) values (0);

CREATE TABLE `tbl_initiatives_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  campaign_id int(11) NOT NULL DEFAULT 0,
  name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;



--
-- Table structure for table `tbl_user_client_aliases`
--

CREATE TABLE `tbl_user_client_aliases` (
  id int(11) NOT NULL,
  client_id int(11) NOT NULL,
  alias varchar(255) NOT NULL,
  user_id int(11) not null,
  active tinyint(1) not null default 0,
  PRIMARY KEY  (id),  
  KEY ix_tbl_user_client_aliases_client_id (client_id),
  KEY ix_tbl_user_client_aliases_user_id (user_id),
  KEY ix_tbl_user_client_aliases_active (active),
  CONSTRAINT `ix_tbl_user_client_aliases_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_user_client_aliases_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_user_client_aliases_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_user_client_aliases_seq (sequence) values (0);

CREATE TABLE `tbl_user_client_aliases_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  client_id int(11) NOT NULL,
  alias varchar(255) NOT NULL,
  user_id int(11) not null,
  active tinyint(1) not null default 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_user_client_access`
--

CREATE TABLE `tbl_user_client_access` (
  id int(11) NOT NULL,
  client_id int(11) NOT NULL,
  user_id int(11) not null,
  PRIMARY KEY  (id),  
  KEY ix_tbl_user_client_access_client_id (client_id),
  KEY ix_tbl_user_client_access_user_id (user_id),
  CONSTRAINT `ix_tbl_user_client_access_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_user_client_access_ibfk2` FOREIGN KEY (`user_id`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_user_client_access_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_user_client_access_seq (sequence) values (0);

CREATE TABLE `tbl_user_client_access_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  client_id int(11) NOT NULL,
  user_id int(11) not null,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_post_initiatives`
--

CREATE TABLE `tbl_post_initiatives` (
  id int(11) NOT NULL,
  post_id int(11) NOT NULL,
  initiative_id int(11) NOT NULL,
  propensity int(11) NOT NULL default 0,
  status_id int(11) NOT NULL DEFAULT '0',
  last_effective_communication_id int(11) default NULL,
  last_communication_id int(11) default NULL,
  next_communication_date datetime default NULL,
  PRIMARY KEY  (id),  
  KEY ix_tbl_post_initiatives_initiative_id (initiative_id),
  KEY ix_tbl_post_initiatives_post_id (post_id),
  KEY ix_tbl_post_initiatives_last_effective_communication_id (last_effective_communication_id),
  KEY ix_tbl_post_initiatives_last_communication_id (last_communication_id),
  KEY ix_tbl_post_initiatives_next_communication_id (next_communication_date),
  KEY ix_tbl_post_initiatives_status (status),
  CONSTRAINT `ix_tbl_post_initiatives_ibfk1` FOREIGN KEY (`initiative_id`) REFERENCES `tbl_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiatives_ibfk2` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_post_initiatives_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_post_initiatives_seq (sequence) values (0);

CREATE TABLE `tbl_post_initiatives_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  post_id int(11) NOT NULL,
  initiative_id int(11) NOT NULL,
  propensity int(11) NOT NULL default 0,
  status_id int(11) NOT NULL DEFAULT '0',
  last_effective_communication_id int(11) default NULL,
  last_communication_id int(11) default NULL,
  next_communication_date datetime default NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;








--
-- Table structure for table `tbl_communications`
--

CREATE TABLE `tbl_communications` (
  id int(11) NOT NULL auto_increment,
  type_id int(11) NOT NULL default '1', 
  post_initiative_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  old_status varchar(50) NULL,
  communication_date datetime NOT NULL,
  direction enum('out','in') NOT NULL default 'out',
  effective enum('effective','non-effective') NOT NULL default 'non-effective',
  ote tinyint(1) NOT NULL default '0',
  targeting_id int(11) NOT NULL default 0,
  receptiveness_id int(11) NOT NULL default 0,
  decision_maker_type_id int(11) NULL,
  next_communication_date datetime default NULL,
  next_communication_date_reason_id int(11) default NULL,
  comments text NULL,
  notes text NULL,
  PRIMARY KEY  (id),  
  KEY ix_tbl_communications_user_id (user_id),
  KEY ix_tbl_communications_type_id (type_id),
  KEY ix_tbl_communications_initiative_id (post_initiative_id),
  KEY ix_tbl_communications_communication_date (communication_date),
  KEY ix_tbl_communications_effective (effective),
  KEY ix_tbl_communications_next_communication_date (next_communication_date),
  KEY ix_tbl_communications_status (status),
  CONSTRAINT `ix_tbl_communications_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk2` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_communication_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communications_ibfk3` FOREIGN KEY (`user_id`) REFERENCES `tbl_lkp_communications` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_communications_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_communications_seq (sequence) values (0);

CREATE TABLE `tbl_communications_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  type_id int(11) NOT NULL default '1', 
  post_initiative_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  old_status varchar(50) NULL,
  communication_date datetime NOT NULL,
  direction enum('out','in') NOT NULL default 'out',
  effective enum('effective','non-effective') NOT NULL default 'non-effective',
  ote tinyint(1) NOT NULL default '0',
  targeting_id int(11) NOT NULL default 0,
  receptiveness_id int(11) NOT NULL default 0,
  decision_maker_type_id int(11) NULL,
  next_communication_date datetime default NULL,
  next_communication_date_reason_id int(11) default NULL,
  comments text NULL,
  notes text NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;




--
-- Table structure for table `tbl_tag_categories`
--

CREATE TABLE tbl_tag_categories (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) TYPE=InnoDB;

CREATE TABLE `tbl_tag_categories_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_tag_categories_seq (sequence) values (0);

CREATE TABLE tbl_tag_categories_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  name varchar(50) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_tags`
--

CREATE TABLE tbl_tags (
  id int(11) NOT NULL auto_increment,
  value varchar(50) NOT NULL,
  category_id int(11) NOT NULL,
  PRIMARY KEY  (id),
  KEY ix_tbl_tags_value (value),
  KEY ix_tbl_tags_category_id (category_id)
) TYPE=InnoDB;

CREATE TABLE `tbl_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_tags_seq (sequence) values (0);

CREATE TABLE tbl_tags_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  value varchar(50) NOT NULL,
  category_id int(11) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_company_tags`
--

CREATE TABLE tbl_company_tags (
  id int(11) NOT NULL auto_increment,
  company_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY tag (company_id, tag_id),
  KEY ix_tbl_company_tags_company_id (company_id),
  KEY ix_tbl_company_tags_tag_id (tag_id),
  CONSTRAINT `ix_tbl_company_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_tags_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_company_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_company_tags_seq (sequence) values (0);

CREATE TABLE tbl_company_tags_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  company_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_post_tags`
--

CREATE TABLE tbl_post_tags (
  id int(11) NOT NULL auto_increment,
  post_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY tag (post_id, tag_id),
  KEY ix_tbl_company_tags_post_id (post_id),
  KEY ix_tbl_company_tags_tag_id (tag_id),
  CONSTRAINT `ix_tbl_post_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_tags_ibfk2` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_post_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_post_tags_seq (sequence) values (0);

CREATE TABLE tbl_post_tags_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  post_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_post_initiative_tags`
--

CREATE TABLE tbl_post_initiative_tags (
  id int(11) NOT NULL auto_increment,
  post_initiative_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY tag (post_initiative_id, tag_id),
  KEY ix_tbl_company_tags_post_initiative_id (post_initiative_id),
  KEY ix_tbl_company_tags_tag_id (tag_id),
  CONSTRAINT `ix_tbl_post_initiative_tags_ibfk1` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_post_initiative_tags_ibfk2` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_post_initiative_tags_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_post_initiative_tags_seq (sequence) values (0);

CREATE TABLE tbl_post_initiative_tags_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  post_initiative_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;



--
-- Table structure for table `tbl_tiered_characteristic_categories`
--

CREATE TABLE tbl_tiered_characteristic_categories (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) TYPE=InnoDB;

CREATE TABLE `tbl_tiered_characteristic_categories_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_tiered_characteristic_categories_seq (sequence) values (0);

CREATE TABLE tbl_tiered_characteristic_categories_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  name varchar(50) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_tiered_characteristics`
--

CREATE TABLE tbl_tiered_characteristics (
  id int(11) NOT NULL auto_increment,
  category_id int(11) NOT NULL,
  value varchar(100) NOT NULL,
  parent_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY ix_tbl_tiered_characteristics_value (value),
  KEY ix_tbl_tiered_characteristics_category_id (category_id),
  KEY ix_tbl_tiered_characteristics_parent_id (parent_id),
  CONSTRAINT `ix_tbl_tiered_characteristics_ibfk1` FOREIGN KEY (`category_id`) REFERENCES `tbl_tiered_characteristic_categories` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_tiered_characteristics_seq (sequence) values (0);

CREATE TABLE tbl_tiered_characteristics_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  category_id int(11) NOT NULL,
  value varchar(100) NOT NULL,
  parent_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_company_tiered_characteristics`
--

CREATE TABLE tbl_company_tiered_characteristics (
  id int(11) NOT NULL auto_increment,
  company_id int(11) NOT NULL,
  tiered_characteristic_id int(11) NOT NULL,
  tier int(11) NOT NULL, 
  PRIMARY KEY  (id),
  UNIQUE KEY tag (company_id, tiered_characteristic_id),
  KEY ix_tbl_company_tiered_characteristics_company_id (company_id),
  KEY ix_tbl_company_tiered_characteristics_tiered_characteristic_id (tiered_characteristic_id),
  KEY ix_tbl_company_tiered_characteristics_tier (tier),
  CONSTRAINT `ix_tbl_company_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_company_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_company_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_company_tiered_characteristics_seq (sequence) values (0);

CREATE TABLE tbl_company_tiered_characteristics_shadow (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL default 0,
  company_id int(11) NOT NULL,
  tiered_characteristic_id int(11) NOT NULL,
  tier int(11) NOT NULL, 
  PRIMARY KEY  (uid)
) TYPE=InnoDB;



--
-- Table structure for table `tbl_meetings`
--

CREATE TABLE `tbl_meetings` (
  id int(11) NOT NULL auto_increment,
  post_initiative_id int(11) NOT NULL,
  communication_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  type_id int(11) NOT NULL,
  date datetime NOT NULL,
  reminder_date datetime NULL,
  notes varchar(255) NULL DEFAULT '',
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL,
  PRIMARY KEY  (id),
  KEY ix_tbl_meetings_post_initiative_id (post_initiative_id),
  KEY ix_tbl_meetings_communication_id (communication_id),
  KEY ix_tbl_meetings_reminder_type_id (type_id),
  KEY ix_tbl_meetings_date (date),
  KEY ix_tbl_meetings_reminder_date (reminder_date),
  KEY ix_tbl_meetings_created_by (created_by),
  CONSTRAINT `ix_tbl_meetings_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_meetings_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_meeting_status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_meetings_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_meeting_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_meetings_ibfk4` FOREIGN KEY (`created_by`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_meetings_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_meetings_seq (sequence) values (0);

CREATE TABLE `tbl_meetings_shadow` (
  shadow_id int(11) NOT NULL auto_increment,
  shadow_timestamp timestamp NOT NULL,
  shadow_updated_by int(11) NOT NULL,
  shadow_type char(1) default NULL,
  id int(11) NOT NULL default '0',
  post_initiative_id int(11) NOT NULL,
  communication_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  type_id int(11) NOT NULL,
  date datetime NOT NULL,
  reminder_date datetime default NULL,
  notes varchar(255) default '',
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`),
  KEY ix_tbl_meetings_shadow_shadow_timestamp (shadow_timestamp),
  KEY ix_tbl_meetings_shadow_shadow_updated_by (shadow_updated_by),
  KEY ix_tbl_meetings_shadow_shadow_type (shadow_type)
) TYPE=InnoDB;




--
-- Table structure for table `tbl_information_requests`
--

CREATE TABLE `tbl_information_requests` (
  id int(11) NOT NULL auto_increment,
  post_initiative_id int(11) NOT NULL,
  communication_id int(11) NOT NULL,
  type_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  comm_type_id int(11) NOT NULL,
  date datetime NOT NULL,
  reminder_date datetime NULL,
  notes varchar(255) NULL DEFAULT '',
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL,
  PRIMARY KEY  (id),
  KEY ix_tbl_information_requests_post_initiative_id (post_initiative_id),
  KEY ix_tbl_meetings_communication_id (communication_id),
  KEY ix_tbl_information_requests_date (date),
  KEY ix_tbl_information_requests_reminder_date (reminder_date),
  KEY ix_tbl_information_requests_type_id (type_id),
  KEY ix_tbl_information_requests_comm_type_id (comm_type_id),
  KEY ix_tbl_information_requests_created_by (created_by),
  CONSTRAINT `ix_tbl_information_requests_ibfk1` FOREIGN KEY (`post_initiative_id`) REFERENCES `tbl_post_initiatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk2` FOREIGN KEY (`status_id`) REFERENCES `tbl_lkp_information_request_status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk3` FOREIGN KEY (`type_id`) REFERENCES `tbl_lkp_information_request_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk4` FOREIGN KEY (`comm_types_id`) REFERENCES `tbl_lkp_information_request_comm_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_information_requests_ibfk5` FOREIGN KEY (`created_by`) REFERENCES `tbl_lkp_users` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_information_requests_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_information_requests_seq (sequence) values (0);

CREATE TABLE `tbl_information_requests_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  post_initiative_id int(11) NOT NULL,
  communication_id int(11) NOT NULL,
  type_id int(11) NOT NULL,
  status_id int(11) NOT NULL,
  comm_type_id int(11) NOT NULL,
  date datetime NOT NULL,
  reminder_date datetime NULL,
  notes varchar(255) NULL DEFAULT '',
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;












--
-- Table structure for table `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  id int(11) NOT NULL auto_increment,
  name varchar(255) UNIQUE NOT NULL DEFAULT '',
--  address varchar(20) NOT NULL DEFAULT '',
--  telephone varchar(20) NOT NULL DEFAULT '',
--  fax varchar(20) NOT NULL DEFAULT '',
--  website varchar(255) NOT NULL DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_clients_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_clients_seq (sequence) values (0);

CREATE TABLE `tbl_clients_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(255) UNIQUE NOT NULL DEFAULT '',
--  address varchar(20) NOT NULL DEFAULT '',
--  telephone varchar(20) NOT NULL DEFAULT '',
--  fax varchar(20) NOT NULL DEFAULT '',
--  website varchar(255) NOT NULL DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid),
  KEY ix_tbl_clients_deleted (deleted)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_client_contacts`
--

CREATE TABLE `tbl_client_contacts` (
  id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL DEFAULT 0,
  name text NOT NULL,
  job_title varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  telephone varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  KEY ix_tbl_client_contacts_client_id (client_id),
  CONSTRAINT `ix_tbl_client_contacts_ibfk1` FOREIGN KEY (`client_id`) REFERENCES `tbl_clients` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_client_contacts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_client_contacts_seq (sequence) values (0);

CREATE TABLE `tbl_client_contacts_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  client_id int(11) NOT NULL DEFAULT 0,
  name text NOT NULL,
  job_title varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  telephone varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_companies`
--

CREATE TABLE `tbl_companies` (
  id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  website varchar(255) DEFAULT '',
  telephone varchar(50) DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY ix_tbl_companies_name(`name`),
  KEY ix_tbl_companies_deleted (`deleted`),
  KEY ix_tbl_companies_id_and_deleted (`id`, `deleted`),
  KEY ix_tbl_companies_telephone (`telephone`),
  KEY ix_tbl_companies_website (`website`)
) TYPE=InnoDB;

CREATE TABLE `tbl_companies_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_companies_seq (sequence) values (0);

CREATE TABLE `tbl_companies_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(255) NOT NULL DEFAULT '',
  website varchar(255) DEFAULT '',
  telephone varchar(50) DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_sites`
--

CREATE TABLE `tbl_sites` (
  id int(11) NOT NULL DEFAULT 0,
  company_id int(11) NOT NULL DEFAULT 0,
  name varchar(255) DEFAULT NULL,
  address_1 varchar(255) DEFAULT '',
  address_2 varchar(255) DEFAULT '',
  town varchar(50) DEFAULT '',
  city varchar(50) DEFAULT '',
  postcode varchar(25) DEFAULT '',
  telephone varchar(50) DEFAULT '',
  county_id int(11) DEFAULT '',
  country_id int(11) DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY ix_tbl_sites_company_id (company_id),
  KEY ix_tbl_sites_deleted (deleted),
  KEY ix_tbl_sites_county_id (county_id),
  KEY ix_tbl_sites_country_id (country_id),
  KEY ix_tbl_sites_postcode (postcode),
  CONSTRAINT `tbl_sites_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk2` FOREIGN KEY (`county_id`) REFERENCES `tbl_lkp_counties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tbl_sites_ibfk3` FOREIGN KEY (`country_id`) REFERENCES `tbl_lkp_countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_sites_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_sites_seq (sequence) values (0);

CREATE TABLE `tbl_sites_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  company_id int(11) NOT NULL DEFAULT 0,
  name varchar(255) DEFAULT '',
  address_1 varchar(255) DEFAULT '',
  address_2 varchar(255) DEFAULT '',
  town varchar(50) DEFAULT ''',
  city varchar(50) DEFAULT '',
  postcode varchar(25) DEFAULT '',
  telephone varchar(50) DEFAULT '',
  county_id int(11) DEFAULT NULL,
  deleted tinyint(1) NOT NULL DEFAULT 0,
  country_id int(11) DEFAULT NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  id int(11) NOT NULL DEFAULT 0,
  company_id int(11) NOT NULL DEFAULT 0,
  job_title varchar(255) NOT NULL DEFAULT '',
  propensity int(11) NOT NULL default 0,
  telephone_1 varchar(50) DEFAULT '',
  telephone_2 varchar(50) DEFAULT '',
  telephone_switchboard varchar(50) DEFAULT '',
  telephone_fax varchar(50) DEFAULT '',
  notes text DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY ix_tbl_posts_company_id (company_id),
  KEY ix_tbl_posts_job_title (job_title),
  KEY ix_tbl_posts_deleted (deleted),
  KEY ix_tbl_posts_id_and_deleted (id, deleted),
  KEY ix_tbl_posts_propensity (propensity),
  CONSTRAINT `tbl_companies_ibfk1` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_posts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_posts_seq (sequence) values (0);

CREATE TABLE `tbl_posts_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  company_id int(11) NOT NULL DEFAULT 0,
  job_title varchar(255) NOT NULL DEFAULT '',
  propensity int(11) NOT NULL default 0,
  telephone_1 varchar(50) DEFAULT '',
  telephone_2 varchar(50) DEFAULT '',
  telephone_switchboard varchar(50) DEFAULT '',
  telephone_fax varchar(50) DEFAULT '',
  notes text DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_contacts`
--

CREATE TABLE `tbl_contacts` (
  id int(11) NOT NULL DEFAULT 0,
  post_id int(11) NOT NULL DEFAULT 0,
  title varchar(25) DEFAULT '',
  first_name varchar(50) DEFAULT '',
  surname varchar(50) DEFAULT '',
  full_name varchar(100) DEFAULT null,
  telephone_mobile varchar(50) DEFAULT '',
  email varchar(100) DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY ix_tbl_contacts_post_id (post_id),
  KEY ix_tbl_contacts_deleted (deleted),
  KEY ix_tbl_contacts_first_name (first_name),
  KEY ix_tbl_contacts_surname (surname),
  KEY ix_tbl_contacts_full_name (full_name),
  CONSTRAINT `tbl_posts_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_contacts_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_contacts_seq (sequence) values (0);

CREATE TABLE `tbl_contacts_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  post_id int(11) NOT NULL DEFAULT 0,
  title varchar(25) DEFAULT '',
  first_name varchar(50) DEFAULT '',
  surname varchar(50) DEFAULT '',
  full_name varchar(100) DEFAULT null,
  telephone_mobile varchar(50) DEFAULT '',
  email varchar(100) DEFAULT '',
  deleted tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_post_site`
--

CREATE TABLE `tbl_post_site` (
  id int(11) NOT NULL DEFAULT 0,
  post_id int(11) NOT NULL DEFAULT 0,
  site_id int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  UNIQUE KEY ix_tbl_post_unique1 (post_id,site_id),
  KEY ix_tbl_post_site_post_id (post_id),
  KEY ix_tbl_post_site_site_id (site_id),
  CONSTRAINT `tbl_post_site_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_post_site_ibfk2` FOREIGN KEY (`site_id`) REFERENCES `tbl_sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE `tbl_post_site_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_post_site_seq (sequence) values (0);

CREATE TABLE `tbl_post_site_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  post_id int(11) NOT NULL DEFAULT 0,
  site_id int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_filters`
--

CREATE TABLE `tbl_filters` (
  id int(11) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  description text NULL,
  type_id int(11) NOT NULL default 1,
  results_format varchar(50) NOT NULL default 'company',
  client_initiative_id int(11) default NULL,
  company_count int(11) default 0,
  post_count int(11) default 0,
  communication_count int(11) default 0,
  effective_count int(11) default 0,
  client_initiative_communication_count int(11) default 0,
  client_initiative_effective_count int(11) default 0,
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL, 
  created_at datetime NULL,
  PRIMARY KEY  (id),
  KEY ix_tbl_filters_name (name),
  KEY ix_tbl_filters_type_id (type_id)
) TYPE=InnoDB;

CREATE TABLE `tbl_filters_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_filters_seq (sequence) values (0);

drop table if exists tbl_filters_shadow;
CREATE TABLE `tbl_filters_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(100) NOT NULL default '',
  description text NULL,
  type_id int(11) NOT NULL default 1,
  results_format varchar(50) NOT NULL default 'company',
  client_initiative_id int(11) default NULL,
  company_count int(11) default 0,
  post_count int(11) default 0,
  communication_count int(11) default 0,
  effective_count int(11) default 0,
  client_initiative_communication_count int(11) default 0,
  client_initiative_effective_count int(11) default 0,
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL, 
  created_at datetime NULL,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_filter_lines'
--
CREATE TABLE `tbl_filter_lines` (
  id int(11) NOT NULL auto_increment,
  filter_id int(11) NOT NULL,
  table_name varchar(50) NOT NULL default '',
  field_name varchar(50) NOT NULL default '',
  params text NOT NULL,
  params_display text NOT NULL,
  operator varchar(50) NULL,
  concatenator varchar(10) NULL,
  bracket_open varchar(25) NULL,
  bracket_close varchar(25) NULL,
  direction varchar(25) NULL,
  PRIMARY KEY (id),
  KEY ix_tbl_filter_lines_filter_id (filter_id),
  KEY ix_tbl_filter_lines_direction (direction)
) TYPE=InnoDB;

CREATE TABLE `tbl_filter_lines_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_filter_lines_seq (sequence) values (0);

CREATE TABLE `tbl_filter_lines_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
 filter_id int(11) NOT NULL,
  table_name varchar(50) NOT NULL default '',
  field_name varchar(50) NOT NULL default '',
  params text NOT NULL,
  params_display text NOT NULL,
  operator varchar(50) NULL,
  concatenator varchar(10) NULL,
  bracket_open varchar(25) NULL,
  bracket_close varchar(25) NULL,
  direction varchar(25) NULL,
  PRIMARY KEY (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_filter_results`
--

drop table if exists tbl_filter_results;
CREATE TABLE `tbl_filter_results` (
  id int(11) NOT NULL auto_increment,
  filter_id int(11) NOT NULL,
  company_id int(11) default NULL,
  post_id int(11) default NULL,
  post_initiative_id int(11) default NULL,
  propensity_max int(11),
  propensity_avg int(11),
  propensity_min int(11),
  propensity_sum int(11),
  post_count int(11) default 0,
  post_communication_count int(11) default 0,
  post_effective_count int(11) default 0,
  client_initiative_communication_count int(11) default 0,
  client_initiative_effective_count int(11) default 0,
  company_communication_count int(11) default 0,
  company_effective_count int(11) default 0,
  company_client_initiative_communication_count int(11) default 0,
  company_client_initiative_effective_count int(11) default 0,
  PRIMARY KEY  (id),
  KEY ix_tbl_filters_results_company_id (company_id),
  KEY ix_tbl_filters_results_post_id (post_id),
  KEY ix_tbl_filters_results_post_initiative_id (post_initiative_id)
) TYPE=InnoDB;

drop table if exists tbl_filter_results_seq;
CREATE TABLE `tbl_filter_results_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_filter_results_seq (sequence) values (0);

drop table if exists tbl_filter_results_shadow;
CREATE TABLE `tbl_filter_results_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  filter_id int(11) NOT NULL,
  company_id int(11) default NULL,
  post_id int(11) default NULL,
  post_initiative_id int(11) default NULL,
  propensity_max int(11),
  propensity_avg int(11),
  propensity_min int(11),
  propensity_sum int(11),
  post_count int(11),
  post_communication_count int(11),
  post_effective_count int(11),
  client_initiative_communication_count int(11) default 0,
  client_initiative_effective_count int(11) default 0,
  company_communication_count int(11) default 0,
  company_effective_count int(11) default 0,
  company_client_initiative_communication_count int(11) default 0,
  company_client_initiative_effective_count int(11) default 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_communication_status`
--
drop table if exists tbl_lkp_communication_status;
CREATE TABLE `tbl_lkp_communication_status` (
  id int(11) NOT NULL DEFAULT 0,
  lower_value int(11) NOT NULL DEFAULT 0,
  upper_value int(11) NOT NULL DEFAULT 0,
  description varchar(50) NOT NULL default '',
  is_auto_calculate boolean NOT NULL default '0',
  show_auto_calculate_options boolean NOT NULL default '0',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  UNIQUE KEY name (description),
  KEY ix_tbl_lkp_communication_status_lower_value (lower_value),
  KEY ix_tbl_lkp_communication_status_upper_value (upper_value)
) TYPE=InnoDB;

drop table if exists tbl_lkp_communication_status_seq;
CREATE TABLE `tbl_lkp_communication_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_communication_status_seq (sequence) values (0);

drop table if exists tbl_lkp_communication_status_shadow;
CREATE TABLE `tbl_lkp_communication_status_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  lower_value int(11) NOT NULL DEFAULT 0,
  upper_value int(11) NOT NULL DEFAULT 0,
  is_auto_calculate boolean NOT NULL default '0',
  show_auto_calculate_options boolean NOT NULL default '0',
  description varchar(50) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_lkp_communication_status_rules`
--
DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules`;
CREATE TABLE `tbl_lkp_communication_status_rules` (
  id int(11) NOT NULL DEFAULT 0,
  status_id int(11) NOT NULL DEFAULT 0,
  child_status_id int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY ix_tbl_lkp_communication_status_rules_status_id (status_id),
  KEY ix_tbl_lkp_communication_status_rules_child_status_id (child_status_id),
  KEY ix_tbl_lkp_communication_status_rules_status_id_child_status_id (status_id, child_status_id)
) TYPE=InnoDB;

DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules_seq`;
CREATE TABLE `tbl_lkp_communication_status_rules_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_communication_status_rules_seq (sequence) values (0);

DROP TABLE IF EXISTS `tbl_lkp_communication_status_rules_shadow`;
CREATE TABLE `tbl_lkp_communication_status_rules_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  status_id int(11) NOT NULL DEFAULT 0,
  child_status_id int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;


--
-- Table structure for table `tbl_lkp_counties`
--

CREATE TABLE `tbl_lkp_counties` (
  id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_counties_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_counties_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_counties_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_countries`
--

CREATE TABLE `tbl_lkp_countries` (
  id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_countries_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_countries_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_countries_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(50) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_users`
--

CREATE TABLE `tbl_lkp_users` (
  id int(11) NOT NULL DEFAULT 0,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_users_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_users_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_users_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_user_call_names`
--

CREATE TABLE `tbl_user_call_names` (
  id int(11) NOT NULL DEFAULT 0,
  user_id int(11) NOT NULL DEFAULT 0,
  client_id int(11) NOT NULL DEFAULT 0,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_users_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_users_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_users_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_decision_maker_types`
--

CREATE TABLE `tbl_lkp_decision_maker_types` (
  id int(11) NOT NULL DEFAULT 0,
  description varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_decision_maker_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_decision_maker_types_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_decision_maker_types_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(50) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_communication_targeting`
--

CREATE TABLE `tbl_lkp_communication_targeting` (
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_communication_targeting_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_communication_targeting_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_communication_targeting_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_communication_receptiveness`
--

CREATE TABLE `tbl_lkp_communication_receptiveness` (
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_communication_receptiveness_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_communication_receptiveness_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_communication_receptiveness_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_next_communication_reasons`
--

CREATE TABLE `tbl_lkp_next_communication_reasons` (
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_next_communication_reasons_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_next_communication_reasons_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_next_communication_reasons_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  status_score int(11) NOT NULL DEFAULT 0,
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_communication_types`
--

CREATE TABLE `tbl_lkp_communication_types` (
  id int(11) NOT NULL DEFAULT 0,
  type varchar(50) NOT NULL default '',
  description varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_communication_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_communication_types_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_communication_types_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  type varchar(50) NOT NULL default '',
  description varchar(100) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_meeting_status`
--

CREATE TABLE `tbl_lkp_meeting_status` (
  id int(11) NOT NULL auto_increment,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_meeting_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_meeting_status_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_meeting_status_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_meeting_types`
--

CREATE TABLE `tbl_lkp_meeting_types` (
  id int(11) NOT NULL auto_increment,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_meeting_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_meeting_types_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_meeting_types_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_information_request_status`
--

CREATE TABLE `tbl_lkp_information_request_status` (
  id int(11) NOT NULL auto_increment,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_information_request_status_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_information_request_status_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_information_request_status_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_information_request_types`
--

CREATE TABLE `tbl_lkp_information_request_types` (
  id int(11) NOT NULL auto_increment,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_information_request_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_information_request_types_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_information_request_types_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_lkp_information_request_comm_types`
--

CREATE TABLE `tbl_lkp_information_request_comm_types` (
  id int(11) NOT NULL auto_increment,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_information_request_comm_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_information_request_comm_types_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_information_request_comm_types_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT 0,
  description varchar(100) NOT NULL default '',
  sort_order int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (uid)
) TYPE=InnoDB;

--
-- ***** NOT USED *****
-- Table structure for table `tbl_lkp_contact_titles`
--
/*
CREATE TABLE `tbl_lkp_contact_titles` (
  id int(11) NOT NULL DEFAULT '0',
  description varchar(20) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY description (description)
) TYPE=InnoDB;

CREATE TABLE `tbl_lkp_contact_titles_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

insert into tbl_lkp_contact_titles_seq (sequence) values (0);

CREATE TABLE `tbl_lkp_contact_titles_shadow` (
  uid int(11) NOT NULL auto_increment,
  timestamp timestamp NOT NULL,
  id int(11) NOT NULL DEFAULT '0',
  description varchar(20) NOT NULL default '',
  PRIMARY KEY  (uid)
) TYPE=InnoDB;
*/

--
-- View structure for vw_companies
--
-- Note: This view filters out deleted companies
create view vw_companies 
as 
select * 
from tbl_companies 
where deleted = 0;

--
-- View structure for vw_companies_sites
--
-- Note: This view filters out deleted companies (and therefore, by association, deleted sites).
-- 
drop view if exists vw_companies_sites;
create view vw_companies_sites 
as 
select c.*, s.id as site_id, s.name as site_name, address_1, address_2, town, city, postcode, 
s.telephone as site_telephone, s.county_id, lkp_county.name as county, s.country_id, lkp_country.name as country
from tbl_companies c 
left join tbl_sites s on c.id = s.company_id
left join tbl_lkp_counties lkp_county on lkp_county.id = s.county_id
left join tbl_lkp_countries lkp_country on lkp_country.id = s.country_id
where c.deleted = 0;

--
-- View structure for vw_sites
--
-- Note: This view filters out deleted sites
create view vw_sites
as 
select * 
from tbl_sites
where deleted = 0;

--
-- View structure for vw_posts
--
-- Note: This view filters out deleted posts
create view vw_posts 
as 
select * 
from tbl_posts 
where deleted = 0;

--
-- View structure for vw_contacts
--
-- Note: This view filters out deleted contacts
create view vw_contacts 
as 
select * 
from tbl_contacts 
where deleted = 0;

--
-- View structure for vw_posts_contacts 
--
create view vw_posts_contacts 
as 
select p.id, p.company_id, p.job_title, p.propensity, p.telephone_1, p.telephone_2, p.telephone_switchboard, p.telephone_fax, 
c.title, c.first_name, c.surname, c.full_name, c.telephone_mobile, c.email, p.notes
from vw_posts p 
left join vw_contacts c on p.id = c.post_id;

--
-- View structure for vw_client_initiatives 
--
create view vw_client_initiatives 
AS 
select 	c.id 		AS client_id,
		c.name 		AS client_name,
		cm.id 		AS campaign_id,
		cm.revision AS revision,
		i.id 		AS initiative_id,
		i.name 		AS initiative_name 
from tbl_clients c join tbl_campaigns cm on c.id = cm.client_id
left join tbl_initiatives i on cm.id = i.campaign_id;

--
-- View structure for vw_calendar_meetings
--
CREATE VIEW vw_calendar_meetings AS 
SELECT m.*, cli.id AS client_id, cli.name AS client, c.id AS company_id, c.name AS company 
FROM tbl_meetings AS m 
INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id
INNER JOIN tbl_posts AS p ON pi.post_id = p.id
INNER JOIN tbl_companies AS c ON p.company_id = c.id
INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id 
INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id 
INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id;

--
-- View structure for vw_calendar_information_requests
--
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
-- View structure for vw_nbm_communication_count
--
CREATE VIEW vw_nbm_communication_count AS 
SELECT u.id AS user_id, u.name, count(c.id) AS calls
FROM tbl_lkp_users AS u
LEFT JOIN tbl_communications AS c ON u.id = c.user_id
GROUP BY u.id
ORDER BY NULL;

--
-- View structure for vw_nbm_effective_count
--
CREATE VIEW vw_nbm_effective_count AS 
SELECT u.id AS user_id, u.name, count(c.id) AS effectives
FROM tbl_lkp_users AS u
LEFT JOIN tbl_communications AS c ON u.id = c.user_id
WHERE c.type = 'effective'
GROUP BY u.id
ORDER BY NULL;

--
-- View structure for vw_nbm_ote_count
--
CREATE VIEW vw_nbm_ote_count AS 
SELECT u.id AS user_id, u.name, count(c.id) AS otes
FROM tbl_lkp_users AS u
LEFT JOIN tbl_communications AS c ON u.id = c.user_id
WHERE c.ote = 1
GROUP BY u.id
ORDER BY NULL;

--
-- View structure for vw_nbm_meeting_count
--
CREATE VIEW vw_nbm_meeting_count AS
SELECT u.id AS user_id, u.name, count(m.id) AS meetings 
FROM tbl_lkp_users AS u 
LEFT JOIN tbl_meetings AS m on u.id = m.created_by 
GROUP BY u.id 
ORDER BY NULL;

--
-- View structure for vw_nbm_meeting_count_between_dates
--
CREATE VIEW vw_nbm_meeting_count_between_dates AS
SELECT u.id, u.name, count(m.id) AS meetings 
FROM tbl_lkp_users AS u 
LEFT JOIN tbl_meetings AS m on u.id = m.created_by 
WHERE m.date >= {date_from} AND m.date <= {date_to} 
GROUP BY u.id 
ORDER BY NULL;


--
-- View strucure for max communication id by post_initiative_id
--
drop view if exists vw_communication_max_id_by_post_initiative_id;
CREATE VIEW vw_communication_max_id_by_post_initiative_id AS
select max(id), max(communication_date), post_initiative_id
from
tbl_communications com
group by post_initiative_id;

--
-- View strucure for last communication date by post_initiative_id
--
drop view if exists vw_communication_max_date_by_post_initiative_id;
CREATE VIEW vw_communication_max_date_by_post_initiative_id AS
select max(communication_date), post_initiative_id
from
tbl_communications com
group by post_initiative_id;

--
-- View strucure for project refs by post_initiative_id
--
drop view if exists vw_tags_project_ref;
create view vw_tags_project_ref as
SELECT pit.post_initiative_id 
FROM tbl_post_initiative_tags pit 
join tbl_tags t on pit.tag_id = t.id 
join tbl_tag_categories tc on t.category_id = tc.id 
WHERE tc.id = 3;

--
-- View strucure for showing 1 for each communication and 1 or 0 for whether that communication was effective
-- NOTE: Use this view by summing the 1 and 0's to get post communcation/effective counts
drop view if exists vw_post_communication_stats_base;
create view vw_post_communication_stats_base as 
select p.id as post_id, 1 as comm_count, 
case comm.effective when 'effective' then 1 else 0 end as eff_count
from tbl_communications comm
join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
join tbl_posts p on pi.post_id = p.id;

drop view if exists vw_post_communication_stats;
create view vw_post_communication_stats as 
select post_id, sum(comm_count) as communication_count, sum(eff_count) as effective_count 
from vw_post_communication_stats_base
group by post_id;

drop view if exists vw_post_communication_stats_1;
create view vw_post_communication_stats_1 as 
select pi.post_id, count(comm.id) as communication_count, sum(is_effective) as effective_count
from tbl_communications comm
join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
group by post_id;

--drop view if exists vw_post_propensity;
--create view vw_post_propensity as
--select company_id, id as post_id, sum(p.propensity) as sum_propensity, max(p.propensity) as max_propensity, avg(p.propensity) as ave_propensity, min(p.propensity) as min_propensity
--from tbl_posts p 
--group by company_id;

----------------------------------------------------------
-- Stored functions
----------------------------------------------------------
drop function if exists f_next_comm_date_period;
delimiter |
create function f_next_comm_date_period (future_date DATETIME, no_of_months INT(11)) RETURNS CHAR(100) 
NOT DETERMINISTIC NO SQL
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
END |
delimiter ;

drop function if exists f_get_post_meeting_count;
delimiter |
create function f_get_post_meeting_count (var_post_id INT(11)) RETURNS int(11) 
DETERMINISTIC READS SQL DATA
BEGIN 

DECLARE my_result int(11) DEFAULT 0;
select count(*) into my_result FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id and m.status_id = 1;
RETURN my_result;
END |
delimiter ;

----------------------------------------------------------
-- NOTES 
----------------------------------------------------------
/*Can also call stored_procedures from within a stored function - as below:

drop function if exists getPostMeetingCount;
delimiter |;
create function getPostMeetingCount (var_post_id INT(11)) RETURNS int(11) 
BEGIN 

DECLARE my_result int(11) DEFAULT 0;
CALL simpleproc(my_result, var_post_id);
RETURN my_result;
END |
delimiter ;|

drop procedure if exists simpleproc;
delimiter |;
CREATE PROCEDURE simpleproc (OUT param1 INT, IN var_post_id INT)
BEGIN
SELECT COUNT(*) INTO param1 FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id;
END |
delimiter ;|
*/

/* script to remove extra user aliases
create temporary table t_singles select min(id) as id from tbl_user_client_aliases group by user_id, client_id having count(user_id) = 1;

create temporary table t_multiples select min(id) as id from tbl_user_client_aliases group by user_id, client_id having count(user_id) > 1;

create temporary table t_final select id from t_singles union select id from t_multiples;

delete from tbl_user_client_aliases where id not in (select id from t_final);

drop table t_singles;
drop table t_multiple;
drop table t_final;
*/

SET FOREIGN_KEY_CHECKS = 1;