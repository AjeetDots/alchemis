{include file="header2.tpl" title="Company Delete"}

{if $success}

{*	<p>You are being redirected...</p>*}

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
	
	<form action="index.php?cmd=CompanyDelete" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" id="id" name="id" value="{$id}" />
		<input type="hidden" id="data_source" name="data_source" value="{$data_source}" />
		<input type="hidden" id="post_count" name="post_count" value="{$post_count}" />
	
		<fieldset class="adminform">
			<legend>Company Deletion</legend>
			<p>Please confirm you wish to delete the following company by checking the box below:</p>
			
			<strong>{$company->getName()}</strong>
			<br />
			<br />
			{$company->getSiteAddress("","paragraph")}
			
			<p>All associated information, including all posts will be deleted.</p>
			
			<p>Currently there are {$post_count} posts associated with this company.</p>
			
			<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Company_deleted" name="app_domain_Company_deleted" /></p>
		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}