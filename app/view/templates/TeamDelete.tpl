{include file="header2.tpl" title="Team Delete"}

{if $success}

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=DashboardTeams';
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
	
	<form action="index.php?cmd=TeamDelete" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" id="team_id" name="team_id" value="{$team_id}" />
	
		<fieldset class="adminform">
			<legend>Team Deletion</legend>
			<p>Please confirm you wish to delete the following team by checking the box below:</p>
			<p><strong>{$team->getName()}</strong></p>
			<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Team_deleted" name="app_domain_Team_deleted" /></p>
		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('delete')" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}