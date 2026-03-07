
function popupWindow(target)
{
	showPopWin(target, 800, 400, null);
}

function logCommunication(source_tab)
{
	
	
	if ($('company_id'))	
	{
		company_id = $F('company_id');	
	}
	else // should never get here, but just in case.....
	{
		alert ("Error - No company available. Please report this error to the system administrator");
		return;
	}
	
	if ($('post_id'))	
	{
		post_id = $F('post_id');	
	}
	else // should never get here, but just in case.....
	{
		alert ("Error - No post available. Please report this error to the system administrator");
		return;
	}
	
/*	// confirm there is a non-blank contact name for this post 
	if ($('post_contact_title'))	
	{
		post_contact_title = $F('post_contact_title') 
	}	
	else
	{
		post_contact_title = '';
	}
*/	
	if ($('post_contact_first_name'))	
	{
		post_contact_first_name = $F('post_contact_first_name');
	}	
	else
	{
		post_contact_first_name = '';
	}
	
	if ($('post_contact_surname'))	
	{
		post_contact_surname = $F('post_contact_surname');
	}	
	else
	{	
		post_contact_surname = '';
	}
			
	if (post_contact_first_name.length == 0 || post_contact_surname == 0)
	{
		alert ("A communication cannot be logged as the contact name for this post is not complete. Please edit the name before proceding");
		return;
	}
	
	if ($('post_initiative_id'))	
	{
		//if ($F('post_initiative_id') != '')
		//{
		//	alert("Here");
			post_initiative_id = $F('post_initiative_id');
			
		//	alert('post_initiative_id = ' + post_initiative_id);
		//} 
	}
	else
	{
		post_initiative_id = "";
	}
	
	if ($('initiative_id'))	
	{
		//if ($F('post_initiative_id') != '')
		//{
		//	alert("Here");
			initiative_id = $F("initiative_id");
			
		//	alert('initiative_id= ' + initiative_id);
		//} 
	}
	else
	{
		initiative_id = "";
	}
	
	//alert('post_initiative_id = ' + post_initiative_id + ' : initiative_id = ' + initiative_id);
	
	if (post_initiative_id != "")	
	{
//		post_initiative_id = $F('post_initiative_id');
		initiative_id = $F('initiative_id');
		//alert("Communication&company_id=" + company_id + "&post_id=" + post_id + "&post_initiative_id=" + post_initiative_id +  "&initiative_id=" + initiative_id + "&source_tab=" + source_tab);
		top.loadTab(4,"Communication&company_id=" + company_id + "&post_id=" + post_id + "&post_initiative_id=" + post_initiative_id +  "&initiative_id=" + initiative_id + "&source_tab=" + source_tab, true);
	}
	else //if (post_initiative_id == "" || initiative_id == "")
	{
		//alert ("No post initiative available");
		var initiative_list = top.$("initiative_list");
		var initiative_name = initiative_list.options[initiative_list.selectedIndex].text;
		var initiative_id = top.$F("initiative_list");
		
		if (initiative_id == "" || initiative_id == 0)
		{
			alert ("No initiative selected. Please choose a default client initiative");
			return false;
		}
	
		if (confirm("There is currently no client record for " + initiative_name + " for this post. Do you wish to add a client record and log a communication?"))
		{
			//initiative_id = top.$F("initiative_list");
			post_initiative_id = "";
			responders_total_count = 1;
			responders_in_progress_count = 1;
			addPostInitiative(post_id, initiative_id, source_tab);
		}
		else
		{
			return;
		}
	}
	
	

}

function openInfoPane(src)
{
	//alert('openInfoPane(' + src + ')');

	if (ifr_info == undefined)
	{
		popupWindow(src);
	}
	else
	{
		iframeLocation(ifr_info, src);
	}
}

/* --- Ajax calling functions --- */
function getCompanyDetail(company_id, post_id, post_initiative_id, done)
{
	var ill_params = new Object;
	ill_params.item_id = company_id;
	ill_params['post_id'] = post_id;
	ill_params['post_initiative_id'] = post_initiative_id;
	
	getAjaxData("AjaxCompany", "", "get_company_detail", ill_params, "Saving...", null, done);
}

