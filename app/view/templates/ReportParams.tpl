{include file="header2.tpl" title="Report Params"}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script>

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script>
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">

<script type="text/javascript" src="{$APP_URL}app/view/js/date/date.js"></script>

<script language="JavaScript" type="text/javascript">
{literal}

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;


/* --- Ajax calling functions --- */
function getClientReportFilters()
{

	// set the current data entry fields to blank
//	$('div_field_data').hide();
	//alert($('div_field_data').style.display);

	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = '0';
	//set the field/value pairs - eg telephone/0121....
	ill_params.client_id = $F('client_id');

	getAjaxData("AjaxFilterBuilder", "", "get_client_report_filters", ill_params, "Saving...")
}

/* --- Ajax return data handlers --- */
function AjaxFilterBuilder(data)
{
	for (i = 1; i < data.length + 1; i++)
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "get_client_report_filters":
				makeSelectOptions("filter_id", t.filter_list);
				break;
		}
	}
}

function makeSelectOptions(dataElement, optionData)
{
	var nodeList = $(dataElement);
	//empty the existing options
	nodeList.options.length=0;

	for (i = 0; i < optionData.length + 1; i++)
	{
		t = optionData[i];
		nodeList.options[i]=new Option(t.text, t.value, true, false);
	}
}

// NBMs to exlcude from reports by defualt
// Import Process 1
// Dave 21
// Jim 20
// Amanda 28
// Rob 41
// Phil 24
// Ian 16
// Jas 59
var default_nbm_exclusions = new Array(1, 21, 20, 28, 41, 24, 16, 59);

var default_status_exclusions = [8];

function clearDate(obj)
{
	$(obj).value = null;
}

function clearSelect(obj)
{
	for (var i = 0; i < $(obj).options.length; i++)
	{
		$(obj).options[i].selected = false;
	}
}

function selectAll(obj)
{
	for (var i = 0; i < $(obj).options.length; i++)
	{
		$(obj).options[i].selected = true;
	}
}

// Checks whether a string s is empty
function isEmpty(s)
{
	re = /^\s*$/;
	if ( s.length == 0 || s == null || re.test(s) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

function validate()
{
	var report_id = $F('report_id');

	// validation error variables
	var msg_error = "";
	var msg_error_count = 0;

	switch (report_id)
	{
		case '1':
			break;

		case '2':
			break;

		case '3':
			break;

		case '4':
			break;

		case '5':
			var date_from    = formatYear($F('date_from'));
			var date_to      = formatYear($F('date_to'));
			var client_id = $F('client_id');
			if (isEmpty(client_id) || client_id < 1)
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Client must be selected\n";
			}
			break;

		case '7':
			if ($F('date_from').replace(/^\s+|\s+$/g, '') == '')
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Date From must be selected\n";
			}
			if ($F('date_to').replace(/^\s+|\s+$/g, '') == '')
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Date To must be selected\n";
			}
			var client_id = $F('client_id');
			if (isEmpty(client_id) || client_id < 1)
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Client must be selected\n";
			}

			break;
		case '8':
			if ($F('date_from').replace(/^\s+|\s+$/g, '') == '')
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Date From must be selected\n";
			}
			if ($F('date_to').replace(/^\s+|\s+$/g, '') == '')
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Date To must be selected\n";
			}
			var client_id = $F('client_id');
			if (isEmpty(client_id) || client_id < 1)
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Client must be selected\n";
			}
//			var sort_by = $F('sort_by');
//			if (isEmpty(sort_by) || sort_by < 1)
//			{
//				msg_error_count ++;
//				msg_error += msg_error_count + ". Sort By must be selected\n";
//			}
			var front_page_statuses = $F('front_page_statuses');
			if (isEmpty(front_page_statuses) || front_page_statuses < 1)
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Display Statuses on First Page must be selected\n";
			}
			break;
		case '9':
			break;
		case '10':
		case '11':
		case '12':

		default:

        case '15':
            var client_id = $F('client_id');
            if (isEmpty(client_id) || client_id < 1)
            {
                msg_error_count ++;
                msg_error += msg_error_count + ". Client must be selected\n";
            }
            break;
	}

	if (msg_error != "")
	{
		alert("Please complete or correct the following items:\n\n" + msg_error);
		return false;
	}
	else
	{
		return true;
	}
}


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

