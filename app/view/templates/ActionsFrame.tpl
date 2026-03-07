{include file="header.tpl" title="Actions &amp; Calendar"}

<script language="JavaScript" type="text/javascript">
{literal}

// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
var tab_id = 3;

if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(tab_id))
{
	parent.tab_colln.add(tab_id);
}

function doMenuItem(location, location_href)
{
//	alert('doMenuItem(' + location + ')');
	if (location == 'ClientCalendar')
	{
		hideNbmDiv();
		toggleClientDiv();
		if (location_href !== undefined && location_href != '') {
			doClientCalendar(0,location_href) // note: use of dummy client_id 0
		}
	}
	else if (location == 'NbmCalendar')
	{
		hideClientDiv();
		toggleNbmDiv();
	}
	else
	{
		hideClientDiv();
		hideNbmDiv();

		switch (location)
		{
			case 'GlobalCalendar':
				location = 'Calendar';
				break;

			case 'MyCalendar':
				location = 'Calendar&nbm_id={/literal}{$user_id}{literal}';
				break;

//			case 'NbmCalendar':
//				location = 'Calendar&nbm_id={/literal}{$user_id}{literal}';
//				break;

			case 'DashboardActions':
			case 'DashboardEvents':
				break;
			
			default:
				alert('Invalid location: ' + location);
				return;
		}
		iframeLocation(ifr_admin, "index.php?cmd=" + location);
		// Reset option menu after a second
		setTimeout("$('admin_menu').selectedIndex = 0", 1000);
	}
}

function toggleClientDiv()
{
	new Effect.toggle($('div_client_id'), 'appear', {duration: 0.3});
	return false;
}

function hideClientDiv()
{
	if ($('div_client_id').style.display != 'none')
	{
		$('div_client_id').style.display = 'none';
//		toggleClientDiv();
	}
}

function doClientCalendar(client_id, location)
{
	if (!location) {
		var location = 'Calendar&client_id=' + client_id;
	}
	iframeLocation(ifr_admin, 'index.php?cmd=' + location);
	
	// Reset option menu after a second
	setTimeout("$('admin_menu').selectedIndex = 0", 1000);
}

function toggleNbmDiv()
{
	new Effect.toggle($('div_nbm_id'), 'appear', {duration: 0.3});
	return false;
}

function hideNbmDiv()
{
	if ($('div_nbm_id').style.display != 'none')
	{
		$('div_nbm_id').style.display = 'none';
//		toggleNbmDiv();
	}
}

function doNbmCalendar(nbm_id)
{
	var location = 'index.php?cmd=Calendar&nbm_id=' + nbm_id;
	iframeLocation(ifr_admin, location);
	
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
				<select id="admin_menu" name="admin_menu" style="width: 250px" onchange="javascript:doMenuItem($F('admin_menu')); return false;">
					<option value="0">-- select --</option>
					<option value="MyCalendar">My Calendar</option>
					{if $session_user->hasPermission('permission_view_global_calendar')}
						<option value="NbmCalendar">NBM Calendar</option>
					{/if}
					<option value="ClientCalendar">Client Calendar</option>
					{if $session_user->hasPermission('permission_view_global_calendar')}
						<option value="GlobalCalendar">Global Calendar</option>
					{/if}
					<option value="DashboardActions">Actions</option>
					<option value="DashboardEvents">Events</option>
				</select>
			</div>

			<div id="div_client_id" name="div_client_id" style="float: left">
				Client 
				<select id="client_id" name="client_id" style="width: 250px" onchange="javascript:doClientCalendar($F('client_id')); return false;">
					{html_options options=$client_options selected=$client_selected}
				</select>
			</div>

			<div id="div_nbm_id" name="div_nbm_id" style="float: left">
				NBM 
				<select id="nbm_id" name="nbm_id" style="width: 250px" onchange="javascript:doNbmCalendar($F('nbm_id')); return false;">
					{html_options options=$nbm_options selected=$nbm_selected}
				</select>
			</div>

		</td>
	</tr>
	<tr>
		<td>
			<iframe id="ifr_admin" name="ifr_admin" src="" scrolling="yes" border="0" frameborder="no" style="padding: 0; height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

<script language="JavaScript" type="text/javascript">
	{if $redirect == ''}
		doMenuItem('{$menu_item|default:'MyCalendar'}');
	{else}
		doMenuItem('ClientCalendar','{$redirect|replace:'/':'&'}');
	{/if}
</script>

{include file="footer.tpl"}