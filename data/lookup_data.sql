set FOREIGN_KEY_CHECKS = 0;

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


#-----------------------------------
select 'tbl_lkp_event_types';
#-----------------------------------
/*!40000 alter table tbl_lkp_event_types disable keys */;
lock tables tbl_lkp_event_types write;
#--------------------------------------|----|---------------|
# -- Columns --                        | id | name          |
#--------------------------------------|----|---------------|
INSERT INTO tbl_lkp_event_types VALUES (  1 , 'Calling Day' );
INSERT INTO tbl_lkp_event_types VALUES (  2 , 'Holiday'     );
INSERT INTO tbl_lkp_event_types VALUES (  3 , 'Incentive'   );
INSERT INTO tbl_lkp_event_types VALUES (  4 , 'Internal'    );
INSERT INTO tbl_lkp_event_types VALUES (  5 , 'Sick'        );
#--------------------------------------|----|---------------|
unlock tables;
/*!40000 alter table tbl_lkp_regions enable keys */;

#------------------------------------
select 'tbl_events_seq';
#------------------------------------
/*!40000 alter table tbl_events_seq disable keys */;
lock tables tbl_events_seq write;

ALTER TABLE tbl_events_seq AUTO_INCREMENT = 5;
INSERT INTO tbl_events_seq VALUES (5);

unlock tables;
/*!40000 alter table tbl_events_seq enable keys */;

#-----------------------------------
select 'tbl_lkp_regions';
#-----------------------------------
/*!40000 alter table tbl_lkp_regions disable keys */;
lock tables tbl_lkp_regions write;
#----------------------------------|----|---------------------|
# -- Columns --                    | id | name (100)          |
#----------------------------------|----|---------------------|
insert into tbl_lkp_regions values (   1, 'London'            );
insert into tbl_lkp_regions values (   2, 'South East'        );
insert into tbl_lkp_regions values (   3, 'South West'        );
#----------------------------------|----|---------------------|
unlock tables;
/*!40000 alter table tbl_lkp_regions enable keys */;

#------------------------------------
select 'tbl_lkp_regions_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_regions_seq disable keys */;
lock tables tbl_lkp_regions_seq write;

update tbl_lkp_regions_seq set sequence = 3;
alter table tbl_lkp_regions_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_regions_seq enable keys */;

#-----------------------------------
select 'tbl_lkp_region_postcodes';
#-----------------------------------
/*!40000 alter table tbl_lkp_region_postcodes disable keys */;
lock tables tbl_lkp_region_postcodes write;
#-------------------------------------------|----|-----------|---------------|
# -- Columns --                             | id | region_id | postcode (10) |
#-------------------------------------------|----|-----------|---------------|
insert into tbl_lkp_region_postcodes values (  1,  1,         'E'            );
insert into tbl_lkp_region_postcodes values (  2,  1,         'EC'           );
insert into tbl_lkp_region_postcodes values (  3,  1,         'N'            );
insert into tbl_lkp_region_postcodes values (  4,  1,         'NW'           );
insert into tbl_lkp_region_postcodes values (  5,  1,         'SE'           );
insert into tbl_lkp_region_postcodes values (  6,  1,         'SW'           );
insert into tbl_lkp_region_postcodes values (  7,  1,         'W'            );
insert into tbl_lkp_region_postcodes values (  8,  1,         'WC'           );
#-------------------------------------------|----|-----------|---------------|
unlock tables;
/*!40000 alter table tbl_lkp_region_postcodes enable keys */;

#------------------------------------
select 'tbl_lkp_region_postcodes_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_region_postcodes_seq disable keys */;
lock tables tbl_lkp_region_postcodes_seq write;

update tbl_lkp_region_postcodes_seq set sequence = 8;
alter table tbl_lkp_region_postcodes_seq auto_increment = 9;

unlock tables;
/*!40000 alter table tbl_lkp_region_postcodes_seq enable keys */;

#-----------------------------------
select 'tbl_lkp_mailer_types';
#-----------------------------------
/*!40000 alter table tbl_lkp_mailer_types disable keys */;
lock tables tbl_lkp_mailer_types write;
#---------------------------------------|----|----------------------|---------------|
# -- Columns --              	        | id | 	description (100)	|  sort_order   |
#---------------------------------------|----|----------------------|---------------|
insert into tbl_lkp_mailer_types values	( 1,  'Postal',              1            );
insert into tbl_lkp_mailer_types values ( 2,  'E-mail',              2            );
insert into tbl_lkp_mailer_types values ( 3,  'Fax',                 3            );
#---------------------------------------|----|----------------------|---------------|
unlock tables;
/*!40000 alter table tbl_lkp_mailer_types enable keys */;

#------------------------------------
select 'tbl_lkp_mailer_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_mailer_types_seq disable keys */;
lock tables tbl_lkp_mailer_types_seq write;

update tbl_lkp_mailer_types_seq set sequence = 3;
alter table tbl_lkp_mailer_types_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_mailer_types_seq enable keys */;

#-----------------------------------------
select 'tbl_lkp_mailer_response_groups';
#-----------------------------------------
/*!40000 alter table tbl_lkp_mailer_response_groups disable keys */;
lock tables tbl_lkp_mailer_response_groups write;
#-------------------------------------------------|----|--------------------------------------|---------------|
# -- Columns --              	                  | id | 	description (100)		      	  |  sort_order   |
#-------------------------------------------------|----|--------------------------------------|---------------|
insert into tbl_lkp_mailer_response_groups values ( 1,  'Tickbox (Standard) - 5 choices',       1             );
insert into tbl_lkp_mailer_response_groups values ( 2,  'Tickbox (Advertising) - 3 choices',    2             );
insert into tbl_lkp_mailer_response_groups values ( 3,  'General Comment',                      3             );
#-------------------------------------------------|----|--------------------------------------|----------------|
unlock tables;
/*!40000 alter table tbl_lkp_mailer_response_groups enable keys */;

#------------------------------------
select 'tbl_lkp_mailer_response_groups_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_mailer_types_seq disable keys */;
lock tables tbl_lkp_mailer_response_groups_seq write;

update tbl_lkp_mailer_response_groups_seq set sequence = 3;
alter table tbl_lkp_mailer_response_groups_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_mailer_response_groups_seq enable keys */;

#-----------------------------------------
select 'tbl_lkp_mailer_responses';
#-----------------------------------------
/*!40000 alter table tbl_lkp_mailer_responses disable keys */;
lock tables tbl_lkp_mailer_responses write;
#-------------------------------------------|----|----------------------|---------------------------------------------------|------------|
# -- Columns --              	            | id | 	response_group_id	| description                                       | sort_order |
#-------------------------------------------|----|----------------------|---------------------------------------------------|------------|
insert into tbl_lkp_mailer_responses values ( 1,    1,                    'Contact gone away',                                 1         );
insert into tbl_lkp_mailer_responses values ( 2,    1,                    'Never darken my door again',                        2         );
insert into tbl_lkp_mailer_responses values ( 3,    1,                    'No need for a new business agency at the moment',   3         );
insert into tbl_lkp_mailer_responses values ( 4,    1,                    'Use a new business agency',                         4         );
insert into tbl_lkp_mailer_responses values ( 5,    1,                    'Would like to find out more',                       5         );
#-------------------------------------------|----|----------------------|---------------------------------------------------|------------|

unlock tables;
/*!40000 alter table tbl_lkp_mailer_responses enable keys */;

#------------------------------------
select 'tbl_lkp_mailer_responses_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_mailer_responses_seq disable keys */;
lock tables tbl_lkp_mailer_responses_seq write;

update tbl_lkp_mailer_responses_seq set sequence = 5;
alter table tbl_lkp_mailer_responses_seq auto_increment = 6;

unlock tables;
/*!40000 alter table tbl_lkp_mailer_responses_seq enable keys */;

