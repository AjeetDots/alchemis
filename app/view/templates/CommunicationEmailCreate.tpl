{include file="header2.tpl" title="Create Communication Email"}

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
	
	if (pressbutton == 'send')
	{
		if (confirm("Are you sure you wish to send this email?"))
		{
			submitform(pressbutton);
			return;
		}
	
	}
}

{/literal}

{if $success}
	parent.getWorkspaceNotes(0,0,{$post_initiative_id});
{/if}


</script>

{if $post}
<form action="index.php?cmd=CommunicationEmailCreate" method="post" name="adminForm" autocomplete="off">
<input type="hidden" name="post_initiative_id" value="{$post_initiative_id}" />
<input type="hidden" id="from" name="from" value="{$campaign_nbm->getUserEmail()}" />
<input type="hidden" name="campaign_nbm_user_alias" value="{$campaign_nbm->getUserAlias()}" />
<input type="hidden" name="task" value="" />

	<fieldset class="adminform">
		<legend>Compose Email</legend>
		<table cols="2">
			<tr>
				<td style="width: 20%" class="key">
					<label>To *</label>
				</td>
				<td><input style="width: 100%" type="text" id="to" name="to" value="{$contact->getEmail()}" /></td>
			</tr>
			<tr>
				<td class="key">
					<label>From</label>
				</td>
				<td>{$campaign_nbm->getUserEmail()}</td>
			</tr>
			<tr>
				<td class="key">
					<label>&lt;alias&gt;</label>
				</td>
				<td>{$campaign_nbm->getUserAlias()}</td>
			</tr>
			<tr>
				<td class="key">
					<label>Subject *</label>
				</td>
				<td><input style="width: 100%"type="text" id="subject" name="subject" /></td>
			</tr>
			<tr>
				<td class="key">
					<label>Body *</label>
				</td>
				<td><textarea style="width: 100%" rows="15" id="body" name="body"></textarea></td>
			</tr>
			<tr>
				<td class="key">Attachments</td>
				<td>
					
					<select name="attachment_id[]" id="attachment_id" multiple="multiple" size="5" style="width: 100%">
						{html_options options=$attachments}
					</select>
				</td>
			</tr>
			<tr>
				<td class="key">Information Requests</td>
				<td>
					
					<select name="action_id" id="action_id" style="width: 100%">
						{html_options options=$information_requests}
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	
	{if $contact->getEmail() == '' || $campaign_nbm->getUserEmail() == ''}
		{* do nothing *}
	{else}
	<input type="button" value="Send" onclick="javascript:submitbutton('send')" />&nbsp;|&nbsp;
	<input type="button" value="Clear" onclick="$('body').value = '';" />
	{/if}
	

</form>
{/if}
{include file="footer2.tpl"}