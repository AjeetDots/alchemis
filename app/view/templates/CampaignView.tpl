{include file="header2.tpl" title="Campaign View"}

<script language="JavaScript" type="text/javascript">
{literal}

function submitform(pressbutton, form_name)
{
	var frm = $(form_name);
	frm.task.value = pressbutton;
	try 
	{
		frm.onsubmit();
	}
	catch(e)
	{}
	frm.submit();
}


function submitbutton(pressbutton, form_name)
{
	if (pressbutton == 'save') 
	{
		if (validation())
		{
			submitform(pressbutton, form_name);
		}
		return;
	}
}

function getInfoFrame()
{
	return document.getElementById('ifr_info');
}

function openInfoPane(src)
{
	var infoFrame = getInfoFrame();
	if (!infoFrame)
	{
		popupWindow(src);
	}
	else
	{
		iframeLocation(infoFrame, src);
	}
}

function addClient()
{
	openInfoPane("index.php?cmd=ClientCreate");
}

function addNbm()
{
	var user_id = $F('user_id');
	if (user_id == 0)
	{
		alert("No user selected");
		return false;
	}
	
	var user_alias = $F('user_alias');
	if (user_alias == '')
	{
		alert("Call name must be completed");
		return false;
	}
	
	var sel = $('user_id');
	user_name = sel.options[sel.selectedIndex].text;
	
	var ill_params = new Object;
	ill_params.user_id = user_id;
	ill_params.campaign_id = $F('campaign_id');
	ill_params.user_name = user_name;
	ill_params.user_alias = user_alias;
	ill_params.user_email = $F('user_email');
	
	getAjaxData("AjaxCampaignNbm", "", "add_nbm", ill_params, "Saving...")
}

function deleteNbm(id, name)
{
	if (confirm("Confirm delete '" + name + "' from this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignNbm", "", "delete_nbm", ill_params, "Saving...")
	}
}

function makeLeadNbm(id, name)
{
	if (confirm("Confirm '" + name + "' is to be the lead NBM for this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignNbm", "", "make_lead_nbm", ill_params, "Saving...")
	}
}

function reinstateNbm(id, name)
{
	if (confirm("Confirm '" + name + "' is to be reinstated for this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignNbm", "", "reinstate_nbm", ill_params, "Saving...")
	}
}

// this array used to hold the inPlaceEditor objects for nbm call names
nbm_call_names_inplace_editor_ids = new Array();

function editNbmCallName(id)
{
	if (nbm_call_names_inplace_editor_ids.length > 0)
	{
		var exists = nbm_call_names_inplace_editor_ids.find( function(editor){
				return (editor == id);
			});
		
		if (exists)
		{
			// in place editor for this id already exists
			var in_place_editor = nbm_call_names_inplace_editor_ids.indexOf(id);
		}
		else
		{
			// create new one
			var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_call_name_' + id , '', {externalControl: 'img_edit_nbm_call_name_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_call_name', ill_item_id: id, ill_field: 'user_alias'});
			nbm_call_names_inplace_editor_ids.push(in_place_editor);
		}
	}
	else
	{	
		var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_call_name_' + id , '', {externalControl: 'img_edit_nbm_call_name_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_call_name', ill_item_id: id, ill_field: 'user_alias'});
		nbm_call_names_inplace_editor_ids.push(in_place_editor);
	}
	
		
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
}

// this array used to hold the inPlaceEditor objects for nbm user email addresses
nbm_email_inplace_editor_ids = new Array();

function editNbmEmail(id)
{
	if (nbm_email_inplace_editor_ids.length > 0)
	{
		var exists = nbm_email_inplace_editor_ids.find( function(editor){
				return (editor == id);
			});

		if (exists)
		{
			// in place editor for this id already exists
			var in_place_editor = nbm_email_inplace_editor_ids.indexOf(id);
		}
		else
		{
			// create new one
			var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_email_' + id , '', {externalControl: 'img_edit_nbm_email_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_email', ill_item_id: id, ill_field: 'user_email'});
			nbm_email_inplace_editor_ids.push(in_place_editor);
		}
	}
	else
	{	
		var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_email_' + id , '', {externalControl: 'img_edit_nbm_email_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_email', ill_item_id: id, ill_field: 'user_email'});
		nbm_email_inplace_editor_ids.push(in_place_editor);
	}
		
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
	
}

//this array used to hold the inPlaceEditor objects for nbm user deactived date
nbm_deactivated_date_inplace_editor_ids = new Array();

