{include file="header2.tpl" title="Mailer Create"}

<script type="text/javascript">
{literal}

function submitform(pressbutton)
{
	document.frm_mailer_add.task.value = pressbutton;
	
	try
	{
		document.frm_mailer_add.onsubmit();
	}
	catch(e)
	{}
	
	document.frm_mailer_add.submit();
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

{if $feedback == "Mailer added successfully"}
	<p>
		<a href="index.php?cmd=MailerItemCreate&mailer_id={$new_mailer->getId()}&initiative_id={$new_mailer->getClientInitiativeId()}">
			Add new recipients to this mailer
		</a>
	</p>
	
	<p><a href="index.php?cmd=MailerList" target="_parent">Refresh the mailer list</a></p>
	
{else}
	
	<form id="frm_mailer_add" name="frm_mailer_add" action="index.php?cmd=MailerCreate" method="post">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="source_tab" value="{$source_tab}" />
	
		<table class="adminlist" style="width:500px">
			<tr>	
				<th>Client Initiative</th>
				<td>
					<select style="width: 100%" id="app_domain_Mailer_client_initiative_id" name="app_domain_Mailer_client_initiative_id">
						<option value="0">&ndash; Select &ndash;</option>
						{html_options options=$client_initiatives}
					</select>
				</td>
			</tr>
			<tr>
				<th>Name</th>
				<td><input type="text" id="app_domain_Mailer_name" name="app_domain_Mailer_name" style="width:100%" /></td>
			</tr>
			<tr>	
				<th>Description</th>
				<td><textarea id="app_domain_Mailer_description" name="app_domain_Mailer_description" rows="4" cols="50" style="width:100%"></textarea></td>
			</tr>
			<tr>	
				<th>Type</th>
				<td>
					<select style="width: 100%" id="app_domain_Mailer_type_id" name="app_domain_Mailer_type_id">
						<option value="0">&ndash; Select &ndash;</option>
						{html_options options=$mailer_types}
					</select>
				</td>
			</tr>
			<tr>	
				<th>Response Group</th>
				<td>
					<select style="width: 100%" id="app_domain_Mailer_response_group_id" name="app_domain_Mailer_response_group_id">
						<option value="0">&ndash; Select &ndash;</option>
						{html_options options=$mailer_response_groups}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span style="float: right">
						<input type="button" value="Save"  onclick="javascript:submitbutton('save')"  />
						<input type="button" value="Cancel" class="popup_closebox" />
					</span>
				</td>
			</tr>
		
		</table>
	</form>

{/if}
{include file="footer2.tpl"}