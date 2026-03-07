--
-- Table structure for table `tbl_object_tiered_characteristics`
--

CREATE TABLE `tbl_object_tiered_characteristics` (
  `id` int(11) NOT NULL auto_increment,
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tiered_characteristic_id`, `company_id`),
  KEY `ix_tbl_object_tiered_characteristics_tiered_characteristic_id` (`tiered_characteristic_id`),
  KEY `ix_tbl_object_tiered_characteristics_tier` (`tier`),
  KEY `ix_tbl_object_tiered_characteristics_company_id` (`company_id`),
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk1` FOREIGN KEY (`tiered_characteristic_id`) REFERENCES `tbl_tiered_characteristics` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ix_tbl_object_tiered_characteristics_ibfk2` FOREIGN KEY (`company_id`) REFERENCES `tbl_companies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics_seq`
--

CREATE TABLE `tbl_object_tiered_characteristics_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_object_tiered_characteristics_shadow`
--

CREATE TABLE `tbl_object_tiered_characteristics_shadow` (
  `shadow_id` int(11) NOT NULL auto_increment,
  `shadow_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `shadow_updated_by` int(11) NOT NULL,
  `shadow_type` char(1) default NULL,
  `id` int(11) NOT NULL default '0',
  `tiered_characteristic_id` int(11) NOT NULL,
  `tier` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Copy the data across
--
INSERT INTO tbl_object_tiered_characteristics SELECT id, tiered_characteristic_id, tier, company_id FROM tbl_company_tiered_characteristics;

--
-- Set up the sequence table
--
SELECT MAX(id) FROM tbl_object_tiered_characteristics;
ALTER TABLE tbl_object_tiered_characteristics_seq AUTO_INCREMENT = 15863;
DELETE FROM tbl_object_tiered_characteristics_seq;
INSERT INTO tbl_object_tiered_characteristics_seq VALUES (15863);


DROP TABLE IF EXISTS tbl_company_tiered_characteristics;
DROP TABLE IF EXISTS tbl_company_tiered_characteristics_seq;
DROP TABLE IF EXISTS tbl_company_tiered_characteristics_shadow;