function editNbmDeactivatedDate(id)
{
	if (nbm_deactivated_date_inplace_editor_ids.length > 0)
	{
		var exists = nbm_deactivated_date_inplace_editor_ids.find( function(editor){
				return (editor == id);
			});

		if (exists)
		{
			// in place editor for this id already exists
			var in_place_editor = nbm_deactivated_date_inplace_editor_ids.indexOf(id);
		}
		else
		{
			// create new one
			var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_deactivated_date_' + id , '', {externalControl: 'img_edit_nbm_deactivated_date_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_deactivated_date', ill_item_id: id, ill_field: 'deactivated_date'});
			nbm_deactivated_date_inplace_editor_ids.push(in_place_editor);
		}
	}
	else
	{	
		var in_place_editor = new Ajax.InPlaceEditor('edit_nbm_deactivated_date_' + id , '', {externalControl: 'img_edit_nbm_deactivated_date_' + id, ill_cmd: 'AjaxCampaignNbm', ill_cmd_action: 'update_nbm_deactivated_date', ill_item_id: id, ill_field: 'deactivated_date'});
		nbm_deactivated_date_inplace_editor_ids.push(in_place_editor);
	}
		
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
	
}

// this array used to hold the inPlaceEditor objects for nbm user email addresses
sector_inplace_editor_ids = new Array();

function editSectorWeighting(id)
{
	if (sector_inplace_editor_ids.length > 0)
	{
		var exists = sector_inplace_editor_ids.find( function(editor){
				return (editor == id);
			});
		
		if (exists)
		{
			// in place editor for this id already exists
			var in_place_editor = sector_inplace_editor_ids.indexOf(id);
		}
		else
		{
			// create new one
			var in_place_editor = new Ajax.InPlaceEditor('edit_sector_weighting_' + id , '', {externalControl: 'img_sector_weighting_' + id, ill_cmd: 'AjaxCampaignSector', ill_cmd_action: 'update_sector_weighting', ill_item_id: id, ill_field: 'weighting'});
			sector_inplace_editor_ids.push(in_place_editor);
		}
	}
	else
	{	
		var in_place_editor = new Ajax.InPlaceEditor('edit_sector_weighting_' + id , '', {externalControl: 'img_sector_weighting_' + id, ill_cmd: 'AjaxCampaignSector', ill_cmd_action: 'update_sector_weighting', ill_item_id: id, ill_field: 'weighting'});
		sector_inplace_editor_ids.push(in_place_editor);
	}
		
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
}

function editLine(target_id)
{
	$('span_calls_' + target_id).style.display = 'none';
	$('span_effectives_' + target_id).style.display = 'none';
	$('span_meets_set_' + target_id).style.display = 'none';
	$('span_meets_attended_' + target_id).style.display = 'none';
	$('span_opportunities_' + target_id).style.display = 'none';
	$('span_wins_' + target_id).style.display = 'none';
	
	$(target_id + '-calls').style.display = 'block';
	$(target_id + '-effectives').style.display = 'block';
	$(target_id + '-meets_set').style.display = 'block';
	$(target_id + '-meets_attended').style.display = 'block';
	$(target_id + '-opportunities').style.display = 'block';
	$(target_id + '-wins').style.display = 'block';
	
	var form = $('dataForm');
	var btn = $('btn_edit_' + target_id);
	if (btn.value.slice(0,4) == 'Edit')
	{
		btn.value = 'Save Line';
		var do_exit = true;
	}
	else
	{
		btn.value = 'Saving...';
		btn.disabled = true;
	}	
	
	var texts = form.getInputs('text'); // -> only text inputs
	var data_to_save = new Array();
	var results = [];
	var validation_nan = 0;
	var validation_negative = 0;
	
	texts.each(function(item) 
		{
			if (item.id.slice(0,target_id.length) == target_id)
			{
				if (item.value.strip() == '' || isNaN(item.value))
				{
//					 //results.push('One or more items is not a number;\n');
					 validation_nan ++;
				}
				else
				{
					if (Number(item.value) < 0)
					{
//						//results.push('One or more items is less than zero;\n');
						validation_negative ++;
					}
				}
			
				// add it to the array of items to pass back to save
				if (btn.disabled)
				{
					data_to_save.push([item.id + '-' + item.value]);
				}
			}
		});
	
	if (do_exit)
	{
		return false;
	}
	else
	{
		var msg = '';
	
		if 	(validation_nan > 0)
		{
			msg += 'One or more items is not a number;\n'
		}
		if 	(validation_negative > 0)
		{
			msg += 'One or more items is less than zero;\n'
		}
		
		if (msg != '')
		{
			msg = 'Please correct the following errors\n\n' + msg;
			alert(msg);
			btn.value = 'Save Line';
			btn.disabled = false;
			return false;
		}
	}
	
//	alert(data_to_save.toJSON());
	
	var ill_params = new Object;
	ill_params.target_id = target_id;
	ill_params.form_data = data_to_save;
	
	getAjaxData("AjaxCampaignView", "", "save_target_line", ill_params, "Saving...");
}

function deleteCompanyDoNotCall(id, name)
{
	if (confirm("Confirm delete company '" + name + "' from the Do Not Call list for this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignCompanyDoNotCall", "", "delete_company_do_not_call", ill_params, "Saving...")
	}
}

