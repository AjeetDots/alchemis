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
#insert into tbl_lkp_communication_status values (   2, 14,           21,           'Tepid',                                1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   2, 22,           23,           'Receptive',                            1,                  1,                            2          );
insert into tbl_lkp_communication_status values (   3, 24,           25,           'Very receptive',                       1,                  1,                            3          );
insert into tbl_lkp_communication_status values (   4, 26,           63,           'Hot',                                  1,                  1,                            4          );

insert into tbl_lkp_communication_status values (   5, 64  ,         64,           'Fresh lead',                           0,                  1,                            5          );
insert into tbl_lkp_communication_status values (   6, 128,          128,          'Do not call',                          0,                  1,                            6          );
#insert into tbl_lkp_communication_status values (   8, 256,         128,          'Cold',                                 0,                  1,                            7          );
insert into tbl_lkp_communication_status values (   7, 512,          256,          'Not worthwhile',                       0,                  1,                            8          );
insert into tbl_lkp_communication_status values (   8, 512,          512,          'Not worthwhile Company',               0,                  1,                            9          );

insert into tbl_lkp_communication_status values (  10, 1000,         1000,         'Meeting set',                          0,                  0,                            10         );
insert into tbl_lkp_communication_status values (  11, 2000,         2000,         'Follow-up meeting set',                0,                  0,                            11         );

insert into tbl_lkp_communication_status values (  12, 3000,         3000,         'Meeting to be rearranged',             0,                  0,                            12         );
insert into tbl_lkp_communication_status values (  13, 4000,         4000,         'Follow-up meeting to be rearranged',   0,                  0,                            13         );

insert into tbl_lkp_communication_status values (  14, 5000,         5000,         'Meeting rearranged',                   0,                  0,                            14         );
insert into tbl_lkp_communication_status values (  15, 6000,         6000,         'Follow-up meeting rearranged',         0,                  0,                            15         );

insert into tbl_lkp_communication_status values (  16, 7000,         7000,         'Meeting cancelled',                    0,                  0,                            16         );
insert into tbl_lkp_communication_status values (  17, 8000,         8000,         'Follow-up meeting cancelled',          0,                  0,                            17         );

insert into tbl_lkp_communication_status values (  18, 9000,         9000,         'Meeting attended: client',             0,                  0,                            18         );
insert into tbl_lkp_communication_status values (  19, 10000,        10000,        'Follow-up meeting attended: client',   0,                  0,                            19         );

insert into tbl_lkp_communication_status values (  20, 11000,        11000,        'Meeting attended: Alchemis',           0,                  0,                            20         );
insert into tbl_lkp_communication_status values (  21, 12000,        12000,        'Follow-up meeting attended: Alchemis', 0,                  0,                            21         );

insert into tbl_lkp_communication_status values (  22, 13000,        13000,        'Brief received',                       0,                  0,                            22         );
insert into tbl_lkp_communication_status values (  23, 14000,        14000,        'Win',                                  0,                  1,                            23         );
insert into tbl_lkp_communication_status values (  24, 15000,        15000,        'No business',                          0,                  1,                            24         );
insert into tbl_lkp_communication_status values (  25, 16000,        16000,        'Follow-up meeting to be arranged',     0,                  0,                            25         );
#-----------------------------------------------|----|-------------|-------------|---------------------------------------|-------------------|-----------------------------|------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_seq disable keys */;
lock tables tbl_lkp_communication_status_seq write;

update tbl_lkp_communication_status_seq set sequence = 25;
alter table tbl_lkp_communication_status_seq auto_increment = 26;

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
#Fresh lead
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (   1,  5,            5,                   1);
insert into tbl_lkp_communication_status_rules values (   2,  5,            10,                   2);
insert into tbl_lkp_communication_status_rules values (   3,  5,            6,                   3);
insert into tbl_lkp_communication_status_rules values (   4,  5,            7,                   4);
insert into tbl_lkp_communication_status_rules values (   5,  5,            8,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (   6,  5,            1,                   6);
insert into tbl_lkp_communication_status_rules values (   7,  5,            2,                   7);
insert into tbl_lkp_communication_status_rules values (   8,  5,            3,                   8);
insert into tbl_lkp_communication_status_rules values (   9,  5,            4,                   9);

#Do not call
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  11,  6,            6,                   1);
insert into tbl_lkp_communication_status_rules values (  12,  6,            5,                   2); #?
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  13,  6,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  14,  6,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  15,  6,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  16,  6,            4,                   9);

#Not worthwhile
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  27,  7,            7,                   1);
insert into tbl_lkp_communication_status_rules values (  28,  7,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  29,  7,            6,                   3);
insert into tbl_lkp_communication_status_rules values (  30,  7,            5,                   4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  31,  7,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  32,  7,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  33,  7,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  34,  7,            4,                   9);

