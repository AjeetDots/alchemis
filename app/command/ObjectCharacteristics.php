<?php

/**
 * Defines the app_command_ObjectCharacteristics class.
 *
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once 'app/domain/Characteristic.php';
require_once 'app/domain/ObjectCharacteristicHelper.php';
/**
 * @package Alchemis
 */
class app_command_ObjectCharacteristics extends app_command_Command
{

    /**
     * Normalize helper result to array for PHP 8+ count() safety.
     * @param mixed $value
     * @return array
     */
    protected static function asArray($value)
    {
        return is_array($value) ? $value : array();
    }

    /**
     * Populate a simple characteristic value for a specific parent object.
     *
     * @param array  $characteristic
     * @param string $type
     * @param int    $id
     * @return void
     */
    protected static function populateSimpleCharacteristicValue(array &$characteristic, $type, $id)
    {
        if (! isset($characteristic['data_type']) || $characteristic['data_type'] === '') {
            $characteristic['data_type'] = 'text';
        }
        switch ($type) {
            case 'post':
                $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostId($characteristic['id'], $characteristic['data_type'], $id));
                break;
            case 'post_initiative':
                $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostInitiativeId($characteristic['id'], $characteristic['data_type'], $id));
                break;
            case 'company':
            default:
                $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByCompanyId($characteristic['id'], $characteristic['data_type'], $id));
                break;
        }

        if (count($characteristic_item) > 0) {
            $characteristic['value']                          = $characteristic_item[0]['value'];
            $characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
        } else {
            $characteristic['value']                          = null;
            $characteristic['object_characteristic_value_id'] = null;
        }
    }

    /**
     * Characteristics that are marked as complex but have no elements render as blank panels.
     * Fall back to simple text inputs so users can still edit values.
     *
     * @param array  $characteristic
     * @param string $type
     * @param int    $id
     * @return void
     */
    protected static function applySimpleFallbackWhenNoElements(array &$characteristic, $type, $id)
    {
        if (!empty($characteristic['elements'])) {
            return;
        }

        $characteristic['attributes']      = 0;
        $characteristic['options']         = 0;
        $characteristic['multiple_select'] = 0;
        if (empty($characteristic['data_type'])) {
            $characteristic['data_type'] = 'text';
        }
        self::populateSimpleCharacteristicValue($characteristic, $type, $id);
    }

    /**
     * UI templates only render boolean/date/text. Missing types produce blank edit forms.
     *
     * @param array $characteristic
     * @return void
     */
    protected static function normalizeCharacteristicDataTypes(array &$characteristic)
    {
        $simple = ($characteristic['attributes'] == 0 && $characteristic['options'] == 0);
        if ($simple && (! isset($characteristic['data_type']) || $characteristic['data_type'] === '')) {
            $characteristic['data_type'] = 'text';
        }
        if (! empty($characteristic['elements']) && is_array($characteristic['elements'])) {
            foreach ($characteristic['elements'] as &$element) {
                if (! isset($element['data_type']) || $element['data_type'] === '') {
                    $element['data_type'] = 'text';
                }
            }
            unset($element);
        }
    }

    /**
     * Ensure template-consumed runtime keys exist for each characteristic element.
     * Prevents PHP 8 undefined-key warnings in ObjectCharacteristics.tpl.
     *
     * @param array $element
     * @return void
     */
    protected static function normalizeElementRuntimeFields(array &$element)
    {
        if (!array_key_exists('value', $element) || $element['value'] === null) {
            $element['value'] = '';
        }
        if (!isset($element['object_characteristic_id']) || $element['object_characteristic_id'] === null || $element['object_characteristic_id'] === '') {
            $element['object_characteristic_id'] = 0;
        }
        if (!isset($element['object_characteristic_element_id']) || $element['object_characteristic_element_id'] === null || $element['object_characteristic_element_id'] === '') {
            $element['object_characteristic_element_id'] = 0;
        }
    }

    /**
     * Ajax save for "simple" field names writes to tbl_object_characteristics_text. If this characteristic
     * is still defined with attributes and a single text element, the loader only reads element tables —
     * the saved text never appears. Copy simple-text value into that element for display/edit.
     *
     * Only runs when there is exactly one text-type element (safe for typical single-field AWS-style rows).
     *
     * @param array  $characteristic
     * @param string $type           company|post|post_initiative
     * @param int    $parentId
     * @return void
     */
    protected static function mergeOrphanSimpleTextIntoSingleTextElement(array &$characteristic, $type, $parentId)
    {
        if (empty($characteristic['elements']) || ! is_array($characteristic['elements'])) {
            return;
        }
        $textIdx = array();
        foreach ($characteristic['elements'] as $i => $el) {
            if (isset($el['data_type']) && $el['data_type'] === 'text') {
                $textIdx[] = $i;
            }
        }
        if (count($textIdx) !== 1) {
            return;
        }
        $idx = $textIdx[0];
        $current = isset($characteristic['elements'][$idx]['value']) ? $characteristic['elements'][$idx]['value'] : null;
        if ($current !== null && $current !== '') {
            return;
        }
        switch ($type) {
            case 'post':
                $rows = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostId($characteristic['id'], 'text', $parentId));
                break;
            case 'post_initiative':
                $rows = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostInitiativeId($characteristic['id'], 'text', $parentId));
                break;
            case 'company':
            default:
                $rows = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByCompanyId($characteristic['id'], 'text', $parentId));
                break;
        }
        if (count($rows) === 0) {
            return;
        }
        $val = isset($rows[0]['value']) ? $rows[0]['value'] : null;
        if ($val === null || $val === '') {
            return;
        }
        $characteristic['elements'][$idx]['value'] = $val;
    }

    public function doExecute(app_controller_Request $request)
    {
        $id   = $request->getProperty('id');
        $type = $request->getProperty('type');
        $object_characteristics = self::getObjectCharacteristicsByTypeAndId($type, $id);
        $request->setObject('parent_object_id', $id);

        switch ($type) {
            case 'company':  // fall-through
            case 'post':
                $parent_object_type = ucfirst($type);
                break;

            case 'post_initiative':
                $parent_object_type = 'PostInitiative';
                break;

            default:
                throw new Exception('Invalid object type specified');
                break;
        }

        $request->setObject('parent_object_type',   'app_domain_' . $parent_object_type);
        $request->setObject('characteristics',      $object_characteristics->collection);
        $request->setObject('characteristic_array', $object_characteristics->characteristics);
        $request->setObject('available',            $object_characteristics->available);
        $request->setObject('type',                 $type);
        $request->setObject('initiative_id', $request->getProperty('initiative_id'));
        return self::statuses('CMD_OK');
    }

    /**
     * Get the characteristics associated and available for the object.
     *
     * @param  string  $type the type of object, one of {company, post, post_initiative}
     * @param  integer $id   the object ID
     * @return stdClass object
     *     app_mapper_CharacteristicCollection $object->available a collection of charateristics which are not
     *                                                            associated with the object
     *     app_mapper_CharacteristicCollection $object->collection a collection characteristics which are associated
     *                                                             with the object
     *     array $object->characteristics numeric array of characteristics which are associated with the object
     */
    public static function getObjectCharacteristicsByTypeAndId($type, $id)
    {
        $db = app_controller_ApplicationHelper::instance()->DB();

        if ($type == 'company') {
            // echo $id;
            $temp_data_all = [];
            $available = app_domain_Characteristic::selectAvailableByCompanyId($id);
            $collection = app_domain_Characteristic::findByCompanyId($id);
            $characteristics = $collection->toRawArray();
            foreach ($characteristics as &$characteristic) {
                if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0) {
                    self::populateSimpleCharacteristicValue($characteristic, 'company', $id);
                } else {
                    $charId    = (int)$characteristic['id'];
                    $companyId = (int)$id;
                    $sql = "SELECT id FROM tbl_object_characteristics WHERE characteristic_id = $charId AND company_id = $companyId";
                    $ocId = (int)$db->queryOne($sql);

                    $temp_data = array();
                    if ($ocId > 0) {
                        $sql = "SELECT * FROM tbl_object_characteristic_elements_boolean WHERE object_characteristic_id = $ocId";
                        $temp_data = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
                    }
                    if ($temp_data) {
                        foreach ($temp_data as $item) {
                            $temp_data_all[$item['characteristic_element_id']] = $item;
                        }
                    }
                    $characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();



                    $fallbackObjectCharacteristicId = null;
                    foreach ($characteristic['elements'] as $key => &$element) {
                        // Legacy data sometimes stores empty element data types.
                        // Normalize before lookup so helper mappers can return records.
                        if (!isset($element['data_type']) || $element['data_type'] === '') {
                            $element['data_type'] = 'text';
                        }

                        if (isset($temp_data_all[$element['id']])) {
                            $element['value']                            = $temp_data_all[$element['id']]['value'];
                            $element['object_characteristic_id']         = $temp_data_all[$element['id']]['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $temp_data_all[$element['id']]['id'];
                        }
                        if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByCompanyId($element['id'], $element['data_type'], $id)) {
                            $element['value']                            = $record['value'];
                            $element['object_characteristic_id']         = $record['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $record['id'];
                        } else {
                            if ($fallbackObjectCharacteristicId === null) {
                                $fallbackObjectCharacteristicId = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($id, $characteristic['id']);
                            }
                            $element['object_characteristic_id'] = $fallbackObjectCharacteristicId;
                        }
                        self::normalizeElementRuntimeFields($element);

                    }

                    self::mergeOrphanSimpleTextIntoSingleTextElement($characteristic, 'company', $id);

                    self::applySimpleFallbackWhenNoElements($characteristic, 'company', $id);
                }
                self::normalizeCharacteristicDataTypes($characteristic);
            }
        } elseif ($type == 'post') {
            $available = app_domain_Characteristic::selectAvailableByPostId($id);
            $collection = app_domain_Characteristic::findByPostId($id);
            $characteristics = $collection->toRawArray();
            foreach ($characteristics as &$characteristic) {
                if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0) {
                    self::populateSimpleCharacteristicValue($characteristic, 'post', $id);
                } else {
                    $characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
                    $fallbackObjectCharacteristicId = null;
                    foreach ($characteristic['elements'] as &$element) {
                        if (!isset($element['data_type']) || $element['data_type'] === '') {
                            $element['data_type'] = 'text';
                        }
                        if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByPostId($element['id'], $element['data_type'], $id)) {
                            $element['value']                            = $record['value'];
                            $element['object_characteristic_id']         = $record['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $record['id'];
                        } else {
                            if ($fallbackObjectCharacteristicId === null) {
                                $fallbackObjectCharacteristicId = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostIdAndCharacteristicId($id, $characteristic['id']);
                            }
                            $element['object_characteristic_id'] = $fallbackObjectCharacteristicId;
                        }
                        self::normalizeElementRuntimeFields($element);
                    }
                    self::mergeOrphanSimpleTextIntoSingleTextElement($characteristic, 'post', $id);
                    self::applySimpleFallbackWhenNoElements($characteristic, 'post', $id);
                }
                self::normalizeCharacteristicDataTypes($characteristic);
            }
        } elseif ($type == 'post_initiative') {
            $available = app_domain_Characteristic::selectAvailableByPostInitiativeId($id);
            $collection = app_domain_Characteristic::findByPostInitiativeId($id);
            $characteristics = $collection->toRawArray();
            foreach ($characteristics as &$characteristic) {
                if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0) {
                    self::populateSimpleCharacteristicValue($characteristic, 'post_initiative', $id);
                } else {
                    $characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
                    $fallbackObjectCharacteristicId = null;
                    foreach ($characteristic['elements'] as &$element) {
                        if (!isset($element['data_type']) || $element['data_type'] === '') {
                            $element['data_type'] = 'text';
                        }
                        if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByPostInitiativeId($element['id'], $element['data_type'], $id)) {
                            $element['value']                            = $record['value'];
                            $element['object_characteristic_id']         = $record['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $record['id'];
                        } else {
                            if ($fallbackObjectCharacteristicId === null) {
                                $fallbackObjectCharacteristicId = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostInitiativeIdAndCharacteristicId($id, $characteristic['id']);
                            }
                            $element['object_characteristic_id'] = $fallbackObjectCharacteristicId;
                        }
                        self::normalizeElementRuntimeFields($element);
                    }
                    self::mergeOrphanSimpleTextIntoSingleTextElement($characteristic, 'post_initiative', $id);
                    self::applySimpleFallbackWhenNoElements($characteristic, 'post_initiative', $id);
                }
                self::normalizeCharacteristicDataTypes($characteristic);
            }
        } else {
            throw new Exception('Invalid object type: ' . $type);
        }

        $return_data = new stdClass();
        $return_data->available = $available;
        $return_data->collection = $collection;
        $return_data->characteristics = $characteristics;
        // echo '<pre>';
        // print_r($characteristic);
        // echo '</pre>';
        return $return_data;
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