function handleSelect(input, type, args, obj)
{
	var dates = args[0];
	var date = dates[0];

	// Convert incoming params to string type so we can pad the day and month values later on in the function
	var year = date[0].toString(), month = date[1].toString(), day = date[2].toString();

	var txtDate1 = $(input);
	txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year;
	Effect.toggle($(input + '_calendar_display'), 'blind', {duration: 0.3});
}

function handleSelectDateFrom(type, args, obj)
{
	handleSelect('date_from', type, args, obj);
}

function handleSelectDateTo(type, args, obj)
{
	handleSelect('date_to', type, args, obj);
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateDateFrom()
{
	Effect.toggle($('date_from_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('date_from');

	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_date_from.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_date_from.render();
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateDateTo()
{
	Effect.toggle($('date_to_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('date_to');

	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_date_to.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_date_to.render();
}

function init()
{
	// Due date
	YAHOO.example.calendar.cal_date_from = new YAHOO.widget.Calendar("cal_date_from", "div_cal_date_from");
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_date_from.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_date_from.selectEvent.subscribe(handleSelectDateFrom, YAHOO.example.calendar.cal_date_from, true);
	YAHOO.example.calendar.cal_date_from.render();

	// Reminder date
	YAHOO.example.calendar.cal_date_to = new YAHOO.widget.Calendar("cal_date_to", "div_cal_date_to");
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_date_to.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_date_to.selectEvent.subscribe(handleSelectDateTo, YAHOO.example.calendar.cal_date_to, true);
	YAHOO.example.calendar.cal_date_to.render();
}

YAHOO.namespace("example.calendar");
YAHOO.util.Event.addListener(window, "load", init);



// The following variable used in validation. It will be set to true if we have a current meeting.
var has_current_meeting = false;

function submitbutton(pressbutton)
{
//	alert('submitbutton(' + pressbutton + ')');
//	var form = document.adminForm;
//	var type = form.type.value;

	if (pressbutton == 'save' && validate())
	{
		submitform(pressbutton);
	}
	else if (pressbutton == 'reset')
	{
		document.adminForm.reset();
	}
	return;
}

function submitform(pressbutton)
{
	var report_id = $F('report_id');

	switch (report_id)
	{
		case '1':
			var year                 = $F('Date_Year');
			var month                = $F('Date_Month');
			var year_month           = year + month;
			var nbm_id               = $F('nbm_id');
			var include_zero_targets = $F('include_zero_targets');
			var nbm_exclusions       = $F('nbm_exclusions');
			var loc = 'index.php?cmd=Report1&year_month=' + year_month + '&nbm_id=' + nbm_id;
			if (include_zero_targets)
			{
				loc += '&include_zero_targets=1';
			}
			if (nbm_exclusions.length > 0)
			{
				loc += '&nbm_exclusions=' + nbm_exclusions;
			}
			break;

		case '2':
			var date_from  = formatYear($F('date_from'));
			var date_to    = formatYear($F('date_to'));
			var nbm_exclusions = $F('nbm_exclusions');
			if (date_to.length > 0 && date_from.length > 0)
			{
				var loc = 'index.php?cmd=Report2&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report2';
			}
			if (nbm_exclusions.length > 0)
			{
				loc += '&nbm_exclusions=' + nbm_exclusions;
			}
			break;

		case '3':
			var year       = $F('Date_Year');
			var month      = $F('Date_Month');
			var year_month = year + month;
			var nbm_id     = $F('nbm_id');
			var loc        = 'index.php?cmd=Report3&year_month=' + year_month + '&nbm_id=' + nbm_id;
			break;

		case '4':
			var date_from = formatYear($F('date_from'));
			var date_to   = formatYear($F('date_to'));
			var team_id   = $F('team_id');
			var nbm_id    = $F('nbm_id');

			if (date_from.length > 0 && date_to.length > 0)
			{
				var loc = 'index.php?cmd=Report4&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report4';
			}

			if (team_id && team_id > 0)
			{
				loc += '&team_id=' + team_id;
			}

			if (nbm_id && nbm_id > 0)
			{
				loc += '&nbm_id=' + nbm_id;
			}

			break;

		case '5':
			var date_from       = formatYear($F('date_from'));
			var date_to         = formatYear($F('date_to'));
			var client_id       = $F('client_id');
			var project_ref     = $F('project_ref');
			var effectives      = $F('effectives');
			var summary_figures = $F('summary_figures');
			var all_statuses    = $F('all_statuses');
			var full_history    = $F('full_history');
			if (date_from.length > 0 && date_to.length > 0)
			{
				var loc = 'index.php?cmd=Report5&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report5';
			}
			if (client_id && client_id > 0)
			{
				loc += '&client_id=' + client_id;
			}
			if (project_ref && project_ref != '')
			{
				loc += '&project_ref=' + project_ref;
			}
			if (effectives && effectives > 0)
			{
				loc += '&effectives=' + effectives;
			}
			if (summary_figures)
			{
				loc += '&summary_figures=1';
			}
			if (all_statuses)
			{
				loc += '&all_statuses=1';
			}
			if (full_history)
			{
				loc += '&full_history=1';
			}
			break;

		case '6':
			var date_from         = formatYear($F('date_from'));
			var date_to           = formatYear($F('date_to'));
			var client_id         = $F('client_id');
			var order_by          = $F('order_by');
			var imperative_target = $F('imperative_target');
			if (date_to.length > 0 && date_from.length > 0)
			{
				var loc = 'index.php?cmd=Report6&order_by=' + order_by + '&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report6&order_by=' + order_by;
			}
			if (imperative_target)
			{
				loc += '&include_imperative_target=1';
			}
			else
			{
				loc += '&include_imperative_target=0';
			}
			if (client_id && client_id > 0)
			{
				loc += '&client_id=' + client_id;
			}
			break;

        case '7':
            var date_from         = formatYear($F('date_from'));
            var date_to           = formatYear($F('date_to'));
            var client_id         = $F('client_id');
            if (date_from.length > 0 && date_to.length > 0)
            {
                var loc = 'index.php?cmd=Report7&start=' + date_from + '&end=' + date_to;
            }
            else
            {
                var loc = 'index.php?cmd=Report7';
            }
            if (client_id && client_id > 0)
            {
                loc += '&client_id=' + client_id;
            }
            //if ($F('client_fact_summary')) loc += '&client_fact_summary=1';
            if ($F('campaign_statistics')) loc += '&campaign_statistics=1';
            if ($F('nbm_statistics')) loc += '&nbm_statistics=1';
            if ($F('meetings_set_summary')) loc += '&meetings_set_summary=1';
            if ($F('cancellation_clinic')) loc += '&cancellation_clinic=1';
            //if ($F('opportunities_and_wins_clinic')) loc += '&opportunities_and_wins_clinic=1';
            //if ($F('targeting_clinic')) loc += '&targeting_clinic=1';
            if ($F('database_analysis')) loc += '&database_analysis=1';
            if ($F('effectives_analysis')) loc += '&effectives_analysis=1';
            if ($F('nbm_discipline_effectiveness')) loc += '&nbm_discipline_effectiveness=1';
            if ($F('nbm_industry_effectiveness')) loc += '&nbm_industry_effectiveness=1';
            if ($F('pipeline_report')) loc += '&pipeline_report=1';
            //if ($F('effective_notes')) loc += '&effective_notes=1';
            break;

          case '8':
          	var date_from       = formatYear($F('date_from'));
			var date_to         = formatYear($F('date_to'));
			var client_id       = $F('client_id');
			var filter_id       = $F('filter_id');
//			var communication_status     = $F('communication_status');
//			var effectives      = $F('effectives');
//			var summary_figures = $F('summary_figures');
//			var all_statuses    = $F('all_statuses');
//			var full_history    = $F('full_history');
			if (date_from.length > 0 && date_to.length > 0)
			{
				var loc = 'index.php?cmd=Report8&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report8';
			}

			if (client_id && client_id > 0)
			{
				loc += '&client_id=' + client_id;
			}

			if (filter_id) {
				loc += '&filter_id=' + filter_id;
			}

			var front_page_statuses = $F('front_page_statuses');
			if (front_page_statuses && front_page_statuses > 0)
			{
				loc += '&front_page_statuses=' + front_page_statuses;
			}
			if ($F('front_page_figures'))
				loc += '&front_page_figures=1';
			else
				loc += '&front_page_figures=0';

			if ($F('summary_figures'))
				loc += '&summary_figures=1';
			else
				loc += '&summary_figures=0';

			break;

          case '9':
  			var date_from  = formatYear($F('date_from'));
  			var date_to    = formatYear($F('date_to'));
  			var nbm_exclusions = $F('nbm_exclusions');
  			if (date_to.length > 0 && date_from.length > 0)
  			{
  				var loc = 'index.php?cmd=Report9&start=' + date_from + '&end=' + date_to;
  			}
  			else
  			{
  				var loc = 'index.php?cmd=Report9';
  			}
  			if (nbm_exclusions.length > 0)
  			{
  				loc += '&nbm_exclusions=' + nbm_exclusions;
  			}
  			break;
          case '10':
			var date_from  = formatYear($F('date_from'));
			var date_to    = formatYear($F('date_to'));
			if (date_to.length > 0 && date_from.length > 0)
			{
				var loc = 'index.php?cmd=Report10&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report10';
			}
			break;
          case '11':
  			var date_from  = formatYear($F('date_from'));
  			var date_to    = formatYear($F('date_to'));
  			if (date_to.length > 0 && date_from.length > 0)
  			{
  				var loc = 'index.php?cmd=Report11&start=' + date_from + '&end=' + date_to;
  			}
  			else
  			{
  				var loc = 'index.php?cmd=Report11';
  			}
  			break;
          case '12':
			var date_from  = formatYear($F('date_from'));
			var date_to    = formatYear($F('date_to'));
			if (date_to.length > 0 && date_from.length > 0)
			{
				var loc = 'index.php?cmd=Report12&start=' + date_from + '&end=' + date_to;
			}
			else
			{
				var loc = 'index.php?cmd=Report12';
			}
			break;
          case '13':
          	var year                 = $F('Date_Year');
  			var nbm_exclusions       = $F('nbm_exclusions');
  			var loc = 'index.php?cmd=Report13&year=' + year;
  			if (nbm_exclusions.length > 0)
  			{
  				loc += '&nbm_exclusions=' + nbm_exclusions;
  			}
  			break;
          case '14':
        	var year                 = $F('Date_Year');
        	var loc = 'index.php?cmd=Report14&year=' + year;
        	{/literal}
        	{if $user->hasPermission('permission_admin_reports')}
        	{literal}
        	var nbm_exclusions       = $F('nbm_exclusions');
  			if (nbm_exclusions.length > 0)
  			{
  				loc += '&nbm_exclusions=' + nbm_exclusions;
  			}
  			{/literal}
  			{/if}
  			{literal}
  			break;

          case '15':
          	var client_id       = $F('client_id');
          	var loc = 'index.php?cmd=Report15';
          	if (client_id && client_id > 0)
			{
				loc += '&client_id=' + client_id;
			}
          	break;
	}
//	alert(loc);
	launchReport(loc);
}

function formatYear(str)
{
	var day   = str.substr(0, 2);
	var month = str.substr(3, 2);
	var year  = str.substr(6, 4);
	return year + '-' + month + '-' + day;
}

/**
 * Launches a pop-up window in which to display the report.
 * @param string the report to be loaded in the pop-up window
 */
function launchReport(source)
{
	//alert(source);
	reportWindow = window.open(source, "", "width=720,height=600,resizable=yes,toolbar=no,scrollbars=yes");
	reportWindow.focus();
	if (window.event) window.event.cancelBubble = true;
}

function selectDefaultNbmExclusions(obj)
{
	for (var i = 0; i < $(obj).options.length; i++)
	{
		if (in_array($(obj).options[i].value, default_nbm_exclusions))
		{
			$(obj).options[i].selected = true;
		}
		else
		{
			$(obj).options[i].selected = false;
		}
	}
}

function removeDefaultStatusExclusions(obj)
{
	for (var i = 0; i < $(obj).options.length; i++)
	{
		default_status_exclusions.each(function(item){
			if (item == $(obj).options[i].value)
			{
				$(obj).options[i].remove();
			}
		});
	}
}

// http://kevin.vanzonneveld.net
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
// *     returns 1: true
function in_array(needle, haystack, strict)
{
	var found = false, key, strict = !!strict;
	for (key in haystack)
	{
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle))
		{
			found = true;
			break;
		}
	}
	return found;
}

{/literal}
</script>


<form name="form1">
	<input type="hidden" id="report_id" name="report_id" value="{$report_id}" />
<table class="adminform">
	<tr>
		<td width="100%" valign="top">

			<table id="tbl_characteristic_list" class="adminlist">
				<tr>
					<th>Report</th>
					<td style="vertical-align: middle">{$report}</td>
				</tr>

				{if $report_id == 1}

					<tr>
						<th>Start Month</th>
						<td>
							{html_select_date display_days=false start_year=2007}
						</td>
					</tr>
					<tr>
						<th>NBM</th>
						<td>
							<select id="nbm_id" name="nbm_id">
								{html_options options=$users}
							</select>
						</td>
					</tr>
					<tr>
						<th>Include Zero Target Rows</th>
						<td>
							<input type="checkbox" id="include_zero_targets" name="include_zero_targets" />
						</td>
					</tr>
					<tr>
						<th>NBM Exclusions</th>
						<td>
							<select name="nbm_exclusions[]" id="nbm_exclusions" style="width: 175px" multiple="multiple" size="{if $users|@count > 20}20{else}{$users|@count}{/if}">
								{html_options options=$users}
							</select>
							<a href="#" onclick="javascript:selectDefaultNbmExclusions('nbm_exclusions');">[default]</a>
							<a href="#" onclick="javascript:selectAll('nbm_exclusions');">[select all]</a>
							<a href="#" onclick="javascript:clearSelect('nbm_exclusions');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								selectDefaultNbmExclusions('nbm_exclusions');
							</script>
						</td>
					</tr>

				{elseif $report_id == 2}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>NBM Exclusions</th>
						<td>
							<select name="nbm_exclusions[]" id="nbm_exclusions" style="width: 175px" multiple="multiple" size="{if $users|@count > 20}20{else}{$users|@count}{/if}">
								{html_options options=$users}
							</select>
							<a href="#" onclick="javascript:selectDefaultNbmExclusions('nbm_exclusions');">[default]</a>
							<a href="#" onclick="javascript:selectAll('nbm_exclusions');">[select all]</a>
							<a href="#" onclick="javascript:clearSelect('nbm_exclusions');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								selectDefaultNbmExclusions('nbm_exclusions');
							</script>
						</td>
					</tr>

				{elseif $report_id == 3}

					<tr>
						<th>Start Month</th>
						<td>
							{html_select_date display_days=false start_year=2007}
						</td>
					</tr>
					<tr>
						<th>NBM</th>
						<td>
							<select id="nbm_id" name="nbm_id">
								{html_options options=$users}
							</select>
						</td>
					</tr>

				{elseif $report_id == 4}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Team</th>
						<td>
							<select id="team_id" name="team_id">
								<option value="0">-- select --</option>
								{html_options options=$teams}
							</select>
						</td>
					</tr>
					<tr>
						<th>NBM</th>
						<td>
							<select id="nbm_id" name="nbm_id">
								{html_options options=$users}
							</select>
						</td>
					</tr>

				{elseif $report_id == 5}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Client</th>
						<td>
							<select id="client_id" name="client_id">
								<option value="0">-- select --</option>
								{html_options options=$clients}
							</select>
						</td>
					</tr>
					<tr>
						<th>Project Ref</th>
						<td>
							<input type="text" id="project_ref" name="project_ref" />
						</td>
					</tr>
					<tr>
						<th>Effectives/Non-effectives</th>
						<td>
							<select id="effectives" name="effectives">
								<option value="1">Effectives only</option>
								<option value="2">Non-effectives only</option>
								<option value="3">Effectives and Non-effectives</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Include Summary Figures</th>
						<td>
							<input type="checkbox" id="summary_figures" name="summary_figures" />
						</td>
					</tr>
					<tr>
						<th>Show All Statuses on First Page</th>
						<td>
							<input type="checkbox" id="all_statuses" name="all_statuses" />
						</td>
					</tr>
					<tr>
						<th>Include Full Notes History</th>
						<td>
							<input type="checkbox" id="full_history" name="full_history" checked="checked" />
						</td>
					</tr>

				{elseif $report_id == 6}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Client</th>
						<td>
							<select id="client_id" name="client_id">
								<option value="0">-- select --</option>
								{html_options options=$clients}
							</select>
						</td>
					</tr>
					<tr>
						<th>Order By</th>
						<td>
							<select id="order_by" name="order_by">
								<option value="0">Client Name</option>
								<option value="1">Status</option>
								<option value="2">Campaign Owner</option>
								<option value="3">Campaign Month</option>
								<option value="5">Campaign Meets Set +/-</option>
								<option value="6">Campaign Meets Att +/-</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Include Imperative Target For Period</th>
						<td>
							<input type="checkbox" id="imperative_target" name="imperative_target" checked="checked" />
						</td>
					</tr>

                {elseif $report_id == 7}

                    <tr>
                        <th>Date From</th>
                        <td>
                            <input type="text" id="date_from" name="date_from" value="{$date_from}" />
                            <input type="button" value="..." onclick="javascript:updateDateFrom();" />
                            <a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
                            <div id="date_from_calendar_display" style="display: none">
                                <div id="div_cal_date_from"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Date To</th>
                        <td>
                            <input type="text" id="date_to" name="date_to" value="{$date_to}" />
                            <input type="button" value="..." onclick="javascript:updateDateTo();" />
                            <a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
                            <div id="date_to_calendar_display" style="display: none">
                                <div id="div_cal_date_to"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td>
                            <select id="client_id" name="client_id">
                                <option value="0">-- select --</option>
                                {html_options options=$clients}
                            </select>
                        </td>
                    </tr>
                    <!--<tr>
                        <th>Client Fact Summary</th>
                        <td>
                            <input type="checkbox" id="client_fact_summary" name="client_fact_summary"  />
                        </td>
                    </tr>-->
                    <tr>
                        <th>Campaign Statistics</th>
                        <td>
                            <input type="checkbox" id="campaign_statistics" name="campaign_statistics"  checked="checked"/>
                        </td>
                    </tr>
                    <tr>
                        <th>NBM Statistics</th>
                        <td>
                            <input type="checkbox" id="nbm_statistics" name="nbm_statistics" checked="checked"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Meetings Set Summary</th>
                        <td>
                            <input type="checkbox" id="meetings_set_summary" name="meetings_set_summary" checked="checked" />
                        </td>
                    </tr>
                    <tr>
                        <th>Cancellation Clinic</th>
                        <td>
                            <input type="checkbox" id="cancellation_clinic" name="cancellation_clinic"  />
                        </td>
                    </tr>
                    <tr>
                        <th>Opportunities and Wins Clinic</th>
                        <td>
                            <input type="checkbox" id="opportunities_and_wins_clinic" name="opportunities_and_wins_clinic" disabled  />
                        </td>
                    </tr>
                    <tr>
                        <th>Targeting Clinic</th>
                        <td>
                            <input type="checkbox" id="targeting_clinic" name="targeting_clinic" disabled  />
                        </td>
                    </tr>
                    <tr>
                        <th>Database Analysis</th>
                        <td>
                            <input type="checkbox" id="database_analysis" name="database_analysis"   />
                        </td>
                    </tr>
                    <tr>
                        <th>Effectives Analysis</th>
                        <td>
                            <input type="checkbox" id="effectives_analysis" name="effectives_analysis"   />
                        </td>
                    </tr>
                    <tr>
                        <th>NBM Discipline Effectiveness</th>
                        <td>
                            <input type="checkbox" id="nbm_discipline_effectiveness" name="nbm_discipline_effectiveness"  />
                        </td>
                    </tr>
                    <tr>
                        <th>NBM Industry Effectiveness</th>
                        <td>
                            <input type="checkbox" id="nbm_industry_effectiveness" name="nbm_industry_effectiveness"   />
                        </td>
                    </tr>
                    <tr>
                        <th>Pipeline Report</th>
                        <td>
                            <input type="checkbox" id="pipeline_report" name="pipeline_report"   />
                        </td>
                    </tr>
                    <tr>
                        <th>Effective Notes</th>
                        <td>
                            <input type="checkbox" id="effective_notes" name="effective_notes" disabled/>
                        </td>
                    </tr>
				 {elseif $report_id == 8}

                   <tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Client</th>
						<td>
							<select id="client_id" name="client_id" onchange="javascript:getClientReportFilters();">
								{html_options options=$clients}
							</select>
						</td>
					</tr>
					<tr>
						<th>Filters</th>
						<td>
							<select id="filter_id" name="filter_id">
								<option value="0">-- select --</option>
							</select>
						</td>
					</tr>
					{*<tr>
						<th>Project Ref</th>
						<td>
							<input type="text" id="project_ref" name="project_ref" />
						</td>
					</tr>
					*}
					{*<tr>
						<th>Effectives/Non-effectives</th>
						<td>
							<select id="effectives" name="effectives">
								<option value="1">Effectives only</option>
								<option value="2">Non-effectives only</option>
								<option value="3">Effectives and Non-effectives</option>
							</select>
						</td>
					</tr>
						<tr>
						<th>Post Initiative Status</th>
						<td>
							<select name="communication_status[]" id="communication_status" style="width: 300px" multiple="multiple" size="10">
								{html_options options=$status}
							</select>
							<br />
							<a href="#" onclick="javascript:selectAll('communication_status');">[select all]</a>
							&nbsp;|&nbsp;
							<a href="#" onclick="javascript:clearSelect('communication_status');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								removeDefaultStatusExclusions('communication_status');
							</script>
						</td>
					</tr>
					<tr>
						<th>Sort By</th>
						<td>
							<select id="sort_by" name="sort_by">
								<option value="0">-- select --</option>
								<option value="1">Post Initiative Status</option>
								<option value="2">Company Name</option>
								<option value="3">Sector</option>
							</select>
						</td>
					</tr>*}
					<tr>
						<th>Display Statuses on First Page</th>
						<td>
							<select id="front_page_statuses" name="front_page_statuses">
								<option value="0">-- select --</option>
								<option value="1">Display All</option>
								<option value="2">Display Only Those With a Communication</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Display Status Figures on First Page</th>
						<td>
							<input type="checkbox" id="front_page_figures" name="front_page_figures"/>
						</td>
					</tr>
					<tr>
						<th>Display Summary Section</th>
						<td>
							<input type="checkbox" id="summary_figures" name="summary_figures" checked="checked"/>
						</td>
					</tr>

					{*<tr>
						<th>Display Top Level Category</th>
						<td>
							<input type="checkbox" id="top_level_category" name="top_level_category" checked="checked"/>
						</td>
					</tr>
					<tr>
						<th>Display Recall Date</th>
						<td>
							<input type="checkbox" id="recall_date" name="recall_date"/>
						</td>
					</tr>*}

					{elseif $report_id == 9}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>NBM Exclusions</th>
						<td>
							<select name="nbm_exclusions[]" id="nbm_exclusions" style="width: 175px" multiple="multiple" size="{if $users|@count > 20}20{else}{$users|@count}{/if}">
								{html_options options=$users}
							</select>
							<a href="#" onclick="javascript:selectDefaultNbmExclusions('nbm_exclusions');">[default]</a>
							<a href="#" onclick="javascript:selectAll('nbm_exclusions');">[select all]</a>
							<a href="#" onclick="javascript:clearSelect('nbm_exclusions');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								selectDefaultNbmExclusions('nbm_exclusions');
							</script>
						</td>
					</tr>

					{elseif $report_id == 10}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>

					{elseif $report_id == 11}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>

					{elseif $report_id == 12}

					<tr>
						<th>Date From</th>
						<td>
							<input type="text" id="date_from" name="date_from" value="{$date_from}" />
							<input type="button" value="..." onclick="javascript:updateDateFrom();" />
							<a href="#" onclick="javascript:clearDate('date_from');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_from_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_from_calendar_display" style="display: none">
								<div id="div_cal_date_from"></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Date To</th>
						<td>
							<input type="text" id="date_to" name="date_to" value="{$date_to}" />
							<input type="button" value="..." onclick="javascript:updateDateTo();" />
							<a href="#" onclick="javascript:clearDate('date_to');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('date_to_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="date_to_calendar_display" style="display: none">
								<div id="div_cal_date_to"></div>
							</div>
						</td>
					</tr>

					{elseif $report_id == 13}

					<tr>
						<th>Year</th>
						<td>
							{html_select_date display_days=false display_months=false start_year=2007}
						</td>
					</tr>
					<tr>
						<th>NBM Exclusions</th>
						<td>
							<select name="nbm_exclusions[]" id="nbm_exclusions" style="width: 175px" multiple="multiple" size="{if $users|@count > 20}20{else}{$users|@count}{/if}">
								{html_options options=$users}
							</select>
							<a href="#" onclick="javascript:selectDefaultNbmExclusions('nbm_exclusions');">[default]</a>
							<a href="#" onclick="javascript:selectAll('nbm_exclusions');">[select all]</a>
							<a href="#" onclick="javascript:clearSelect('nbm_exclusions');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								selectDefaultNbmExclusions('nbm_exclusions');
							</script>
						</td>
					</tr>

					{elseif $report_id == 14}

					<tr>
						<th>Year</th>
						<td>
							{html_select_date display_days=false display_months=false start_year=2007}
						</td>
					</tr>
					{if $user->hasPermission('permission_admin_reports')}
					<tr>
						<th>NBM Exclusions</th>
						<td>
							<select name="nbm_exclusions[]" id="nbm_exclusions" style="width: 175px" multiple="multiple" size="{if $users|@count > 20}20{else}{$users|@count}{/if}">
								{html_options options=$users}
							</select>
							<a href="#" onclick="javascript:selectDefaultNbmExclusions('nbm_exclusions');">[default]</a>
							<a href="#" onclick="javascript:selectAll('nbm_exclusions');">[select all]</a>
							<a href="#" onclick="javascript:clearSelect('nbm_exclusions');">[clear]</a>
							<script language="JavaScript" type="text/javascript">
								selectDefaultNbmExclusions('nbm_exclusions');
							</script>
						</td>
					</tr>
					{/if}

					{elseif $report_id == 15}

					<tr>
						<th>Client</th>
						<td>
							<select id="client_id" name="client_id">
								<option value="0">-- select --</option>
								{html_options options=$clients}
							</select>
						</td>
					</tr>

                {/if}
			</table>
			<p><input type="button" value="&nbsp;Run&nbsp;" onclick="javascript:submitbutton('save')" /></p>
		</td>
	</tr>
</table>
</form>

{include file="footer.tpl"}