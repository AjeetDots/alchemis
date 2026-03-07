CREATE DATABASE dbname CHARACTER SET utf8 COLLATE utf8_general_ci;

mysqldump -u $user -p --opt --quote-names --skip-set-charset \
--default-character-set=latin1 $dbname > dump.sql


mysqldump --user=alchemis --password="rYT4maP7" --routines --skip-set-charset --default-character-set=latin1 alchemis > charencoding.sql

mysql --default-character-set=utf8 alchemis_test < charencoding.sql