#-----------------------------------
select 'tbl_tag_categories';
#-----------------------------------
/*!40000 alter table tbl_tag_categories disable keys */;
lock tables tbl_tag_categories write;
#-------------------------------------|----|-----------------------|
# -- Columns --                       | id | name (50)             |
#-------------------------------------|----|-----------------------|
insert into tbl_tag_categories values (   1, 'brand'               );
insert into tbl_tag_categories values (   2, 'general'             );
insert into tbl_tag_categories values (   3, 'project ref'         );
#-------------------------------------|----|-----------------------|
unlock tables;
/*!40000 alter table tbl_tag_categories enable keys */;

#------------------------------------
select 'tbl_tag_categories_seq';
#------------------------------------
/*!40000 alter table tbl_tag_categories_seq disable keys */;
lock tables tbl_tag_categories_seq write;

update tbl_tag_categories_seq set sequence = 3;
alter table tbl_tag_categories_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_tag_categories_seq enable keys */;

#------------------------------------
select 'tbl_tag_categories_shadow';
#------------------------------------
insert into tbl_tag_categories_shadow (id, name) select id, name from tbl_tag_categories order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#-----------------------------------
select 'tbl_tags';
#-----------------------------------
/*!40000 alter table tbl_tags disable keys */;
lock tables tbl_tags write;
#---------------------------|----|----------------------------------------|-------------|
# -- Columns --             | id | value (50)                             | category_id |
#---------------------------|----|----------------------------------------|-------------|
insert into tbl_tags values (   1, 'woolwich',                              1           );
insert into tbl_tags values (   2, 'barclay card',                          1           );
insert into tbl_tags values (   3, 'marstons',                              1           );
insert into tbl_tags values (   4, 'burtons',                               1           );
insert into tbl_tags values (   5, 'brewer',                                2           );
#---------------------------|----|----------------------------------------|-------------|
unlock tables;
/*!40000 alter table tbl_tags enable keys */;

#------------------------------------
select 'tbl_tags_seq';
#------------------------------------
/*!40000 alter table tbl_tags_seq disable keys */;
lock tables tbl_tags_seq write;

update tbl_tags_seq set sequence = 3;
alter table tbl_tags_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_tags_seq enable keys */;

#------------------------------------
select 'tbl_tags_shadow';
#------------------------------------
insert into tbl_tags_shadow (id, value, category_id) select id, value, category_id from tbl_tags order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_company_tags';
#-----------------------------------
/*!40000 alter table tbl_company_tags disable keys */;
lock tables tbl_company_tags write;
#-----------------------------------|----|------------|--------|
# -- Columns --                     | id | company_id | tag_id |
#-----------------------------------|----|------------|--------|
insert into tbl_company_tags values (   1,          15,       1);
insert into tbl_company_tags values (   2,          15,       2);
insert into tbl_company_tags values (   3,           1,       3);
insert into tbl_company_tags values (   4,           1,       4);
insert into tbl_company_tags values (   5,           1,       5);
#-----------------------------------|----|------------|--------|
unlock tables;
/*!40000 alter table tbl_company_tags enable keys */;

#------------------------------------
select 'tbl_company_tags_seq';
#------------------------------------
/*!40000 alter table tbl_company_tags_seq disable keys */;
lock tables tbl_company_tags_seq write;

update tbl_company_tags_seq set sequence = 3;
alter table tbl_company_tags_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_company_tags_seq enable keys */;

#------------------------------------
select 'tbl_company_tags_shadow';
#------------------------------------
insert into tbl_company_tags_shadow (id, company_id, tag_id) select id, company_id, tag_id from tbl_company_tags order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_tiered_characteristic_categories';
#-----------------------------------
/*!40000 alter table tbl_tiered_characteristic_categories disable keys */;
lock tables tbl_tiered_characteristic_categories write;
#-------------------------------------------------------|----|---------------------|
# -- Columns --                                         | id | name (50)           |
#-------------------------------------------------------|----|---------------------|
insert into tbl_tiered_characteristic_categories values (   1, 'company categories');
#-------------------------------------------------------|----|---------------------|
unlock tables;
/*!40000 alter table tbl_tiered_characteristic_categories enable keys */;

#------------------------------------
select 'tbl_tiered_characteristic_categories_seq';
#------------------------------------
/*!40000 alter table tbl_tiered_characteristic_categories_seq disable keys */;
lock tables tbl_tiered_characteristic_categories_seq write;

update tbl_tiered_characteristic_categories_seq set sequence = 1;
alter table tbl_tiered_characteristic_categories_seq auto_increment = 2;

unlock tables;
/*!40000 alter table tbl_tiered_characteristic_categories_seq enable keys */;

#------------------------------------
select 'tbl_tiered_characteristic_categories_shadow';
#------------------------------------
insert into tbl_tiered_characteristic_categories_shadow (id, name) select id, name from tbl_tag_categories order by id;

#---------------------------------------------------------------------------------------------------------------------------------



#-----------------------------------
select 'tbl_tiered_characteristics';
#-----------------------------------
/*!40000 alter table tbl_tiered_characteristics disable keys */;
lock tables tbl_tiered_characteristics write;
#---------------------------------------------|----|-------------|----------------------|-----------|
# -- Columns --                               | id | category_id | value (100)          | parent_id |
#---------------------------------------------|----|-------------|----------------------|-----------|
insert into tbl_tiered_characteristics values (   1,            1, 'Finance',                      0);
insert into tbl_tiered_characteristics values (   2,            1, 'Telecoms',                     0);
insert into tbl_tiered_characteristics values (   3,            1, 'Technology',                   0);
insert into tbl_tiered_characteristics values (   4,            1, 'Mortgages',                    1);
insert into tbl_tiered_characteristics values (   5,            1, 'Retail banking',               1);
insert into tbl_tiered_characteristics values (   6,            1, 'Business banking',             1);
insert into tbl_tiered_characteristics values (   7,            1, 'Landline',                     2);
insert into tbl_tiered_characteristics values (   8,            1, 'Mobile',                       2);
insert into tbl_tiered_characteristics values (   9,            1, 'Hardware',                     3);
insert into tbl_tiered_characteristics values (  10,            1, 'Networking',                   3);
insert into tbl_tiered_characteristics values (  11,            1, 'Factoring',                    1);
#---------------------------------------------|----|-------------|----------------------|-----------|
unlock tables;
/*!40000 alter table tbl_tiered_characteristics enable keys */;

#------------------------------------
select 'tbl_tiered_characteristics_seq';
#------------------------------------
/*!40000 alter table tbl_tiered_characteristics_seq disable keys */;
lock tables tbl_tiered_characteristics_seq write;

update tbl_tiered_characteristics_seq set sequence = 10;
alter table tbl_tiered_characteristics_seq auto_increment = 11;

unlock tables;
/*!40000 alter table tbl_tiered_characteristics_seq enable keys */;

#------------------------------------
select 'tbl_tiered_characteristics_shadow';
#------------------------------------
insert into tbl_tiered_characteristics_shadow (id, category_id, value, parent_id) select id, category_id, value, parent_id from tbl_tiered_characteristics order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_company_tiered_characteristics';
#-----------------------------------
/*!40000 alter table tbl_company_tiered_characteristics disable keys */;
lock tables tbl_company_tiered_characteristics write;
#-----------------------------------------------------|----|-----------------|--------------------------|------|
# -- Columns --                                       | id | company_id      | tiered_characteristic_id | tier | 
#-----------------------------------------------------|----|-----------------|--------------------------|------|
insert into tbl_company_tiered_characteristics values (   1, 15,               1,                         1    );
insert into tbl_company_tiered_characteristics values (   2, 15,               5,                         2    );
insert into tbl_company_tiered_characteristics values (   3, 7516,             3,                         1    );
insert into tbl_company_tiered_characteristics values (   4, 7516,             9,                         2    );
insert into tbl_company_tiered_characteristics values (   5, 7516,             10,                        1    );
insert into tbl_company_tiered_characteristics values (   6, 15,               3,                         2    );
insert into tbl_company_tiered_characteristics values (   7, 15,               6,                         2    );
insert into tbl_company_tiered_characteristics values (   8, 15,               4,                         2    );
insert into tbl_company_tiered_characteristics values (   9, 15,               7,                         2    );
insert into tbl_company_tiered_characteristics values (  10, 15,               9,                         2    );
#-----------------------------------------------------|----|-----------------|--------------------------|------|
unlock tables;
/*!40000 alter table tbl_company_tiered_characteristics enable keys */;

