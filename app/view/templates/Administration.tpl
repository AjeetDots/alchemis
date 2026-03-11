{include file="header.tpl" title="Administration"}

<script language="JavaScript" type="text/javascript">
{literal}

// Maintain global tab collection (tab_colln)
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (top.parent.tab_colln !== undefined && ! top.parent.tab_colln.goToValue(12))
{
	top.parent.tab_colln.add(12);
}

function doMenuItem(location)
{
	if (location == "")
	{
		return false;
	}
	else
	{
		switch (location)
		{
			case 'CharacteristicList':
			case 'AdminRegions':
			case 'AdminRegionPostcodes':
			case 'AdminReports':
			case 'User':
			case 'CampaignView':
			case 'Dedupe':
			case 'Categories':
			case 'Whitelist':
			case 'Postcode':
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
	
{/literal}
</script>


<table class="adminform">
	<tr>
		<td style="height:20px">
			Choose administration option 
			<select id="admin_menu" name="admin_menu" style="width:250px" onchange="javascript:doMenuItem($F('admin_menu'));">
				<option value="0">-- select --</option>

				{if $session_user->hasPermission('permission_admin_client_campaigns')
					|| $session_user->hasPermission('permission_admin_clients_nbm_admin')}
					<option value="CampaignView">Client Campaigns</option>
				{/if}
{*
				{if $session_user->hasPermission('permission_admin_nbm_monthly_planner')} 
					<option value="NbmMonthlyPlanner">NBM Monthly Planner</option>
				{/if}
*}
				{if $session_user->hasPermission('permission_admin_characteristics')} 
					<option value="CharacteristicList">Characteristics</option>
				{/if}

				{if $session_user->hasPermission('permission_admin_regions')} 
					<option value="AdminRegions">Regions</option>
				{/if}

				{if $session_user->hasPermission('permission_admin_reports')} 
					<option value="AdminReports">Reports</option>
				{/if}

				{if $session_user->hasPermission('permission_admin_users')} 
					<option value="User">Users</option>
				{/if}

					<option value="Categories">Categories</option>

					<option value="Dedupe">Dedupe</option>
					
				{if $session_user->hasPermission('permission_admin_whitelist')} 
					<option value="Whitelist">IP Whitelist</option>
				{/if}
				
				{if $session_user->hasPermission('permission_admin_postcode')} 
					<option value="Postcode">Postcodes</option>
				{/if}
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<iframe id="ifr_admin" name="ifr_admin" src="" scrolling="yes" 
			border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

<script type="text/javascript">init_moofx();</script>

{include file="footer.tpl"}