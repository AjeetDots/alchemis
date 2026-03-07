set foreign_key_checks = 0;

alter table tbl_campaign_nbms add column user_email varchar(100) null after user_alias;

CREATE TABLE `tbl_communication_attachments` (
  `id` int(11) NOT NULL auto_increment,
  `communication_id` int(11) NOT NULL default '0',
  `document_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_communication_attachments_comunication_id` (`communication_id`),
  KEY `ix_tbl_communication_attachments_document_id` (`document_id`),
  CONSTRAINT `ix_tbl_communication_attachments_ibfk1` FOREIGN KEY (`communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_communication_attachments_ibfk2` FOREIGN KEY (`document_id`) REFERENCES `tbl_documents` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1; 

CREATE TABLE `tbl_communication_attachments_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

insert into tbl_lkp_communication_types (id, type, description, is_active, sort_order) values (6, 'user', 'email', 1, 0);

alter table tbl_communications add column `has_attachment` tinyint(1) default '0';
update tbl_communications set `has_attachment` = 0;

alter table tbl_documents add column `deleted` tinyint(1) NOT NULL default '0';

set foreign_key_checks = 1;