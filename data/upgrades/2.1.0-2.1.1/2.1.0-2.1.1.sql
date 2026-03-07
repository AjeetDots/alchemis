ALTER TABLE `tbl_import_lines` ADD COLUMN `sub_category_1` varchar(150) default NULL AFTER `sub_category_id`;
ALTER TABLE `tbl_import_lines` ADD COLUMN `sub_category_1_id` int(11) default '0' AFTER `sub_category_1`;
ALTER TABLE `tbl_import_lines` ADD COLUMN `post_tag` varchar(150)  default NULL AFTER `company_tag`;

