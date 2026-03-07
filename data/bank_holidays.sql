--
-- This data is based on Bank and Public Holidays in England and Wales taken from
--  http://www.dti.gov.uk/employment/bank-public-holidays/index.html (as of 10/10/2007)
--

SET FOREIGN_KEY_CHECKS = 0;

/*!40000 ALTER TABLE tbl_bank_holidays DISABLE KEYS */;
LOCK TABLES tbl_bank_holidays WRITE;
#------------------------------------|----|--------------|--------------------------|
# -- Columns --                      | id | date         | name                     |
#------------------------------------|----|--------------|--------------------------|
INSERT INTO tbl_bank_holidays VALUES (  1 , '2007-01-01' , 'New Year\'s Day'        );
INSERT INTO tbl_bank_holidays VALUES (  2 , '2007-04-06' , 'Good Friday'            );
INSERT INTO tbl_bank_holidays VALUES (  3 , '2007-04-09' , 'Easter Monday'          );
INSERT INTO tbl_bank_holidays VALUES (  4 , '2007-05-07' , 'Early May Bank Holiday' );
INSERT INTO tbl_bank_holidays VALUES (  5 , '2007-05-28' , 'Spring Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES (  6 , '2007-08-27' , 'Summer Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES (  7 , '2007-12-25' , 'Christmas Day'          );
INSERT INTO tbl_bank_holidays VALUES (  8 , '2007-12-26' , 'Boxing Day'             );

INSERT INTO tbl_bank_holidays VALUES (  9 , '2008-01-01' , 'New Year\'s Day'        );
INSERT INTO tbl_bank_holidays VALUES ( 10 , '2008-03-21' , 'Good Friday'            );
INSERT INTO tbl_bank_holidays VALUES ( 11 , '2008-03-24' , 'Easter Monday'          );
INSERT INTO tbl_bank_holidays VALUES ( 12 , '2008-05-05' , 'Early May Bank Holiday' );
INSERT INTO tbl_bank_holidays VALUES ( 13 , '2008-05-26' , 'Spring Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 14 , '2008-08-25' , 'Summer Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 15 , '2008-12-25' , 'Christmas Day'          );
INSERT INTO tbl_bank_holidays VALUES ( 16 , '2008-12-26' , 'Boxing Day'             );

INSERT INTO tbl_bank_holidays VALUES ( 17 , '2009-01-01' , 'New Year\'s Day'        );
INSERT INTO tbl_bank_holidays VALUES ( 18 , '2009-04-10' , 'Good Friday'            );
INSERT INTO tbl_bank_holidays VALUES ( 19 , '2009-04-13' , 'Easter Monday'          );
INSERT INTO tbl_bank_holidays VALUES ( 20 , '2009-05-04' , 'Early May Bank Holiday' );
INSERT INTO tbl_bank_holidays VALUES ( 21 , '2009-05-25' , 'Spring Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 22 , '2009-08-31' , 'Summer Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 23 , '2009-12-25' , 'Christmas Day'          );
INSERT INTO tbl_bank_holidays VALUES ( 24 , '2009-12-28' , 'Boxing Day (substitute)' );

INSERT INTO tbl_bank_holidays VALUES ( 25 , '2010-01-01' , 'New Year\'s Day'        );
INSERT INTO tbl_bank_holidays VALUES ( 26 , '2010-04-02' , 'Good Friday'            );
INSERT INTO tbl_bank_holidays VALUES ( 27 , '2010-04-05' , 'Easter Monday'          );
INSERT INTO tbl_bank_holidays VALUES ( 28 , '2010-05-03' , 'Early May Bank Holiday' );
INSERT INTO tbl_bank_holidays VALUES ( 29 , '2010-05-31' , 'Spring Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 30 , '2010-08-30' , 'Summer Bank Holiday'    );
INSERT INTO tbl_bank_holidays VALUES ( 31 , '2010-12-27' , 'Christmas Day (substitute)' );
INSERT INTO tbl_bank_holidays VALUES ( 32 , '2010-12-28' , 'Boxing Day (substitute)'    );
#------------------------------------|----|--------------|--------------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE tbl_bank_holidays ENABLE KEYS */;

ALTER TABLE tbl_bank_holidays_seq AUTO_INCREMENT = 32;
INSERT INTO tbl_bank_holidays_seq VALUES (32);

SET FOREIGN_KEY_CHECKS = 1;