function refreshCompanyDetail(company_id, post_id)
{
	// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
	page_isloaded = true;
	iframeLocation(		top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&refresh_number=true&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
	top.loadTab(5,"");
	colln.goToPostId(post_id);
}

function setCompanyTelephoneTps(company_id)
{
	var ill_params = new Object;
	ill_params.item_id = company_id;
	ill_params.telephone_tps = 0;
	getAjaxData("AjaxCompany", "", "update_telephone_tps", ill_params, "Saving...");
}

function getPostDetail(post_id)
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = post_id;
	//set the field/value pairs - eg telephone/0121....
	ill_params['blank'] = "";
	//ill_params['post_initiative_id'] = post_initiative_id;
	//ill_params['initiative_id'] = initiative_id;
	getAjaxData("AjaxPost", "", "get_post_detail", ill_params, "Saving...");
}

function getPostInitiativeDetail(object_id, initiative_id, post_initiative_id)
{
//	alert("At start of getPostInitiativeDetail(" + object_id + " : " + initiative_id + " : " + post_initiative_id + ")");
	if (object_id)
	{
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.item_id = object_id;
		//set the field/value pairs - eg telephone/0121....
		ill_params['post_initiative_id'] = post_initiative_id;
		ill_params['initiative_id'] = initiative_id;
		getAjaxData("AjaxPost", "", "get_post_initiative_detail", ill_params, "Saving...");
	}
	else
	{
		responders_in_progress_count --;
		makePostInitiativeDetail(null);
	}
}

function getCompanyInitiativesDetail(object_id, initiative_id, post_initiative_id)
{
//	alert("At start of getCompanyInitiativesDetail(" + object_id + ", " + initiative_id + ", post_initiative_id " + post_initiative_id + ")");
	if (object_id)
	{
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.item_id = object_id;
		//set the field/value pairs - eg telephone/0121....
		ill_params['post_initiative_id'] = post_initiative_id;
		ill_params['initiative_id'] = initiative_id;
		getAjaxData("AjaxPost", "", "get_company_initiatives_detail", ill_params, "Saving...");
	}
}

function getWorkspaceNotes(object_id, initiative_id, post_initiative_id)
{
	src = "index.php?cmd=WorkspaceNotes&post_id=" + object_id + "&initiative_id=" + initiative_id + "&post_initiative_id=" + post_initiative_id; 	
iframeLocation(	ifr_notes, src);
	return;	
}

function getWorkspaceNotesByCompany(company_id, initiative_id)
{
	src = "index.php?cmd=WorkspaceNotes&company_id=" + company_id + "&initiative_id=" + initiative_id; 	
iframeLocation(	ifr_notes, src);
	return;	
}

function displayPostInitiativeProjectRefs()
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = $F('post_initiative_id');
	//set the field/value pairs - eg telephone/0121....
	//ill_params['post_initiative_id'] = post_initiative_id;
	//ill_params['initiative_id'] = initiative_id;
	getAjaxData("AjaxPostInitiative", "", "display_project_refs", ill_params, "Saving...");
}

function addPostInitiative(post_id, initiative_id, source_tab)
{
	// check if this initiative_id already exists in the post_initiatives dropdown list
	if (post_id == "")
	{
		alert ("Error: No post selected");
		return false;
	}
	
	if (initiative_id == "" || initiative_id == 0)
	{
		alert ("No initiative selected. Please choose a default client initiative");
		return false;
	}
	
	var initiative_list = top.$("initiative_list");
	var initiative_name = initiative_list.options[initiative_list.selectedIndex].text;
	if (confirm("Please confirm you wish to add a record to this post for the initiative '" + initiative_name + "'"))
	{
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.item_id = "";
		//set the field/value pairs - eg telephone/0121....
		ill_params['post_id'] = post_id;
		ill_params['initiative_id'] = initiative_id;
		ill_params['source_tab'] = source_tab;
		
		if (source_tab)
		{
			getAjaxData("AjaxPostInitiative", "", "add_post_initiative_with_call", ill_params, "Saving...");
		}
		else
		{
			getAjaxData("AjaxPostInitiative", "", "add_post_initiative", ill_params, "Saving...");
		}
	}
}

