{include file="header.tpl" title="Client List"}

<fieldset class="adminform">

	<legend>Clients</legend>

{*
	<table class="paginate">
		<tr>
			<td style="text-align: left; width: 33%">
				Items {$paginate.first}-{$paginate.last} of {$paginate.total} total
			</td>
			<td style="text-align: center; width: 34%">
				{paginate_first} {paginate_prev} {paginate_middle} {paginate_next} {paginate_last}
			</td>
			<td style="text-align: right; width: 33%">
				Items per page:
				{if $paginate.limit != 10}<a href="index.php?cmd=ClientList&amp;limit=10&next={$paginate.current_item}">{/if}10{if $paginate.limit != 10}</a>{/if}
				{if $paginate.limit != 25}<a href="index.php?cmd=ClientList&amp;limit=25&next={$paginate.current_item}">{/if}25{if $paginate.limit != 25}</a>{/if}
				{if $paginate.limit != 50}<a href="index.php?cmd=ClientList&amp;limit=50&next={$paginate.current_item}">{/if}50{if $paginate.limit != 50}</a>{/if}
				{if $paginate.limit != 100}<a href="index.php?cmd=ClientList&amp;limit=100&next={$paginate.current_item}">{/if}100{if $paginate.limit != 100}</a>{/if}
				{if $paginate.limit != $paginate.total}<a href="index.php?cmd=ClientList&amp;limit={$paginate.total}&next={$paginate.current_item}">{/if}All{if $paginate.limit != $paginate.total}</a>{/if}
			</td>
		</tr>
	</table>
*}
	{*{include file="paginate.tpl" url="index.php?cmd=ClientList"}*}
	<table id="table1" class="adminlist"{*class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
		<thead>
			<tr>
{*				<th style="width: 1%; text-align: center">#</th>*}
				<th style="width: 10%; text-align: center">ID</th>
				<th style="width: 49%; text-align: center">Client</th>
{*				<th style="width: 39%; text-align: center">Campaigns</th>*}
{*				<th style="width: 1%; text-align: center"></th>*}
			</tr>
		<tfoot>
			<tr>
				<th colspan="5">&nbsp;</th>
			</tr>
		</tfoot>
		<tbody>
			{foreach name=client_loop from=$clients item=client}
			<tr>
{*				<td>{$smarty.foreach.client_loop.iteration+$paginate.first-1}</td>*}
				<td style="text-align: right">{$client->getId()}</td>
				<td><span id="client_{$client->getId()}">{$client->getName()}</span></td>
{*				<td>
					{foreach from=$client->getCampaigns() item=campaign}
						{$campaign->getName()}<br />
					{/foreach}
				</td>*}
{*				<td style="text-align: center; background-color: #F3F3F3">
					<div class="button2-left">
						<div class="page"><a id="detailsBtn_{$client->getId()}" title="Edit" href="index.php?cmd=ClientView&amp;client_id={$client->getId()}">Details</a></div>
					</div>
				</td>*}
			</tr>
			{/foreach}
		</tbody>
	</table>
	{*{include file="paginate.tpl" url="index.php?cmd=ClientList"}*}

</fieldset>

{include file="footer.tpl"}