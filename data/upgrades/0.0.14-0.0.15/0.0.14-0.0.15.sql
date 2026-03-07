update tbl_communications set type_id = 5 where comments like 'Marketing item%' and user_id in (1,41) and type_id != 5;

alter table tbl_lkp_communication_types add column is_active boolean not null default '0';
alter table tbl_lkp_communication_types add column sort_order int(11) not null default '0';

alter table tbl_lkp_communication_types add key `ix_tbl_lkp_communication_types_is_active` (`is_active`);
alter table tbl_lkp_communication_types add key `ix_tbl_lkp_communication_types_sort_order` (`sort_order`);

update tbl_lkp_communication_types set is_active = 1 where id in (1,4,5);

--
-- Table structure for table `tbl_lkp_regions`
--
DROP TABLE IF EXISTS tbl_lkp_regions;
CREATE TABLE `tbl_lkp_regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  `description` varchar(100) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_regions_seq`
--
DROP TABLE IF EXISTS tbl_lkp_regions_seq;
CREATE TABLE `tbl_lkp_regions_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_postcodes`
--

CREATE TABLE `tbl_lkp_postcodes` (
  `id` int(11) NOT NULL auto_increment,
  `postcode` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_postcodes_postcode` (`postcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_postcodes_seq`
--

CREATE TABLE `tbl_lkp_postcodes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data updates to populate tbl_lkp_postcodes
insert into tbl_lkp_postcodes (postcode) select substring(postcode, 1,3) from tbl_sites where substring(postcode,3,1) = ' ' group by substring(postcode,1,3);
insert into tbl_lkp_postcodes (postcode) select substring(postcode, 1,4) from tbl_sites where substring(postcode,4,1) = ' ' group by substring(postcode,1,4);
insert into tbl_lkp_postcodes (postcode) select substring(postcode, 1,5) from tbl_sites where substring(postcode,5,1) = ' ' group by substring(postcode,1,5);

--
-- Table structure for table `tbl_lkp_region_postcodes`
--
DROP TABLE IF EXISTS `tbl_lkp_region_postcodes`;
CREATE TABLE `tbl_lkp_region_postcodes` (
  `id` int(11) NOT NULL auto_increment,
  `region_id` int(11) NOT NULL,
  `postcode_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ix_tbl_lkp_region_postcodes_region_id` (`region_id`),
  KEY `ix_tbl_lkp_region_postcodes_postcode_id` (`postcode_id`),
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk1` FOREIGN KEY (`region_id`) REFERENCES `tbl_lkp_regions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `tbl_lkp_region_postcodes_ibfk2` FOREIGN KEY (`postcode_id`) REFERENCES `tbl_lkp_postcodes` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_lkp_region_postcodes_seq`
--
DROP TABLE IF EXISTS `tbl_lkp_region_postcodes_seq`;
CREATE TABLE `tbl_lkp_region_postcodes_seq` (
  `sequence` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;	

--
-- Table structure for table `tbl_sites`
--
alter table tbl_sites add column region_postcode varchar(10) NULL;
alter table tbl_sites add key `ix_tbl_sites_region_postcode` (`region_postcode`);
alter table tbl_sites add CONSTRAINT `tbl_sites_region_postcode` FOREIGN KEY (`region_postcode`) REFERENCES `tbl_lkp_postcodes` (`postcode`) ON UPDATE CASCADE ON DELETE CASCADE;

-- Data updates to populate tbl_sites.region_postcode
update tbl_sites set region_postcode = substring(postcode, 1,3) where substring(postcode,3,1) = ' ';
update tbl_sites set region_postcode = substring(postcode, 1,4) where substring(postcode,4,1) = ' ';
update tbl_sites set region_postcode = substring(postcode, 1,5) where substring(postcode,5,1) = ' ';

drop view if exists vw_companies_sites;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_companies_sites` AS select `c`.`id` AS `id`,`c`.`name` AS `name`,`c`.`website` AS `website`,`c`.`telephone` AS `telephone`,`c`.`deleted` AS `deleted`,`s`.`id` AS `site_id`,`s`.`name` AS `site_name`,`s`.`address_1` AS `address_1`,`s`.`address_2` AS `address_2`,`s`.`town` AS `town`,`s`.`city` AS `city`,`s`.`postcode` AS `postcode`,`s`.`region_postcode`, `s`.`telephone` AS `site_telephone`,`s`.`county_id` AS `county_id`,`lkp_county`.`name` AS `county`,`s`.`country_id` AS `country_id`,`lkp_country`.`name` AS `country` from (((`tbl_companies` `c` left join `tbl_sites` `s` on((`c`.`id` = `s`.`company_id`))) left join `tbl_lkp_counties` `lkp_county` on((`lkp_county`.`id` = `s`.`county_id`))) left join `tbl_lkp_countries` `lkp_country` on((`lkp_country`.`id` = `s`.`country_id`))) where (`c`.`deleted` = 0) */;


