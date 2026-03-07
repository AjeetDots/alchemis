{include file="header2.tpl" title="Log Communication"}

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
var post_initiative_actions = '';

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

/* --- Ajax calling functions --- */
function makeDisciplineRow(discipline_id, discipline, index)
{
	if (discipline_id == '')
	{
		alert('Please select a valid discipline');
		return false;
	}
	else
	{
		removeOption('available_disciplines', index)
	}
	
	var ill_params = new Object;
	ill_params['discipline_id'] = discipline_id;
	ill_params['discipline'] = discipline;
	
	responders_total_count = 1;
	responders_in_progress_count = 1;
			
	getAjaxData("AjaxCommunication", "", "make_discipline_row", ill_params, "Saving...")
}

function getPostInitiativeActionsCount(post_initiative_id)
{
	var ill_params = new Object;
	ill_params['post_initiative_id'] = post_initiative_id;
	
	responders_total_count = 1;
	responders_in_progress_count = 1;
			
	getAjaxData("AjaxCommunication", "", "get_post_initiative_actions", ill_params, "Saving...", true)
}

/* --- Ajax return data handlers --- */
function AjaxCommunication(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		//alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		switch (t.cmd_action)
		{
			case "make_discipline_row":
//				alert(t.result['template']);
				insertRow('discipline_grid', '<td colspan="5"><hr /></td>');
				insertRow('discipline_grid', t.result['template']);
				break;
			case "get_post_initiative_actions":
				post_initiative_actions = t.post_initiative_actions;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function insertRow(table_id, html)
{
	var tbl = document.getElementById(table_id);
	var lastRow = tbl.rows.length;
	
	// if there`s no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	row.innerHTML = html;	
}

function removeOption(select_id, index_to_remove)
{
	$(select_id).remove(index_to_remove);
}

function doDMStuff()
{
	//alert($("ote").style.visibility + $("dm").value);
	if ($("effective").checked == true)
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
		
		// if fresh lead then set status to auto_calculate (option 0)
		if ($F('communication_status_id') == 7)
		{
			$('communication_status_id_select').selectedIndex = 0;
			$('communication_status_id').value = 0;
		}

    var spoken = $('data_source_id').dataset.spoken;
    if (spoken) $('data_source_id').value = spoken;
	}
	else
	{
		// don't need to set any displayed values (eg OTE) in effectives section back to their defaults
		// as the save function in command/CommunicationCreate doesn't save them if effective
		// checkbox is not selected

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
	}
	return false;
}

function doSelectGlobalDataSource() {
  var select = $('data_source_id');

  if (select) {
    var suggested = select.dataset.suggested;
    var current = select.dataset.current;

    if (select.value == '0' && current == suggested) {
      var global = $('global_data_source_id').value;

      for (i = 0; i < select.length; ++i){
        if (select.options[i].value == global){
          select.value = global;
        }
      }
    }
  } 
}

function doStatusChange()
{
	if ($F("communication_status_id_select") >= 12)
	{
		$("row_meeting_date").style.visibility = "visible";
		$("row_meeting_time").style.visibility = "visible";
		$("row_meeting_location").style.visibility = "visible";
		$("row_nbm_predicted_rating").style.visibility = "visible";
	}
	else
	{
		$("row_meeting_date").style.visibility = "collapse";
		$("row_meeting_time").style.visibility = "collapse";
		$("row_meeting_location").style.visibility = "collapse";
		$("row_nbm_predicted_rating").style.visibility = "collapse";
	}
	
	if ($F("communication_status_id_select") > 19)
	{
		$("app_domain_Meeting_date").disabled = true;
		$("meeting_time_Hour").disabled = true;
		$("meeting_time_Minute").disabled = true;
		$("span_meeting_calendar_controls").style.display = "none";
		$("meeting_location").disabled = true;
		$("nbm_predicted_rating").disabled = true;
		
	}
	else
	{
		$("app_domain_Meeting_date").disabled = false;
		$("meeting_time_Hour").disabled = false;
		$("meeting_time_Minute").disabled = false;
		$("span_meeting_calendar_controls").style.display = "";
		$("meeting_location").disabled = false;
		$("nbm_predicted_rating").disabled = false;
	}
	
	
	if ($F("communication_status_id") == 12 && $F("communication_status_id_orig") != 12)
	{
		// Status changed to 'Meeting set'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 13 && $F("communication_status_id_orig") != 13)
	{
		// Status changed to 'Follow-up meeting set'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 18 && $F("communication_status_id_orig") != 18)
	{
		// Status changed to 'Meeting rearranged'
		$("span_meeting_details_checked").style.display = "block";
	}
	else if ($F("communication_status_id") == 19 && $F("communication_status_id_orig") != 19)
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
			if ($("meeting_location") != undefined)
				$("meeting_location").disabled = false;
			if ($("nbm_predicted_rating") != undefined)
				$("nbm_predicted_rating").disabled = false;	
			
				
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
	

	
	// Now get on with the rest of the validation and then check if action info has been returned
	if ($F("lead_source_id") == 0)
	{
		msg_error_count++;
		msg_error += msg_error_count + ". Please ensure you have assigned a Lead Source for this prospect.\n";
	}
	
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
	if ($F("communication_status_id_orig") != 12 && $F("communication_status_id") == 12)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting set', but Predicted outcome not specified.\n";
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
	if ($F("communication_status_id_orig") == 12 && $F("communication_status_id") == 12)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting set', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting set', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting set', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting set', but date/time of meeting has been changed. Should status be changed to 'Meeting rearranged'?\n";
		}
	}
	
	// follow-up meeting set checks - new status of follow-up meeting set
	if ($F("communication_status_id_orig") != 13 && $F("communication_status_id") == 13)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting set', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting set', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting set', but Predicted outcome not specified.\n";
		}
		
		if ($F("notes") == 0)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Notes must be completed\n";
		}
	}
	
	// follow-up meeting set checks - existing status of follow-upmeeting set
	if ($F("communication_status_id_orig") == 13 && $F("communication_status_id") == 13)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting set', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting set', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting set', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting set', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting rearranged'?\n";
		}
	}
			
	// meeting to be re-arranged: client checks - new status of meeting to be re-arranged: client
	if ($F("communication_status_id_orig") != 14 && $F("communication_status_id") == 14)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: client', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: client', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: client', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Meeting to be rearranged: client', but date/time of meeting has been changed. Should status be changed to 'Meeting to be rearranged: client'?\n";
		}
	}

	// meeting to be re-arranged: client checks - existing status of meeting to be re-arranged: client
	if ($F("communication_status_id_orig") == 14 && $F("communication_status_id") == 14)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: client', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: client', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: client', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting to be rearranged: client', but date/time of meeting has been changed. Should status be changed to 'Meeting to be rearranged: client'?\n";
		}
	}
	
	// follow-up meeting to be re-arranged: client checks - new status of follow-up meeting to be re-arranged: client
	if ($F("communication_status_id_orig") != 15 && $F("communication_status_id") == 15)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: client', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: client', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: client', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Follow-up meeting to be rearranged: client', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting to be rearranged: client'?\n";
		}
	}

	// follow-up meeting to be re-arranged: client checks - existing status of follow-up meeting to be re-arranged: client
	if ($F("communication_status_id_orig") == 15 && $F("communication_status_id") == 15)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: client', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: client', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: client', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting to be rearranged: client', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting rearranged: client'?\n";
		}
	}
	
	// meeting to be re-arranged: Alchemis checks - new status of meeting to be re-arranged: Alchemis
	if ($F("communication_status_id_orig") != 16 && $F("communication_status_id") == 16)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: Alchemis', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: Alchemis', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting to be rearranged: Alchemis', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Meeting to be rearranged: Alchemis', but date/time of meeting has been changed. Should status be changed to 'Meeting to be rearranged: Alchemis'?\n";
		}
	}

	// meeting to be re-arranged: Alchemis checks - existing status of meeting to be re-arranged: Alchemis
	if ($F("communication_status_id_orig") == 16 && $F("communication_status_id") == 16)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: Alchemis', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: Alchemis', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting to be rearranged: Alchemis', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting to be rearranged: Alchemis', but date/time of meeting has been changed. Should status be changed to 'Meeting to be rearranged: Alchemis'?\n";
		}
	}
	
	// follow-up meeting to be re-arranged: Alchemis checks - new status of follow-up meeting to be re-arranged: Alchemis
	if ($F("communication_status_id_orig") != 17 && $F("communication_status_id") == 17)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: Alchemis', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: Alchemis', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting to be rearranged: Alchemis', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status changed to 'Follow-up meeting to be rearranged: Alchemis', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting to be rearranged: Alchemis'?\n";
		}
	}

	// follow-up meeting to be re-arranged: Alchemis checks - existing status of follow-up meeting to be re-arranged: Alchemis
	if ($F("communication_status_id_orig") == 17 && $F("communication_status_id") == 17)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: Alchemis', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: Alchemis', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting to be rearranged: Alchemis', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime != curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Follow-up meeting to be rearranged: Alchemis', but date/time of meeting has been changed. Should status be changed to 'Follow-up meeting to be rearranged: Alchemis'?\n";
		}
	}
	
	// meeting re-arranged checks - new status of meeting re-arranged
	if ($F("communication_status_id_orig") != 18 && $F("communication_status_id") == 18)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// meeting re-arranged checks - existing status of meeting re-arranged
	if ($F("communication_status_id_orig") == 18 && $F("communication_status_id") == 18)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting rearranged', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting rearranged', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Meeting rearranged', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status still set to 'Meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// follow-up meeting re-arranged checks - new status of follow-up meeting re-arranged
	if ($F("communication_status_id_orig") != 19 && $F("communication_status_id") == 19)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but Predicted outcome not specified.\n";
		}
		
		var curr_meeting_datetime = $F("app_domain_Meeting_date") +  " " + $F("meeting_time_Hour") + ":" + $F("meeting_time_Minute");
		if (orig_meeting_datetime == curr_meeting_datetime)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status changed to 'Follow-up meeting rearranged', but no change made to date/time of meeting.\n";
		}
	}
	
	// follow-up meeting re-arranged checks - existing status of follow-up meeting re-arranged
	if ($F("communication_status_id_orig") == 19 && $F("communication_status_id") == 19)
	{
		if ($F("app_domain_Meeting_date") == "" || $F("meeting_time_Hour") == "00")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting rearranged', but no meeting date or time specified.\n";
		}
		
		if ($F("meeting_location") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting rearranged', but location not specified.\n";
		}
		
		if ($F("nbm_predicted_rating") == "0")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status is 'Follow-up meeting rearranged', but Predicted outcome not specified.\n";
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
	
	if ($F("priority_callback") && $F("next_communication_date") == "") {
		msg_error_count ++;
		msg_error += msg_error_count + ". You have selected a Priority Recall Date but have not entered a Recall Date\n";
	}
	
	if ($F("next_communication_date") != "")
	{
		// Make the selected date into a js format date & adjust for timezone
		var raw_date_string = $F("next_communication_date") + " " + $F("next_communication_time_Hour") + ":" + $F("next_communication_time_Minute");
		selected_date = new Date(getDateFromFormat(raw_date_string, "dd/MM/yyyy HH:mm"));
		
		// Get current date
		var d = new Date();
		//alert("selected date = " + formatDate(selected_date, "dd/MM/yyyy HH:mm"));
		//alert("current date = " + formatDate(d, "dd/MM/yyyy HH:mm"));

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
		
		
//		// Check recall reason has been completed
//		if ($F("next_communication_date_reason_id") == 0)
//		{
//			msg_error_count ++;
//			msg_error += msg_error_count + ". Recall reason must be completed\n";
//		}
		
	}
	
	if ($F("effective"))
	{
		if ($F("communication_status_id") == 7)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Status cannot be set to Fresh Lead for Effective calls\n";
		}
		
		switch ($F("communication_status_id"))
		{
			case '8':
			case '9':
			case '10':
			case '11':
				// do not enforce next communciation date requirement		
				break;
			default:
				if ($F("next_communication_date") == "")
				{
					msg_error_count ++;
					msg_error += msg_error_count + ". Recall Date must be completed\n";
				}
				break;
		}
		/*
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
		*/
		if ($F("comments") == "")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Comments must be completed\n";
		}
		
		if ($F("notes") == "")
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Notes must be completed\n";
		}
		
		// if effective comm and status not changed then warn user
		if ($F("communication_status_id_orig") == $F("communication_status_id"))
		{
			msg_warning_count ++;
			msg_warning += msg_warning_count + ". Status has not been changed for this effective - is this correct?\n";
			
		}
	}

  if ($('data_source_id') && $F('data_source_id') === '0') {
    msg_error_count ++;
    msg_error += msg_error_count + ". Please select a data source.";
  }

	// discipline grid validation
	discipline_check.each(function(item){
		if (($F('decision_maker_type_id_' + item[0]) == 0 && $('decision_maker_confirmed_' + item[0]).checked))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Mis-match for decision maker info for discipline " + item[1] + "\n";
		}
		if (($F('agency_user_type_id_' + item[0]) == 0 && $('agency_user_confirmed_' + item[0]).checked))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Mis-match for agency user info for discipline " + item[1] + "\n";
		}
		if (($F(item[0] + 'Month') == "" && $('review_date_confirmed_' + item[0]).checked) || 
		($F(item[0] + 'Year') == "" && $('review_date_confirmed_' + item[0]).checked))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Mis-match for review date info for discipline " + item[1] + "\n";
		}
	});
	
	
	t = $F("comments");
	if (t.length > 255)
	{
		msg_error_count ++;
		msg_error += msg_error_count + ". Comments cannot be longer than 255 characters (" + t.length + " characters entered)\n";
	}
	
	// fire off ajax request to check if any meeting or information request actions exist
	getPostInitiativeActionsCount($F("post_initiative_id"));
	
	// meeting confirmation actions 
	// for: meeting set/meeting rearranged
