{include file="header2.tpl" title="NBM Teams"}

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

function editMessage(message_id)
{
iframeLocation(	iframe1, 'index.php?cmd=MessageCreate&message_id=' + message_id);
}

function deleteMessage(message_id)
{
iframeLocation(	iframe1, 'index.php?cmd=MessageDelete&message_id=' + message_id);
}

{/literal}
</script>

<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>

		<form name="form1" action="index.php?cmd=DashboardNbms" method="post">
			<table id="tbl_characteristic_list" class="adminlist">
				<thead>
					<tr>
						<th>NBM</th>
						<th>Team</th>
					</tr>
				</thead>
				{foreach name=nbm_loop from=$nbms item=nbm}
				<tr id="tr_{$nbm.id}">
					<td>{$nbm.name}</td>
					<td>
						<select name="team_{$nbm.id}" tabindex="{$smarty.foreach.nbm_loop.iteration}">
							<option value="-1">- Select -</option>
							{html_options options=$teams selected=$nbm.team_id}
						</select>
					</td>
				</tr>
				{/foreach}
			</table>
			<p><input type="submit" name="save_button" value="Save" /></p>
		</form>


		</td>
	</tr>
</table>

{include file="footer.tpl"}