{include file="header2.tpl" title="Team Create"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

{if $success}

	<p>The team has been created.</p>
{*	<input type="button" id="btn_add_team" value="Add another team" onclick="javascript:document.location.href='index.php?cmd=Team'" />*}

	<p>Redirect to 'index.php?cmd=DashboardTeams'</p>
	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=DashboardTeams';
	</script>
	
{else}


<script language="JavaScript" type="text/javascript">
{literal}

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

function submitbutton(pressbutton)
{
	if (pressbutton == 'save') 
	{
		if (validate())
		{
			// If any of the meeting date/time related form items exist but are hidden (eg when status changed to cancelled)
			// then need to re-enable them so that the data is submitted. Otherwise the meeting update will fail on the server
			if ($("app_domain_Meeting_date") != undefined)
				$("app_domain_Meeting_date").disabled = false;
			if ($("meeting_time_Hour") != undefined)
				$("meeting_time_Hour").disabled = false;
			if ($("meeting_time_Minute") != undefined)
				$("meeting_time_Minute").disabled = false;
				
			submitform( pressbutton );
			return;
		}
	}
}


function submitbutton(pressbutton)
{
//	alert('submitbutton(' + pressbutton + ')');
//	var form = document.adminForm;
//	var type = form.type.value;
	
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
	}
	else if (pressbutton == 'reset')
	{
		document.adminForm.reset();
	}
}

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

{/literal}
</script>

	<form action="index.php?cmd=TeamCreate" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="app_domain_Team_id" value="{$app_domain_Team_id}" />
	
		<fieldset class="adminform">
			<legend>Team</legend>

			<table>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Team_name} class="key_error" title="{$errors.app_domain_Team_name->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Team_name">Name *</label>
					</td>
					<td><input type="text" name="app_domain_Team_name" id="app_domain_Team_name" style="width: 200px" value="{$app_domain_Team_name}" maxlength="50" /></td>
				</tr>
			</table>

		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /> | <input type="reset" value="Reset" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}