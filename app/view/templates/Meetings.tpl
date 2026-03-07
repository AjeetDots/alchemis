{include file="header2.tpl" title="Meetings"}

<fieldset class="adminform">

	<legend>Meetings with {$post->getContactName()}</legend>
	(for {$initiative_name})
	<br />
	<input type="hidden" id="post_initiative_id" name="post_initiative_id" value={$post_initiative_id}" />
	<br />

	{if $meetings}

		<table id="table1" class="adminlist sortable" border="0" cellpadding="0" cellspacing="1" width="100%">
			<thead>
				<tr>
					<th>Date</th>
					<th>Status</th>
				</tr>
			<tfoot>
				<tr>
					<th colspan="4">&nbsp;</th>
				</tr>
			</tfoot>
			<tbody>
				
				{foreach name="meeting_loop" from=$meetings item=meeting}
				<tr>
					<td>{$meeting->getDate()|date_format:"%d %b %y at %H:%M"}</td>
					<td>{$meeting->getStatus()}</td>
					<td>
						<a href="#" onclick="javascript:document.location.href='index.php?cmd=MeetingEdit&id={$meeting->getId()}&company_id={$company_id}&referrer_type={$referrer_type}';return false;" title="Displays meeting detail">Detail/Edit</a>
						<br />
						<a href="#" onclick="javascript:document.location.href='index.php?cmd=MeetingHistory&meeting_id={$meeting->getId()}&company_id={$company_id}';return false;" title="Displays meeting history">History</a>
						<br />
						<a href="#" onclick="javascript:document.location.href='index.php?cmd=PostInitiativeActions&post_initiative_id={$post_initiative_id}&referrer_type={$referrer_type}&type_id=1_3_4';return false;" title="Displays meeting actions">Actions</a>
					</td>
					<td>
						{if $referrer_type == 'workspace'}
							<a href="index.php?cmd=MeetingPrint&amp;id={$meeting->getId()}" target="_blank" title="Print meeting" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a>
							<a href="index.php?cmd=MeetingEmail&amp;id={$meeting->getId()}" target="_blank" title="Email meeting" ><img src="{$APP_URL}app/view/images/icons/email.png" alt="Email" /></a>
						{/if}
					</td>
				</tr>
				{/foreach}

			</tbody>
		</table>
	{else}

		<p><em>&lt;-- No meetings found --&gt;</em></p>

	{/if}

</fieldset>

{include file="footer2.tpl"}