#------------------------------------
select 'tbl_company_tiered_characteristics_seq';
#------------------------------------
/*!40000 alter table tbl_company_tiered_characteristics_seq disable keys */;
lock tables tbl_company_tiered_characteristics_seq write;

update tbl_company_tiered_characteristics_seq set sequence = 5;
alter table tbl_company_tiered_characteristics_seq auto_increment = 6;

unlock tables;
/*!40000 alter table tbl_company_tiered_characteristics_seq enable keys */;

#------------------------------------
select 'tbl_company_tiered_characteristics_shadow';
#------------------------------------
insert into tbl_company_tiered_characteristics_shadow (id, company_id, tiered_characteristic_id, tier) select id, company_id, tiered_characteristic_id, tier from tbl_company_tiered_characteristics order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#-----------------------------------
select 'tbl_lkp_counties';
#-----------------------------------
/*!40000 alter table tbl_lkp_counties disable keys */;
lock tables tbl_lkp_counties write;
#-----------------------------------|----|----------------------------------------------------|
# -- Columns --                     | id | name (50)                                          |
#-----------------------------------|----|----------------------------------------------------|
#--- England ---
insert into tbl_lkp_counties values (   1, 'Bedfordshire');
insert into tbl_lkp_counties values (   2, 'Berkshire');
insert into tbl_lkp_counties values (   3, 'Buckinghamshire');
insert into tbl_lkp_counties values (   4, 'Cambridgeshire');
insert into tbl_lkp_counties values (   5, 'Cheshire');
insert into tbl_lkp_counties values (   6, 'Cornwall');
insert into tbl_lkp_counties values (   7, 'Cumberland');
insert into tbl_lkp_counties values (   8, 'Derbyshire');
insert into tbl_lkp_counties values (   9, 'Devon');
insert into tbl_lkp_counties values (  10, 'Dorset');
insert into tbl_lkp_counties values (  11, 'Durham');
insert into tbl_lkp_counties values (  12, 'Essex');
insert into tbl_lkp_counties values (  13, 'Gloucestershire');
insert into tbl_lkp_counties values (  14, 'Hampshire');
insert into tbl_lkp_counties values (  15, 'Herefordshire');
insert into tbl_lkp_counties values (  16, 'Hertfordshire');
insert into tbl_lkp_counties values (  17, 'Huntingdonshire');
insert into tbl_lkp_counties values (  18, 'Kent');
insert into tbl_lkp_counties values (  19, 'Lancashire');
insert into tbl_lkp_counties values (  20, 'Leicestershire');
insert into tbl_lkp_counties values (  21, 'Lincolnshire');
insert into tbl_lkp_counties values (  22, 'Middlesex');
insert into tbl_lkp_counties values (  23, 'Norfolk');
insert into tbl_lkp_counties values (  24, 'Northamptonshire');
insert into tbl_lkp_counties values (  25, 'Northumberland');
insert into tbl_lkp_counties values (  26, 'Nottinghamshire');
insert into tbl_lkp_counties values (  27, 'Oxfordshire');
insert into tbl_lkp_counties values (  28, 'Rutland');
insert into tbl_lkp_counties values (  29, 'Shropshire');
insert into tbl_lkp_counties values (  30, 'Somerset');
insert into tbl_lkp_counties values (  31, 'Staffordshire');
insert into tbl_lkp_counties values (  32, 'Suffolk');
insert into tbl_lkp_counties values (  33, 'Surrey');
insert into tbl_lkp_counties values (  34, 'Sussex');
insert into tbl_lkp_counties values (  35, 'Warwickshire');
insert into tbl_lkp_counties values (  36, 'Westmorland');
insert into tbl_lkp_counties values (  37, 'Wiltshire');
insert into tbl_lkp_counties values (  38, 'Worcestershire');
insert into tbl_lkp_counties values (  39, 'Yorkshire');
#--- Scotland ---
insert into tbl_lkp_counties values (  40, 'Aberdeenshire');
insert into tbl_lkp_counties values (  41, 'Angus/Forfarshire');
insert into tbl_lkp_counties values (  42, 'Argyllshire');
insert into tbl_lkp_counties values (  43, 'Ayrshire');
insert into tbl_lkp_counties values (  44, 'Banffshire');
insert into tbl_lkp_counties values (  45, 'Berwickshire');
insert into tbl_lkp_counties values (  46, 'Buteshire');
insert into tbl_lkp_counties values (  47, 'Cromartyshire');
insert into tbl_lkp_counties values (  48, 'Caithness');
insert into tbl_lkp_counties values (  49, 'Clackmannanshire');
insert into tbl_lkp_counties values (  50, 'Dumfriesshire');
insert into tbl_lkp_counties values (  51, 'Dunbartonshire/Dumbartonshire');
insert into tbl_lkp_counties values (  52, 'East Lothian/Haddingtonshire');
insert into tbl_lkp_counties values (  53, 'Fife');
insert into tbl_lkp_counties values (  54, 'Inverness-shire');
insert into tbl_lkp_counties values (  55, 'Kincardineshire');
insert into tbl_lkp_counties values (  56, 'Kinross-shire');
insert into tbl_lkp_counties values (  57, 'Kirkcudbrightshire');
insert into tbl_lkp_counties values (  58, 'Lanarkshire');
insert into tbl_lkp_counties values (  59, 'Midlothian/Edinburghshire');
insert into tbl_lkp_counties values (  60, 'Morayshire');
insert into tbl_lkp_counties values (  61, 'Nairnshire');
insert into tbl_lkp_counties values (  62, 'Orkney');
insert into tbl_lkp_counties values (  63, 'Peeblesshire');
insert into tbl_lkp_counties values (  64, 'Perthshire');
insert into tbl_lkp_counties values (  65, 'Renfrewshire');
insert into tbl_lkp_counties values (  66, 'Ross-shire');
insert into tbl_lkp_counties values (  67, 'Roxburghshire');
insert into tbl_lkp_counties values (  68, 'Selkirkshire');
insert into tbl_lkp_counties values (  69, 'Shetland');
insert into tbl_lkp_counties values (  70, 'Stirlingshire');
insert into tbl_lkp_counties values (  71, 'Sutherland');
insert into tbl_lkp_counties values (  72, 'West Lothian/Linlithgowshire');
insert into tbl_lkp_counties values (  73, 'Wigtownshire');
#--- Wales ---
insert into tbl_lkp_counties values (  74, 'Anglesey');
insert into tbl_lkp_counties values (  75, 'Brecknockshire');
insert into tbl_lkp_counties values (  76, 'Caernarfonshire');
insert into tbl_lkp_counties values (  77, 'Carmarthenshire');
insert into tbl_lkp_counties values (  78, 'Cardiganshire');
insert into tbl_lkp_counties values (  79, 'Denbighshire');
insert into tbl_lkp_counties values (  80, 'Flintshire');
insert into tbl_lkp_counties values (  81, 'Glamorgan');
insert into tbl_lkp_counties values (  82, 'Merioneth');
insert into tbl_lkp_counties values (  83, 'Monmouthshire');
insert into tbl_lkp_counties values (  84, 'Montgomeryshire');
insert into tbl_lkp_counties values (  85, 'Pembrokeshire');
insert into tbl_lkp_counties values (  86, 'Radnorshire');
# --- Northern Ireland ---
insert into tbl_lkp_counties values (  87, 'Antrim');
insert into tbl_lkp_counties values (  88, 'Armagh');
insert into tbl_lkp_counties values (  89, 'Derry/Londonderry');
insert into tbl_lkp_counties values (  90, 'Down');
insert into tbl_lkp_counties values (  91, 'Fermanagh');
insert into tbl_lkp_counties values (  92, 'Tyrone');
#--- Ireland ---
insert into tbl_lkp_counties values (  93, 'Dublin');
insert into tbl_lkp_counties values (  94, 'Cavan');
insert into tbl_lkp_counties values (  95, 'Kilkenny');
insert into tbl_lkp_counties values (  96, 'Kildare');
insert into tbl_lkp_counties values (  97, 'Carlow');
insert into tbl_lkp_counties values (  98, 'Kerry');
insert into tbl_lkp_counties values (  99, 'Clare');
insert into tbl_lkp_counties values ( 101, 'Wicklow');
insert into tbl_lkp_counties values ( 102, 'Cork');
insert into tbl_lkp_counties values ( 103, 'Donegal');
insert into tbl_lkp_counties values ( 104, 'Galway');
insert into tbl_lkp_counties values ( 105, 'Westmeath');
insert into tbl_lkp_counties values ( 106, 'Leix');
insert into tbl_lkp_counties values ( 107, 'Wexford');
insert into tbl_lkp_counties values ( 108, 'Leitrim');
insert into tbl_lkp_counties values ( 109, 'Limerick');
insert into tbl_lkp_counties values ( 110, 'Longford');
insert into tbl_lkp_counties values ( 111, 'Louth');
insert into tbl_lkp_counties values ( 112, 'Mayo');
insert into tbl_lkp_counties values ( 113, 'Meath');
insert into tbl_lkp_counties values ( 114, 'Monaghan');
insert into tbl_lkp_counties values ( 115, 'Waterford');
insert into tbl_lkp_counties values ( 116, 'Roscommon');
insert into tbl_lkp_counties values ( 117, 'Sligo');
insert into tbl_lkp_counties values ( 118, 'Tipperary');
insert into tbl_lkp_counties values ( 119, 'Offaly');
#-----------------------------------|----|----------------------------------------------------|
unlock tables;
/*!40000 alter table tbl_lkp_counties enable keys */;