function addDiscipline()
{
	var discipline_id = $F('discipline_id');
	if (discipline_id == 0)
	{
		alert("No discipline selected");
		return false;
	}
	
	var sel = $('discipline_id');
	discipline_name = sel.options[sel.selectedIndex].text;
	
	var ill_params = new Object;
	ill_params.discipline_id = discipline_id;
	ill_params.campaign_id = $F('campaign_id');
	ill_params.discipline_name = discipline_name;
	
	getAjaxData("AjaxCampaignDiscipline", "", "add_discipline", ill_params, "Saving...")
}

function deleteDiscipline(id, name)
{
	if (confirm("Confirm delete discipline'" + name + "' from the disciplines list for this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignDiscipline", "", "delete_discipline", ill_params, "Saving...")
	}
}

function addSector()
{
	var sector_id = $F('sector_id');
	if (sector_id == 0)
	{
		alert("No sector selected");
		return false;
	}
	
	var sel = $('sector_id');
	sector_name = sel.options[sel.selectedIndex].text;
	
	var ill_params = new Object;
	ill_params.sector_id = sector_id;
	ill_params.campaign_id = $F('campaign_id');
	ill_params.weighting = $F('sector_weighting');
	ill_params.sector_name = sector_name;
	
	getAjaxData("AjaxCampaignSector", "", "add_sector", ill_params, "Saving...")
}

function deleteSector(id, name)
{
	if (confirm("Confirm delete sector '" + name + "' from the sectors list for this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignSector", "", "delete_sector", ill_params, "Saving...")
	}
}

function addRegion()
{
	var region_id = $F('region_id');
	if (region_id == 0)
	{
		alert("No region selected");
		return false;
	}
	
	var sel = $('region_id');
	region_name = sel.options[sel.selectedIndex].text;
	
	var ill_params = new Object;
	ill_params.region_id = region_id;
	ill_params.campaign_id = $F('campaign_id');
	ill_params.region_name = region_name;
	
	getAjaxData("AjaxCampaignRegion", "", "add_region", ill_params, "Saving...")
}

function deleteRegion(id, name)
{
	if (confirm("Confirm delete '" + name + "' from this campaign?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxCampaignRegion", "", "delete_region", ill_params, "Saving...")
	}
}

