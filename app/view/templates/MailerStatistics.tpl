{config_load file="example.conf"}

{include file="header2.tpl" title="Mailer Item List"}

<script language="JavaScript" type="text/javascript">
{literal}
function showPost(company_id, post_id)
{
iframeLocation(	top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
	top.loadTab(5,"");
}

function showCompany(company_id, mailer_item_id)
{
iframeLocation(	top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=&initiative_id=" + top.$F("initiative_list"));
	top.loadTab(5,"");
}
{/literal}

</script>

<div id="div_mailer_statistics" style="border: solid 1px #ccc; padding: 2px; width: 100%; height:690px; overflow-x: hidden; overflow-y: y:auto">
	<h3>Statistics for Marketing Item: {$mailer->getName()}</h1>
	
	<input type="button" value="print" onClick="javascript: window.self.print();" />
	
	<br />
	
	<table width="700" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
		<tr style="background-color: {#readColor2#}">
			<td>
				<img src="index.php?cmd=MailerStatisticsGraph1&id={$mailer->getId()}" width="700" height="180" />
				<br />
				<table width="100%" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
					<tr>
						<td style="width: 100px; background-color: {#readColor1#}">Total Items</td>
						<td style="background-color: {#readColor2#}">{$graph1_item_count}</td>
					</tr>
					<tr>
						<td style="background-color: {#readColor1#}">Despatched</td>
						<td style="background-color: {#readColor2#}">{$graph1_despatched_count}&nbsp;({$graph1_despatched_count_perc}%)</td>
					</tr>
					<tr>
						<td style="background-color: {#readColor1#}">Not Despatched</td>
						<td style="background-color: {#readColor2#}">{$graph1_not_despatched_count}&nbsp;({$graph1_not_despatched_count_perc}%)</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<br />
	
	<table width="700" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
		<tr style="background-color: {#readColor2#}">
			<td>
				<img src="index.php?cmd=MailerStatisticsGraph2&id={$mailer->getId()}" width="700" height="180" />
				<br />
				<table width="100%" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
					<tr>
						<td style="width: 100px; background-color: {#readColor1#}">Total Dispatched</td>
						<td style="background-color: {#readColor2#}">{$graph2_despatched_count}</td>
					</tr>
					<tr>
						<td style="background-color: {#readColor1#}">Responses</td>
						<td style="background-color: {#readColor2#}">{$graph2_response_count}&nbsp;({$graph2_response_count_perc}%)</td>
					</tr>
					<tr>
						<td style="background-color: {#readColor1#}">No Responses</td>
						<td style="background-color: {#readColor2#}">{$graph2_no_response_count}&nbsp;({$graph2_no_response_count_perc}%)</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<br />
	
	<table width="700" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
		<tr style="background-color: {#readColor2#}">
			<td>
				<img src="index.php?cmd=MailerStatisticsGraph3&id={$mailer->getId()}" width="700" height="180" />
				<br />
				<table width="100%" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
					<tr>
						<td style="background-color: {#readColor1#}">Total Responses</td>
						<td style="background-color: {#readColor2#}">{$graph3_total_count}</td>
					</tr>
					{section name=sec1 loop=$graph3_data}
					<tr>
						<td style="width: 300px; background-color: {#readColor1#}">{$graph3_data[sec1].response}</td>
						<td style="background-color: {#readColor2#}">{$graph3_data[sec1].count}&nbsp;({$graph3_data[sec1].count_perc}%)</td>
					</tr>
					{/section}
				</table>
			</td>
		</tr>
	</table>
	
	<br />
	
	<table width="700" border="0" cellspacing="1" cellpadding="3" class="adminlist" style="">
		<tr>
			<td>
				
				<table width="100%" border="0" cellspacing="1" cellpadding="3" style="background-color: white">
					<tr>
						<th>Response</th>
						<th>Company</th>
						<th>Post</th>
						<th>Contact</th>
						<th>Response Date</th>
						<th>Note</th>
					</tr>
					{section name=sec1 loop=$responses}
					<tr>
						<td style="width: 300px">{$responses[sec1].response}</td>
						<td style="width: 300px">
							<a href="#" onclick="javascript:showCompany({$responses[sec1].company_id});return false;" title="Go to {$responses[sec1].company_name}">
							{$responses[sec1].company_name}</a>
						</a>
						</td>
						<td style="width: 300px">
							<a href="#" onclick="javascript:showPost({$responses[sec1].company_id}, {$responses[sec1].post_id});return false;" title="Go to {$responses[sec1].company_name}">
							{$responses[sec1].post}
							</a>
						</td>
						<td style="width: 300px">{$responses[sec1].contact}</td>
						<td style="width: 300px">{$responses[sec1].response_date|date_format:"%A %d %B, %Y"}</td>
						<td style="width: 300px">{$responses[sec1].response_note}</td>
					</tr>
					{/section}
				</table>
			</td>
		</tr>
	</table>

</div>

{include file="footer.tpl"}