#------------------------------------
select 'tbl_lkp_counties_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_counties_seq disable keys */;
lock tables tbl_lkp_counties_seq write;

update tbl_lkp_counties_seq set sequence = 119;

unlock tables;
/*!40000 alter table tbl_lkp_counties_seq enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_lkp_countries';
#-----------------------------------
/*!40000 alter table tbl_lkp_countries disable keys */;
lock tables tbl_lkp_countries write;
#------------------------------------|----|----------------------------------------------------|
# -- Columns --                      | id | name (50)                                          |
#------------------------------------|----|----------------------------------------------------|
insert into tbl_lkp_countries values (   1, 'England');
insert into tbl_lkp_countries values (   2, 'Scotland');
insert into tbl_lkp_countries values (   3, 'Wales');
insert into tbl_lkp_countries values (   4, 'Northern Ireland');
insert into tbl_lkp_countries values (   5, 'Ireland');
#------------------------------------|----|----------------------------------------------------|
unlock tables;
/*!40000 alter table tbl_lkp_countries enable keys */;

#------------------------------------
select 'tbl_lkp_countries_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_countries_seq disable keys */;
lock tables tbl_lkp_countries_seq write;

update tbl_lkp_countries_seq set sequence = 5;

unlock tables;
/*!40000 alter table tbl_lkp_countries_seq enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_lkp_communication_types';
#-----------------------------------
/*!40000 alter table tbl_lkp_communication_types disable keys */;
lock tables tbl_lkp_communication_types write;
#----------------------------------------------|----|-----------|-------------------|
# -- Columns --                                | id | type (50) | description (100) |
#----------------------------------------------|----|-----------|-------------------|
insert into tbl_lkp_communication_types values (   1, 'user',     'telephone'       );
insert into tbl_lkp_communication_types values (   2, 'system',   'meeting'         );
insert into tbl_lkp_communication_types values (   3, 'system',   'request'         );
insert into tbl_lkp_communication_types values (   4, 'system',   'data edit'       );
#----------------------------------------------|----|-----------|-------------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_types enable keys */;

#------------------------------------
select 'tbl_lkp_communication_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_types_seq disable keys */;
lock tables tbl_lkp_communication_types_seq write;

update tbl_lkp_communication_types_seq set sequence = 4;
alter table tbl_lkp_communication_types_seq auto_increment = 5;

unlock tables;
/*!40000 alter table tbl_lkp_communication_types_seq enable keys */;

#------------------------------------
select 'tbl_lkp_communication_types_shadow';
#------------------------------------
#insert into tbl_lkp_communication_types_shadow (id, type, description) select id, type, description from tbl_lkp_communication_types;

#---------------------------------------------------------------------------------------------------------------------------------


#---------------------------------------
select 'tbl_lkp_communication_targeting';
#---------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting disable keys */;
lock tables tbl_lkp_communication_targeting write;
#--------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                    | id | description (50) | status_score | sort_order |
#--------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_targeting values (   1, 'Perfect',         3,             0          );
insert into tbl_lkp_communication_targeting values (   2, '80% plus',        2,             1          );
insert into tbl_lkp_communication_targeting values (   3, '50% to 80%',      1,             2          );
insert into tbl_lkp_communication_targeting values (   4, 'less than 50%',   0,             3          );
#--------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting enable keys */;

#------------------------------------
select 'tbl_lkp_communication_targeting_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_targeting_seq disable keys */;
lock tables tbl_lkp_communication_targeting_seq write;

update tbl_lkp_communication_targeting_seq set sequence = 4;

unlock tables;
/*!40000 alter table tbl_lkp_communication_targeting_seq enable keys */;

#------------------------------------
select 'tbl_lkp_communication_targeting_shadow';
#------------------------------------
#insert into tbl_lkp_communication_targeting_shadow (id, description, status_score, sort_order) select id, description, status_score, sort_order from tbl_lkp_communication_targeting order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#------------------------------------------
select 'tbl_lkp_communication_receptiveness';
#------------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness disable keys */;
lock tables tbl_lkp_communication_receptiveness write;
#------------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                        | id | description (50) | status_score | sort_order |
#------------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_communication_receptiveness values (   1, 'V. receptive',    6,             0          );
insert into tbl_lkp_communication_receptiveness values (   2, 'Receptive',       4,             1          );
insert into tbl_lkp_communication_receptiveness values (   3, 'Tepid',           2,             2          );
insert into tbl_lkp_communication_receptiveness values (   4, 'Not receptive',   0,             3          );
#------------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness enable keys */;

#------------------------------------
select 'tbl_lkp_communication_receptiveness_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_receptiveness_seq disable keys */;
lock tables tbl_lkp_communication_receptiveness_seq write;

update tbl_lkp_communication_receptiveness_seq set sequence = 4;

unlock tables;
/*!40000 alter table tbl_lkp_communication_receptiveness_seq enable keys */;

#------------------------------------
select 'tbl_lkp_communication_receptiveness_shadow';
#------------------------------------
#insert into tbl_lkp_communication_receptiveness_shadow (id, description, status_score, sort_order) select id, description, status_score, sort_order from tbl_lkp_communication_receptiveness order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_next_communication_reasons';
#------------------------------------------
/*!40000 alter table tbl_lkp_next_communication_reasons disable keys */;
lock tables tbl_lkp_next_communication_reasons write;
#-----------------------------------------------------|----|------------------|--------------|------------|
# -- Columns --                                       | id | description (50) | status_score | sort_order |
#-----------------------------------------------------|----|------------------|--------------|------------|
insert into tbl_lkp_next_communication_reasons values (   1, 'Pitch/Brief',     8,             0          );
insert into tbl_lkp_next_communication_reasons values (   2, 'Review',          6,             1          );
insert into tbl_lkp_next_communication_reasons values (   3, 'Activity',        4,             2          );
insert into tbl_lkp_next_communication_reasons values (   4, 'KIT',             0,             3          );
#-----------------------------------------------------|----|------------------|--------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_next_communication_reasons enable keys */;

#------------------------------------
select 'tbl_lkp_next_communication_reasons_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_next_communication_reasons_seq disable keys */;
lock tables tbl_lkp_next_communication_reasons_seq write;

update tbl_lkp_next_communication_reasons_seq set sequence = 4;
alter table tbl_lkp_next_communication_reasons_seq auto_increment = 5;