//	var meeting_confirmation_actions = false;
	switch ($F("communication_status_id"))
	{
		case '12':
		case '13':
		case '18':
		case '19':
//			alert($F("communication_status_id"));
//			alert('post_initiative_actions.indexOf(1) = ' + post_initiative_actions.indexOf(1));
//			if (post_initiative_actions.indexOf(1) == -1)
//			{
//				msg_error_count ++;
//				msg_error += msg_error_count + ". For a status of meeting set or re-arranged you must create at least one meeting confirmation action\n";
//			}
			if (post_initiative_actions.indexOf(1) == -1)
			{
				msg_warning_count ++;
				msg_warning += msg_warning_count + ". You have changed to a status of meeting set or re-arranged. You may want to consider creating a meeting confirmation action\n";
			}
			break;
	}
	
	// meeting re-arrangement actions
	// for: meetings to be rearranged
//	var meeting_rearrangement_actions = false;
	switch ($F("communication_status_id"))
	{
		case '14':
		case '15':
		case '16':
		case '17':
//			if (post_initiative_actions.indexOf(3) == -1)
//			{
//				msg_error_count ++;
//				msg_error += msg_error_count + ". For a status of meeting to be rearranged you must create at least one meeting rearrangement action\n";
//			}
			if (post_initiative_actions.indexOf(3) == -1)
			{
				msg_warning_count ++;
				msg_warning += msg_warning_count + ". You have changed to a status of meeting to be rearranged. You may want to consider creating a meeting rearrangement action\n";
			}
			break;
	}
	
	// meeting follow-up checks
	// for: meetings attended
