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
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		
		switch (isset($this->request->cmd_action) ? $this->request->cmd_action : null)
		{
			case 'get_postcodes_start_with':
				$results = app_domain_Region::findPostcodesStartWith(isset($this->request->search_item) ? $this->request->search_item : '');
				$this->request->results = $this->getPostcodeResults($results);
				break;
			case 'delete_region':
				$region_id = isset($this->request->item_id) ? $this->request->item_id : null;
				$region = app_domain_Region::find($region_id);
				if ($region) {
					$campaign_link_count = app_domain_Region::findCampaignLinkCount($region->getId());
					if ($campaign_link_count > 0) {
						array_push(
							$this->response->warnings,
							'Cannot delete region "' . $region->getName() . '" because it is assigned to ' . $campaign_link_count . ' campaign(s).'
						);
					} else {
						$region->markDeleted();
						$region->commit();
					}
				}
				break;
			case 'delete_region_postcode':
				$region = app_domain_Region::find(isset($this->request->region_id) ? $this->request->region_id : null);
				if ($region) {
					$region->deletePostcode(isset($this->request->postcode_id) ? $this->request->postcode_id : null);
				}
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