{include file="header2.tpl" title="Actions"}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script type="text/javascript" src="{$APP_URL}app/view/js/date/date.js"></script> 

<script language="JavaScript">

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

{literal}
function openInfoPane(src)
{
	//alert(src);

	if (parent.ifr_info == undefined)
	{
		alert("Going into popup");
		popupWindow(src);
	}
	else
	{
		//alert("Here1");
iframeLocation(		parent.ifr_info, src);
	}
		
}

function doDMStuff()
{
	//alert($("ote").style.visibility + $("dm").value);
	if ($("decision_maker_type_id").value == 1 && $("effective").checked == true)
	{
		$("row_ote").style.visibility = "visible";
	}
	else
	{
		$("row_ote").style.visibility = "collapse";
	}
	return false;
}

function doEffectiveShow()
{
	//alert($("ote").style.visibility + $("dm").value);
	if ($("effective").checked == true)
	{
		$("row_dm").style.visibility = "visible";
		if ($("row_last_dm"))
		{
			$("row_last_dm").style.visibility = "visible";
		}
		
		$("row_match").style.visibility = "visible";
		if ($("row_last_match"))
		{
			$("row_last_match").style.visibility = "visible";
		}
		
		$("row_receptiveness").style.visibility = "visible";
		if ($("row_last_receptiveness"))
		{
			$("row_last_receptiveness").style.visibility = "visible";
		}
		
		$("row_next_communication_date_agency_user").style.visibility = "visible";
	}
	else
	{
		$("row_dm").style.visibility = "collapse";
		if ($("row_last_dm"))
		{
			$("row_last_dm").style.visibility = "collapse";
		}
		$("row_match").style.visibility = "collapse";
		if ($("row_last_match"))
		{
			$("row_last_match").style.visibility = "collapse";
		}
		$("row_receptiveness").style.visibility = "collapse";
		if ($("row_last_receptiveness"))
		{
			$("row_last_receptiveness").style.visibility = "collapse";
		}
		$("row_next_communication_date_agency_user").style.visibility = "collapse";
	}
	return false;
}

