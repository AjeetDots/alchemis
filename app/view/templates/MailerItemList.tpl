{config_load file="example.conf"}

{include file="header2.tpl" title="Mailer Item List"}

<script language="JavaScript" type="text/javascript">
{literal}
function submitform(pressbutton)
{
	document.mailer_items.task.value = pressbutton;
	
	try
	{
		document.mailer_items.onsubmit();
	}
	catch(e)
	{}
	
	document.mailer_items.submit();
}

function submitbutton(pressbutton)
{
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
		return;
	}
}

function openInfoPane(src)
{
	location.href = src;
}

function addResponse(item_id)
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = item_id;
	ill_params.mailer_id = $F("mailer_id");
	getAjaxData("AjaxMailerItem", "", "get_blank_response_form", ill_params, "Saving...")
}

function editResponse(item_id)
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = item_id;
	ill_params.mailer_id = $F("mailer_id");
	getAjaxData("AjaxMailerItem", "", "edit_response", ill_params, "Saving...")
}

function saveResponses(mailer_item_id)
{
	frm = $("frm_mailer_item_" + mailer_item_id);
	var frm_data = frm.serialize(true);
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id                          = mailer_item_id;
	ill_params.form_data                        = frm_data;
	getAjaxData("AjaxMailerItem", "", "save_responses", ill_params, "Saving...")
}

var pop = null;

