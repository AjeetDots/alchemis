<?php

/**
 * Defines the app_domain_StatisticsReader class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReaderObject.php');
require_once('app/mapper/StatisticsReaderMapper.php');

/**
 * @package Alchemis
 */
class app_domain_StatisticsReader extends app_domain_ReaderObject
{
	/**
	 * By declaring private, we prevent instatiation by other objects.
	 */
	protected function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * @param MDB2_Result $result 
	 * @param app_mapper_Mapper $mapper
	 */
	protected static function init_db(MDB2_Result $result)
	{
		$raw = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw;
	}

	/**
	 * Returns call statistics for a given year_month period for a user
	 * @param integer $user_id
	 * @param string $year_month
	 */
	 public static function findCallsByUserIdAndYearMonth($user_id, $year_month)
	{
		$finder = self::getReader(__CLASS__);
		return $finder->findCallsByUserIdAndYearMonth($user_id, $year_month);
	}
	


}

?>