#Not worthwhile company
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  18,  8,            8,                   1);
insert into tbl_lkp_communication_status_rules values (  19,  8,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  20,  8,            5,                   3);
insert into tbl_lkp_communication_status_rules values (  21,  8,            6,                   4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  22,  8,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  23,  8,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  24,  8,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  25,  8,            4,                   9);

#Dormant
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  36,  1,            1,                   1);
insert into tbl_lkp_communication_status_rules values (  37,  1,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  38,  1,            5,                   3);
insert into tbl_lkp_communication_status_rules values (  39,  1,            6,                   4);
insert into tbl_lkp_communication_status_rules values (  40,  1,            7,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  41,  1,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  42,  1,            3,                   8);
insert into tbl_lkp_communication_status_rules values (  43,  1,            4,                   9);

#Receptive
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  54,  2,            2,                   1);
insert into tbl_lkp_communication_status_rules values (  55,  2,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  56,  2,            5,                   3);
insert into tbl_lkp_communication_status_rules values (  57,  2,            6,                   4);
insert into tbl_lkp_communication_status_rules values (  58,  2,            7,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  59,  2,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  60,  2,            3,                   7);
insert into tbl_lkp_communication_status_rules values (  61,  2,            4,                   9);

#Very receptive
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  63,  3,            3,                   1);
insert into tbl_lkp_communication_status_rules values (  64,  3,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  65,  3,            5,                   3);
insert into tbl_lkp_communication_status_rules values (  66,  3,            6,                   4);
insert into tbl_lkp_communication_status_rules values (  67,  3,            7,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  68,  3,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  69,  3,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  70,  3,            4,                   8);

#Hot
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  72,  4,            4,                   1);
insert into tbl_lkp_communication_status_rules values (  73,  4,            10,                  2);
insert into tbl_lkp_communication_status_rules values (  74,  4,            5,                   3);
insert into tbl_lkp_communication_status_rules values (  75,  4,            6,                   4);
insert into tbl_lkp_communication_status_rules values (  76,  4,            7,                   5);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values (  77,  4,            1,                   6);
insert into tbl_lkp_communication_status_rules values (  78,  4,            2,                   7);
insert into tbl_lkp_communication_status_rules values (  79,  4,            3,                   8);

#Meeting set
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  81,  10,           10,                  1);
insert into tbl_lkp_communication_status_rules values (  82,  10,           12,                  2);
insert into tbl_lkp_communication_status_rules values (  83,  10,           14,                  3);
insert into tbl_lkp_communication_status_rules values (  84,  10,           16,                  4);
insert into tbl_lkp_communication_status_rules values (  85,  10,           18,                  5);
insert into tbl_lkp_communication_status_rules values (  86,  10,           20,                  6);

#Follow-up meeting set
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  87,  11,           11,                  1);
insert into tbl_lkp_communication_status_rules values (  88,  11,           13,                  2);
insert into tbl_lkp_communication_status_rules values (  89,  11,           15,                  3);
insert into tbl_lkp_communication_status_rules values (  90,  11,           17,                  4);
insert into tbl_lkp_communication_status_rules values (  91,  11,           19,                  5);
insert into tbl_lkp_communication_status_rules values (  92,  11,           21,                  6);

#Meeting to be rearranged
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  93,  12,           12,                  1);
insert into tbl_lkp_communication_status_rules values (  94,  12,           14,                  2);
insert into tbl_lkp_communication_status_rules values (  95,  12,           16,                  3);

#Follow-up meeting to be rearranged
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  96,  13,           13,                  1);
insert into tbl_lkp_communication_status_rules values (  97,  13,           15,                  2);
insert into tbl_lkp_communication_status_rules values (  98,  13,           17,                  3);

#Meeting rearranged
#--- selectable options available
insert into tbl_lkp_communication_status_rules values (  99,  14,           14,                  1);
insert into tbl_lkp_communication_status_rules values ( 100,  14,           12,                  2);
insert into tbl_lkp_communication_status_rules values ( 101,  14,           16,                  3);
insert into tbl_lkp_communication_status_rules values ( 102,  14,           18,                  4);
insert into tbl_lkp_communication_status_rules values ( 103,  14,           20,                  5);

#Follow-up meeting rearranged
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 104,  15,           15,                  1);
insert into tbl_lkp_communication_status_rules values ( 105,  15,           13,                  2);
insert into tbl_lkp_communication_status_rules values ( 106,  15,           17,                  3);
insert into tbl_lkp_communication_status_rules values ( 107,  15,           19,                  4);
insert into tbl_lkp_communication_status_rules values ( 108,  15,           21,                  5);

#Meeting cancelled
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 109,  16,           16,                  1);
insert into tbl_lkp_communication_status_rules values ( 110,  16,           6,                   2);
insert into tbl_lkp_communication_status_rules values ( 111,  16,           7,                   3);
insert into tbl_lkp_communication_status_rules values ( 112,  16,           8,                   4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 113,  16,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 114,  16,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 115,  16,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 116,  16,            4,               9);

