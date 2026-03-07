{include file="header.tpl" title="Meeting History"}

{assign var=count value=$history|@count}

{foreach name=history_loop from=$history item=history}
<div style="border: 1px solid black; margin-bottom: 10px">
	<table class="ianlist">
		<tr>
			<td colspan="2" style="background: lightgray">
				[{$count+1-$smarty.foreach.history_loop.iteration}]
				Meeting <strong>{if $history.shadow_type == 'i'}inserted{elseif $history.shadow_type == 'u'}updated{elseif $history.shadow_type == 'd'}deleted{/if}</strong>
				on <strong>{$history.shadow_timestamp|date_format:$smarty.config.FORMAT_DATETIME_LONG}</strong> by <strong>{$history.updated_by_handle}</strong>
			</td>
		</tr>
{*		<tr>
			<th style="width: 10%">ID</th>
			<td>{$history.id}</td>
		</tr>
*}		<tr>
			<th style="width: 10%">Initiative</th>
			<td>{$history.initiative}{* [{$history.post_initiative_id}]*}</td>
		</tr>
		<tr>
			<th>Post</th>
			<td>{$history.post_job_title}</td>
		</tr>
{*		<tr>
			<th>Communication ID</th>
			<td>{$history.communication_id}</td>
		</tr>
*}		<tr>
			<th>Status</th>
			<td>{$history.status}</td>
		</tr>
		<tr>
			<th>Type</th>
			<td>{$history.type}</td>
		</tr>
		<tr>
			<th>Date</th>
			<td>{$history.date|date_format:$smarty.config.FORMAT_DATETIME_LONG}</td>
		</tr>
		<tr>
			<th>Reminder</th>
			<td>{$history.reminder_date}</td>
		</tr>
		<tr>
			<th>Notes</th>
			<td>{$history.notes}</td>
		</tr>
{*		<tr>
			<th>Created At</th>
			<td>{$history.created_at|date_format:$smarty.config.FORMAT_DATETIME_LONG}</td>
		</tr>
		<tr>
			<th>Created By</th>
			<td>{$history.created_by_handle} [{$history.created_by}]</td>
		</tr>
*}		<tr>
			<th>Creation</th>
			<td>{$history.created_at|date_format:$smarty.config.FORMAT_DATETIME_LONG} by {$history.created_by_handle}</td>
		</tr>
	</table>
</div>
{/foreach}

{include file="footer.tpl"}