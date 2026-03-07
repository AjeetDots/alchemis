<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Region.php');
require_once('app/mapper/RegionMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_Region objects.
 * @package alchemis
 */
class app_command_AjaxRegion extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'get_postcodes_start_with':
				$results = app_domain_Region::findPostcodesStartWith($this->request->search_item);
				$this->request->results = $this->getPostcodeResults($results);
				break;
			case 'delete_region':
				$region = app_domain_Region::find($this->request->item_id);
				$region->markDeleted();
				$region->commit();
				break;
			case 'delete_region_postcode':
				$region = app_domain_Region::find($this->request->region_id);
				$region->deletePostcode($this->request->postcode_id);
				break;
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}
	
	protected function getPostcodeResults($results)
	{
		
		$return_data = array();
		$postcodes = $results;

		foreach ($postcodes as $postcode)
		{
			$return_data[] = array(	'id' 		=> $postcode['id'],
									'postcode'	=> $postcode['postcode']);
		}
		
		return $return_data;
	}
	

	
}

?>