unlock tables;
/*!40000 alter table tbl_lkp_next_communication_reasons_seq enable keys */;

#------------------------------------
select 'tbl_lkp_next_communication_reasons_shadow';
#------------------------------------
#insert into tbl_lkp_next_communication_reasons_shadow (id, description, status_score, sort_order) select id, description, status_score, sort_order from tbl_lkp_next_communication_reasons order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#-----------------------------------
select 'tbl_lkp_communication_status';
#-----------------------------------
/*!40000 alter table tbl_lkp_communication_status disable keys */;
lock tables tbl_lkp_communication_status write;
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
# -- Columns --                                 | id | lower_value | upper_value | description (50)                      | is_auto_calculate | show_auto_calculate_options | sort_order |
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
insert into tbl_lkp_communication_status values (   1, 0,            13,           'Dormant',  		                       1,                  1,                            1          );
insert into tbl_lkp_communication_status values (   2, 22,           23,           'Receptive long term',                1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   3, 22,           23,           'Receptive medium term',                  1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   4, 24,           25,           'Very receptive medium term',             1,                  1,                            3          );
insert into tbl_lkp_communication_status values (   5, 24,           25,           'Very receptive near term',           1,                  1,                            3          );
insert into tbl_lkp_communication_status values (   6, 26,           63,           'Hot',                                  1,                  1,                            4          );

insert into tbl_lkp_communication_status values (   7, 64  ,         64,           'Fresh lead',                           0,                  1,                            5          );
insert into tbl_lkp_communication_status values (   8, 128,          128,          'Do not call',                          0,                  1,                            6          );
insert into tbl_lkp_communication_status values (   9, 256,          256,          'Not worthwhile prospect',              0,                  1,                            8          );
insert into tbl_lkp_communication_status values (   10, 512,          512,          'Not worthwhile company',              0,                  1,                            9          );
insert into tbl_lkp_communication_status values (   11, 750,          750,          'Referred to new DM',                  0,                  1,                            9          );

insert into tbl_lkp_communication_status values (  12, 1000,         1000,         'Meeting set',                          0,                  0,                            10         );
insert into tbl_lkp_communication_status values (  13, 2000,         2000,         'Follow-up meeting set',                0,                  0,                            11         );

insert into tbl_lkp_communication_status values (  14, 3000,         3000,         'Meeting to be rearranged: client',     0,                  0,                            12         );
insert into tbl_lkp_communication_status values (  15, 4000,         4000,         'Follow-up meeting to be rearranged: client',   0,                  0,                            13         );

insert into tbl_lkp_communication_status values (  16, 4500,         4500,         'Meeting to be rearranged: Alchemis',     0,                  0,                            12         );
insert into tbl_lkp_communication_status values (  17, 5000,         5000,         'Follow-up meeting to be rearranged: Alchemis',   0,                  0,                            13         );

insert into tbl_lkp_communication_status values (  18, 6000,         6000,         'Meeting rearranged',                   0,                  0,                            14         );
insert into tbl_lkp_communication_status values (  19, 7000,         7000,         'Follow-up meeting rearranged',         0,                  0,                            15         );

insert into tbl_lkp_communication_status values (  20, 8000,         8000,         'Meeting cancelled: prospect',          0,                  0,                            16         );
insert into tbl_lkp_communication_status values (  21, 9000,         9000,         'Follow-up meeting cancelled: prospect',0,                  0,                            17         );

insert into tbl_lkp_communication_status values (  22, 10000,        10000,         'Meeting cancelled: client',            0,                  0,                            16         );
insert into tbl_lkp_communication_status values (  23, 11000,        11000,         'Follow-up meeting cancelled: client',  0,                  0,                            17         );

insert into tbl_lkp_communication_status values (  24, 12000,        12000,         'Meeting attended: client',             0,                  0,                            18         );
insert into tbl_lkp_communication_status values (  25, 13000,        13000,        'Follow-up meeting attended: client',   0,                  0,                            19         );

insert into tbl_lkp_communication_status values (  26, 14000,        14000,        'Meeting attended: Alchemis',           0,                  0,                            20         );
insert into tbl_lkp_communication_status values (  27, 15000,        15000,        'Follow-up meeting attended: Alchemis', 0,                  0,                            21         );

insert into tbl_lkp_communication_status values (  28, 16000,        16000,        'Brief received',                       0,                  0,                            22         );
insert into tbl_lkp_communication_status values (  29, 17000,        17000,        'Proposal',                             0,                  0,                            22         );
insert into tbl_lkp_communication_status values (  30, 18000,        18000,        'Win',                                  0,                  1,                            23         );
insert into tbl_lkp_communication_status values (  31, 19000,        19000,        'Gone cold',                            0,                  1,                            24         );
insert into tbl_lkp_communication_status values (  32, 20000,        20000,        'Follow-up meeting to be arranged',     0,                  0,                            25         );
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_seq disable keys */;
lock tables tbl_lkp_communication_status_seq write;

update tbl_lkp_communication_status_seq set sequence = 32;
alter table tbl_lkp_communication_status_seq auto_increment = 33;

unlock tables;
/*!40000 alter table tbl_lkp_communication_status_seq enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_shadow';
#------------------------------------
#insert into tbl_lkp_communication_status_shadow (id, lower_value, upper_value, description, is_auto_calculate, show_auto_calculate_options, sort_order) select id, lower_value, upper_value, description, is_auto_calculate, show_auto_calculate_options, sort_order from tbl_lkp_communication_status order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_lkp_communication_status_rules';
#-----------------------------------
/*!40000 alter table tbl_lkp_communication_status_rules disable keys */;
lock tables tbl_lkp_communication_status_rules write;
#-----------------------------------------------------|------|-------------|-----------------|-------------|
# -- Columns --                                       | id   | status_id   | child_status_id | sort_order  |
#-----------------------------------------------------|------|-------------|-----------------|-------------|
#Fresh lead (7)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (   1,  7,            7,                   1);
insert into tbl_lkp_communication_status_rules values (   2,  7,            8,                   2);
insert into tbl_lkp_communication_status_rules values (   3,  7,            9,                   3);
insert into tbl_lkp_communication_status_rules values (   4,  7,            10,                   4);
insert into tbl_lkp_communication_status_rules values (   5,  7,            12,                   5);

#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (   6,  7,            1,                   6);
insert into tbl_lkp_communication_status_rules values (   7,  7,            2,                   7);
insert into tbl_lkp_communication_status_rules values (   8,  7,            3,                   8);
insert into tbl_lkp_communication_status_rules values (   9,  7,            4,                   9);
insert into tbl_lkp_communication_status_rules values (   10,  7,            5,                   10);
insert into tbl_lkp_communication_status_rules values (   11,  7,            6,                   11);

#Do not call (8)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  12,  8,            8,                   1);
insert into tbl_lkp_communication_status_rules values (  13,  8,            7,                   2); 
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  14,  8,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  15,  8,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  16,  8,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  17,  8,            4,                   9);
insert into tbl_lkp_communication_status_rules values (   18,  8,            5,                   10);
insert into tbl_lkp_communication_status_rules values (   19,  8,            6,                   11);

#Not worthwhile prospect (9)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  20,  9,            9,                   1);
insert into tbl_lkp_communication_status_rules values (  21,  9,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  22,  9,            11,                   3);
insert into tbl_lkp_communication_status_rules values (  23,  9,            12,                  4);
insert into tbl_lkp_communication_status_rules values (  24,  9,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  25,  9,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  26,  9,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  27,  9,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  28,  9,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  29,  9,            4,                   10);
insert into tbl_lkp_communication_status_rules values (   30,  9,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   31,  9,            6,                   12);

#Not worthwhile company (10)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  32,  10,            10,                 1);
insert into tbl_lkp_communication_status_rules values (  33,  10,            9,                  2);
insert into tbl_lkp_communication_status_rules values (  34,  10,            11,                 3);
insert into tbl_lkp_communication_status_rules values (  35,  10,            12,                   4);
insert into tbl_lkp_communication_status_rules values (  36,  10,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  37,  10,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  38,  10,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  39,  10,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  40,  10,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  41,  10,            4,                   10);
insert into tbl_lkp_communication_status_rules values (   42,  10,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   43,  10,            6,                   12);

