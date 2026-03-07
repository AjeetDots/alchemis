{include file="header.tpl" title="Dashboard - Meetings"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
{literal}

	// Maintain global tab collection (tab_colln)
	// If this page has been loaded then we don't want to reload it when the tab is clicked
	if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(1))
	{
		top.parent.tab_colln.add(1);
	}
	
	function showPost(company_id, post_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
iframeLocation(		top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
		top.loadTab(5,"");
		colln.goToPostId(post_id);
		highlightSelectedRow(company_id, post_id);
	}

	// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them
	// back to normal when a new company and/or post is selected
//	var last_company_class_change_id = "";
	var last_post_class_change_id = "";
		
	function highlightSelectedRow(company_id, post_id, post_initiative_id)
	{
		//set the background of the selected row
//		$("tr_" + company_id).className="current";
		
//		if (post_id != "")
//		{
			$("tr_post_" + post_id).className="current";
//		}
		
		// now set the previously selected items to a normal background
//		if (last_company_class_change_id != "" && last_company_class_change_id != company_id)
//		{
//			$("tr_" + last_company_class_change_id).className="";
//		}
//		last_company_class_change_id = company_id;
		
		if (last_post_class_change_id != "" && last_post_class_change_id != post_id)
		{
			$("tr_post_" + last_post_class_change_id).className="";
		}
		last_post_class_change_id = post_id;
	}

	function goToHash(hash_location)
	{
		var mypos = findPos($(hash_location));
		$("div_results").scrollTop = mypos[1]-200;
	}
	
	function findPos(obj) 
	{
		//alert ("in pos");
        var curleft = curtop = 0;
        if (obj.offsetParent) 
        {
                curleft = obj.offsetLeft;
                curtop = obj.offsetTop;
                //alert (curtop);
                while (obj = obj.offsetParent) 
                {
                        curleft += obj.offsetLeft;
                        //alert (curtop);
                        curtop += obj.offsetTop;
                }
        }
        return [curleft,curtop];

	}	

	colln = new ill_Data_Collection(); 
	
	// set page_isloaded to false so we can check in header_js.loadTab whether we need to highlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
	page_isloaded = false;


	// Make Ajax call to load table of meetings with the selected status
	function loadMeetingStatus()
	{
		var ill_params = new Object;
		ill_params.item_id = $F('meeting_status_id');
		$('meeting_status_id').disabled = true;
		$('ajax_loader').style.display = '';
		getAjaxData('AjaxDashboard', '', 'load_meeting_status', ill_params, 'Adding...')
	}

	// Ajax return data handlers
	function AjaxDashboard(data)
	{
		for (i = 1; i < data.length + 1; i++) 
		{
			t = data[i-1];
			switch (t.cmd_action)
			{
				case 'load_meeting_status':
					$('meeting_status').innerHTML = t.line_html;
					$('meeting_status_id').disabled = false;
					$('ajax_loader').style.display = 'none';
					break;
				
				default:
					alert('No cmd_action specified');
					break;
			}
		}
	}

{/literal}
</script>


<div class="panel">
	<h3><span>Meetings</span></h3>
	<div>
		
		<p style="margin-left: 10px">You have <strong>{$todays_meetings|@count}</strong> meeting{if $todays_meetings|@count != 1}s{/if} taking place today</p>
		
		{if $todays_meetings}
		<table id="table1" class="adminlist sortable" id="sortable_{$result.id}" cellspacing="1">
			<thead>
				<tr>
					<th style="width: 1%; text-align: center">#</th>
					<th style="width: 1%; text-align: center">ID</th>
					<th style="width: 14%; text-align: left">Client</th>
					<th style="width: 13%; text-align: left">Company</th>
					<th style="width: 14%; text-align: left">Job Title</th>
					<th style="width: 13%; text-align: left">Post Holder</th>
					<th style="width: 10% ;text-align: left">Date &amp; Time</th>
					<th style="width: 8%">Propensity</th>
					<th style="width: 21%; text-align: left">Notes</th>
					<th style="width: 5%"></th>
				</tr>
			</thead>
			<tbody>
				{foreach name="todays_meeting_loop" from=$todays_meetings item=result}
					<tr style="vertical-align: top">
						<td>{$smarty.foreach.todays_meeting_loop.iteration}</td>
						<td>{$result.id}</td>
						<td>{$result.client}</td>
						<td>
							<span id="client_{$result.id}">{$result.company_name}</span>
							<br />
							{assign var="website" value=$result.website}
							{if $website != ""}
								<a href="{$website}" target="_new">{$website}</a>
							{/if}
						</td>
						<td>{$result.job_title}</td>
						<td>{$result.full_name}</td>
						<td>{$result.date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
			 			<td style="text-align: center">
			 				<span style="display: none">{$result.propensity}</span>
			 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
			 			</td>
						<td>{$result.notes}</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>&nbsp;
							<a href="index.php?cmd=MeetingPrint&amp;id={$result.id}" target="_blank" title="Print meeting" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		{/if}
{*
		<hr style="width: 99%" />

		<p style="margin-left: 10px">
			Meeting Status
			<select id="meeting_status_id" name="meeting_status_id" onchange="loadMeetingStatus(); return false;">
				{html_options options=$meeting_statuses selected=$meeting_status_id}
			</select>
			<img id="ajax_loader" style="display: none" src="{$APP_URL}app/view/images/ajax_loader.gif" alt="Loading" />
		</p>
		
		<div id="meeting_status">
			<table id="table2" class="adminlist sortable" id="sortable2_{$result.id}" cellspacing="1">
				{if $meetings}
					<thead>
						<tr>
							<th style="width: 1%; text-align: center">#</th>
							<th style="width: 1%; text-align: center">ID</th>
							<th style="width: 14%; text-align: left">Client</th>
							<th style="width: 13%; text-align: left">Company</th>
							<th style="width: 14%; text-align: left">Job Title</th>
							<th style="width: 13%; text-align: left">Post Holder</th>
							<th style="width: 10% ;text-align: left">Date &amp; Time</th>
							<th style="width: 8%">Propensity</th>
							<th style="width: 21%; text-align: left">Notes</th>
							<th style="width: 5%"></th>
						</tr>
					</thead>
					<tbody>
						{foreach name="meeting_loop" from=$meetings item=result}
							<tr style="vertical-align: top">
								<td>{$smarty.foreach.meeting_loop.iteration}</td>
								<td>{$result.id}</td>
								<td>{$result.client}</td>
								<td>
									<span id="client_{$result.id}">{$result.company_name}</span>
									<br />
									{assign var="website" value=$result.website}
									{if $website != ""}
										<a href="{$website}" target="_new">{$website}</a>
									{/if}
								</td>
								<td>{$result.job_title}</td>
								<td>{$result.full_name}</td>
								<td>{$result.date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
					 			<td style="text-align: center">
					 				<span style="display: none">{$result.propensity}</span>
					 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
					 			</td>
								<td>{$result.notes}</td>
								<td style="text-align: center; background-color: #F3F3F3">
									<a id="detailsBtn2_{$meeting.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>&nbsp;
									<a href="index.php?cmd=MeetingPrint&amp;id={$result.id}" target="_blank" title="Print meeting" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a>
								</td>
							</tr>
						{/foreach}
					</tbody>
					{else}
					<tr>
						<td style="text-align: center"><em>&lt;&mdash; No meetings found &mdash;&gt;</em></td>
					</tr>
				{/if}
			</table>
		</div>
*}
	</div>
<div>

<script type="text/javascript">init_moofx();</script>

{include file="footer.tpl"}