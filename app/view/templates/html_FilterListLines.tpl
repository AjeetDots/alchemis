{config_load file="example.conf"}
{foreach from=$filters item=filter}
<tr id="tr_{$filter->getId()}">
{if $filter->getTypeId() == 2}
	<td>{$filter->getId()}</td>
	<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong><br />
		({$filter->getCreatedByName()})
	</td>
	<td><span id="span_campaign_{$filter->getId()}">{$filter->getCampaignName()}</span></td>
	<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
	<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
	<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
{elseif $filter->getTypeId() == 3}
	<td>{$filter->getId()}</td>
	<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong><br />
		({$filter->getCreatedByName()})
	</td>
	<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
	<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
	<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
{else}
	<td>{$filter->getId()}</td>
	<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong></td>
	<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
	<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
	<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
{/if}
<td style="text-align: center; vertical-align: middle; background-color: #F3F3F3">
	<a id="btn_refresh_{$filter->getId()}" title="Rebuild filter using saved parameters" href="#" onclick="javascript:loadFilter({$filter->getId()}, 'build');return false;"><img src="{$APP_URL}app/view/images/icons/table_refresh.png" alt="Re-generate" title="Re-generate filter from database and display results" /></a>&nbsp;
	<a id="btn_statistics_{$filter->getId()}" title="Refresh Statistics" href="#" onclick="javascript:getFilterStatistics({$filter->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/chart_pie.png" alt="Statistics" title="Refresh statistics for this filter" /></a>&nbsp;
	<a id="btn_edit_{$filter->getId()}" title="Edit Filter" href="#" onclick="javascript:editFilter({$filter->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/table_edit.png" alt="Edit" title="Edit/view parameters for this filter" /></a>&nbsp;
	{if $can_export}
	<a id="btn_export_{$filter->getId()}" title="Export Filter" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'standard');return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export filter" /></a>&nbsp;
	<a id="btn_export_csv_{$filter->getId()}" title="Export Filter as CSV" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'standard', 'csv');return false;"><img src="{$APP_URL}app/view/images/icons/csv.gif" alt="Export CSV" title="Export filter as CSV" /></a>&nbsp;
	<a id="btn_export_meeting_format_{$filter->getId()}" title="Export Meeting Report" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'meeting_report');return false;"><img src="{$APP_URL}app/view/images/icons/chart_curve.png" alt="Export Meeting Report" title="Export Meeting Report" /></a>&nbsp;
	{/if}
	<a id="btn_delete_{$filter->getId()}" title="Delete filter" href="#" onclick="javascript:deleteFilter({$filter->getId()});return false"><img src="{$APP_URL}app/view/images/icons/table_delete.png" alt="Delete" title="Delete this filter" /></a>
</td>
</tr>
{/foreach}
