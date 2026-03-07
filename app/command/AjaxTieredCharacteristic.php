<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/TieredCharacteristic.php');
require_once('app/mapper/TieredCharacteristicMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_TieredCharacteristic objects.
 * @package alchemis
 */
class app_command_AjaxTieredCharacteristic extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug)
		{
			echo "<pre>";
			echo print_r($this->request);
			echo "</pre>";
		}
		
		// Instantiate the object
		$id = $this->request->item_id;
			
//		if ($id)
//		{
//			$tiered_characteristic = app_domain_TieredCharacteristic::find($id);
//		}
//		else
//		{
//			$tiered_characteristic = new app_domain_TieredCharacteristic();
//		} 
//		
		switch ($this->request->cmd_action)
		{
			case 'add_tiered_characteristic':
				$obj = new app_domain_TieredCharacteristic();
				$obj->setValue($this->request->value);
				$obj->setParentId($this->request->parent_id);
				$obj->setCategoryId($this->request->category_id);
				$obj->commit();
				$this->request->line_html = $this->getTieredCharacteristicListLine($obj);
				$this->request->success = true;
				break;

			
			case 'get_sub_characteristics':
				$sub_tier = app_domain_TieredCharacteristic::findTierByParentObjectIdAndCategoryIdAndParentId($this->request->parent_object_type, $this->request->parent_object_id, $this->request->category_id, $this->request->parent_id);
				$this->request->sub_tier = $sub_tier;
				break;
			case 'get_sub_characteristics_options':
				$sub_tier = app_domain_TieredCharacteristic::findByCategoryIdAndParentIdNotUsedByParentObjectId($this->request->category_id, $this->request->parent_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->sub_tier_options = $sub_tier->toRawArray();
				break;
			case 'delete_sub_characteristic':
				$sub_tier = app_domain_TieredCharacteristic::findByCategoryIdAndParentId($this->request->category_id, $this->request->parent_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->sub_tier_options = $sub_tier->toRawArray();
				break;
			case 'add_parent_object_characteristic':
				if ($this->request->tiered_characteristic_id == 0) // means we haven't passed through an existing tiered characteristic id
				{
					
					$t = new $this->request->parent_object_type;
					
					$parent_domain_object = $t->find($this->request->parent_object_id);
					
					
					$tiered_characteristic = new app_domain_TieredCharacteristic();
					$tiered_characteristic->setCategoryId($this->request->category_id);
					$tiered_characteristic->setValue($this->request->new_value);
					$tiered_characteristic->setParentId($this->request->parent_id);
					$tiered_characteristic->setParentDomainObject($parent_domain_object);
					$tiered_characteristic->setTier($this->request->tier);
					$tiered_characteristic->commit();
					
					$this->request->tiered_characteristic_id = $tiered_characteristic->getId();
				}
				else
				{
					app_domain_TieredCharacteristic::insertParentObjectTieredCharacteristic($this->request->parent_object_type, $this->request->parent_object_id,$this->request->tiered_characteristic_id, $this->request->tier);
				}
				
				$sub_tier = app_domain_TieredCharacteristic::findByIdAndParentObjectId($this->request->tiered_characteristic_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->tiered_characteristic = $sub_tier->toRawArray();
				break;	
			case 'add_top_level_category':

				app_domain_TieredCharacteristic::insertParentObjectTieredCharacteristic($this->request->parent_object_type, $this->request->parent_object_id,$this->request->tiered_characteristic_id, $this->request->tier);
				
				$sub_tier = app_domain_TieredCharacteristic::findByIdAndParentObjectId($this->request->tiered_characteristic_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->tiered_characteristic = $sub_tier->toRawArray();
				break;	
				
//			case 'insert_sub_tier':
//				$tiered_characteristic = new app_domain_TieredCharacteristic();
//				
//				$tiered_characteristic = app_domain_Post::find($this->request->parent_object_id);
//				$tag->setParentDomainObject($post);
//				$tag->commit();
//				break;
			default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
//		if ($this->request->item_id == null)
//		{
//			$this->request->item_id = $tag->getId();
//		}

		array_push($this->response->data, $this->request);
		
	}

	/**
	 * @param app_domain_Characteristic $characteristic
	 */
	protected function getTieredCharacteristicListLine(app_domain_TieredCharacteristic $obj)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('characteristic', $obj);
		return $smarty->fetch('html_TieredCharacteristicListLine.tpl');
	}

}

?>