#Referred to new decision maker(11)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  44,  11,            11,                   1);
insert into tbl_lkp_communication_status_rules values (  45,  11,            9,                  2);
insert into tbl_lkp_communication_status_rules values (  46,  11,            10,                  3);
insert into tbl_lkp_communication_status_rules values (  47,  11,            12,                   4);
insert into tbl_lkp_communication_status_rules values (  48,  11,            7,                   5);
insert into tbl_lkp_communication_status_rules values (  49,  11,            8,                   6);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  50,  11,            1,                   7);
insert into tbl_lkp_communication_status_rules values (  51,  11,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  52,  11,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  53,  11,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  54,  11,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   55,  11,            6,                   12);

#Dormant (1)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  56,  1,            1,                   1);
insert into tbl_lkp_communication_status_rules values (  57,  1,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  58,  1,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  59,  1,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  60,  1,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  61,  1,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  62,  1,            12,                  7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  63,  1,            2,                   8);
insert into tbl_lkp_communication_status_rules values (  64,  1,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  65,  1,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  66,  1,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  67,  1,            6,                   12);

#Receptive long term (2)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  68,  2,            2,                   1);
insert into tbl_lkp_communication_status_rules values (  69,  2,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  70,  2,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  71,  2,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  72,  2,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  73,  2,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  74,  2,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  75,  2,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  76,  2,            3,                   9);
insert into tbl_lkp_communication_status_rules values (  77,  2,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  78,  2,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  79,  2,            6,                   12);

#Receptive medium term (3)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  80,  3,            3,                   1);
insert into tbl_lkp_communication_status_rules values (  81,  3,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  82,  3,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  83,  3,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  84,  3,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  85,  3,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  86,  3,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  87,  3,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  88,  3,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  89,  3,            4,                   10);
insert into tbl_lkp_communication_status_rules values (  90,  3,            5,                   11);
insert into tbl_lkp_communication_status_rules values (  91,  3,            6,                   12);

#Very receptive medium term (4)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  92,  4,            4,                   1);
insert into tbl_lkp_communication_status_rules values (  93,  4,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  94,  4,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  95,  4,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  96,  4,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  97,  4,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  98,  4,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  99,  4,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  100,  4,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  101,  4,            3,                   10);
insert into tbl_lkp_communication_status_rules values (   102  4,            5,                   11);
insert into tbl_lkp_communication_status_rules values (   103,  4,            6,                   12);

#Very receptive near term (5)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  104,  5,            5,                   1);
insert into tbl_lkp_communication_status_rules values (  105,  5,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  106,  5,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  107,  5,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  108,  5,            10,                   5);
insert into tbl_lkp_communication_status_rules values (  109,  5,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  110,  5,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  111,  5,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  112,  5,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  113,  5,            3,                   10);
insert into tbl_lkp_communication_status_rules values (  114,  5,            4,                   11);
insert into tbl_lkp_communication_status_rules values (  115,  5,            6,                   12);

#Hot (6)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  116,  6,            6,                   1);
insert into tbl_lkp_communication_status_rules values (  117,  6,            7,                   2);
insert into tbl_lkp_communication_status_rules values (  118,  6,            8,                   3);
insert into tbl_lkp_communication_status_rules values (  119,  6,            9,                   4);
insert into tbl_lkp_communication_status_rules values (  120,  6,            10,                  5);
insert into tbl_lkp_communication_status_rules values (  121,  6,            11,                   6);
insert into tbl_lkp_communication_status_rules values (  122,  6,            12,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  123,  6,            1,                   8);
insert into tbl_lkp_communication_status_rules values (  124,  6,            2,                   9);
insert into tbl_lkp_communication_status_rules values (  125,  6,            3,                   10);
insert into tbl_lkp_communication_status_rules values (  126,  6,            4,                   11);
insert into tbl_lkp_communication_status_rules values (  127,  6,            5,                   12);

#Meeting set (12)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  128,  12,           12,                  1);
insert into tbl_lkp_communication_status_rules values (  129,  12,           14,                  2);
insert into tbl_lkp_communication_status_rules values (  130,  12,           16,                  3);
insert into tbl_lkp_communication_status_rules values (  131,  12,           18,                  4);
insert into tbl_lkp_communication_status_rules values (  132,  12,           20,                  5);
insert into tbl_lkp_communication_status_rules values (  133,  12,           22,                  6);
insert into tbl_lkp_communication_status_rules values (  134,  12,           24,                  7);
insert into tbl_lkp_communication_status_rules values (  135,  12,           26,                  8);

#Follow-up meeting set (13)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  137,  13,           13,                  1);
insert into tbl_lkp_communication_status_rules values (  138,  13,           15,                  2);
insert into tbl_lkp_communication_status_rules values (  139,  13,           17,                  3);
insert into tbl_lkp_communication_status_rules values (  140,  13,           19,                  4);
insert into tbl_lkp_communication_status_rules values (  141,  13,           21,                  5);
insert into tbl_lkp_communication_status_rules values (  142,  13,           23,                  6);
insert into tbl_lkp_communication_status_rules values (  143,  13,           25,                  7);
insert into tbl_lkp_communication_status_rules values (  144,  13,           27,                  8);

#Meeting to be rearranged: client (14)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  145,  14,           14,                  1);
insert into tbl_lkp_communication_status_rules values (  146,  14,           18,                  2);
insert into tbl_lkp_communication_status_rules values (  147,  14,           20,                  3);
insert into tbl_lkp_communication_status_rules values (  148,  14,           22,                  4);

#Follow-up meeting to be rearranged: client (15)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  149,  15,           15,                  1);
insert into tbl_lkp_communication_status_rules values (  150,  15,           19,                  2);
insert into tbl_lkp_communication_status_rules values (  151,  15,           21,                  3);
insert into tbl_lkp_communication_status_rules values (  152,  15,           23,                  4);

#Meeting to be rearranged: Alchemis (16)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  153,  16,           16,                  1);
insert into tbl_lkp_communication_status_rules values (  154,  16,           18,                  2);
insert into tbl_lkp_communication_status_rules values (  155,  16,           20,                  3);
insert into tbl_lkp_communication_status_rules values (  156,  16,           22,                  4);

#Follow-up meeting to be rearranged: Alchemis (17)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  157,  17,           17,                  1);
insert into tbl_lkp_communication_status_rules values (  158,  17,           19,                  2);
insert into tbl_lkp_communication_status_rules values (  159,  17,           21,                  3);
insert into tbl_lkp_communication_status_rules values (  160,  17,           23,                  4);

#Meeting rearranged (18)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  161,  18,           18,                  1);
insert into tbl_lkp_communication_status_rules values ( 162,  18,           20,                  2);
insert into tbl_lkp_communication_status_rules values ( 163,  18,           22,                  3);
insert into tbl_lkp_communication_status_rules values ( 164,  18,           24,                  4);
insert into tbl_lkp_communication_status_rules values ( 165,  18,           26,                  5);

#Follow-up meeting rearranged (19)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 166,  19,           19,                  1);
insert into tbl_lkp_communication_status_rules values ( 167,  19,           21,                  2);
insert into tbl_lkp_communication_status_rules values ( 168,  19,           23,                  3);
insert into tbl_lkp_communication_status_rules values ( 169,  19,           25,                  4);
insert into tbl_lkp_communication_status_rules values ( 170,  19,           27,                  5);

#Meeting cancelled: prospect (20)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 171,  20,           20,                  1);
insert into tbl_lkp_communication_status_rules values ( 172,  20,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 173,  20,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 174,  20,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 175,  20,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 176,  20,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 177,  20,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 178,  20,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 179,  20,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 180,  20,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 181,  20,            6,              11);

#Follow-up meeting cancelled: prospect (21)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 182,  21,           21,                  1);
insert into tbl_lkp_communication_status_rules values ( 183,  21,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 184,  21,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 185,  21,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 186,  21,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 187,  21,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 188,  21,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 189,  21,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 190,  21,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 191,  21,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 192,  21,            6,               11);

