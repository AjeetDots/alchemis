{include file="header.tpl" title="Calendar"}

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
			iframeLocation(iframe_day_view, location);
		}
	
	//	// Reset menu after a second
	//	setTimeout("$('admin_menu').selectedIndex = 0", 1000);
	}

	function showAll()
	{
		elements = document.getElementsByTagName('div');
		var arr = ['action', 'event', 'meeting'];
		for (i = 0; i < elements.length; i++)
		{
			if (in_array(elements[i].id, arr))
			{
				elements[i].style.display = '';
			}
		}
	}	

	function showActions()
	{
		showType('action');
	}

	function showEvents()
	{
		showType('event');
	}

	function showMeetings()
	{
		showType('meeting');
	}

	/**
	 * @param string type {'action', 'event', 'meeting'}
	 */
	function showType(type)
	{
		elements = document.getElementsByTagName('div');
		var arr = ['action', 'event', 'meeting'];
		for (i = 0; i < elements.length; i++)
		{
			if (in_array(elements[i].id, arr))
			{
				if (elements[i].id.startsWith(type))
				{
					elements[i].style.display = '';
				}
				else
				{
					elements[i].style.display = 'none';
				}
			}
		}
	}

	function in_array(needle, haystack)
	{
		for (var i = 0; i < haystack.length; i++)
		{
			if (needle.startsWith(haystack[i]))
			{
				return true;
			}
		}
		return false;
	}

{/literal}
</script>

<table class="dashboard" border="0" cellpadding="0" cellspacing="20" style="background-color: #F9F9F9; border: 1px solid #ccc; width: 100%; height: 650px">
	<tr>
		<td style="width: 50%">
			
			<div class="monthYearTextTOC" style="margin-bottom: 10px"> 
				{if $nbm_id}
					Calendar for {$nbm_name} 
				{elseif $client_id}
					Calendar for {$client_name}
				{else}
					Global Calendar
				{/if}
			</div>
			
			<div style="margin: 0 0 10px 0">
				<span style="margin-right: 10px">Display Options:</span>
				<a href="#" style="cursor: pointer" onClick="showAll(); return false;">All</a> |
				<a href="#" style="cursor: pointer" onClick="showActions(); return false;">Actions</a> |
				<a href="#" style="cursor: pointer" onClick="showEvents(); return false;">Events</a> |
				<a href="#" style="cursor: pointer" onClick="showMeetings(); return false;">Meetings</a>
			</div>

			{calendar_month data=$month_data
			                year=$year
			                month=$month
			                day=$day
			                legend=true
			                hide_completed_items=true
			                print_month_name=true
			                print_year=true
			                url="home.php"
			                width="100%"
			                day_link="index.php?cmd=Calendar&client_id=$client_id"
			                day_onclick=true
			                navigation=true
			                onclick='showCalendarDay'
			                nbm_id=$nbm_id
			                client_id=$client_id}
		</td>
		<td style="width: 25%">
			<iframe id="iframe_day_view" name="iframe_day_view" src="" scrolling="yes" border="0" frameborder="no"
				style="height: 650px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
		<td style="width: 25%">
			<iframe id="iframe_edit_pane" name="iframe_edit_pane" src="" scrolling="yes" border="0" frameborder="no"
				style="height: 650px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

{if $date}
	<script language="JavaScript" type="text/javascript">
		doFrameItem('index.php?cmd=CalendarDay&client_id=&date={$date}&nbm_id={$nbm_id}&client_id={$client_id}');
	</script>
{/if}

{include file="footer.tpl"}