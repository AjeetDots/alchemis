{config_load file="example.conf"}

{include file="header2.tpl" title="Import Post 2"}

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
{if $action == "init"}
    <form action="index.php?cmd=ImportPost2" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
		
		<p>All data is now ready for importing to the database. Click the 'Submit' button below to start.</p>
		
		<p>Run with rollback:<input type="checkbox" id="with_rollback" name="with_rollback" /></p>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		      
	</form>
{elseif $action == "completed"}
    <h2>Import complete</h2p>
    <h3>Companies</h3>
    <p>Companies processed: {$company_processed_count}</p>
    <p>Companies added: {$company_added_count} (duplicates: {$company_duplicates_count} | pre-existing: {$company_existing_count})</p>
    <p>Companies failed to add: {$company_failure_count}</p>
    
    <p></p>
    <h3>Contacts</h3>
    <p>Contacts processed: {$contact_processed_count}</p>
    <p>Contacts added: {$contact_added_count} (duplicates: {$contact_duplicates_count} | pre-existing: {$contact_existing_count})</p>
    <p>Contacts failed to add: {$contact_failure_count}</p>
    
    <a href="{$APP_URL}{$log_file_path}" target="_new">View log file for this import</a>
    
{/if}
{include file="footer.tpl"}