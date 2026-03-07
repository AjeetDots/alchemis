-- MySQL dump 10.11
--
-- Host: localhost    Database: alchemis
-- ------------------------------------------------------
-- Server version	5.0.45-community
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;

/*!40103 SET TIME_ZONE='+00:00' */
;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;

--
-- Dumping routines for database 'alchemis'
--
DELIMITER;

;

/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER"*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 FUNCTION `f_get_post_meeting_count`(var_post_id INT(11)) RETURNS int(11)
 READS SQL DATA
 DETERMINISTIC
 BEGIN
 DECLARE my_result int(11) DEFAULT 0;
 select count(*) into my_result FROM tbl_meetings m join tbl_post_initiatives pi on pi.id = m.post_initiative_id where pi.post_id = var_post_id and m.is_current= 1;
 RETURN my_result;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 FUNCTION `f_get_site_address`(var_in_company_id INT(11)) RETURNS char(255) CHARSET latin1
 READS SQL DATA
 DETERMINISTIC
 BEGIN
 DECLARE var_out_address char(255) DEFAULT '';
 SELECT CONCAT(	s.address_1,
 IF( LENGTH(s.address_2) > 0,
 CONCAT( IF( LENGTH(s.address_1) > 0, ', ', '' ), s.address_2 ),
 ''),
 IF( LENGTH(s.town) > 0,
 CONCAT( IF( LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.town ),
 ''),
 IF( LENGTH(s.city) > 0,
 CONCAT( IF( LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.city ),
 ''),
 IF( LENGTH(county.name) > 0,
 CONCAT( IF( LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), county.name ),
 ''),
 IF( LENGTH(s.postcode) > 0,
 CONCAT( IF( LENGTH(county.name) > 0 OR LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), s.postcode ),
 ''),
 IF( LENGTH(country.name) > 0,
 CONCAT( IF( LENGTH(county.name) > 0 OR LENGTH(s.postcode) > 0 OR LENGTH(s.city) > 0 OR LENGTH(s.town) > 0 OR LENGTH(s.address_2) > 0 OR LENGTH(s.address_1) > 0, ', ', '' ), country.name ),
 '')

 ) INTO var_out_address
 FROM tbl_sites AS s
 LEFT JOIN tbl_lkp_counties AS county ON s.county_id = county.id
 LEFT JOIN tbl_lkp_countries AS country ON s.country_id = country.id
 WHERE s.company_id = var_in_company_id;
 RETURN var_out_address;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER"*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 FUNCTION `f_next_comm_date_period`(future_date DATETIME, no_of_months INT(11)) RETURNS char(100) CHARSET latin1
 NO SQL
 BEGIN
 DECLARE day_count int(11) DEFAULT 0;
 DECLARE day_result char(100) DEFAULT '';
 DECLARE month_period int(11);
 DECLARE benchmark_date DATETIME;
 DECLARE benchmark_period int(11);
 SET benchmark_date = DATE_ADD(now(), INTERVAL no_of_months MONTH);
 SET benchmark_period = DATEDIFF(benchmark_date, now());
 SET day_count = DATEDIFF(future_date, now());
 IF day_count > benchmark_period THEN SET day_result = concat('> ' ,no_of_months , ' mth');
 ELSE SET day_result = concat(day_count, ' days') ;
 END IF;
 RETURN day_result;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_3a_1`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 select CAST(concat('To attend: ', DATE_FORMAT(m.date, '%b'), ' ', YEAR(m.date)) AS CHAR(255)) AS category,
 EXTRACT(YEAR_MONTH FROM m.date) AS `year_month`, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
 join vw_posts_contacts p on pi.post_id = p.id
 join tbl_companies co on co.id = p.company_id
 where m.date >= var_in_date_start
 AND m.date <= var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id IN (12, 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32)
 UNION
 select CAST('Live' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
 join vw_posts_contacts p on pi.post_id = p.id
 join tbl_companies co on co.id = p.company_id
 where m.date > var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id IN (12, 13, 18, 19)
 UNION
 select CAST('Lapsed' AS CHAR(255)) as category, 0, lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
 join vw_posts_contacts p on pi.post_id = p.id
 join tbl_companies co on co.id = p.company_id
 where m.date >= var_in_date_start
 AND c.client_id = var_in_client_id
 AND m.status_id IN (14, 15, 16, 17, 20, 21, 22, 23)
 ORDER BY category DESC, `year_month`, date;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_3a_2`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE meet_set INT;
 DECLARE to_attend INT;
 DECLARE live INT;
 DECLARE lapsed INT;
 DECLARE call_backs INT;
 DECLARE start_year_month varchar(6);
 DECLARE end_year_month varchar(6);
 SET start_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_start);
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 select sum(meeting_set_count) INTO meet_set
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` >= start_year_month
 and `year_month` <= end_year_month;
 select count(m.id) into to_attend
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date >= var_in_date_start
 and m.date <=var_in_date_end
 and c.client_id = var_in_client_id
 AND m.status_id in (12 , 13, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32);
 select count(m.id) into live
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date > var_in_date_end
 and c.client_id = var_in_client_id
 AND m.status_id in (12 , 13, 18, 19);
 select count(m.id) into lapsed
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date >= var_in_date_start
 and c.client_id = var_in_client_id
 AND m.status_id in (14,15,16,17,20,21,22,23);
 select count(pi.id) into call_backs
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 AND pi.status_id in (5, 6);
 create temporary table t (meet_set int(11),  to_attend int(11), live int(11), lapsed int(11), call_backs int(11));
 insert into t (meet_set, to_attend, live, lapsed, call_backs) values (meet_set, to_attend, live, lapsed, call_backs);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_3a_3`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE end_year_month varchar(6);
 DECLARE meets_set_target_to_date INT;
 DECLARE meets_attended_target_to_date INT;
 DECLARE meets_set_to_date INT;
 DECLARE meets_attended_to_date INT;
 DECLARE meets_potential_attended INT;
 DECLARE meets_live INT;
 DECLARE meets_lapsed_tbr INT;
 DECLARE meets_lapsed_cancelled INT;
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 select sum(meetings_set) INTO meets_set_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meetings_attended) INTO meets_attended_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_set_count) INTO meets_set_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_attended_count) INTO meets_attended_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_unknown_count) INTO meets_potential_attended
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(m.id) INTO meets_live
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date > var_in_date_end
 and c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_cancelled_count) INTO meets_lapsed_cancelled
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 create temporary table t (
 meets_set_target_to_date int(11),
 meets_attended_target_to_date int(11),
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_live int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11)
 );
 insert into t (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled)
 values (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_3a_3_chart`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 BEGIN
 DECLARE end_year_month varchar(6);
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 CREATE TEMPORARY TABLE t (
 category varchar(255),
 name varchar(255),
 value int(11));
 INSERT INTO t
 SELECT 'Set', 'Target', SUM(meetings_set)
 FROM tbl_campaign_targets
 WHERE campaign_id = var_in_client_id
 AND `year_month` <= end_year_month;
 INSERT INTO t
 SELECT 'Attended', 'Target', SUM(meetings_attended)
 FROM tbl_campaign_targets
 WHERE campaign_id = var_in_client_id
 AND `year_month` <= end_year_month;
 INSERT INTO t
 SELECT 'Set', 'Actual', SUM(meeting_set_count)
 FROM tbl_data_statistics
 WHERE campaign_id = var_in_client_id
 AND `year_month` <= end_year_month;
 INSERT INTO t
 SELECT 'Attended', 'Actual', SUM(meeting_category_attended_count)
 FROM tbl_data_statistics
 WHERE campaign_id = var_in_client_id
 AND `year_month` <= end_year_month;
 SELECT * FROM t;
 DROP TEMPORARY TABLE t;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_3b_4`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 SELECT CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category,
 EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`,
 lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
 join vw_posts_contacts p on pi.post_id = p.id
 join tbl_companies co on co.id = p.company_id
 where m.created_at >= var_in_date_start
 AND m.created_at <= var_in_date_end
 AND c.client_id = var_in_client_id
 ORDER BY category DESC, `year_month`, date;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_5_1`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t
 select count(com.id), tc.id, tc.value
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 where tc.category_id = 1
 and tc.parent_id = 0
 and com.communication_date >= var_in_date_start
 and com.communication_date <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t1
 select count(m.id), tc.id, tc.value
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 where tc.category_id = 1
 and tc.parent_id = 0
 and m.created_at >= var_in_date_start
 and m.created_at <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
 insert into t2
 select sum(t.item_count)
 from t;
 select t.tc_value as sector,
 t1.item_count as meeting_count,
 t.item_count/t2.item_count as activity_percentage,
 t1.item_count/t.item_count as conversion_percentage
 from t2, t
 left join t1 on t.tc_id = t1.tc_id
 order by activity_percentage desc, meeting_count desc;
 DROP TABLE IF EXISTS `t`;
 DROP TABLE IF EXISTS `t1`;
 DROP TABLE IF EXISTS `t2`;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_5_2`(var_in_client_id int(11))
 begin
 DECLARE var_start_year_month varchar(6);
 DECLARE start_date datetime;
 select start_year_month INTO var_start_year_month
 from tbl_campaigns
 where id = var_in_client_id;
 SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');
 CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t
 select count(com.id), tc.id, tc.value
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 where tc.category_id = 1
 and tc.parent_id = 0
 and com.communication_date >= start_date
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t1
 select count(m.id), tc.id, tc.value
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 where tc.category_id = 1
 and tc.parent_id = 0
 and m.created_at >= start_date
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
 insert into t2
 select sum(t.item_count)
 from t;
 select t.tc_value as sector,
 t1.item_count as meeting_count,
 t.item_count/t2.item_count as activity_percentage,
 t1.item_count/t.item_count as conversion_percentage
 from t2, t
 left join t1 on t.tc_id = t1.tc_id
 order by activity_percentage desc, meeting_count desc;
 DROP TABLE IF EXISTS `t`;
 DROP TABLE IF EXISTS `t1`;
 DROP TABLE IF EXISTS `t2`;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_5_3`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t
 select count(com.id), tc.id, tc.value, tc_parent.value
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id
 where tc.category_id = 1
 and tc.parent_id > 0
 and com.communication_date >= var_in_date_start
 and com.communication_date <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t1
 select count(m.id), tc.id, tc.value, tc_parent.value
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id
 where tc.category_id = 1
 and tc.parent_id = 0
 and m.created_at >= var_in_date_start
 and m.created_at <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
 insert into t2
 select sum(t.item_count)
 from t;
 select concat('(', t.tc_parent, ') ', t.tc_value) as sector,
 t1.item_count as meeting_count,
 t.item_count/t2.item_count as activity_percentage,
 t1.item_count/t.item_count as conversion_percentage
 from t2, t
 left join t1 on t.tc_id = t1.tc_id
 where round((t.item_count/t2.item_count)*100) > 0
 order by activity_percentage desc;
 DROP TABLE IF EXISTS `t`;
 DROP TABLE IF EXISTS `t1`;
 DROP TABLE IF EXISTS `t2`;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_5_4`(var_in_client_id int(11))
 begin
 DECLARE var_start_year_month varchar(6);
 DECLARE start_date datetime;
 select start_year_month INTO var_start_year_month
 from tbl_campaigns
 where id = var_in_client_id;
 SET start_date = STR_TO_DATE(concat(var_start_year_month, '01'), '%Y%m%d');
 CREATE TEMPORARY TABLE `t` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t
 select count(com.id), tc.id, tc.value, tc_parent.value
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id
 where tc.category_id = 1
 and tc.parent_id > 0
 and com.communication_date >= start_date
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t1` ( `item_count` int(11), `tc_id` int(11), `tc_value` varchar(100), `tc_parent` varchar(100), PRIMARY KEY (`tc_id`));
 insert into t1
 select count(m.id), tc.id, tc.value, tc_parent.value
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_companies c on p.company_id = c.id
 join tbl_object_tiered_characteristics otc on c.id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 join tbl_tiered_characteristics tc_parent on tc.parent_id = tc_parent.id
 where tc.category_id = 1
 and tc.parent_id > 0
 and m.created_at >= start_date
 and cam.client_id = var_in_client_id
 group by tc.id, tc.value;
 CREATE TEMPORARY TABLE `t2` ( `item_count` int(11), PRIMARY KEY (`item_count`));
 insert into t2
 select sum(t.item_count)
 from t;
 select concat('(', t.tc_parent, ') ', t.tc_value) as sector,
 t1.item_count as meeting_count,
 t.item_count/t2.item_count as activity_percentage,
 t1.item_count/t.item_count as conversion_percentage
 from t2, t
 left join t1 on t.tc_id = t1.tc_id
 where round((t.item_count/t2.item_count)*100) > 0
 order by activity_percentage desc;
 DROP TABLE IF EXISTS `t`;
 DROP TABLE IF EXISTS `t1`;
 DROP TABLE IF EXISTS `t2`;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_detail_data`(	var_in_start datetime,
 var_in_end datetime,
 var_in_client_id int(11),
 var_in_project_ref text,
 var_in_effectives_filter int(11))
 BEGIN
 --

 --

 CASE var_in_effectives_filter WHEN 1 THEN

 CREATE TEMPORARY table t1
 SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications
 WHERE is_effective = 1
 AND communication_date >= var_in_start
 AND communication_date <= var_in_end;

 WHEN 2 THEN



 CREATE TEMPORARY table t1
 SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications
 WHERE is_effective = 0
 AND communication_date >= var_in_start
 AND communication_date <= var_in_end;

 WHEN 3 THEN



 CREATE TEMPORARY table t1
 SELECT id, post_initiative_id, communication_date, note_id FROM tbl_communications
 WHERE communication_date >= var_in_start
 AND communication_date <= var_in_end;

 END CASE;
 --

 --
 CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN
 CASE var_in_effectives_filter WHEN 1 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

 WHEN 2 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

 WHEN 3 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;

 END CASE;
 ELSE
 CASE var_in_effectives_filter WHEN 1 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
 WHEN 2 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
 WHEN 3 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;
 END CASE;
 END CASE;
 --

 --

 DROP TEMPORARY table t1;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_detail_data_order_by_company`(	var_in_start datetime,
 var_in_end datetime,
 var_in_client_id int(11),
 var_in_project_ref text,
 var_in_effectives_filter int(11))
 BEGIN
 --

 --

 CASE var_in_effectives_filter WHEN 1 THEN

 CREATE TEMPORARY table t1
 SELECT id, communication_date, note_id FROM tbl_communications
 WHERE is_effective = 1
 AND communication_date >= var_in_start
 AND communication_date <= var_in_end;

 WHEN 2 THEN



 CREATE TEMPORARY table t1
 SELECT id, communication_date, note_id FROM tbl_communications
 WHERE is_effective = 0
 AND communication_date >= var_in_start
 AND communication_date <= var_in_end;

 WHEN 3 THEN



 CREATE TEMPORARY table t1
 SELECT id, communication_date, note_id FROM tbl_communications
 WHERE communication_date >= var_in_start
 AND communication_date <= var_in_end;

 END CASE;
 --

 --
 CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN
 CASE var_in_effectives_filter WHEN 1 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY comp.name, comm.communication_date;

 WHEN 2 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY comp.name, comm.communication_date;

 WHEN 3 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 ORDER BY comp.name, comm.communication_date;

 END CASE;
 ELSE
 CASE var_in_effectives_filter WHEN 1 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY comp.name, comm.communication_date;
 WHEN 2 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY comp.name, comm.communication_date;
 WHEN 3 THEN



 SELECT pi.id AS post_initiative_id,
 comp.id AS company_id,
 comp.name AS company,
 cs1.sort_order,
 cs1.id AS status_id,
 cs1.description AS status,
 comm.communication_date AS date,
 pin.note AS note,
 cli.name AS client,
 post.id AS post_id,
 post.job_title,
 IFNULL(post.full_name, '') AS full_name,
 f_get_site_address(comp.id) AS address
 FROM tbl_post_initiatives as pi
 INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id
 INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id
 INNER JOIN tbl_companies AS comp ON post.company_id = comp.id
 INNER JOIN tbl_sites AS site ON comp.id = site.company_id
 INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id
 INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id
 INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id
 INNER JOIN tbl_tags AS t ON pit.tag_id = t.id
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 WHERE cli.id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 ORDER BY comp.name, comm.communication_date;
 END CASE;
 END CASE;
 --

 --

 DROP TEMPORARY table t1;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_notes_detail`(var_in_date datetime, var_in_post_initiative_id int(11), var_in_effectives_filter int(11))
 BEGIN
 CASE var_in_effectives_filter WHEN 1 THEN



 SELECT cs.description AS status,
 comm.communication_date AS date,
 pin.note AS note
 FROM tbl_post_initiative_notes AS pin
 LEFT JOIN tbl_communications AS comm ON comm.note_id = pin.id
 LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id
 WHERE pin.post_initiative_id = var_in_post_initiative_id
 AND comm.is_effective = 1
 AND pin.for_client = 1
 AND comm.communication_date <= var_in_date
 ORDER by pin.created_at DESC;
 WHEN 2 THEN



 CREATE TEMPORARY table t1
 SELECT pin.id, pin.post_initiative_id, pin.note AS note
 FROM tbl_post_initiative_notes AS pin
 WHERE pin.post_initiative_id = var_in_post_initiative_id
 AND pin.for_client = 1
 AND pin.created_at <= var_in_date;

 SELECT cs.description AS status,
 comm.communication_date AS date,
 pin.note AS note
 FROM tbl_communications AS comm
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id
 LEFT JOIN t1 ON comm.note_id = t1.id
 WHERE comm.post_initiative_id = var_in_post_initiative_id
 AND comm.communication_date <= var_in_date
 AND comm.is_effective = 0
 ORDER by comm.communication_date DESC;
 DROP TEMPORARY table t1;


 WHEN 3 THEN



 CREATE TEMPORARY table t1
 SELECT pin.id, pin.post_initiative_id, pin.note AS note
 FROM tbl_post_initiative_notes AS pin
 WHERE pin.post_initiative_id = var_in_post_initiative_id
 AND pin.for_client = 1
 AND pin.created_at <= var_in_date;

 SELECT cs.description AS status,
 comm.communication_date AS date,
 pin.note AS note
 FROM tbl_communications AS comm
 LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id
 LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id
 LEFT JOIN t1 ON comm.note_id = t1.id
 WHERE comm.post_initiative_id = var_in_post_initiative_id
 AND comm.communication_date <= var_in_date
 ORDER by comm.communication_date DESC;
 DROP TEMPORARY table t1;
 END CASE;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_5_summary_data`(	var_in_start datetime,
 var_in_end datetime,
 var_in_client_id int(11),
 var_in_project_ref text)
 BEGIN
 CASE var_in_project_ref IS NULL OR TRIM(var_in_project_ref) = '' WHEN true THEN

 CREATE TEMPORARY TABLE t1_rpt5
 SELECT cs.id AS status_id,
 COUNT(comm.id) AS effectives
 FROM tbl_lkp_communication_status AS cs
 LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id
 INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id
 LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 WHERE cam.client_id = var_in_client_id
 AND comm.communication_date >= var_in_start
 AND comm.communication_date <= var_in_end
 GROUP BY cs.id;



 CREATE TEMPORARY TABLE t2_rpt5
 SELECT cs.id AS status_id,
 COUNT(comm.id) AS non_effectives
 FROM tbl_lkp_communication_status AS cs
 LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id
 LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id
 LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 WHERE comm.is_effective = 0
 AND cam.client_id = var_in_client_id
 AND comm.communication_date >= var_in_start
 AND comm.communication_date <= var_in_end
 GROUP BY cs.id;
 ELSE

 CREATE TEMPORARY TABLE t1_rpt5
 SELECT cs.id AS status_id,
 COUNT(comm.id) AS effectives
 FROM tbl_lkp_communication_status AS cs
 LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id
 INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id
 LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id
 LEFT JOIN tbl_tags t on pit.tag_id = t.id
 WHERE cam.client_id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 AND comm.communication_date >= var_in_start
 AND comm.communication_date <= var_in_end
 GROUP BY cs.id;


 CREATE TEMPORARY TABLE t2_rpt5
 SELECT cs.id AS status_id,
 COUNT(comm.id) AS non_effectives
 FROM tbl_lkp_communication_status AS cs
 LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id
 LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id
 LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id
 LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id
 LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id
 LEFT JOIN tbl_tags t on pit.tag_id = t.id
 WHERE comm.is_effective = 0
 AND cam.client_id = var_in_client_id
 AND t.value = var_in_project_ref
 AND t.category_id = 3
 AND comm.communication_date >= var_in_start
 AND comm.communication_date <= var_in_end
 GROUP BY cs.id;
 END CASE;
 SELECT cs.id AS status_id, cs.description, cs.full_description,
 IFNULL(t1.effectives, 0) AS effectives,
 IFNULL(t2.non_effectives, 0) AS non_effectives
 FROM tbl_lkp_communication_status AS cs
 LEFT JOIN t1_rpt5 AS t1 ON cs.id = t1.status_id
 LEFT JOIN t2_rpt5 AS t2 ON cs.id = t2.status_id
 ORDER BY cs.sort_order DESC;
 DROP TEMPORARY table t1_rpt5;
 DROP TEMPORARY table t2_rpt5;
 END */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_7_rearranged_meetings`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 drop table if exists t1;
 create temporary table t1
 select ms.id
 from tbl_meetings_shadow ms
 join tbl_post_initiatives pi on pi.id = ms.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where ms.created_at < var_in_date_start
 and ms.shadow_timestamp >= var_in_date_start
 and ms.shadow_timestamp <= var_in_date_end
 AND c.client_id = var_in_client_id
 AND ms.status_id IN (18, 19)
 GROUP BY ms.id;
 SELECT m.id, CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category,
 EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`,
 lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment
 from t1
 join tbl_meetings m on t1.id = m.id
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id
 join vw_posts_contacts p on pi.post_id = p.id
 join tbl_companies co on co.id = p.company_id
 ORDER BY category DESC, `year_month`, date;
 drop table if exists t1;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Key_To_Terms`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 drop table if exists t;
 create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
 insert into t
 select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
 from tbl_communications com
 left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
 left join tbl_initiatives i on i.id = pi.initiative_id
 left join tbl_campaigns cam on i.campaign_id = cam.id
 left join tbl_clients cl on cam.client_id = cl.id
 where communication_date >= var_in_date_start
 and communication_date <= var_in_date_end
 and cl.id = var_in_client_id
 and com.type_id = 1
 group by com.post_initiative_id;
 update t set effective_count = 1 where effective_count > 1;
 drop table if exists t1;
 create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
 insert into t1
 select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
 from tbl_communications com
 join t on t.max_com_id = com.id
 group by com.status_id;
 select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
 from tbl_lkp_communication_status cs
 left join t1 on t1.status_id = cs.id
 order by cs.report_sort_order;
 drop temporary table t;
 drop temporary table t1;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Key_To_Terms_All`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
 begin
 drop table if exists t;
 create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
 if var_in_filter_id > 0 then
 insert into t
 select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
 from tbl_communications com
 left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 left join tbl_initiatives i on i.id = pi.initiative_id
 left join tbl_campaigns cam on i.campaign_id = cam.id
 left join tbl_clients cl on cam.client_id = cl.id
 where communication_date >= var_in_date_start
 and communication_date <= var_in_date_end
 and cl.id = var_in_client_id
 and com.type_id = 1
 and fr.filter_id = var_in_filter_id
 group by com.post_initiative_id;
 else
 insert into t
 select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
 from tbl_communications com
 left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
 left join tbl_initiatives i on i.id = pi.initiative_id
 left join tbl_campaigns cam on i.campaign_id = cam.id
 left join tbl_clients cl on cam.client_id = cl.id
 where communication_date >= var_in_date_start
 and communication_date <= var_in_date_end
 and cl.id = var_in_client_id
 and com.type_id = 1
 group by com.post_initiative_id;
 end if;
 update t set effective_count = 1 where effective_count > 1;
 drop table if exists t1;
 create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
 insert into t1
 select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
 from tbl_communications com
 join t on t.max_com_id = com.id
 group by com.status_id;
 select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
 from tbl_lkp_communication_status cs
 left join t1 on t1.status_id = cs.id
 order by cs.report_sort_order;
 drop temporary table t;
 drop temporary table t1;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Key_To_Terms_Only_Communications`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
 begin

 drop table if exists t;
 create temporary table t ( max_com_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `max_com_id`(`max_com_id`));
 if var_in_filter_id > 0 then
 insert into t
 select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
 from tbl_communications com
 left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 left join tbl_initiatives i on i.id = pi.initiative_id
 left join tbl_campaigns cam on i.campaign_id = cam.id
 left join tbl_clients cl on cam.client_id = cl.id
 where communication_date >= var_in_date_start
 and communication_date <= var_in_date_end
 and cl.id = var_in_client_id
 and com.type_id = 1
 and fr.filter_id = var_in_filter_id
 group by com.post_initiative_id;
 else
 insert into t
 select max(com.id) as max_com_id, count(com.id) as com_count, sum(is_effective) as effective_count, count(com.id) - sum(is_effective) as non_effective_count
 from tbl_communications com
 left join tbl_post_initiatives pi on pi.id = com.post_initiative_id
 left join tbl_initiatives i on i.id = pi.initiative_id
 left join tbl_campaigns cam on i.campaign_id = cam.id
 left join tbl_clients cl on cam.client_id = cl.id
 where communication_date >= var_in_date_start
 and communication_date <= var_in_date_end
 and cl.id = var_in_client_id
 and com.type_id = 1
 group by com.post_initiative_id;
 end if;
 update t set effective_count = 1 where effective_count > 1;
 drop table if exists t1;
 create temporary table t1 (status_id int(11), com_count int(11), effective_count int(11), non_effective_count int(11), key `status_id` (`status_id`));
 insert into t1
 select com.status_id, sum(t.com_count), sum(effective_count), sum(non_effective_count)
 from tbl_communications com
 join t on t.max_com_id = com.id
 group by com.status_id;
 select cs.description, cs.report_description, effective_count, com_count, non_effective_count, cs.report_break_after_line
 from tbl_lkp_communication_status cs
 join t1 on t1.status_id = cs.id
 where effective_count > 0
 order by cs.report_sort_order;
 drop temporary table t;
 drop temporary table t1;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Meetings_Reconciliation`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 drop table if exists  t_all_meets;
 create temporary table t_all_meets(
 meeting_id int(11),
 key `meeting_id`(`meeting_id`)
 );
 insert into t_all_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end;
 select count(*) from t_all_meets;
 drop table if exists  t_temp_meets;
 create temporary table t_temp_meets(
 meeting_id int(11),
 key `meeting_id`(`meeting_id`)
 );
 insert into t_temp_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id >= 24;
 insert into t_temp_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (12,13,18,19);
 insert into t_temp_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 WHERE
 m.date > var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 insert into t_temp_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date > var_in_date_start
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (14,15,16,17);
 insert into t_temp_meets
 select m.id
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (20,21,22,23);
 select count(*) from t_temp_meets;
 drop table if exists  t_meets_not_in_all_set;
 create temporary table t_meets_not_in_all_set(
 meeting_id int(11),
 key `meeting_id`(`meeting_id`)
 );
 insert into t_meets_not_in_all_set
 select tm. meeting_id
 from t_temp_meets tm left join t_all_meets am on tm. meeting_id = am. meeting_id
 where am. meeting_id is null
 group by tm.meeting_id;
 select m.* from tbl_meetings m join t_meets_not_in_all_set t on t.meeting_id = m.id
 order by t.meeting_id;
 drop table if exists  t_meets_set_not_in_any_status;
 create temporary table t_meets_set_not_in_any_status(
 meeting_id int(11),
 key `meeting_id`(`meeting_id`)
 );
 insert into t_meets_set_not_in_any_status
 select am. meeting_id
 from t_all_meets am left join t_temp_meets tm on tm. meeting_id = am. meeting_id
 where tm. meeting_id is null
 group by am.meeting_id;
 select m.* from tbl_meetings m join t_meets_set_not_in_any_status t on t.meeting_id =  m.id
 order by t.meeting_id;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_period_results_calls`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11), var_in_filter_id int(11))
 begin
 DECLARE filter_format varchar(100);
 DROP TABLE IF EXISTS `t_meetings`;
 CREATE TEMPORARY TABLE `t_meetings` (
 `meeting_id` int(11),
 INDEX `ix_meeting_id` (meeting_id));
 insert into t_meetings
 select max(id)
 from tbl_meetings
 where status_id >= 12
 group by post_initiative_id;
 DROP TABLE IF EXISTS `t_meetings_1`;
 CREATE TEMPORARY TABLE `t_meetings_1` (
 `post_initiative_id` int(11),
 `meeting_id` int(11),
 `meeting_date` datetime,
 INDEX `ix_post_initiative_id` (post_initiative_id),
 INDEX `ix_meeting_id` (meeting_id));
 insert into t_meetings_1
 select post_initiative_id, id, date
 from tbl_meetings
 join t_meetings on tbl_meetings.id = t_meetings.meeting_id;
 DROP TABLE IF EXISTS `t_post_initiatives`;
 CREATE TEMPORARY TABLE `t_post_initiatives` (
 `post_initiative_id` int(11),
 INDEX `ix_post_initiative_id` (post_initiative_id));
 if var_in_filter_id > 0 then
 BEGIN
 insert into t_post_initiatives
 select pi.id
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 where
 com.type_id = 1
 and com.is_effective = 1
 and com.communication_date >= var_in_date_start
 and com.communication_date <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by pi.id;
 SELECT results_format INTO filter_format
 FROM tbl_filters
 WHERE id = var_in_filter_id;
 CASE filter_format
 WHEN 'Company' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on c.id = fr.company_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;

 WHEN 'Client initiative with last note' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;
 WHEN 'Client initiative' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;
 WHEN 'Company and posts' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on p.id = fr.post_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;
 WHEN 'Mailer' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;
 WHEN 'Meeting' THEN
 BEGIN
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 join tbl_filter_results fr on pi.id = fr.post_initiative_id
 where fr.filter_id = var_in_filter_id
 order by cs.report_sort_order, c.name;
 END;
 ELSE
 BEGIN
 END;
 END CASE;
 END;
 else
 BEGIN
 insert into t_post_initiatives
 select pi.id
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 where
 com.type_id = 1
 and com.is_effective = 1
 and com.communication_date >= var_in_date_start
 and com.communication_date <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by pi.id;
 select pi.id, c.id, c.name as company_name, p.job_title, concat(con.first_name, ' ',con.surname) as prospect_name, cs.description as status,
 pi.comment, date_format(meeting_date, '%d/%m/%y') as meeting_date, cl.name as next_action_by, date_format(next_communication_date, '%d/%m/%y') as next_communication_date
 from tbl_post_initiatives pi
 join t_post_initiatives t on t.post_initiative_id = pi.id
 join vw_posts p on p.id = pi.post_id
 join tbl_companies c on p.company_id = c.id
 join vw_contacts con on con.post_id = p.id
 join tbl_lkp_communication_status cs on cs.id = pi.status_id
 left join t_meetings_1 m on m.post_initiative_id = pi.id
 join tbl_clients cl on cl.id = pi.next_action_by
 order by cs.report_sort_order, c.name;
 END;
 end if;
 drop table if exists `t_meetings`;
 drop table if exists `t_meetings_1`;
 drop table if exists `t_post_initiatives`;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`malvern`@`81.140.67.147`*/
/*!50003 PROCEDURE `sp_report_8_sector_penetration`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 declare tmp int(11);
 DROP TABLE IF EXISTS `t_companies_with_calls`;
 CREATE TEMPORARY TABLE `t_companies_with_calls` (
 `company_id` int(11),
 `sector_id` int(11),
 `calls` int(11),
 `effectives` int(11),
 INDEX `ix_company_id` (`company_id`),
 INDEX `ix_sector_id` (sector_id));
 insert into t_companies_with_calls
 select p.company_id, tc.id, count(distinct com.id) as calls, sum(com.is_effective) as effectives
 from tbl_communications com
 join tbl_post_initiatives pi on com.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 join tbl_posts p on pi.post_id = p.id
 join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id
 join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id
 where
 com.type_id = 1
 and tc.category_id = 1
 and tc.parent_id = 0
 and com.communication_date >= var_in_date_start
 and com.communication_date <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by p.company_id, tc.id;
 DROP TABLE IF EXISTS `t_company_meetings`;
 CREATE TEMPORARY TABLE `t_company_meetings` (
 `company_id` int(11),
 `meetings_set` int(11),
 INDEX `ix_company_id` (`company_id`));
 insert into t_company_meetings
 select p.company_id, count(distinct m.id) as meetings
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_posts p on p.id = pi.post_id
 join t_companies_with_calls cwc on cwc.company_id = p.company_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns cam on i.campaign_id = cam.id
 where m.created_at >= var_in_date_start
 and m.created_at <= var_in_date_end
 and cam.client_id = var_in_client_id
 group by p.company_id;
 DROP TABLE IF EXISTS `t_companies_with_effectives_and_weighting`;
 CREATE TEMPORARY TABLE `t_companies_with_effectives_and_weighting` (
 `company_id` int(11),
 `sector_id` int(11),
 `weighting` int(11),
 `calls` int(11),
 `effectives` int(11),
 `meetings_set` int(11),
 INDEX 	`ix_company_id` (`company_id`),
 INDEX `ix_sector_id` (sector_id),
 INDEX `ix_weighting` (weighting),
 INDEX `ix_calls` (calls),
 INDEX `ix_effectives` (effectives),
 INDEX `ix_meetings_set` (meetings_set)
 );
 insert into t_companies_with_effectives_and_weighting
 select t_cwe.company_id, t_cwe.sector_id, cs.weighting, t_cwe.calls, t_cwe.effectives, t_cm.meetings_set
 from t_companies_with_calls t_cwe
 left join t_company_meetings t_cm on t_cm.company_id = t_cwe.company_id
 left join tbl_campaign_sectors cs on cs.tiered_characteristic_id = t_cwe.sector_id  and cs.campaign_id = var_in_client_id;
 DROP TABLE IF EXISTS `t_company_max_sector_id`;
 CREATE TEMPORARY TABLE `t_company_max_sector_id` (
 `company_id` int(11),
 `sector_id` int(11),
 `weighting` int(11),
 INDEX 	`ix_company_id` (`company_id`),
 INDEX `ix_sector_id` (sector_id),
 INDEX `ix_weighting` (weighting)
 );
 set @num := 0, @company_id := '';
 insert into t_company_max_sector_id
 select company_id, sector_id, weighting
 from (
 select company_id, sector_id, weighting,
 @num := if(@company_id = company_id, @num + 1, 1) as row_number,
 @company_id := company_id as dummy
 from t_companies_with_effectives_and_weighting
 order by company_id, weighting desc, sector_id
 ) as x where x.row_number <= 1
 order by company_id;
 DROP TABLE IF EXISTS `t_target_sector_stats`;
 CREATE TEMPORARY TABLE `t_target_sector_stats` (
 `sector_id` int(11),
 `calls` int(11),
 `effectives` int(11),
 `meetings_set` int(11),
 `weighting` int(11),
 INDEX `ix_sector_id` (sector_id),
 INDEX `ix_calls` (calls),
 INDEX `ix_effectives` (effectives),
 INDEX `ix_meetings_set` (meetings_set),
 INDEX `ix_weighting` (weighting)
 );
 insert into t_target_sector_stats
 select t_cwew.sector_id, sum(t_cwew.calls),  sum(t_cwew.effectives), sum(t_cwew.meetings_set), min(t_cwew.weighting)
 from t_company_max_sector_id t_cms
 join t_companies_with_effectives_and_weighting t_cwew on t_cwew.company_id = t_cms.company_id and t_cwew.sector_id = t_cms.sector_id
 where t_cwew.weighting is not null
 group by t_cwew.sector_id
 limit 10;
 insert into t_target_sector_stats
 select null, sum(t_cwew.calls),  sum(t_cwew.effectives), sum(t_cwew.meetings_set), min(t_cwew.weighting)
 from t_company_max_sector_id t_cms
 join t_companies_with_effectives_and_weighting t_cwew on t_cwew.company_id = t_cms.company_id and t_cwew.sector_id = t_cms.sector_id
 where t_cwew.weighting is null
 group by t_cwew.weighting;
 set @total_calls := 0;
 select sum(calls) INTO @total_calls
 from t_target_sector_stats;
 select tc.value as sector, t_ts.calls,  ROUND((t_ts.calls/@total_calls * 100),1) as call_percentage, t_ts.meetings_set, ROUND((t_ts.effectives / t_ts.calls * 100),1) as access, ROUND((t_ts.meetings_set / t_ts.effectives * 100),1) as conversion
 from t_target_sector_stats t_ts
 left join tbl_tiered_characteristics tc on tc.id = t_ts.sector_id
 order by t_ts.weighting desc;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Summary_Figures_For_Campaign`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin

 DECLARE end_year_month varchar(6);
 DECLARE meets_set_target_to_date INT;
 DECLARE meets_attended_target_to_date INT;
 DECLARE meets_set_to_date INT;
 DECLARE meets_attended_to_date INT;
 DECLARE meets_potential_attended INT;
 DECLARE meets_in_diary INT;
 DECLARE meets_lapsed_tbr INT;
 DECLARE meets_lapsed_cancelled INT;
 DECLARE tmp INT;
 DECLARE strong_callbacks INT;
 select 0 into strong_callbacks;
 select count(pi.id) INTO tmp
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and status_id in (4,5,6);
 select strong_callbacks + tmp INTO strong_callbacks;
 drop temporary table if exists t_comm_max;
 create temporary table t_comm_max (
 post_initiative_id int(11),
 comm_id int(11),
 key `post_initiative_id`(`post_initiative_id`),
 key `comm_id`(`comm_id`)
 );
 insert into t_comm_max
 select pi.id as post_initiative_id, max(comm.id)
 from tbl_communications comm
 join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and comm.communication_date <= var_in_date_end
 and pi.status_id not in (4,5,6,12,8,9,10)
 group by pi.id;
 drop temporary table if exists t_strong_callbacks;
 create temporary table t_strong_callbacks (
 post_initiative_id int(11),
 key `post_initiative_id`(`post_initiative_id`)
 );
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > var_in_date_end;
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 delete from t_strong_callbacks;
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 drop temporary table if exists t;
 select sum(meetings_set) INTO meets_set_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meetings_attended) INTO meets_attended_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(m.id)
 INTO meets_set_to_date
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end;
 select count(m.id)
 INTO meets_attended_to_date
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m.date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.created_at >= var_in_date_start
 and m.created_at <= var_in_date_end
 and m.status_id >= 24;
 select count(m.id)
 INTO meets_potential_attended
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m.date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.created_at >= var_in_date_start
 and m.created_at <= var_in_date_end
 and m.status_id in (12,13,18,19);
 select count(m.id) INTO meets_in_diary
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 WHERE
 m.date > var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select count(m.id)
 INTO meets_lapsed_tbr
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date > var_in_date_start
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (14,15,16,17);
 select count(m.id)
 INTO meets_lapsed_cancelled
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (20,21,22,23);
 create temporary table t (
 meets_set_target_to_date int(11),
 meets_attended_target_to_date int(11),
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_in_diary int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11),
 strong_callbacks_pipeline int(11)
 );
 insert into t (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_in_diary,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks_pipeline)
 values (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_in_diary,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Summary_Figures_For_Campaign_dev`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin

 DECLARE end_year_month varchar(6);
 DECLARE meets_set_target_to_date INT;
 DECLARE meets_attended_target_to_date INT;
 DECLARE meets_set_to_date INT;
 DECLARE meets_attended_to_date INT;
 DECLARE meets_potential_attended INT;
 DECLARE meets_live INT;
 DECLARE meets_lapsed_tbr INT;
 DECLARE meets_lapsed_cancelled INT;
 DECLARE tmp INT;
 DECLARE strong_callbacks INT;
 select 0 into strong_callbacks;
 select count(pi.id) INTO tmp
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and status_id in (4,5,6);
 select strong_callbacks + tmp INTO strong_callbacks;
 drop temporary table if exists t_comm_max;
 create temporary table t_comm_max (
 post_initiative_id int(11),
 comm_id int(11),
 key `post_initiative_id`(`post_initiative_id`),
 key `comm_id`(`comm_id`)
 );
 insert into t_comm_max
 select pi.id as post_initiative_id, max(comm.id)
 from tbl_communications comm
 join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and comm.communication_date <= var_in_date_end
 and pi.status_id not in (4,5,6,12,8,9,10)
 group by pi.id;
 drop temporary table if exists t_strong_callbacks;
 create temporary table t_strong_callbacks (
 post_initiative_id int(11),
 key `post_initiative_id`(`post_initiative_id`)
 );
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > var_in_date_end;
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 delete from t_strong_callbacks;
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 drop temporary table if exists t;
 select sum(meetings_set) INTO meets_set_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meetings_attended) INTO meets_attended_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(m.id)
 INTO meets_set_to_date
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end;
 select count(m.id)
 INTO meets_attended_to_date
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id >= 24;
 select count(m.id)
 INTO meets_potential_attended
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (12,13,18,19);
 select count(m.id) INTO meets_live
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 WHERE
 m.date > var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select count(m.id)
 INTO meets_lapsed_tbr
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date > var_in_date_start
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (14,15,16,17);
 select count(m.id)
 INTO meets_lapsed_cancelled
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end
 and m.status_id in (20,21,22,23);
 create temporary table t (
 meets_set_target_to_date int(11),
 meets_attended_target_to_date int(11),
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_live int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11),
 strong_callbacks_pipeline int(11)
 );
 insert into t (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks_pipeline)
 values (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Summary_Figures_For_Current_Month`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE end_year_month varchar(6);
 DECLARE meets_set_to_date INT;
 DECLARE meets_attended_to_date INT;
 DECLARE meets_potential_attended INT;
 DECLARE meets_live INT;
 DECLARE meets_lapsed_tbr INT;
 DECLARE meets_lapsed_cancelled INT;
 DECLARE strong_callbacks INT;
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 select sum(meeting_set_count) INTO meets_set_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_attended_count) INTO meets_attended_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_unknown_count) INTO meets_potential_attended
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(m.id) INTO meets_live
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date > var_in_date_end
 and c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_cancelled_count) INTO meets_lapsed_cancelled
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(pi.id) INTO strong_callbacks
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where next_communication_date > var_in_date_end
 and c.client_id = var_in_client_id
 AND pi.status_id in (4,5,6);
 create temporary table t (
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_live int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11),
 strong_callbacks int(11)
 );
 insert into t (
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks)
 values (
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled,
 strong_callbacks);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Summary_Figures_For_Period`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE meets_set INT;
 DECLARE meets_in_diary INT;
 DECLARE strong_callbacks INT;
 DECLARE meets_attended INT;
 DECLARE meets_awaiting_feedback INT;
 DECLARE meets_tbr INT;
 DECLARE meets_cancelled INT;
 DECLARE tmp INT;
 select 0 into strong_callbacks;
 select count(pi.id) INTO tmp
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and status_id in (4,5,6);
 select strong_callbacks + tmp INTO strong_callbacks;
 drop temporary table if exists t_comm_max;
 create temporary table t_comm_max (
 post_initiative_id int(11),
 comm_id int(11),
 key `post_initiative_id`(`post_initiative_id`),
 key `comm_id`(`comm_id`)
 );
 insert into t_comm_max
 select pi.id as post_initiative_id, max(comm.id)
 from tbl_communications comm
 join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and comm.communication_date <= var_in_date_end
 and pi.status_id not in (4,5,6,12,8,9,10)
 group by pi.id;
 drop temporary table if exists t_strong_callbacks;
 create temporary table t_strong_callbacks (
 post_initiative_id int(11),
 key `post_initiative_id`(`post_initiative_id`)
 );
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > var_in_date_end;
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 delete from t_strong_callbacks;
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 select count(m.id)
 INTO meets_set
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. created_at >= var_in_date_start
 and m. created_at <= var_in_date_end;
 select count(m.id)
 INTO meets_attended
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.status_id >= 24;
 select count(m.id)
 INTO meets_awaiting_feedback
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where
 c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.status_id in (12,13,18,19);
 select count(m.id) INTO meets_in_diary
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 WHERE
 m.date > var_in_date_end
 AND c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select count(m.id)
 INTO meets_tbr
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date > var_in_date_start
 and m.status_id in (14,15,16,17);
 select count(m.id)
 INTO meets_cancelled
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.status_id in (20,21,22,23);
 drop temporary table if exists t;
 create temporary table t (
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_in_diary int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11),
 strong_callbacks_generated int(11)
 );
 insert into t
 select
 meets_set,
 meets_attended,
 meets_awaiting_feedback,
 meets_in_diary as meets_in_diary,
 meets_tbr,
 meets_cancelled,
 strong_callbacks;
 select * from t;
 drop temporary table if exists t;
 drop temporary table if exists t_comm_max;
 drop temporary table if exists t_strong_callbacks;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Summary_Figures_For_Period_dev`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE meets_in_diary INT;
 DECLARE strong_callbacks INT;
 DECLARE meets_attended INT;
 DECLARE meets_awaiting_feedback INT;
 DECLARE meets_tbr INT;
 DECLARE tmp INT;
 select count(m.id) INTO meets_in_diary
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 WHERE
 m.date > var_in_date_end
 AND c.client_id = var_in_client_id
 and is_current = 1
 AND m.status_id in (12, 13, 18, 19);
 select 0 into strong_callbacks;
 select count(pi.id) INTO tmp
 from tbl_post_initiatives pi
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and status_id in (4,5,6);
 select strong_callbacks + tmp INTO strong_callbacks;
 drop temporary table if exists t_comm_max;
 create temporary table t_comm_max (
 post_initiative_id int(11),
 comm_id int(11),
 key `post_initiative_id`(`post_initiative_id`),
 key `comm_id`(`comm_id`)
 );
 insert into t_comm_max
 select pi.id as post_initiative_id, max(comm.id)
 from tbl_communications comm
 join tbl_post_initiatives pi on comm.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where c.client_id = var_in_client_id
 and comm.communication_date <= var_in_date_end
 and pi.status_id not in (4,5,6,12,8,9,10)
 group by pi.id;
 drop temporary table if exists t_strong_callbacks;
 create temporary table t_strong_callbacks (
 post_initiative_id int(11),
 key `post_initiative_id`(`post_initiative_id`)
 );
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > var_in_date_end;
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 delete from t_strong_callbacks;
 insert into t_strong_callbacks
 select comm.post_initiative_id as post_initiative_id
 from tbl_communications comm
 join t_comm_max t_cm on comm.id = t_cm.comm_id
 where comm.next_communication_date_reason_id = 1
 and comm.next_communication_date > date_sub(var_in_date_start, INTERVAL -2 MONTH);
 select count(post_initiative_id) INTO tmp
 from t_strong_callbacks;
 select strong_callbacks + tmp INTO strong_callbacks;
 select count(m.id)
 INTO meets_attended
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where c.client_id = var_in_client_id
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.status_id >= 24;
 select count(m.id)
 INTO meets_awaiting_feedback
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date >= var_in_date_start
 and m.date <= var_in_date_end
 and m.status_id in (12,13,18,19)
 and is_current = 1;
 select count(m.id)
 INTO meets_tbr
 from tbl_meetings m
 join tbl_post_initiatives pi on m.post_initiative_id = pi.id
 join tbl_initiatives i on pi.initiative_id = i.id
 left join tbl_lkp_communication_status cs on m.status_id = cs.id
 where
 i.campaign_id = 1
 and m. date > var_in_date_start
 and m.status_id in (14,15,16,17)
 and is_current = 1;
 drop temporary table if exists t;
 create temporary table t (
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_in_diary int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11),
 strong_callbacks_generated int(11)
 );
 insert into t
 select
 sum(meeting_set_count) AS meeting_set_count,
 meets_attended,
 meets_awaiting_feedback,
 meets_in_diary as meets_in_diary,
 meets_tbr,
 sum(meeting_category_cancelled_count) AS meets_lapsed_cancelled,
 strong_callbacks
 from
 tbl_data_statistics_daily ds
 join tbl_campaigns cam on ds.campaign_id = cam.id
 WHERE date >= var_in_date_start
 AND date <= var_in_date_end
 AND cam.client_id = var_in_client_id;
 select * from t;
 drop temporary table if exists t;
 drop temporary table if exists t_comm_max;
 drop temporary table if exists t_strong_callbacks;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

