<?php

/**
 * Defines the app_command_AjaxObjectCharacteristic class.
 *
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once 'app/command/AjaxCommand.php';
require_once 'app/domain/Characteristic.php';
require_once 'app/mapper/CharacteristicMapper.php';
require_once 'app/command/ObjectCharacteristics.php';
require_once 'app/mapper/ObjectCharacteristicHelperMapper.php';
require_once 'app/domain/ObjectCharacteristicElementHelper.php';

require_once 'include/EasySql/EasySql.class.php';

/**
 * @package Alchemis
 */
class app_command_AjaxObjectCharacteristic extends app_command_AjaxCommand
{
    /**
     * Excute the command.
     */
    public function execute()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $debug = false;
        if ($debug) { print_r($this->request);
        }

        // Instantiate the object
        $id = $this->request->item_id;

        switch ($this->request->cmd_action)
        {
        case 'add_object_characteristic':
            $characteristic = app_domain_ObjectCharacteristicHelper::factory(null, null);
            $characteristic->setParentObjectId($this->request->parent_object_id);
            $characteristic->setParentObjectType($this->request->parent_object_type);
            $characteristic->setCharacteristicId($this->request->characteristic_id);
            $characteristic->commit();
            break;

        case 'delete_object_characteristic':
            $characteristic_id = $this->request->characteristic_id;
            $characteristic_data_type = app_domain_Characteristic::lookupDataType($characteristic_id);

            switch ($this->request->parent_object_type)
            {
            case 'app_domain_Company':
                $object_characteristic_id = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($this->request->parent_object_id, $characteristic_id);
                break;

            case 'app_domain_Post':
                $object_characteristic_id = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostIdAndCharacteristicId($this->request->parent_object_id, $characteristic_id);
                break;

            case 'app_domain_PostInitiative':
                $object_characteristic_id = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostInitiativeIdAndCharacteristicId($this->request->parent_object_id, $characteristic_id);
                break;
            }

            $object_characteristic = app_domain_ObjectCharacteristicHelper::factory($characteristic_data_type, $object_characteristic_id);
            $object_characteristic->markDeleted();
            $object_characteristic->commit();
            break;

        case 'save_object_characteristic':
            $characteristic = app_domain_Characteristic::find($id);
            $form_data = $this->request->form_data;

            // array to hold incoming field data arrays
            $field_data = array();
            $date_data = array();

            foreach ($form_data as $key => $data_item)
            {

                // Check if we have an element in form_data called characteristic_data_type.
                // If so then, need to remove this and record the value
                if ($key == 'characteristic_data_type') {
                    $characteristic_data_type = $data_item;
                }
                else
                {
                    if (substr($key, 0, 6) != 'ignore') {
                        $temp = array();
                        $temp = explode('_', $key);
                        array_push($temp, $data_item);
                        if ($temp[6] == 'date') {
                            // deal with dates
                            $date_data[] = $temp;
                        }
                        else
                        {
                            $field_data[] = $temp;
                        }
                    }
                }
            }

            $date_working = array();
            foreach ($date_data as $date_item)
            {
                $iterator_value = $date_item[0];
                if ($date_item[7] == 'Year') {
                    $date_working[$iterator_value] = array(    $date_item[0],
                                                                $date_item[1],
                                                                $date_item[2],
                                                                $date_item[3],
                                                                $date_item[4],
                                                                $date_item[5],
                                                                $date_item[6],
                                                                $date_item[8]);
                }
            }

            foreach ($date_data as $date_item)
            {
                $iterator_value = $date_item[0];
                if ($date_item[7] == 'Month') {
                    $date_working[$iterator_value][7] = $date_working[$iterator_value][7] . '-' . $date_item[8];
                }
            }

            foreach ($date_data as $date_item)
            {
                $iterator_value = $date_item[0];
                if ($date_item[7] == 'Day') {
                    $date_working[$iterator_value][7] = $date_working[$iterator_value][7] . '-' . $date_item[8];
                }
            }

            foreach ($date_working as $date_item)
            {
                $field_data[] = array(    $date_item[0],
                                            $date_item[1],
                                            $date_item[2],
                                            $date_item[3],
                                            $date_item[4],
                                            $date_item[5],
                                            $date_item[6],
                                            $date_item[7]);
            }

            unset($date_working);
            unset($date_data);

            if ($debug) { print_r($this->request);
            }

            $this->processObjectCharacteristicElements($field_data, $characteristic, $this->request->parent_object_type, $this->request->parent_object_id);
            $this->request->characteristic_screen = $this->getCharacteristicScreen($field_data, $characteristic);
            break;
        }

