{include file="header2.tpl" title="Message Delete"}

{if $success}

{*	<p>You are being redirected...</p>*}

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=DashboardMessages';
	</script>

{else}

<script language="JavaScript" type="text/javascript">
{literal}
	
	function submitbutton(pressbutton)
	{
		if (pressbutton == 'delete')
		{
			submitform(pressbutton);
			return;
		}
	}

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

{/literal}
</script>
	
	<form action="index.php?cmd=MessageDelete" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" id="message_id" name="message_id" value="{$message_id}" />
	
		<fieldset class="adminform">
			<legend>Message Deletion</legend>
			<p>Please confirm you wish to delete the following message by checking the box below:</p>
			<p><strong>{$message->getMessage()}</strong></p>
			<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Message_deleted" name="app_domain_Message_deleted" /></p>
		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('delete')" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}