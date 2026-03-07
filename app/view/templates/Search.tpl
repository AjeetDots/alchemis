{include file="header.tpl" title="Search"}

<script language="JavaScript" type="text/javascript"> 
{literal}

	/**
	 * @param search_item
	 */
function doSearch(search_item)
{
	top.responderFadeIn();
	var href = "index.php?cmd=SearchResults&search_type=" + search_item.name + "&search_param=" +  encodeURIComponent(search_item.value);
	iframeLocation(iframe1, href);
}

function doCompanySearch(search_item)
{
	top.responderFadeIn();
	var href = 'index.php?cmd=ParentCompany&action=search' + search_item.name + '&query=' + encodeURIComponent(search_item.value);
	iframeLocation(iframe1, href);
}

function doInitiativeCompanySearch()
{
	var company_name = $F("initiative_company_start");
	var initiative_id = $F("initiative_list");
	
iframeLocation(	iframe1, "index.php?cmd=SearchResults&search_type=company_initiative&search_param=" + company_name + "&search_param_1=" + initiative_id);
}
    
// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(6))
{
	top.parent.tab_colln.add(6);
}

function showProjectRefs()
{
	var t = top.$("initiative_list");
	$("span_project_ref_client").innerHTML = 'Project refs for: ' + t.options[t.selectedIndex].text;
	$("span_project_ref_client_1").innerHTML = t.options[t.selectedIndex].text;
	$("span_loading").style.display="";
	alert($F(t));
	getClientInitiativeProjectRefs($F(t));
}

/* --- Ajax calling functions --- */
function getClientInitiativeProjectRefs(client_initiative_id)
{
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = client_initiative_id;
	//must have minimum of one ill_params - if none, use ill_params.blank=""
	ill_params.blank = "";
	
	getAjaxData("AjaxClientInitiative", "", "get_project_ref_tags", ill_params, "Saving...")
}

function markCompaniesDoNotCall()
{
	ifr = $("iframe1");
	if (iframe1.location == "about:blank")
	{
		alert("You must load at least one search result in order to create projects");
		return;
	}
	else
	{
		if (top.$F("initiative_list") == 0)
		{
			alert("You must select an campaign against which to mark these companies as 'do not call'");
			return;
		}
		
		frm = iframe1.contentWindow.$('frm_search_results');
		
		inputs = frm.getInputs('checkbox');
		submit_inputs = [];
		inputs.each(function(item) {
		  if (item.id.substr(0, 12) == 'chk_company_' && item.checked)
		  {
		  	submit_inputs.push(item);
		  }
		});
		
		if (submit_inputs.length == 0)
		{
			alert("You have not selected any companies from the results");
			return;
		}
		
		var t = Form.serializeElements(submit_inputs, true);
		
		// set the selected item checkboxes to blank		
		submit_inputs.each(function(item) {
			item.checked = false;
		});
		
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.item_id = "";
		//must have minimum of one ill_params - if none, use ill_params.blank=""
		ill_params.form_data = t;
		ill_params.campaign_id = top.$F("initiative_list");
			
		getAjaxData("AjaxCampaignCompanyDoNotCall", "", "multiple_add_company_do_not_call", ill_params, "Saving...")
		
	}
}

function addCompanyTags()
{
	ifr = $("iframe1");
	if (iframe1.location == "about:blank")
	{
		alert("You must load at least one search result in order to add tags");
		return;
	}
	else
	{
		frm = iframe1.contentWindow.$('frm_search_results');
		
		if ($F("company_tag_value") == '')
		{
			alert("You have not entered a tag value");
			return;
		}
		
		inputs = frm.getInputs('checkbox');
		submit_inputs = [];
		inputs.each(function(item) {
		  if (item.id.substr(0, 12) == 'chk_company_' && item.checked)
		  {
		  	submit_inputs.push(item);
		  }
		});
		
		if (submit_inputs.length == 0)
		{
			alert("You have not selected any companies from the results");
			return;
		}
		
		var t = Form.serializeElements(submit_inputs, true);
		
		// set the selected item checkboxes to blank		
		submit_inputs.each(function(item) {
			item.checked = false;
		});
		
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.tag_value = $F("company_tag_value");
		//must have minimum of one ill_params - if none, use ill_params.blank=""
		ill_params.form_data = t;
			
		getAjaxData("AjaxSearch", "", "add_multiple_company_tags", ill_params, "Saving...")
		
	}
}

