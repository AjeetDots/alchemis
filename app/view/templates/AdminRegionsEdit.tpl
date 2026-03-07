{include file="header2.tpl" title="Edit Region"}

{if $success}

	<script language="JavaScript" type="text/javascript">
		parent.$('span_region_name_{$region->getId()}').innerHTML = "{$region->getName()}";
	</script>

{/if}

<script type="text/javascript">
{literal}


function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.adminForm.task.value=pressbutton;
	
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
//	alert('submitbutton(' + pressbutton + ')');
//	var form = document.adminForm;
//	var type = form.type.value;
	
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}

{/literal}
</script>
<form action="index.php?cmd=AdminRegionsEdit" method="post" name="adminForm" autocomplete="off">
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="{$region->getId()}" />

	<fieldset class="adminform">
		<legend>Edit Region</legend>
		<table>
			<tr>
				<td>Name</td>
				<td><input type="text" name="app_domain_Region_name" id="app_domain_Region_name" style="width:200px" value="{$region->getName()}" maxlength="100" /></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><input type="text" name="app_domain_Region_description" id="app_domain_Region_description" style="width:200px" value="{$region->getDescription()}" maxlength="100" /></td>
			</tr>
		</table>
	</fieldset>

	<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
	
</form>
{*
{/if}
*}

{include file="footer2.tpl"}