/* --- Ajax return data handlers --- */
function AjaxMailerItem(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "get_blank_response_form":
				//alert(t.template);
				$("log_response_" + t.item_id).innerHTML = t.template;
				
				pop = new Popup('log_response_' + t.item_id,null,{position:'auto',trigger:'click',duration:'0.25',show_delay:'100'});
				pop.show();
				return;
			case "edit_response":
				$("log_response_" + t.item_id).innerHTML = t.template;
				
				pop = new Popup('log_response_' + t.item_id,null,{position:'auto',trigger:'click',duration:'0.25',show_delay:'100'});
				pop.show();
				return;
			case "save_responses":
//				alert("In switch: " + t.return_data['result']);
				if (t.return_data['result'] == true) 
				{
					alert("Save successful");
					pop.hide()
					$("div_response_" + t.item_id).innerHTML = t.return_data['response_date'];
				}
				else
				{
					alert("Error saving - please retry");
				}
				
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

/* --- Helper functions --- */
function setActiveRow(filter_id)
{
	//set the background of the selected row
	$("tr_" + filter_id).className="current";
	
	// now set the previously selected items to a normal background
	if (last_filter_class_change_id != "" && last_filter_class_change_id != filter_id)
	{
		$("tr_" + last_filter_class_change_id).className="";
	}
	last_filter_class_change_id = filter_id;
}

function showPost(company_id, post_id)
{
	// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we dont need to highlight/navigate to anything the first time this page is loaded
	page_isloaded = true;
iframeLocation(	top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
	top.loadTab(5,"");
//	colln.goToPostId(post_id);
//	highlightSelectedRow(company_id, post_id);
}

function showCompany(company_id, mailer_item_id)
{
	// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we dont need to highlight/navigate to anything the first time this page is loaded
	goToHash('div_mailer_items', 'tr_' + mailer_item_id);
	page_isloaded = true;
iframeLocation(	top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=&initiative_id=" + top.$F("initiative_list"));
	top.loadTab(5,"");
//	alert('tr_' + mailer_item_id);
	
//	highlightSelectedRow(company_id, post_id);
}

function goToHash(hash_container, hash_location)
{
	var mypos = findPos($(hash_location));
//	alert(mypos);
	$(hash_container).scrollTop = mypos[1];
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

function selectRemoveAll()
{
	var select_all = $("chk_remove_select_all");
	var is_checked = select_all.checked;
	var form = $('mailer_items')
	var buttons = form.getInputs('checkbox')
	buttons.each(function(item) 
		{
			var s = item.name;
  			if (s.slice(0,11) == 'chk_remove_')
  				item.checked = is_checked;
  				
  			var s = item.name;
  			if (s.slice(0,15) == 'chk_despatched_' && is_checked)
  				item.checked = !is_checked;
		}
	)
}

function selectDespatchedAll()
{
	var select_all = $("chk_despatched_select_all");
	var is_checked = select_all.checked;
	var form = $('mailer_items')
	var buttons = form.getInputs('checkbox')
	buttons.each(function(item) 
		{
			var s = item.name;
  			if (s.slice(0,15) == 'chk_despatched_')
  				item.checked = is_checked;
  				
  			var s = item.name;
  			if (s.slice(0,11) == 'chk_remove_' && is_checked)
  				item.checked = !is_checked;
		}
	)
}

function selectDespatched(id)
{
	var chk_despatched = $("chk_despatched_" + id);
	
	// only unset the remove check box if the despatched checkbox has been selected - ie dont just do the inverse of the despatched checkbox
	if (chk_despatched.checked)
	{
		var chk_remove = $("chk_remove_" + id);
		chk_remove.checked = !chk_despatched.checked;
	}
	
}

function selectRemove(id)
{
	var chk_remove = $("chk_remove_" + id);
	
	// only unset the despatched check box if the remove checkbox has been selected - ie dont just do the inverse of the remove checkbox
	if (chk_remove.checked)
	{
		var chk_despatched = $("chk_despatched_" + id);
		chk_despatched.checked = !chk_remove.checked;
	}
	
}

{/literal}
</script>
<div style="height:690px;">
	<div id="div_mailer_items_menu" >
		<span id="span_menu_left" style="float: left">
			<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MailerItemCreate&mailer_id={$mailer->getId()}&initiative_id={$mailer->getClientInitiativeId()}');return false;">
				{*<img src="{$APP_URL}app/view/images/icons/email_attach.png" alt="Add Recipients" title="Add new recipients to mailer" />*}
				Add new recipients
			</a>
		</span>
		<span id="span_menu_right" style="float: right">
			<a href="#" onclick="javascript:submitbutton('save'); return false;">
				{*<img src="{$APP_URL}app/view/images/icons/email_open_image.png" alt="Process selected recipients" title="Process selected recipients" />*}
				Process selected recipients
			</a>
		</span>
		
	</div>
	<br />
	<br />
	<div id="div_mailer_items" class="cfg" style="border: solid 1px #ccc; padding: 2px; width: 100%; height: 99%; overflow-x: hidden; overflow-y: y:auto">
		<form id="mailer_items" name="mailer_items" action="" method="post">
		<input type="hidden" name="task" value="" />
		<input type="hidden" id="mailer_id" name="mailer_id" value="{$mailer->getId()}" />
		
		<table id="tbl_mailer_item_list" class="sortable" style="width:100%">
			<thead>
				<tr>
					<th style="width: 5%">ID</th>
					<th style="width: 25%; text-align: center">Company</th>
					<th style="width: 25%; text-align: center">Post</th>
					<th style="width: 25; text-align: center">Contact</th>
					<th style="width: 10%; text-align: center" class="no_sort">Despatched<input type="checkbox" id="chk_despatched_select_all" name="chk_despatched_select_all" onchange="javascript:selectDespatchedAll();return false;" /></th>
					<th style="width: 10%; text-align: center" class="no_sort">Remove<input type="checkbox" id="chk_remove_select_all" name="chk_remove_select_all" onchange="javascript:selectRemoveAll();return false;" /></th>
					<th style="width: 10%; text-align: center">Response</th>
				</tr>
			</thead>
			<tbody>
				{foreach name=mailer_itm from=$mailer_items item=mailer_item}
				<tr id="tr_{$mailer_item.id}">
					<td>{$mailer_item.id}</td>
					<td
						{if $mailer_item.company_deleted} 
							style="text-decoration:line-through;"
						>
							{$mailer_item.company_name}
						{else}
							<a id="detailsBtn_{$mailer_item.company_id}" title="Go to company '{$mailer_item.company_name}'" onclick="javascript:showCompany({$mailer_item.company_id}, {$mailer_item.id}); return false;">
								{$mailer_item.company_name}
							</a>
						{/if}
					</td>
					<td
						{if $mailer_item.post_deleted || $mailer_item.company_deleted} 
							style="text-decoration:line-through;"
						>
							{$mailer_item.job_title}
						{else}
						>
						<a id="detailsBtn_{$mailer_item.post_id}" title="Details" onclick="javascript:showPost({$mailer_item.company_id}, {$mailer_item.post_id});return false;">
							{$mailer_item.job_title}
						</a>
						{/if}
					</td>
					<td
						{if  $mailer_item.post_deleted || $mailer_item.company_deleted || is_null($mailer_item.contact)} 
							style="text-decoration:line-through;"
						{/if}
					>
					{if is_null($mailer_item.contact)}
					POST VACANT
					{else}
					{$mailer_item.contact}
					{/if}
					</td>
					<td style="text-align: center">
					{if $mailer_item.despatched_date == ""}
						<input type="checkbox" id="chk_despatched_{$mailer_item.id}" name="chk_despatched_{$mailer_item.id}" onchange="javascript:selectDespatched({$mailer_item.id});return false;" />
					{else}
						{$mailer_item.despatched_date|date_format:"`$smarty.config.format_date_short`"}
					{/if}
					</td>
					<td style="text-align: center">
					{if $mailer_item.despatched_date == ""}
						<input type="checkbox" id="chk_remove_{$mailer_item.id}" name="chk_remove_{$mailer_item.id}" onchange="javascript:selectRemove({$mailer_item.id});return false;" />
					{/if}
					</td>
					<td style="text-align: center">
					{if $mailer_item.despatched_date != ""}
						{if $mailer_item.response_date == ""}
							<div id="div_response_{$mailer_item.id}">
								<a href="#" id="a_log_response_{$mailer_item.id}" name="a_log_response_{$mailer_item.id}" onclick="javascript:addResponse({$mailer_item.id});return false;">
									Log response
								</a>
							</div>
							
						{else}
							<div id="div_response_{$mailer_item.id}">
								<a onmouseover="return overlib('<ul>{foreach name=responses from=$mailer_item.responses item=response}<li>{$response}</li>{/foreach}</ul>', VAUTO, 1, CAPTION, 'Details', CAPCOLOR, '#000000', FGCOLOR, '#fff', BGCOLOR, '#e9e9e9');" onmouseout="return nd();">{$mailer_item.response_date|date_format:"`$smarty.config.format_date_short`"}</a>
								<a href="#" id="a_edit_response_{$mailer_item.id}" name="a_edit_response_{$mailer_item.id}" onclick="javascript:editResponse({$mailer_item.id});return false;">
									Edit
								</a>
							</div>
							
						{/if}
						
							<div id="log_response_{$mailer_item.id}" class="popup" style="display: none; height: 400px; width: 500px; overflow-x: hidden; overflow-y: y:auto"></div>
					{/if}
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		</form>
	</div>
</div>
{include file="footer.tpl"}