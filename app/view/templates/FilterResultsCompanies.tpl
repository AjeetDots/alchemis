{strip}
{include file="header2.tpl" title="Search Results"}



{if $filter == null || $filter == ""}

	No filter results loaded. Please click on the <a href="#" onclick="javascript:parent.loadTab(9, 'FilterList');">Filters</a> tab to load a filter.

{else}

	<script type="text/javascript" src="{$APP_URL}app/view/templates/FilterResults.js"></script>
	

	<script language="JavaScript" type="text/javascript">

	{literal}

	// Maintain global tab collection (tab_colln) 

	// If this page has been loaded then we don't want to reload it when the tab is clicked

	if (top.parent.tab_colln !== undefined && !top.parent.tab_colln.goToValue(8))

	{

		top.parent.tab_colln.add(8);

	}

	var tab_id = 8;

	

		function openInfoPane(src)

		{

			//alert("parent.frames[0].src = " + parent.iframe5.location.href);

			if (parent.information == undefined)

			{

				//alert("Here");

				parent.popupWindow(src);

				//alert("Here");

			}

			else

			{

iframeLocation(				parent.information, src);

			}

		}

	

		function showWindow()

		{

			parent.showWindow();

		}

		

		function logNonEffective(post_id)

		{

			if (post_id == '')

			{

				alert("No post selected");

				return;

			}

			

			initiative_id = top.$F("initiative_list");

			if (initiative_id == '' || initiative_id == '0')

			{

				alert("No 'Default initiative' selected");

				return;

			}

			

			var ill_params = new Object;

			//set item_id - the id of the object we are dealing with

			ill_params.post_id = post_id;

			ill_params.initiative_id = initiative_id;

	

			getAjaxData("AjaxCommunication", "", "log_non_effective", ill_params, "Saving...")

		}



		function makeHomeScoreboard()

		{

			var ill_params = new Object;

			ill_params.item_id = "";

			// need at least one param

			ill_params['blank'] = "";

			

			getAjaxData("AjaxScoreboard", "", "get_home_scoreboard", ill_params, "Saving...")

		}



		/* --- Ajax return data handlers --- */

		function AjaxCommunication(data)

		{

			for (i = 1; i < data.length + 1; i++) 

			{

				t = data[i-1];

				switch (t.cmd_action)

				{

					case "log_non_effective":

						if (t.result)

						{

							makeHomeScoreboard();

							alert("Non-effective call logged");

						}

						break;

					

					default:

						alert("No cmd_action specified");

						break;

				}

			}

		}

		

		function AjaxScoreboard(data)

		{

			for (i = 1; i < data.length + 1; i++) 

			{

				t = data[i-1];

				switch (t.cmd_action)

				{

					case "get_home_scoreboard":

						if (t.success == true)

						{

							top.$("communication_count").innerHTML = "Calls: " + t.communication_count;

							top.$("effective_count").innerHTML = "Effectives: " + t.effective_count;

						}

						else

						{

							// do nothing - may present user with error in future? If so, how display it?

						}

						break;

					

					default:

						alert("No cmd_action specified");

						break;

				}

			}

		}		



		function showPost(company_id, post_id)

		{

			// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.

			// This need only occurs when we navigate back to the results set from the filter workspace.

			// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded

			page_isloaded = true;

			top.responderFadeIn();

			iframeLocation(top.frames["iframe_7"], "index.php?cmd=WorkspaceFilter&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list") + "&filter_id=" + $F("filter_id"));

			top.loadTab(7,"");

			colln.goToPostId(post_id);

			highlightSelectedRow(company_id, post_id);

		}

		

		function showCompany(company_id)

		{

			// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.

			// This need only occurs when we navigate back to the results set from the filter workspace.

			// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded

			page_isloaded = true;

			top.responderFadeIn();

			colln.goToCompanyId(company_id);

			var t = colln.getCurrent();

			var post_id = t.post_id;

			iframeLocation(top.frames["iframe_7"], "index.php?cmd=WorkspaceFilter&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list") + "&filter_id=" + $F("filter_id"));

			top.loadTab(7,"");

			highlightSelectedRow(company_id, post_id);

		}

		

		// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them

		// back to normal when a new company and/or post is selected

		var last_company_class_change_id = "";

		var last_post_class_change_id = "";

		

		function highlightSelectedRow(company_id, post_id, post_initiative_id)

		{

			//set the background of the selected row
			var ele = $("tr_" + company_id);
			if(ele) ele.className="current";

			if(post_id){
				ele = $("tr_post_" + post_id);
				if(ele) ele.className = "current";
			}
			

			// now set the previously selected items to a normal background

			if (last_company_class_change_id != "" && last_company_class_change_id != company_id)

			{

				ele = $("tr_post_" + last_company_class_change_id);
				if(ele) ele.className = "";

			}

			last_company_class_change_id = company_id;

			

			if (last_post_class_change_id != "" && last_post_class_change_id != post_id)

			{

				ele = $("tr_post_" + last_post_class_change_id);
				if(ele) ele.className = "";

			}

			last_post_class_change_id = post_id;

		}

		

		function goToHash(hash_location)

		{
			console.log('hash', hash_location);
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

	

	//display filter name in menu bar

	{/literal}	

	filter_name = "{$filter->getName()}";

	{literal}	

	if (filter_name.length > 60)

	{

		filter_name = filter_name.substring(0,61) + "...";

	}

	

	top.$('loaded_filter_name').innerHTML = "Current Filter:&nbsp;<strong>" + filter_name + "</strong>";

	
	//here

			

	{/literal}				

	

	// we need to set the statistics on the FilterList.tpl page

	doc = top.frames["iframe_9"].document;

	//company_count

	itm = "span_company_count_" + {$filter->getId()};

	doc.getElementById(itm).innerHTML = {$filter->getCompanyCount()};

	//post_count

	itm = "span_post_count_" + {$filter->getId()};

	doc.getElementById(itm).innerHTML = {$filter->getPostCount()};

	

	

	// set page_isloaded to false so we can check in header_js.loadTab whether we need to highlight/navigate to any lines in the results set.

	// This need only occurs when we navigate back to the results set from the filter workspace.

	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded

	page_isloaded = false;

	

	</script>

	

	<!-- height was 704px-->

	<div id="div_results" class="cfg" style="border: solid 1px #ccc; padding: 2px; width: 100%; height: 704px; overflow: auto;">

		<input type="hidden" name="filter_id" id="filter_id" value="{$filter->getId()}" />

		Results for the filter <strong>{$filter->getName()}</strong>

		<br />

		<br />

		Sites: {$filter->getCompanyCount()} &nbsp;|&nbsp;

		Posts: {$filter->getPostCount()} 

		
		{*{if $session_user->hasPermission('permission_add_bulk_ref')}*}
		&nbsp;&nbsp;&nbsp;|&nbsp;
		<a href="#" onclick="setProjectRefDropdowns();new Effect.toggle($('div_bulk_ref'), 'blind', {literal}{duration: 0.3}{/literal}); return false;">[Create bulk references]</a>
		{*{/if}*}
		<br />
		<br />
		<form id="frm_results" name="form_results" method="POST" action="index.php?cmd=AjaxTest"/>	
		<div id='div_bulk_ref' style='display: none'>
			<table style="width: 90%">
				<tr>
					<th style="width: 24%; text-align: left">Company tag</th>
					<td><input type="text" id="company_tag" name="company_tag" style="width:200px" /></td>
					<td><input type="checkbox" id="chk_select_all_company" name="chk_select_all_company" onclick="selectAll('company', 'company');"> [select all companies]</td>
					<td>
						<input type="button" value="Create site tags" onclick="javascript:submitToAjax('company')" style="width:150px" />
					</td>
				</tr>
				<tr>
					<td colspan="4"><hr></td>
				</tr>
				<tr>
					<th style="width: 24%; text-align: left">Post tag</th>
					<td><input type="text" id="post_tag" name="post_tag" style="width:200px" /></td>
					<td><input type="checkbox" id="chk_select_all_post" name="chk_select_all_post" onclick="selectAll('post', 'post');"> [select all posts]</td>
					<td>
						<input type="button" value="Create post tags" onclick="javascript:submitToAjax('post')" style="width:150px" />
					</td>
				</tr>
				<tr>
					<td colspan="4"><hr></td>
				</tr>
				<tr>
					<th style="width: 24%; text-align: left">Project ref for
						<br />
						<select id="client_list" name="client_list" onchange="javascript:ajaxGetProjectRefs()"></select>
					</th>
					<td>
						<span id="span_project_ref_html">Loading...</span>
						<input type="text" id="client_tag" name="client_tag" style="display:none; width:200px" />
						<span id="span_new_project_ref_link">&nbsp;or <a href="#" onclick="showNewProjectRef();">create new</a></span>
						<span id="span_existing_project_ref_link" style="display:none">&nbsp;or <a href="#" onclick="showNewProjectRef();">use existing</a></span> 
					</td>
					<td><input type="checkbox" id="chk_select_all_post_1" name="chk_select_all_post_1" onclick="selectAll('post_1', 'post');"> [select all posts]</td>
					<td>
						<input type="button" value="Create project refs" onclick="javascript:submitToAjax('client')" style="width:150px" />
					</td>
				</tr>
			</table>
			<br />
			
		</div>
		

		<table id="table1" class="adminlist" cellspacing="1">

			<thead>

				<tr>

					<th style="width: 4%; text-align: center">Posts</th>

					<th style="width: 10%; text-align: left">Company</th>

					<th style="width: 40%; text-align: left">Site</th>

					<th style="width: 5%">&nbsp;</th>

				</tr>

			</thead>

			<tfoot>

			</tfoot>

			<tbody>

				{assign var="company_id" value="0"}

				{assign var="company_post_count" value="0"}

				{foreach name="result_loop" from=$results item=result}

					{* if we've already used this company id then don't use it again*}

					{if $company_id != $result.id}	

						{*close off the post list from the previous company*}

						{if $company_post_count == "1"}

									</tbody>

							 	</table>

							 	<br />

							 	<a href="#" onclick="new Effect.BlindUp($('post_list_{$company_id}'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>

							 	

							 </div>

						 </td>

					</tr>

					{assign var="company_post_count" value="0"}

						{/if}

					<tr id="tr_{$result.id}" style="vertical-align:top">

						<td><a href="#" onclick="javascript:new Effect.toggle($('post_list_{$result.id}'), 'blind', {literal}{duration: 0.3}{/literal});return false; "><img style="vertical-align:middle" src="{$APP_URL}app/view/images/icons/group.png" alt="Posts" title="Number of posts at this company (as included in the filter criteria)" /> ({if $result.post_count != ""}{$result.post_count}{else}0{/if})</a></td>

						<td>{$result.parent_company}</td>

						<td>
							<a name="a_company_{$result.id}"></a>
							<span>
								<span style="float:left" id="client_{$result.id}"><strong>{$result.name}</strong></span>
								<span style="float:right"><input id="chk_company_{$result.id}" name="chk_company_{$result.id}" type="checkbox" /></span>
							</span>
							{if $result.telephone != ""}
							<br />
								<span{if $result.telephone_tps == 1} style="color:red"{/if}>{$result.telephone}</span>{*&nbsp;&nbsp;<a href="#">Dial</a>*}
							{/if}
							{if $result.website != ""}
							<br />
								<a href="{$result.website}" target="_new">{$result.website}</a>
							{/if}
							<em><br />{br_format town=$result.town postcode=$result.postcode}</em>

						</td>

						<td style="text-align: center; background-color: #F3F3F3">

							<div class="button2-left">

								<div class="page"><a id="detailsBtn_{$result.id}" title="Go to details for this site" href="#" onclick="javascript:showCompany({$result.id});return false;">Details</a></div>

							</div>

						</td>

					</tr>

						{if $result.post_id != ""}

					<tr>

						<td colspan="6" style="height: 0px; background-color: #e7e7e7;">

							<div id="post_list_{$result.id}" style="background-color: #f9f9f9; float: none; display: none; margin: 10px 10px 10px 10px">

								<table class="sortable" id="sortable_{$result.id}">

									<thead>

										<tr>

											<th>Job Title</th>

											<th>Post Holder</th>

										</tr>

									</thead>

									<tbody>

										<tr id="tr_post_{$result.post_id}">

											<td><a href="#a_post_{$result.post_id}"></a>
												<span>
													<span style="float:left">{$result.job_title}</span>
													<span style="float:right"><input id="chk_post_{$result.post_id}" name="chk_post_{$result.post_id}" type="checkbox" /></span>
												</span>	

										 		<br />

										 		<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align:middle">

								 				{$result.telephone_1}

								 				{if $result.telephone_1 != ''}&nbsp;&nbsp;<a href="#">Dial</a>{/if}

								 				</span>

											</td>

											<td>{$result.title}&nbsp;{$result.first_name}&nbsp;{$result.surname}</td>

											<td style="text-align: center; background-color: #F3F3F3">

												<div class="button2-left">

													<div class="page"><a id="detailsBtn_{$result.id}" title="Go to details for this post" href="#" onclick="javascript:showPost('{$result.id}', '{$result.post_id}', '{$result.initiative_id}');return false;">Details</a></div>

												</div>

												<div class="button2-left">

												{if $result.first_name == '' || $result.surname == ''}
													<div class="page"><a id="detailsBtn_{$result.id}" title="Log non-effective call not available as post has no contact details. Add contact details before calling." href="#">Non-Eff</a></div>
												{else} 
													<div class="page"><a id="detailsBtn_{$result.id}" title="Log a non-effective call for this record" href="#" onclick="javascript:logNonEffective('{$result.post_id}');return false;">Non-Eff</a></div>

												{/if}
												</div>

											</td>

										</tr>

										<script language="javascript">

											var row = new Object;

											row.company_id = {$result.id};

											row.post_id = {$result.post_id};

											row.post_initiative_id = "";

											colln.add(row);

											//alert("added company_id: " + {$result.id} + " | post_id: " + {$result.post_id});

										</script>

										{assign var="company_post_count" value="1"}

						{else} 

							{* No posts but we still need to add an entry to our record set for this company*}

							<script language="javascript">

								var row = new Object;

								row.company_id = {$result.id};

								row.post_id = "";

								row.post_initiative_id = "";

								colln.add(row);

								//alert("added company_id: " + {$result.id} + " | post_id: ''");

							</script>

						{/if}			

					

					{else}

						{if $result.post_id != ""}

										<tr id="tr_post_{$result.post_id}">

											<td><a href="#a_post_{$result.post_id}"></a>

										 		<span>
													<span style="float:left">{$result.job_title}</span>
													<span style="float:right"><input id="chk_post_{$result.post_id}" name="chk_post_{$result.post_id}" type="checkbox" /></span>
												</span>	
										 		

										 		<br />

										 		<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align:middle">

								 				{$result.telephone_1}

								 				{if $result.telephone_1 != ''}&nbsp;&nbsp;<a href="#">Dial</a>{/if}

								 				

											</td>

											<td>{$result.title}&nbsp;{$result.first_name}&nbsp;{$result.surname}</td>

											<td style="text-align: center; background-color: #F3F3F3">

												<div class="button2-left">

													<div class="page"><a id="detailsBtn_{$result.id}" title="Go to details for this post" href="#" onclick="javascript: showPost('{$result.id}', '{$result.post_id}', '{$result.initiative_id}');return false;">Details</a></div>

												</div>

												<div class="button2-left">

													{if $result.first_name == '' || $result.surname == ''}
													<div class="page"><a id="detailsBtn_{$result.id}" title="Log non-effective call not available as post has no contact details. Add contact details before calling." href="#">Non-Eff</a></div>
													{else} 
													<div class="page"><a id="detailsBtn_{$result.id}" title="Log a non-effective call for this record" href="#" onclick="javascript:logNonEffective('{$result.post_id}');return false;">Non-Eff</a></div>

													{/if}
												</div>

											</td>

										</tr>

										<script language="javascript">

											var row = new Object;

											row.company_id = {$result.id};

											row.post_id = {$result.post_id};

											row.post_initiative_id = "";

											colln.add(row);

											//alert("added company_id: " + {$result.id} + " | post_id: " + {$result.post_id});

										</script>

						{/if}

					{/if}

					

					{assign var="company_id" value=$result.id}

					

				{/foreach}

			</tbody>

		</table>

		

	</div>



{/if}

{include file="footer2.tpl"}
{/strip}