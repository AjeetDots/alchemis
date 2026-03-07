{include file="header2.tpl" title="Campaign Report Summaries"}

<script language="JavaScript" type="text/javascript">
{literal}
function openInfoPane(src)
{
	//alert('openInfoPane(' + src + ')');

	if (parent.ifr_info == undefined)
	{
		popupWindow(src);
	}
	else
	{
		iframeLocation(parent.ifr_info, src);
	}
}
	
function deleteReportSummary(id, subject)
{
	if (confirm("Confirm delete report summary ('" + subject + "') from this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignReportSummary", "", "delete_report_summary", ill_params, "Saving...")
	}
}


function AjaxCampaignReportSummary(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "delete_report_summary":
				deleteRow('tbl_report_sumary_list', 'tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function deleteRow(table_name, item_id)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

{/literal}
</script>


<table class="adminlist">
	<tr>
		<input type="button" id="add_new_summary" name="add_new_summary" value="Add New Summary" onclick="javascript: openInfoPane('index.php?cmd=CampaignReportSummaryCreate&amp;campaign_id={$campaign_id}');" />
	</tr>
	<tr>
		<td style="width: 50%">
			<table id="tbl_report_sumary_list" class="adminlist">
				<thead>
					<tr>
						<th style="width: 20%" >Summary</th>
						<th>Note</th>
						<th style="width: 10%">Updated By</th>
						<th style="width: 10%">Updated At</th>
						<th style="width: 10%; text-align: center">&nbsp;</th>
					</tr>
				</thead>
				{foreach name=summary_loop from=$report_summaries item=summary}
				<tr id="tr_{$summary->getId()}">
					<td>
						{$summary->getSubject()}
					</td>
					<td>
						{$summary->getNote()}
					</td>
					<td>
						{$summary->getUserName()}
					</td>
					<td>{$summary->getUpdatedAt()|date_format:"%d/%m/%Y"}</td>
					<td style="text-align: left; vertical-align: middle">
						<a href="javascript: openInfoPane('index.php?cmd=CampaignReportSummaryEdit&amp;id={$summary->getId()}');" title="Edit summary details"><img src="{$APP_URL}app/view/images/icons/group_edit.png" alt="Edit Report Summary" title="Edit Report Summary" /></a>
						<a id="deleteReportSummary_{$summary->getId()}" title="Remove Report Summary from campaign" href="#" onclick="javascript:deleteReportSummary({$summary->getId()}, '{$summary->getSubject()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove Report Summary from campaign" title="Remove Report Summary from campaign" /></a>&nbsp;
					</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
	
</table>


{include file="footer2.tpl"}