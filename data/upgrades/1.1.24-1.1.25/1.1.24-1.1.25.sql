set foreign_key_checks = 0;

alter table tbl_companies add column telephone_tps boolean default 0 null;

update tbl_companies set telephone_tps = 0;

update tbl_companies t join tbl_object_characteristics_date ocd on t.id = ocd.company_id set t.telephone_tps = 1 where ocd.characteristic_id = 17;

delete from tbl_object_characteristics_date where characteristic_id = 17;

delete from tbl_object_characteristics_boolean where characteristic_id = 17;

delete from tbl_characteristics where id = 17;

set foreign_key_checks = 1;