function getCharacteristics(type, id)
{
	var ill_params = new Object;
	ill_params.item_id = id;
	ill_params['type'] = type;
	ill_params['div_id'] = 'popup_characteristics';
	getAjaxData("AjaxObjectCharacteristic", "", "get_object_characteristics", ill_params, "Saving...");
}

function logNonEffective()
{
	if ($('company_id'))	
	{
		company_id = $F('company_id');	
	}
	else // should never get here, but just in case.....
	{
		alert ("Error - No company available. Please report this error to the system administrator");
		return;
	}
	
	if ($('post_id'))	
	{
		post_id = $F('post_id');	
	}
	else // should never get here, but just in case.....
	{
		alert ("Error - No post available. Please report this error to the system administrator");
		return;
	}
	
	
/*	// confirm there is a non-blank contact name for this post 
	if ($('post_contact_title'))	
	{
		post_contact_title = $F('post_contact_title') 
	}	
	else
	{
		post_contact_title = '';
	}
*/	
	if ($('post_contact_first_name'))	
	{
		post_contact_first_name = $F('post_contact_first_name');
	}	
	else
	{
		post_contact_first_name = '';
	}
	
	if ($('post_contact_surname'))	
	{
		post_contact_surname = $F('post_contact_surname');
	}	
	else
	{	
		post_contact_surname = '';
	}
			
	if (post_contact_first_name.length == 0 || post_contact_surname == 0)
	{
		alert ("A communication cannot be logged as the contact name for this post is not complete. Please edit the name before proceding");
		return;
	}
		
	if ($('post_initiative_id'))	
	{
		//if ($F('post_initiative_id') != '')
		//{
		//	alert("Here");
			post_initiative_id = $F('post_initiative_id');
			
		//	alert('initiative_id= ' + initiative_id + ' : post_initiative_id = ' + post_initiative_id);
		//} 
	}
	else
	{
		post_initiative_id = "";
	}
	
	initiative_id = "";
	
	if ($('initiative_id'))	
	{
		//if ($F('post_initiative_id') != '')
		//{
		//	alert("Here");
			initiative_id = $F("initiative_id");
			
		//	alert('initiative_id= ' + initiative_id + ' : post_initiative_id = ' + post_initiative_id);
		//} 
	}
	else
	{
		initiative_id = "";
	}
	
	
	if (post_initiative_id == "" || initiative_id == "")
	{
		var initiative_list = top.$("initiative_list");
		var initiative_name = initiative_list.options[initiative_list.selectedIndex].text;
		var initiative_id = top.$F("initiative_list");
		
		if (initiative_id == "" || initiative_id == 0)
		{
			alert ("No initiative selected. Please choose a default client initiative");
			return false;
		}
		
		if (confirm("There is currently no client record for " + initiative_name + " for this post. Do you wish to add a client record and log a communication?"))
		{
			//initiative_id = top.$F("initiative_list");
			post_initiative_id = "";
		}
		else
		{
			return;
		}
	}
	
	if (initiative_id == '')
	{
		alert("No client initiative selected");
		return false;
	}
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.post_id = post_id;
	ill_params.initiative_id = initiative_id;
	ill_params.post_initiative_id = post_initiative_id;

	getAjaxData("AjaxCommunication", "", "log_non_effective", ill_params, "Saving...")
}

function deleteLastCall()
{
	if ($('post_initiative_id'))	
	{
		if (confirm("Are you sure you wish to delete the last telephone communication? This will delete ALL associated meetings, information requests and actions"))
		{
			post_initiative_id = $F('post_initiative_id')
			var ill_params = new Object;
			ill_params.post_initiative_id = post_initiative_id;
		
			getAjaxData("AjaxPostInitiative", "", "delete_last_call", ill_params, "Saving...", true)
		}
	}
	else
	{
		alert("No post initiative available - unable to execute function");
	}
}