function doStatusChange()
{
	if ($F("communication_status_id_select") >= 10)
	{
		$("row_meeting_date").style.visibility = "visible";
		$("row_meeting_time").style.visibility = "visible";
	}
	else
	{
		$("row_meeting_date").style.visibility = "collapse";
		$("row_meeting_time").style.visibility = "collapse";
	}
	
	if ($F("communication_status_id_select") > 15)
	{
		$("app_domain_Meeting_date").disabled = true;
		$("meeting_time_Hour").disabled = true;
		$("meeting_time_Minute").disabled = true;
		$("span_meeting_calendar_controls").style.display = "none";
	}
	else
	{
		$("app_domain_Meeting_date").disabled = false;
		$("meeting_time_Hour").disabled = false;
		$("meeting_time_Minute").disabled = false;
		$("span_meeting_calendar_controls").style.display = "";
	}
	
	
	if ($F("communication_status_id") == 10 && $F("communication_status_id_orig") != 10)
	{
		// Status changed to 'Meeting set'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 11 && $F("communication_status_id_orig") != 11)
	{
		// Status changed to 'Follow-up meeting set'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 14 && $F("communication_status_id_orig") != 14)
	{
		// Status changed to 'Meeting rearranged'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 15 && $F("communication_status_id_orig") != 15)
	{
		// Status changed to 'Follow-up meeting rearranged'
		$("span_meeting_details_checked").style.display = "block";
	}
	else
	{
		$("span_meeting_details_checked").style.display = "none";
	}
	
	return false;
}

function showDisciplines()
{
	//alert($("agency_user").value);
	if ($("agency_user").value == 1)
	{
		//$("show_agency_disciplines").style.display = "block";
		javascript:new Effect.BlindDown($('agency_disciplines_display'), {duration: 0.3})
	}
	else
	{
		//$("show_agency_disciplines").style.display = "none";
		javascript:new Effect.BlindUp($('agency_disciplines_display'), {duration: 0.3})
	}
	return false;
}

function clearDate(obj)
{
	$(obj).value = null;
}

function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.adminForm.task.value=pressbutton;
	
	try 
	{
		document.adminForm.onsubmit();
	}
	
	catch(e)
	{}
	
	document.adminForm.submit();
}


function submitbutton(pressbutton)
{
	if (pressbutton == 'save') 
	{
		if (validate())
		{
			// If any of the meeting date/time related form items exist but are hidden (eg when status changed to cancelled)
			// then need to re-enable them so that the data is submitted. Otherwise the meeting update will fail on the server
			if ($("app_domain_Meeting_date") != undefined)
				$("app_domain_Meeting_date").disabled = false;
			if ($("meeting_time_Hour") != undefined)
				$("meeting_time_Hour").disabled = false;
			if ($("meeting_time_Minute") != undefined)
				$("meeting_time_Minute").disabled = false;
				
			submitform( pressbutton );
			return;
		}
	}
}

function validate()
{
	// validation error variables
	var msg_error = "";
	var msg_error_count = 0;
	
	// validation warning variables
	var msg_warning = "";
	var msg_warning_count = 0;
	
	if ($("span_meeting_details_checked").style.display == "block" && !$F("meeting_details_checked"))
	{
		msg_error_count++;
		msg_error += msg_error_count + ". Please ensure you have checked the meeting details, addresses and timings.\n";
	}
	
	if ($F("communication_status_id") == -1)
	{
		msg_error_count ++;
		msg_error += msg_error_count + ". You must select a Status.\n";
	}
	
	// meeting set checks - new status of meeting set
	if ($F("communication_status_id_orig") != 10 && $F("communication_status_id") == 10)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but no meeting date or time specified.\n";
		}
		
		if (!$F("effective"))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but communication is not marked as 'Effective'.\n";
		}
		
		if ($F("notes") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Notes must be completed\n";
		}
	}
	
	// meeting set checks - existing status of meeting set
	if ($F("communication_status_id_orig") == 10 && $F("communication_status_id") == 10)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting set', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting set', but date/time of meeting has been changed. Should status be changed to 'Meeting rearranged'?\n";
		}
	}
	
	// follow-up meeting set checks - new status of follow-up meeting set
	if ($F("communication_status_id_orig") != 11 && $F("communication_status_id") == 11)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting set', but no meeting date or time specified.\n";
		}
		
		/*if (!$F("effective"))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but communication is not marked as 'Effective'.\n";
		}*/
		
		if ($F("notes") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Notes must be completed\n";
		}
	}
	
	// follow-up meeting set checks - existing status of follow-upmeeting set
	if ($F("communication_status_id_orig") == 11 && $F("communication_status_id") == 11)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting set', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting set', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting rearranged'?\n";
		}
	}
			
	// meeting to be re-arranged checks - new status of meeting to be re-arranged
	if ($F("communication_status_id_orig") != 12 && $F("communication_status_id") == 12)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Meeting to be rearranged', but date/time of meeting has been changed. Should status be changed to 'Meeting rearranged'?\n";
		}
	}

	// meeting to be re-arranged checks - existing status of meeting to be re-arranged
	if ($F("communication_status_id_orig") == 12 && $F("communication_status_id") == 12)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting to be rearranged', but date/time of meeting has been changed. Should status be changed to 'Meeting rearranged'?\n";
		}
	}
	
	// follow-up meeting to be re-arranged checks - new status of follow-up meeting to be re-arranged
	if ($F("communication_status_id_orig") != 13 && $F("communication_status_id") == 13)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Follow-up meeting to be rearranged', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting rearranged'?\n";
		}
	}

	// follow-up meeting to be re-arranged checks - existing status of follow-up meeting to be re-arranged
	if ($F("communication_status_id_orig") == 13 && $F("communication_status_id") == 13)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting to be rearranged', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting rearranged'?\n";
		}
	}
	
	// meeting re-arranged checks - new status of meeting re-arranged
	if ($F("communication_status_id_orig") != 14 && $F("communication_status_id") == 14)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// meeting re-arranged checks - existing status of meeting re-arranged
	if ($F("communication_status_id_orig") == 14 && $F("communication_status_id") == 14)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status set to 'Meeting rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// follow-up meeting re-arranged checks - new status of follow-up meeting re-arranged
	if ($F("communication_status_id_orig") != 15 && $F("communication_status_id") == 15)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// follow-up meeting re-arranged checks - existing status of follow-up meeting re-arranged
	if ($F("communication_status_id_orig") == 15 && $F("communication_status_id") == 15)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status set to 'Follow-up meeting rearranged', but no meeting date or time specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	if ($("has_current_information_request"))
	{
		if ($("has_current_information_request").innerHTML == "true" && $F("notes") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error + ". Notes must be completed when an information is created.\n";
			
		}
	}
	
	
	if ($F("due_date") != "")
	{
		// Make the selected date into a js format date & adjust for timezone
		var raw_date_string = $F("due_date") + " " + $F("next_communication_time_Hour") + ":" + $F("next_communication_time_Minute");
		selected_date = new Date(getDateFromFormat(raw_date_string, "dd/MM/yyyy HH:mm"));
//		alert("Before adjustment(selected_date) = " + selected_date);
		
		// Need to adjust the created date by the time zone offset
//		hours_to_adjust = selected_date.getTimezoneOffset()/60;
//		alert('hours_to_adjust = ' + hours_to_adjust); 
//		selected_date.setHours(selected_date.getHours() - hours_to_adjust);
//		alert("After adjustment(selected_date) = " + selected_date);
		
		// Get current date
		var d = new Date();
//		alert("selected date = " + formatDate(selected_date, "dd/MM/yyyy HH:mm"));
//		alert("current date = " + formatDate(d, "dd/MM/yyyy HH:mm"));

		//debugging - leave for a while
/*		alert("selected date object after hour adjustment (selected_date) = " + selected_date);
		alert("selected date formatted (formatDate(selected_date, 'dd/MM/yyyy HH:mm')) = " + formatDate(selected_date, "dd/MM/yyyy HH:mm"));
		alert("selected date object (selected_date) = " + selected_date.toUTCString());
		alert("selected date object (selected_date) time zone offset = " + selected_date.getTimezoneOffset());
		alert("current date object (d) = " + d);
		alert("current date  formatted (formatDate(d, 'dd/MM/yyyy HH:mm')) = " + formatDate(d, "dd/MM/yyyy HH:mm"));
		alert("current date object (d) = " + d.toUTCString());
		alert("current date object (d) time zone offset = " + d.getTimezoneOffset());
*/
		// Check if valid date
		if (getDateFromFormat(raw_date_string, 'dd/MM/yyyy HH:mm'))
		{
			if (compareDates(formatDate(selected_date, "dd/MM/yyyy HH:mm"), "dd/MM/yyyy HH:mm", formatDate(d, "dd/MM/yyyy HH:mm"), "dd/MM/yyyy HH:mm") == 0)
			{
				msg_error_count ++;
				msg_error += msg_error_count + ". Recall Date must be in the future\n";
			}
		}
		else
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Recall Date is invalid (check date and also ensure format 'dd/mm/yyyy' is used)\n";
		}
		
		// Check recall reason has been completed
		if ($F("next_communication_date_reason_id") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Recall reason must be completed\n";
		}
	}
	
	if ($F("effective"))
	{
		if ($F("due_date") == "")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Recall Date must be completed\n";
		}
		if ($F("decision_maker_type_id") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Decision Maker must be selected\n";
		}
		if ($F("targeting") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". % Match to Offer must be selected\n";
		}
		if ($F("receptiveness") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Receptiveness must be selected\n";
		}
		if ($F("agency_user") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Agency User must be selected\n";
		}
	}
	
	if ($F("comments") == 0)
	{
		msg_error_count ++;
		msg_error += msg_error_count + ". Comments must be completed\n";
	}
	
	if ($F("effective"))
	{
		if ($F("notes") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Notes must be completed\n";
		}
	}
	
	if (msg_error != "")
	{
		alert("Please complete or correct the following items:\n\n" + msg_error);
		return false;
	}
	else
	{
		if (msg_warning != "")
		{
			if (confirm("Please check the following suggestions:\n\n" + msg_warning + "\n\If you still wish to log this communication click 'OK', otherwise click 'Cancel' and you will be able to amend the communication details."))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
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

//--- Meeting calendar functions ---
// Handles input from meeting calendar - updates app_domain_Meeting_date text field
function handleSelect_meeting(type,args,obj) 
{ 
    var dates = args[0];  
    var date = dates[0]; 
	//convert incoming params to string type so we can pad the day and month values later on in the function
    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 

    var txtDate1 = $("app_domain_Meeting_date"); 
    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
	Effect.toggle($("meeting_calendar_display"), 'blind', {duration: 0.3});
     
} 
 
// Handles input to the meeting calendar from app_domain_Meeting_date text field when the ... button is clicked
function updateMeetingCal() 
{ 
	Effect.toggle($("meeting_calendar_display"), 'blind', {duration: 0.3});
    var txtDate1 = $("app_domain_Meeting_date"); 
 
 	if (txtDate1.value != "")
 	{
 		// Select the date typed in the field 
    	YAHOO.example.calendar.cal_meeting.select(txtDate1.value);  
 	}
   	       
   YAHOO.example.calendar.cal_meeting.render();
}

//--- Recall calendar functions ---
// Handles input from recall calendar - updates recall_date text field
function handleSelect_recall(type,args,obj) 
{ 
    var dates = args[0];  
    var date = dates[0]; 
	//convert incoming params to string type so we can pad the day and month values later on in the function
    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 

    var txtDate1 = $("due_date"); 
    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
	Effect.toggle($("due_date_calendar_display"), 'blind', {duration: 0.3});
} 
 
// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateDueDate() 
{ 
	Effect.toggle($('due_date_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('due_date');
	
	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_due_date.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_due_date.render();
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateReminderDate() 
{ 
	Effect.toggle($('reminder_date_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('reminder_date');
	
	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_reminder_date.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_reminder_date.render();
}

function togglePopup(item_type)
{
	var popup_div = $('popup_' + item_type);
	var popup_icon = $('popup_icon_' + item_type);
	
	var src = popup_icon.src;
	
	if (popup_div.popup.is_open == undefined || popup_div.popup.is_open === false)
	{
		if (src.slice(0, src.length-9) != "_down.png")
		{
			src = src.slice(0, (src.length -4)) + '_down.png';
		}
		popup_icon.src = src;
		popup_div.popup.show();
	}
	else if (popup_div.popup.is_open === true)
	{
		if (src.slice((src.length-9)) == "_down.png")
		{
			src = src.slice(0, src.length-9) + '.png' ;
		}
		popup_icon.src = src;
		popup_div.popup.hide();
	}
	else //we should never get here,but just in case.....
	{
		alert("Please report error with Workspace.js in function 'togglePopup'");
	}
}

function createMeetingEditFields()
{
	var frm = $("adminForm");
	
	//meeting id
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "meeting_id");
	input.setAttribute("id", "meeting_id");
	input.setAttribute("value", parent.ifr_info$F("id"));
	frm.appendChild(input);
	
	//meeting date
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "meeting_date");
	input.setAttribute("id", "meeting_date");
	input.setAttribute("value", parent.ifr_info$F("app_domain_Meeting_date"));
	frm.appendChild(input);
	
	//meeting time
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "meeting_date");
	input.setAttribute("id", "meeting_date");
	input.setAttribute("value", parent.ifr_info.$F("time_Hour") + ":" + parent.ifr_info.$F("time_Minute"));
	frm.appendChild(input);
	
	//meeting notes
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "meeting_notes");
	input.setAttribute("id", "meeting_notes");
	input.setAttribute("value", parent.ifr_info.$F("app_domain_Meeting_notes"));
	frm.appendChild(input);
}

function init()
{
	// Due date
	YAHOO.example.calendar.cal_due_date = new YAHOO.widget.Calendar("cal_due_date", "div_cal_due_date");
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_due_date.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_due_date.selectEvent.subscribe(handleSelect_recall, YAHOO.example.calendar.cal_due_date, true);
	YAHOO.example.calendar.cal_due_date.render();
	
	// Reminder date
	YAHOO.example.calendar.cal_reminder_date = new YAHOO.widget.Calendar("cal_reminder_date", "div_cal_reminder_date");
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_reminder_date.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_reminder_date.selectEvent.subscribe(handleSelect_recall, YAHOO.example.calendar.cal_due_date, true);
	YAHOO.example.calendar.cal_reminder_date.render();
}

YAHOO.namespace("example.calendar");
YAHOO.util.Event.addListener(window, "load", init);

top.communication_loaded = true;

// The following variable used in validation. It will be set to true if we have a current meeting.
var has_current_meeting = false;







function toggleReminderDate()
{
	if ($F('set_reminder'))
	{
		if ($('div_reminder_date').style.display == 'block' || $('div_reminder_date').style.display == '') 
		{
			new Effect.BlindUp($('div_reminder_date'), {duration: 0.3});
		}
	}
	else
	{
		if ($('div_reminder_date').style.display == 'none') 
		{
			new Effect.BlindDown($('div_reminder_date'), {duration: 0.3});
		}
	}
	return false;
}
{/literal}
</script>


<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Actions &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$actions|@count}</strong> record{if $actions|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_characteristic" name="add_new_characteristic" value="Add New Action" onclick="javascript:$('div_new_action').show();" />
						<div id="div_new_action" style="display: none; margin-top: 10px">
							<form id="form_new_action" name="form_new_characteristic">

								<table class="ianlist">
									<tr>
										<th style="vertical-align: top; width: 20%">Subject</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="name" style="width: 350px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Notes</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><textarea id="notes" rows="5" style="width: 350px"></textarea></td>
									</tr>

				<tr>
					<th style="width: 30%; vertical-align: top">Due Date</th>
					<td style="width: 70%">
						<input type="text" value="" id="due_date" name="due_date" />
						<input type="button" value="..." onclick="javascript:updateDueDate();" />
						<a href="#" onclick="javascript:clearDate('due_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('due_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						<div id="due_date_calendar_display" style="display: none">
							<div id="div_cal_due_date"></div> 
						</div>
					</td>
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top"></th>
					<td style="width: 70%">
						{html_select_time 
							prefix          = "next_communication_time_"
							time            = '00:00'
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5}
					</td>
				</tr>

									<tr>
										<th style="vertical-align: top; width: 20%">Set Reminder?</th>
										<td colspan="2" style="vertical-align: top; width: 80%">
											<input type="checkbox" id="set_reminder" name="set_reminder" onchange="toggleReminderDate(); return false;" />
											<div id="div_reminder_date" style="display: none">
												<input type="text" value="" id="reminder_date" name="reminder_date" />
												<input type="button" value="..." onclick="javascript:updateReminderDate();" />
												<a href="#" onclick="javascript:clearDate('reminder_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('reminder_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
												<div id="reminder_date_calendar_display" style="display: none">
													<div id="div_cal_reminder_date"></div> 
												</div>
											</div>
										</td>
									</tr>
								</table>
							

								<div>
									<input type="button" id="cancel_characteristic" name="cancel_characteristic" value="Cancel" onclick="javascript:$('form_new_characteristic').reset(); toggleCharacteristicDataType(); $('div_new_action').hide();" />&nbsp;
									<input type="button" id="reset_characteristic" name="reset_characteristic" value="Reset" onclick="javascript:$('form_new_characteristic').reset(); toggleCharacteristicDataType(); return false;" />&nbsp;
									<input type="button" id="save_characteristic" name="save_characteristic" value="Save" onclick="javascript:saveCharacteristic();" />
								</div>

							</form>
						</div>

					</td>
				</tr>

				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Subject</th>
									<th>Due</th>
									<th style="width: 10%; text-align: center">Reminder</th>
									<th style="width: 10%; text-align: center">Completed</th>
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=action_loop from=$actions item=action}
							<tr id="tr_{$action->getId()}">
								<td style="text-align: center">{$action->getId()}</td>
								<td>{$action->getSubject()}</td>
								<td>{$action->getDueDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td>{$action->getReminderDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td style="text-align: center; vertical-align: middle">{if $action->isCompleted()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if} {$action->isCompleted()}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$action->getId()}" title="Edit" href="#" onclick="javascript:editCharacteristic({$action->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$action->getId()}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$action->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

<script language="JavaScript" type="text/javascript">
/*	switchTypeIcon();*/
</script>

{include file="footer.tpl"}