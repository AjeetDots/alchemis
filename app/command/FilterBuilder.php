<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/FilterBuilderMapper.php');
require_once('app/domain/FilterBuilder.php');
require_once('app/mapper/FilterMapper.php');
require_once('app/domain/Filter.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/String.class.php');

class app_command_FilterBuilder extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{

		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request
	 */
	protected function init(app_controller_Request $request)
	{

		$id = $request->getProperty('id');
		$filter = app_domain_Filter::find($id);
		$request->setObject('filter',$filter);

		require_once('Auth/Session.php');
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();

		if ($user['id'] == $filter->getCreatedBy())
		{
			$is_owner = true;
		}
		else
		{
			$is_owner = false;
		}
		$request->setProperty('is_owner',$is_owner);

		$filter_lines = app_domain_Filter::findFilterLinesByFilterId($id);
		$request->setObject('filter_lines',$filter_lines);

		$labels = app_domain_FilterBuilder::getFieldSpecMap();
		// group_options
		if ($items = app_domain_FilterBuilder::getDataGroupsByParentGroup())
		{
			$options = array();
			$i = 0;
			foreach ($items as $item)
			{
				if ($i == 0)
				{
					$first_group = $item;
				}
				$i++;
				$options[$item] = @C_String::htmlDisplay($labels[$item] ? $labels[$item] : ucfirst($item));

			}
			$request->setObject('group_options', $options);
		}

		// group_options
		if ($items = app_domain_FilterBuilder::getDataGroupsByParentGroup($first_group))
		{
			$options = array();
			$options['0'] = '-- select --';

			foreach ($items as $item)
			{

				$options[$item] = @C_String::htmlDisplay($labels[$item] ? $labels[$item] : ucfirst($item));
			}
			$request->setObject('field_options', $options);
		}

		// results format options
		$options_values = array();
		$options_values[] = '0';
		$options_values[] = 'Site';
		$options_values[] = 'Site and posts';
		$options_values[] = 'Client initiative';
		$options_values[] = 'Client initiative with last note';
		$options_values[] = 'Meeting';
		$options_values[] = 'Mailer';

		$options_output = array();
		$options_output[] = '-- select --';
		$options_output[] = 'Companies & Sites (posts available)';
		$options_output[] = 'Companies, Sites & posts';
		$options_output[] = 'Client initiative';
		$options_output[] = 'Client initiative with last note';
		$options_output[] = 'Meeting';
		$options_output[] = 'Mailer';

		$options_selected = $filter->getResultsFormat();

		$request->setObject('results_format_values', $options_values);
		$request->setObject('results_format_output', $options_output);
		$request->setObject('results_format_selected', $options_selected);

		// filter type options
		$options_values = array();
		$options_values[] = '0';
		$options_values[] = '1';
		$options_values[] = '2';
		$options_values[] = '3';

		$options_output = array();
		$options_output[] = '-- select --';
		$options_output[] = 'Personal';
		$options_output[] = 'Campaign';
		$options_output[] = 'Global';

		$options_selected = $filter->getTypeId();

		$request->setObject('type_values', $options_values);
		$request->setObject('type_output', $options_output);
		$request->setObject('type_selected', $options_selected);

		// client initiative options
		$campaigns = app_domain_Campaign::findByCurrentUserId();
		$request->setObject('campaigns', $campaigns);

		// is report source checkbox
		$request->setProperty('is_report_source', $filter->getIsReportSource());

		// report parameter description
		$request->setProperty('report_parameter_description', $filter->getReportParameterDescription());

	}

}

?>