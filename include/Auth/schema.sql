SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_rbac_commands`
--

DROP TABLE IF EXISTS `tbl_rbac_commands`;
CREATE TABLE `tbl_rbac_commands` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '' UNIQUE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `tbl_rbac_permissions`
--

DROP TABLE IF EXISTS `tbl_rbac_permissions`;
CREATE TABLE `tbl_rbac_permissions` (
  `id` int(11) NOT NULL default '0',
  `command_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `tbl_rbac_roles`
--

DROP TABLE IF EXISTS `tbl_rbac_roles`;
CREATE TABLE `tbl_rbac_roles` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '' UNIQUE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `tbl_rbac_roles_seq`;
CREATE TABLE `tbl_rbac_roles_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) TYPE=InnoDB;

--
-- Table structure for table `tbl_rbac_role_permissions`
--

DROP TABLE IF EXISTS `tbl_rbac_role_permissions`;
CREATE TABLE `tbl_rbac_role_permissions` (
  `id` int(11) NOT NULL default '0',
  `role_id` int(11) NOT NULL default '0',
  `permission_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ix_tbl_rbac_role_permissions_1` (`role_id`,`permission_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `tbl_rbac_sessions`
--

DROP TABLE IF EXISTS `tbl_rbac_sessions`;
CREATE TABLE `tbl_rbac_sessions` (
  `id` varchar(32) NOT NULL default '',
  `expiration` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_rbac_sessions_expiration` (`expiration`)
) ENGINE=InnoDB;

--
-- Table structure for table `tbl_rbac_users`
--

DROP TABLE IF EXISTS `tbl_rbac_users`;
CREATE TABLE `tbl_rbac_users` (
  `id` int(11) NOT NULL default '0',
  `handle` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `last_login` datetime default '1970-01-01 00:00:00',
  `is_active` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `tbl_rbac_user_roles`
--

DROP TABLE IF EXISTS `tbl_rbac_user_roles`;
CREATE TABLE `tbl_rbac_user_roles` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `role_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ix_tbl_rbac_user_roles_1` (`user_id`,`role_id`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;