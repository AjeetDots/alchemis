#-----------------------------------
select 'tbl_rbac_users';
#-----------------------------------
/*!40000 alter table tbl_rbac_users disable keys */;
lock tables tbl_rbac_users write;
#----------------------------------------------|----|--------------------|----------------------|-----------------------------------|---------------|-----------|
# -- Columns --                                | id | handle (32)        | password(32)         | name(255)                         | last_login    | is_active |
#----------------------------------------------|----|--------------------|----------------------|-----------------------------------|---------------|-----------|
insert into tbl_rbac_users values 				(1,  'import_process',     md5('import process'), 'Import Process',                   null,           1         );
#----------------------------------------------|----|--------------------|----------------------|-----------------------------------|---------------|-----------|
unlock tables;
/*!40000 alter table tbl_rbac_users enable keys */;

#------------------------------------
#select 'tbl_rbac_users_seq';
#------------------------------------
#/*!40000 alter table tbl_rbac_users_seq disable keys */;
#lock tables tbl_rbac_users_seq write;

#update tbl_rbac_users_seq set sequence = 1;
#alter table tbl_rbac_users_seq auto_increment = 2;

#unlock tables;
#/*!40000 alter table tbl_rbac_users_seq enable keys */;

#------------------------------------
#select 'tbl_rbac_users_shadow';
#------------------------------------
#insert into tbl_rbac_users_shadow (id, type, description) select id, type, description from tbl_rbac_users;
#---------------------------------------------------------------------------------------------------------------------------------
