
update tbl_object_tiered_characteristics set tiered_characteristic_id = 1 where tiered_characteristic_id = 549;

update tbl_object_tiered_characteristics set tiered_characteristic_id = 2 where tiered_characteristic_id = 833;
update tbl_tiered_characteristics set parent_id = 1 where id = 834;
update tbl_object_tiered_characteristics set tiered_characteristic_id = 8 where tiered_characteristic_id = 835;
update tbl_object_tiered_characteristics set tiered_characteristic_id = 10 where tiered_characteristic_id = 836;
update tbl_object_tiered_characteristics set tiered_characteristic_id = 18 where tiered_characteristic_id = 837;
update tbl_object_tiered_characteristics set tiered_characteristic_id = 24 where tiered_characteristic_id = 838;
update tbl_object_tiered_characteristics set tiered_characteristic_id = 28 where tiered_characteristic_id = 839; 

delete from tbl_tiered_characteristics where parent_id = 549;
delete from tbl_tiered_characteristics where id = 549;

delete from tbl_campaign_nbm_targets where user_id = 87 and campaign_id = 549;
