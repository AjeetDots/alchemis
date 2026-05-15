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
            $characteristic_id = (int) $this->request->characteristic_id;
            $parent_object_id = (int) $this->request->parent_object_id;
            $parent_object_type = $this->request->parent_object_type;

            if ($characteristic_id < 1 || $parent_object_id < 1 || $parent_object_type === '') {
                $this->response->warnings[] = 'Unable to delete characteristic: missing parent or characteristic id.';
                break;
            }

            try {
                app_domain_ObjectCharacteristicHelper::deleteByParentObjectAndCharacteristicId(
                    $parent_object_id,
                    $parent_object_type,
                    $characteristic_id
                );
            } catch (Throwable $e) {
                $this->response->warnings[] = 'Could not delete characteristic.';
            }
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
        $db = app_controller_ApplicationHelper::instance()->DB();




        // print_r($characteristic);
        if($field_data) {
            // For complex characteristics (with attributes/elements), item[3] holds the
            // object_characteristic_id (non-zero). For simple characteristics it is "0".
            $is_simple = ($field_data[0][3] == '0' || $field_data[0][3] == '' || $field_data[0][3] === null);

            // Reset all boolean element values to 0 for complex multi-element characteristics.
            if ($field_data[0][6] == 'boolean') {
                $firstCharacteristicId = (int) $field_data[0][1];
                $firstIsComplex = $this->isComplexCharacteristicDefinition($db, $firstCharacteristicId);
                $firstHasElements = $this->hasAnyCharacteristicElements($db, $firstCharacteristicId);
                if ($firstIsComplex && $firstHasElements) {
                    $resetObjectCharacteristicId = (int) $field_data[0][3];
                    if ($resetObjectCharacteristicId < 1) {
                        $resetObjectCharacteristicId = $this->ensureObjectCharacteristicIdForParent(
                            $firstCharacteristicId,
                            $parent_object_type,
                            (int) $parent_object_id
                        );
                    }
                    if ($resetObjectCharacteristicId > 0) {
                        $elements_table = "tbl_object_characteristic_elements_boolean";
                        $query = "UPDATE `".$elements_table."` SET `value` = '0' WHERE `object_characteristic_id` = ".$resetObjectCharacteristicId;
                        $res = $db->exec($query);
                        if (MDB2::isError($res)) {
                            throw new app_base_MDB2Exception($res);
                        }
                    }
                }
            }

            foreach ($field_data as $item) {
                if ($item[6] == 'boolean' && $item[7] == "on") {
                    $item[7] = 1;
                }

                $item_is_simple = ($item[3] == '0' || $item[3] == '' || $item[3] === null);
                $is_complex_characteristic = $this->isComplexCharacteristicDefinition($db, (int) $item[1]);
                $has_complex_elements = $this->hasAnyCharacteristicElements($db, (int) $item[1]);

                // Some legacy forms post object_characteristic_id as 0/empty even for complex characteristics.
                // Resolve/create the parent object_characteristic row so values are written to element tables.
                if ($item_is_simple && $is_complex_characteristic && $has_complex_elements) {
                    $resolvedObjectCharacteristicId = $this->ensureObjectCharacteristicIdForParent(
                        (int) $item[1],
                        $parent_object_type,
                        (int) $parent_object_id
                    );
                    if ($resolvedObjectCharacteristicId > 0) {
                        $item[3] = $resolvedObjectCharacteristicId;
                        $item_is_simple = false;
                    }
                }

                $is_complex_submit = (!$item_is_simple && $is_complex_characteristic && $has_complex_elements);

                if ($is_complex_submit) {
                    // Complex characteristics with elements use tbl_object_characteristic_elements_{type}.
                    $elements_table = "tbl_object_characteristic_elements_" . $item[6];
                    if (!empty($item[5]) && $item[5] != '0') {
                        $query = "UPDATE `".$elements_table."` SET `value` = '".$item[7]."' WHERE `id` = ".$item[5];
                    } else {
                        $query = "INSERT INTO `".$elements_table."` (`id`, `object_characteristic_id`, `characteristic_element_id`, `value`) VALUES(null, '".$item[3]."', '".$item[2]."', '".$item[7]."') ON DUPLICATE KEY UPDATE `value` = '".$item[7]."'";
                    }
                } elseif ($item[6] == 'date') {
                    // Dates always use the simple table regardless of attributes.
                    $date_table = "tbl_object_characteristics_date";
                    if (!empty($item[4]) && $item[4] != '0') {
                        $query = "UPDATE `".$date_table."` SET `value` = '".$item[7]."' WHERE `id` = ".$item[4];
                    } else {
                        $existingId = $this->findExistingSimpleValueRowId($db, $date_table, $item, $parent_object_type, $parent_object_id);
                        if ($existingId) {
                            $query = "UPDATE `".$date_table."` SET `value` = '".$item[7]."' WHERE `id` = ".$existingId;
                        } else {
                            switch ($parent_object_type) {
                                case 'app_domain_Post':
                                    $query = "INSERT INTO `".$date_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', NULL, '".$parent_object_id."', NULL, '".$item[7]."')";
                                    break;
                                case 'app_domain_PostInitiative':
                                    $query = "INSERT INTO `".$date_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', NULL, NULL, '".$parent_object_id."', '".$item[7]."')";
                                    break;
                                default: // app_domain_Company
                                    $query = "INSERT INTO `".$date_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', '".$parent_object_id."', NULL, NULL, '".$item[7]."')";
                            }
                        }
                    }
                } elseif ($item_is_simple) {
                    // Simple characteristics (no attributes/elements) store values in
                    // tbl_object_characteristics_{type}, using item[4] as the record ID.
                    $simple_table = "tbl_object_characteristics_" . $item[6];
                    if (!empty($item[4]) && $item[4] != '0') {
                        $query = "UPDATE `".$simple_table."` SET `value` = '".$item[7]."' WHERE `id` = ".$item[4];
                    } else {
                        $existingId = $this->findExistingSimpleValueRowId($db, $simple_table, $item, $parent_object_type, $parent_object_id);
                        if ($existingId) {
                            $query = "UPDATE `".$simple_table."` SET `value` = '".$item[7]."' WHERE `id` = ".$existingId;
                        } else {
                            switch ($parent_object_type) {
                                case 'app_domain_Post':
                                    $query = "INSERT INTO `".$simple_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', NULL, '".$parent_object_id."', NULL, '".$item[7]."')";
                                    break;
                                case 'app_domain_PostInitiative':
                                    $query = "INSERT INTO `".$simple_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', NULL, NULL, '".$parent_object_id."', '".$item[7]."')";
                                    break;
                                default: // app_domain_Company
                                    $query = "INSERT INTO `".$simple_table."` (`id`, `characteristic_id`, `company_id`, `post_id`, `post_initiative_id`, `value`) VALUES(null, '".$item[1]."', '".$parent_object_id."', NULL, NULL, '".$item[7]."')";
                            }
                        }
                    }
                } else {
                    // Fallback for legacy complex submit shapes.
                    $elements_table = "tbl_object_characteristic_elements_" . $item[6];
                    $query = "INSERT INTO `".$elements_table."` (`id`, `object_characteristic_id`, `characteristic_element_id`, `value`) VALUES(null, '".$item[3]."', '".$item[2]."', '".$item[7]."') ON DUPLICATE KEY UPDATE `value` = '".$item[7]."'";
                }

                $res = $db->exec($query);
                if (MDB2::isError($res)) {
                    throw new app_base_MDB2Exception($res);
                }
            }
        } else {
            // Checkbox-only characteristics submit no fields when everything is unchecked.
            $characteristicId = (int) $characteristic->getId();
            if ($characteristicId > 0 && $this->hasOnlyBooleanElements($db, $characteristicId)) {
                $resetObjectCharacteristicId = $this->ensureObjectCharacteristicIdForParent(
                    $characteristicId,
                    $parent_object_type,
                    (int) $parent_object_id
                );
                if ($resetObjectCharacteristicId > 0) {
                    $elements_table = "tbl_object_characteristic_elements_boolean";
                    $query = "UPDATE `".$elements_table."` SET `value` = '0' WHERE `object_characteristic_id` = ".$resetObjectCharacteristicId;
                    $res = $db->exec($query);
                    if (MDB2::isError($res)) {
                        throw new app_base_MDB2Exception($res);
                    }
                }
            } elseif ($characteristicId > 0
                && !$characteristic->hasAttributes()
                && !$characteristic->hasOptions()
                && $characteristic->getDataType() === 'boolean') {
                $this->resetSimpleBooleanValue($db, $characteristicId, $parent_object_type, (int) $parent_object_id);
            }
        }
        return;
        die;

    }

    /**
     * When the form has no value-row id, avoid inserting duplicate rows (same characteristic + parent):
     * tbl_object_characteristics_* has no unique key, so repeated INSERTs leave stale rows that the reader may return first.
     *
     * @param object $db MDB2 connection
     * @param string $simple_table e.g. tbl_object_characteristics_text
     * @param array $item exploded field name + value
     * @param string $parent_object_type
     * @param int $parent_object_id
     * @return int|null existing row id or null
     */
    private function resetSimpleBooleanValue($db, $characteristicId, $parent_object_type, $parent_object_id)
    {
        if ($characteristicId < 1 || $parent_object_id < 1) {
            return;
        }
        $table = 'tbl_object_characteristics_boolean';
        switch ($parent_object_type) {
            case 'app_domain_Post':
                $where = 'characteristic_id = ' . (int) $characteristicId . ' AND post_id = ' . (int) $parent_object_id;
                break;
            case 'app_domain_PostInitiative':
                $where = 'characteristic_id = ' . (int) $characteristicId . ' AND post_initiative_id = ' . (int) $parent_object_id;
                break;
            default:
                $where = 'characteristic_id = ' . (int) $characteristicId . ' AND company_id = ' . (int) $parent_object_id;
                break;
        }
        $res = $db->exec('UPDATE `' . $table . '` SET `value` = 0 WHERE ' . $where);
        if (MDB2::isError($res)) {
            throw new app_base_MDB2Exception($res);
        }
    }

    private function findExistingSimpleValueRowId($db, $simple_table, $item, $parent_object_type, $parent_object_id)
    {
        $charId = (int) $item[1];
        $parentId = (int) $parent_object_id;
        if ($charId < 1 || $parentId < 1) {
            return null;
        }
        switch ($parent_object_type) {
            case 'app_domain_Post':
                $sql = 'SELECT id FROM `' . $simple_table . '` WHERE characteristic_id = ' . $charId . ' AND post_id = ' . $parentId . ' ORDER BY id DESC LIMIT 1';
                break;
            case 'app_domain_PostInitiative':
                $sql = 'SELECT id FROM `' . $simple_table . '` WHERE characteristic_id = ' . $charId . ' AND post_initiative_id = ' . $parentId . ' ORDER BY id DESC LIMIT 1';
                break;
            default:
                $sql = 'SELECT id FROM `' . $simple_table . '` WHERE characteristic_id = ' . $charId . ' AND company_id = ' . $parentId . ' ORDER BY id DESC LIMIT 1';
                break;
        }
        $rowId = $db->queryOne($sql);
        if (MDB2::isError($rowId)) {
            return null;
        }
        return $rowId ? (int) $rowId : null;
    }

    /**
     * @param object $db
     * @param int    $characteristicId
     * @return bool
     */
    private function isComplexCharacteristicDefinition($db, $characteristicId)
    {
        if ($characteristicId < 1) {
            return false;
        }
        $sql = 'SELECT attributes, options FROM tbl_characteristics WHERE id = ' . (int) $characteristicId;
        $row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
        if (MDB2::isError($row) || !is_array($row)) {
            return false;
        }
        return ((int) $row['attributes'] === 1 || (int) $row['options'] === 1);
    }

    /**
     * @param object $db
     * @param int    $characteristicId
     * @return bool
     */
    private function hasAnyCharacteristicElements($db, $characteristicId)
    {
        if ($characteristicId < 1) {
            return false;
        }
        $sql = 'SELECT id FROM tbl_characteristic_elements WHERE characteristic_id = ' . (int) $characteristicId . ' LIMIT 1';
        $id = $db->queryOne($sql);
        if (MDB2::isError($id)) {
            return false;
        }
        return ((int) $id > 0);
    }

    /**
     * @param object $db
     * @param int    $characteristicId
     * @return bool
     */
    private function hasOnlyBooleanElements($db, $characteristicId)
    {
        if ($characteristicId < 1) {
            return false;
        }
        $countAll = (int) $db->queryOne(
            'SELECT COUNT(*) FROM tbl_characteristic_elements WHERE characteristic_id = ' . (int) $characteristicId
        );
        if (MDB2::isError($countAll) || $countAll < 1) {
            return false;
        }
        $countNonBoolean = (int) $db->queryOne(
            "SELECT COUNT(*) FROM tbl_characteristic_elements WHERE characteristic_id = " . (int) $characteristicId .
            " AND (data_type IS NULL OR data_type = '' OR data_type <> 'boolean')"
        );
        if (MDB2::isError($countNonBoolean)) {
            return false;
        }
        return ($countNonBoolean === 0);
    }

    /**
     * @param int    $characteristicId
     * @param string $parentObjectType
     * @param int    $parentObjectId
     * @return int
     */
    private function getObjectCharacteristicIdForParent($characteristicId, $parentObjectType, $parentObjectId)
    {
        switch ($parentObjectType) {
            case 'app_domain_Post':
                return (int) app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostIdAndCharacteristicId($parentObjectId, $characteristicId);
            case 'app_domain_PostInitiative':
                return (int) app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostInitiativeIdAndCharacteristicId($parentObjectId, $characteristicId);
            case 'app_domain_Company':
            default:
                return (int) app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($parentObjectId, $characteristicId);
        }
    }

    /**
     * Returns existing object_characteristic id for parent/characteristic or creates it if missing.
     *
     * @param int    $characteristicId
     * @param string $parentObjectType
     * @param int    $parentObjectId
     * @return int
     */
    private function ensureObjectCharacteristicIdForParent($characteristicId, $parentObjectType, $parentObjectId)
    {
        $objectCharacteristicId = $this->getObjectCharacteristicIdForParent($characteristicId, $parentObjectType, $parentObjectId);
        if ($objectCharacteristicId > 0) {
            return (int) $objectCharacteristicId;
        }

        $obj = app_domain_ObjectCharacteristicHelper::factory(null, null);
        $obj->setParentObjectId($parentObjectId);
        $obj->setParentObjectType($parentObjectType);
        $obj->setCharacteristicId($characteristicId);
        $obj->commit();
        return (int) $obj->getId();
    }

    /**
     * Ensures simple text submits for complex characteristics persist to the element table the UI reads.
     *
     * @param object $db
     * @param int    $characteristicId
     * @param string $value
     * @param string $parentObjectType
     * @param int    $parentObjectId
     * @return void
     */
    private function upsertFirstTextElementForComplexCharacteristic($db, $characteristicId, $value, $parentObjectType, $parentObjectId)
    {
        $objectCharacteristicId = $this->getObjectCharacteristicIdForParent($characteristicId, $parentObjectType, $parentObjectId);
        if ($objectCharacteristicId < 1) {
            $obj = app_domain_ObjectCharacteristicHelper::factory(null, null);
            $obj->setParentObjectId($parentObjectId);
            $obj->setParentObjectType($parentObjectType);
            $obj->setCharacteristicId($characteristicId);
            $obj->commit();
            $objectCharacteristicId = (int) $obj->getId();
        }
        if ($objectCharacteristicId < 1) {
            return;
        }

        $elementId = $db->queryOne(
            'SELECT id FROM tbl_characteristic_elements WHERE characteristic_id = ' . (int) $characteristicId .
            " AND (data_type = 'text' OR data_type = '' OR data_type IS NULL) ORDER BY sort, id LIMIT 1"
        );
        if (MDB2::isError($elementId)) {
            return;
        }
        $elementId = (int) $elementId;
        if ($elementId < 1) {
            return;
        }

        $existingId = (int) $db->queryOne(
            'SELECT id FROM tbl_object_characteristic_elements_text WHERE object_characteristic_id = ' . $objectCharacteristicId .
            ' AND characteristic_element_id = ' . $elementId . ' ORDER BY id DESC LIMIT 1'
        );
        $valueSql = $db->quote($value, 'text');
        if ($existingId > 0) {
            $query = 'UPDATE tbl_object_characteristic_elements_text SET value = ' . $valueSql . ' WHERE id = ' . $existingId;
        } else {
            $query = 'INSERT INTO tbl_object_characteristic_elements_text (id, object_characteristic_id, characteristic_element_id, value) ' .
                'VALUES (NULL, ' . $objectCharacteristicId . ', ' . $elementId . ', ' . $valueSql . ')';
        }
        $res = $db->exec($query);
        if (MDB2::isError($res)) {
            throw new app_base_MDB2Exception($res);
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
        $parts = parse_url($dsn);
        
        return array(
            'username' => $parts['user'] ?? '',
            'password' => $parts['pass'] ?? '',
            'database' => isset($parts['path']) ? ltrim($parts['path'], '/') : '',
            'hostname' => $parts['host'] ?? '',
            'port'     => $parts['port'] ?? 3306,
        );
    }

}

?>