/*!50003 SET SESSION SQL_MODE=""*/
;

;

/*!50003 CREATE*/
/*!50020 DEFINER=`root`@`localhost`*/
/*!50003 PROCEDURE `sp_report_8_Topline_Summary`(var_in_date_start datetime, var_in_date_end datetime, var_in_client_id int(11))
 begin
 DECLARE end_year_month varchar(6);
 DECLARE meets_set_target_to_date INT;
 DECLARE meets_attended_target_to_date INT;
 DECLARE meets_set_to_date INT;
 DECLARE meets_attended_to_date INT;
 DECLARE meets_potential_attended INT;
 DECLARE meets_live INT;
 DECLARE meets_lapsed_tbr INT;
 DECLARE meets_lapsed_cancelled INT;
 SET end_year_month = EXTRACT(YEAR_MONTH FROM var_in_date_end);
 drop temporary table if exists t;
 select sum(meetings_set) INTO meets_set_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meetings_attended) INTO meets_attended_target_to_date
 from tbl_campaign_targets
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_set_count) INTO meets_set_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_attended_count) INTO meets_attended_to_date
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_unknown_count) INTO meets_potential_attended
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select count(m.id) INTO meets_live
 from tbl_meetings m
 join tbl_post_initiatives pi on pi.id = m.post_initiative_id
 join tbl_initiatives i on pi.initiative_id = i.id
 join tbl_campaigns c on c.id = i.campaign_id
 where m.date > var_in_date_end
 and c.client_id = var_in_client_id
 AND m.status_id in (12, 13, 18, 19);
 select sum(meeting_category_tbr_count) INTO meets_lapsed_tbr
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 select sum(meeting_category_cancelled_count) INTO meets_lapsed_cancelled
 from tbl_data_statistics
 where campaign_id = var_in_client_id
 and `year_month` <= end_year_month;
 create temporary table t (
 meets_set_target_to_date int(11),
 meets_attended_target_to_date int(11),
 meets_set_to_date int(11),
 meets_attended_to_date int(11),
 meets_potential_attended int(11),
 meets_live int(11),
 meets_lapsed_tbr int(11),
 meets_lapsed_cancelled int(11)
 );
 insert into t (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled)
 values (
 meets_set_target_to_date,
 meets_attended_target_to_date,
 meets_set_to_date,
 meets_attended_to_date,
 meets_potential_attended,
 meets_live,
 meets_lapsed_tbr,
 meets_lapsed_cancelled);
 select * from t;
 drop temporary table t;
 end */
;

;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/
;

;

DELIMITER;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */
;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;

-- Dump completed on 2014-11-27 18:01:31