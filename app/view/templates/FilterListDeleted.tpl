
{config_load file="example.conf"}

{include file="header.tpl" title="Filter List Deleted"}
{strip}

<script language="JavaScript" type="text/javascript">
{literal}

// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(9))
{
	parent.tab_colln.add(9);
}


// Following variable holds the id of the filter row which had its background changed to highlighted. We need this so we can set it
// back to normal when a new filter is selected
var last_filter_class_change_id = "";

function editFilter(filter_id)
{
	//check if we actually need to reload the filter builder
	var reload = true;
	if (iframe1.location != 'about:blank')
	{
		if (iframe1.contentWindow.$("id"))
		{
			//now check that the filter id being requested matches the id of the filter currently being built
			if (filter_id == iframe1.contentWindow.$F("id"))
			{
				reload = false;
			}
		}
		else
		{
			reload = true;
		}
	}
	else
	{
		reload = true;
	}

	if (reload)
	{
iframeLocation(		iframe1, "index.php?cmd=FilterBuilder&id=" + filter_id);
		top.responderFadeIn();	
	}
	
	$("iframe1").show();
	setActiveRow(filter_id);
}

function setActiveRow(filter_id)
{
	//set the background of the selected row
	$("tr_" + filter_id).className="current";
	
	// now set the previously selected items to a normal background
	if (last_filter_class_change_id != "" && last_filter_class_change_id != filter_id)
	{
		$("tr_" + last_filter_class_change_id).className="";
	}
	last_filter_class_change_id = filter_id;
}

function addFilter()
{
	$("iframe1").show();
iframeLocation(	iframe1, "index.php?cmd=FilterBuilderCreate");
}

function loadFilter(filter_id, action)
{
	if (top.iframe_8.colln != undefined)
	{
		top.iframe_8.colln.clear();
		while (top.iframe_8.colln.size() > 0)
		{
			// do nothing
		}
	}
	top.responderFadeIn();
	var href = "index.php?cmd=FilterResults&id=" + filter_id + "&action=" + action;
	iframeLocation(top.frames["iframe_8"], href);
	top.loadTab(8,"");
	setActiveRow(filter_id);
	$("iframe1").hide();
}

function getFilterStatistics(filter_id)
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = filter_id;
	
	getAjaxData("AjaxFilterBuilder", "", "get_filter_statistics", ill_params, "Saving...")
}

/* --- Ajax return data handlers --- */
function AjaxFilterBuilder(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "update_filter_name":
				$("edit_filter_name_" + t.item_id).innerHTML = t.filter_name;
				break;
			case "add_filter":
				addNewLine(t.item_id, t.line_html);
				break;
			case "delete_filter":
				deleteRow(t.item_id);
				break;
			case "get_filter_statistics":
				$("span_company_count_" + t.item_id).innerHTML = t.company_count;
				$("span_post_count_" + t.item_id).innerHTML = t.post_count;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function addNewLine(table_name, id, html)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	// add to row underneath headers (using 0 puts new line above header row)
	var row = tbl.insertRow(1);
	row.setAttribute("id", "tr_" + id);
	row.innerHTML = html;
}

function deleteFilter(id)
{
	if (confirm("Confirm delete?"))
	{
		$("iframe1").hide();
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxFilterBuilder", "", "delete_filter", ill_params, "Saving...")
	}
}

