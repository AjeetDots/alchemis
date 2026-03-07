DROP TABLE IF EXISTS `tbl_import_lines`;

CREATE TABLE `tbl_import_lines` (
    `id` int(11) NOT NULL auto_increment,
    `row_id` int(11) NOT NULL default '0',
    `alchemis_company_id` int(11) default '0',
    `company_name` varchar(255) default NULL,
    `company_telephone` varchar(50) default NULL,
    `company_website` varchar(255) default NULL,
    `site_address_1` varchar(255) default NULL,
    `site_address_2` varchar(255) default NULL,
    `site_town` varchar(50) default NULL,
    `site_city` varchar(50) default NULL,
    `site_postcode` varchar(25) default NULL,
    `site_county`  varchar(50) default NULL,
    `site_county_id` int(11) default '0',
    `site_country`  varchar(50) default NULL,
    `site_country_id` int(11) default '0',
    `alchemis_post_id` int(11) NULL default '0',
    `post_job_title` varchar(255) default NULL,
    `post_telephone` varchar(50) default NULL,
    `contact_title` varchar(25) default NULL,
    `contact_first_name` varchar(50) default NULL,
    `contact_surname` varchar(50) default NULL,
    `contact_email` varchar(100) default NULL,
    `brand` varchar(100) default NULL,
    `sub_category` varchar(50) default NULL,
    `sub_category_id` int(11) default '0',
    `project_ref` varchar(50) default NULL,
    `client` varchar(100) default NULL,
    `client_initiative_id` int(11) default '0',
    `dedupe_company_name` varchar(255) default NULL,
    `dedupe_company_match_1` varchar(150) default NULL,
    `dedupe_company_match_2` varchar(150) default NULL,
    `dedupe_company_match_3` varchar(150) default NULL,
    `dedupe_contact_first_name` varchar(50) default NULL,
    `dedupe_contact_surname` varchar(50) default NULL,
    PRIMARY KEY  (`id`),
    INDEX `ix_tbl_import_lines_dedupe_company_name` (`dedupe_company_name`),
    INDEX `ix_tbl_import_lines_dedupe_company_match_1` (`dedupe_company_match_1`),
    INDEX `ix_tbl_import_lines_dedupe_company_match_2` (`dedupe_company_match_2`),
    INDEX `ix_tbl_import_lines_dedupe_company_match_3` (`dedupe_company_match_3`),
    INDEX `ix_tbl_import_lines_dedupe_contact_first_name` (`dedupe_contact_first_name`),
    INDEX `ix_tbl_import_lines_dedupe_contact_surname` (`dedupe_contact_surname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ALTER TABLE `tbl_import_lines` ADD COLUMN `site_county_id` int(11) default '0' AFTER `site_county`;
-- ALTER TABLE `tbl_import_lines` ADD COLUMN `site_country_id` int(11) default '0' AFTER `site_county_id`;
-- ALTER TABLE `tbl_import_lines` DROP COLUMN `alchemis_contact_id`;
-- ALTER TABLE `tbl_import_lines` ADD COLUMN `dedupe_contact_first_name` varchar(150) default NULL AFTER `dedupe_company_match_3`;
-- ALTER TABLE `tbl_import_lines` ADD COLUMN `dedupe_contact_surname` varchar(150) default NULL AFTER `dedupe_contact_first_name`;
-- ALTER TABLE `tbl_import_lines` ADD INDEX `ix_tbl_import_lines_dedupe_contact_first_name` (`dedupe_contact_first_name`);
-- ALTER TABLE `tbl_import_lines` ADD INDEX `ix_tbl_import_lines_dedupe_contact_surname` (`dedupe_contact_surname`);

DROP TABLE IF EXISTS `tbl_import_lines_archive`;

CREATE TABLE `tbl_import_lines_archive` (
    `shadow_id` int(11) NOT NULL auto_increment,
    `id` int(11) NOT NULL default '0',
    `row_id` int(11) NOT NULL default '0',
    `alchemis_company_id` int(11) default '0',
    `company_name` varchar(255) default NULL,
    `company_telephone` varchar(50) default NULL,
    `company_website` varchar(255) default NULL,
    `site_address_1` varchar(255) default NULL,
    `site_address_2` varchar(255) default NULL,
    `site_town` varchar(50) default NULL,
    `site_city` varchar(50) default NULL,
    `site_postcode` varchar(25) default NULL,
    `site_county`  varchar(50) default NULL,
    `site_county_id` int(11) default '0',
    `site_country`  varchar(50) default NULL,
    `site_country_id` int(11) default '0',
    `alchemis_post_id` int(11) NULL default '0',
    `post_job_title` varchar(255) default NULL,
    `post_telephone` varchar(50) default NULL,
    `contact_title` varchar(25) default NULL,
    `contact_first_name` varchar(50) default NULL,
    `contact_surname` varchar(50) default NULL,
    `contact_email` varchar(100) default NULL,
    `brand` varchar(100) default NULL,
    `sub_category` varchar(50) default NULL,
    `sub_category_id` int(11) default '0',
    `project_ref` varchar(50) default NULL,
    `client` varchar(100) default NULL,
    `client_initiative_id` int(11) default '0',
    `dedupe_company_name` varchar(255) default NULL,
    `dedupe_company_match_1` varchar(150) default NULL,
    `dedupe_company_match_2` varchar(150) default NULL,
    `dedupe_company_match_3` varchar(150) default NULL,
    `dedupe_contact_first_name` varchar(50) default NULL,
    `dedupe_contact_surname` varchar(50) default NULL,
    PRIMARY KEY  (`shadow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `tbl_companies` ADD COLUMN `dedupe_company_name` varchar(150) default NULL AFTER telephone_tps;
ALTER TABLE `tbl_companies` ADD COLUMN `dedupe_company_match_1` varchar(150) default NULL;
ALTER TABLE `tbl_companies` ADD COLUMN `dedupe_company_match_2` varchar(150) default NULL;
ALTER TABLE `tbl_companies` ADD COLUMN `dedupe_company_match_3` varchar(150) default NULL;

ALTER TABLE `tbl_companies` ADD INDEX `ix_tbl_companies_dedupe_company_name` (`dedupe_company_name`);
ALTER TABLE `tbl_companies` ADD INDEX `ix_tbl_companies_dedupe_company_match_1` (`dedupe_company_match_1`);
ALTER TABLE `tbl_companies` ADD INDEX `ix_tbl_companies_dedupe_company_match_2` (`dedupe_company_match_2`);
ALTER TABLE `tbl_companies` ADD INDEX `ix_tbl_companies_dedupe_company_match_3` (`dedupe_company_match_3`);

ALTER TABLE `tbl_contacts` ADD COLUMN `dedupe_contact_first_name` varchar(50) default NULL;
ALTER TABLE `tbl_contacts` ADD COLUMN `dedupe_contact_surname` varchar(50) default NULL;

ALTER TABLE `tbl_contacts` ADD INDEX `ix_tbl_contacts_dedupe_contact_first_name` (`dedupe_contact_first_name`);
ALTER TABLE `tbl_contacts` ADD INDEX `ix_tbl_contacts_dedupe_contact_surname` (`dedupe_contact_surname`);

DROP TABLE IF EXISTS `tbl_import_company_matches`;

CREATE TABLE `tbl_import_company_matches` (
    `id` int(11) NOT NULL auto_increment,
    `import_row_id` int(11) NOT NULL default '0',
    `alchemis_company_id` int(11) NOT NULL default '0',
    `match_type` varchar(100) default NULL,
    PRIMARY KEY  (`id`),
    INDEX `ix_tbl_import_company_matches_import_row_id` (`import_row_id`),
    INDEX `ix_tbl_import_company_matches_alchemis_company_id` (`alchemis_company_id`),
    INDEX `ix_tbl_import_company_matches_match_type` (`match_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tbl_import_post_matches`;

CREATE TABLE `tbl_import_post_matches` (
    `id` int(11) NOT NULL auto_increment,
    `import_id` int(11) NOT NULL default '0',
    `alchemis_post_id` int(11) NOT NULL default '0',
    `match_type` varchar(100) default NULL,
    PRIMARY KEY  (`id`),
    INDEX `ix_tbl_import_post_matches_import_id` (`import_id`),
    INDEX `ix_tbl_import_post_matches_alchemis_post_id` (`alchemis_post_id`),
    INDEX `ix_tbl_import_post_matches_match_type` (`match_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;