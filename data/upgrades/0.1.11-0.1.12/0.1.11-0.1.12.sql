SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_action_resources`
--

ALTER TABLE `tbl_action_resources` ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_action_resources` CHANGE COLUMN `id` `id` int(11) NOT NULL auto_increment;

  
SET FOREIGN_KEY_CHECKS = 1;