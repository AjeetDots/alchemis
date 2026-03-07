<?php

/**
 * Defines the app_command_CharacteristicEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Characteristic.php');
require_once('app/domain/CharacteristicElement.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_CharacteristicEdit extends app_command_Command
{
	protected $debug = false;
	
	public function doExecute(app_controller_Request $request)
	{
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($request);
		if ($this->debug) echo "</pre>";

		$task = $request->getProperty('task');

		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();

		$request->setObject('user', $user);
		$client_initiatives = app_domain_CampaignNbm::findCampaignInitiativesByUserId($user['id']);
		$client_initiatives = array_map(function ($item) {
			$init = app_model_Initiatives::find($item["initiative_id"]);
			$item['campaign_id'] = $init->campaign_id;
			return $item;
		}, $client_initiatives);
		$request->setObject('client_initiatives', $client_initiatives);

		$characteristic_id = $request->getProperty('id');
		$campaignCharacteristics = app_model_CampaignCharacteristic::where('characteristic_id', '=', $characteristic_id)->get();
		$campCharList = $campaignCharacteristics->lists('campaign_id');
		$request->setObject('campaignCharacteristics', $campaignCharacteristics);


		if ($task == 'save')
		{
			if (!$this->getElementErrors())
			{
				if ($this->debug) echo "<p>should now save them</p>";
				
				$elements = $this->getPostedElements();

				$obj = new app_domain_Characteristic($request->getProperty('id'));
				$obj->setName($request->getProperty('name'));
				$obj->setDescription($request->getProperty('description'));
				$obj->setType($request->getProperty('type'));
				$obj->setAttributes($request->getProperty('attributes'));
				$obj->setOptions($request->getProperty('options'));
				$obj->setMultipleSelect($request->getProperty('multiple_select'));

				if (!$request->getProperty('attributes') && !$request->getProperty('options'))
				{
					// not a multiple-element characteristic
					if ($this->debug) echo "<p>No attributes or options characteristic</p>";

					$obj->setDataType($request->getProperty('data_type'));
					$obj->commit();

				}
				else
				{
					// is a multiple-element characteristic
					if ($this->debug) echo "<p>Is a multiple-element characteristic</p>";

					$obj->setDataType(null);
					$obj->commit();

					// Get elements
					if ($elements)
					{
						if ($this->debug) echo '<div style="border: 2px solid green"><p><b>Process posted elements</b></p>';
						foreach ($elements as &$element)
						{
							if ($this->debug) echo '<hr />';
							$ele = new app_domain_CharacteristicElement($element['id']);
							$ele->setCharacteristic(new app_domain_Characteristic($request->getProperty('id')));
							$ele->setDataType($element['data_type']);
							$ele->setName($element['name']);
							$ele->setSort($element['sort']);
							if ($this->debug) echo "<pre>";
							if ($this->debug) print_r($ele);
							if ($this->debug) echo "</pre>";
							$ele->commit();
							$element['id'] = $ele->getId();
						}
						if ($this->debug) echo '</div>';
					}
				}

				$initiatives = filter_input(INPUT_POST, 'selected_initiatives', FILTER_SANITIZE_STRING);
				$postedInits = explode(',', $initiatives);
				$deletes = array_diff($campCharList, $postedInits);

				array_map(function ($id) use ($characteristic_id) {
//					$initiative = app_model_Initiatives::with('campaign')->find($id);
					app_model_CampaignCharacteristic::where('characteristic_id', '=', $characteristic_id)->where('campaign_id', '=', $id)->delete();
				}, $deletes);

				foreach ($postedInits as $init) {
					if (!empty($init) && !in_array($init, $campCharList)) {
						$campaignCharacteristic = new app_model_CampaignCharacteristic();
						$campaignCharacteristic->campaign_id = $init;
						$campaignCharacteristic->characteristic_id = $request->getProperty('id');
						$campaignCharacteristic->save();
					}
				}
				
				if (isset($elements))
				{
					// Delete any removed resources
					$this->deleteElements($request->getProperty('id'), $elements);
				}
				
				$this->init($request);
				$request->addFeedback('Save Successful');
				$campaignCharacteristics = app_model_CampaignCharacteristic::where('characteristic_id', '=', $characteristic_id)->get();
				$request->setObject('campaignCharacteristics', $campaignCharacteristics);

				return self::statuses('CMD_OK');
			}
			else
			{
				// there were errors so make sticky
				$characteristic_id = $request->getProperty('id');
				$characteristic = app_domain_Characteristic::find($characteristic_id);
				$request->setObject('characteristic', $characteristic);
				$request->setObject('elements', $this->getPostedElements());
			}
		}
		else
		{
			$this->init($request);
		}
	}


	function init(app_controller_Request $request)
	{
		$characteristic_id = $request->getProperty('id');
		$characteristic = app_domain_Characteristic::find($characteristic_id);
		$request->setObject('characteristic', $characteristic);
		$request->setObject('elements', $this->populateElements($characteristic));
	}

	/**
	 * Returns a more useable array of the posted elements.
	 * @return array
	 */
	function getPostedElements()
	{
		if ($this->debug) echo "<p><b>app_command_CharacteristicEdit::getPostedElements()</b></p>";
		$items = array();
		
		// Identify the number of items by getting the working numbers
		$working_ids = array();
		foreach ($_POST as $key => $value)
		{
			if (preg_match('/element_name_\d+/', $key))
			{
				$working_ids[] = str_replace('element_name_', '', $key);
			}
		}
		
		$i = 1;
		// For each number, construct an array
		foreach ($working_ids as $working_id)
		{
			if ($_POST['element_dbId_' . $working_id] != '')
			{
				$id = $_POST['element_dbId_' . $working_id];
			}
			else
			{
				$id = null;
			}
			
			if (isset($_POST['element_data_type_' . $working_id]))
			{
				$data_type =$_POST['element_data_type_' . $working_id];
			}
			else
			{
				$data_type = 'boolean';
			}

			$items[] = array(	'id'        => $id,
								'name'      => $_POST['element_name_' . $working_id],
								'value'     => $_POST['element_name_' . $working_id],
								'sort'      => $i++,
					'data_type' => $data_type);
		}
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($items);
		if ($this->debug) echo "</pre>";
		return $items;	
	}

	/**
	 * Check the posted elements for errors.
	 * @return array
	 */
	function getElementErrors()
	{
		$errors = array();
//		if ($resources = getPostedResources())
//		{
//			$names = array();
//			foreach ($resources as $resource)
//			{
//				if (!trim($resource['name']))
//				{
//					$errors[] = new Error("name", TERM_RISK_OWNER . " cannot be blank.");
//				}
//				elseif(strlen(trim($resource['name'])) > 100)
//				{
//					$errors[] = new Error("name", TERM_RISK_OWNER . " can have a maximum length of 100 characters.");
//				}
//				elseif (Resource::resourceNameExists($resource['name'], $resource['id']) || in_array($resource['name'], $names))
//				{
//					$errors[] = new Error('resource', 
//									  		"Name must be unique.", 
//											"An " . strtolower(TERM_RISK_OWNER) . " with the name '" . $resource['name'] . "' already exists. Each " . strtolower(TERM_RISK_OWNER) . " name must be unique.");
//				}
//				$names[] = $resource['name'];
//			}
//		}
		return $errors;
	}

	/**
	 * Delete any previously existing resource which have been removed by the user.
	 * @param integer $characteristic_id
	 * @param array $postedElements array of posted elements
	 */
	function deleteElements($characteristic_id, $postedElements = array())
	{
		if ($this->debug) echo '<div style="border: 2px solid red"><p><b>Delete removed elements</b></p>';
		$dbElements = app_domain_CharacteristicElement::findByCharacteristicId($characteristic_id);
		
		if (!is_null($dbElements) && $dbElements->count() > 0)
		{
			if ($this->debug) echo "<pre>";
			if ($this->debug) print_r($postedElements);
			if ($this->debug) echo "</pre>";
			
			foreach ($dbElements as $dbElement)
			{
				if ($this->debug) echo "<hr /><p><b>Element(" . $dbElement->getId() . ")</b>";
				$keep = false;
				
				// each database causal factor
				if ($postedElements)
				{
					foreach ($postedElements as $postedElement)
					{
						// each posted causal factor
						if ($dbElement->getId() === $postedElement['id'])
						{
							$keep = true;
							break;
						}
					}
				}
				
				if (!$keep)
				{
					if ($this->debug) echo "<br />not in list - delete";
					$element = new app_domain_CharacteristicElement($dbElement->getId());
					$element->markDeleted();
					$element->commit();
				}
				else
				{
					if ($this->debug) echo "<br />in list";
				}
				if ($this->debug) echo "</p>";
			}
		}
		if ($this->debug) echo "</div>";
	}

	/**
	 * Populate the existing resources already associated with the user (used on load).
	 * @param integer $characteristic_id
	 */
	function populateElements($characteristic)
	{
		if ($this->debug) echo "<p><b>app_command_CharacteristicEdit::populateElements(" . get_class($characteristic) . ")</b></p>";
		if ($elements = $characteristic->getElements())
		{
			$items = array();
			foreach ($elements as $element)
			{
				$item = array(	'id'        => $element->getId(),
								'data_type' => $element->getDataType(),
								'name'      => $element->getName(),
								'sort'      => $element->getSort());
				$items[] = $item;
			}
			return $items;
		}
	}

}

?>