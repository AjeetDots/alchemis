SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_lkp_event_types DISABLE KEYS */;
LOCK TABLES tbl_lkp_event_types WRITE;
#--------------------------------------|----|---------------|
# -- Columns --                        | id | name          |
#--------------------------------------|----|---------------|
INSERT INTO tbl_lkp_event_types VALUES (  1 , 'Calling Day' );
INSERT INTO tbl_lkp_event_types VALUES (  2 , 'Holiday'     );
INSERT INTO tbl_lkp_event_types VALUES (  3 , 'Incentive'   );
INSERT INTO tbl_lkp_event_types VALUES (  4 , 'Internal'    );
INSERT INTO tbl_lkp_event_types VALUES (  5 , 'Sick'        );
#--------------------------------------|----|---------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_lkp_event_types ENABLE KEYS */;

--ALTER TABLE tbl_lkp_event_types_seq AUTO_INCREMENT = 5;
--INSERT INTO tbl_lkp_event_types_seq VALUES (5);


/*!40000 ALTER TABLE tbl_events DISABLE KEYS */;
LOCK TABLES tbl_events WRITE;
#-----------------------------|----|------------------------|------------------|--------------|-----------------------|---------|---------|
# -- Columns --               | id | subject                | notes            | date         | reminder_date         | user_id | type_id |
#-----------------------------|----|------------------------|------------------|--------------|-----------------------|---------|---------|
INSERT INTO tbl_events VALUES (  1 , 'My Calling Day Event' , 'Blah blah blah' , '2007-10-19' , '2007-09-20 00:00:00' ,      70 ,       1 );
INSERT INTO tbl_events VALUES (  2 , 'My Holiday Event'     , 'Blah blah blah' , '2007-10-21' , '2007-09-20 00:00:00' ,      70 ,       2 );
INSERT INTO tbl_events VALUES (  3 , 'My Incentive Event'   , 'Blah blah blah' , '2007-10-22' , '2007-09-20 00:00:00' ,      70 ,       3 );
INSERT INTO tbl_events VALUES (  4 , 'My Internal Event'    , 'Blah blah blah' , '2007-10-23' , '2007-09-20 00:00:00' ,      70 ,       4 );
INSERT INTO tbl_events VALUES (  5 , 'My Sick Event'        , 'Blah blah blah' , '2007-10-24' , '2007-09-20 00:00:00' ,      70 ,       5 );
#-----------------------------|----|------------------------|------------------|--------------|-----------------------|---------|---------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_events ENABLE KEYS */;

ALTER TABLE tbl_events_seq AUTO_INCREMENT = 5;
INSERT INTO tbl_events_seq VALUES (5);

SET FOREIGN_KEY_CHECKS = 1;