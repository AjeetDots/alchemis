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
<td {popup text="`$planning_data_total.effectives` / 10 = `$planning_data_total.call_days_actual`"}>
	{* Call Days Actual *}
	{$planning_data_total.call_days_actual}
</td>
<td>
	{* Project Managment Days *}
	{$planning_data_total.project_management_days}
</td>
{math assign=total_days equation="x + y" x=$planning_data_total.project_management_days y=$planning_data_total.call_days_actual}
<td {popup text="`$planning_data_total.call_days_actual` + `$planning_data_total.project_management_days` = `$total_days`"}>
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
<td {popup text="`$planning_data_total.meetings_set` - `$planning_data_total.meetings_set_target`"}>
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
	{if $planning_data_total.standard_campaign_meeting_category_attended_target	 > 0}
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
<td {popup text="`$planning_data_total.effectives` / `$planning_data_total.call_days_actual`"}>
	{* Average Effectives Per Day *}
	{$planning_data_total.average_effectives_per_day}
</td>
<td class="border-left">&nbsp;</td>