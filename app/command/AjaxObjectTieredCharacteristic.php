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
require_once('app/domain/ObjectTieredCharacteristic.php');
require_once('app/mapper/ObjectTieredCharacteristicMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_ObjectTieredCharacteristic objects.
 * @package alchemis
 */
class app_command_AjaxObjectTieredCharacteristic extends app_command_AjaxCommand
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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
			case 'add_object_tiered_characteristic':

				// Instantiate the tiered characteristic selected
				$tiered_characteristic = app_domain_TieredCharacteristic::find($this->request->tiered_characteristic_id);

				// We may have to add the parent first before the select tiered characteristic can be added
				if ($tiered_characteristic->hasParent() && !app_domain_ObjectTieredCharacteristicHelper::isAssociated($this->request->parent_object_id, $tiered_characteristic->getParentId()))
				{
					// Parent category needs to be associated first
					$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
					$obj->setParentObjectId($this->request->parent_object_id);
					$obj->setParentObjectType($this->request->parent_object_type);
					$obj->setTieredCharacteristicId($tiered_characteristic->getParentId());
					$obj->setTier(0);
					$obj->commit();
				}

				$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
				$obj->setParentObjectId($this->request->parent_object_id);
				$obj->setParentObjectType($this->request->parent_object_type);
				$obj->setTieredCharacteristicId($this->request->tiered_characteristic_id);
				$obj->setTier($this->request->tier);
				$obj->commit();
				break;

			case 'add_parent_object_tiered_characteristic':

				// Instantiate the tiered characteristic selected
				$tiered_characteristic = app_domain_TieredCharacteristic::find($this->request->tiered_characteristic_id);

				// We may have to add the parent first before the select tiered characteristic can be added
				if ($tiered_characteristic->hasParent() && !app_domain_ObjectTieredCharacteristicHelper::isAssociatedParent($this->request->parent_company, $tiered_characteristic->getParentId()))
				{
					// Parent category needs to be associated first
					$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
					$obj->setParentCompanyId($this->request->parent_company);
					$obj->setParentObjectType($this->request->parent_object_type);
					$obj->setTieredCharacteristicId($tiered_characteristic->getParentId());
					$obj->setTier(0);
					$obj->commit();
				}

				$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
				$obj->setParentCompanyId($this->request->parent_company);
				$obj->setParentObjectType($this->request->parent_object_type);
				$obj->setTieredCharacteristicId($this->request->tiered_characteristic_id);
				$obj->setTier($this->request->tier);
				$obj->commit();
				break;

			case 'delete_object_tiered_characteristic':
				$object_tiered_characteristic = app_domain_ObjectTieredCharacteristicHelper::factory($this->request->tiered_characteristic_id);

				$tiered_characteristic = app_domain_TieredCharacteristic::find($object_tiered_characteristic->getTieredCharacteristicId());
				$parent_id = $tiered_characteristic->getParentId();

				$object_tiered_characteristic->markDeleted();
				$object_tiered_characteristic->commit();

				// check if there are any other subcats left in the same cat
				// If not, then delete the parent cat
				if (app_domain_ObjectTieredCharacteristic::countTieredCharacteristicByCompanyIdAndTieredCharacteristicId($this->request->parent_object_id, $parent_id) == 0)
				{

					$parent_tiered_characteristic = app_domain_ObjectTieredCharacteristic::findByCompanyIdAndTieredCharacterisicId($this->request->parent_object_id, $parent_id);
					$parent_tiered_characteristic->markDeleted();
					$parent_tiered_characteristic->commit();
				}
				break;

			case 'delete_parent_object_tiered_characteristic':
				$object_tiered_characteristic = app_domain_ObjectTieredCharacteristicHelper::factory($this->request->tiered_characteristic_id);

				$tiered_characteristic = app_domain_TieredCharacteristic::find($object_tiered_characteristic->getTieredCharacteristicId());
				$parent_id = $tiered_characteristic->getParentId();

				$object_tiered_characteristic->markDeleted();
				$object_tiered_characteristic->commit();

				// check if there are any other subcats left in the same cat
				// If not, then delete the parent cat
				if (app_domain_ObjectTieredCharacteristic::countTieredCharacteristicByParentCompanyIdAndTieredCharacteristicId($this->request->parent_company, $parent_id) == 0)
				{

					$parent_tiered_characteristic = app_domain_ObjectTieredCharacteristic::findByParentCompanyIdAndTieredCharacterisicId($this->request->parent_company, $parent_id);
					$parent_tiered_characteristic->markDeleted();
					$parent_tiered_characteristic->commit();
				}
				break;

			case 'get_sub_characteristics':
				$sub_tier = app_domain_ObjectTieredCharacteristic::findTierByParentObjectIdAndCategoryIdAndParentId($this->request->parent_object_type, $this->request->parent_object_id, $this->request->category_id, $this->request->parent_id);
				$this->request->sub_tier = $sub_tier;
				break;

			case 'get_sub_characteristics_options':
				$sub_tier = app_domain_ObjectTieredCharacteristic::findByCategoryIdAndParentIdNotUsedByParentObjectId($this->request->category_id, $this->request->parent_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->sub_tier_options = $sub_tier->toRawArray();
				break;

			case 'delete_sub_characteristic':

				$object_tiered_characteristic = app_domain_ObjectTieredCharacteristic::findObjectTieredCharacteristic($this->request->item_id, 'app_domain_' . $this->request->parent_object_type);
				$object_tiered_characteristic->markDeleted();
//				$parent_id = $object_tiered_characteristic->getParent();

				$object_tiered_characteristic->commit();

//				// check if there are any other subcats left in the same cat
//				// If not, then delete the parent cat
//				if (app_domain_ObjectTieredCharacteristic::countTieredCharacteristicByCompanyIdAndTieredCharacteristicId($this->request->parent_object_id, $parent_id) == 0)
//				{
//					$parent_tiered_characteristic = app_domain_ObjectTieredCharacteristic::findObjectTieredCharacteristic($parent_id, 'app_domain_' . $this->request->parent_object_type);
//					$parent_tiered_characteristic->markDeleted();
//					$parent_tiered_characteristic->commit();
//				}
				break;

			case 'add_parent_object_characteristic':
				$t = new $this->request->parent_object_type;
				$parent_domain_object = $t->find($this->request->parent_object_id);

				if ($this->request->tiered_characteristic_id == 0) // means we haven't passed through an existing tiered characteristic id
				{

					$tiered_characteristic = new app_domain_TieredCharacteristic();
					$tiered_characteristic->setCategoryId($this->request->category_id);
					$tiered_characteristic->setValue($this->request->new_value);
					$tiered_characteristic->setParentId($this->request->parent_id);
//					$tiered_characteristic->setParentDomainObject($parent_domain_object);
//					$tiered_characteristic->setTier($this->request->tier);
					$tiered_characteristic->commit();

					$this->request->tiered_characteristic_id = $tiered_characteristic->getId();
				}

				$object_tiered_characteristic = new app_domain_ObjectTieredCharacteristic($parent_domain_object);
				$object_tiered_characteristic->setTieredCharacteristicId($this->request->tiered_characteristic_id);
				$object_tiered_characteristic->setTier($this->request->tier);
				$object_tiered_characteristic->commit();

//					app_domain_ObjectTieredCharacteristic::insertParentObjectTieredCharacteristic($this->request->parent_object_type, $this->request->parent_object_id,$this->request->tiered_characteristic_id, $this->request->tier);

				$sub_tier = app_domain_ObjectTieredCharacteristic::findByIdAndParentObjectId($this->request->tiered_characteristic_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->tiered_characteristic = $sub_tier->toRawArray();
				break;

			case 'add_top_level_category':
				app_domain_TieredCharacteristic::insertParentObjectTieredCharacteristic($this->request->parent_object_type, $this->request->parent_object_id,$this->request->tiered_characteristic_id, $this->request->tier);
				$sub_tier = app_domain_TieredCharacteristic::findByIdAndParentObjectId($this->request->tiered_characteristic_id, $this->request->parent_object_type, $this->request->parent_object_id);
				$this->request->tiered_characteristic = $sub_tier->toRawArray();
				break;

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

}

?>