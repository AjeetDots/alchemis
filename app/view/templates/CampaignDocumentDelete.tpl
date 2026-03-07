{include file="header2.tpl" title="Campaign Document Delete"}

{if $success}

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=CampaignDocuments&campaign_id={$campaign_id}';
	</script>

{else}

	<script language="JavaScript" type="text/javascript">
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
	
	<form action="index.php" method="post" name="adminForm" autocomplete="off">

		<input type="hidden" id="cmd"         name="cmd"         value="CampaignDocumentDelete" />
		<input type="hidden" id="document_id" name="document_id" value="{$document->getId()}" />
		<input type="hidden" id="task"        name="task"        value="" />
	
		<fieldset class="adminform">
			<legend>Campaign Doucment Deletion</legend>
			<p>Please confirm you wish to delete the following campaign document by checking the box below:</p>
			
			<p><strong>{$document->getFilename()}</strong></p>
			
			<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Document_deleted" name="app_domain_Document_deleted" /></p>

		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}