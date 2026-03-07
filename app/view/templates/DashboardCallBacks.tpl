{include file="header.tpl" title="Dashboard - Call Backs"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script language="JavaScript" type="text/javascript">
{literal}

	// Maintain global tab collection (tab_colln)
	// If this page has been loaded then we don't want to reload it when the tab is clicked
	if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(1))
	{
		top.parent.tab_colln.add(1);
	}
	
	function showPost(company_id, post_id, initiative_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
iframeLocation(		top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id);
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


{/literal}
{*
//--- Calendar functions

// This var holds the uniqure suffix id of the calendar controls to pass between function calls.
// NOTE: much easier to this way that to rework js that control yahoo.calendar controls
var global_calendar_id = null;

// function below pads a string
// Usage: 	l = length to pad to
//			s = string to pad with
//			t = where padding occurs (0 = in front, 1 = behind, 2 = both)
String.prototype.pad = function(l, s, t)
{
	return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
		+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
		+ this + s.substr(0, l - t) : this;
};

//--- Recall calendar functions ---
// Handles input from recall calendar - updates recall_date text field
function handleSelect(type, args, obj) 
{ 
	var dates = args[0];
	var date = dates[0];
	
	// Convert incoming params to string type so we can pad the day and month values later on in the function
	var year = date[0].toString(), month = date[1].toString(), day = date[2].toString();
	
	var txtDate1 = $('next_communication_date_' + global_calendar_id);
	txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year;
	Effect.toggle($('next_communication_calendar_display' + global_calendar_id), 'blind', {duration: 0.3});
}

function handleSelectNextCommunicationDate(type, args, obj)
{
	handleSelect(type, args, obj);
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateDueDate(id) 
{ 
	global_calendar_id = id;
	Effect.toggle($('next_communication_date_calendar_display_' + id), 'blind', {duration: 0.3});
	var txtDate1 = $('next_communication_date_' + id);
	
	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		cal_obj_name = 'YAHOO.example.calendar.cal_next_communication_date_' + id;
		cal_obj = YAHOO.example.calendar[cal_obj_name];
		cal_obj.select(txtDate1.value);
	}
	cal_obj.render();
}


function init()
{
	// Next communication date calendars
	{foreach name="timed_call_back_loop" from=$timed_call_backs item=result}
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id} = new YAHOO.widget.Calendar("cal_next_communication_date_{result.post_initiative_id}", "div_cal_next_communication_date_{result.post_initiative_id}");
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.selectEvent.subscribe(handleSelectNextCommunicationDate, YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}, true);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.render();
	
	{/foreach}
	{foreach name="other_call_back_loop" from=$other_call_backs item=result}
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id} = new YAHOO.widget.Calendar("cal_next_communication_date_{result.post_initiative_id}", "div_cal_next_communication_date_{result.post_initiative_id}");
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.selectEvent.subscribe(handleSelectNextCommunicationDate, YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}, true);
	YAHOO.example.calendar.cal_next_communication_date_{result.post_initiative_id}.render();
	
	{/foreach}
}
*}
{literal}
YAHOO.namespace("example.calendar");
YAHOO.util.Event.addListener(window, "load", init);

{/literal}
</script>


<div class="panel">
	<h3><span>Call Backs</span></h3>
	<div>
	
		<p style="margin-left: 10px">You have <strong>{$call_back_count}</strong> call back{if $call_back_count != 1}s{/if} due today</p>
		
		{if $timed_call_backs || $other_call_backs}
		<table id="table1" class="adminlist sortable" id="sortable_{$result.id}"cellspacing="1">
			<thead>
				<tr class="sortable" id="sortable_{$result.id}">
					<th style="width: 1%; text-align: center">#</th>
					<th style="text-align: left">Company</th>
					<th style="text-align: left">Job Title</th>
					<th style="text-align: left">Post Holder</th>
					<th style="text-align: left">Date &amp; Time</th>
					<th>Client &amp Last Effective</th>
					<th style="text-align: left">Comments</th>
					<th style="width: 5%"></th>
				</tr>
			</thead>
			<tbody>
				{assign var=timed_call_back_count value=$timed_call_backs|@count}
				{foreach name="timed_call_back_loop" from=$timed_call_backs item=result}
					<tr id="tr_post_{$result.post_id}" style="vertical-align:top">
						<td>{$smarty.foreach.timed_call_back_loop.iteration}</td>
						<td>
							<span id="client_{$result.id}"><strong>{$result.company_name}</strong></span>
							<br />
							{$result.company_telephone}
							<br />
							{assign var="website" value=$result.website}
							{if $website != ""}
								<a href="{$website}" target="_new">{$website}</a>
							{/if}
						</td>
						<td><strong>{$result.job_title}</strong>
							<br />
							<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
							<br />
							{$result.telephone_1}
						</td>
						<td><strong>{$result.full_name}</strong></td>
						</td>
						<td>
							<strong>{$result.next_communication_date|date_format:$smarty.config.FORMAT_TIME_SHORT}</strong>
						</td>
			 			<td>
			 				<strong>{$result.client_name}</strong>
			 				<br />
							{$result.status}
							<br />
			 				<em>{$result.last_effective_communication_date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</em>
			 			</td>
						<td>{$result.comments}</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id}, {$result.initiative_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
						</td>
					</tr>
				{/foreach}
				{foreach name="other_call_back_loop" from=$other_call_backs item=result}
					<tr id="tr_post_{$result.post_id}" style="vertical-align:top">
						<td>{$timed_call_back_count+$smarty.foreach.other_call_back_loop.iteration}</td>
						<td>
							<span id="client_{$result.id}"><strong>{$result.company_name}</strong></span>
							<br />
							{$result.company_telephone}
							<br />
							{assign var="website" value=$result.website}
							{if $website != ""}
								<a href="{$website}" target="_new">{$website}</a>
							{/if}
							
						</td>
						<td><strong>{$result.job_title}</strong>
							<br />
							<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
							<br />
							{$result.telephone_1}
						</td>
						<td><strong>{$result.full_name}</strong></td>
						<td>
							&nbsp;
						</td>
			 			<td>
			 				<strong>{$result.client_name}</strong>
			 				<br />
							{$result.status}
							<br />
			 				<em>{$result.last_effective_communication_date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</em>
			 			</td>
						<td>{$result.comments}</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id}, {$result.initiative_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
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