#Follow-up meeting cancelled
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 117,  17,           17,                  1);
insert into tbl_lkp_communication_status_rules values ( 118,  17,           6,                   2);
insert into tbl_lkp_communication_status_rules values ( 119,  17,           7,                   3);
insert into tbl_lkp_communication_status_rules values ( 120,  17,           8,                   4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 121,  17,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 122,  17,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 123,  17,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 124,  17,            4,               9);

#Meeting attended: client
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 125,  18,           18,              1);
insert into tbl_lkp_communication_status_rules values ( 126,  18,           11,                 2);
insert into tbl_lkp_communication_status_rules values ( 127,  18,           22,                 3);
insert into tbl_lkp_communication_status_rules values ( 128,  18,           23,                 4);
insert into tbl_lkp_communication_status_rules values ( 129,  18,           24,                 5);
insert into tbl_lkp_communication_status_rules values ( 130,  18,           25,                 6);


#Follow-up meeting attended: client
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 131,  19,           19,              1);
insert into tbl_lkp_communication_status_rules values ( 132,  19,           11,                 2);
insert into tbl_lkp_communication_status_rules values ( 133,  19,           22,                 3);
insert into tbl_lkp_communication_status_rules values ( 134,  19,           23,                 4);
insert into tbl_lkp_communication_status_rules values ( 135,  19,           24,                 5);
insert into tbl_lkp_communication_status_rules values ( 136,  19,           25,                 6);

#Meeting attended: Alchemis
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 137,  20,           20,                 1);
insert into tbl_lkp_communication_status_rules values ( 138,  20,           11,                 2);
insert into tbl_lkp_communication_status_rules values ( 139,  20,           22,                 3);
insert into tbl_lkp_communication_status_rules values ( 140,  20,           23,                 4);
insert into tbl_lkp_communication_status_rules values ( 141,  20,           24,                 5);
insert into tbl_lkp_communication_status_rules values ( 142,  20,           25,                 6);

#Follow-up meeting attended: Alchemis
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 143,  21,           21,                 1);
insert into tbl_lkp_communication_status_rules values ( 144,  21,           11,                 2);
insert into tbl_lkp_communication_status_rules values ( 145,  21,           22,                 3);
insert into tbl_lkp_communication_status_rules values ( 146,  21,           23,                 4);
insert into tbl_lkp_communication_status_rules values ( 147,  21,           24,                 5);
insert into tbl_lkp_communication_status_rules values ( 148,  21,           25,                 6);

#Brief received
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 149,  22,           22,                 1);
insert into tbl_lkp_communication_status_rules values ( 150,  22,           11,                 2);
insert into tbl_lkp_communication_status_rules values ( 151,  22,           23,                 3);
insert into tbl_lkp_communication_status_rules values ( 152,  22,           24,                 4);
insert into tbl_lkp_communication_status_rules values ( 153,  22,           25,                 5);

#Win
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 154,  23,           23,              1);
insert into tbl_lkp_communication_status_rules values ( 155,  23,           6,               2);
insert into tbl_lkp_communication_status_rules values ( 156,  23,           7,               3);
insert into tbl_lkp_communication_status_rules values ( 157,  23,           8,               4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 158,  23,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 159,  23,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 160,  23,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 161,  23,            4,               9);

#No business
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 163,  24,           24,                 1);
insert into tbl_lkp_communication_status_rules values ( 164,  24,           6,               2);
insert into tbl_lkp_communication_status_rules values ( 165,  24,           7,               3);
insert into tbl_lkp_communication_status_rules values ( 166,  24,           8,               4);
#--- non-selectable options available
insert into tbl_lkp_communication_status_rules values ( 167,  24,            1,               6);
insert into tbl_lkp_communication_status_rules values ( 168,  24,            2,               7);
insert into tbl_lkp_communication_status_rules values ( 169,  24,            3,               8);
insert into tbl_lkp_communication_status_rules values ( 170,  24,            4,               9);

#Follow-up meeting
#--- selectable options available
insert into tbl_lkp_communication_status_rules values ( 172,  25,           25,              1);
insert into tbl_lkp_communication_status_rules values ( 173,  25,           11,              2);

#-------------------------------------------------------|------|-------------|-------------|-----------------|
unlock tables;
/*!40000 alter table tbl_lkp_communication_status_rules enable keys */;

#------------------------------------
select 'tbl_lkp_communication_status_rules_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_communication_status_rules_seq disable keys */;
lock tables tbl_lkp_communication_status_rules_seq write;

update tbl_lkp_communication_status_rules_seq set sequence = 168;
alter table tbl_lkp_communication_status_rules_seq auto_increment = 169;

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

