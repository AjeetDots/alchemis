SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `tbl_campaigns`
--
ALTER TABLE tbl_campaigns MODIFY COLUMN start_year_month char(6) default null;
ALTER TABLE tbl_campaigns MODIFY COLUMN end_year_month char(6) default null;

ALTER TABLE tbl_campaigns ADD COLUMN `initial_fee` int(11) default NULL AFTER `end_year_month`;
ALTER TABLE tbl_campaigns ADD COLUMN `current_fee` int(11) default NULL AFTER `initial_fee`;
ALTER TABLE tbl_campaigns ADD COLUMN `additional_terms_exist` tinyint(1) default '0' AFTER `notice_date`;

ALTER TABLE tbl_campaigns CHANGE COLUMN `billing_terms` `billing_terms_id` int(11) default NULL;
ALTER TABLE tbl_campaigns CHANGE COLUMN `payment_terms` `payment_terms_id` int(11) default NULL;
ALTER TABLE tbl_campaigns CHANGE COLUMN `payment_method` `payment_method_id` int(11) default NULL;


--
-- Table structure for table `tbl_campaigns_shadow`
--
ALTER TABLE tbl_campaigns_shadow MODIFY COLUMN start_year_month char(6) default null;
ALTER TABLE tbl_campaigns_shadow MODIFY COLUMN end_year_month char(6) default null;

ALTER TABLE tbl_campaigns_shadow ADD COLUMN `initial_fee` int(11) default NULL AFTER `end_year_month`;
ALTER TABLE tbl_campaigns_shadow ADD COLUMN `current_fee` int(11) default NULL AFTER `initial_fee`;
ALTER TABLE tbl_campaigns_shadow ADD COLUMN `additional_terms_exist` tinyint(1) default '0' AFTER `notice_date`;

ALTER TABLE tbl_campaigns_shadow CHANGE COLUMN `billing_terms` `billing_terms_id` int(11) default NULL;
ALTER TABLE tbl_campaigns_shadow CHANGE COLUMN `payment_terms` `payment_terms_id` int(11) default NULL;
ALTER TABLE tbl_campaigns_shadow CHANGE COLUMN `payment_method` `payment_method_id` int(11) default NULL;

--
-- Table structure for table `tbl_lkp_campaign_billing_terms`
--

CREATE TABLE `tbl_lkp_campaign_billing_terms` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_billing_terms (id, description, sort_order) VALUES (1, 'In Advance', 1);
INSERT INTO tbl_lkp_campaign_billing_terms (id, description, sort_order) VALUES (2, 'On completion', 2);
INSERT INTO tbl_lkp_campaign_billing_terms (id, description, sort_order) VALUES (3, '50% in advance, 50% on completion', 3);
INSERT INTO tbl_lkp_campaign_billing_terms (id, description, sort_order) VALUES (4, 'Other', 4);

--
-- Table structure for table `tbl_lkp_campaign_billing_terms_seq`
--

CREATE TABLE `tbl_lkp_campaign_billing_terms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_billing_terms_seq (sequence) VALUES (4);
ALTER TABLE tbl_lkp_campaign_billing_terms_seq AUTO_INCREMENT = 4;

--
-- Table structure for table `tbl_lkp_campaign_payment_methods`
--

CREATE TABLE `tbl_lkp_campaign_payment_methods` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (1, 'Immediate', 1);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (2, 'Within 7 days of the date on the invoice', 2);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (3, 'Within 28 days of the date on the invoice', 3);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (4, 'Within 36 days of the date on the invoice', 4);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (5, 'Within 45 days of the date on the invoice', 5);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (6, 'Within 60 days of the date on the invoice', 6);
INSERT INTO tbl_lkp_campaign_payment_methods (id, description, sort_order) VALUES (7, 'Other', 7);

--
-- Table structure for table `tbl_lkp_campaign_payment_methods_seq`
--

CREATE TABLE `tbl_lkp_campaign_payment_methods_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_payment_methods_seq (sequence) VALUES (7);
ALTER TABLE tbl_lkp_campaign_payment_methods_seq AUTO_INCREMENT = 7;

--
-- Table structure for table `tbl_lkp_campaign_payment_terms`
--

CREATE TABLE `tbl_lkp_campaign_payment_terms` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_payment_terms (id, description, sort_order) VALUES (1, 'By Standing Order', 1);
INSERT INTO tbl_lkp_campaign_payment_terms (id, description, sort_order) VALUES (2, 'By cheque or BACS', 2);
INSERT INTO tbl_lkp_campaign_payment_terms (id, description, sort_order) VALUES (3, 'Other', 3);

--
-- Table structure for table `tbl_lkp_campaign_payment_terms_seq`
--

CREATE TABLE `tbl_lkp_campaign_payment_terms_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_payment_terms_seq (sequence) VALUES (3);
ALTER TABLE tbl_lkp_campaign_payment_terms_seq AUTO_INCREMENT = 3;

--
-- Table structure for table `tbl_lkp_campaign_types`
--

CREATE TABLE `tbl_lkp_campaign_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_types (id, description, sort_order) VALUES (1, 'On going', 1);
INSERT INTO tbl_lkp_campaign_types (id, description, sort_order) VALUES (2, 'Project', 2);
INSERT INTO tbl_lkp_campaign_types (id, description, sort_order) VALUES (3, 'Other', 3);

--
-- Table structure for table `tbl_lkp_campaign_types_seq`
--

CREATE TABLE `tbl_lkp_campaign_types_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_lkp_campaign_types_seq (sequence) VALUES (3);
ALTER TABLE tbl_lkp_campaign_types_seq AUTO_INCREMENT = 3;

SET FOREIGN_KEY_CHECKS = 0;