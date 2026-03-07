{include file="header2.tpl" title="Communication Attachments"}

<fieldset class="adminform">

	<legend>Communication Attachments</legend>

	<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
		<thead>
			<tr>
				<th style="width: 3%">#</th>
				<th style="width: 57%; text-align: center">Description</th>
				<th style="width: 40%; text-align: center">Filename</th>
			</tr>
		</thead>
		<tbody>
			{foreach name=attachments from=$attachments item=attachment}
			<tr>
				<td>{$smarty.foreach.attachments.iteration}</td>
				{assign var=document value=$attachment->getDocument()}
				<td style="text-align: center">{$document->getDescription()}</td>
				<td style="text-align: center">{$document->getFilename()}</td>
				{*<td>
					<div class="button2-left">
						<div class="page"><a id="editBtn_{$campaign->getId()}" title="Edit" href="index.php?cmd=CampaignHistory&amp;campaign_id={$campaign->getId()}">History</a></div>
					</div>
				</td>*}
			</tr>
			{/foreach}
		</tbody>
	
	</table>

</fieldset>

{include file="footer2.tpl"}