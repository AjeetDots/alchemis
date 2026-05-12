{include file="header_js.tpl" title="Home"}

<script language="JavaScript" type="text/javascript">
{literal}

	var watch_events = true;
	var current_company_id;
	
//	document.addEventListener("keypress", function(e) {handleF5(e)}, true);
	window.addEventListener("beforeunload", function(e) {handleRefresh(e)}, true);
	
	function disableEventListener()
	{
//		window.removeEventListener('beforeunload', function(e) {handleRefresh(e)}, false);
		// 'Turn off' event watching. Hack around the removeEventListener not seeming to work.
		watch_events = false;
	}

	function handleF5(e)
	{
		if (e.keyCode == 116)
		{
			alert("F5 pressed");
			e.preventDefault();
		}
	}
	
	function handleRefresh(e)
	{
		if (watch_events) e.preventDefault();
	}
	
	var tab_colln = new ill_Data_Collection();
	
	var communication_loaded = false;
	
	var current_tab_id = 1;

	/**
	 * Silently pre-load frequently used tabs so switching to them is instant.
	 * Each tab is staggered to avoid hitting the server all at once.
	 * We set the iframe src directly (no responderFadeIn) so the counter stays 0
	 * and background load completions never dismiss a user "Working..." spinner.
	 */
	function preloadTabsBackground() {
		var preloadQueue = [
			[3, 'ActionsFrame'],
			[2, 'NbmMonthlyPlanner'],
			[4, 'Communication']
		];
		function loadNext(i) {
			if (i >= preloadQueue.length) { return; }
			var tab   = preloadQueue[i][0];
			var cmd   = preloadQueue[i][1];
			if (!tab_colln.goToValue(tab)) {
				$('iframe_' + tab).src = 'index.php?cmd=' + cmd;
			}
			setTimeout(function() { loadNext(i + 1); }, 4000);
		}
		loadNext(0);
	}

	function loadHomeScoreboard() {
		var ill_params = {};
		getAjaxData("AjaxScoreboard", "", "get_home_scoreboard", ill_params, "");
	}

	function AjaxScoreboard(data) {
		if (!data || !data.length) return;
		for (var i = 0; i < data.length; i++) {
			var t = data[i];
			if (t.cmd_action === "get_home_scoreboard" && t.success) {
				if ($("communication_count")) $("communication_count").innerHTML = "Calls: " + (t.communication_count != null ? t.communication_count : 0);
				if ($("effective_count")) $("effective_count").innerHTML = "Effectives: " + (t.effective_count != null ? t.effective_count : 0);
				if ($("span_callback_count")) $("span_callback_count").innerHTML = "Today's Callbacks: " + (t.callback_count != null ? t.callback_count : 0) + " (" + (t.priority_callback_count != null ? t.priority_callback_count : 0) + ")";
				break;
			}
		}
	}

{/literal}
</script>

