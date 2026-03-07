{include file="header.tpl" title="Dashboard - Information Requests"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

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
	<h3><span>Information Requests</span></h3>
	<div>
		
		<p style="margin-left: 10px">You have <strong>{$information_request_count}</strong> information request{if $information_request_count != 1}s{/if} due today</p>
		
		{if $timed_information_requests || $other_information_requests}
		<table id="table1" class="adminlist sortable" id="sortable_{$result.id}"cellspacing="1">
			<thead>
				<tr class="sortable" id="sortable_{$result.id}">
					<th style="width: 1%; text-align: center">#</th>
					<th style="width: 1%; text-align: center">ID</th>
					<th style="text-align: left">Company</th>
					<th style="text-align: left">Job Title</th>
					<th style="text-align: left">Post Holder</th>
					<th style="text-align: left">Date &amp; Time</th>
					<th>Propensity</th>
					<th style="text-align: left">Notes</th>
					<th style="width: 5%"></th>
				</tr>
			</thead>
			<tbody>
				{assign var=timed_information_request_count value=$timed_information_requests|@count}
				{foreach name="timed_information_request_loop" from=$timed_information_requests item=result}
					<tr id="tr_post_{$result.post_id}" style="vertical-align:top">
						<td>{$smarty.foreach.timed_information_request_loop.iteration}</td>
						<td>{$result.id}</td>
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
						<td>
							{*$result.date|date_format:$smarty.config.FORMAT_DATETIME_SHORT*}
							{$result.date}
						</td>
			 			<td style="text-align: center">
			 				<span style="display: none">{$result.propensity}</span>
			 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
			 			</td>
						<td>{$result.notes}</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
						</td>
					</tr>
				{/foreach}
				{foreach name="other_information_request_loop" from=$other_information_requests item=result}
					<tr id="tr_post_{$result.post_id}" style="vertical-align:top">
						<td>{$timed_information_request_count+$smarty.foreach.other_information_request_loop.iteration}</td>
						<td>{$result.id}</td>
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
						<td>
							{*$result.date|date_format:$smarty.config.FORMAT_DATE_SHORT*}
							{$result.date}
						</td>
			 			<td style="text-align: center">
			 				<span style="display: none">{$result.propensity}</span>
			 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
			 			</td>
						<td>{$result.notes}</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		{/if}
		
	</div>
</div>

<script type="text/javascript">init_moofx();</script>

{include file="footer.tpl"}