        $this->response->data[] = $this->request;
    }

    /**
     * @param array                     $field_data
     * @param app_domain_Characteristic $characteristic
     * @param string                    $parent_object_type (e.g. company, post, post initiative)
     * @param integer                   $parent_object_id
     */
    function processObjectCharacteristicElements($field_data, app_domain_Characteristic $characteristic, $parent_object_type, $parent_object_id)
    {
        $debug = false;
        if ($debug) { echo "\nprocessObjectCharacteristicElements()\n";
        }
        //        $has_multiple_elements = $characteristic->hasMultipleElements();
        $has_multiple_elements = $characteristic->hasAttributes() || $characteristic->hasOptions();

        // Get list of existing attributes in the database for this object characteristic id so that we can remove any
        // that are not passed through from the submitting form
        // return;
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // echo 'df';
        // print_r($field_data);
        $connection = $this->getDbConnection();
        $db = new EasySql($connection['username'], $connection['password'], $connection['database'], $connection['hostname']);
        $db->debug_all = false;




        // print_r($characteristic);
        if($field_data) {
            $temp_table = "tbl_object_characteristic_elements_".$field_data[0][6];

			if($field_data[0][6] == 'boolean') {
				$query = "UPDATE `".$temp_table."` SET `value` = '0' WHERE `".$temp_table."`.`object_characteristic_id` = ".$field_data[0][3];
				$db->query($query);
			}

            foreach($field_data as $item){
                if($item[6] == 'boolean' && $item[7] == "on") {
                    $item[7] = 1;
                }
                if($item['5'] != null) {
                    $query = "UPDATE `".$temp_table."` SET `value` = '".$item[7]."' WHERE `".$temp_table."`.`id` = ".$item[5];
                }else{
                    $query = "INSERT INTO `".$temp_table."` (`id`, `object_characteristic_id`, `characteristic_element_id`, `value`) VALUES(null, '".$item[3]."', '".$item[2]."', '".$item[7]."')";
                }
				if ($item[6] == 'date') {
                    $temp_table = "tbl_object_characteristics_date";
                    // $query = "UPDATE `" . $temp_table . "` SET `value` = '" . $item[7] . "' WHERE `" . $temp_table . "`.`id` = " . $item[4];
                    if ($item[4] != null) {
                        $query = "UPDATE `" . $temp_table . "` SET `value` = '" . $item[7] . "' WHERE `" . $temp_table . "`.`id` = " . $item[4];
                    } else {
                        $query = "INSERT INTO `" . $temp_table . "` (`id`, `characteristic_id`, `company_id`, `value`) VALUES(null, '" . $item[1] . "', '" . $parent_object_id . "', '" . $item[7] . "')";
                    }
                }
                // print_r($query);die;
                $db->query($query);
            }
        }
        return;
        die;

        if ($has_multiple_elements) {
            $object_characteristic_helper = new app_mapper_ObjectCharacteristicHelperMapper();
            $objectCharacteristicId = $object_characteristic_helper->getObjectCharacteristicIdByParentObjectIdAndCharacteristicId($parent_object_id, $parent_object_type, $characteristic->getId());
            $dbIds = app_domain_ObjectCharacteristicElementHelper::getAllRecordsByObjectCharacteristicId($objectCharacteristicId);
        }

        //        echo "<p>before</p>";
        //        echo "<pre>";
        //        print_r($dbIds);
        //        echo "</pre>";

        //        $dbIds = array('boolean' => array(1,2,3),
        //                'date' => array(4, 6, 7))

        // Loop through $field_data and process each field
        foreach ($field_data as $field)
        {
            $iterator                         = $field[0];
            $characteristic_id                = $field[1];
            $element_id                       = $field[2];
            $object_characteristic_id         = $field[3];
            $object_characteristic_value_id   = $field[4];
            $object_characteristic_element_id = $field[5];
            $datatype                         = $field[6];
            $value                            = $field[7];

            if ($has_multiple_elements) {
                $index = array_search($object_characteristic_element_id, $dbIds[$datatype]);
                               echo '<pre>';
                               echo 'Found index: ' . $index. '\n';
                               echo '</pre>';
                if (!empty($index)) {
                    if ($index !== false) {
                        unset($dbIds[$datatype][$index]);
                    }
                }

                // do we have to insert or update this element
                if (is_null($object_characteristic_element_id)) {
                    $object_characteristic_element = app_domain_ObjectCharacteristicElementHelper::factory($datatype);
                    // insert item
                    // TODO: insert characteristic element value
                }
                else
                {
                    $object_characteristic_element = app_domain_ObjectCharacteristicElementHelper::factory($datatype, $object_characteristic_element_id);
                    // update item
                    // TODO: update characteristic element value
                }

                $object_characteristic_element->setObjectCharacteristicId($object_characteristic_id);
                $object_characteristic_element->setCharacteristicElementId($element_id);

                $object_characteristic_element->setValue($value);
                $object_characteristic_element->commit();


            }
            else
            {
                // do we have to insert or update this characteristic
                if (is_null($object_characteristic_value_id)) {
                    // insert item
                    $object_characteristic = app_domain_ObjectCharacteristicHelper::factory($datatype);
                }
                else
                {
                    // update item
                    $object_characteristic = app_domain_ObjectCharacteristicHelper::factory($datatype, $object_characteristic_value_id);
                }
                $object_characteristic->setCharacteristicId($characteristic->getId());
                $object_characteristic->setParentObjectId($parent_object_id);
                $object_characteristic->setParentObjectType($parent_object_type);
                $object_characteristic->setValue($value);
                $object_characteristic->commit();
            }
        }
        //        echo "<p>after</p>";
        //        echo "<pre>";
        //        print_r($dbIds);
        //        echo "</pre>";

        if ($has_multiple_elements) {
            foreach ($dbIds['boolean'] as $dbId)
            {
                $object_characteristic_element = app_domain_ObjectCharacteristicElementHelper::factory('boolean', $dbId);
                $object_characteristic_element->markDeleted();
                $object_characteristic_element->commit();
            }

            foreach ($dbIds['date'] as $dbId)
            {
                $object_characteristic_element = app_domain_ObjectCharacteristicElementHelper::factory('date', $dbId);
                $object_characteristic_element->markDeleted();
                $object_characteristic_element->commit();
            }

            foreach ($dbIds['text'] as $dbId)
            {
                $object_characteristic_element = app_domain_ObjectCharacteristicElementHelper::factory('text', $dbId);
                $object_characteristic_element->markDeleted();
                $object_characteristic_element->commit();
            }

        }



    }

    /**
     * Returns the HTML snippet for displaying a characteristic.
     *
     * @param  array                     $field_data
     * @param  app_domain_Characteristic $characteristic
     * @return string HTML code
     */
    function getCharacteristicScreen($field_data, app_domain_Characteristic $characteristic)
    {
        if ($characteristic->hasAttributes() || $characteristic->hasOptions()) {
            $elements = array();
            foreach ($field_data as $field)
            {
                $element_id = $field[2];
                $datatype   = $field[6];
                $value      = $field[7];
                $name = app_domain_CharacteristicElement::lookupName($element_id);
                $elements[] = array(    'name'      => $name,
                'data_type' => $datatype,
                'value'     => $value);
            }
            include_once 'include/Utils/Utils.class.php';
            $obj['elements'] = Utils::msort($elements, 'name');
        }
        else
        {
            foreach ($field_data as $field)
            {
                $datatype = $field[6];
                $value    = $field[7];
            }
            $obj['data_type'] = $datatype;
            $obj['value']     = $value;
        }

        $obj['id']         = $characteristic->hasOptions();
        $obj['attributes'] = $characteristic->hasAttributes();
        $obj['options']    = $characteristic->hasOptions();

        include_once 'app/view/ViewHelper.php';
        $smarty = ViewHelper::getSmarty();
        $smarty->assign('characteristic', $obj);
        return $smarty->fetch('html_ObjectCharacteristics.tpl');
    }

        /**
         * Gets an open DB connection object.
         *
         * @return resource an open database connection ready for use.
         * @access protected
         * @static
         */
    protected static function getDbConnection()
    {
        include_once 'app/base/Registry.php';
        $dsn = app_base_ApplicationRegistry::getDSN();
        $username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
        $password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
        $database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
        $hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
        return array(
        'username' => $username,
        'password' => $password,
        'database' => $database,
        'hostname' => $hostname,
        );
    }

}

?>