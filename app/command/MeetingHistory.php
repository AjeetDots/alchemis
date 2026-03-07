<?php

/**
 * Defines the app_command_MeetingHistory class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
//require_once('app/domain/PaginateHelper.php');

/**
 * @package Alchemis
 */
class app_command_MeetingHistory extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$paginate = new app_domain_PaginateHelper($request);
//		$paginate->setTotal(app_domain_Product::count());
//		$collection = app_domain_Product::findSet($paginate->getLimit(), $paginate->getOffset());
//		$request->setObject('products', $collection);
//		$request->setObject('paginate', $paginate);
//		return self::statuses('CMD_OK');

		if ($meeting_id = $request->getProperty('meeting_id'))
		{
			$collection = app_domain_Meeting::findHistory($meeting_id);
			$request->setObject('history', $collection);
			return self::statuses('CMD_OK');
		}
		else
		{
			die('meeting_id not supplied');
		}
	}
}

?>