#Meeting cancelled: client (22)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 193,  22,           22,                  1);
insert into tbl_lkp_communication_status_rules values ( 194,  22,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 195,  22,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 196,  22,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 197,  22,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 198,  22,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 199,  22,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 200,  22,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 201,  22,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 202,  22,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 203,  22,            6,               11);

#Follow-up meeting cancelled: client (23)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 204,  23,           21,                  1);
insert into tbl_lkp_communication_status_rules values ( 205,  23,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 206,  23,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 207,  23,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 208,  23,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 209,  23,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 210,  23,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 211,  23,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 212,  23,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 213,  23,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 214,  23,            6,              11);

#Meeting attended: client (24)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 215,  24,           24,                  1);
insert into tbl_lkp_communication_status_rules values ( 216,  24,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 217,  24,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 218,  24,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 219,  24,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 220,  24,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 221,  24,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 223,  24,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 224,  24,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 225,  24,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 226,  24,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 227,  24,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 228,  24,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 229,  24,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 230,  24,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 231,  24,           32,                 16);

#Follow-up meeting attended: client (25)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 232,  25,           25,                  1);
insert into tbl_lkp_communication_status_rules values ( 233,  25,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 234,  25,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 235,  25,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 236,  25,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 237,  25,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 238,  25,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 239,  25,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 240,  25,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 241,  25,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 242,  25,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 243,  25,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 244,  25,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 245,  25,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 246,  25,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 247,  25,           32,                 16);


#Meeting attended: Alchemis (26)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 248,  26,           26,                  1);
insert into tbl_lkp_communication_status_rules values ( 249,  26,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 250,  26,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 251,  26,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 252,  26,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 253,  26,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 254,  26,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 255,  26,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 256,  26,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 257,  26,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 258,  26,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 259,  26,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 260,  26,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 261,  26,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 262,  26,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 263,  26,           32,                 16);

#Follow-up meeting attended: Alchemis (27)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 264,  27,           27,                  1);
insert into tbl_lkp_communication_status_rules values ( 265,  27,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 266,  27,           9,                   3);
insert into tbl_lkp_communication_status_rules values ( 267,  27,           10,                   4);
insert into tbl_lkp_communication_status_rules values ( 268,  27,           11,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 269,  27,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 270,  27,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 271,  27,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 272,  27,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 273,  27,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 274,  27,            6,               11);
insert into tbl_lkp_communication_status_rules values ( 275,  27,           28,              12);
insert into tbl_lkp_communication_status_rules values ( 276,  27,           29,                 13);
insert into tbl_lkp_communication_status_rules values ( 277,  27,           30,                 14);
insert into tbl_lkp_communication_status_rules values ( 278,  27,           31,                 15);
insert into tbl_lkp_communication_status_rules values ( 279,  27,           32,                 16);

#Brief received (28)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 280,  28,           28,                 1);
insert into tbl_lkp_communication_status_rules values ( 281,  28,           13,                 2);
insert into tbl_lkp_communication_status_rules values ( 282,  28,           29,                 3);
insert into tbl_lkp_communication_status_rules values ( 283,  28,           30,                 4);
insert into tbl_lkp_communication_status_rules values ( 284,  28,           31,                 5);
insert into tbl_lkp_communication_status_rules values ( 285,  28,           32,                 6);

#Proposal (29)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 286,  29,           29,              1);
insert into tbl_lkp_communication_status_rules values ( 287,  29,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 288,  29,           11,                   3);
insert into tbl_lkp_communication_status_rules values ( 289,  29,           13,                   4);
insert into tbl_lkp_communication_status_rules values ( 290,  29,           30,                   5);
insert into tbl_lkp_communication_status_rules values ( 291,  29,           31,                   6);
insert into tbl_lkp_communication_status_rules values ( 292,  29,           32,                   7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 293,  29,            1,               8);
insert into tbl_lkp_communication_status_rules values ( 294,  29,            2,               9);
insert into tbl_lkp_communication_status_rules values ( 295,  29,            3,               10);
insert into tbl_lkp_communication_status_rules values ( 296,  29,            4,               11);
insert into tbl_lkp_communication_status_rules values ( 297,  29,            5,               12);
insert into tbl_lkp_communication_status_rules values ( 298,  29,            6,               13);

#Win (30)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 299,  30,           30,              1);
insert into tbl_lkp_communication_status_rules values ( 300,  30,           8,                   2);
insert into tbl_lkp_communication_status_rules values ( 301,  30,           11,                   3);
insert into tbl_lkp_communication_status_rules values ( 302,  30,           13,                   4);
insert into tbl_lkp_communication_status_rules values ( 303,  30,           32,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 304,  30,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 305,  30,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 306,  30,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 307,  30,            4,               9);
insert into tbl_lkp_communication_status_rules values ( 308,  30,            5,               10);
insert into tbl_lkp_communication_status_rules values ( 309,  30,            6,               11);

#Gone cold (31)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 310,  31,           31,                 1);
insert into tbl_lkp_communication_status_rules values ( 311,  31,           8,               2);
insert into tbl_lkp_communication_status_rules values ( 312,  31,           9,               3);
insert into tbl_lkp_communication_status_rules values ( 313,  31,           10,               4);
insert into tbl_lkp_communication_status_rules values ( 314,  31,           11,                   5);
insert into tbl_lkp_communication_status_rules values ( 315,  31,           13,                   6);
insert into tbl_lkp_communication_status_rules values ( 316,  31,           28,                   7);
insert into tbl_lkp_communication_status_rules values ( 317,  31,           29,                   8);
insert into tbl_lkp_communication_status_rules values ( 318,  31,           30,                   9);
insert into tbl_lkp_communication_status_rules values ( 319,  31,           32,                   10);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 320,  31,            1,               11);
insert into tbl_lkp_communication_status_rules values ( 321,  31,            2,               12);
insert into tbl_lkp_communication_status_rules values ( 322,  31,            3,               13);
insert into tbl_lkp_communication_status_rules values ( 323,  31,            4,               14);
insert into tbl_lkp_communication_status_rules values ( 324,  31,            5,               15);
insert into tbl_lkp_communication_status_rules values ( 325,  31,            6,               16);

#Follow-up meeting to be arranged (32)
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 326,  32,           32,              1);
insert into tbl_lkp_communication_status_rules values ( 327,  32,           31,              2);
insert into tbl_lkp_communication_status_rules values ( 328,  32,           8,               3);
insert into tbl_lkp_communication_status_rules values ( 329,  32,           9,               4);
insert into tbl_lkp_communication_status_rules values ( 330,  32,           10,               5);
insert into tbl_lkp_communication_status_rules values ( 331,  32,           11,                6);
insert into tbl_lkp_communication_status_rules values ( 332,  32,           13,                 7);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 333,  32,            1,               8);
insert into tbl_lkp_communication_status_rules values ( 334,  32,            2,               9);
insert into tbl_lkp_communication_status_rules values ( 335,  32,            3,               10);
insert into tbl_lkp_communication_status_rules values ( 336,  32,            4,               11);
insert into tbl_lkp_communication_status_rules values ( 337,  32,            5,               12);
insert into tbl_lkp_communication_status_rules values ( 338,  32,            6,               13);

#-------------------------------------------------------|------|-------------|-------------|-----------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status_rules enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_rules_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_rules_seq disable keys */;
lock tables tbl_lkp_communication_status_rules_seq write;

update tbl_lkp_communication_status_rules_seq set sequence = 338;
alter table tbl_lkp_communication_status_rules_seq auto_increment = 339;

