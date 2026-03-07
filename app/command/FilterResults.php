<?php

require_once('app/mapper/FilterMapper.php');
require_once('app/domain/FilterBuilder.php');
require_once('app/mapper/FilterBuilderMapper.php');
require_once('app/domain/Filter.php');
require_once('app/mapper/FilterMapper.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
/**
 * @package alchemis
 */
class app_command_FilterResults extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($request->getProperty('id') == '' || is_null($request->getProperty('id')))
		{
			// do nothing
		}
		else
		{
			$id = $request->getProperty('id');
			$filter_builder = new app_domain_FilterBuilder();

			$filter = app_domain_Filter::find($id);
			$results_format = $filter->getResultsFormat();

			// regenerate filter results and insert into tbl_filter_results
			$filter_lines_include = app_domain_Filter::findFilterLinesByFilterIdAndDirection($id, 'include');
			$filter_lines_exclude = app_domain_Filter::findFilterLinesByFilterIdAndDirection($id, 'exclude');
			$filter_builder->makeSQLData($id, $filter_lines_include, 'include');
			$filter_builder->makeSQLData($id, $filter_lines_exclude, 'exclude');
			$t = null;
			$t = $filter_builder->makeMainSQL($id, true);

			$debug = false;
			// To debug (print query results to screen), set debug = true (below)AND set debug = true on first line in app_domain_FilterBuilder:makeMainSQL
              //$debug = true;
			if ($debug) {
			 echo ($t['query']);
			 exit();
			}

			$request->setObject('results', $t['results']);
			// need to get the filter again as we may have updated the filter statistics
			// NOTE: Need to remove existing filter object from the collection and then
			// replace with a new one which will have the correct statistics
			app_domain_ObjectWatcher::remove($filter);
			$filter = app_domain_Filter::find($id);
			$request->setObject('filter', $filter);

		}
		return self::statuses('CMD_OK');
	}

}

?>