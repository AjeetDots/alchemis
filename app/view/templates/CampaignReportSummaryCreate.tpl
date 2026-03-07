{include file="header2.tpl" title="Add Campaign Report Summary"}

{if $success}

	<p>Report Summaries are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
iframeLocation(		parent.ifr_summary_reports, 'index.php?cmd=CampaignReportSummaries&campaign_id={$campaign_id}');
	</script>

{else}

	<script type="text/javascript">
	{literal}
	
	function submitform(pressbutton)
	{
		document.adminForm.task.value = pressbutton;
		try
		{
			document.adminForm.onsubmit();
		}
		catch(e)
		{}
		document.adminForm.submit();
	}
	
	function submitbutton(pressbutton)
	{
		if (pressbutton == 'save')
		{
			submitform(pressbutton);
			return;
		}
	}
	
	{/literal}
	
	</script>
	
	<form action="index.php?cmd=CampaignReportSummaryCreate" method="post" name="adminForm" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="campaign_id" value="{$campaign_id}" />
		<fieldset class="adminform">
			<legend>Add Campaign Report Summary</legend>
			<table>
				<tr>
					<td style="width: 20%; vertical-align:top" class="key">
						<label>Subject *</label>
					</td>
					<td style="width: 80%">
						<input type="text" id="subject" name="subject" style="width: 100%" {if $error}value="{$subject}"{/if}/>
					</td>
				</tr>
				<tr>
					<td style="width: 20%; vertical-align:top" class="key">
						<label>Note *</label>
					</td>
					<td style="width: 80%">
						<textarea id="note" name="note" cols="40" rows="6">{if $error}{$note}{/if}</textarea>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="button" value="Clear" onclick="$('subject').value = '';$('note').value = '';" />
	
	</form>

{/if}
{include file="footer2.tpl"}