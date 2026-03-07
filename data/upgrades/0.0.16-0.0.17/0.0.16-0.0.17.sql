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
  CONSTRAINT `ix_tbl_post_notes_ibfk1` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `ix_tbl_post_notes_ibfk2` FOREIGN KEY (`created_by`) REFERENCES `tbl_rbac_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_post_notes_seq`
--

CREATE TABLE `tbl_post_notes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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