unlock tables;
/*!40000 alter table tbl_lkp_communication_status_rules_seq enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_rules_shadow';
#------------------------------------
#insert into tbl_lkp_communication_status_rules_shadow (id, status_id, child_status_id, sort_order) select id, status_id, child_status_id, sort_order from tbl_lkp_communication_status_rules order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#------------------------------------------
select 'tbl_lkp_meeting_status';
#------------------------------------------
/*!40000 alter table tbl_lkp_meeting_status disable keys */;
lock tables tbl_lkp_meeting_status write;
#-----------------------------------------|----|------------------------------|------------|
# -- Columns --                           | id | description (50)             | sort_order |
#-----------------------------------------|----|------------------------------|------------|
insert into tbl_lkp_meeting_status values (   1, 'Meeting set',                           0);
insert into tbl_lkp_meeting_status values (   2, 'Meeting to be rearranged',              1);
insert into tbl_lkp_meeting_status values (   3, 'Meeting rearranged',                    2);
#-----------------------------------------|----|------------------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_meeting_status enable keys */;

#------------------------------------
select 'tbl_lkp_meeting_status_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_meeting_status_seq disable keys */;
lock tables tbl_lkp_meeting_status_seq write;

update tbl_lkp_meeting_status_seq set sequence = 3;
alter table tbl_lkp_meeting_status_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_meeting_status_seq enable keys */;

#------------------------------------
select 'tbl_lkp_meeting_status_shadow';
#------------------------------------
#insert into tbl_lkp_meeting_status_shadow (id, description, sort_order) select id, description, sort_order from tbl_lkp_meeting_status order by id;

#---------------------------------------------------------------------------------------------------------------------------------


#------------------------------------------
select 'tbl_lkp_meeting_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_meeting_types disable keys */;
lock tables tbl_lkp_meeting_types write;
#----------------------------------------|----|------------------|------------|
# -- Columns --                          | id | description (50) | sort_order |
#----------------------------------------|----|------------------|------------|
insert into tbl_lkp_meeting_types values (   1, 'initial',                   0);
insert into tbl_lkp_meeting_types values (   2, 'follow-up',                 1);
#----------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_meeting_types enable keys */;

#------------------------------------
select 'tbl_lkp_meeting_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_meeting_types_seq disable keys */;
lock tables tbl_lkp_meeting_types_seq write;

update tbl_lkp_meeting_types_seq set sequence = 2;
alter table tbl_lkp_meeting_types_seq auto_increment = 3;

unlock tables;
/*!40000 alter table tbl_lkp_meeting_types_seq enable keys */;

#------------------------------------
select 'tbl_lkp_meeting_types_shadow';
#------------------------------------
#insert into tbl_lkp_meeting_types_shadow (id, description, sort_order) select id, description, sort_order from tbl_lkp_meeting_types order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_information_request_status';
#------------------------------------------
/*!40000 alter table tbl_lkp_information_request_status disable keys */;
lock tables tbl_lkp_information_request_status write;
#------------------------------------------------------|----|------------------|------------|
# -- Columns --                                        | id | description (50) | sort_order |
#------------------------------------------------------|----|------------------|------------|
insert into tbl_lkp_information_request_status values (   1, 'current',                    0);
insert into tbl_lkp_information_request_status values (   2, 'fulfilled',                  1);
insert into tbl_lkp_information_request_status values (   3, 'cancelled',                  2);
#------------------------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_information_request_status enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_status_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_information_request_status_seq disable keys */;
lock tables tbl_lkp_information_request_status_seq write;

update tbl_lkp_information_request_status_seq set sequence = 3;
alter table tbl_lkp_information_request_status_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_information_request_status_seq enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_status_shadow';
#------------------------------------
insert into tbl_lkp_information_request_status_shadow (id, description, sort_order) select id, description, sort_order from tbl_lkp_information_request_status order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_information_request_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_information_request_types disable keys */;
lock tables tbl_lkp_information_request_types write;
#------------------------------------------------------|----|----------------------------|------------|
# -- Columns --                                        | id | description (50)           | sort_order |
#------------------------------------------------------|----|----------------------------|------------|
insert into tbl_lkp_information_request_types values (   1, 'Specific Letter',             0          );
insert into tbl_lkp_information_request_types values (   2, 'Enclose Brochure',            1          );
insert into tbl_lkp_information_request_types values (   3, 'Work Examples - see notes',   2          );
insert into tbl_lkp_information_request_types values (   4, 'Not Applicable',              3          );
insert into tbl_lkp_information_request_types values (   5, 'Web Site Details',            4          );
#------------------------------------------------------|----|----------------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_information_request_types enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_information_request_types_seq disable keys */;
lock tables tbl_lkp_information_request_types_seq write;

update tbl_lkp_information_request_types_seq set sequence = 5;
alter table tbl_lkp_information_request_types_seq auto_increment = 6;

unlock tables;
/*!40000 alter table tbl_lkp_information_request_types_seq enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_types_shadow';
#------------------------------------
#insert into tbl_lkp_information_request_types_shadow (id, description, sort_order) select id, description, sort_order from tbl_lkp_information_request_types order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_information_request_comm_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_information_request_comm_types disable keys */;
lock tables tbl_lkp_information_request_comm_types write;
#---------------------------------------------------------|----|------------------|------------|
# -- Columns --                                           | id | description (50) | sort_order |
#---------------------------------------------------------|----|------------------|------------|
insert into tbl_lkp_information_request_comm_types values (   1, 'E-mail',                    0);
insert into tbl_lkp_information_request_comm_types values (   2, 'Post',                      1);
insert into tbl_lkp_information_request_comm_types values (   3, 'Fax',                       2);
#---------------------------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_information_request_comm_types enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_comm_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_information_request_comm_types_seq disable keys */;
lock tables tbl_lkp_information_request_comm_types_seq write;

update tbl_lkp_information_request_comm_types_seq set sequence = 3;
alter table tbl_lkp_information_request_comm_types_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_information_request_comm_types_seq enable keys */;

#------------------------------------
select 'tbl_lkp_information_request_comm_types_shadow';
#------------------------------------
#insert into tbl_lkp_information_request_comm_types_shadow (id, description, sort_order) select id, description, sort_order from tbl_lkp_information_request_comm_types order by id;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_decision_maker_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types disable keys */;
lock tables tbl_lkp_decision_maker_types write;
#------------------------------------------------|----|------------------|------------|
# -- Columns --                                  | id | description (50) | sort_order |
#------------------------------------------------|----|------------------|------------|
insert into tbl_lkp_decision_maker_types values (   1, 'Yes',                        0);
insert into tbl_lkp_decision_maker_types values (   2, 'No',                         1);
insert into tbl_lkp_decision_maker_types values (   3, 'Don\'t know',                2);
#------------------------------------------------|----|------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types enable keys */;

#------------------------------------
select 'tbl_lkp_decision_maker_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_decision_maker_types_seq disable keys */;
lock tables tbl_lkp_decision_maker_types_seq write;

update tbl_lkp_decision_maker_types_seq set sequence = 3;
alter table tbl_lkp_decision_maker_types_seq auto_increment = 4;

unlock tables;
/*!40000 alter table tbl_lkp_decision_maker_types_seq enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------

#------------------------------------------
select 'tbl_lkp_agency_user_types';
#------------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types disable keys */;
lock tables tbl_lkp_agency_user_types write;
#---------------------------------------------|----|---------------------|------------|
# -- Columns --                               | id | description (50)    | sort_order |
#---------------------------------------------|----|---------------------|------------|
insert into tbl_lkp_agency_user_types values (   1, 'Yes',                        0);
insert into tbl_lkp_agency_user_types values (   2, 'No',                         1);
insert into tbl_lkp_agency_user_types values (   3, 'Don\'t know',                2);
insert into tbl_lkp_agency_user_types values (   4, 'Project frequent',           3);
insert into tbl_lkp_agency_user_types values (   5, 'Project infrequent',         4);
insert into tbl_lkp_agency_user_types values (   6, 'Retained frequent',          5);
#---------------------------------------------|----|---------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types enable keys */;

#------------------------------------
select 'tbl_lkp_agency_user_types_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_agency_user_types_seq disable keys */;
lock tables tbl_lkp_agency_user_types_seq write;

update tbl_lkp_agency_user_types_seq set sequence = 6;
alter table tbl_lkp_agency_user_types_seq auto_increment = 7;

unlock tables;
/*!40000 alter table tbl_lkp_agency_user_types_seq enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------


set FOREIGN_KEY_CHECKS = 1;