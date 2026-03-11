{strip}
{include file="header2.tpl" title="Monthly Planner"}

<script language="JavaScript" type="text/javascript">
{literal}

// Maintain global tab collection (tab_colln)
// If this page has been loaded then we don't want to reload it when the tab is clicked
var tab_id = 2;

if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(tab_id))
{
	parent.tab_colln.add(tab_id);
}

{/literal}
</script>

<style type="text/css">
{literal}

	tr#tr_head,
	tr#tr_head th
	{
		background-color: #eeeeee;
	}

	tr#tr_head th
	{
		border-bottom: 1px solid #333;
	}

	tr#tr_total td
	{
		border-top: 1px solid #333;
		border-bottom: 1px solid #333;
		text-align: center;
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.border-left
	{
		border-left: 1px solid #bbb;
	}

{/literal}
</style>

{if $media != 'print'}
	<script language="JavaScript" type="text/javascript">
	{literal}

	function submitform(pressbutton, form_name)
	{
		var frm = $(form_name);
		frm.task.value = pressbutton;
		try
		{
			frm.onsubmit();
		}
		catch(e)
		{}
		frm.submit();
	}

	function submitbutton(pressbutton, form_name)
	{
//		alert("Here");
		if (pressbutton == 'save')
		{
			if (validation())
			{
				submitform(pressbutton, form_name);
			}
			return;
		}
	}

	function editLine(campaign_id)
	{
		var form = $('dataForm');
		var btn = $('btn_edit_' + campaign_id);
		if (btn.value.slice(0,4) == 'Edit')
		{
			btn.value = 'Save Line';
			var do_exit = true;
		}
		else
		{
			btn.value = 'Saving...';
			btn.disabled = true;
		}

		var texts = form.getInputs('text'); // -> only text inputs
		var data_to_save = new Array();
		texts.each(function(item)
			{
				var matches = item.id.match(/\d+/i);
				var item_campaign_id = matches[0];
				if (item_campaign_id === campaign_id)
				{
					 // add it to the array of items to pass back to save
					 if (btn.disabled)
					 {
					 	data_to_save.push([item.id + '-' + item.value]);
					 }
					 item.disabled = !item.disabled;
				}
			});

		if (do_exit)
		{
			return false;
		}

		var ill_params = new Object;
		ill_params.campaign_id = campaign_id;
		ill_params.user_id = $F('user_id');
		ill_params.year_month = $F('year_month');
		ill_params.form_data = data_to_save;
		ill_params.start_date = '{/literal}{$year_month_day}{literal}';
		ill_params.end_date = '{/literal}{$end_date}{literal}';

		getAjaxData("AjaxNbmCampaignTarget", "", "save_client_line", ill_params, "Saving...")
	}

	/* --- Ajax return data handlers --- */
	function AjaxNbmCampaignTarget(data)
	{
		for (i = 1; i < data.length + 1; i++)
		{
			t = data[i-1];
			switch (t.cmd_action)
			{
				case "save_client_line":
	//				alert(t.campaign_id + ' --- ' + t.return_data);
					replaceRowHtml('tbl_planner_statistics', t.campaign_id, t.return_data);
					replaceRowHtml('tbl_planner_statistics', 'total', t.return_data_total);
					break;
				default:
					alert("No cmd_action specified");
					break;
			}
		}
	}

	function replaceRowHtml(table_name, item_id, html)
	{
		var tbl = document.getElementById(table_name);
		var lastRow = tbl.rows.length;
		for (var i = 0; i < lastRow; i++)
		{
			var tempRow = tbl.rows[i];
			if (tempRow.getAttribute("id") == "tr_" + item_id)
			{
				tbl.rows[i].innerHTML = html;
				break;
			}
		}
	}

	function showCalendarDate(date, nbm_id)
	{
	//	alert('showCalendarDate(' + date + ', ' + nbm_id + ')');
	//	alert('url: ' + top.frames["iframe_3"].location.href);

		var pos = top.frames["iframe_3"].location.href.indexOf("index.php?cmd=ActionsFrame")
	//	alert('pos = ' + pos);

		if (pos < 0)
		{
			alert ('need to load 3');
iframeLocation(			top.frames["iframe_3"], "index.php?cmd=ActionsFrame");
		}
	//	alert("window: " + top.frames["iframe_3"].frames["ifr_admin"]);
	//	alert(top.frames["iframe_3"].location.href);
iframeLocation(		top.frames["iframe_3"].frames["ifr_admin"], "index.php?cmd=Calendar&date=" + date + '&nbm_id=' + nbm_id);
		top.loadTab(3, "", false);
	//	alert ('done');
	}

	{/literal}
	</script>
{/if}

	<table border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr cfg">

			<td colspan="3">
				{if $media != 'print'}
					<form action="index.php?cmd=NbmMonthlyPlanner" method="post" id="headerForm" name="headerForm" autocomplete="off">
						<input type="hidden" name="task" value="" />
				{/if}
						Monthly Planner for
						<select name="user_options" id="user_options" style="width: 175px"{if $user_options|@count == 2 || $media == 'print'} disabled="disabled"{/if}>
							{html_options options=$user_options selected=$user_selected}
						</select>
						for
					{if $media == 'print'}
						{html_select_date time=$year_month_day display_days=false end_year="+1" start_year="-2" all_extra='disabled="disabled"'}
					{else}
						{html_select_date time=$year_month_day display_days=false end_year="+1" start_year="-2"}
						<input type="button" value="Go" onclick="javascript:submitform('', 'headerForm');" />
					{/if}
				{if $media != 'print'}
					</form>
				{/if}
			</td>

		</tr>
		<tr valign="top">
			<td>
				<form action="index.php?cmd=NbmMonthlyPlanner" method="post" id="dataForm" name="dataForm" autocomplete="off">
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="user_id" id="user_id" value="{$user_selected}" />
				<input type="hidden" name="year_month" id="year_month" value="{$year_month}" />
				<table id="tbl_planner_statistics" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr id="tr_head">
							<th style="width: 10%; font-weight: normal; text-align: left; vertical-align: bottom; padding-bottom: 8px; padding-left: 2px; white-space: nowrap">
								Client
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Campaign Month" text="The campaign month."}>
								<img src="{$APP_URL}app/view/images/column_headers/campaign_month.gif" alt="Campaign Month" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Cumulative Target Set" text="The target cumulative total of meets set."}>
								<img src="{$APP_URL}app/view/images/column_headers/cumulative_target_set.gif" alt="Cumulative Target Set" />
							</th>
							<th style="width: 35px" {popup caption="Cumulative Total Set" text="The actual cumulative total of meets set."}>
								<img src="{$APP_URL}app/view/images/column_headers/cumulative_total_set.gif" alt="Cumulative Total Set" />
							</th>
							<th style="width: 35px" {popup caption="Total Set Deficit or Surplus" text="The cumulative deficit or surplus of meets set."}>
								<img src="{$APP_URL}app/view/images/column_headers/total_set_deficit_or_surplus.gif" alt="Total Set Deficit or Surplus" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Cumulative Target Attended" text="The target cumulative total of meets attended."}>
								<img src="{$APP_URL}app/view/images/column_headers/cumulative_target_attended.gif" alt="Cumulative Target Attended" />
							</th>
							<th style="width: 35px" {popup caption="Cumulative Total Attended" text="The actual cumulative total of meets attended."}>
								<img src="{$APP_URL}app/view/images/column_headers/cumulative_total_attended.gif" alt="Cumulative Total Attended" />
							</th>
							<th style="width: 35px" {popup caption="Total Attended Deficit or Surplus" text="The cumulative deficit or surplus of meets attended."}>
								<img src="{$APP_URL}app/view/images/column_headers/total_attended_deficit_or_surplus.gif" alt="Total Attended Deficit or Surplus" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Required Days" text="Number of days suggested by software on basis of previous effective and conversion rate for each client to achieve the campaign set target."}>
								<img src="{$APP_URL}app/view/images/column_headers/required_days.gif" alt="Required Days" />
							</th>
							<th style="width: 35px" {popup caption="Planned Days" text="Inputted by NBM at beginning of month."}>
								<img src="{$APP_URL}app/view/images/column_headers/planned_days.gif" alt="Planned Days" />
							</th>
							<th style="width: 35px" {popup caption="Call Days Actual" text="Number of days spent so far in month based on 10 effectives per day.<br /><br />E.g. 25 effectives = 2.5 days"}>
								<img src="{$APP_URL}app/view/images/column_headers/call_days_actual.gif" alt="Call Days Actual" />
							</th>
							<th style="width: 35px" {popup caption="Project Management Days" text="Time spent on going to client meets. This would need to come from the client page and would be based on the figure the NBM submits for 'project management'."}>
								<img src="{$APP_URL}app/view/images/column_headers/project_management_days.gif" alt="Project Management Days" />
							</th>
							<th style="width: 35px" {popup caption="Total Days" text="Sum of call days and project managenment days.<br /><br />Call Days Actual + Project Management Days"}>
								<img src="{$APP_URL}app/view/images/column_headers/total_days.gif" alt="Total Days" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Actual Calls" text="Straight from database."}>
								<img src="{$APP_URL}app/view/images/column_headers/actual_calls.gif" alt="Actual Calls" />
							</th>
							<th style="width: 35px" {popup caption="Required Effectives" text="As with Required Days, this is the number of effectives suggested by the system to achieve the campaign target."}>
								<img src="{$APP_URL}app/view/images/column_headers/required_effectives.gif" alt="Required Effectives" />
							</th>
							<th style="width: 35px" {popup caption="Target Effectives" text="Inputted by NBM at beginning of month."}>
								<img src="{$APP_URL}app/view/images/column_headers/target_effectives.gif" alt="Target Effectives" />
							</th>
							<th style="width: 35px" {popup caption="Off Target Effectives" text="Straight from database."}>
								<img src="{$APP_URL}app/view/images/column_headers/off_target_effectives.gif" alt="Off Target Effectives" />
							</th>
							<th style="width: 35px" {popup caption="On Target Effectives" text="Straight from database."}>
								<img src="{$APP_URL}app/view/images/column_headers/on_target_effectives.gif" alt="On Target Effectives" />
							</th>
							<th style="width: 35px" {popup caption="Off Target Effectives" text="Straight from database, but also the sum of on and off target effectives.<br /><br />Off Target Effectives + On Target Effectives"}>
								<img src="{$APP_URL}app/view/images/column_headers/actual_effectives.gif" alt="Actual Effectives" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Standard Campaign Target" text="Required number of set meets suggested by computer to hit campaign target."}>
								<img src="{$APP_URL}app/view/images/column_headers/standard_campaign_target.gif" alt="Standard Campaign Target" />
							</th>
							<th style="width: 35px" {popup caption="This Months Meeting Target" text="Inputted by NBM at beginning of month."}>
								<img src="{$APP_URL}app/view/images/column_headers/this_months_meeting_target.gif" alt="This Months Meeting Target" />
							</th>
							<th style="width: 35px" {popup caption="Imperative Meeting Set Target" text="Inputted by NBM at beginning of month."}>
								<img src="{$APP_URL}app/view/images/column_headers/imperative_meet_set_target.gif" alt="Imperative Meeting Set Target" />
							</th>
							<th style="width: 35px" {popup caption="Meetings Set Actual" text="Straight from database."}>
								<img src="{$APP_URL}app/view/images/column_headers/meetings_set_actual.gif" alt="Meetings Set Actual" />
							</th>
							<th style="width: 35px" {popup caption="Set Deficit or Surplus" text="Meetings Set Actual - Imperative Meeting Set Target"}>
								<img src="{$APP_URL}app/view/images/column_headers/set_deficit_or_surplus.gif" alt="Set Deficit or Surplus" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="0-3 Weeks" text="Timelag between date meet set and date due to be attended. Straight from the database. If meet set under 21 days from when it's due to happen then the meet set goes in this column."}>
								<img src="{$APP_URL}app/view/images/column_headers/0_weeks.gif" alt="0-3 Weeks" />
							</th>
							<th style="width: 35px" {popup caption="3-5 Weeks" text="Timelag between date meet set and date due to be attended. Straight from the database. If meet set between 21 and 35 days from when it's due to happen then the meet set goes in this column."}>
								<img src="{$APP_URL}app/view/images/column_headers/3_weeks.gif" alt="3-5 Weeks" />
							</th>
							<th style="width: 35px" {popup caption="5-7 Weeks" text="Timelag between date meet set and date due to be attended. Straight from the database. If meet set between 35 and 49 days from when it's due to happen then the meet set goes in this column."}>
								<img src="{$APP_URL}app/view/images/column_headers/5_weeks.gif" alt="5-7 Weeks" />
							</th>
							<th style="width: 35px" {popup caption="7+ Weeks" text="Timelag between date meet set and date due to be attended. Straight from the database. If meet set more than 49 days from when it's due to happen then the meet set goes in this column."}>
								<img src="{$APP_URL}app/view/images/column_headers/7_weeks.gif" alt="7+ Weeks" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Campaign Meetings Attended Target" text="System-suggested attended target for close of month based on original expectations."}>
								<img src="{$APP_URL}app/view/images/column_headers/campaign_meets_attended_target.gif" alt="Campaign Meetings Attended Target" />
							</th>
							<th style="width: 35px" {popup caption="Meetings In Diary This Month" text="The number of meets currently in the diary for this month by client. Knowing how many meets are in diary helps us plan how many more meets we need."}>
								<img src="{$APP_URL}app/view/images/column_headers/meets_in_diary_for_current_month.gif" alt="Meetings In Diary This Month" />
							</th>
							<th style="width: 35px" {popup caption="Meetings Attended Target" text="Inputted by NBM at beginning of month."}>
								<img src="{$APP_URL}app/view/images/column_headers/meets_attended_target.gif" alt="Meetings Attended Target" />
							</th>
							<th style="width: 35px" {popup caption="Meetings Attended Actual" text="Straight from database. Number of meetings attended in month."}>
								<img src="{$APP_URL}app/view/images/column_headers/meets_attended_actual.gif" alt="Meetings Attended Actual" />
							</th>
							<th style="width: 35px" {popup caption="Attended Deficit or Surplus" text="Compared with the campaign attended target, are we ahead or behind?"}>
								<img src="{$APP_URL}app/view/images/column_headers/attended_deficit_or_surplus.gif" alt="Attended Deficit or Surplus" />
							</th>
							<th style="width: 80px" class="border-left" {popup caption="Delivered" text="Have we delivered against the meets attended target?"}>
								<img src="{$APP_URL}app/view/images/column_headers/delivered.gif" alt="Delivered" />
							</th>
							<th style="width: 35px" {popup caption="Fee" text="Client fee if known."}>
								<img src="{$APP_URL}app/view/images/column_headers/fee.gif" alt="Fee" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Conversion Rate" text="Conversion rate of effectives for a client that become meet set.<br /><br />(Meets / Effectives) x 100"}>
								<img src="{$APP_URL}app/view/images/column_headers/conversion_rate.gif" alt="Conversion Rate" />
							</th>
							<th style="width: 35px" {popup caption="Access Rate" text="Percentage of calls which are effective.<br /><br />(Effectives / Calls) x 100"}>
								<img src="{$APP_URL}app/view/images/column_headers/access_rate.gif" alt="Access Rate" />
							</th>
							<th style="width: 35px" class="border-left" {popup caption="Revenue per Day - Profitability" text="Client fee divided by days spent on it - this figure only applies at the end of the month. If less than 3.5 days then profitable, if more than 5 days then unprofitable."}>
								<img src="{$APP_URL}app/view/images/column_headers/revenue_per_day_profitability.gif" alt="Revenue per Day - Profitability" />
							</th>
							<th style="width: 35px" {popup caption="Average Effectives Per Day" text="Average effectives per day for the month.<br /><br />Actual Effectives / Call Days Actual"}>
								<img src="{$APP_URL}app/view/images/column_headers/average_effectives_per_day.gif" alt="Average Effectives Per Day" />
							</th>
							{if $media != 'print'}
								<th style="vertical-align: top; padding-right: 5px" class="border-left">&nbsp;
									<p style="text-align: right"><a href="index.php?cmd=NbmMonthlyPlanner&amp;user_options={$user_selected}&amp;Date_Year={$year}&amp;Date_Month={$month}&amp;media=print" target="_blank"><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" title="Print" /> Print</a></p>
								</th>
							{/if}
						</tr>
					</thead>
					<tbody>
						{foreach name=planning_data from=$planning_data item=data}
							{assign var="row_call_days_actual" value=$data.call_days_actual|scalar:0}
							{assign var="row_project_management_days" value=$data.project_management_days|scalar:0}
							<tr id="tr_{$data.campaign_id}" style="text-align:center; background-color: {cycle values="#ddd,#eee"};">
								<td style="text-align: left">
									{math assign=delivered equation="x - y" x=$data.meeting_category_attended_count y=$data.standard_campaign_meeting_category_attended_target}
									{if $delivered > 0}
										<span style="color: green"><strong>{$data.client_name}</strong></span>
									{elseif $delivered == 0}
										<strong>{$data.client_name}</strong>
									{elseif $delivered < 0}
										<span style="color: red"><strong>{$data.client_name}</strong></span>
									{/if}
								</td>
								<td class="border-left">
									{* Campaign Month *}
									{$data.campaign_current_month}
								</td>
								<td class="border-left">
									{* Cumulative Target Set *}
									{$data.campaign_meeting_set_target_to_date}
								</td>
								<td>
									{* Cumulative Total Set *}
									{$data.campaign_meeting_set_to_date_count}
								</td>
								<td {popup text="`$data.campaign_meeting_set_to_date_count` - `$data.campaign_meeting_set_target_to_date`"}>
									{* Total Set Deficit or Surplus *}
									{math assign=number equation="x - y" x=$data.campaign_meeting_set_to_date_count y=$data.campaign_meeting_set_target_to_date}
									{if $number > 0}
										<span style="color: green">{$number}</span>
									{elseif $number == 0}
										{$number}
									{elseif $number < 0}
										<span style="color: red">{$number}</span>
									{/if}
								</td>
								<td class="border-left">
									{* Cumulative Target Attended *}
									{$data.campaign_meeting_category_attended_target_to_date}
								</td>
								<td>
									{* Cumulative Total Attended *}
									{$data.campaign_meeting_category_attended_to_date_count}
								</td>
								<td {popup text="`$data.campaign_meeting_category_attended_to_date_count` - `$data.campaign_meeting_category_attended_target_to_date`"}>
									{* Total Attended Deficit or Surplus *}
									{math assign=number equation="x - y" x=$data.campaign_meeting_category_attended_to_date_count y=$data.campaign_meeting_category_attended_target_to_date}
									{if $number > 0}
										<span style="color: green">{$number}</span>
									{elseif $number == 0}
										{$number}
									{elseif $number < 0}
										<span style="color: red">{$number}</span>
									{/if}
								</td>
								<td class="border-left">
									{* Required Days *}
									&nbsp;
								</td>
								<td>
									{* Planned Days *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.planned_days}" id="{$data.campaign_id}-planned_days" name="{$data.campaign_id}-planned_days" disabled="disabled" />
								</td>
								<td {popup text=$data.popup_call_days_text}>
									{* Call Days Actual *}
									{$row_call_days_actual}
								</td>
								<td>
									{* Project Management Days *}
									<input type="text" style="text-align: center; width: 30px" value="{$row_project_management_days}" id="{$data.campaign_id}-project_management_days" name="{$data.campaign_id}-project_management_days" disabled="disabled" />
								</td>
								{math assign=total_days equation="x + y" x=$row_call_days_actual y=$row_project_management_days}
								<td {popup text=$data.popup_total_days_text}>
									{* Total Days *}
									{$total_days}
								</td>
								<td class="border-left">
									{* Actual Calls *}
									{$data.call_count}
								</td>
								<td>
									{* Required Effectives *}
									&nbsp;
								</td>
								<td>
									{* Target Effectives *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.effectives_target}" id="{$data.campaign_id}-effectives_target" name="{$data.campaign_id}-effectives_target" disabled="disabled" />
								</td>
								<td>
									{* Off Target Effectives *}
									{$data.offte}
								</td>
								<td>
									{* On Target Effectives *}
									{$data.ote}
								</td>
								<td>
									{* Actual Effectives *}
									{math assign=actual_effectives equation="x + y" x=$data.offte y=$data.ote}
									{$actual_effectives}
								</td>
								<td class="border-left">
									{* Standard Campaign Target *}
									{$data.standard_campaign_meeting_set_target}
								</td>
								<td>
									{* This Month's Meeting Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_set_target}"  id="{$data.campaign_id}-meetings_set_target" name="{$data.campaign_id}-meetings_set_target" disabled="disabled" />
								</td>
								<td>
									{* Imperative Meet Set Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_set_imperative_target}" id="{$data.campaign_id}-meetings_set_imperative_target" name="{$data.campaign_id}-meetings_set_imperative_target" disabled="disabled" />
								</td>
								<td>
									{* Meetings Set Actual *}
									{$data.meetings_set}
								</td>
								<td {popup text="`$data.meetings_set` - `$data.meetings_set_target`"}>
									{* Set Deficit *}
									{math assign=set_deficit equation="x - y" x=$data.meetings_set y=$data.meetings_set_target}
									{if $set_deficit > 0}
										<span style="color: green">{$set_deficit}</span>
									{elseif $set_deficit == 0}
										{$set_deficit}
									{elseif $set_deficit < 0}
										<span style="color: red">{$set_deficit}</span>
									{/if}
								</td>
								<td class="border-left">
									{* 0-3 weeks *}
									{$data.meeting_time_lag_0_3}
								</td>
								<td>
									{* 3-5 weeks *}
									{$data.meeting_time_lag_3_5}
								</td>
								<td>
									{* 5-7 weeks *}
									{$data.meeting_time_lag_5_7}
								</td>
								<td>
									{* 7+ weeks *}
									{$data.meeting_time_lag_7_}
								</td>
								<td class="border-left">
									{* Campaign Meets Attended Target *}
									{if $data.standard_campaign_meeting_category_attended_target > 0}
										<span style="color: red">{$data.standard_campaign_meeting_category_attended_target}</span>
									{elseif $data.standard_campaign_meeting_category_attended_target == 0}
										{$data.standard_campaign_meeting_category_attended_target}
									{elseif $data.standard_campaign_meeting_category_attended_target < 0}
										<span style="color: green">{$data.standard_campaign_meeting_category_attended_target}</span>
									{/if}
								</td>
								<td>
									{* Meets in Diary for Current Month *}
									{$data.meetings_in_diary_this_month}
								</td>
								<td>
									{* Meets Attended Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_attended_target}" id="{$data.campaign_id}-meetings_attended_target" name="{$data.campaign_id}-meetings_attended_target" disabled="disabled" />
								</td>
								<td>
									{* Meets Attended Actual *}
									{$data.meeting_category_attended_count}
								</td>
								<td>
									{* Attended Deficit or Surplus *}
									{$delivered}
								</td>
								<td class="border-left">
									{* Delivered *}
									{if $delivered > 0}
										<span style="color: green">Yes &amp; more</span>
									{elseif $delivered == 0}
										Yes
									{elseif $delivered < 0}
										<span style="color: red">No</span>
									{/if}
								</td>
								<td>
									{* Fee *}
									{$data.campaign_monthly_fee|string_format:"&pound;%d"}
								</td>
								<td class="border-left" {popup text="(`$data.meetings_set` / `$actual_effectives`) x 100"}>
									{* Conversion Rate *}
									{$data.conversion_rate}%
								</td>
								<td {popup text="(`$actual_effectives` / `$data.call_count`) x 100"}>
									{* Access Rate *}
									{$data.access_rate}%
								</td>
								<td class="border-left">
									{* Revenue Per Day - Profitability *}
									&nbsp;
								</td>
								<td {popup text=$data.popup_avg_effectives_text}>
									{* Average Effectives Per Day *}
									{$data.average_effectives_per_day}
								</td>
								{if $media != 'print'}
									<td style="text-align: left" class="border-left">
										<input type="button" id="btn_edit_{$data.campaign_id}" value="Edit Line" onclick="javascript:editLine('{$data.campaign_id}');" />
									</td>
								{/if}
							</tr>
						{/foreach}
							<tr>
								<td colspan="40">&nbsp;</td>
							</tr>
						{* -- Clients with zero in effectives target and meetings target -- *}
						{foreach name=planning_data from=$planning_data_zero_targets item=data}
							{assign var="row_call_days_actual" value=$data.call_days_actual|scalar:0}
							{assign var="row_project_management_days" value=$data.project_management_days|scalar:0}
							<tr id="tr_{$data.campaign_id}" style="text-align:center; background-color: {cycle values="#ddd,#eee"};">
								<td style="text-align: left">
									{math assign=delivered equation="x - y" x=$data.meeting_category_attended_count y=$data.standard_campaign_meeting_category_attended_target}
									{if $delivered > 0}
										<span style="color: green"><strong>{$data.client_name}</strong></span>
									{elseif $delivered == 0}
										<strong>{$data.client_name}</strong>
									{elseif $delivered < 0}
										<span style="color: red"><strong>{$data.client_name}</strong></span>
									{/if}
								</td>
								<td class="border-left">
									{* Campaign Month *}
									{$data.campaign_current_month}
								</td>
								<td class="border-left">
									{* Cumulative Target Set *}
									{$data.campaign_meeting_set_target_to_date}
								</td>
								<td>
									{* Cumulative Total Set *}
									{$data.campaign_meeting_set_to_date_count}
								</td>
								<td {popup text="`$data.campaign_meeting_set_to_date_count` - `$data.campaign_meeting_set_target_to_date`"}>
									{* Total Set Deficit or Surplus *}
									{math assign=number equation="x - y" x=$data.campaign_meeting_set_to_date_count y=$data.campaign_meeting_set_target_to_date}
									{if $number > 0}
										<span style="color: green">{$number}</span>
									{elseif $number == 0}
										{$number}
									{elseif $number < 0}
										<span style="color: red">{$number}</span>
									{/if}
								</td>
								<td class="border-left">
									{* Cumulative Target Attended *}
									{$data.campaign_meeting_category_attended_target_to_date}
								</td>
								<td>
									{* Cumulative Total Attended *}
									{$data.campaign_meeting_category_attended_to_date_count}
									</td>
								<td {popup text="`$data.campaign_meeting_category_attended_to_date_count` - `$data.campaign_meeting_category_attended_target_to_date`"}>
									{* Total Attended Deficit or Surplus *}
									{math assign=number equation="x - y" x=$data.campaign_meeting_category_attended_to_date_count y=$data.campaign_meeting_category_attended_target_to_date}
									{if $number > 0}
										<span style="color: green">{$number}</span>
									{elseif $number == 0}
										{$number}
									{elseif $number < 0}
										<span style="color: red">{$number}</span>
									{/if}
								</td>
								<td class="border-left">
									{* Required Days *}
									&nbsp;
								</td>
								<td>
									{* Planned Days *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.planned_days}" id="{$data.campaign_id}-planned_days" name="{$data.campaign_id}-planned_days" disabled="disabled" />
								</td>
								<td {popup text=$data.popup_call_days_text}>
									{* Call Days Actual *}
									{$row_call_days_actual}
								</td>
								<td>
									{* Project Management Days *}
									<input type="text" style="text-align: center; width: 30px" value="{$row_project_management_days}" id="{$data.campaign_id}-project_management_days" name="{$data.campaign_id}-project_management_days" disabled="disabled" />
								</td>
								{math assign=total_days equation="x + y" x=$row_call_days_actual y=$row_project_management_days}
								<td {popup text=$data.popup_total_days_text}>
									{* Total Days *}
									{$total_days}
								</td>
								<td class="border-left">
									{* Actual Calls *}
									{$data.call_count}
								</td>
								<td>
									{* Required Effectives *}
									&nbsp;
								</td>
								<td>
									{* Target Effectives *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.effectives_target}" id="{$data.campaign_id}-effectives_target" name="{$data.campaign_id}-effectives_target" disabled="disabled" />
								</td>
								<td>
									{* Off Target Effectives *}
									{$data.offte}
								</td>
								<td>
									{* On Target Effectives *}
									{$data.ote}
								</td>
								<td>
									{* Actual Effectives *}
									{math assign=actual_effectives equation="x + y" x=$data.offte y=$data.ote}
									{$actual_effectives}
								</td>
								<td class="border-left">
									{* Standard Campaign Target *}
									{$data.standard_campaign_meeting_set_target}
								</td>
								<td>
									{* This Month's Meeting Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_set_target}"  id="{$data.campaign_id}-meetings_set_target" name="{$data.campaign_id}-meetings_set_target" disabled="disabled" />
								</td>
								<td>
									{* Imperative Meet Set Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_set_imperative_target}" id="{$data.campaign_id}-meetings_set_imperative_target" name="{$data.campaign_id}-meetings_set_imperative_target" disabled="disabled" />
								</td>
								<td>
									{* Meetings Set Actual *}
									{$data.meetings_set}
								</td>
								<td {popup text="`$data.meetings_set` - `$data.meetings_set_target`"}>
									{* Set Deficit *}
									{math assign=set_deficit equation="x - y" x=$data.meetings_set y=$data.meetings_set_target}
									{if $set_deficit > 0}
										<span style="color: green">{$set_deficit}</span>
									{elseif $set_deficit == 0}
										{$set_deficit}
									{elseif $set_deficit < 0}
										<span style="color: red">{$set_deficit}</span>
									{/if}
								</td>
								<td class="border-left">
									{* 0-3 weeks *}
									{$data.meeting_time_lag_0_3}
								</td>
								<td>
									{* 3-5 weeks *}
									{$data.meeting_time_lag_3_5}
								</td>
								<td>
									{* 5-7 weeks *}
									{$data.meeting_time_lag_5_7}
								</td>
								<td>
									{* 7+ weeks *}
									{$data.meeting_time_lag_7_}
								</td>
								<td class="border-left">
									{* Campaign Meets Attended Target *}
									{if $data.standard_campaign_meeting_category_attended_target > 0}
										<span style="color: red">{$data.standard_campaign_meeting_category_attended_target}</span>
									{elseif $data.standard_campaign_meeting_category_attended_target == 0}
										{$data.standard_campaign_meeting_category_attended_target}
									{elseif $data.standard_campaign_meeting_category_attended_target < 0}
										<span style="color: green">{$data.standard_campaign_meeting_category_attended_target}</span>
									{/if}
								</td>
								<td>
									{* Meets in Diary for Current Month *}
									{$data.meetings_in_diary_this_month}
								</td>
								<td>
									{* Meets Attended Target *}
									<input type="text" style="text-align: center; width: 30px" value="{$data.meetings_attended_target}" id="{$data.campaign_id}-meetings_attended_target" name="{$data.campaign_id}-meetings_attended_target" disabled="disabled" />
								</td>
								<td>
									{* Meets Attended Actual *}
									{$data.meeting_category_attended_count}
								</td>
								<td>
									{* Attended Deficit or Surplus *}
									{$delivered}
								</td>
								<td class="border-left">
									{* Delivered *}
									{if $delivered > 0}
										<span style="color: green">Yes &amp; more</span>
									{elseif $delivered == 0}
										Yes
									{elseif $delivered < 0}
										<span style="color: red">No</span>
									{/if}
								</td>
								<td>
									{* Fee *}
									{$data.campaign_monthly_fee|string_format:"&pound;%d"}
								</td>
								<td class="border-left" {popup text="(`$data.meetings_set` / `$actual_effectives`) x 100"}>
									{* Conversion Rate *}
									{$data.conversion_rate}%
								</td>
								<td {popup text="(`$actual_effectives` / `$data.call_count`) x 100"}>
									{* Access Rate *}
									{$data.access_rate}%
								</td>
								<td class="border-left">
									{* Revenue Per Day - Profitability *}
									&nbsp;
								</td>
								<td {popup text=$data.popup_avg_effectives_text}>
									{* Average Effectives Per Day *}
									{$data.average_effectives_per_day}
								</td>
								{if $media != 'print'}
									<td style="text-align: left" class="border-left">
										<input type="button" id="btn_edit_{$data.campaign_id}" value="Edit Line" onclick="javascript:editLine('{$data.campaign_id}');" />
									</td>
								{/if}
							</tr>
						{/foreach}
						{assign var="total_call_days" value=$planning_data_total.call_days_actual|scalar:0}
						{assign var="total_pm_days" value=$planning_data_total.project_management_days|scalar:0}
						<tr id="tr_total" style="background-color: #eee">
							<td style="text-align: left">
								<strong>Client Totals</strong>
							</td>
							<td class="border-left">
								{* campaign month *}
								&nbsp;
							</td>
							<td class="border-left">
								{* Cumulative Target Set *}
								{$planning_data_total.campaign_meeting_set_target_to_date}
							</td>
							<td>
								{* Cumulative Total Set *}
								{$planning_data_total.campaign_meeting_set_to_date_count}
							</td>
							<td {popup text="`$planning_data_total.campaign_meeting_set_to_date_count` - `$planning_data_total.campaign_meeting_set_target_to_date`"}>
								{* Total Set Deficit or Surplus *}
								{math assign=number equation="x - y" x=$planning_data_total.campaign_meeting_set_to_date_count y=$planning_data_total.campaign_meeting_set_target_to_date}
								{if $number > 0}
									<span style="color: green">{$number}</span>
								{elseif $number == 0}
									{$number}
								{elseif $number < 0}
									<span style="color: red">{$number}</span>
								{/if}
							</td>
							<td class="border-left">
								{* Cumulative Target Attended *}
								{$planning_data_total.campaign_meeting_category_attended_target_to_date}
							</td>
							<td>
								{* Cumulative Total Attended *}
								{$planning_data_total.campaign_meeting_category_attended_to_date_count}
							</td>
							<td {popup text="`$planning_data_total.campaign_meeting_category_attended_to_date_count` - `$planning_data_total.campaign_meeting_category_attended_target_to_date`"}>
								{* Total Attended Deficit or Surplus *}
								{math assign=number equation="x - y" x=$planning_data_total.campaign_meeting_category_attended_to_date_count y=$planning_data_total.campaign_meeting_category_attended_target_to_date}
								{if $number > 0}
									<span style="color: green">{$number}</span>
								{elseif $number == 0}
									{$number}
								{elseif $number < 0}
									<span style="color: red">{$number}</span>
								{/if}
							</td>
							<td class="border-left">
								{* Required Days *}
								&nbsp;
							</td>
							<td>
								{* Planned Days *}
								{$planning_data_total.planned_days}
							</td>
							<td {popup text="Effectives / 10 = Call Days<br /><br />`$planning_data_total.effectives` / 10 = `$total_call_days`"}>
								{* Call Days Actual *}
								{$total_call_days}
							</td>
							<td>
								{* Project Managment Days *}
								{$total_pm_days}
							</td>
							{math assign=total_days equation="x + y" x=$total_pm_days y=$total_call_days}
							<td {popup text="{$total_call_days} + {$total_pm_days} = {$total_days}"}>
								{* Total Days *}
								{$total_days}
							</td>
							<td class="border-left">
								{* Actual Calls *}
								{$planning_data_total.call_count}
							</td>
							<td>
								{* Required Effectives *}
								&nbsp;
							</td>
							<td>
								{* Target Effectives *}
								{$planning_data_total.effectives_target}
							</td>
							<td>
								{* Off Target Effectives *}
								{$planning_data_total.offte}
							</td>
							<td>
								{* On Target Effectives *}
								{$planning_data_total.ote}
							</td>
							<td>
								{* Actual Effectives *}
								{math assign=actual_effectives equation="x + y" x=$planning_data_total.offte y=$planning_data_total.ote}
								{$actual_effectives}
							</td>
							<td class="border-left">
								{* Standard Campaign Target *}
								{$planning_data_total.standard_campaign_meeting_set_target}
							</td>
							<td>
								{* This Month's Meeting Target *}
								{$planning_data_total.meetings_set_target}
							</td>
							<td>
								{* Imperative Meet Set Target *}
								{$planning_data_total.meetings_set_imperative_target}
							</td>
							<td>
								{* Meetings Set Actual *}
								{$planning_data_total.meetings_set}
							</td>
							<td>
								{* Set Deficit *}
								{math assign=set_deficit equation="x - y" x=$planning_data_total.meetings_set y=$planning_data_total.meetings_set_target}
								{if $set_deficit > 0}
									<span style="color: green">{$set_deficit}</span>
								{elseif $set_deficit == 0}
									{$set_deficit}
								{elseif $set_deficit < 0}
									<span style="color: red">{$set_deficit}</span>
								{/if}
							</td>
							<td class="border-left">
								{* 0-3 weeks *}
								{$planning_data_total.meeting_time_lag_0_3}
							</td>
							<td>
								{* 3-5 weeks *}
								{$planning_data_total.meeting_time_lag_3_5}
							</td>
							<td>
								{* 5-7 weeks *}
								{$planning_data_total.meeting_time_lag_5_7}
							</td>
							<td>
								{* 7+ weeks *}
								{$planning_data_total.meeting_time_lag_7_}
							</td>
							<td class="border-left">
								{* Campaign Meets Attended Target *}
								{if $planning_data_total.standard_campaign_meeting_category_attended_target > 0}
									<span style="color: red">{$planning_data_total.standard_campaign_meeting_category_attended_target}</span>
								{elseif $planning_data_total.standard_campaign_meeting_category_attended_target == 0}
									{$planning_data_total.standard_campaign_meeting_category_attended_target}
								{elseif $planning_data_total.standard_campaign_meeting_category_attended_target < 0}
									<span style="color: green">{$planning_data_total.standard_campaign_meeting_category_attended_target}</span>
								{/if}
							</td>
							<td>
								{* Meets in Diary for Current Month *}
								{$planning_data_total.meetings_in_diary_this_month}
							</td>
							<td>
								{* Meets Attended Target *}
								{$planning_data_total.meetings_attended_target}
							</td>
							<td>
								{* Meets Attended Actual *}
								{$planning_data_total.meeting_category_attended_count}
							</td>
							<td>
								{* Attended Deficit or Surplus *}
								{math assign=delivered equation="x - y" x=$planning_data_total.meeting_category_attended_count y=$planning_data_total.standard_campaign_meeting_category_attended_target}
								{$delivered}
							</td>
							<td class="border-left">
								{* Delivered *}
								{if $delivered > 0}
									<span style="color: green">Yes &amp; more</span>
								{elseif $delivered == 0}
									Yes
								{elseif $delivered < 0}
									<span style="color: red">No</span>
								{/if}
							</td>
							<td>
								{* Fee *}
								{$planning_data_total.campaign_monthly_fee|string_format:"&pound;%d"}
							</td>
							<td class="border-left" {popup text="(`$planning_data_total.meetings_set` / `$actual_effectives`) x 100"}>
								{* Conversion Rate *}
								{$planning_data_total.conversion_rate}%
							</td>
							<td {popup text="(`$actual_effectives` / `$planning_data_total.call_count`) x 100"}>
								{* Access Rate *}
								{$planning_data_total.access_rate}%
							</td>
							<td class="border-left">
								{* Revenue Per Day - Profitability *}
								&nbsp;
							</td>
							<td {popup text="`$planning_data_total.effectives` / `$total_call_days`"}>
								{* Average Effectives Per Day *}
								{$planning_data_total.average_effectives_per_day}
							</td>
							{if $media != 'print'}
								<td class="border-left">&nbsp;</td>
							{/if}
						</tr>
					<tbody>
				</table>
				</form>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td style="width: 30%; vertical-align: top">

				<div style="margin-bottom: 10px">
					<table class="adminlist" id="tbl_other_days" style="width: 100%">  {* Other days starts here *}
						<thead>
							<tr>
								<td colspan="2">Other days this month{* (<a href="#" onclick="javascript:showCalendarDate('{$year_month_day}', {$user_selected}); return false;">Go to calendar</a>)*}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="width: 100px">&nbsp;</td>
								<td style="width: 100px">Booked for month</td>
								<td style="width: 100px">Taken to date</td>
							</tr>
							{foreach name=days_booked_loop from=$days_booked item=item}
								<tr>
									<td style="width: 100px">{$item.name}</td>
									<td style="width: 100px">{$item.count_total}</td>
									<td style="width: 100px">{$item.count}</td>
								</tr>
							{foreachelse}
								<tr>
									<td class="no_results">None found</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>

				<div style="margin-bottom: 10px">
					<table class="adminlist" id="tbl_working_days" style="width: 100%">  {* Other days starts here *}
						<thead>
							<tr>
								<td colspan="2">Working days this month</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="width: 100px">Available for month</td>
								<td style="width: 100px">{$working_days_month_total}</td>
							</tr>
							<tr>
								<td style="width: 100px">Available for month to date</td>
								<td style="width: 100px">{$working_days_month_to_date}</td>
							</tr>
							<tr>
								<td style="width: 100px">Non-calling days to date</td>
								<td style="width: 100px">{$days_booked_to_date_total}</td>
							</tr>
							<tr>
								<td style="width: 100px">Actually worked to date</td>
								<td style="width: 100px">{$worked_days}</td>
							</tr>
							<tr>
								<td style="width: 100px">Available working days for rest of month</td>
								<td style="width: 100px">{$working_days_for_remainder_of_month}</td>
							</tr>

						</tbody>
					</table>
				</div>

				<div>
					<table class="adminlist" id="tbl_call summary" style="width:100%">  {* Call summary starts here *}
						<thead>
							<tr>
								<td>Call Summary for Month</td>
								<td style="text-align: center">Total in {$worked_days} Days</td>
								<td style="text-align: center">Average per Day</td>
								<td style="text-align: center">Required Daily KPI</td>
								<td style="text-align: center">Difference from KPI</td>
								<td style="text-align: center">Daily Activity Required To Meet Target</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Calls</td>
								<td style="text-align: center">{$monthly_call_data.call_count}</td>
								<td style="text-align: center">{$monthly_call_data.average_calls}</td>
								<td style="text-align: center">{$kpis.calls_per_call_day}</td>
								<td style="text-align: center; color: {if $monthly_call_data.average_calls_variance < 0}red{else}green{/if}">{$monthly_call_data.average_calls_variance}</td>
								<td style="text-align: center">{$targets.calls_per_call_day}</td>
							</tr>
							<tr>
								<td>Effectives</td>
								<td style="text-align: center">{$monthly_call_data.call_effective_count}</td>
								<td style="text-align: center">{$monthly_call_data.average_effectives}</td>
								<td style="text-align: center">{$kpis.effectives_per_call_day}</td>
								<td style="text-align: center; color: {if $monthly_call_data.average_effectives_variance < 0}red{else}green{/if}">{$monthly_call_data.average_effectives_variance}</td>
								<td style="text-align: center">{$targets.effectives_per_call_day}</td>
							</tr>
							<tr>
								<td>Meets Set</td>
								<td style="text-align: center">{$monthly_call_data.meeting_set_count}</td>
								<td style="text-align: center">{$monthly_call_data.average_meetings_set}</td>
								<td style="text-align: center">{$kpis.meets_set_per_call_day}</td>
								<td style="text-align: center; color: {if $monthly_call_data.average_meetings_set_variance < 0}red{else}green{/if}">{$monthly_call_data.average_meetings_set_variance}</td>
								<td style="text-align: center">{$targets.meets_set_per_call_day}</td>
							</tr>
							<tr>
								<td title="(Meets / Effectives) x 100">Conversion Rate</td>
								<td colspan="2" style="text-align: center">{$monthly_call_data.conversion}%</td>
								<td style="text-align: center">{$kpis.conversion}</td>
								<td style="text-align: center; color: {if $monthly_call_data.conversion_variance < 0}red{else}green{/if}">{$monthly_call_data.conversion_variance}%</td>
								<td style="text-align: center">&nbsp;</td>
							</tr>
							<tr>
								<td title="(Effectives / Calls) x 100">Access Rate</td>
								<td colspan="2" style="text-align: center">{$monthly_call_data.access}%</td>
								<td style="text-align: center">{$kpis.access}</td>
								<td style="text-align: center; color: {if $monthly_call_data.access_variance < 0}red{else}green{/if}">{$monthly_call_data.access_variance}%</td>
								<td style="text-align: center">&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</div>

			</td>
			<td style="vertical-align: top; width: 410px; min-width: 410px; overflow: hidden">
				<div>
					<img src="index.php?cmd=NbmMonthlyPlannerGraph4&amp;year_month={$year_month}&amp;nbm_id={$user_selected}" alt="Activity By Client" width="400" height="250" style="display: block" />
				</div>
			</td>
		</tr>
	</table>


{include file="footer.tpl"}
{/strip}