function createProjectRefs()
{
	alert("Here");
	
	alert($("iframe1"));
	//alert(frm);
	ifr = $("iframe1");
	alert(iframe1.location);
	
	if (iframe1.location == "about:blank")
	{
		alert("You must load at least one search result in order to create projects");
		return;
	}
	else
	{
		frm = iframe1.contentWindow.$('frm_search_results');
		var t = Form.serializeElements(frm.getInputs());
		alert(t);
		if (t == "")
		{
			alert("You cannot create project refs without selecting at least one post from the results");
			return;
		}
					
		if (top.$F("initiative_list") == 0)
		{
			alert("You must select a project ref");
			return;
		}
		
		var ill_params = new Object;
		//set item_id - the id of the object we are dealing with
		ill_params.item_id = "";
		//must have minimum of one ill_params - if none, use ill_params.blank=""
		ill_params.checkboxes = t;
		ill_params.client_initiative_id = top.$F("initiative_list");
		
//		alert($("project_refs"));
//		var c = $("project_refs");
//		var project_ref = c.options[c.selectedIndex].text;
		var project_ref = $F("project_refs");
		
		alert(project_ref);
		//return;
		
		ill_params.project_ref = project_ref;
			
		getAjaxData("AjaxSearch", "", "make_project_ref_tags", ill_params, "Saving...")
		
	}

	

}

