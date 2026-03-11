{include file="header.tpl" title="Dashboard"}

<script language="JavaScript" type="text/javascript">
{literal}

// Maintain global tab collection (tab_colln)
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(1))
{
	top.parent.tab_colln.add(1);
}



function doMenuItem(location)
{
	if (location == "")
	{
		return false;
	}
	else if (location == 'NbmDashboard')
	{
		toggleNbmDiv();
	}
	else
	{
		hideNbmDiv();
		
		switch (location)
		{
			case 'Dashboard':
			case 'DashboardActions':
			case 'DashboardCallBacks':
			case 'DashboardEvents':
			case 'DashboardInformationRequests':
			case 'DashboardMeetings':
			case 'DashboardMessages':
			case 'DashboardNbms':
			case 'DashboardTeams':
				break;
			
			default:
				alert('Invalid location: ' + location);
				return;
		}
		var adminFrame = document.getElementById('ifr_admin');
		iframeLocation(adminFrame, "index.php?cmd=" + location);
	}

	// Reset menu after a second
	setTimeout("$('admin_menu').selectedIndex = 0", 1000);
}

function toggleNbmDiv()
{
	new Effect.toggle($('div_nbm_id'), 'appear', {duration: 0.3});
	return false;
}

function hideNbmDiv()
{
	$('div_nbm_id').style.display = 'none';
}

function doNbmDashboard(nbm_id)
{
	var location = 'index.php?cmd=Dashboard&nbm_id=' + nbm_id;
	var adminFrame = document.getElementById('ifr_admin');
	iframeLocation(adminFrame, location); 
	
	// Reset option menu after a second
	setTimeout("$('admin_menu').selectedIndex = 0", 1000);
}

{/literal}
</script>


<table class="adminform">
	<tr>
		<td style="height:20px">

			<div style="float: left; margin-right: 10px">
				Choose administration option 
				<select id="admin_menu" name="admin_menu" style="width:250px" onchange="javascript:doMenuItem($F('admin_menu'));">
					<option value="0">-- select --</option>
					<option value="Dashboard">Dashboard</option>
					{if $session_user->hasPermission('permission_view_global_calendar')}
						<option value="NbmDashboard">NBM Dashboard</option>
					{/if}
	
	{*				<optgroup label="Calendaring">
						<option value="DashboardActions">Actions</option>
						<option value="DashboardEvents">Events</option>
					</optgroup>
	*}				<optgroup label="Due Today">
						<option value="DashboardCallBacks">Call Backs</option>
						<option value="DashboardInformationRequests">Information Requests</option>
						<option value="DashboardMeetings">Meetings</option>
					</optgroup>
	
					{if $session_user->hasPermission('permission_admin_messages') 
						|| $session_user->hasPermission('permission_admin_nbm_teams') 
						|| $session_user->hasPermission('permission_admin_teams')} 
	
						<optgroup label="Administration">
	
							{if $session_user->hasPermission('permission_admin_messages')} 
								<option value="DashboardMessages">Messages</option>
							{/if}
	
							{if $session_user->hasPermission('permission_admin_nbm_teams')}
								<option value="DashboardNbms">NBM Teams</option>
							{/if}
	
							{if $session_user->hasPermission('permission_admin_teams')}
								<option value="DashboardTeams">Teams</option>
							{/if}
	
						</optgroup>
	
					{/if}
	
				</select>
			</div>

			<div id="div_nbm_id" name="div_nbm_id" style="float: left">
				NBM 
				<select id="nbm_id" name="nbm_id" style="width: 250px" onchange="javascript:doNbmDashboard($F('nbm_id')); return false;">
					{html_options options=$nbm_options selected=$nbm_selected}
				</select>
			</div>

		</td>
	</tr>
	<tr>
		<td>
			<iframe id="ifr_admin" name="ifr_admin" src="" scrolling="yes" 
			border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

<script language="JavaScript" type="text/javascript">
	doMenuItem('Dashboard');
</script>

{include file="footer.tpl"}