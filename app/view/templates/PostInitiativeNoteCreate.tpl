{include file="header2.tpl" title="Add Post Initiative Note"}

{if $success}
<script type="text/javascript">
	parent.getWorkspaceNotes('{$post_id}', '{$initiative_id}', '{$post_initiative_id}');
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


<form action="index.php?cmd=PostInitiativeNoteCreate" method="post" name="adminForm" autocomplete="off">
<input type="hidden" name="task" value="" />
<input type="hidden" name="post_id" value="{$post_id}" />
<input type="hidden" name="initiative_id" value="{$initiative_id}" />
<input type="hidden" name="post_initiative_id" value="{$post_initiative_id}" />
<input type="hidden" name="communication_id" value="{$communication_id}" />
	<fieldset class="adminform">
		<legend>{if $communication_id != ''}Add Communication Note{else}Add Post Initiative Note{/if}</legend>
		<table>
			<tr>
				<td style="width: 20%; vertical-align:top" {if $errors.app_domain_Post_name} class="key_error" title="{$errors.app_domain_Post_name->getTip()}"{else}class="key"{/if}>
					<label for="app_domain_Post_name">Note *</label>
				</td>
				<td style="width: 80%"><textarea id="note" name="note" cols="40" rows="6"></textarea></td>
			</tr>
		</table>
	</fieldset>
	
	<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="button" value="Clear" onclick="$('note').value = '';" />

</form>
{/if}
{include file="footer2.tpl"}