/* --- Ajax return data handlers --- */
function AjaxCampaignView(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "save_target_line":
//				alert(t.target_id + ' --- ' + t.return_data);
				replaceRowHtml('tbl_targets_list', t.target_id, t.return_data);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCampaignNbm(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_nbm":
				if (t.is_valid)
				{
					addNewLine('tbl_nbm_list', t.campaign_nbm_id, t.row_html);
				}
				else
				{
					alert ('This user has already been assigned to this campaign. A user can only be assigned once to a campaign.');
				}
				break;
			case "delete_nbm":
				replaceRowHtml('tbl_nbm_list', t.item_id, t.row_html);
				break;
			case "reinstate_nbm":
				replaceRowHtml('tbl_nbm_list', t.item_id, t.row_html);
				break;
			case "make_lead_nbm":
				replaceRowHtml('tbl_nbm_list', t.item_id_old, t.row_html_old);
				replaceRowHtml('tbl_nbm_list', t.item_id, t.row_html_new);
				break;
			case "update_nbm_call_name":
				$("edit_nbm_call_name_" + t.item_id).innerHTML = t.user_alias;
				break;
			case "update_nbm_email":
				$("edit_nbm_email_" + t.item_id).innerHTML = t.user_email;
				break;
			case "update_nbm_deactivated_date":
				$("edit_nbm_deactivated_date_" + t.item_id).innerHTML = t.deactivated_date;
				break;	
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCampaignCompanyDoNotCall(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "delete_company_do_not_call":
				deleteRow('tbl_company_do_not_call_list', 'tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCampaignDiscipline(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_discipline":
//				alert(t.row_html);
				addNewLine('tbl_discipline_list', t.item_id, t.row_html);
				break;
			case "delete_discipline":
				deleteRow('tbl_discipline_list', 'tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCampaignSector(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_sector":
//				alert(t.row_html);
				addNewLine('tbl_sector_list', t.item_id, t.row_html);
				break;
			case "update_sector_weighting":
				$("edit_sector_weighting_" + t.item_id).innerHTML = t.weighting;
				break;
			case "delete_sector":
				deleteRow('tbl_sector_list', 'tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxCampaignRegion(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_region":
//				alert(t.row_html);
				addNewLine('tbl_region_list', t.campaign_region_id, t.row_html);
				break;
			case "delete_region":
				deleteRow('tbl_region_list', 'tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function addNewLine(table_name, id, html)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", "tr_" + id);
	row.innerHTML = html;
}

function replaceRowHtml(table_name, item_id, html)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == "tr_" + item_id)
		{
			tbl.rows[i].innerHTML = html;
			break;
		}
	}
}

function deleteRow(table_name, item_id)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="66%" valign="top">
			<div class="cfg" style="overflow-x: hidden; overflow-y: y:auto">
				<form action="index.php?cmd=CampaignView" method="post" id="adminForm" name="adminForm" autocomplete="off">
					<input type="hidden" name="task" value="" />

					<select name="client_options" id="client_options" style="width: 175px">
						{html_options options=$client_options selected=$client_selected}
					</select> 
					<input type="button" value="Go" onclick="javascript:submitform('', 'adminForm');" />
					&nbsp;&nbsp;|&nbsp;&nbsp;
					<a href="#" onclick="javascript:addClient()"><img src="{$APP_URL}app/view/images/icons/table_add.png" alt="Add Client" title="Add a new client" /></a>
				</form>		
				{if $client_selected != ''}
        {if isset($campaign) && $campaign}
        <input type="hidden" id="campaign_id" name="campaign_id" value="{$campaign->getId()}">
        {/if}
				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td colspan="3"{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Campaign View</td>
					</tr>
					
					<tr valign="top">
						<td width="50%" class="lcol">
							{if $session_user->hasPermission('permission_admin_client_campaigns')} 
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Client Details</span></summary>
									<div class="moofx-slider content">
										<iframe id="ifr_client_details" name="ifr_client_details" src="index.php?cmd=ClientDetails&id={$client->getId()}" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
									</div>
								</details>
							</div>
							
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Campaign Details</span></summary>
									<div class="moofx-slider content">
										<iframe id="ifr_campaign_details" name="ifr_campaign_details" src="index.php?cmd=CampaignDetails&id={$client->getId()}" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
									</div>
								</details>
							</div>
							{/if}
							{if $session_user->hasPermission('permission_admin_client_campaigns') || $session_user->hasPermission('permission_admin_clients_nbm_admin')}
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Alchemis Team Details</span></summary>
									<div class="moofx-slider content">
										<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
											<tr class="hdr">
												<td>
													NBMs &nbsp;&nbsp;|&nbsp;&nbsp;
													{if $session_user->hasPermission('permission_admin_client_campaigns')}
													<span style="text-align: right"><strong>{$campaign_nbms|@count}</strong> record{if $campaign_nbms|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
													<input type="button" id="add_new_nbm" name="add_new_nbm" value="Add New NBM" onclick="javascript:$('div_new_nbm').show();$('user_id').focus();" />
													<div id="div_new_nbm" style="display: none; margin-top: 10px">
														<form id="form_new_nbm" name="form_new_nbm" action="" method="post">
															<table class="adminlist">
																<tr>
																	<th style="vertical-align: top; width: 20%">Name</th>
																	<td>
																		<select name="user_id" id="user_id" style="width: 250px">
																			{html_options options=$user_options}
																		</select>
																	</td>
																</tr>
																<tr>
																	<th style="vertical-align: top; width: 20%">Call Name</th>
																	<td>
																		<input type="text" name="user_alias" id="user_alias" value="" style="width: 250px" />
																	</td>
																</tr>
																<tr>
																	<th style="vertical-align: top; width: 20%">Email address</th>
																	<td>
																		<input type="text" name="user_email" id="user_email" value="" style="width: 250px" />
																	</td>
																</tr>
																<tr>
															</table>
																	
															<div>
																<input type="button" id="cancel_nbm" name="cancel_nbm" value="Cancel" onclick="javascript:$('form_new_nbm').reset(); $('div_new_nbm').hide();" />&nbsp;
																<input type="button" id="reset_nbm" name="reset_nbm" value="Reset" onclick="javascript:$('form_new_nbm').reset(); return false;" />&nbsp;
																<input type="button" id="save_nbm" name="save_nbm" value="Save" onclick="javascript:addNbm();" />
															</div>
							
														</form>
													</div>
													{/if}
												</td>
											</tr>
							
											<tr valign="top">
												<td>
							
													<table id="tbl_nbm_list" class="adminlist">
														<thead>
															<tr>
																<th style="width: 3%">ID</th>
																<th>Name</th>
																<th>Lead NBM</th>
																<th>Call name</th>
																<th>Email Alias</th>
																<th>Deactivated Date</th>
																<th style="width: 10%; text-align: center">&nbsp;</th>
															</tr>
														</thead>
														
														<!-- NOTE: Any changes to lines in the following foreach loop also need to made in html_CampaignNbmLine.tpl -->
														{foreach name=nbms_loop from=$campaign_nbms item=nbm}
														<tr id="tr_{$nbm->getId()}">
															<td style="text-align: center">{$nbm->getId()}</td>
															<td {if $nbm->isActive() == false}style="text-decoration:line-through"{/if}>{$nbm->getName()}</td>
															<td style="text-align: center">{if $nbm->getIsLeadNbm()}<img src="{$APP_URL}app/view/images/icons/tick.png" alt="Lead NBM" title="Lead NBM" />{/if}</td>
															<td>
																<span {if $nbm->isActive() == false}style="text-decoration:line-through"{/if} id="edit_nbm_call_name_{$nbm->getId()}">{$nbm->getUserAlias()}</span>
																{if $nbm->isActive()}
																{if $session_user->hasPermission('permission_admin_client_campaigns') || ($nbm->getUserId() == $session_user->getId() && $session_user->hasPermission('permission_admin_clients_nbm_admin'))}
																<a id="editCallNameBtn_{$nbm->getId()}" title="Edit NBM call name" href="#" onclick="javascript:editNbmCallName({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/group_edit.png" alt="Change NBM call name" title="Change NBM call name" /></a>&nbsp;
																{/if}
																{/if}		
															</td>
															<td {if $nbm->isActive() == false}style="text-decoration:line-through"{/if}>
																<span {if $nbm->isActive() == false}style="text-decoration:line-through"{/if} id="edit_nbm_email_{$nbm->getId()}">{$nbm->getUserEmail()}</span>
																{if $nbm->isActive()}
																{if $session_user->hasPermission('permission_admin_client_campaigns') || ($nbm->getUserId() == $session_user->getId() && $session_user->hasPermission('permission_admin_clients_nbm_admin'))}
																<a id="editEmailBtn_{$nbm->getId()}" title="Edit NBM email address" href="#" onclick="javascript:editNbmEmail({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Change NBM email" title="Change NBM email" /></a>&nbsp;
																{/if}
																{/if}		
															</td>
															<td>
																{if $nbm->isActive() == false}
																{if $session_user->hasPermission('permission_admin_client_campaigns')}
																<span id="edit_nbm_deactivated_date_{$nbm->getId()}">{$nbm->getDeactivatedDate()}</span>
																<a id="editDeactivatedDateBtn_{$nbm->getId()}" title="Edit NBM deactivation date" href="#" onclick="javascript:editNbmDeactivatedDate({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Change NBM deactivation date" title="Change NBM deactivation date" /></a>&nbsp;
																{else}
																	&nbsp;
																{/if}
																{/if}	
															</td>
															<td style="text-align: left; vertical-align: middle">
																{if $nbm->isActive() && !$nbm->getIsLeadNbm()}
																{if $session_user->hasPermission('permission_admin_client_campaigns')}
																<a id="makeLeadNbm_{$nbm->getId()}" title="Make Lead NBM" href="#" onclick="javascript:makeLeadNbm({$nbm->getId()}, '{$nbm->getName()|escape:'quotes'}');return false;"><img src="{$APP_URL}app/view/images/icons/key_add.png" alt="Make Lead NBM" title="Make Lead NBM" /></a>&nbsp;
																<a id="deleteBtn_{$nbm->getId()}" title="Remove NBM from campaign" href="#" onclick="javascript:deleteNbm({$nbm->getId()}, '{$nbm->getName()|escape:'quotes'}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove NBM from campaign" title="Remove NBM from campaign" /></a>&nbsp;
																{elseif !$nbm->isActive()}
																<a id="reinstateBtn_{$nbm->getId()}" title="Reinstate NBM" href="#" onclick="javascript:reinstateNbm({$nbm->getId()}, '{$nbm->getName()|escape:'quotes'}');return false;"><img src="{$APP_URL}app/view/images/icons/arrow_redo.png" alt="Reinstate NBM" title="Reinstate NBM" /></a>&nbsp;
																{/if}
																{/if}
															</td>
														</tr>
														{/foreach}
													</table>
							
												</td>
											</tr>
										</table>
									</div>
								</details>
							</div>
							{/if}
							{if $session_user->hasPermission('permission_admin_client_campaigns') || $session_user->hasPermission('permission_admin_clients_nbm_admin')}
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Campaign Disciplines ({$campaign_disciplines_count}</strong> record{if $campaign_disciplines_count != 1}s{/if})</span></summary>
									<div class="moofx-slider content">
										<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
											<tr class="hdr">
												<td>
													Disciplines&nbsp;&nbsp;|&nbsp;&nbsp;
													<input type="button" id="add_new_discipline" name="add_new_discipline" value="Add New Discipline" onclick="javascript:$('div_new_discipline').show();$('discipline_id').focus();" />
													<div id="div_new_discipline" style="display: none; margin-top: 10px">
														<form id="form_new_discipline" name="form_new_discipline" action="" method="post">
															<table class="adminlist">
																<tr>
																	<th style="vertical-align: top; width: 20%">Name</th>
																	<td>
																		<select name="discipline_id" id="discipline_id" style="width: 250px">
																			{html_options options=$discipline_options}
																		</select>
																	</td>
																</tr>
															</table>
							
															<div>
																<input type="button" id="cancel_discipline" name="cancel_discipline" value="Cancel" onclick="javascript:$('form_new_discipline').reset(); $('div_new_region').hide();" />&nbsp;
																<input type="button" id="reset_discipline" name="reset_discipline" value="Reset" onclick="javascript:$('form_new_discipline').reset(); return false;" />&nbsp;
																<input type="button" id="save_discipline" name="save_discipline" value="Save" onclick="javascript:addDiscipline();" />
															</div>
							
														</form>
													</div>
							
												</td>
											</tr>
							
											<tr valign="top">
												<td>
							
													<table id="tbl_discipline_list" class="adminlist">
														<thead>
															<tr>
																<th style="width: 3%">ID</th>
																<th>Discipline</th>
																<th style="width: 10%; text-align: center">&nbsp;</th>
															</tr>
														</thead>
														
														<!-- NOTE: Any changes to lines in the following foreach loop also need to made in html_CampaignDisciplineLine.tpl -->
														{foreach name=disciplines_loop from=$campaign_disciplines item=discipline}
														<tr id="tr_{$discipline->getId()}">
															<td style="text-align: center">{$discipline->getId()}</td>
															<td>{$discipline->getDisciplineName()}</td>
															<td style="text-align: center; vertical-align: middle">
																<a id="deleteBtn_{$discipline->getId()}" title="Remove discipline from campaign" href="#" onclick="javascript:deleteDiscipline({$discipline->getId()}, '{$discipline->getDisciplineName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove discipline from campaign" title="Remove discipline from campaign" /></a>&nbsp;
															</td>
														</tr>
														{/foreach}
														
													</table>
							
												</td>
											</tr>
										</table>
									</div>
								</details>
							</div>
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Targets</span></summary>
									<div class="moofx-slider content">
										{if isset($campaign) && $campaign}
											{if $campaign->getStartYearMonth()}
											<a href="#" onclick="javascript:openInfoPane('index.php?cmd=CampaignTargetCreate&amp;campaign_id={$campaign->getId()}'); return false;">Add additional 12 months</a>
											{else}
											<p>New campaign targets cannot be added until a campaign start date has been specified</p>
											{/if}
										{/if}
										<form action="" method="post" id="dataForm" name="dataForm" autocomplete="off">
											<input type="hidden" name="task" value="" />
											<table id="tbl_targets_list" class="adminlist">
												<thead>
													<tr>
														<th style="width: 5%">Year/Month</th>
														<th style="width: 15%">Calls</th>
														<th style="width: 15%">Effectives</th>
														<th style="width: 15%">Set</th>
														<th style="width: 15%">Attended</th>
														<th style="width: 15%">Opportunities</th>
														<th style="width: 15%">Wins</th>
														<th style="width: 5%; text-align: center">&nbsp;</th>
													</tr>
												</thead>
												{foreach name=targets_loop from=$campaign_targets item=target}
												<tr id="tr_{$target->getId()}">
													<td>{$target->getYearMonth()}</td>
													<td style="text-align: center">
														<span id="span_calls_{$target->getId()}">{$target->getCalls()}</span>
														<input type="text" value="{$target->getCalls()}" style="display: none; text-align: center" id="{$target->getId()}-calls" name="{$target->getId()}-calls" />
													</td>
													<td style="text-align: center">
														<span id="span_effectives_{$target->getId()}">{$target->getEffectives()}</span>
														<input type="text" value="{$target->getEffectives()}" style="display: none; text-align: center" id="{$target->getId()}-effectives" name="{$target->getId()}-effectives" />
													</td>
													<td style="text-align: center">
														<span id="span_meets_set_{$target->getId()}">{$target->getMeetingsSet()}</span>
														<input type="text" value="{$target->getMeetingsSet()}" style="display: none; text-align: center" id="{$target->getId()}-meets_set" name="{$target->getId()}-meets_set" />
													</td>
													<td style="text-align: center">
														<span id="span_meets_attended_{$target->getId()}">{$target->getMeetingsAttended()}</span>
														<input type="text" value="{$target->getMeetingsAttended()}" style="display: none; text-align: center" id="{$target->getId()}-meets_attended" name="{$target->getId()}-meets_attended" />
													</td>
													<td style="text-align: center">
														<span id="span_opportunities_{$target->getId()}">{$target->getOpportunities()}</span>
														<input type="text" value="{$target->getOpportunities()}" style="display: none; text-align: center" id="{$target->getId()}-opportunities" name="{$target->getId()}_opportunities" />
													</td>
													<td style="text-align: center">
														<span id="span_wins_{$target->getId()}">{$target->getWins()}</span>
														<input type="text" value="{$target->getWins()}" style="display: none; text-align: center" id="{$target->getId()}-wins" name="{$target->getId()}-wins" />
													</td>
													<td style="text-align: center; vertical-align: middle">
														<input type="button" id="btn_edit_{$target->getId()}" value="Edit Line" onclick="javascript:editLine('{$target->getId()}');" />
													</td>
												</tr>
												{/foreach}
											</table>
										</form>
									</div>
								</details>
							</div>
							
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>'Do Not Call' Companies</span></summary>
									<div class="moofx-slider content">
										<div style="height:400px; overflow-x: hidden; overflow-y: y:auto">
										<table id="tbl_company_do_not_call_list" class="adminlist">
											<thead>
												<tr>
													<th style="width: 3%">ID</th>
													<th>Name</th>
													<th>Address</th>
													<th style="width: 10%; text-align: center">&nbsp;</th>
												</tr>
											</thead>
											<!-- NOTE: Any changes to lines in the following foreach loop also need to made in html_CampaignNbmLine.tpl -->
											{foreach name=ccdnc_loop from=$campaign_companies_do_not_call item=ccdnc}
											<tr id="tr_{$ccdnc.id}">
												<td style="text-align: center">{$ccdnc.company_id}</td>
												<td>{$ccdnc.company_name}</td>
												<td>{$ccdnc.site_address}</td>
												<td style="text-align: center; vertical-align: middle">
													<a id="deleteBtn_{$ccdnc.id}" title="Remove company from campaign Do Not Call list" href="#" onclick="javascript:deleteCompanyDoNotCall({$ccdnc.id}, '{$ccdnc.company_name}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove company from campaign Do Not Call list" title="Remove company from campaign Do Not Call list" /></a>&nbsp;
												</td>
											</tr>
											{/foreach}
										</table>
										</div>
									</div>
								</details>
							</div>
									
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Target Sectors</span></summary>
									<div class="moofx-slider content">
										<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
											<tr class="hdr">
												<td>
													Sectors&nbsp;&nbsp;|&nbsp;&nbsp;
													<span style="text-align: right"><strong>{$nbms|@count}</strong> record{if $nbms|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
													<input type="button" id="add_new_sector" name="add_new_sector" value="Add New Sector" onclick="javascript:$('div_new_sector').show();$('user_id').focus();" />
													<div id="div_new_sector" style="display: none; margin-top: 10px">
														<form id="form_new_sector" name="form_new_sector" action="" method="post">
															<table class="adminlist">
																<tr>
																	<th style="vertical-align: top; width: 20%">Name</th>
																	<td>
																		<select name="sector_id" id="sector_id" style="width: 250px">
																			{html_options options=$campaign_sector_options}
																		</select>
																	</td>
																</tr>
																<tr>
																	<th style="vertical-align: top; width: 20%">Weighting</th>
																	<td>
																		<input type="text" name="sector_weighting" id="sector_weighting" value="" style="width: 250px" />
																	</td>
																</tr>
															</table>
							
															<div>
																<input type="button" id="cancel_sector" name="cancel_sector" value="Cancel" onclick="javascript:$('form_new_sector').reset(); $('div_new_sector').hide();" />&nbsp;
																<input type="button" id="reset_sector" name="reset_sector" value="Reset" onclick="javascript:$('form_new_sector').reset(); return false;" />&nbsp;
																<input type="button" id="save_sector" name="save_sector" value="Save" onclick="javascript:addSector();" />
															</div>
							
														</form>
													</div>
							
												</td>
											</tr>
							
											<tr valign="top">
												<td>
							
													<table id="tbl_sector_list" class="adminlist">
														<thead>
															<tr>
																<th style="width: 3%">ID</th>
																<th style="text-align: left;">Sector</th>
																<th style="width: 10%; text-align: left;">Weighting</th>
																<th style="width: 10%; text-align: center">&nbsp;</th>
															</tr>
														</thead>
														
														<!-- NOTE: Any changes to lines in the following foreach loop also need to made in html_CampaignSectorLine.tpl -->
														{foreach name=sector_loop from=$campaign_sectors item=sector}
														<tr id="tr_{$sector->getId()}">
															<td >{$sector->getId()}</td>
															<td >{$sector->getSectorName()}</td>
															<td >
																<span id="edit_sector_weighting_{$sector->getId()}">{$sector->getWeighting()}</span>&nbsp;
																<a id="editSectorWeightingBtn_{$sector->getId()}" title="Edit weighting" href="#" onclick="javascript:editSectorWeighting({$sector->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/group_edit.png" alt="Change sector weighting" title="Change sector weighting" /></a>
															</td>
															<td style="text-align: center; vertical-align: middle">
																<a id="deleteBtn_{$sector->getId()}" title="Remove sector from campaign" href="#" onclick="javascript:deleteSector({$sector->getId()}, '{$sector->getSectorName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove sector from campaign" title="Remove sector from campaign" /></a>&nbsp;
															</td>
														</tr>
														{/foreach}
													</table>
							
												</td>
											</tr>
										</table>
									</div>
								</details>
							</div>
							
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Target Regions</span></summary>
									<div class="moofx-slider content">
										<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
											<tr class="hdr">
												<td>
													Regions&nbsp;&nbsp;|&nbsp;&nbsp;
													<span style="text-align: right"><strong>{$nbms|@count}</strong> record{if $regions|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
													<input type="button" id="add_new_region" name="add_new_sector" value="Add New Region" onclick="javascript:$('div_new_region').show();$('region_id').focus();" />
													<div id="div_new_region" style="display: none; margin-top: 10px">
														<form id="form_new_region" name="form_new_region" action="" method="post">
															<table class="adminlist">
																<tr>
																	<th style="vertical-align: top; width: 20%">Name</th>
																	<td>
																		<select name="region_id" id="region_id" style="width: 250px">
																			{html_options options=$region_options}
																		</select>
																	</td>
																</tr>
															</table>
							
															<div>
																<input type="button" id="cancel_region" name="cancel_region" value="Cancel" onclick="javascript:$('form_new_region').reset(); $('div_new_region').hide();" />&nbsp;
																<input type="button" id="reset_region" name="reset_region" value="Reset" onclick="javascript:$('form_new_region').reset(); return false;" />&nbsp;
																<input type="button" id="save_region" name="save_region" value="Save" onclick="javascript:addRegion();" />
															</div>
							
														</form>
													</div>
							
												</td>
											</tr>
							
											<tr valign="top">
												<td>
							
													<table id="tbl_region_list" class="adminlist">
														<thead>
															<tr>
																<th style="width: 3%">ID</th>
																<th>Region</th>
																<th style="width: 10%; text-align: center">&nbsp;</th>
															</tr>
														</thead>
														
														{* NOTE: Any changes to lines in the following foreach loop also need to made in html_CampaignRegionLine.tpl *}
														{foreach name=regions_loop from=$campaign_regions item=region}
														<tr id="tr_{$region->getId()}">
															<td style="text-align: center">{$region->getId()}</td>
															<td>{$region->getName()}</td>
															<td style="text-align: center; vertical-align: middle">
																<a id="deleteBtn_{$region->getId()}" title="Remove region from campaign" href="#" onclick="javascript:deleteRegion({$region->getId()}, '{$region->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove region from campaign" title="Remove region from campaign" /></a>&nbsp;
															</td>
														</tr>
														{/foreach}
													</table>
							
												</td>
											</tr>
										</table>
									</div>
								</details>
							</div>
							{/if}
				
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Report Summaries</span></summary>
									<div class="moofx-slider content">
										{if isset($campaign) && $campaign}
										<iframe id="ifr_summary_reports" name="ifr_summary_reports" src="index.php?cmd=CampaignReportSummaries&campaign_id={$campaign->getId()}" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
										{/if}
									</div>
								</details>
							</div>
									
							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span{if isset($client) && $client && !$client->getIsCurrent()} style="color:red;"{/if}>Document Library</span></summary>
									<div class="moofx-slider content">
										{if isset($campaign) && $campaign}
										<iframe id="ifr_documents" name="ifr_documents" src="index.php?cmd=CampaignDocuments&campaign_id={$campaign->getId()}" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
										{/if}
									</div>
								</details>
							</div>

							<div id="content-pane" class="pane-sliders">
								<details class="panel accordion-panel">
									<summary class="moofx-toggler title"><span>Gui settings</span></summary>
									<div class="moofx-slider content">
										{if isset($campaign) && $campaign}
										<iframe id="ifr_documents" name="ifr_documents" src="index.php?cmd=CampaignSettings&campaign_id={$campaign->getId()}" scrolling="no" border="0" frameborder="no" style="height: 300px; width: 100%; "></iframe>
										{/if}
									</div>
								</details>
							</div>
							{/if}


							
						</td>
					</tr>
				</table>
				
			</div>
		</td>
	
		<td width="33%" valign="top">
			<div style="height:730px;">
				<iframe id="ifr_info" name="ifr_info" src="" scrolling="no" border="0" frameborder="no" style="height: 720px; width: 100%; "></iframe>
			</div>
		</td>
	</tr>
</table>
<div id="notification" style="display: none;"><img src="{$APP_URL}app/view/images/ajax_loader.gif" width="16" height="16" align="absmiddle">&nbsp;Working...</div>

<style>
{literal}
.accordion-panel { margin-bottom: 2px; }
.accordion-panel summary { cursor: pointer; list-style: none; }
.accordion-panel summary::-webkit-details-marker { display: none; }
.accordion-panel[open] summary { margin-bottom: 0; }
/* Override global .pane-sliders .content { display:none } so open panel content is visible */
.accordion-panel[open] .moofx-slider,
.accordion-panel[open] .content { display: block !important; height: auto !important; }
{/literal}
</style>
{include file="footer.tpl"}
