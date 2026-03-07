{include file="header.tpl" title="Calendar Day"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
{literal}

	// Maintain global tab collection (tab_colln)
	// If this page has been loaded then we don't want to reload it when the tab is clicked
	if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(3))
	{
		parent.tab_colln.add(3);
	}

	function doFrameItem(location)
	{
	//	alert('doMenuItem(' + location + ')');
		if (location == "")
		{
			return false;
		}
		else
		{
			switch (location)
			{
				case 'Actions':
				case 'Calendar':
					break;
	
	//			case '0':
	//				return;
	
	//			default:
	//				alert('Invalid location: ' + location);
	//				return;
			}
// iframeLocation(iframe_day_view, "index.php?cmd=" + location);
			iframeLocation(parent.iframe_edit_pane, location);
		}
	
	//	// Reset menu after a second
	//	setTimeout("$('admin_menu').selectedIndex = 0", 1000);
	}

	function showPost(company_id, post_id, initiative_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
		if (initiative_id && initiative_id != '')
		{
			// do nothing
		}
		else
		{
			initiative_id= + top.$F("initiative_list");
		}
		iframeLocation(top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id);
		top.loadTab(5,"");
		colln.goToPostId(post_id);
		highlightSelectedRow(company_id, post_id);
	}

	function showAll(table_id)
	{
		tbl = document.getElementById(table_id);
		for (i = 0; i < tbl.rows.length; i++)
		{
			tbl.rows[i].style.display = '';
		}
	}	

	function showActions(table_id)
	{
		showType(table_id, 'action');
	}

	function showEvents(table_id)
	{
		showType(table_id, 'event');
	}

	function showMeetings(table_id)
	{
		showType(table_id, 'meeting');
	}

	/**
	 * @param string table_id
	 * @param string type {'action', 'event', 'meeting'}
	 */
	function showType(table_id, type)
	{
		tbl = document.getElementById(table_id);
		for (i = 0; i < tbl.rows.length; i++)
		{
			if (tbl.rows[i].id.startsWith(type))
			{
				tbl.rows[i].style.display = '';
			}
			else
			{
				tbl.rows[i].style.display = 'none';
			}
		}
	}

{/literal}
</script>

<div style="margin: 0 0 10px 0">
	<input type="button" id="add_action" name="add_action" value="Add Action" href="#" onclick="javascript:doFrameItem('index.php?cmd=ActionCreate&amp;referrer=Calendar&amp;client_id={$client_id}&amp;nbm_id={$nbm_id}&amp;date={$date}'); return false;" />&nbsp;
	<input type="button" id="add_event" name="add_event" value="Add Event" href="#" onclick="javascript:doFrameItem('index.php?cmd=EventCreate&amp;referrer=Calendar&amp;client_id={$client_id}&amp;nbm_id={$nbm_id}&amp;date={$date}'); return false;" />&nbsp;
</div>

<div style="margin: 0 0 10px 0">
	<span style="margin-right: 10px">Display Options:</span>
	<a href="#" style="cursor: pointer" onClick="showAll('calendar_day'); return false;">All</a> |
	<a href="#" style="cursor: pointer" onClick="showActions('calendar_day'); return false;">Actions</a> |
	<a href="#" style="cursor: pointer" onClick="showEvents('calendar_day'); return false;">Events</a> |
	<a href="#" style="cursor: pointer" onClick="showMeetings('calendar_day'); return false;">Meetings</a>
</div>

{calendar_day data=$day_data legend=true}

{include file="footer.tpl"}