function deleteRow(item_id)
{
	var tbl = document.getElementById('tbl_filter_list');
	var lastRow = tbl.rows.length;
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == "tr_" + item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

function exportFilter(filter_id, format)
{
	top.responderFadeIn();
	location.href = "index.php?cmd=FilterExport&id=" + filter_id + "&format=" + format;
	setActiveRow(filter_id);
	top.responderFadeOut();
}


{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td style="vertical-align: middle">
						Deleted Filters for {$user.handle}&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="index.php?cmd=FilterList">Back To filters</a>
						&nbsp;|&nbsp;
						
					</td>
				</tr>
				<tr valign="top">
					<td>
						<div id="content-pane" class="pane-sliders">
					
							<!--Deleted Personal filters start here -->
							<div class="panel">
								<h3 class="moofx-toggler title" id="filter_type_1_count"><span>Personal ({$deleted_filters_personal_count})</span></h3>
								<div class="moofx-slider content">
									{* -- The '1' part of the id on the following table (sortable_1) is the filter type_id.
									This is used so that we can tell FilterBuilderCreate which table in FilterList to add the 
									new row to *}
									<table class="adminlist sortable" id="sortable_1" cellspacing="1">
										<thead>
											<tr>
												<th style="width: 5%">ID</th>
												<th style="width: 30%; text-align: center">Name</th>
												<th style="width: 10%; text-align: center">Results Format</th>
			{*									<th style="width: 20%; text-align: center">Created On</th>*}
												<th style="width: 5%; text-align: center">Company Count</th>
												<th style="width: 5%; text-align: center">Post Count</th>
												<th style="text-align: center">Actions</th>
											</tr>
										</thead>
										<tbody>
											
											{foreach name=fil from=$deleted_filters_personal item=filter}
											<tr id="tr_{$filter->getId()}">
												<td>{$filter->getId()}</td>
												<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong></td>
												<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
												<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
												<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
												
			{*									<td>{$filter->getCreatedAt()|date_format:"`$smarty.config.format_datetime_short`"}</td>*}
												<td style="text-align: center; vertical-align: middle; background-color: #F3F3F3">
													{*<a id="btn_display_{$filter->getId()}" title="Display current results" href="#" onclick="javascript:loadFilter({$filter->getId()}, 'reload');return false;"><img src="{$APP_URL}app/view/images/icons/table_go.png" alt="Display" title="Display currently saved results" /></a>&nbsp;*}
													<a id="Restore" title="Restore" href="index.php?cmd=FilterController&action=restore&id={$filter->getId()}"><img src="{$APP_URL}app/view/images/icons/table_add.png" alt="Restore" title="Restore" /></a>&nbsp;
													{*<a id="btn_export_{$filter->getId()}" title="Export Filter" href="index.php?cmd=FilterExport&id={$filter->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export filter" /></a>&nbsp;*}
													{if $can_export}
													<a id="btn_export_{$filter->getId()}" title="Export Filter" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'standard');return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export filter" /></a>&nbsp;
													{/if}
													{if $can_export}
													<a id="btn_export_meeting_format_{$filter->getId()}" title="Export Meeting Report" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'meeting_report');return false;"><img src="{$APP_URL}app/view/images/icons/chart_curve.png" alt="Export Meeting Report" title="Export Meeting Report" /></a>&nbsp;
													{/if}		
												</td>
											</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							</div>
							
							{* -- Deleted Campaign filters start here -- *}
							<div class="panel">
								<h3 class="moofx-toggler title" id="filter_type_2_count"><span>Campaign ({$deleted_filters_campaign_count})</span></h3>
								<div class="moofx-slider content">
									{* -- The '2' part of the id on the following table (sortable_2) is the filter type_id.
									This is used so that we can tell FilterBuilderCreate which table in FilterList to add the 
									new row to *}
									<table class="adminlist sortable" id="sortable_2" cellspacing="1">
										<thead>
											<tr>
												<th style="width: 5%">ID</th>
												<th style="width: 30%; text-align: center">Name</th>
												<th style="width: 20%; text-align: center">Campaign</th>
												<th style="width: 10%; text-align: center">Results Format</th>
			{*									<th style="width: 20%; text-align: center">Created On</th>*}
												<th style="width: 5%; text-align: center">Company Count</th>
												<th style="width: 5%; text-align: center">Post Count</th>
												<th style="text-align: center">Actions</th>
											</tr>
										</thead>
										<tbody>
											
											{foreach name=fil from=$deleted_filters_campaign item=filter}
											<tr id="tr_{$filter->getId()}">
												<td>{$filter->getId()}</td>
												<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong><br />
													({$filter->getCreatedByName()})
												</td>
												<td>
													<span id="span_campaign_{$filter->getId()}">{$filter->getCampaignName()}</span>
												</td>
												<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
												<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
												<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
												
			{*									<td>{$filter->getCreatedAt()|date_format:"`$smarty.config.format_datetime_short`"}</td>*}
												<td style="text-align: center; vertical-align: middle; background-color: #F3F3F3">
													{*<a id="btn_display_{$filter->getId()}" title="Display current results" href="#" onclick="javascript:loadFilter({$filter->getId()}, 'reload');return false;"><img src="{$APP_URL}app/view/images/icons/table_go.png" alt="Display" title="Display currently saved results" /></a>&nbsp;*}
													<a id="Restore" title="Restore" href="index.php?cmd=FilterController&action=restore&id={$filter->getId()}"><img src="{$APP_URL}app/view/images/icons/table_add.png" alt="Restore" title="Restore" /></a>&nbsp;
													{if $can_export}
													<a id="btn_export_{$filter->getId()}" title="Export Filter" href="#" onclick="javascript:exportFilter({$filter->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export filter" /></a>&nbsp;
													{/if}
													{if $can_export}
													<a id="btn_export_meeting_format_{$filter->getId()}" title="Export Meeting Report" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'meeting_report');return false;"><img src="{$APP_URL}app/view/images/icons/chart_curve.png" alt="Export Meeting Report" title="Export Meeting Report" /></a>&nbsp;
													{/if}		
												</td>
											</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							</div>
							
							{* --Deleted Global filters start here -- *}
							<div class="panel">
								<h3 class="moofx-toggler title" id="filter_type_3_count"><span>Global ({$deleted_filters_global_count})</span></h3>
								<div class="moofx-slider content">
									{* -- The '3' part of the id on the following table (sortable_3) is the filter type_id.
									This is used so that we can tell FilterBuilderCreate which table in FilterList to add the 
									new row to *}
									<table class="adminlist sortable" id="sortable_3" cellspacing="1">
										<thead>
											<tr>
												<th style="width: 5%">ID</th>
												<th style="width: 30%; text-align: center">Name</th>
												<th style="width: 10%; text-align: center">Results Format</th>
			{*									<th style="width: 20%; text-align: center">Created On</th>*}
												<th style="width: 5%; text-align: center">Company Count</th>
												<th style="width: 5%; text-align: center">Post Count</th>
												<th style="text-align: center">Actions</th>
											</tr>
										</thead>
										<tbody>
											
											{foreach name=fil from=$deleted_filters_global item=filter}
											<tr id="tr_{$filter->getId()}">
												<td>{$filter->getId()}</td>
												<td><strong><span id="span_name_{$filter->getId()}">{$filter->getName()}</span></strong><br />
													({$filter->getCreatedByName()})
												</td>
												<td><span id="span_results_format_{$filter->getId()}">{$filter->getResultsFormat()}</span></td>
												<td><span id="span_company_count_{$filter->getId()}">{$filter->getCompanyCount()}</span></td>
												<td><span id="span_post_count_{$filter->getId()}">{$filter->getPostCount()}</span></td>
												
			{*									<td>{$filter->getCreatedAt()|date_format:"`$smarty.config.format_datetime_short`"}</td>*}
												<td style="text-align: center; vertical-align: middle; background-color: #F3F3F3">
													{*<a id="btn_display_{$filter->getId()}" title="Display current results" href="#" onclick="javascript:loadFilter({$filter->getId()}, 'reload');return false;"><img src="{$APP_URL}app/view/images/icons/table_go.png" alt="Display" title="Display currently saved results" /></a>&nbsp;*}
													<a id="Restore" title="Restore" href="index.php?cmd=FilterController&action=restore&id={$filter->getId()}"><img src="{$APP_URL}app/view/images/icons/table_add.png" alt="Restore" title="Restore" /></a>&nbsp;
													{if $can_export}
													<a id="btn_export_{$filter->getId()}" title="Export Filter" href="#" onclick="javascript:exportFilter({$filter->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export filter" /></a>&nbsp;
													<a id="btn_export_meeting_format_{$filter->getId()}" title="Export Meeting Report" href="#" onclick="javascript:exportFilter({$filter->getId()}, 'meeting_report');return false;"><img src="{$APP_URL}app/view/images/icons/chart_curve.png" alt="Export Meeting Report" title="Export Meeting Report" /></a>&nbsp;
													{/if}		
												</td>
											</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							</div>
						</div>	
						<script language="JavaScript" type="text/javascript">
							init_moofx();
						</script>	
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="yes" border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>
{/strip}

{include file="footer.tpl"}
