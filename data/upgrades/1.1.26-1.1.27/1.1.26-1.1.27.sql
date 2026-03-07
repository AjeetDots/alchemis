alter table tbl_rbac_users add column permission_add_bulk_ref tinyint(1) not null default 0;
alter table tbl_rbac_users add column permission_email_to_prospect tinyint(1) not null default 0;
