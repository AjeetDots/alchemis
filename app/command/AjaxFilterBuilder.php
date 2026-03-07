<?php

/**
 * Define the app_command_AjaxFilterBuilder class.
 * @author    David Carter <david.carter@illumen.co.uk
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Filter.php');
require_once('app/mapper/FilterMapper.php');
require_once('app/domain/FilterLine.php');
require_once('app/mapper/FilterLineMapper.php');
require_once('app/domain/FilterBuilder.php');
require_once('app/mapper/FilterBuilderMapper.php');
require_once('app/domain/TieredCharacteristic.php');
require_once('app/mapper/TieredCharacteristicMapper.php');
require_once('app/domain/Characteristic.php');
require_once('app/mapper/CharacteristicMapper.php');
require_once('app/domain/CharacteristicElement.php');
require_once('app/mapper/CharacteristicElementMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_Post objects.
 * @package Alchemis
 */
class app_command_AjaxFilterBuilder extends app_command_AjaxCommand
{

	protected $filter_builder;
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);

		$this->filter_builder = new app_domain_FilterBuilder();

		$filter_id = $this->request->item_id;

		switch ($this->request->cmd_action)
		{
			case 'update_filter_name':
				$this->filter = app_domain_Filter::find($filter_id);
				$this->filter->setName($this->request->filter_name);
				$this->filter->commit();
				break;
			case 'get_data_screen_html':
				$return = $this->makeFieldDataHTML($this->request->group_level, $this->request->field_type);
				$this->request->data_screen_html = $return['html'];
				$this->request->data_screen_type = $return['type'];
				$this->request->data_screen_target = $return['target'];
				break;
			case 'get_characteristic_data_screen_html':
				$return = $this->getCharacteristic($this->request->item_id);
				$this->request->data_screen_html = $return['html'];
				$this->request->data_screen_type = $return['type'];
				$this->request->data_screen_target = $return['target'];
				break;
			case 'get_characteristic_element_data_screen_html':
				$return = $this->getElement($this->request->item_id);
				$this->request->data_screen_html = $return['html'];
				$this->request->data_screen_type = $return['type'];
				$this->request->data_screen_target = $return['target'];
				break;
			case 'get_field_list':
				$this->request->field_list = $this->makeFieldList($this->request->group_level);
				break;
			case 'get_client_report_filters':
				$this->request->filter_list = $this->makeFilterList($this->request->client_id);
				break;
			case 'get_filter_statistics':
				$this->filter = app_domain_Filter::find($filter_id);
				$filter_lines_include = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filter_id, 'include');
				$filter_lines_exclude = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filter_id, 'exclude');
				$results_format = $this->filter->getResultsFormat();
				$this->filter_builder->makeSQLData($filter_id, $filter_lines_include, 'include');
				$this->filter_builder->makeSQLData($filter_id, $filter_lines_exclude, 'exclude');

				$t = $this->filter_builder->makeMainSQL($filter_id, false);

				app_domain_ObjectWatcher::remove($this->filter);

				$this->filter = app_domain_Filter::find($filter_id);

				$this->request->company_count = $this->filter->getCompanyCount();
				$this->request->post_count = $this->filter->getPostCount();

				// following three lines return name, results type and campaign name (optional) of the filter so these can be updated at the same time as the filter
				// statistics if required.
				$this->request->name = $this->filter->getName();
				$this->request->results_format = $this->filter->getResultsFormat();
				$this->request->campaign = $this->filter->getCampaignName();

				break;
			case 'save_filter':
			case 'save_and_load_filter':
			case 'save_as_filter':
			case 'save_as_and_load_filter':
				if ($filter_id > 0)
				{
					$this->filter = app_domain_Filter::find($filter_id);
					$this->filter->setName($this->request->filter_name);
					$this->filter->setTypeId($this->request->type_id);
					$this->filter->setResultsFormat($this->request->results_format);
					if ($this->request->campaign_id > 0)
					{
						$this->filter->setCampaignId($this->request->campaign_id);
					}
					$this->filter->setIsReportSource($this->request->is_report_source);
					$this->filter->setReportParameterDescription($this->request->report_parameter_description);
					$this->filter->commit();

					$this->filter_builder->saveLineItems($this->request->line_items_include, $this->filter->getId(), 'include');
					$this->filter_builder->saveLineItems($this->request->line_items_exclude, $this->filter->getId(), 'exclude');

				}
				else
				{
					$this->filter = new app_domain_Filter();
					$this->filter->setName($this->request->filter_name);
					$this->filter->setTypeId($this->request->type_id);
					if ($this->request->campaign_id > 0)
					{
						$this->filter->setCampaignId($this->request->campaign_id);
					}
					if ($this->filter->getCreatedBy() == '')
					{
						$this->filter->setCreatedBy($_SESSION['auth_session']['user']['id']);
					}
					$this->filter->setCreatedAt(date('Y-m-d H:i:s'));
					$this->filter->setResultsFormat($this->request->results_format);
					$this->filter->setIsReportSource($this->request->is_report_source);
					$this->filter->setReportParameterDescription($this->request->report_parameter_description);
					$this->filter->commit();

					$this->filter_builder->saveLineItems($this->request->line_items_include, $this->filter->getId(), 'include');
					$this->filter_builder->saveLineItems($this->request->line_items_exclude, $this->filter->getId(), 'exclude');
					// do this to force filter object to reload the correct campaign name - otherwise
					$filter_id = $this->filter->getId();
					app_domain_ObjectWatcher::remove($this->filter);
					$this->filter = app_domain_Filter::find($filter_id);

					$this->request->line_html = $this->getFilterListLine();
					$this->request->item_id = $this->filter->getId();
				}
				$this->request->filter_name = $this->filter->getName();
				break;
			case 'delete_filter':
				if ($filter_id)
				{
					$this->filter = app_domain_Filter::find($filter_id);
					$this->filter->markDeleted();
					$this->filter->commit();
				}
				break;
			default:
				break;
		}

		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null && isset($this->filter))
		{
			$this->request->item_id = $this->filter->getId();
		}
		array_push($this->response->data, $this->request);
	}

	/**
	 * Creates a list of fields available for a given group level (eg company).
	 * Eg. selecting 'company' returns all the fields available to query at the company level
	 * @param string $group_level
	 */
	 protected function makeFieldList($group_level)
	 {
		$labels = app_domain_FilterBuilder::getFieldSpecMap();
	 	if ($items = app_domain_FilterBuilder::getDataGroupsByParentGroup($group_level))
		{
			$options = array();
			$options[] = array(	'value' => '0',
								'text' 	=> '-- select --');
			foreach ($items as $item)
			{

				$options[] = array(	'value' => $item,
									'text' 	=> @C_String::htmlDisplay($labels[$item] ? $labels[$item] : ucfirst($item)));
			}
			return $options;
		}
	 }

    /**
     * Creates a list of filters available for a given client.
     * @param string $client_id
     */
     protected function makeFilterList($client_id)
     {
        if ($items = app_domain_Filter::findReportSourceFiltersByClientIdAndUserId($client_id, $_SESSION['auth_session']['user']['id']))
        {
        	if (count($items) > 0) {
	            $options = array();
	            $options[] = array( 'value' => '0',
	                                'text'  => '-- select --');
	            foreach ($items as $item)
	            {

	                $options[] = array( 'value' => $item['id'],
	                                    'text'  => @C_String::htmlDisplay(ucfirst($item['name'])));
	            }
        	} else {
        		$options = array();
                $options[] = array( 'value' => '0',
                                    'text'  => '-- None found --');
        	}
        } else {
        	$options = array();
            $options[] = array( 'value' => '0',
                                'text'  => '-- None found --');
        }
        return $options;
     }

	/**
	 * Creates the necessary HTML to be passed to the filter builder frontend div which offers the data available for a given field type.
	 * Eg. selecting 'telephone' returns a simple input text box
	 * @param number $field_type_id
	 */
	protected function makeFieldDataHTML($group_level, $field_type)
	{
		// lookup the relevant field construction data
		$field_spec = $this->filter_builder->fields[$group_level][$field_type];

		$html_select_operand = $this->makeOperatorSelect($field_spec['operators'], $field_spec['operators']);

		switch ($field_spec['html_type'])
		{
			case 'select':
				// get lookup data
				// set up the command objects
				if (file_exists('app/domain/' . $field_spec['domain'] . '.php'))
				{
					require_once('app/domain/' . $field_spec['domain'] . '.php');

					if (class_exists('app_domain_' . $field_spec['domain']))
					{
							$commandObjectName = 'app_domain_' . $field_spec['domain'];
							$commandObject = new $commandObjectName();
					}
					else
					{
						// raise error
						throw new Exception('Unspecified domain object');
					}

					if (isset($field_spec['sql_source_params'])) {
						$results = call_user_func_array([$commandObject, $field_spec['sql_source']], $field_spec['sql_source_params']);
					} else {
						// $results = $commandObject->$field_spec['sql_source']();
						$results = call_user_func([$commandObject, $field_spec['sql_source']]);
					}

					$options = array();
					foreach ($results as $item)
					{
						$options[$item[$field_spec['sql_value_field']]] = @C_String::htmlDisplay(ucfirst($item[$field_spec['sql_text_field']]));
					}

					$smarty = ViewHelper::getSmarty();

					$smarty->assign('html_select_id', 'where_data');
					$smarty->assign('html_select_name', 'where_data');
					$smarty->assign('html_select_style', $field_spec['html_style']);
					$smarty->assign('html_select_options',$options);
					$smarty->assign('html_select_multiple',true);
					$smarty->assign('html_select_size',12);

					$html_select_where = $smarty->fetch('html_select.tpl');

				}
				else
				{
					// raise error
					throw new Exception('Unspecified domain object');
				}

				$return['html'] = $html_select_operand . $html_select_where;
				$return['type'] = 'select';
				break;
			case 'select_boolean':
				$smarty = ViewHelper::getSmarty();

				$options = array();
				$options['false'] = 'False';
				$options['true'] = 'True';

				$smarty->assign('html_select_id', 'where_data');
				$smarty->assign('html_select_name', 'where_data');
				$smarty->assign('html_select_style', $field_spec['html_style']);
				$smarty->assign('html_select_options',$options);
				$smarty->assign('html_select_multiple',false);
				$smarty->assign('html_select_size',2);

				$html_select_where = $smarty->fetch('html_select.tpl');

				$return['html'] = $html_select_operand . $html_select_where;
				$return['type'] = 'select';
				break;
			case 'screen':
				break;
			case 'date':
				$html = $html_select_operand;
				$html .= '<input type="text" value="" id="where_data" name="where_data"/>' .
						'<input type="button" value="..." onclick="javascript:updateCal();"/>' .
						'<div id="calendar_display" style="display: none;">' .
						'<div id="calendar">' .
						'</div>' .
						'</div>';
				$return['html'] = $html;
				$return['type'] = 'date';
				break;
			case 'sub category':
				$options = app_domain_TieredCharacteristic::selectAllSubCategoriesForDropdown();

				$smarty = ViewHelper::getSmarty();

				$smarty->assign('html_select_id', 'where_data');
				$smarty->assign('html_select_name', 'where_data');
				$smarty->assign('html_select_style', $field_spec['html_style']);
				$smarty->assign('html_select_options',$options);
				$smarty->assign('html_select_multiple',true);
				$smarty->assign('html_select_size',12);

				$html_select_where = $smarty->fetch('html_select.tpl');

				$return['html'] = $html_select_operand . $html_select_where;
				$return['type'] = 'select';
				break;
			case 'sub category tier':

				$values = array(1,2,3,9);
				$output	= array(1,2,3,9);

				$smarty = ViewHelper::getSmarty();

				$smarty->assign('html_select_id', 'where_data');
				$smarty->assign('html_select_name', 'where_data');
				$smarty->assign('html_select_style', $field_spec['html_style']);
				$smarty->assign('html_select_values',$values);
				$smarty->assign('html_select_output',$output);
				$smarty->assign('html_select_multiple',false);
				$smarty->assign('html_select_size',12);

				$html_select_where = $smarty->fetch('html_select.tpl');

				$return['html'] = $html_select_operand . $html_select_where;
				$return['type'] = 'select';
				break;

			case 'review date':
				$values = array();
				$output	= array();
				for ($year = 2005; $year<=2010; $year++)
				{
					for ($month = 1; $month<=12; $month++)
					{
						$output[] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
						$values[] = $year . str_pad($month, 2, '0', STR_PAD_LEFT);
					}
				}

				$smarty = ViewHelper::getSmarty();

				$smarty->assign('html_select_id', 'where_data');
				$smarty->assign('html_select_name', 'where_data');
				$smarty->assign('html_select_style', $field_spec['html_style']);
				$smarty->assign('html_select_values',$values);
				$smarty->assign('html_select_output',$output);
				$smarty->assign('html_select_multiple',false);
				$smarty->assign('html_select_size',12);

				$html_select_where = $smarty->fetch('html_select.tpl');

				$return['html'] = $html_select_operand . $html_select_where;
				$return['type'] = 'select';
				break;


			case 'company characteristic':
				$html = $this->getObjectCharacteristics('company', $field_spec['html_style']);
				$return['html'] = $html;
				$return['type'] = 'company characteristic';
				$return['target'] = 'div_additional_data';
				break;
			case 'post characteristic':
				$html = $this->getObjectCharacteristics('post', $field_spec['html_style']);
				$return['html'] = $html;
				$return['type'] = 'post characteristic';
				$return['target'] = 'div_additional_data';
				break;
			case 'post initiative characteristic':
				$html = $this->getObjectCharacteristics('post initiative', $field_spec['html_style']);
				$return['html'] = $html;
				$return['type'] = 'post initiative characteristic';
				$return['target'] = 'div_additional_data';
				break;

			default:

//				$return['html'] = '<span style="vertical-align: top;" id="span_where_label">' . $field_spec['html_label'] . '</span>&nbsp;' . $html_select_operand . ' <input type="' . $field_spec['input_type'] . '" id="where_data" name="where_data" style="' . $field_spec['html_style'] . '"/>';
				$return['html'] = $html_select_operand . ' <input type="' . $field_spec['input_type'] . '" id="where_data" name="where_data" style="' . $field_spec['html_style'] . '"/>';
				$return['type'] = 'text';
				break;

		}
		return $return;
	}

	protected function makeResultsDisplay($results)
	{

		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();

		$smarty->assign('search_results', $results->toRawArray());

		return $smarty->fetch('FilterResultsPostInitiatives.tpl');

	}

	protected function getFilterListLine()
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('filter', $this->filter);

		$user = app_domain_RbacUser::find($_SESSION['auth_session']['user']['id']);

		if ($user->hasPermission('permission_admin_users'))
		{
			$smarty->assign('can_export', true);
		}

		return $smarty->fetch('html_FilterListLine.tpl');
	}

	protected function getObjectCharacteristics($object_type, $select_style)
	{
		$results = app_domain_Characteristic::findByType($object_type);

		$options = array();
		$options[] = array(	'value' => '0',
							'text' 	=> '-- select --');
		foreach ($results as $item)
		{

			$options[] = array(	'value' => $item->getId(),
								'text' 	=> @C_String::htmlDisplay(ucfirst($item->getName())));
		}
		return $options;

	}

	protected function getCharacteristicElementsSelectHtml($characteristic_id)
	{
		$results = app_domain_CharacteristicElement::findByCharacteristicId($characteristic_id);

		$options = array();
		$options[0] = '-- select --';
		foreach ($results as $item)
		{
			$options[$item->getId()] = @C_String::htmlDisplay(ucfirst($item->getName()));
		}

		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();

		$smarty->assign('html_select_id', 'span_where_label');
		$smarty->assign('html_select_name', 'span_where_label');
		$smarty->assign('html_select_style', 'width: 175px; vertical-align: top;');
		$smarty->assign('html_select_options', $options);
		$smarty->assign('html_select_onchange', 'javascript:getCharactersticElementDataScreenHtml(this.options[this.selectedIndex].value);');
		return $smarty->fetch('html_select.tpl');
	}

	protected function getCharacteristic($id)
	{
		// base settings
		$text_style = 'width: 200px';

		$characteristic = app_domain_Characteristic::find($id);

		if ($characteristic->hasAttributes())
		{
			$return['html'] = $this->getCharacteristicElementsSelectHtml($id);
//			$return['html'] = '<span id="where_elements">' . $html . '</span>';
		}
		else
		{
			// get data type
			$data_type = $characteristic->getDataType();
			$operators = $this->lookupOperators($data_type);
			$operator_select_html = $this->makeOperatorSelect($operators, $operators);

			switch ($data_type)
			{
				case 'text':
					$return['html'] = $operator_select_html . ' <input type="text" id="where_data" name="where_data" style="' . $text_style . '" />';
//					$return['html'] = '<span style="vertical-align: top;" id="span_where_label">' .$characteristic->getName() . '</span>&nbsp;' . $operator_select_html . ' <input type="text" id="where_data" name="where_data" style="' . $text_style . '" />';
					$return['type'] = 'text';
					break;
				case 'boolean':
//					$return['html'] = '<span style="vertical-align: top;" id="span_where_label">' .$characteristic->getName() . '</span>&nbsp;' . $operator_select_html . ' <input type="checkbox" id="where_data" name="where_data" />';
					$return['html'] = $operator_select_html . ' <select id="where_data" name="where_data"><option value="true">True</option><option value="false">False</option></select>';
					$return['type'] = 'boolean';
					break;
				case 'date':
					$html = $operator_select_html;
//					$html = '<span style="vertical-align: top;" id="span_where_label">' .$characteristic->getName() . '</span>&nbsp;' . $operator_select_html;
					$html .= '<input type="text" value="" id="where_data" name="where_data"/>' .
							'<input type="button" value="..." onclick="javascript:updateCal();"/>' .
							'<div id="calendar_display" style="display: none;">' .
							'<div id="calendar">' .
							'</div>' .
							'</div>';
					$return['html'] = $html;
					$return['type'] = 'date';
					break;
				default:
					break;
			}
			$return['html'] = '<span id="where_elements">' . $return['html'] . '</span>';
		}

		return $return;
	}

	protected function getElement($id)
	{
		// base settings
		$text_style = 'width: 200px';


		$element = app_domain_CharacteristicElement::find($id);

//		$return['html'] = $id;
//		$return['type'] = 'test';
//		$return['target'] = 'test';
//
//		return $return;


		// get data type
		$data_type = $element->getDataType();
		$operators = $this->lookupOperators($data_type);
		$operator_select_html = $this->makeOperatorSelect($operators, $operators);

		switch ($data_type)
		{
			case 'text':
				$return['html'] = $operator_select_html . ' <input type="text" id="where_data" name="where_data" style="' . $text_style . '" />';
//				$return['html'] = '<span style="vertical-align: top;" id="span_where_label">' .$element->getName() . '</span>&nbsp;' . $operator_select_html . ' <input type="text" id="where_data" name="where_data" style="' . $text_style . '" />';
				$return['type'] = 'text';
				break;
			case 'boolean':
//				$return['html'] = '<span style="vertical-align: top;" id="span_where_label">' .$characteristic->getName() . '</span>&nbsp;' . $operator_select_html . ' <input type="checkbox" id="where_data" name="where_data" />';
				$return['html'] = $operator_select_html . ' <select id="where_data" name="where_data"><option value="true">True</option><option value="false">False</option></select>';
				$return['type'] = 'boolean';
				break;
			case 'date':
//				$html = '<span style="vertical-align: top;" id="span_where_label">' .$element->getName() . '</span>&nbsp;' . $operator_select_html;
				$html = $operator_select_html;
				$html .= '<input type="text" value="" id="where_data" name="where_data"/>' .
						'<input type="button" value="..." onclick="javascript:updateCal();"/>' .
						'<div id="calendar_display" style="display: none;">' .
						'<div id="calendar">' .
						'</div>' .
						'</div>';
				$return['html'] = $html;
				$return['type'] = 'date';
				break;
			default:
				break;
		}

		$return['html'] = '<span id="where_elements">' . $return['html'] . '</span>';

		return $return;
	}

	protected function lookupOperators($data_type)
	{

		$operators = array();
		switch($data_type)
		{
			case 'text':
				$operators[] = 'starts with';
				$operators[] = 'contains';
				$operators[] = 'equals';
				break;
			case 'boolean':
				$operators[] = 'equals';
				break;
			case 'date':
				$operators[] = 'before (and including)';
				$operators[] = 'before (and not including)';
				$operators[] = 'since (and including)';
				$operators[] = 'since (and not including)';
				break;
		}

		return $operators;
	}

	protected function makeOperatorSelect($values, $text)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();

		$smarty->assign('html_select_id', 'where_operator');
		$smarty->assign('html_select_name', 'where_operator');
		$smarty->assign('html_select_style', 'width: 175px; vertical-align: top;');

		$smarty->assign('html_select_values', $values);
		$smarty->assign('html_select_output', $text);

		return $smarty->fetch('html_select.tpl');

	}
}



?>