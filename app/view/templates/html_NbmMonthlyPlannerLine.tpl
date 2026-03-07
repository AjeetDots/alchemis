{foreach name=planning_data from=$planning_data item=data}
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
		{$data.campaign_meeting_attended_to_date_count}
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
	<td {popup text="`$data.effectives` / 10 = `$data.call_days_actual`"}>
		{* Call Days Actual *}
		{$data.call_days_actual}
	</td>
	<td>
		{* Project Management Days *}
		<input type="text" style="text-align: center; width: 30px" value="{$data.project_management_days}" id="{$data.campaign_id}-project_management_days" name="{$data.campaign_id}-project_management_days" disabled="disabled" />
	</td>
	{math assign=total_days equation="x + y" x=$data.call_days_actual y=$data.project_management_days}
	<td {popup text="`$data.call_days_actual` + `$data.project_management_days` = `$total_days`"}>
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
	<td {popup text="`$data.effectives` / `$data.call_days_actual`"}>
		{* Average Effectives Per Day *}
		{$data.average_effectives_per_day}
	</td>
	<td style="text-align: left" class="border-left">
		<input type="button" id="btn_edit_{$data.campaign_id}" value="Edit Line" onclick="javascript:editLine('{$data.campaign_id}');" />
	</td>
{/foreach}