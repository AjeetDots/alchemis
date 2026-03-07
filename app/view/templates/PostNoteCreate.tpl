{include file="header2.tpl" title="Add Post Note"}

<script type="text/javascript">
{literal}

function submitform(pressbutton)
{
//	alert('submitform(' + pressbutton + ')');
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
	alert('submitbutton(' + pressbutton + ')');
//	var form = document.adminForm;
//	var type = form.type.value;
	
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
		return;
	}
}

{/literal}
</script>

<form action="index.php?cmd=PostNoteCreate" method="post" name="adminForm" autocomplete="off">
<input type="hidden" name="task" value="" />
<input type="hidden" name="post_id" value="{$post_id}" />

	<fieldset class="adminform">
		<legend>Post Note</legend>
		<table>
			<tr>
				<td style="width: 80px" {if $errors.app_domain_Post_name} class="key_error" title="{$errors.app_domain_Post_name->getTip()}"{else}class="key"{/if}>
					<label for="app_domain_Post_name">Note *</label>
				</td>
				<td><textarea id="note" name="note"></textarea></td>
			</tr>
		</table>
	</fieldset>
	
	<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="button" value="Clear" onclick="$('note').value = '';" />

</form>

{include file="footer2.tpl"}