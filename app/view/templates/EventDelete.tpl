{include file="header2.tpl" title="Event Delete"}

{if $success}

	<p>The event has been deleted.</p>
{*	<p>Redirect to 'index.php?cmd={$referrer}&date={$referrer_date}'</p>*}
	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd={$referrer}&date={$referrer_date}';
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
	
	<form action="index.php?cmd=EventDelete" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="referrer" value="{$referrer}" />
		<input type="hidden" name="referrer_date" value="{$referrer_date}" />
		<input type="hidden" id="event_id" name="event_id" value="{$event->getId()}" />
	
		<fieldset class="adminform">
			<legend>Event Deletion</legend>
			<p>Please confirm you wish to delete the following event by checking the box below:</p>
			<p><strong>{$event->getSubject()}</strong><br />{$event->getDate()|date_format:$smarty.config.FORMAT_DATETIME_LONG}</p>
			<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Event_deleted" name="app_domain_Event_deleted" /></p>
		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}