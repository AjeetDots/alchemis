SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_actions DISABLE KEYS */;
LOCK TABLES tbl_actions WRITE;
#------------------------------|----|-----------------|------------------|-----------------------|-----------------------|---------|-----------|
# -- Columns --                | id | subject         | notes            | due_date              | reminder_date         | user_id | completed |
#------------------------------|----|-----------------|------------------|-----------------------|-----------------------|---------|-----------|
INSERT INTO tbl_actions VALUES (  1 , 'Test Action 1' , 'Blah blah blah' , '2007-09-19 00:00:00' , '2007-09-20 00:00:00' ,      70 ,         0 );
INSERT INTO tbl_actions VALUES (  2 , 'Test Action 2' , 'Blah blah blah' , '2007-09-19 09:30:00' , '2007-09-20 00:00:00' ,      70 ,         0 );
INSERT INTO tbl_actions VALUES (  3 , 'Test Action 3' , 'Blah blah blah' , '2007-09-20 00:00:00' , '2007-09-20 00:00:00' ,      70 ,         0 );
INSERT INTO tbl_actions VALUES (  4 , 'Test Action 4' , 'Blah blah blah' , '2007-10-12 18:00:00' , '2007-10-11 10:30:00' ,      70 ,         0 );
INSERT INTO tbl_actions VALUES (  5 , 'Test Action 5' , 'Blah blah blah' , '2007-10-17 00:00:00' , '2007-10-10 12:00:00' ,      70 ,         0 );
INSERT INTO tbl_actions VALUES (  6 , 'Test Action 6' , 'Blah blah blah' , '2007-12-20 09:00:00' , '2007-12-13 09:00:00' ,      70 ,         0 );
#------------------------------|----|-----------------|------------------|-----------------------|-----------------------|---------|-----------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_actions ENABLE KEYS */;

ALTER TABLE tbl_actions_seq AUTO_INCREMENT = 6;
INSERT INTO tbl_actions_seq VALUES (6);

SET FOREIGN_KEY_CHECKS = 1;