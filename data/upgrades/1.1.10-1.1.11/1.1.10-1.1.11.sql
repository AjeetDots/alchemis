ALTER TABLE tbl_post_initiatives_shadow ADD COLUMN `status_id` int(11) NOT NULL default '0' NULL AFTER initiative_id;
ALTER TABLE tbl_post_initiatives_shadow ADD COLUMN `comment` varchar(255) default NULL AFTER status_id;

ALTER TABLE tbl_post_initiatives_shadow DROP COLUMN `status`;