/* --- Ajax return data handlers --- */
function AjaxClientInitiative(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "get_project_ref_tags":
//				alert(t.project_ref_html);
				var div = $("div_project_ref_html");
				div.innerHTML = t.project_ref_html;
				div.style.display = "";
				new Effect.BlindDown($('div_project_ref_text'), {duration: 0.5});
				$("span_loading").style.display = "none";
//				$("div_project_ref_text").style.display = "";
				break;
			case "make_project_ref_tags":
				alert(t.posts);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxSearch(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "make_project_ref_tags":
				alert("new_post_initiative_count = " + t.project_refs_added['new_post_initiative_count'] + "\nnew_project_ref_count = " + t.project_refs_added['new_project_ref_count']);
				break;
			case "add_multiple_company_tags":
				
				if (t.count_items_added ==0)
				{
					alert('No companies were tagged');
				}
				else
				{
					if(t.count_items_added==1)
					{
						msg = 'company was';
					}
					else
					{
						msg = 'companies were';
					}
					
					alert(t.count_items_added + ' ' + msg + ' successfully tagged');
				}
				
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
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "multiple_add_company_do_not_call":
				c = top.$('initiative_list');
				initiative = c.options[c.selectedIndex].text;
				if (t.count_items_added ==0)
				{
					alert('No companies were added to the \'Do Not Call\' list of the parent campaign for the initiative \''+ initiative + '\'');
				}
				else
				{
					if(t.count_items_added==1)
					{
						msg = 'company was';
					}
					else
					{
						msg = 'companies were';
					}
					
					alert(t.count_items_added + ' ' + msg + ' added to the \'Do Not Call\' list of the parent campaign for the initiative \''+ initiative + '\'');
				}
				
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="67%" valign="top">
			<div class="module_content" tabindex="-1">
				<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" tabindex="-1" 	frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
			</div>
		</td>
		<td width="33%" valign="top">
			<div class="cfg">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr class="hdr">
							<td>Search Parameters</td>
						</tr>
						<tr valign="top">
							<td style="padding-top: 3px">

								<div id="content-pane" class="pane-sliders">
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Company</span></h3>
										<div class="moofx-slider content">
											<form name="companySearchForm">
												<table class="ianlist">
													<tr>
														<th style="width: 20%">Name starting with</th>
														<td style="width: 75%"><input type="text" style="width: 100%" name="ByNameStartsWith" tabindex="1" class="enter-docompanysearch" auto-complete="ParentCompany" ac-value="id" ac-text="name" /></td>
														<td style="width: 5%; text-align: center">
															<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doCompanySearch(companySearchForm.ByNameStartsWith);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
														</td>
													</tr>
													<tr>
														<th style="width: 20%">Name equal to</th>
														<td style="width: 75%"><input type="text" style="width: 100%" name="ByName" tabindex="1" class="enter-docompanysearch" auto-complete="ParentCompany" ac-value="id" ac-text="name" /></td>
														<td style="width: 5%; text-align: center">
															<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doCompanySearch(companySearchForm.ByName);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
														</td>
													</tr>
												</table>
											</form>
										</div>
									</div>
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Site</span></h3>
										<div class="moofx-slider content">
											<form name="siteSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Name starting with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_start" tabindex="1" class="enter-dosearch" auto-complete="Company" ac-value="id" ac-text="name" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doSearch(siteSearchForm.company_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Name list starting with (; delimited)</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_list_start" tabindex="1" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doSearch(siteSearchForm.company_list_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												
												<tr>
													<th style="width: 20%">Name includes</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_includes" tabindex="3" class="enter-dosearch" auto-complete="Company" ac-value="id" ac-text="name" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="4" onclick="javascript:doSearch(siteSearchForm.company_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Name equal to</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_equal" tabindex="5" class="enter-dosearch" auto-complete="Company" ac-value="id" ac-text="name"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="6" onclick="javascript:doSearch(siteSearchForm.company_equal);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												{*<tr style="height:2px">
													<td colspan="4" style="border-top:1px solid #fff;">&nbsp;</td>
												</tr>*}
												<tr>
													<th style="width: 20%">Telephone starts with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_telephone_start" tabindex="7" class="enter-dosearch"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="8" onclick="javascript:doSearch(siteSearchForm.company_telephone_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Telephone includes</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_telephone_includes" tabindex="9" class="enter-dosearch"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="10" onclick="javascript:doSearch(siteSearchForm.company_telephone_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Telephone equal to</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_telephone_equal" tabindex="11" class="enter-dosearch"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="12" onclick="javascript:doSearch(siteSearchForm.company_telephone_equal);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Postcode starts with</th>
													<td style="width: 75%"><input id="postcode_start" name="postcode_start" type="text" style="width: 100%" tabindex="13" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="14" onclick="javascript:doSearch(siteSearchForm.postcode_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Postcode includes</th>
													<td style="width: 75%"><input id="postcode_includes" name="postcode_includes" type="text" style="width: 100%" tabindex="15" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="16" onclick="javascript:doSearch(siteSearchForm.postcode_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Postcode equal to</th>
													<td style="width: 75%"><input id="postcode_equal" name="postcode_equal" type="text" style="width: 100%" tabindex="17" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="18" onclick="javascript:doSearch(siteSearchForm.postcode_equal);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
	
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Contact</span></h3>
										<div class="moofx-slider content">
											<form name="contactSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 30%">Surname starting with</th>
													<td style="width: 65%"><input type="text" style="width: 100%" name="contact_surname_start" tabindex="1" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2"  onclick="javascript:doSearch(contactSearchForm.contact_surname_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 30%">Full name starting with</th>
													<td style="width: 65%"><input type="text" style="width: 100%" name="contact_fullname_start" tabindex="3" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="4" onclick="javascript:doSearch(contactSearchForm.contact_fullname_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												{*<tr>
													<th style="width: 20%">Telephone starts with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="contact_telephone_start" tabindex="5" class="enter-dosearch" /></td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="6" onclick="javascript:doSearch(contactSearchForm.contact_telephone_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>*}
											</table>
											</form>
										</div>
									</div>
	
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Initiative / Site</span></h3>
										<div class="moofx-slider content">
											<form name="initiativeSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Site name starts with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" id="initiative_company_start" name="initiative_company_start" tabindex="1" /></td>
													<td style="width: 5%; text-align: center">
														&nbsp;
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Client initiative</th>
													<td style="width: 75%">
														<select id="initiative_list" name="initiative_list" style="width:100%" tabindex="2">
															<option selected value="0">&mdash; Select Initiative &mdash;</option>
														{foreach name="result_loop" from=$client_initiatives item=result}
															<option value="{$result.initiative_id}">{$result.client_initiative_display}</option>
														{/foreach}
														</select>
													</td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="3" onclick="javascript:doInitiativeCompanySearch();"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
	
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Project Ref</span></h3>
										<div class="moofx-slider content">
											<form name="projectrefSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Project ref starting with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="project_ref_start" tabindex="1" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doSearch(projectrefSearchForm.project_ref_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Project ref includes</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="project_ref_includes" tabindex="3" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="4" onclick="javascript:doSearch(projectrefSearchForm.project_ref_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Project ref equal to</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="project_ref_equal" tabindex="5"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="6" onclick="javascript:doSearch(projectrefSearchForm.project_ref_equal);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
									
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Brand</span></h3>
										<div class="moofx-slider content">
											<form name="brandSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Company brand starting with</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_brand_start" tabindex="1" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doSearch(brandSearchForm.company_brand_start);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Company brand includes</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_brand_includes" tabindex="3" /></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="4" onclick="javascript:doSearch(brandSearchForm.company_brand_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
												<tr>
													<th style="width: 20%">Company brand equal to</th>
													<td style="width: 75%"><input type="text" style="width: 100%" name="company_brand_equal" tabindex="5"/></td>
													<td style="width: 5%; text-align: center">
														<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="6" onclick="javascript:doSearch(brandSearchForm.company_brand_equal);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
									
									{*<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Project Reference</span></h3>
										<div class="moofx-slider content">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Includes</th>
													<td style="width: 75%"><input type="text" style="width: 100%" /></td>
													<td style="width: 5%; text-align: center">
														<div class="button2-left">
															<div class="page"><a id="searchBtn_1" title="Search" onclick="#">Search</a></div>
														</div>
													</td>
												</tr>
											</table>
										</div>
									</div>

									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Postcode</span></h3>
										<div class="moofx-slider content">
											<form name="postcodeSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Includes</th>
													<td style="width: 75%"><input id="postcode_includes" name="postcode_includes" type="text" style="width: 100%" /></td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" onclick="javascript:doSearch(postcodeSearchForm.postcode_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
	
									<div class="panel">
										<h3 class="moofx-toggler title" id="cpanel-panel"><span>Brand</span></h3>
										<div class="moofx-slider content">
											<form name="brandIncSearchForm">
											<table class="ianlist">
												<tr>
													<th style="width: 20%">Includes</th>
													<td style="width: 75%"><input type="text" id="brand_includes" name="brand_includes" style="width: 100%" /></td>
													<td style="width: 5%; text-align: center">
														<a style="cursor: pointer" id="searchBtn_1" title="Search" onclick="javascript:doSearch(brandIncSearchForm.brand_includes);"><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
													</td>
												</tr>
											</table>
											</form>
										</div>
									</div>
								</div>
*}

								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Add Site</span></h3>
									<div class="moofx-slider content">
										<div id="div_add_company_html" style="display:block">
											<iframe id="ifr_add_company" name="ifr_add_company" src="index.php?cmd=CompanyCreate" scrolling="yes" border="0" frameborder="no" style="height: 500px; width: 100%; overflow-x: hidden; overflow-y: y:auto">
											</iframe>
										</div>
									</div>
								</div>

								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Add Site Do Not Call</span></h3>
									<div class="moofx-slider content">
										<div id="div_project_refs">
											<input type="button" id="btn_mark_company_do_not_call" value="Mark companies as do not call" onclick="javascript:markCompaniesDoNotCall();return false;" />
										</div>
									</div>
								</div>
								
								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Add Site Tags</span></h3>
									<div class="moofx-slider content">
									<table class="ianlist">
										<tr>
											<td style="width: 20%">Site Tag:</td>
											<td style="width: 80%"><input type="text" style="width: 100%" id="company_tag_value" name="company_tag_value" tabindex="1" /></td>
										</tr>
									</table>
									<br />
									<input type="button" id="btn_add_company_tags" value="Add tag to selected companies" onclick="javascript:addCompanyTags();return false;" />
									</div>
								</div>
								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Add Company</span></h3>
									<div class="moofx-slider content">
										<div id="div_add_parent_company_html" style="display:block">
											<iframe id="ifr_add_parent_company" name="ifr_add_company" src="index.php?cmd=ParentCompany" scrolling="yes" border="0" frameborder="no" style="height: 500px; width: 100%; overflow-x: hidden; overflow-y: y:auto">
											</iframe>
										</div>
									</div>
								</div>
							
{*								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Project refs</span></h3>
									<div class="moofx-slider content">
										<input type="button" id="btn_show_project_ref" value="Project refs" onclick="javascript:showProjectRefs();return false;" />
										<div id="div_project_refs">
											<span id="span_project_ref_client"></span>
											<br />
											<span id="span_loading" style="display:none">loading...</span>
											<div id="div_project_ref_html" style="display:none"></div>
											<div id="div_project_ref_text" style="display:none">
												<p>Use the following button to create project refs for all of the selected posts.
												<br />
												If a selected post does not contain a client record for <span style="font-weight:bold" id="span_project_ref_client_1"></span> then create a client record before
												marking with a project ref?
												<input type="checkbox" id="chk_create_client_record" /> 
												(Otherwise the post will not be project ref'd)
												<p>
												<input type="button" id="btn_mark_project_refs" value="Create Project refs" onclick="javascript:createProjectRefs();return false;" />
											</div>
										</div>
									</div>
								</div>
*}												
								<script language="JavaScript" type="text/javascript">
									init_moofx();
								</script>
								<script>
								{literal}

									(function ($) {
										$('.enter-dosearch').keyup(function (e) {
											if(e.which == 13){
												e.preventDefault();
												doSearch(this);
											}
										});

										$('.enter-docompanysearch').keyup(function (e) {
											if(e.which == 13){
												e.preventDefault();
												doCompanySearch(this);
											}
										});
									})(jQuery)

								{/literal}
								</script>
								
							</td>
						</tr>
					</table>
				</form>
			</div>

		</td>
	</tr>
</table>




{include file="footer.tpl"}