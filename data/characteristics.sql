SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_characteristics DISABLE KEYS */;
LOCK TABLES tbl_characteristics WRITE;
#--------------------------------------|----|------------------|------------------------------|-------------------------------------------------------------|------------|---------|-----------------|-----------|
# -- Columns --                        | id | type             | name                         | description                                                 | attributes | options | multiple_select | data_type |
#--------------------------------------|----|------------------|------------------------------|-------------------------------------------------------------|------------|---------|-----------------|-----------|
INSERT INTO tbl_characteristics VALUES (   1, 'company',         'Company attributes',           'General company attributes',                                          1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   2, 'company',         'XAlchemis company attributes', 'XAlchemis company attributes',                                        1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   3, 'company',         'Marketing speciality',         'Marketing speciality',                                                1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   4, 'company',         'Employees',                    'Employees',                                                           1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   5, 'post initiative', 'Current New Biz Approach',     'Current New Biz Approach',                                            1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   6, 'company',         'Agency User',                  'Agency User',                                                         1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   7, 'post initiative', 'Fee Confirmed',                'Fee Confirmed',                                                       0,        0,                0, 'boolean' );
INSERT INTO tbl_characteristics VALUES (   8, 'company',         'XMailer Ref',                  'X',                                                                   1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (   9, 'company',         'XResearch Leads',              'XUsed for flagging new companies added by Rob, Aisha, etc',           1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  10, 'company',         'Cleaned Date',                 'Flags last cleaned',                                                  0,        0,                0, 'date'    );
INSERT INTO tbl_characteristics VALUES (  11, 'company',         'XBoss\'s Name',                'XName of the MD',                                                     0,        0,                0, 'text'    );
INSERT INTO tbl_characteristics VALUES (  12, 'post',            'XAgency Disciplines',          'XWhat type of agencies the post uses',                                1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  13, 'post initiative', 'Existing Clients',             'Examples of an agency\'s current clients',                            1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  14, 'company',         'XLiked by Ian',                'XWhether Ian likes them or not',                                      0,        0,                0, 'boolean' );
INSERT INTO tbl_characteristics VALUES (  15, 'company',         'XSectors',                     'XSectors the company operates in',                                    0,        1,                1, NULL      );
INSERT INTO tbl_characteristics VALUES (  16, 'post initiative', 'Competitive',                  'Can the agency afford monthly retainer',                              0,        1,                0, 'boolean' );
INSERT INTO tbl_characteristics VALUES (  17, 'post',            'Mailer Sent',                  'Whether they have been sent a mailer',                                0,        0,                0, 'boolean' );
INSERT INTO tbl_characteristics VALUES (  18, 'company',         'XCompany Attributes',          '',                                                                    1,        0,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  19, 'company',         'XTurnover',                    '',                                                                    0,        1,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  20, 'post',            'XMailer Response',             '',                                                                    1,        1,                0, NULL      );
INSERT INTO tbl_characteristics VALUES (  21, 'post',            'XMailer Response 2',           '',                                                                    1,        1,                1, NULL      );

#--------------------------------------|----|------------------|------------------------------|-------------------------------------------------------------|------------|---------|-----------------|-----------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_characteristics ENABLE KEYS */;

ALTER TABLE tbl_characteristics_seq AUTO_INCREMENT = 22;
INSERT INTO tbl_characteristics_seq VALUES (22);



/*!40000 ALTER TABLE tbl_characteristic_elements DISABLE KEYS */;
LOCK TABLES tbl_characteristic_elements WRITE;
#----------------------------------------------|----|-------------------|-----------|-------------------|------|
# -- Columns --                                | id | characteristic_id | data_type | value             | sort |
#----------------------------------------------|----|-------------------|-----------|-------------------|------|
INSERT INTO tbl_characteristic_elements VALUES (   1,                  6, 'boolean',  'Don\'t Know',          1);
INSERT INTO tbl_characteristic_elements VALUES (   2,                  6, 'boolean',  'No',                   2);
INSERT INTO tbl_characteristic_elements VALUES (   3,                  6, 'boolean',  'Yes',                  3);
INSERT INTO tbl_characteristic_elements VALUES (   4,                 12, 'boolean',  'Advertising',          1);
INSERT INTO tbl_characteristic_elements VALUES (   5,                 12, 'boolean',  'Marketing',            2);
INSERT INTO tbl_characteristic_elements VALUES (   6,                 12, 'boolean',  'New Media',            3);
INSERT INTO tbl_characteristic_elements VALUES (   7,                 13, 'text',     'Example 1',            1);
INSERT INTO tbl_characteristic_elements VALUES (   8,                 13, 'text',     'Example 2',            2);
INSERT INTO tbl_characteristic_elements VALUES (   9,                  1, 'text',     'Actual Employees',     1);
INSERT INTO tbl_characteristic_elements VALUES (  10,                  1, 'date',     'Cleaned Date',         2);
INSERT INTO tbl_characteristic_elements VALUES (  11,                  1, 'text',     'Country Notes',        3);
INSERT INTO tbl_characteristic_elements VALUES (  12,                  1, 'text',     'Description',          4);
INSERT INTO tbl_characteristic_elements VALUES (  13,                  1, 'boolean',  'Had mailer?',          5);
INSERT INTO tbl_characteristic_elements VALUES (  14,                  1, 'text',     'Research Fail',        6);
INSERT INTO tbl_characteristic_elements VALUES (  15,                  1, 'text',     'Research Work',        7);
INSERT INTO tbl_characteristic_elements VALUES (  16,                  1, 'text',     'Turnover',             8);

INSERT INTO tbl_characteristic_elements VALUES (  17,                 15, 'boolean',  'Finance',              1);
INSERT INTO tbl_characteristic_elements VALUES (  18,                 15, 'boolean',  'Health care',          2);
INSERT INTO tbl_characteristic_elements VALUES (  19,                 15, 'boolean',  'Retail',               3);
INSERT INTO tbl_characteristic_elements VALUES (  20,                 15, 'boolean',  'FMCG',                 4);

INSERT INTO tbl_characteristic_elements VALUES (  21,                 13, 'text',     'Example 3',            3);
INSERT INTO tbl_characteristic_elements VALUES (  22,                 13, 'text',     'Example 4',            4);
INSERT INTO tbl_characteristic_elements VALUES (  23,                  5, 'boolean',  'Internal',             1);
INSERT INTO tbl_characteristic_elements VALUES (  24,                  5, 'boolean',  'New biz agency',       2);
INSERT INTO tbl_characteristic_elements VALUES (  25,                  5, 'boolean',  'Other',                3);

INSERT INTO tbl_characteristic_elements VALUES (  26,                 19, 'boolean',  'Small',                1);
INSERT INTO tbl_characteristic_elements VALUES (  27,                 19, 'boolean',  'Medium',               2);
INSERT INTO tbl_characteristic_elements VALUES (  28,                 19, 'boolean',  'Large',                3);

INSERT INTO tbl_characteristic_elements VALUES (  33,                 20, 'date',     'Yes',                  1);
INSERT INTO tbl_characteristic_elements VALUES (  34,                 20, 'boolean',  'No',                   2);
INSERT INTO tbl_characteristic_elements VALUES (  35,                 20, 'text',     'Other',                3);

INSERT INTO tbl_characteristic_elements VALUES (  36,                 21, 'date',     'Yes',                  1);
INSERT INTO tbl_characteristic_elements VALUES (  37,                 21, 'boolean',  'No',                   2);
INSERT INTO tbl_characteristic_elements VALUES (  38,                 21, 'text',     'Other',                3);
#----------------------------------------------|----|-------------------|-----------|-------------------|------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_characteristic_elements ENABLE KEYS */;

ALTER TABLE tbl_characteristic_elements_seq AUTO_INCREMENT = 38;
INSERT INTO tbl_characteristic_elements_seq VALUES (38);



/*!40000 ALTER TABLE tbl_object_characteristics_boolean DISABLE KEYS */;
LOCK TABLES tbl_object_characteristics_boolean WRITE;
#-----------------------------------------------------|----|-------------------|------------|---------|--------------------|-------|
# -- Columns --                                       | id | characteristic_id | company_id | post_id | post_initiative_id | value |
#-----------------------------------------------------|----|-------------------|------------|---------|--------------------|-------|
INSERT INTO tbl_object_characteristics_boolean VALUES (   1,                 14,          15,     NULL,                NULL,      0);
INSERT INTO tbl_object_characteristics_boolean VALUES (   2,                  7,        NULL,     NULL,              341448,      1);
INSERT INTO tbl_object_characteristics_boolean VALUES (   3,                 16,        NULL,     NULL,              341448,      1);
#-----------------------------------------------------|----|-------------------|------------|---------|--------------------|-------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristics_boolean ENABLE KEYS */;

ALTER TABLE tbl_object_characteristics_boolean_seq AUTO_INCREMENT = 3;
INSERT INTO tbl_object_characteristics_boolean_seq VALUES (3);



/*!40000 ALTER TABLE tbl_object_characteristics_date DISABLE KEYS */;
LOCK TABLES tbl_object_characteristics_date WRITE;
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|-------------|
# -- Columns --                                    | id | characteristic_id | company_id | post_id | post_initiative_id | value       |
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|-------------|
INSERT INTO tbl_object_characteristics_date VALUES (   1,                 10,          14,     NULL,                NULL, '2007-04-06');
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|-------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristics_date ENABLE KEYS */;

ALTER TABLE tbl_object_characteristics_date_seq AUTO_INCREMENT = 1;
INSERT INTO tbl_object_characteristics_date_seq VALUES (1);


/*!40000 ALTER TABLE tbl_object_characteristics_text DISABLE KEYS */;
LOCK TABLES tbl_object_characteristics_text WRITE;
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|---------------------|
# -- Columns --                                    | id | characteristic_id | company_id | post_id | post_initiative_id | value               |
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|---------------------|
INSERT INTO tbl_object_characteristics_text VALUES (   1,                 11,          15,     NULL,                NULL, 'Mr Ian Munday Esq.');
#--------------------------------------------------|----|-------------------|------------|---------|--------------------|---------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristics_text ENABLE KEYS */;

ALTER TABLE tbl_object_characteristics_text_seq AUTO_INCREMENT = 1;
INSERT INTO tbl_object_characteristics_text_seq VALUES (1);



/*!40000 ALTER TABLE tbl_object_characteristics DISABLE KEYS */;
LOCK TABLES tbl_object_characteristics WRITE;
#---------------------------------------------|----|-------------------|------------|---------|--------------------|
# -- Columns --                               | id | characteristic_id | company_id | post_id | post_initiative_id |
#---------------------------------------------|----|-------------------|------------|---------|--------------------|
INSERT INTO tbl_object_characteristics VALUES (   1,                  1,          15,     NULL,                NULL);
INSERT INTO tbl_object_characteristics VALUES (   2,                  6,          15,     NULL,                NULL);
INSERT INTO tbl_object_characteristics VALUES (   3,                 15,          15,     NULL,                NULL);
INSERT INTO tbl_object_characteristics VALUES (   4,                  1,          13,     NULL,                NULL);
INSERT INTO tbl_object_characteristics VALUES (   5,                  5,        NULL,     NULL,              341448);
#---------------------------------------------|----|-------------------|------------|---------|--------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristics ENABLE KEYS */;

ALTER TABLE tbl_object_characteristics_seq AUTO_INCREMENT = 5;
INSERT INTO tbl_object_characteristics_seq VALUES (5);



/*!40000 ALTER TABLE tbl_object_characteristic_elements_boolean DISABLE KEYS */;
LOCK TABLES tbl_object_characteristic_elements_boolean WRITE;
#-------------------------------------------------------------|----|--------------------------|---------------------------|-------|
# -- Columns --                                               | id | object_characteristic_id | characteristic_element_id | value |
#-------------------------------------------------------------|----|--------------------------|---------------------------|-------|
INSERT INTO tbl_object_characteristic_elements_boolean VALUES (   1,                         3,                         17,      1);
INSERT INTO tbl_object_characteristic_elements_boolean VALUES (   2,                         5,                         23,      1);
INSERT INTO tbl_object_characteristic_elements_boolean VALUES (   3,                         3,                         20,      1);
#-------------------------------------------------------------|----|--------------------------|---------------------------|-------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristic_elements_boolean ENABLE KEYS */;

ALTER TABLE tbl_object_characteristic_elements_boolean_seq AUTO_INCREMENT = 3;
INSERT INTO tbl_object_characteristic_elements_boolean_seq VALUES (3);



/*!40000 ALTER TABLE tbl_object_characteristic_elements_date DISABLE KEYS */;
LOCK TABLES tbl_object_characteristic_elements_date WRITE;
#----------------------------------------------------------|----|--------------------------|---------------------------|----------------------|
# -- Columns --                                            | id | object_characteristic_id | characteristic_element_id | value                |
#----------------------------------------------------------|----|--------------------------|---------------------------|----------------------|
INSERT INTO tbl_object_characteristic_elements_date VALUES (   1,                         1,                         10, '2007-05-05 00:00:00');
#----------------------------------------------------------|----|--------------------------|---------------------------|----------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristic_elements_date ENABLE KEYS */;

ALTER TABLE tbl_object_characteristic_elements_date_seq AUTO_INCREMENT = 1;
INSERT INTO tbl_object_characteristic_elements_date_seq VALUES (1);



/*!40000 ALTER TABLE tbl_object_characteristic_elements_text DISABLE KEYS */;
LOCK TABLES tbl_object_characteristic_elements_text WRITE;
#----------------------------------------------------------|----|--------------------------|---------------------------|--------------------------------|
# -- Columns --                                            | id | object_characteristic_id | characteristic_element_id | value                          |
#----------------------------------------------------------|----|--------------------------|---------------------------|--------------------------------|
INSERT INTO tbl_object_characteristic_elements_text VALUES (   1,                         1,                          9, 'My actual employees goes here');
INSERT INTO tbl_object_characteristic_elements_text VALUES (   2,                         1,                         11, 'united kingdom'               );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   3,                         1,                         12, 'My description goes here'     );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   4,                         4,                         11, 'scotland'                     );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   5,                         6,                          7, 'eg 1'                         );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   6,                         7,                          8, 'eg 2'                         );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   7,                         8,                         21, 'eg 3'                         );
INSERT INTO tbl_object_characteristic_elements_text VALUES (   8,                         9,                         22, 'eg 4'                         );
#----------------------------------------------------------|----|--------------------------|---------------------------|--------------------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_object_characteristic_elements_text ENABLE KEYS */;

ALTER TABLE tbl_object_characteristic_elements_text_seq AUTO_INCREMENT = 8;
INSERT INTO tbl_object_characteristic_elements_text_seq VALUES (8);






SET FOREIGN_KEY_CHECKS = 1;