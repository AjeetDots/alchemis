insert into tbl_lkp_communication_types (id, type, description) values (5, 'system', 'mailer');

--
-- Table structure for table `tbl_mailer_items`
--
alter table tbl_mailer_items add column despatched_communication_id int(11);
alter table tbl_mailer_items add column response_communication_id int(11);
alter table tbl_mailer_items add index `ix_tbl_mailer_items_despatched_communication_id` (`despatched_communication_id`);
alter table tbl_mailer_items add index `ix_tbl_mailer_items_response_communication_id` (`response_communication_id`);
alter table tbl_mailer_items add CONSTRAINT `tbl_mailer_items_ibfk3` FOREIGN KEY (`despatched_communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;
alter table tbl_mailer_items add CONSTRAINT `tbl_mailer_items_ibfk4` FOREIGN KEY (`response_communication_id`) REFERENCES `tbl_communications` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Table structure for table `tbl_mailer_item_responses`
--
ALTER TABLE tbl_mailer_item_responses DROP FOREIGN KEY `tbl_mailer_item_reponses_ibfk1`;
alter table tbl_mailer_item_responses drop index `ix_tbl_mailer_item_reponses_mailer_item_id`;
alter table tbl_mailer_item_responses add index `ix_tbl_mailer_item_responses_mailer_item_id` (`mailer_item_id`);
alter table tbl_mailer_item_responses add CONSTRAINT `tbl_mailer_item_responses_ibfk1` FOREIGN KEY (`mailer_item_id`) REFERENCES `tbl_mailer_items` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tbl_mailer_item_responses DROP FOREIGN KEY `tbl_mailer_item_reponses_ibfk2`;
alter table tbl_mailer_item_responses drop index `ix_tbl_mailer_item_reponses_mailer_response_id`;
alter table tbl_mailer_item_responses add index `ix_tbl_mailer_item_responses_mailer_response_id` (`mailer_response_id`);
alter table tbl_mailer_item_responses add CONSTRAINT `tbl_mailer_item_responses_ibfk2` FOREIGN KEY (`mailer_response_id`) REFERENCES `tbl_lkp_mailer_responses` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;




