{strip}
{include file="header.tpl" title="Dashboard"}

{if $media == 'print'}
<link rel="stylesheet" type="text/css" media="all" href="{$APP_URL}app/view/styles/print.css"/>
{/if}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
{literal}

	// Maintain global tab collection (tab_colln)
	// If this page has been loaded then we don't want to reload it when the tab is clicked
	if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(1))
	{
		top.parent.tab_colln.add(1);
	}
	
	function showPost(company_id, post_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
iframeLocation(		top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
		top.loadTab(5,"");
		colln.goToPostId(post_id);
		highlightSelectedRow(company_id, post_id);
	}

	// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
	function showCalendarDate(date, nbm_id)
	{
		page_isloaded = true;
iframeLocation(		top.frames["iframe_3"], "index.php?cmd=Calendar&date=" + date + "&nbm_id=" + nbm_id);
		top.loadTab(3,"");
	}

// onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"
 
	// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them
	// back to normal when a new company and/or post is selected
//	var last_company_class_change_id = "";
	var last_post_class_change_id = "";
		
	function highlightSelectedRow(company_id, post_id, post_initiative_id)
	{
		//set the background of the selected row
//		$("tr_" + company_id).className="current";
		
//		if (post_id != "")
//		{
			$("tr_post_" + post_id).className="current";
//		}
		
		// now set the previously selected items to a normal background
//		if (last_company_class_change_id != "" && last_company_class_change_id != company_id)
//		{
//			$("tr_" + last_company_class_change_id).className="";
//		}
//		last_company_class_change_id = company_id;
		
		if (last_post_class_change_id != "" && last_post_class_change_id != post_id)
		{
			$("tr_post_" + last_post_class_change_id).className="";
		}
		last_post_class_change_id = post_id;
	}

	function goToHash(hash_location)
	{
		var mypos = findPos($(hash_location));
		$("div_results").scrollTop = mypos[1]-200;
	}
	
	function findPos(obj) 
	{
		//alert ("in pos");
        var curleft = curtop = 0;
        if (obj.offsetParent) 
        {
                curleft = obj.offsetLeft;
                curtop = obj.offsetTop;
                //alert (curtop);
                while (obj = obj.offsetParent) 
                {
                        curleft += obj.offsetLeft;
                        //alert (curtop);
                        curtop += obj.offsetTop;
                }
        }
        return [curleft,curtop];

	}	

	colln = new ill_Data_Collection(); 
	
	// set page_isloaded to false so we can check in header_js.loadTab whether we need to highlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
	page_isloaded = false;


	// Make Ajax call to load table of meetings with the selected status
	function loadMeetingStatus()
	{
		var ill_params = new Object;
		ill_params.item_id = $F('meeting_status_id');
		$('meeting_status_id').disabled = true;
		getAjaxData('AjaxDashboard', '', 'load_meeting_status', ill_params, 'Adding...')
	}

	// Ajax return data handlers
	function AjaxDashboard(data)
	{
		for (i = 1; i < data.length + 1; i++) 
		{
			t = data[i-1];
			switch (t.cmd_action)
			{
				case 'load_meeting_status':
					$('meeting_status').innerHTML = t.line_html;
					$('meeting_status_id').disabled = false;
					break;
				
				default:
					alert('No cmd_action specified');
					break;
			}
		}
	}

	function reload(client_id)
	{
		self.location = 'index.php?cmd=Dashboard&client_id=' + client_id;
	}

{/literal}
</script>


{*if $messages}
	{assign var=ticker value=$messages->next()}
	<marquee scrollamount="1" scrolldelay="10" width="100" height="20" style="font-family: Verdana; font-size: 8pt">
		{$ticker->getMessage()}
	</marquee>
{/if*}



<table class="dashboard" border="0" cellpadding="0" cellspacing="20" style="border: 1px solid #ccc; width: 100%{*; border-collapse: collapse*}" bgcolor="#F9F9F9">
	<tr>
		<td style="width: 40%">

			<table class="adminlist">
				<thead>
					<tr>
						<th style="width: 33%">Time &amp; Date</th>
						<th style="width: 34%">Scheduled Client &amp; Targets</th>
						<th style="width: 33%">Today's Meetings</th>
					</tr>
				</thead>
				<tr>
					<td>{$smarty.now|date_format:"%H:%M %p"}<br />{$smarty.now|date_format:"%A %e %B %Y"}</td>
					<td>Scheduled Client &amp; Targets</td>
					<td {if $todays_meetings}style="padding: 0"{else}style="text-align: center"{/if}>
						{if $todays_meetings}
							<div style="padding: 4px; width: 200px; height : 100px; overflow: auto;">
								<ul id="meetings">
									{foreach name="meeting_loop" from=$todays_meetings item=result}
										<li><a id="detailsBtn2_{$meeting.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;">{$result.client} / {$result.company_name}</a></li>
									{/foreach}
								</ul>
							</div>
						{else}
						<em>No meetings</em>
						{/if}
					</td>
				</tr>
			</table>

			<br />

			<!-- Client Area & Calendar -->
			<table border="0" style="border: 0px solid purple; width: 100%">
				<tr>
					<th style="width: 20%">Client Area</th>
					<th style="width: 80%; padding-left: 20px">Calendar</th>
				</tr>
				<tr>
					<td>
						{if $clients}
							<table class="adminlist">
								{foreach name=client_loop_2 from=$clients item=client}
								<tr{if $client_selected == $client.id} class="current"{/if}>
									<td><a href="#" onclick="javascript:reload({$client.id});">{$client.client_name}</a></td>
								</tr>
								{foreachelse}
								<tr>
									<td class="no_results">No results</td>
								</tr>
								{/foreach}
							</table>
						{/if}
					</td>
					<td style="padding-left: 20px">
						{calendar_month data=$month_data
						                year=$year
						                month=$month
						                day_format=short
						                day_link="index.php?cmd=Calendar&client_id=$client_id"
						                group=true
						                legend=true
						                hide_completed_items=true
						                onclick="showCalendarDate"
						                print_month_name=true
						                print_year=true
						                url="home.php"
						                width="100%"
						                nbm_id=$session_user_id
						                client_id=$client_id}
					</td>
				</tr>
			</table>
			<!-- /Client Area & Calendar -->

			<br />

			<h3>Summary of progress this month to date</h3>
			
			<div style="{*border: 1px solid red; *}text-align: center">
				<img src="index.php?cmd=DashboardGraph1" />
			</div>

		</td>
		<!-- /Column 1 -->
		
		<!-- Column 2 -->
		<td style="width: 35%">

			<!-- Top Line Summary -->
			<h3>Top Line Summary for 
				<select id="client_id" name="client_id" onchange="javascript:reload($F('client_id'));">{html_options options=$clients_dropdown selected=$client_selected}</select>
			</h3>
			
			<table class="adminlist"{*border="1" style="border: 1px solid yellow; width: 100%"*}>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th style="text-align: center">Set</th>
						<th style="text-align: center">Attended</th>
						<th style="text-align: center">Calls</th>
						<th style="text-align: center">Effectives</th>
						<th style="text-align: center">Conversion</th>
					</tr>
				</thead>
				<tr>
					<td>Monthly Target</td>
					<td style="text-align: center">{$client_targets.meeting_set_target}</td>
					<td style="text-align: center">{$client_targets.meeting_attended_target}</td>
					<td style="text-align: center">{$client_targets.call_target}</td>
					<td style="text-align: center">{$client_targets.call_effective_target}</td>
					<td style="text-align: center">{$client_targets.conversion|string_format:"%.0f"}%</td>
				</tr>
				<tr>
					<td>Monthly Actual</td>
					<td style="text-align: center">{$client_actuals.meeting_set_count}</td>
					<td style="text-align: center">{$client_actuals.meeting_attended_count}</td>
					<td style="text-align: center">{$client_actuals.call_count}</td>
					<td style="text-align: center">{$client_actuals.call_effective_count}</td>
					<td style="text-align: center">{$client_actuals.conversion|string_format:"%.0f"}%</td>
				</tr>
			</table>
			<!-- /Top Line Summary -->

			<br />

			<!-- Actions -->
			<h3>Actions</h3>
			<table class="adminlist">
				<thead>
					<tr>
						<th>Action</th>
						<th>Set By</th>
						<th>Deadline</th>
					</tr>
				</thead>
				{foreach name=action_loop from=$actions item=action}
					{assign var=action_user value=$action->getUser()}
					<tr{if $action->isOverdue()} class="highlight_negative"{/if}>
						<td>{$action->getSubject()}</td>
						<td>{$action_user->getHandle()}</td>
						<td>{$action->getDueDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="3" class="no_results">No results</td>
					</tr>
				{/foreach}
			</table>
			{if $more_actions || true}
				<p style="padding-left: 10px"><a href="#" onclick="javascript:parent.doMenuItem('DashboardActions')" style="font-style: italic">Other Actions</a></p>
			{/if}
			<!-- /Actions -->

			<br />

			<!-- Campaign Progress -->
			<h3>Campaign Progress as at end of {$yesterday|date_format:$smarty.config.FORMAT_DATE_LONG}</h3>
			<table class="adminlist"{*border="1" style="border: 1px solid yellow; width: 100%"*}>
				<thead>
					<tr>
						<th>Campaign</th>
						<th>Set</th>
						<th>Atd</th>
						<th>Diary</th>
						<th>Need</th>
						<th>Issue</th>
						<th>Month</th>
					</tr>
				</thead>
				{foreach name=campaign_loop from=$campaigns item=campaign}
				<tr{if $client_selected == $campaign.client_id} class="current"{/if}>
					<td>{$campaign.campaign_name}</td>
					<td style="text-align: center; white-space: nowrap">{$campaign.campaign_meeting_set_count_to_date} v {$campaign.campaign_meeting_set_target_to_date}</td>
					<td style="text-align: center; white-space: nowrap">{$campaign.campaign_meeting_attended_count_to_date} v {$campaign.campaign_meeting_category_attended_target_to_date}</td>
					<td style="text-align: center; white-space: nowrap">{$campaign.meeting_in_diary_this_month_count}</td>
					<td style="text-align: center; white-space: nowrap">
						{$campaign.campaign_meeting_set_target_to_date-$campaign.campaign_meeting_set_count_to_date} &amp;
						{$campaign.campaign_meeting_category_attended_target_to_date-$campaign.campaign_meeting_category_attended_count_to_date}
					</td>
					<td style="text-align: center; white-space: nowrap"></td>
					<td style="text-align: center; white-space: nowrap">{$campaign.campaign_current_month}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="7" class="no_results">No results</td>
				</tr>
				{/foreach}
			</table>
			<!-- /Campaign Progress -->

			<br />

			<!-- Recommended Actions -->
			<h3>Recommended actions to recover progress in next {$weekdays_remaining} day{if $weekdays_remaining != 1}s{/if}</h3>
			<table class="adminlist"{*border="1" style="border: 1px solid yellow; width: 100%"*}>
				<tr>
					<th style="width: 20%">Calls</th>
					<td style="width: 30%">{$recommended_calls}</td>
					<th style="width: 20%">Effectives</th>
					<td style="width: 30%">{$recommended_effectives}</td>
				</tr>
				<tr>
					<th style="width: 20%">Set</th>
					<td style="width: 30%">{$recommended_meets_set}</td>
					<th style="width: 20%">Attended</th>
					<td style="width: 30%">{$recommended_meets_attended}</td>
				</tr>
			</table>
			<p class="highlight_negative" style="padding-left: 10px">Lapsed rate is {$lapsed_rate}% across client base.</p>
			<!-- /Recommended Actions -->
			
			<br />
			
			<!-- Other Performance Summary Reports -->
			<h3>Other Performance Summary Reports in Detail</h3>
			<ul>
				<li><a href="#" onclick="return false;">Month Plan &amp; Full Personal Summary of Performance for Month</a></li>
				<li><a href="#" onclick="return false;">Full Sales Team Summary of Performance for Month</a></li>
				<li><a href="#" onclick="return false;">Your Team Summary of Performance for Month</a></li>
			</ul>
			<!-- /Other Performance Summary Reports -->

		</td>
		<!-- /Column 2 -->
		
		<!-- Column 3 -->
		<td style="width: 25%">

			{if $media != 'print'}
			<p style="text-align: right"><a href="index.php?cmd=Dashboard&amp;media=print" target="_blank"><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" title="Print" /> Print</a></p>
			{/if}

			{if $media != 'print'}
			<table class="adminlist">
				<tr>
					<td style="width: 50%">
						<!-- Knowledge Zone -->
						<h3>Knowledge Zone</h3>
						<ul>
							<li><a href="/wiki/Disciplines" onclick="return false;">Disciplines</a></li>
							<li><a href="/wiki/Sectors" onclick="return false;">Sectors</a></li>
							<li><a href="/wiki/Internal Process" onclick="return false;">Internal Process</a></li>
							<li><a href="/wiki/Client Management" onclick="return false;">Client Management</a></li>
							<li><a href="/wiki/Top Filters" onclick="return false;">Top Filters</a></li>
							<li><a href="/wiki/Other" onclick="return false;">Other</a></li>
						</ul>
						<!-- /Knowledge Zone -->
					</td>
					<td style="width: 50%">
						<!-- Dave's Zone -->
						<h3>Dave's Zone</h3>
						<ul>
							<li><a href="#" onclick="return false;">Bonus Earned</a></li>
							<li><a href="#" onclick="return false;">Client Retention</a></li>
							<li><a href="#" onclick="return false;">Holiday Countdown</a></li>
							<li><a href="#" onclick="return false;">Sickness So Far</a></li>
							<li><a href="#" onclick="return false;">Citizenship</a></li>
							<li><a href="#" onclick="return false;">Other</a></li>
						</ul>
						<!-- /Dave's Zone -->
					</td>
				</tr>
			</table>
			
			<br />
			<h3>Message Board</h3>
			
			{if $messages}
			<ul>
				{foreach name=message_loop from=$messages item=message}
					<li>{$message->getMessage()}</li>
				{/foreach}
			</ul>
			{/if}
			
			<br />
			{/if}

			<!-- Team Zone -->
			<h3>Team Zone</h3>
			<table class="adminlist"{*border="1" style="border: 1px solid yellow; width: 100%"*}>
				<thead>
					<tr>
						<th style="width: 20%">&nbsp;</th>
						<th style="width: 20%">Calls</th>
						<th style="width: 20%">Effectives</th>
						<th style="width: 20%">Meets</th>
						<th style="width: 20%" title="Calls + (OTEs x 10) + (Meetings x 100)">KPIs</th>
					</tr>
				</thead>
				{foreach name=team_loop from=$team_stats item=team}
					<tr{if $user_team == $team.team_id} class="current"{/if}>
						<td>{$team.team}</td>
						<td>{$team.call_count}</td>
						<td>{$team.call_effective_count}</td>
						<td>{$team.meeting_set_count}</td>
						<td>{$team.kpi}</td>
					</tr>
				{/foreach}
			</table>
			<!-- /Team Zone -->
			
			<div style="{*border: 1px solid red; *}text-align: center">
				<img src="index.php?cmd=DashboardGraph2" />
			</div>

		</td>
		<!-- /Column 3 -->

	</tr>
</table>

{include file="footer.tpl"}
{/strip}