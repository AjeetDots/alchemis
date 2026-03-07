SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_messages DISABLE KEYS */;
LOCK TABLES tbl_messages WRITE;
#-------------------------------|----|-----------------------|---------|--------------------------------------------------|-----------|
# -- Columns --                 | id | timestamp             | user_id | message                                          | published |
#-------------------------------|----|-----------------------|---------|--------------------------------------------------|-----------|
INSERT INTO tbl_messages VALUES (  1 , '2007-09-20 00:00:00' ,       4 , 'Dave you one cool mo\'fo dude'                  ,         1 );
INSERT INTO tbl_messages VALUES (  2 , '2007-09-21 00:00:00' ,       4 , 'Claudia scores a hatrick'                       ,         1 );
INSERT INTO tbl_messages VALUES (  3 , '2007-09-22 00:00:00' ,       4 , 'Well done on setting 1 meet yesterday with BBC' ,         1 );
#-------------------------------|----|-----------------------|---------|--------------------------------------------------|-----------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_messages ENABLE KEYS */;

ALTER TABLE tbl_messages_seq AUTO_INCREMENT = 3;
INSERT INTO tbl_messages_seq VALUES (3);

SET FOREIGN_KEY_CHECKS = 1;