<body style="background-color: #003366" onload="loadTab(1, 'DashboardFrame');{if $redirect != ''}loadTab(3,'ActionsFrame&redirect={$redirect}', true){/if}; loadHomeScoreboard(); monitorCallBacks(); setTimeout(preloadTabsBackground, 6000);"{*if $tab == "tabWorkspace"} onload="javascript:screenSize();"{/if*}>

	<form id="adminform">

	<div id="header-box">
		<div id="module-status" style="float: left">
			<span id="prod" style="padding-right: 3px">{$APP_NAME} {$APP_VERSION}</span>
			<span id="current_handle" class="subheader">Current User: &nbsp;<strong>{$user.handle} [{$user.id}]</strong></span>
			<span class="subheader">
				Default Initiative:&nbsp;
				<select id="initiative_list" name="initiative_list">
					<option selected value="0">&mdash; Select Initiative &mdash;</option>
					{foreach name="result_loop" from=$client_initiatives item=result}
						<option value="{$result.initiative_id}"{if $result.initiative_id == 1} selected{/if}>{$result.client_initiative_display}</option>
					{/foreach}
				</select>
			</span>
			<span id="loaded_filter_name" class="subheader" style="width:500px">Current Filter:&nbsp;<strong>None</strong></span>
		</div>
		<div id="module-status">
{*			<span id="prod">Help</span>*}
			<span id="communication_count">Calls: {$scoreboard->getCommunicationCount()}</span>
			<span id="effective_count">Effectives: {$scoreboard->getEffectiveCount()}</span>
			<span id="span_callback_count" style="cursor: pointer" onclick="javascript:showCallBacks(); return false;">Today's Callbacks: {$scoreboard->getCallBackCount()} ({$scoreboard->getPriorityCallBackCount()})</span>
			<span style="cursor: pointer" onclick="javascript:showScoreboard(); return false;"><img src="{$APP_URL}app/view/images/icons/chart_bar.png" alt="Scoreboard" title="Scoreboard" /></span>
{*			<span class="no-unread-messages"><a href="#">0</a></span>*}
			<span class="logout"><a href="index.php?cmd=Logout">Logout</a></span>
		</div>
		<div class="clr"></div>
	</div>

	<div id="border-top" style="border: 0px solid blue; height: 25px; {*background: url({$APP_URL}app/view/images/tile-0001.png)*}">
		<table class="Tabs" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20" class="NoTabs" nowrap="nowrap"><div style="height: 23px; width: 20px">&nbsp;</div></td>
				<td id="tab_1" class="{if $tab == "1"}TabSlct{else}TabDsbl{/if}" valign="middle" nowrap="nowrap"><div style="height: 50px;"><a href="javascript: loadTab(1, 'DashboardFrame');">Dashboard</a><a id="ref_1" style="display: inline" href="javascript: refreshTab(1);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_2" class="{if $tab == "2"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(2, 'NbmMonthlyPlanner');">Monthly Planner</a><a id="ref_2" style="display: none" href="javascript: refreshTab(2);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_3" class="{if $tab == "3"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(3, 'ActionsFrame{*Calendar&amp;client_id={$client_id}&amp;date={$smarty.now|date_format:"%Y-%m"}*}');">Actions &amp; Calendar</a><a id="ref_3" style="display: none" href="javascript: refreshTab(3);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_4" class="{if $tab == "4"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(4, 'Communication');">Communication</a><a id="ref_4" style="display: none" href="javascript: refreshTab(4);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_5" class="{if $tab == "5"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(5, 'WorkspaceSearch');">Search Workspace</a><a id="ref_5" style="display: none" href="javascript: refreshTab(5);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_6" class="{if $tab == "6"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(6, 'Search');">Search</a><a id="ref_6" style="display: none" href="javascript: refreshTab(6);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_7" class="{if $tab == "7"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(7, 'WorkspaceFilter');">Filter Workspace</a><a id="ref_7" style="display: none" href="javascript: refreshTab(7);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_8" class="{if $tab == "8"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(8, 'FilterResults');">Filter Results</a><a id="ref_8" style="display: none" href="javascript: refreshTab(8);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_9" class="{if $tab == "9"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(9, 'FilterList');">Filter List</a><a id="ref_9" style="display: none" href="javascript: refreshTab(9);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>
				<td id="tab_10" class="{if $tab == "10"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap"><div style="height: 50px"><a href="javascript: loadTab(10, 'MailerList');">Mailers</a><a id="ref_10" style="display: none" href="javascript: refreshTab(10);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a></div></td>

				<td id="tab_11" class="{if $tab == "11"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap">
					<div style="height: 50px">
						{*{if $session_user->hasPermission('permission_admin_reports')}*}
							<a href="javascript: loadTab(11, 'Reporting');">Reporting</a>
							<a id="ref_11" style="display: none" href="javascript: refreshTab(11);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a>
						{*{else}*}
						{*	<a href="#" style="color: #333">Reporting</a>
							<a id="ref_11" style="display: none" href="#" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a>
						*}
						{*{/if}*}
					</div>
				</td>

				<td id="tab_12" class="{if $tab == "12"}TabSlct{else}TabDsbl{/if}" valign="bottom" nowrap="nowrap">
					<div style="height: 50px">
						{if $session_user->hasPermission('permission_admin_client_campaigns')
							|| $session_user->hasPermission('permission_admin_clients_nbm_admin')
							|| $session_user->hasPermission('permission_admin_characteristics')
							|| $session_user->hasPermission('permission_admin_regions')}
							{*|| $session_user->hasPermission('permission_admin_reports')*}
							<a href="javascript: loadTab(12, 'Administration');">Administration</a>
							<a id="ref_12" style="display: none" href="javascript: refreshTab(12);" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a>
						{else}
							<a href="#" style="color: #333">Administration</a>
							<a id="ref_12" style="display: none" href="#" title="Click to refresh this page">&nbsp;&nbsp;<img style="vertical-align: middle;" src="{$APP_URL}app/view/images/refresh.png" /></a>
						{/if}
					</div>
				</td>
				
				
				<td width="100%" class="NoTabs" style="border-right: none" nowrap="nowrap"><div>&nbsp;</div></td>
			</tr>
		</table>
	</div>

	<div id="content-box">
		<div class="border">
			<div class="padding">
				<div class="clr">
					<iframe id="iframe_1" name="iframe_1" src="" scrolling="auto" border="0" frameborder="no" style="display: block; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_2" name="iframe_2" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_3" name="iframe_3" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_4" name="iframe_4" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_5" name="iframe_5" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_6" name="iframe_6" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_7" name="iframe_7" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_8" name="iframe_8" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_9" name="iframe_9" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_10" name="iframe_10" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_11" name="iframe_11" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					<iframe id="iframe_12" name="iframe_12" src="" scrolling="auto" border="0" frameborder="no" style="display: none; height: 1000px; width: 100%; overflow-x: hidden; overflow-y: auto"></iframe>
					{* Voispeed / custom protocol: links from workspace iframes target this so Chrome does not replace iframe_5 or open an about:blank tab. *}
					<iframe id="alchemis_voispeed_dial" name="alchemis_voispeed_dial" title="" src="about:blank" style="position:absolute;width:0;height:0;border:0;left:0;top:0;opacity:0;pointer-events:none" aria-hidden="true"></iframe>
				</div>
			</div>
		</div>
	</div>

	</form>

	<script language="JavaScript">
	{literal}
	function screenSize()
	{
		var iframe_height = Math.floor((window.innerHeight - 90));
		$('iframe_1').style.height = iframe_height + 'px';
		$('iframe_2').style.height = iframe_height + 'px';
		$('iframe_3').style.height = iframe_height + 'px';
		$('iframe_4').style.height = iframe_height + 'px';
		$('iframe_5').style.height = iframe_height + 'px';
		$('iframe_6').style.height = iframe_height + 'px';
		$('iframe_7').style.height = iframe_height + 'px';
		$('iframe_8').style.height = iframe_height + 'px';
		$('iframe_9').style.height = iframe_height + 'px';
		$('iframe_10').style.height = iframe_height + 'px';
		$('iframe_11').style.height = iframe_height + 'px';
		$('iframe_12').style.height = iframe_height + 'px';
	}
	screenSize();
	jQuery(window).resize(screenSize);
	{/literal}
	</script>

	<style>
	{literal}
		#notification {
			position: fixed;
			bottom: 0;
			left: 0;
			right: 0;
			height: 32px;
			background: #fffde7;
			border-top: 2px solid #f9a825;
			box-shadow: 0 -2px 8px rgba(0,0,0,0.18);
			z-index: 9999;
			font-family: Verdana, Arial, sans-serif;
			font-size: 12px;
			padding: 0 12px;
			box-sizing: border-box;
		}
		.alc-notif-inner {
			display: inline-flex;
			align-items: center;
			height: 32px;
			vertical-align: top;
		}
		.alc-spinner {
			width: 14px;
			height: 14px;
			border: 2px solid #f9a825;
			border-top-color: #e65100;
			border-radius: 50%;
			animation: alc-spin 0.75s linear infinite;
			flex-shrink: 0;
		}
		@keyframes alc-spin {
			to { transform: rotate(360deg); }
		}
		.alc-stop-btn {
			float: right;
			margin-top: 5px;
			background: #e53935;
			color: #fff;
			border: none;
			border-radius: 3px;
			padding: 3px 10px;
			font-size: 11px;
			font-family: Verdana, Arial, sans-serif;
			font-weight: 600;
			cursor: pointer;
			line-height: 1.4;
		}
		.alc-stop-btn:hover { background: #b71c1c; }

		/* Disable all nav tabs while loading */
		body.alc-loading td[id^="tab_"] a,
		body.alc-loading a[id^="ref_"] {
			pointer-events: none !important;
			opacity: 0.38 !important;
			cursor: not-allowed !important;
		}
		body.alc-loading td[id^="tab_"] {
			cursor: not-allowed;
		}

		/* Prevent the outer body scrollbar */
		body { overflow-y: hidden; }
		
		/* Remove ugly browser scrollbars but preserve scrolling */
		iframe {
			scrollbar-width: none; /* Firefox */
			-ms-overflow-style: none; /* IE/Edge */
		}
		iframe::-webkit-scrollbar {
			display: none; /* Chrome/Safari */
		}
	{/literal}
	</style>
	<div id="notification" style="display: none;"></div>
	
</body>
</html>