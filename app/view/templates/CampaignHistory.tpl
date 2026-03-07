{include file="header.tpl" title="Campaign History"}

<fieldset class="adminform">

	<legend>Campaign History</legend>

	<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
		<thead>
			<tr>
				<th style="width: 3%">#</th>
				<th style="width: 10%; text-align: center">Timestamp</th
				<th style="width: 37%; text-align: center">Campaign</th>
				<th style="width: 10%; text-align: center">Revision</th>
				<th style="width: 10%; text-align: center">ID</th>
				<th style="width: 10%; text-align: center">Client</th>
				<th style="width: 10%; text-align: center">Created</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<del class="container">
						<div class="pagination">
							<div class="limit">Display #
								<select name="limit" id="limit" class="inputbox" size="1" onchange="document.adminForm.submit();">
									<option value="5" >5</option>
									<option value="10" >10</option>
									<option value="15" >15</option><option value="20"  selected="selected">20</option>
									<option value="25" >25</option><option value="30" >30</option>
									<option value="50" >50</option><option value="100" >100</option>
								</select>
							</div>
							<div class="button2-right off">
								<div class="start"><span>Start</span></div>
							</div>
							<div class="button2-right off">
								<div class="prev"><span>Prev</span></div>
							</div>
							<div class="button2-left">
								<div class="page"><a title="1" onclick="javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;">1</a><a title="2" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">2</a><a title="3" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">3</a></div>
							</div>
							<div class="button2-left">
								<div class="next"><a title="Next" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">Next</a></div>
							</div>
							<div class="button2-left">
								<div class="end"><a title="End" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">End</a></div>
							</div>
							<div class="limit">page 1 of 3</div>
							<input type="hidden" name="limitstart" value="0" />
						</div>
					</del>
				</td>
			</tr>
		</tfoot>
		<tbody>
			{foreach name=cam from=$campaigns item=campaign}
			<tr>
				<td>{$smarty.foreach.cam.iteration}</td>
				<td style="text-align: center">{$campaign.timestamp|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
				<td style="text-align: center">{$campaign.campaign_name}</td>
				<td style="text-align: center">{$campaign.revision}</td>
				<td style="text-align: center">{$campaign.id}</td>
				<td style="text-align: center">{*$client->getName()*}</td>
				<td style="text-align: center">{$campaign.created|date_format:"%A, %B %e, %Y"}</td>
			</tr>
			{/foreach}
		</tbody>
	
	</table>

</fieldset>

{include file="footer.tpl"}