//	var meeting_followup_actions = false;
	switch ($F("communication_status_id"))
	{
		case '24':
		case '25':
		case '26':
		case '27':
//			if (post_initiative_actions.indexOf(4) == -1)
//			{
//				msg_error_count ++;
//				msg_error += msg_error_count + ". For a status of meeting attended you must create at least one meeting follow-up action\n";
//			}
			if (post_initiative_actions.indexOf(4) == -1)
			{
				msg_warning_count ++;
				msg_warning += msg_warning_count + ". You have changed to a status of meeting attended. You may want to consider creating a meeting follow-up action\n";
			}
			break;
	}
	
	// follow up meeting to be arranged
	// ??
	
	// information request actions
	// if info_req is checked but no 'current' information request actions
	if ($('information_request_checked').checked)
	{
		if (post_initiative_actions.indexOf(2) == -1)
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". You have selected an information request but no information request has been created\n";
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

//--- Generic calendar support functions ---
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

    var txtDate1 = $("next_communication_date"); 
    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
	Effect.toggle($("recall_calendar_display"), 'blind', {duration: 0.3});
     
   $("row_next_communication_date_reason_id").style.visibility = "visible";
} 
 
// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateRecallCal() 
{ 
	Effect.toggle($("recall_calendar_display"), 'blind', {duration: 0.3});
    var txtDate1 = $("next_communication_date"); 
 
 	if (txtDate1.value != "")
 	{
 		// Select the date typed in the field 
    	YAHOO.example.calendar.cal_recall.select(txtDate1.value);  
 	}
 	       
   YAHOO.example.calendar.cal_recall.render(); 
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
    YAHOO.example.calendar.cal_meeting = new YAHOO.widget.Calendar("cal_meeting","div_cal_meeting"); 
    
    YAHOO.example.calendar.cal_meeting.cfg.setProperty("START_WEEKDAY", 1); 
 	YAHOO.example.calendar.cal_meeting.cfg.setProperty("MDY_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_meeting.cfg.setProperty("MDY_MONTH_POSITION", 2); 
	YAHOO.example.calendar.cal_meeting.cfg.setProperty("MDY_YEAR_POSITION", 3); 
	 
	YAHOO.example.calendar.cal_meeting.cfg.setProperty("MD_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_meeting.cfg.setProperty("MD_MONTH_POSITION", 2); 

	YAHOO.example.calendar.cal_meeting.selectEvent.subscribe(handleSelect_meeting, YAHOO.example.calendar.cal_meeting, true); 
    YAHOO.example.calendar.cal_meeting.render(); 
    
    YAHOO.example.calendar.cal_recall = new YAHOO.widget.Calendar("cal_recall","div_cal_recall"); 
    
    YAHOO.example.calendar.cal_recall.cfg.setProperty("START_WEEKDAY", 1); 
 	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_MONTH_POSITION", 2); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_YEAR_POSITION", 3); 
	 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MD_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MD_MONTH_POSITION", 2); 

	YAHOO.example.calendar.cal_recall.selectEvent.subscribe(handleSelect_recall, YAHOO.example.calendar.cal_recall, true); 
    YAHOO.example.calendar.cal_recall.render(); 
} 

YAHOO.namespace("example.calendar"); 
YAHOO.util.Event.addListener(window, "load", init); 

top.communication_loaded = true;

// The following variable used in validation. It will be set to true if we have a current meeting.
var has_current_meeting = false;
var discipline_check = Array();

{/literal}
</script>

<form action="index.php?cmd=CommunicationCreate" method="post" name="adminForm" autocomplete="off">
<input type="hidden" name="task" value="" />
<input type="hidden" name="source_tab" value="{$source_tab}" />
<input type="hidden" id="post_initiative_id" name="post_initiative_id" value="{$post_initiative_id}" />
<input type="hidden" name="initiative_id" value="{$initiative_id}" />
<input type="hidden" name="post_id" value="{$post->getId()}" />
<input type="hidden" name="company_id" value="{$company->getId()}" />
<input type="hidden" name="meeting_id" value="{if $meeting}{$meeting->getId()}{/if}" />


<div id="meeting_alert" style="display: none; text-align: center; width:100%; background-color:#ffd; padding: 3px; border: thin solid red;">
	<span style="color: red; font-weight: bold;">Meetings exist</span>
</div>
	
{if $meetings}	
	<script language="JavaScript">
	{if $meetings->count() > 0}
		{literal}
		$('meeting_alert').style.display = '';
		new Effect.Pulsate($('meeting_alert'), {duration: 5});
		{/literal}
	{/if}
	</script>	
	<br />
{/if}


{* action warnings *}
<div id="action_alert" style="text-align: center; width:100%; background-color:#ffd; padding: 3px; border: thin solid {if $overdue_actions}red{elseif $actions}green{/if}; display: {if $actions || $overdue_actions}block{else}none{/if}">
	<a href="#" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={if $post_initiative}{$post_initiative->getId()}{/if}&referrer_type=communication');return false;" title="Displays actions">
		<span style="color: {if $overdue_actions}red{elseif $actions}green{/if}; font-weight: bold;">{if $overdue_actions}Overdue {/if} Actions exist</span>
	</a>	
</div>
<script language="JavaScript">
{if $overdue_actions || $actions}
{literal}
	new Effect.Pulsate($('action_alert'), {duration: 5});
{/literal}
{/if}
</script>
				
<table class="ianlist">
	<tr>
		<td style="width: 66%">
			<table class="ianlist">
				{*<tr>
					<th style="width: 30%; vertical-align: top">Type</th>
					<td style="width: 70%">
						<select id="direction" name="direction" style="width: 45%">
							<option value="out" selected="selected">Outbound</option>
							<option value="in">Inbound</option>
						</select>
						&nbsp;
						<select style="width: 45%">
							<option value="telephone" selected="selected">Telephone</option>
							<option value="email">E-mail</option>
							<option value="fax">Fax</option>
							<option value="mail">mail</option>
						</select>
					</td>
				</tr>*}
				<tr>
					<th style="width: 30%; vertical-align: top">For Client Initiative</th>
					<td style="width: 70%">{$client->getName()}{* (<em>initiative name goes here</em>)*}</td>
				</tr>
{*				<tr>
					<th style="width: 30%; vertical-align: top">Call Name</th>
					<td style="width: 70%"><em>&lt;&mdash; Call Name goes here &mdash;&gt;</em></td>
				</tr>
*}				<tr>
					<th style="width: 30%; vertical-align: top">Company</th>
					<td style="width: 70%">{$company->getName()}</td>
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top">Post/Contact</th>
					<td style="width: 70%">
						{$post->getJobTitle()}
						<br />
						{$post->getContactName()}
					</td>
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top">Lead Source</th>
					<td style="width: 70%">
						<select style="width: 50%" id="lead_source_id" name="lead_source_id">
							<option value="0">&ndash; Select &ndash;</option>
							{html_options options=$lead_source_options selected=$lead_source_selected}
						</select>
					</td>
				</tr>
				{*
				<tr>
					<th style="width: 30%; vertical-align: top">Post/Contact</th>
					<td style="width: 70%">
						<select style="width: 100%" id="post_list_by_first_name" onchange="javascript:loadPost(this.options[this.selectedIndex].value, 'post_list_by_post');">
							{foreach name="result_loop" from=$company_posts_first_name item=result}
								<option 
									{if $post_id == $result.id}selected {/if}
									value="{$result.id}" title="
									{$result.job_title}&nbsp;-&nbsp;{$result.first_name} {$result.surname}
									{if $result.telephone != ''}&nbsp;({$result.telephone}){/if}">
								{$result.first_name} {$result.surname} - {$result.job_title}
								{if $result.telephone != ''}&nbsp;({$result.telephone}){/if}
								</option>
							{/foreach}
						</select>
					</td>
				</tr>
				*}
				{if $last_communication}
				<tr>
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous communication: {$last_communication->getCommunicationDate()|date_format:"%d %B %Y"})
					</th>
				</tr>
				{/if}
				<tr id="row_communication_status_id" name="row_communication_status_id">
					<th style="width: 30%; vertical-align: top">Status</th>
					<td style="width: 70%">
						{* need to have the following hidden field as the 'communication_status_id_select' select field below may have some of the items
						disabled (for subjective status' where the system works out the value). If one of the disabled option is selected by default (user cannot select them),
						then nothing is passed back when the form is submitted. So we'll use a hidden field to hold the value. This will be updated whenever
						the select field is updated. *}
						<input type="hidden" id="communication_status_id" name="communication_status_id" value="{$status_id}" />
						<input type="hidden" id="communication_status_id_orig" name="communication_status_id_orig" value="{$status_id}" />
						
						<select style="width: 50%" id="communication_status_id_select" name="communication_status_id_select" onchange="javascript:$('communication_status_id').value = $F('communication_status_id_select');doStatusChange();return false;" >
							{if $status_is_auto_calculate}
								<option value="0">Other (system generated) ...</option>
							{/if}
							{html_options options=$status_options selected=$status_id}
							{*
							<option disabled="disabled" value="-1">&ndash; Select &ndash;</option>
							{assign var="show_system_generated" value=false}
							{foreach name=targeting from=$status_html item=status}
								<option value="{$status.id}"{if $status.is_auto_calculate} {assign var="show_system_generated" value=true}disabled="disabled"{/if}
							{/foreach}
							{if $show_system_generated}
								<option value="0">Other (system generated) ...</option>
							{/if}
							*}
						</select>
						
						
						<span id="span_meeting_details_checked" style="display: none">Meeting details, addresses and timings checked? <input type="checkbox" id="meeting_details_checked" /></span>
					</td>
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top">Next Action By</th>
					<th style="width: 70%">
						<select style="width: 50%" id="next_action_by" name="next_action_by">
							<option value="1" {if $post_initiative}{if $post_initiative->getNextActionBy() == 1}selected{/if}{/if}>Alchemis</option>
							<option value="{$client->getId()}" {if $post_initiative}{if $post_initiative->getNextActionBy() == $client->getId()}selected{/if}{/if}>{$client->getName()}</option>
						</select>
					</th>
				</tr>
				<tr style="visibility: {if $meeting}visible{else}collapse{/if}" id="row_meeting_date" name="row_meeting_date">
					<th style="vertical-align: top;">Meeting Date *</th>
					<td style="width: 70%;">
						<input type="text" value="{if $meeting}{$meeting->getDate()|date_format:"%d/%m/%Y"}{/if}"{if $meeting && $meeting->getStatusId() >= 16} {* meeting status cancelled or above*}disabled="disabled"{/if} id="app_domain_Meeting_date" name="app_domain_Meeting_date" />
						<span id="span_meeting_calendar_controls"{if $meeting && $meeting->getStatusId() >= 16} {* meeting status cancelled or above*}style="display: none"{/if}>
							<input type="button" value="..." onclick="javascript:updateMeetingCal();" /> 
							<a href="#" onclick="javascript:clearDate('app_domain_Meeting_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('meeting_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="meeting_calendar_display" style="display: none;">
							<div id="div_cal_meeting">
							</div> 
						</div>
					</td>
				</tr>
				<tr style="visibility: {if $meeting}visible{else}collapse{/if}" id="row_meeting_time" name="row_meeting_time">
					<th>Time *</th>
					<td>
						{if $meeting}
							{if $meeting->getStatusId() >= 16 }{* meeting status cancelled or above*}
								{assign var="all_extra" value="disabled"}
							{else}
								{assign var="all_extra" value="enabled"}
							{/if}
						{html_select_time 
							prefix          = "meeting_time_"
							time            = $meeting->getDate()|date_format:"%H:%M"
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5
							all_extra       = $all_extra}
						{else}
							{html_select_time 
							prefix          = "meeting_time_"
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5
							all_extra       = $all_extra}
						{/if}
					</td>
				</tr>
				<tr style="visibility: {if $meeting}visible{else}collapse{/if}" id="row_meeting_location" name="row_meeting_location">
					<th>Location *</th>
					<td>
						<select id="meeting_location" name="meeting_location" style="width: 50%;" {if $meeting && $meeting->getStatusId() >= 16} {* meeting status cancelled or above*}disabled="disabled"{/if}>
							<option value="0">-- Select --</option>
							{html_options options=$meeting_location_options selected=$meeting_location_selected}
						</select>
					<td>
				</tr>
				<tr style="visibility: {if $meeting}visible{else}collapse{/if}" id="row_nbm_predicted_rating" name="row_nbm_predicted_rating">
					<th>Predicted outcome *</th>
					<td>
						<select id="nbm_predicted_rating" name="nbm_predicted_rating" style="width: 50%;" {if $meeting && $meeting->getStatusId() >= 16} {* meeting status cancelled or above*}disabled="disabled"{/if}>
							<option value="0">-- Select --</option>
							{html_options options=$nbm_predicted_rating_options selected=$nbm_predicted_rating_selected}
						</select>
					</td>
				</tr>
				{* grab the date and time into a javascript variable. Use this in case we need to check if a date/time has changed - eg if status
				changed from meeting set to meeting rearranged*}
				{if $meeting}
				<script type="text/javascript">
					var orig_meeting_date = "{$meeting->getDate()|date_format:"%d/%m/%Y"}";
					var orig_meeting_time = "{$meeting->getDate()|date_format:"%H:%M"}";
					var orig_meeting_datetime = "{$meeting->getDate()|date_format:"%d/%m/%Y %H:%M"}";
					var has_current_meeting = true;
				</script>
				{/if}
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top">Recall Date</th>
					<td style="width: 70%">
						<input type="text" value="" id="next_communication_date" name="next_communication_date" />
						<input type="button" value="..." onclick="javascript:updateRecallCal();" />
						<a href="#" onclick="javascript:clearDate('next_communication_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('recall_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						<div id="recall_calendar_display" style="display: none">
							<div id="div_cal_recall"></div> 
						</div>
					</td>
				</tr>
				{if $last_communication}
				<tr>
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous recall date: {$last_communication->getNextCommunicationDate()|date_format:"%d %B %Y"})
					</th>
				</tr>
				{/if}
				<tr>
					<th style="width: 30%; vertical-align: top">Recall Time</th>
					<td style="width: 70%">
						{html_select_time 
							prefix          = "next_communication_time_"
							time            = '00:00'
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5}
						Priority?&nbsp;<input type="checkbox" name="priority_callback" id="priority_callback" />
					</td>
				</tr>
				<tr style="visibility: collapse" id="row_next_communication_date_reason_id" name="row_next_communication_date_reason_id">
					<th style="width: 30%; vertical-align: top">Recall Reason</th>
					<td style="width: 70%">
						<select style="width: 100%" id="next_communication_date_reason_id" name="next_communication_date_reason_id">
							<option value="0">&ndash; Select &ndash;</option>
							{html_options options=$next_communication_reasons}
						</select>
					</td>
				</tr>
				{if $last_communication}
				<tr style="visibility: collapse" id="row_last_next_communication_date_reason_id" name="row_last_next_communication_date_reason_id">
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous recall reason: {$last_communication->getNextCommunicationDateReasonDescription()})
					</th>
				</tr>
				{/if}
				<tr>
					<th style="width: 30%; vertical-align: top">Information request?</th>
					<td style="width: 70%">
						<input type="checkbox" id="information_request_checked" onclick="javascript:if ($('information_request_checked').checked){literal}{$('infomation_request_display').show()}else{$('infomation_request_display').hide()}{/literal}" />
						&nbsp;
						<span id="infomation_request_display" style="display:none">
						<a href="#" id="infomation_request_dd" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActionEdit&post_initiative_id={$post_initiative_id}&referrer_type=communication&type_id=2');return false;">
							[add information request]
						</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="#" id="infomation_request_view" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={$post_initiative_id}&referrer_type=communication&type_id=2');return false;">
							[view information requests]
						</a>
						</span>
					</td>
				</tr>
				<tr>
					<th style="width: 30%; vertical-align: top">Effective?</th>
					<td style="width: 70%">
						<input type="checkbox" id="effective" name="effective" onchange="doEffectiveShow();doDMStuff();" />
					</td>
				</tr>
				
				{if $last_communication}
				<tr style="visibility: collapse" id="row_last_dm" name="row_last_dm">
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous DM: {$last_communication->getDecisionMakerTypeDescription()})
					</th>
				</tr>
				{/if}

				<tr style="visibility: collapse" id="row_ote" name="row_ote">
					<th style="width: 30%; vertical-align: top">OTE?</th>
					<td style="width: 70%"><input type="checkbox" id="ote" name="ote" /></td>
				</tr>

				<tr style="visibility: collapse" id="row_match" name="row_match">
					<th style="width: 30%; vertical-align: top">&#37;&nbsp;Match to Offer</th>
					<td style="width: 70%">
						<select style="width: 50%" id="targeting" name="targeting">
							<option value="0">&ndash; Select &ndash;</option>
							{foreach name=targeting from=$targeting item=target_item}
								<option value="{$target_item.id}">{$target_item.description}</option>
							{/foreach}
							{*<option value="4" >Perfect</option>
							<option value="3">80% plus</option>
							<option value="2">50% - 80%</option>
							<option value="1">less than 50%</option>*}
						</select>
					</td>
				</tr>

				{if $last_communication}
				<tr style="visibility: collapse" id="row_last_match" name="row_last_match">
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous Match to Offer: {$last_communication->getTargetingDescription()})
					</th>
				</tr>
				{/if}

				<tr style="visibility: collapse" id="row_receptiveness" name="row_receptiveness">
					<th style="width: 30%; vertical-align: top">Receptiveness</th>
					<td style="width: 70%">
						<select style="width: 50%" id="receptiveness" name="receptiveness">
							<option value="0">&ndash; Select &ndash;</option>
							{foreach name=receptiveness from=$receptiveness item=receptiveness_item}
								<option value="{$receptiveness_item.id}">{$receptiveness_item.description}</option>
							{/foreach}
						</select>
					</td>
				</tr>

				{if $last_communication}
				<tr style="visibility: collapse" id="row_last_receptiveness" name="row_last_receptiveness">
					<th style="width: 30%; vertical-align: top">&nbsp;</th>
					<th style="width: 70%">
						(Previous Receptiveness: {$last_communication->getReceptivenessDescription()})
					</th>
				</tr>
				{/if}

        <tr>
					<th style="width: 30%; vertical-align: top">Global Data Source</th>
					<td style="width: 70%">
						<select style="width: 50%" id="global_data_source_id" name="global_data_source_id" onchange="doSelectGlobalDataSource();">
							{html_options options=$global_data_source_options selected=$global_data_source_selected}
						</select>
					</td>
				</tr>

        {if $data_source_current_id neq $data_source_spoken_id}
          <tr>
            <th style="width: 30%; vertical-align: top">Data Source</th>
            <th style="width: 70%">
              (Current data source: {$data_source_current})
            </th>
          </tr>
          <tr>
            <th style="width: 30%; vertical-align: top"></th>
            <td style="width: 70%">
              <select style="width: 50%" id="data_source_id" name="data_source_id" data-spoken="{$data_source_spoken_id}" data-suggested="{$data_source_suggested_id}" data-current="{$data_source_current_id}">
                <option value="0">&ndash; Select &ndash;</option>
                {html_options options=$data_source_options}
              </select>
            </td>
          </tr>
        {/if}
				
				<tr style="visibility: block" id="row_dm" name="row_dm">
					<td colspan="2">
						<table width="100%" id="discipline_grid">
							<thead>
								<tr>
									<td colspan="5" style="width: 100%">
										<hr />
									</td>
								</tr>
								<tr>
									<th style="width: 90px">Discipline</th>
									<th style="width: 100px">Decision Maker</th>
									<th style="width: 130px">Agency User</th>
									<th style="width: 190px">Review Date</th>
									<th>Incumbents</th>
								</tr>
							</thead>
							<tbody>
								{foreach name=discplines_grid from=$campaign_disciplines_grid item=grid_item}
								<script type="text/javascript">
									discipline_check.push(new Array({$grid_item.discipline_id}, '{$grid_item.discipline}'));
								</script>
								<tr>
									<td colspan="5">
										<hr />
									</td>
								</tr>
								<tr>
									<td>{$grid_item.discipline} *</td>
									<td>
										<select style="width: 100px" id="decision_maker_type_id_{$grid_item.discipline_id}" name="decision_maker_type_id_{$grid_item.discipline_id}" onchange="javascript:$('decision_maker_confirmed_{$grid_item.discipline_id}').checked = true;">
											<option value="0">Select...</option>
										{foreach name=dm_types from=$decison_maker_options item=dm_types_item}
											<option value="{$dm_types_item.id}" {if $dm_types_item.id == $grid_item.decison_maker_type_id}selected{/if}>{$dm_types_item.description}</option>
										{/foreach}
										</select>
										<br />
										<input type="checkbox" id="decision_maker_confirmed_{$grid_item.discipline_id}" name="decision_maker_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.dm_last_updated}{$grid_item.dm_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
										</td>
									<td>
										<select style="width: 125px" id="agency_user_type_id_{$grid_item.discipline_id}" name="agency_user_type_id_{$grid_item.discipline_id}" onchange="javascript:$('agency_user_confirmed_{$grid_item.discipline_id}').checked = true;">
											<option value="0">Select...</option>
										{foreach name=agency_user_types from=$agency_user_options item=agency_user_types_item}
											<option value="{$agency_user_types_item.id}" {if $agency_user_types_item.id == $grid_item.agency_user_type_id}selected{/if}>{$agency_user_types_item.description}</option>
										{/foreach}
										</select>
										<br />
										<input type="checkbox" id="agency_user_confirmed_{$grid_item.discipline_id}" name="agency_user_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.agency_user_last_updated}{$grid_item.agency_user_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
									</td>	
									<td>
										{if $grid_item.review_date}
											{html_select_date 	time=$grid_item.review_date 
															start_year='-5' 
															end_year='+5' 
															display_days=false 
															prefix=$grid_item.discipline_id 
															year_empty='Select...' 
															month_empty='Select...' 
															all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
										{else}
											{html_select_date time=0000-00-00  
															start_year='-5' 
															end_year='+5' 
															display_days=false 
															prefix=$grid_item.discipline_id 
															year_empty='Select...' 
															month_empty='Select...' 
															all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
										{/if}
										
										
										<br />
										<input type="checkbox" id="review_date_confirmed_{$grid_item.discipline_id}" name="review_date_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.review_date_last_updated}{$grid_item.review_date_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
									</td>
									<td>
										{if $grid_item.incumbent_count}
											{$grid_item.incumbent_count}
										{else}
											0
										{/if}
										<br />
										<a href="#" onclick="openInfoPane('index.php?cmd=PostIncumbentAgencies&post_id={$post->getId()}&discipline_id={$grid_item.discipline_id}');">Edit</a>
									</td>
				
								</tr>
								{/foreach}
								{foreach name=discplines_grid from=$non_campaign_disciplines_grid item=grid_item}
								<script type="text/javascript">
									discipline_check.push(new Array({$grid_item.discipline_id}, '{$grid_item.discipline}'));
								</script>
								<tr>
									<td colspan="5">
										<hr />
									</td>
								</tr>
								<tr>
									<td>{$grid_item.discipline}</td>
									<td>
										<select style="width: 100px" id="decision_maker_type_id_{$grid_item.discipline_id}" name="decision_maker_type_id_{$grid_item.discipline_id}" onchange="javascript:$('decision_maker_confirmed_{$grid_item.discipline_id}').checked = true;">
											<option value="">Select...</option>
										{foreach name=dm_types from=$decison_maker_options item=dm_types_item}
											<option value="{$dm_types_item.id}" {if $dm_types_item.id == $grid_item.decison_maker_type_id}selected{/if}>{$dm_types_item.description}</option>
										{/foreach}
										</select>
										<br />
										<input type="checkbox" id="decision_maker_confirmed_{$grid_item.discipline_id}" name="decision_maker_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.dm_last_updated}{$grid_item.dm_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
										</td>	
									<td>
										<select style="width: 125px" id="agency_user_type_id_{$grid_item.discipline_id}" name="agency_user_type_id_{$grid_item.discipline_id}" onchange="javascript:$('agency_user_confirmed_{$grid_item.discipline_id}').checked = true;">
											<option value="">Select...</option>
										{foreach name=agency_user_types from=$agency_user_options item=agency_user_types_item}
											<option value="{$agency_user_types_item.id}" {if $agency_user_types_item.id == $grid_item.agency_user_type_id}selected{/if}>{$agency_user_types_item.description}</option>
										{/foreach}
										</select>
										<br />
										<input type="checkbox" id="agency_user_confirmed_{$grid_item.discipline_id}" name="agency_user_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.agency_user_last_updated}{$grid_item.agency_user_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
									</td>	
									<td>
										{if $grid_item.review_date}
											{html_select_date 	time=$grid_item.review_date 
															start_year='-5' 
															end_year='+5' 
															display_days=false 
															prefix=$grid_item.discipline_id 
															year_empty='Select...' 
															month_empty='Select...' 
															all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
										{else}
											{html_select_date time=0000-00-00  
															start_year='-5' 
															end_year='+5' 
															display_days=false 
															prefix=$grid_item.discipline_id 
															year_empty='Select...' 
															month_empty='Select...' 
															all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
										{/if}
										
										
										<br />
										<input type="checkbox" id="review_date_confirmed_{$grid_item.discipline_id}" name="review_date_confirmed_{$grid_item.discipline_id}" />
										&nbsp;&nbsp;
										<span class="label" style="font-size:7pt">({if $grid_item.review_date_last_updated}{$grid_item.review_date_last_updated|date_format:"%d %B %Y"}{else}n/a{/if})</span>
									</td>	
									<td>
										{if $grid_item.incumbent_count}
											{$grid_item.incumbent_count}
										{else}
											0
										{/if}
										<br />
										<a href="#" onclick="openInfoPane('index.php?cmd=PostIncumbentAgencies&post_id={$post->getId()}&discipline_id={$grid_item.discipline_id}');">Edit</a>
									</td>
								</tr>
								
								{/foreach}
								<tr>
									<td colspan="5">
										<hr />
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<select style="width: 125px" id="available_disciplines" name="available_disciplines">
											<option value="">- Select -</option>
											{html_options options=$available_disciplines}
										</select>
										<a href="#" onclick="javascript:makeDisciplineRow($F('available_disciplines'), $('available_disciplines').options[$('available_disciplines').selectedIndex].text, $('available_disciplines').selectedIndex);">Add new discipline</a>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				
			</table>
		</td>
		<td style="width:50%">
			{* --meetings -- *}
			<p>
			 <a href="" onclick="javascript:openInfoPane('index.php?cmd=Meetings&post_initiative_id={$post_initiative_id}&referrer_type=communication');return false;">
				Meetings ({if $meetings}{$meetings->count()}{else}0{/if})
			</a>
			</p>
			{* --meeting actions -- *}
			<p>
			 <a href="" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={$post_initiative_id}&referrer_type=communication&type_id=&category=meeting');return false;">
				Meeting actions ({$meeting_action_count})
			</a>
			</p>
			{* --information requests -- *}
			<p>
			 <a href="" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={$post_initiative_id}&referrer_type=communication&type_id=2');return false;">
				Information requests ({$information_request_count})
			</a>
			</p>
			{* -- all actions -- *}
			<p>
		    <a href="" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={$post_initiative_id}&referrer_type=communication');return false;">
				View all actions ({$actions})
			</a>
			</p>
		    <p></p>
			Comments<br />
			<textarea rows="3" id="comments" name="comments" style="width: 99%">{if $post_initiative}{$post_initiative->getComment()}{/if}</textarea>
			<p></p>
			Notes&nbsp;&nbsp;<a href='index.php?cmd=WorkspaceNotes&post_id={$post->getId()}&initiative_id={$initiative_id}&post_initiative_id={$post_initiative_id}' target='ifr_info' >(View current notes)</a>
			<textarea rows="10" id="notes" name="notes" style="width: 100%"></textarea>
			<br />
			<br />
			<div style="float: right">
				<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
			</div>
		</td>
		
	</tr>
</table>
			
<br />

</form>

{include file="footer2.tpl"}