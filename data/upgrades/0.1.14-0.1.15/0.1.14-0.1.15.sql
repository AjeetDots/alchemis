SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `tbl_batch_run` (
  `id` int(11) NOT NULL auto_increment,
 `type` varchar(50) NOT NULL default '',
  `start` timestamp NOT NULL default '0000-00-00 00:00:00',
  `end` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
 KEY `ix_tbl_batch_run_type` (`type`),
  KEY `ix_tbl_batch_run_start` (`start`),
  KEY `ix_tbl_batch_run_end` (`end`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `tbl_batch_run_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 0;
