<table id="table2" class="adminlist sortable" id="sortable2_{$result.id}" cellspacing="1">
	{if $meetings}
		<thead>
			<tr>
				<th style="width: 1%; text-align: center">#</th>
				<th style="width: 1%; text-align: center">ID</th>
				<th style="width: 18%; text-align: left">Company</th>
				<th style="width: 18%; text-align: left">Job Title</th>
				<th style="width: 18%; text-align: left">Post Holder</th>
				<th style="width: 10% ;text-align: left">Date &amp; Time</th>
				<th style="width: 8%">Propensity</th>
				<th style="width: 21%; text-align: left">Notes</th>
				<th style="width: 5%"></th>
			</tr>
		</thead>
		<tbody>
			{foreach name="meeting_loop" from=$meetings item=result}
				<tr style="vertical-align: top">
					<td>{$smarty.foreach.meeting_loop.iteration}</td>
					<td>{$result.id}</td>
					<td>
						<span id="client_{$result.id}">{$result.company_name}</span>
						<br />
						{assign var="website" value=$result.website}
						{if $website != ""}
							<a href="{$website}" target="_new">{$website}</a>
						{/if}
					</td>
					<td>{$result.job_title}</td>
					<td>{$result.full_name}</td>
					<td>{$result.date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
		 			<td style="text-align: center">
		 				<span style="display: none">{$result.propensity}</span>
		 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
		 			</td>
					<td>{$result.notes}</td>
					<td style="text-align: center; background-color: #F3F3F3">
						<a id="detailsBtn2_{$meeting.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>&nbsp;
						<a href="index.php?cmd=MeetingPrint&amp;id={$result.id}" target="_blank" title="Print meeting" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	{else}
		<tr>
			<td style="text-align: center">
				<em>&lt;&mdash; No meetings found &mdash;&gt;</em>
			</td>
		</tr>
	{/if}
</table>