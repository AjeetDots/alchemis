SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_teams DISABLE KEYS */;
LOCK TABLES tbl_teams WRITE;
#----------------------------|----|----------|
# -- Columns --              | id | name     |
#----------------------------|----|----------|
INSERT INTO tbl_teams VALUES (  1 , 'Team A' );
INSERT INTO tbl_teams VALUES (  2 , 'Team B' );
INSERT INTO tbl_teams VALUES (  3 , 'Team C' );
#----------------------------|----|----------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_teams ENABLE KEYS */;

ALTER TABLE tbl_teams_seq AUTO_INCREMENT = 3;
INSERT INTO tbl_teams_seq VALUES (3);

/*!40000 ALTER TABLE tbl_team_nbms DISABLE KEYS */;
LOCK TABLES tbl_team_nbms WRITE;
#--------------------------------|----|---------|---------|
# -- Columns --                  | id | team_id | user_id |
#--------------------------------|----|---------|---------|
INSERT INTO tbl_team_nbms VALUES (  1 ,       1 ,      70 );
INSERT INTO tbl_team_nbms VALUES (  2 ,       1 ,      23 );
INSERT INTO tbl_team_nbms VALUES (  3 ,       1 ,      39 );
#--------------------------------|----|---------|---------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_teams ENABLE KEYS */;

ALTER TABLE tbl_team_nbms_seq AUTO_INCREMENT = 1;
INSERT INTO tbl_team_nbms_seq VALUES (1);

SET FOREIGN_KEY_CHECKS = 1;