drop function f_get_post_meeting_count;

DELIMITER |
/*!50003 SET SESSION SQL_MODE=""*/|
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `f_get_post_meeting_count`(var_post_id INT(11)) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
DECLARE my_result int(11) DEFAULT 0;
select count(*) into my_result FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id and m.is_current= 1;
RETURN my_result;
END */|
DELIMITER ;|
