{include file="header.tpl" title="Reporting"}

<script language="JavaScript" type="text/javascript">
{literal}

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 11;

// Maintain global tab collection (tab_colln)
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(11))
{
	top.parent.tab_colln.add(11);
}
	
function runReport(report_id)
{
	highlightSelectedRow(report_id);
	loc = 'index.php?cmd=ReportParams&report_id=' + report_id;
//	alert(loc);
iframeLocation(	ifr_info, loc);
}

// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them
// back to normal when a new company and/or post is selected
var last_report_class_change_id = '';

function highlightSelectedRow(report_id)
{
	// Set the background of the selected row
	$('rpt_' + report_id).className = 'current';
	
	// Set the previously selected items to a normal background
	if (last_report_class_change_id != '' && last_report_class_change_id != report_id)
	{
		$('rpt_' + last_report_class_change_id).className = '';
	}
	last_report_class_change_id = report_id;
}

function getReportSummaries()
{
	if ($F('client_options') == 0)
	{
		alert("Please select a client");
	}
	else
	{
iframeLocation(		ifr_summary_reports, 'index.php?cmd=CampaignReportSummaries&campaign_id=' + $F('client_options'));
	}
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="60%" valign="top">

			<table id="tbl_characteristic_list" class="adminlist">
				<thead>
					<tr>
						<th style="width: 3%">ID</th>
						<th style="text-align: left">Report</th>
						<th style="width: 3%">&nbsp;</th>
						<th style="width: 3%">&nbsp;</th>
					</tr>
				</thead>
				{*{if $user->hasPermission('permission_admin_users')}*}
				{if $user->hasPermission('permission_admin_reports')}
				<tr id="rpt_1">
					<td style="text-align: center">1</td>
					<td>Alchemis Allocation Report</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_1" title="Run Report" href="#" onclick="javascript:runReport(1); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_2">
					<td style="text-align: center">2</td>
					<td>Basic Sales Team Activity Statistics</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_2" title="Run Report" href="#" onclick="javascript:runReport(2); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_3">
					<td style="text-align: center">3</td>
					<td>Source of Meetings Set</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_3" title="Run Report" href="#" onclick="javascript:runReport(3); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_4">
					<td style="text-align: center">4</td>
					<td>Sales Team Summary vs Target for Period</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_4" title="Run Report" href="#" onclick="javascript:runReport(4); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				{/if}
				<tr id="rpt_5">
					<td style="text-align: center">5</td>
					<td>Alchemis Activity Report of Conversation Notes</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_5" title="Run Report" href="#" onclick="javascript:runReport(5); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
						<a title="Run Report" href="http://212.48.87.174/birt-viewer/frameset?__report=/var/www/html/alchemis/app/birt/reports/Report5_ActivityReport.rptdesign&amp;uid={$md5_user_id}" target="_blank"><img src="{$APP_URL}app/view/images/icons/book_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
				</tr>
				<tr id="rpt_6">
					<td style="text-align: center">6</td>
					<td>Client Services Report</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_6" title="Run Report" href="#" onclick="javascript:runReport(6); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>	
				<tr id="rpt_7">
					<td style="text-align: center">7</td>
					<td>Client Clinic Report</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_7" title="Run Report" href="#" onclick="javascript:runReport(7); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>	
				<tr id="rpt_8">
					<td style="text-align: center">8</td>
					<td>Line Listing</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_8" title="Run Report" href="#" onclick="javascript:runReport(8); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
				</tr>
				<tr id="rpt_14">
					<td style="text-align: center">14</td>
					<td>NBM Bonus Detail Report</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_14" title="Run Report" href="#" onclick="javascript:runReport(14); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				{if $user->hasPermission('permission_admin_reports')}
				<tr id="rpt_9">
					<td style="text-align: center">9</td>
					<td>Sales Team Performance Against KPI Targets</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_9" title="Run Report" href="#" onclick="javascript:runReport(9); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_10">
					<td style="text-align: center">10</td>
					<td>Global Sector Analysis</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_10" title="Run Report" href="#" onclick="javascript:runReport(10); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_11">
					<td style="text-align: center">11</td>
					<td>Global Discipline Analysis</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_11" title="Run Report" href="#" onclick="javascript:runReport(11); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				<tr id="rpt_12">
					<td style="text-align: center">12</td>
					<td>Global Sector Discipline Analysis</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_12" title="Run Report" href="#" onclick="javascript:runReport(12); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				{if $user->client_id == null}
					<tr id="rpt_13">
						<td style="text-align: center">13</td>
						<td>Global NBM Bonus Report</td>
						<td style="text-align: center; vertical-align: middle">
							<a id="viewBtn_13" title="Run Report" href="#" onclick="javascript:runReport(13); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
						</td>
						<td style="text-align: center; vertical-align: middle">
						</td>
					</tr>
				{/if}
				<tr id="rpt_15">
					<td style="text-align: center">15</td>
					<td>Client Exception Report</td>
					<td style="text-align: center; vertical-align: middle">
						<a id="viewBtn_15" title="Run Report" href="#" onclick="javascript:runReport(15); return false;"><img src="{$APP_URL}app/view/images/icons/report_go.png" alt="Run Report" title="Run Report" /></a>
					</td>
					<td style="text-align: center; vertical-align: middle">
					</td>
				</tr>
				{/if}
				<tr>
					<td colspan="4">
						&nbsp;
					</td>
				</tr>		
				<tr>
					<td colspan="4">
						<form action="index.php?cmd=CampaignView" method="post" id="adminForm" name="adminForm" autocomplete="off">
							<input type="hidden" name="task" value="" />
							Report Summaries for client:
							<select name="client_options" id="client_options" style="width: 175px" onchange="javascript:getReportSummaries();">
								{html_options options=$client_options selected=$client_selected}
							</select> 
						</form>		
					</td>
				</tr>		
				<tr>
					<td colspan="4">
						<iframe id="ifr_summary_reports" name="ifr_summary_reports" src="" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
					</td>
				</tr>		
				
			</table>
		</td>
		
		<td width="40%" valign="top">
			<iframe id="ifr_info" name="ifr_info" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>

	</tr>
</table>

{include file="footer.tpl"}