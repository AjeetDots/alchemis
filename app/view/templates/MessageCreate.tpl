{include file="header2.tpl" title="Message Create"}

<script language="JavaScript">

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

{literal}

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




// The following variable used in validation. It will be set to true if we have a current meeting.
var has_current_meeting = false;


function addMessage()
{
iframeLocation(	document, 'index.php?cmd=MessageCreate');
}

{/literal}
</script>


{if $success}

	<p>The message has been created.</p>
	<input type="button" id="btn_add_message" value="Add another message" onclick="javascript:addMessage(); return false;" />
{*	<script language="JavaScript" type="text/javascript">
		parent.document.forms.searchForm.company_equal.value = "{$name}";
		parent.doSearch(parent.document.forms.searchForm.company_equal);
	</script>
*}

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=DashboardMessages';
	</script>
	
{else}

	<script type="text/javascript">
	{literal}
	
	function submitbutton(pressbutton)
	{
	//	alert('submitbutton(' + pressbutton + ')');
	//	var form = document.adminForm;
	//	var type = form.type.value;
		
		if (pressbutton == 'save')
		{
			submitform(pressbutton);
		}
		else if (pressbutton == 'reset')
		{
			document.adminForm.reset();
		}
	}

	function submitform(pressbutton)
	{
	//	alert('submitform(' + pressbutton + ')');
		document.adminForm.task.value = pressbutton;
		try
		{
			document.adminForm.onsubmit();
		}
		catch(e)
		{}
		document.adminForm.submit();
	}

	{/literal}
	</script>

	<form action="index.php?cmd=MessageCreate" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="message_id" value="{$app_domain_Message_id}" />
		<input type="hidden" name="app_domain_Message_timestamp" value="{$app_domain_Message_timestamp}" />

		<fieldset class="adminform">
			<legend>Message</legend>

			<table>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Message_message} class="key_error" title="{$errors.app_domain_Message_message->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Message_message">Message *</label>
					</td>
					<td><input type="text" name="app_domain_Message_message" id="app_domain_Message_message" style="width: 200px" value="{$app_domain_Message_message}" maxlength="255" /></td>
				</tr>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Message_published} class="key_error" title="{$errors.app_domain_Message_published->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Message_published">Published</label>
					</td>
					<td>
						<input type="checkbox" id="app_domain_Message_published" name="app_domain_Message_published"{if $app_domain_Message_published} checked="checked"{/if} />
					</td>
				</tr>
			</table>

		</fieldset>
		
		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /> | <input type="reset" value="Reset" /></p>
	
	</form>

{/if}

{include file="footer2.tpl"}