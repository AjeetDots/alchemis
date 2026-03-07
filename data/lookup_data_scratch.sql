
#-----------------------------------
select 'tbl_lkp_contact_titles';
#-----------------------------------
/*!40000 alter table tbl_lkp_contact_titles disable keys */;
lock tables tbl_lkp_contact_titles write;
#----------------------------------------|----|----------------------|
# -- Columns --                          | id | description (20)     |
#----------------------------------------|----|----------------------|
insert into tbl_lkp_contact_titles values (   1, 'Mr');
insert into tbl_lkp_contact_titles values (   2, 'Mrs');
insert into tbl_lkp_contact_titles values (   3, 'Dr');
insert into tbl_lkp_contact_titles values (   4, 'Ms');
#----------------------------------------|----|----------------------|
unlock tables;
/*!40000 alter table tbl_lkp_contact_titles enable keys */;

#------------------------------------
select 'tbl_lkp_contact_titles_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_contact_titles_seq disable keys */;
lock tables tbl_lkp_contact_titles_seq write;

update tbl_lkp_contact_titles_seq set id = 4;

unlock tables;
/*!40000 alter table tbl_lkp_countries_seq enable keys */;

#------------------------------------
select 'tbl_lkp_contact_titles_shadow';
#------------------------------------
/*!40000 alter table tbl_lkp_contact_titles_shadow disable keys */;
lock tables tbl_lkp_contact_titles_shadow write;

insert into tbl_lkp_contact_titles_shadow select * from tbl_lkp_contact_titles order by id;

unlock tables;
/*!40000 alter table tbl_lkp_contact_titles_shadow enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------