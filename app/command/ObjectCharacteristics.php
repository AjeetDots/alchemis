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
                    $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByCompanyId($characteristic['id'], $characteristic['data_type'], $id));
                    if (count($characteristic_item) > 0) {
                        $characteristic['value']                          = $characteristic_item[0]['value'];
                        $characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
                    } else {
                        $characteristic['value']                          = null;
                        $characteristic['object_characteristic_value_id'] = null;
                    }
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



                    foreach ($characteristic['elements'] as $key => &$element) {

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
                            $record = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($id, $characteristic['id']);
                            $element['object_characteristic_id'] = $record;
                        }

                    }


                }
            }
        } elseif ($type == 'post') {
            $available = app_domain_Characteristic::selectAvailableByPostId($id);
            $collection = app_domain_Characteristic::findByPostId($id);
            $characteristics = $collection->toRawArray();
            foreach ($characteristics as &$characteristic) {
                if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0) {
                    $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostId($characteristic['id'], $characteristic['data_type'], $id));
                    if (count($characteristic_item) > 0) {
                        $characteristic['value']                          = $characteristic_item[0]['value'];
                        $characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
                    } else {
                        $characteristic['value']                          = null;
                        $characteristic['object_characteristic_value_id'] = null;
                    }
                } else {
                    $characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
                    foreach ($characteristic['elements'] as &$element) {
                        if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByPostId($element['id'], $element['data_type'], $id)) {
                            $element['value']                            = $record['value'];
                            $element['object_characteristic_id']         = $record['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $record['id'];
                        } else {
                            $ocId = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostIdAndCharacteristicId($id, $characteristic['id']);
                            $element['object_characteristic_id'] = $ocId;
                        }
                    }
                }
            }
        } elseif ($type == 'post_initiative') {
            $available = app_domain_Characteristic::selectAvailableByPostInitiativeId($id);
            $collection = app_domain_Characteristic::findByPostInitiativeId($id);
            $characteristics = $collection->toRawArray();
            foreach ($characteristics as &$characteristic) {
                if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0) {
                    $characteristic_item = self::asArray(app_domain_ObjectCharacteristicHelper::getValueByPostInitiativeId($characteristic['id'], $characteristic['data_type'], $id));
                    if (count($characteristic_item) > 0) {
                        $characteristic['value']                          = $characteristic_item[0]['value'];
                        $characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
                    } else {
                        $characteristic['value']                          = null;
                        $characteristic['object_characteristic_value_id'] = null;
                    }
                } else {
                    $characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
                    foreach ($characteristic['elements'] as &$element) {
                        if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByPostInitiativeId($element['id'], $element['data_type'], $id)) {
                            $element['value']                            = $record['value'];
                            $element['object_characteristic_id']         = $record['object_characteristic_id'];
                            $element['object_characteristic_element_id'] = $record['id'];
                        } else {
                            $ocId = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByPostInitiativeIdAndCharacteristicId($id, $characteristic['id']);
                            $element['object_characteristic_id'] = $ocId;
                        }
                    }
                }
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
