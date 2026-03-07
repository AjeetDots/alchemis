alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_value` (`value`);
alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_company_id_value` (`company_id`, `value`);
alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_char_id_company_id_value` (`characteristic_id`, `company_id`, `value`);

alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_value` (`value`);
alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_post_id_value` (`post_id`, `value`);
alter table tbl_object_characteristics_date add index `ix_tbl_object_characteristics_date_char_id_post_id_value` (`characteristic_id`, `post_id`, `value`);