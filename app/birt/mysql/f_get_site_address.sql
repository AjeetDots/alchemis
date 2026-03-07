DROP FUNCTION IF EXISTS alchemis.f_get_site_address;

DELIMITER |


CREATE FUNCTION alchemis.f_get_site_address(var_in_company_id INT(11)) RETURNS char(255) CHARSET latin1
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

END |

DELIMITER ;