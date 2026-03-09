{include file="header.tpl" title="Dashboard"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<table class="dashboard" border="0" cellpadding="0" cellspacing="20" style="border: 1px solid #ccc; width: 100%{*; border-collapse: collapse*}" bgcolor="#ffffff">
	<tr>
		<td style="width: 40%">

			<table class="adminlist">
				<thead>
					<tr>
						<th style="width: 34%">Scheduled Client &amp; Targets</th>
						<th style="width: 33%">Today's Meetings</th>
					</tr>
				</thead>
				<tr>
					<td>Scheduled Client &amp; Targets</td>
					<td>
						{if $meetings}
							<ol id="meetings">
								{foreach name="meeting_loop" from=$meetings item=result}
									{if $smarty.foreach.meeting_loop.iteration <= 10}<li><a href="#" onclick="return false;">{$result.client} / {$result.company_name}</a></li>{/if}
								{/foreach}
							</ol>
							{if $meetings|@count > 10}
								{assign var=meeting_count value=$meetings|@count}
								<p><em>{$meeting_count-10} more...</em></p>
							{/if}
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
						{calendar_month data             = $month_data
						                year             = $year
						                month            = $month
						                day_format       = short
						                day_link         = "index.php?cmd=Calendar&client_id=$client_id"
						                group            = true
						                legend           = true
						                onclick          = "showCalendarDate"
						                print_month_name = true
						                print_year       = true
						                url              = "home.php"
						                width            = "100%"}
					</td>
				</tr>
			</table>
			<!-- /Client Area & Calendar -->

			<br />

			<h3>Summary of progress this month to date</h3>
			
			<div style="{*border: 1px solid red; *}text-align: center">
				<img src="{$APP_URL}index.php?cmd=DashboardGraph1&amp;client_id={$client_selected|default:0}&amp;v={$APP_VERSION|default:'1'}" alt="Set and Attended chart" />
			</div>

		</td>
		<!-- /Column 1 -->
		
		<!-- Column 2 -->
		<td style="width: 35%">

			<!-- Top Line Summary -->
			<h3>Top Line Summary for 
				<select id="client_id" name="client_id" onchange="javascript:reload($F('client_id'));" disabled="disabled">{html_options options=$clients_dropdown selected=$client_selected}</select>
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
			<h3>Campaign Progress as at end of {$campaign_progress_date_label}</h3>
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
					<td style="text-align: center; white-space: nowrap">{$campaign.campaign_current_month}--{$campaign.id}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="7" class="no_results">No results</td>
				</tr>
				{/foreach}
			</table>

			{if $campaigns && $campaigns|@count > 10}
				{assign var=campaign_count value=$campaigns|@count}
				<p><em>{$campaign_count-10} more...</em></p>
			{/if}
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

		</td>
		<!-- /Column 2 -->
		
		<!-- Column 3 -->
		<td style="width: 25%">

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
				<img src="{$APP_URL}index.php?cmd=DashboardGraph2&amp;media=print&amp;v={$APP_VERSION|default:'1'}" alt="Team Zone KPI chart" />
			</div>

		</td>
		<!-- /Column 3 -->

	</tr>
</table>

{*
<div id="appendedlinks">test 1</div>
<div id="appendedlinks" class="page">test 4</div> 
*}
{*<script type="text/javascript">init_moofx();</script>*}

{*if $media == 'print'}
<script language="JavaScript" type="text/javascript">
	window.print();
</script>
{/if*}

{include file="footer.tpl"}