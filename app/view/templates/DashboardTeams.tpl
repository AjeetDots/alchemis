{include file="header2.tpl" title="Teams"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

{literal}



function toggleReminderDate()
{
	if ($F('set_reminder'))
	{
		if ($('div_reminder_date').style.display == 'block' || $('div_reminder_date').style.display == '') 
		{
			new Effect.BlindUp($('div_reminder_date'), {duration: 0.3});
		}
	}
	else
	{
		if ($('div_reminder_date').style.display == 'none') 
		{
			new Effect.BlindDown($('div_reminder_date'), {duration: 0.3});
		}
	}
	return false;
}

function doFrameItem(location)
{
//	alert('doMenuItem(' + location + ')');
	if (location == '')
	{
		return false;
	}
	else
	{
iframeLocation(		iframe1, location);
	}
}

function editTeam(team_id)
{
iframeLocation(	iframe1, 'index.php?cmd=TeamCreate&team_id=' + team_id);
}

function deleteTeam(team_id)
{
iframeLocation(	iframe1, 'index.php?cmd=TeamDelete&team_id=' + team_id);
}

{/literal}
</script>

<table class="adminform" border="0" cellpadding="0" cellspacing="0"{* style="border: 1px solid blue"*}>
	<tr>
		<td width="75%" valign="top">

			<div style="margin: 0 0 10px 0">
				<input type="button" id="add_team" name="add_team" value="Add Team" href="#" onclick="javascript:doFrameItem('index.php?cmd=TeamCreate')" style="cursor: pointer" />
			</div>

			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th style="text-align: left">Team Name</th>
									<th style="width: 5%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=action_loop from=$teams item=team}
							<tr id="tr_{$team->getId()}">
								<td style="text-align: center">{$team->getId()}</td>
								<td>{$team->getName()}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$team->getId()}" title="Edit" href="#" onclick="javascript:editTeam({$team->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/group_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$team->getId()}" title="Delete" href="#" onclick="javascript:deleteTeam({$team->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/group_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>

		</td>
		<td width="25%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

{include file="footer.tpl"}