/* --- Ajax return data handlers --- */
function ParentCompany(data)
{
	switch (data.cmd_action)
	{
		case 'updateName':
			$('edit_parent_company_name_' + data.item_id).innerHTML = data.name;
			break;
	}
}

function AjaxCompany(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		//alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		switch (t.cmd_action)
		{
			case "update_name":
				$("edit_company_name").innerHTML  = t.name;
				break;
			case "update_telephone":
				$("edit_company_telephone").innerHTML  = t.telephone;
				$("edit_company_telephone").style.display  = 'none';
				break;
			case "update_additional_info":
				$("edit_additional_info").innerHTML  = t.additional_info;
				break;
			case "update_telephone_tps":
				var msg = '[Make '
				if (t.telephone_tps)
				{
					msg += ' Non ';
					$('edit_company_telephone').style.color = 'red';
				}
				else
				{
					$('edit_company_telephone').style.color = 'black';
				}
				msg += 'TPS]';
				$('sp_telephone_tps').innerHTML = msg;
				new Effect.Pulsate($('edit_company_telephone'), {duration: 5});
				break;	
			case "update_website":
				$("edit_website").innerHTML  = t.website;
				break;
			case "get_company_detail":
				makeCompanyDetail(t.company_detail['template']);
				updateCount(t.item_id);
				last_post_class_change_id = "";
				loadPost(t.company_detail['post_id'], null, t.company_detail['post_initiative_id']);
				if(t.company_detail.parent_companies.length){
					t.company_detail.parent_companies.forEach(function (p) {
						new Ajax.InPlaceEditor('edit_parent_company_name_' + p.id, '', {externalControl: 'img_edit_parent_company_name_' + p.id, ill_cmd: 'ParentCompany', ill_cmd_action: 'updateName', ill_item_id: p.id, ill_field: 'name'});
					});
				}
				var_edit_company_name = null;
				var_edit_company_name = new Ajax.InPlaceEditor('edit_company_name', '', {externalControl: 'img_edit_company_name', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_name', ill_item_id: t.item_id, ill_field: 'name'});
				var_edit_website = null;
				var_edit_website = new Ajax.InPlaceEditor('edit_website', '', {externalControl: 'img_edit_website', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_website', ill_item_id: t.item_id, ill_field: 'website'});
				var_edit_company_telephone = null;
				var_edit_company_telephone = new Ajax.InPlaceEditor('edit_company_telephone', '', {externalControl: 'img_edit_company_telephone', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_telephone', ill_item_id: t.item_id, ill_field: 'telephone'});
				sortables_init();
				var_popup_posts = new Popup('popup_posts','popup_posts_link',{position:'20,240',trigger:'click',duration:'0.25',show_delay:'100'});
				$('company_note_count').innerHTML = t.company_detail['company_note_count'];
				return;
				break;
			case "get_workspace_notes_by_company":
				note_inplace_editor_ids.length = 0;
				makeWorkspaceNotes(t.workspace_notes['template']);
				break;
			case "dial_number_request":
				var tempHtml = '<p style="text-align: center;padding-top:5px;"><span style="'+t.data[0].style+'">'+t.data[0].number+'</span>' + " "+ t.data[0].tpsStatus+"</p>";
				tempHtml+= '<p style="text-align: center;"><a href="voispeed:'+t.data[0].number+'" style="'+t.data[0].style+'">[ DIAL ]</a>' + '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:$(\'popup_doCall\').popup.hide();">[ Cancel ]</a>'+"</p>";
				document.getElementById('call-to-dialog').innerHTML = tempHtml;
				$('popup_doCall').popup.show();
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxPost(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		
		switch (t.cmd_action)
		{
			case "edit_telephone_1":
				$("edit_telephone_1").innerHTML  = t.telephone_1;
				$("edit_telephone_1").style.display  = 'none';
				break;

			case "edit_email":
				$("edit_email").innerHTML  = t.email;
				break;

				case "edit_telephone_mobile":
				$("edit_telephone_mobile").innerHTML  = t.telephone_mobile;
				break;
			
			case "edit_linked_in":
				$("edit_linked_in").innerHTML  = t.linked_in;
				break;
                
			case "update_additional_info":
				$("edit_post_additional_info").innerHTML  = t.additional_info;
				break;
				
			case "get_post_detail":
				makePostDetail(t.post_detail['template']);
				//ned to reset field 'post_id'??
				$("post_id").value = t.item_id;
				var_edit_telephone_1 = null;
				var_edit_telephone_1 = new Ajax.InPlaceEditor('edit_telephone_1', '', {externalControl: 'img_edit_telephone_1', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_telephone_1', ill_item_id: t.item_id, ill_field: 'telephone_1'});
				var_edit_email = null;
				var_edit_email = new Ajax.InPlaceEditor('edit_email', '', {externalControl: 'img_edit_email', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_email', ill_item_id: t.item_id, ill_field: 'email'});
				var_edit_telephone_mobile = null;
				var_edit_telephone_mobile = new Ajax.InPlaceEditor('edit_telephone_mobile', '', {externalControl: 'img_edit_telephone_mobile', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_telephone_mobile', ill_item_id: t.item_id, ill_field: 'telephone_mobile'});
				var_edit_linked_in = null;
				var_edit_linked_in = new Ajax.InPlaceEditor('edit_linked_in', '', {externalControl: 'img_edit_linked_in', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_linked_in', ill_item_id: t.item_id, ill_field: 'linked_in'});
				$('post_note_count').innerHTML = t.post_detail['post_note_count'];
                var_post_edit_additional_info = new Ajax.InPlaceEditor('edit_post_additional_info', '', {externalControl: 'post_edit_additional_info', ill_cmd: 'AjaxPost', ill_cmd_action: 'update_additional_info', ill_item_id: t.item_id, ill_field: 'additional_info'});
				break;
			
			case "get_post_initiative_detail":
				if (makePostInitiativeDetail(t.post_initiative_detail) == true) // need to do this test else if ['template'] is blank then javascript fails on creation of popups 
				{
					// meeting alert
					if (t.post_initiative_detail['meetings'] > 0)
					{
						$('meeting_alert').show();
						new Effect.Pulsate($('meeting_alert'), {duration: 5});
					}
					
					// actions alert
					if (t.post_initiative_detail['actions'] > 0 || t.post_initiative_detail['overdue_actions'] > 0)
					{
						$('action_alert').show();
						new Effect.Pulsate($('action_alert'), {duration: 5});
					}
					
					// company do not call alert
					if (t.post_initiative_detail['company_do_not_call'])
					{
						$('company_do_not_call_alert').show();
						new Effect.Pulsate($('company_do_not_call_alert'), {duration: 20});
					}
					else
					{
						$('company_do_not_call_alert').hide();
					}
		
					// sortable tables initiatilsation
					sortables_init();
					
				}
				
				loadCompanyCharacteristics();
				
				break;
				
			case "get_company_initiatives_detail":
				if (makeCompanyInitiativesDetail(t.company_initiatives_detail) == true)
				{
					sortables_init();
				}
				break;
				
			case "get_workspace_notes":
				note_inplace_editor_ids.length = 0;
				makeWorkspaceNotes(t.workspace_notes['template']);
				//clear out anything in the ifr_info to make sure we don't have mismatched records.
				//NOTE: this is rather crude - it would be nicer to devise a system which knew the level of
				//information being displayed in ifr_info (eg company or post) and only cleared ifr_info if
				//there was a change to the relevant parent or object (eg a different company or post was displayed.
				//ifr_info.location.href="app/view/templates/blank.html";
//				openInfoPane("index.php?cmd=ObjectCharacteristics&id=" + $F("company_id") + "&type=company");
//				openInfoPane("index.php?cmd=ObjectTieredCharacteristics&parent_object_type=app_domain_Company&parent_object_id=" + $F('company_id')); 						
				break;
		
			default:
				alert("No cmd_action specified2");
				break;
		}
	}
}

function AjaxPostInitiative(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_post_initiative":
				if (t.currently_exists == true)
				{
					alert("A record for this post/client initiative already exists - no action taken");
				}
				else
				{
					loadPost(t.post_id, t.initiative_id, t.item_id);
				}
				break;
			case "add_post_initiative_with_call":
				top.loadTab(4,"Communication&company_id=" + $F('company_id') + "&post_id=" + t.post_id + "&post_initiative_id=" + t.item_id +  "&initiative_id=" + t.initiative_id + "&source_tab=" + t.source_tab, true);
				loadPost(t.post_id, t.initiative_id, t.item_id);
				break;
			case "display_project_refs":
				$('div_project_refs').innerHTML = t.return_data['template'];
				break;
			case "delete_last_call":
				if (t.return_data['result'] == true)
				{
					// alert("Call deleted - will now refresh page using (" + t.return_data['post_id'] + ", " + t.return_data['initiative_id'] + ", " + t.return_data['post_initiative_id']);
					loadPost(t.return_data['post_id'], t.return_data['initiative_id'], t.return_data['post_initiative_id']);
				}
				else
				{
					alert("Error - " + t.return_data['feedback']);
				}
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCommunication(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "update_note":
				$("edit_note_" + t.item_id).innerHTML = t.note;
				break;
			case "update_comment":
				$("edit_comment_" + t.item_id).innerHTML = t.comment;
				break;
			case "log_non_effective":
				if (t.result)
				{
					alert("Non-effective call logged");
					//alert(t.post_initiative_id);
					loadPost(t.post_id, t.initiative_id, t.post_initiative_id);
				}
				else
				{
					alert("Non-effective call could not be logged as no post initiative id could be found");
				}				
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}
		
function AjaxObjectCharacteristic(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		
		switch (t.cmd_action)
		{
			case "get_object_characteristics":
				var div = $(t.div_id);
				div.innerHTML = t.characteristic_screen;
				break;
			
			default:
				alert("No cmd_action specified2");
				break;
		}
	}
}
/* --- End of Ajax return data handlers --- */


function makeCompanyDetail(data)
{
	if (data == null)
	{
		data = "No company information found";
	}
	$("div_company").innerHTML = data;
}

function makePostDetail(data)
{
	$("div_post").innerHTML = data;
}

function makePostInitiativeDetail(data)
{
	if (data == null || data['template'] == "")
	{
		$("div_post_initiative").innerHTML = "No client initiatives exist for this post";
		return false;
	}
	else
	{
		$("div_post_initiative").innerHTML = data['template'];
//		if (data['meeting_count'] > 0)
//		{
//			new Effect.Pulsate($('meeting_alert'), {duration: 5});
//		}
		return true;
	}
}

function makeCompanyInitiativesDetail(data)
{
	if (data == null || data['template'] == "")
	{
		$("div_company_initiatives").innerHTML = "No posts contacted for this client";
		return false;
	}
	else
	{
		$("div_company_initiatives").innerHTML = data['template'];
		return true;	
	}
}

function makeWorkspaceNotes(data)
{
//	alert("makeWorkspaceNotes");
	if (data == null)
	{
		data = "No notes";
	}
	$("div_notes_screen").innerHTML = data;
}

function loadPost(post_id, initiative_id, post_initiative_id)
{
// Loads WorkspacePost.tpl, WorkspacePostInitiative.tpl, WorkspaceCompanyInitiatives.tpl and WorkspaceNotes.tpl
// screens via Ajax calls. The initiative_id param is passed so that we can default to records for the client
// initiative selected by the user in the main frame.

	//alert("In load post....post_id = " + post_id);
	if (post_id == "" || post_id == null)
	{
		$("div_post").innerHTML = "";
		$("div_post_initiative").innerHTML = "";
		$("div_company_initiatives").innerHTML = "";
		$("div_notes_screen").innerHTML = "";
		// hide post menus
		$("div_post_menu").style.display = "none";
		$("div_post_initiative_menu").style.display = "none";
		//openInfoPane("index.php?cmd=ObjectCharacteristics&id=" + $F("company_id") + "&type=company");
		loadCompanyCharacteristics();
		return;	
	}
	
	// display post menus
	$("div_post_menu").style.display = "block";
	$("div_post_initiative_menu").style.display = "block";
  document.getElementById('post_list_by_post').value = post_id;
	
	// if no post_initiative_id is supplied then need to check initiative_id is set correctly
	if (post_initiative_id== "" || post_initiative_id == null)	
	{
		if (initiative_id == "" || initiative_id == null)
		{
	
	// initiative_id = top.$F("initiative_list");
	
			if ($("initiative_id"))
			{
				initiative_id = $F("initiative_id");
				if (initiative_id == '')
				{
					initiative_id = top.$F("initiative_list");
				}
			}
			else
			{
				initiative_id = top.$F("initiative_list");
			}

		
		}
	}
	
	initiative_id = top.$F("initiative_list");

//	alert("initiative id = " + initiative_id + " post_id = " + post_id);
	responders_total_count = 3;
	responders_in_progress_count = 3;
	
//	alert('initiative_id = ' + initiative_id);
	getPostDetail(post_id);
//	alert("post_id = " + post_id + " : initiative_id = " + initiative_id + ": post_initiative_id = " + post_initiative_id);
	
	// although at this point we could in detect the post_initiative_id from the preceding function, because
	// the functions make asynchronous ajax calls, we don't know if the preceding function will have returned
	// by the time we call the next function (getCompanyInitiativesDetail). And, by not waiting for it to 
	// return we should in theory have a quicker return since we can get on with the next function before the 
	// preceding one has finished.
	getCompanyInitiativesDetail(post_id, initiative_id, post_initiative_id);
//	getWorkspaceNotes(post_id, initiative_id, post_initiative_id);
	getPostInitiativeDetail(post_id, initiative_id, post_initiative_id);

//	alert("In LoadPost: post_id = " + post_id + ", initiative_id = " + initiative_id + ", post_initiative_id = " + post_initiative_id);
	getWorkspaceNotes(post_id, initiative_id, post_initiative_id);
//	alert("Done getWorkspaceNotes");
	
}

function loadAssociatedPosts(post_id, post_initiative_id)
{
// Note: we pass in post id in the functions below because they are going to the AjaxPost command which expects a post id as the mandatory item_id param
// We pass null for the second param (initiative_id) because we know at this stage what the post_initiative_id will be
//	alert("HEre");

	responders_total_count = 3;
	responders_in_progress_count = 3;
	
	getWorkspaceNotes(post_id, null, post_initiative_id);
	getCompanyInitiativesDetail(post_id, null, post_initiative_id);
	getPostInitiativeDetail(post_id, null, post_initiative_id);
}

function makeSelected(objSel, default_value)
{
//	alert(objSel + " | " + default_value);
	objSel = $(objSel);
	
	var nodes = $A(objSel.options);
	
	nodes.each(function(node)
		{
			if (node.value.toString() == default_value.toString())
			{
				node.selected = true;
			}
		});
}

function doMenuItem(location, id)
{
//	alert('doMenuItem(' + location + ', ' + id + ')');
	if (location == "")
	{
		return false;
	}
	else
	{
		switch (location)
		{
			case 'CompanyCreate':
				location = location;
				break;
			
			case 'CompanyTags':
				location = "WorkspaceTags&parent_object_type=app_domain_Company&parent_object_id=" + $F("company_id") + "&category_id=2";
				break;
				
			case 'CompanyNoteCreate':
				location = location + '&company_id=' + $F('company_id');
				break;
			
			case "CompanyDelete":
				location = location + "&id=" + $F("company_id");
				break;
			
			case "PostEdit":
				location = location + "&id=" + $F("post_id");
				break;
			
			case "PostEditLocation":
				location = location + "&id=" + $F("post_id");
				break;
			
			case "PostCreate":
				location = location + "&company_id=" + $F("company_id");
				break;
			
			case "PostDelete":
				location = location + "&id=" + $F("post_id") + "&company_id=" + $F("company_id") + "&source_tab=" + tab_id;
				break;
			
			case "PostNoteCreate":
				location = location + "&post_id=" + $F("post_id") + "&source_tab=" + tab_id;
				break;	
				
			case "PostAgencyTags":
				location = "WorkspaceTags&parent_object_type=app_domain_Post&parent_object_id=" + $F("post_list_by_post") + "&category_id=4";
				break;
			
			case "PostTags":
				location = "WorkspaceTags&parent_object_type=app_domain_Post&parent_object_id=" + $F("post_list_by_post") + "&category_id=2";
				break;
				
			default:
				alert('Invalid location: ' + location);
				return;
		}
		iframeLocation(ifr_info, "index.php?cmd=" + location);
	}

	// Reset menu after a second
	setTimeout("$('company_menu').selectedIndex = 0", 1000);
	setTimeout("$('post_menu').selectedIndex = 0", 1000);
}

function doPulsate(element)
{
	var t = new Effect.Pulsate(element, {duration: 5});
}



// this variable holds the id of the post which last had its background changed to highlighted. We need this so we can set the selected row
// back to normal when a new post is selected
var last_post_class_change_id = "";
	
function highlightSelectedPost(post_id)
{
	//set the background of the selected row
	if (post_id != "")
	{
		$("tr_post_" + post_id).className="current";
	}
	
	if (last_post_class_change_id != "" && last_post_class_change_id != post_id)
	{
		$("tr_post_" + last_post_class_change_id).className="";
	}
	
	last_post_class_change_id = post_id;
}

// this variable holds the id of the information request which last had its background changed to highlighted. We need this so we can set the selected row
// back to normal when a new post is selected
var last_information_request_class_change_id = "";
	
function highlightSelectedInformationRequest(information_request_id)
{
	// Set the background of the selected row
	if (information_request_id != "")
	{
		$("tr_information_request_" + information_request_id).className="current";
	}
	
	if (last_information_request_class_change_id != "" && last_information_request_class_change_id != information_request_id)
	{
		$("tr_information_request_" + last_information_request_class_change_id).className="";
	}
	
	last_information_request_class_change_id = information_request_id;
}

function goToHash(hash_container, hash_location)
{
	var mypos = findPos($(hash_location));
	$(hash_container).scrollTop = mypos[1]-300;
}

function findPos(obj) 
{
	var curleft = curtop = 0;
	if (obj.offsetParent)
	{
		curleft = obj.offsetLeft;
		curtop = obj.offsetTop;
		while (obj = obj.offsetParent)
		{
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		}
	}
	return [curleft,curtop];
}

function togglePopup(item_type)
{
	if ($('popup_' + item_type) && $('popup_icon_' + item_type))
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
			
			switch (item_type)
			{
				case 'characteristics':
					popup_div.innerHTML = "Please wait - information loading...";
					//alert($F('company_id'));
					getCharacteristics('post_initiative', $F('post_initiative_id'));
					break;
				
				default:
					break;
			}
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
	else
	{
		alert("Action not available");
	}
}


function sendMail(email_container) 
{
	if (!$(email_container)) 
	{
		alert("No valid email container");
	}
iframeLocation(	document, "mailto:" + $(email_container).innerHTML);
}

function hideMenus()
{
	if ($F("post_id") == "")
	{
		$("div_post_menu").style.display = "none";
		$("div_post_initiative_menu").style.display = "none";
	}
}

function checkTPS(number,element)
{
	var ill_params = new Object;
	ill_params.number = number.toString();
	ill_params.refresh_number = "true";
	if(element.rel == "post"){
		document.getElementById('popup_doCall').style="height: 70px; width: 320px;left: 395px;top: 415px; width: 320px; overflow-x: hidden; overflow-y: auto;";
		var_popup_doCall = new Popup('popup_doCall','popup_doCall_link',{position:'395,415',trigger:'click',duration:'0.25',show_delay:'100'});
	}
	else{
		document.getElementById('popup_doCall').style="height: 70px; width: 320px;left: 395px;top: 110px; width: 320px; overflow-x: hidden; overflow-y: auto;";
		var_popup_doCall = new Popup('popup_doCall','popup_doCall_link',{position:'395,110',trigger:'click',duration:'0.25',show_delay:'100'});
	}
	getAjaxData("AjaxCompany", "getNumberInfo", "dial_number_request", ill_params, "Loading...",true);
}