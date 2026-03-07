{strip}
{include file="header2.tpl" title="Search Results"}

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

iframeLocation(			parent.information, src);

		}

	}



	function showWindow()

	{

		parent.showWindow();

	}



	function logNonEffective(post_id, post_initiative_id)

	{

		if (post_id == "")	

		{

			alert ("Error - No post available. Please report this error to the system administrator");

			return;

		}

		

		if (post_initiative_id == "")	

		{

			alert ("Error - No post initiative available. Please report this error to the system administrator");

			return;

		}

		else

		{

			initiative_id = "";

		}

		

		var ill_params = new Object;

		//set item_id - the id of the object we are dealing with

		ill_params.post_id = post_id;

		ill_params.post_initiative_id = post_initiative_id;

		ill_params.initiative_id = initiative_id;



		getAjaxData("AjaxCommunication", "", "log_non_effective", ill_params, "Saving...")

	}



	function logCommunication(company_id, post_id, post_initiative_id, source_tab)

	{

		if (company_id == "")	

		{

			alert ("Error - No company available. Please report this error to the system administrator");

			return;

		}

		

		if (post_id == "")	

		{

			alert ("Error - No post available. Please report this error to the system administrator");

			return;

		}

		

		if (post_initiative_id == "")	

		{

			alert ("Error - No post initiative available. Please report this error to the system administrator");

			return;

		}

		else

		{

			initiative_id = "";

		}

		

		top.loadTab(4,"Communication&company_id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id + "&post_initiative_id=" + post_initiative_id + "&source_tab=" + source_tab, true);

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

		

	function showCompany(company_id, post_id, initiative_id, post_initiative_id)

	{

		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.

		// This need only occurs when we navigate back to the results set from the filter workspace.

		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded

		page_isloaded = true;

		top.responderFadeIn();

iframeLocation(		top.frames["iframe_7"], "index.php?cmd=WorkspaceFilter&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id);

		top.loadTab(7,"");

		

		if (post_initiative_id != "")

		{

			colln.goToPostInitiativeId(post_initiative_id);

		}

		else if (post_id != "")

		{

			colln.goToPostId(post_id);

		}

		else

		{

			colln.goToCompanyId(company_id);

		}

		

		highlightSelectedRow(company_id, post_id, post_initiative_id);

	}

	

	// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them

	// back to normal when a new company and/or post is selected

	var last_company_class_change_id = "";

	var last_post_class_change_id = "";

	var last_post_initiative_class_change_id = "";

	

	function highlightSelectedRow(company_id, post_id, post_initiative_id)

	{

		//we set the background of the rows starting with post_initiative, if that doesn't exist then move to post, then finally to company

		if (post_initiative_id != '')

		{

			//set the background of the selected row

			$("tr_post_initiative_" + post_initiative_id).className="current";

			// now set the previously selected items to a normal background

			if (last_post_initiative_class_change_id != "" && last_post_initiative_class_change_id != post_initiative_id)

			{

				$("tr_post_initiative_" + last_post_initiative_class_change_id).className="";

			}

			last_post_initiative_class_change_id = post_initiative_id;

		}

		else if (post_id != '')

		{

			//set the background of the selected row

			$("tr_post_" + post_id).className="current";

			// now set the previously selected items to a normal background

			if (last_post_class_change_id != "" && last_post_class_change_id != post_id)

			{

				$("tr_post_" + last_post_class_change_id).className="";

			}

			last_post_class_change_id = post_id;

		}

		else if (company_id != '')

		{

			//set the background of the selected row

			$("tr_" + company_id).className="current";

			

			// now set the previously selected items to a normal background

			if (last_company_class_change_id != "" && last_company_class_change_id != company_id)

			{

				$("tr_" + last_company_class_change_id).className="";

			}

			last_company_class_change_id = company_id;

		}

		

	}

	

	function goToHash(hash_location)

	{

		var mypos = findPos($(hash_location));

		$("div_results").scrollTop = mypos[1] - 10;

	}

	

	function findPos(obj) 

	{

		//alert ("in pos: " + obj.offsetParent);

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



<!-- height on following div s/be 704 -->

<div id="div_results" class="cfg" style="border: solid 1px #ccc; padding: 2px; width: 100%; height: 704px; overflow: auto;">



	Results for the filter <strong>{$filter->getName()}</strong>

	<br />

	Companies: {$filter->getCompanyCount()} &nbsp;|&nbsp;

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
					<input type="button" value="Create company tags" onclick="javascript:submitToAjax('company')" style="width:150px" />
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
	

	<table id="table1" class="adminlist sortable" cellspacing="1">

		<thead>

			<tr>

				{*<th style="width: 1%; text-align: center">#</th>*}

				

				<th style="width: 24%; text-align: left">Company</th>

				<th style="width: 24%; text-align: left">Job Title</th>

				<th style="width: 12%; text-align: left">Name</th>

				<th style="width: 10%; text-align: left">Propensity</th>

				<th style="width: 24%; text-align: left">Client Initiative</th>

				<th style="width: 10%; text-align: left">Status</th>

				<th style="width: 5%">&nbsp;</th>

			</tr>

		</thead>

		<tfoot>

		</tfoot>

		<tbody>

			{foreach name="result_loop" from=$results item=result}

				<tr id="{if $result.post_initiative_id != ""}tr_post_initiative_{$result.post_initiative_id}{elseif $result.post_id != ""}tr_post_{$result.post_id}{else}tr_{$result.id}{/if}" style="vertical-align:top">

					<td>

						<a name="a_post_initiative_{$result.post_initiative_id}"></a>

						<a name="a_post_{$result.post_id}"></a>

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
						<br />

						<em>{br_format town=$result.town postcode=$result.postcode}</em>

					</td>

					<td>

						{if $result.job_title}
							<span>
								<span style="float:left"><strong>{$result.job_title}</strong></span>
								<span style="float:right"><input id="chk_post_{$result.post_id}" name="chk_post_{$result.post_id}" type="checkbox" /></span>
							</span>	

							<br />

			 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align:middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />

			 				{$result.telephone_1}

			 				{if $result.telephone_1 != ''}&nbsp;&nbsp;<a href="#">Dial</a>{/if}

		 				{/if}

					</td>

					<td>{$result.title}&nbsp;{$result.first_name}&nbsp;{$result.surname}</td>

					<td>

						{if $result.job_title}

							<span style="display: none">{$result.propensity}</span>

							<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align:middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />

						{/if}

					</td>

					<td><strong>{$result.client_name}&nbsp;-&nbsp;{$result.initiative_name}</strong>

						<br />

						<br />

						Last effective: {$result.communication_date|date_format:"%d %B %Y"}

					</td>

					<td>{$result.status}</td>

					<td style="text-align: center; background-color: #F3F3F3" nowrap="nowrap">

						<a style="cursor: pointer" id="detailsBtn_{$result.id}" title="Go to details for this record" onclick="javascript: showCompany({$result.id}, {$result.post_id}, {$result.initiative_id}, {$result.post_initiative_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>&nbsp;

						{if $result.first_name == '' || $result.surname == ''}
							<a style="cursor: pointer" id="nonEffectivesBtn_{$result.id}" title="Log non-effective call not available as post has no contact details. Add contact details before calling"><img src="{$APP_URL}app/view/images/icons/status_offline.png" /></a>
							<a style="cursor: pointer" id="EffectivesBtn_{$result.id}" title="Log effective call not available as post has no contact details. Add contact details before calling"><img src="{$APP_URL}app/view/images/icons/status_offline.png" /></a>
						{else}					
							<a style="cursor: pointer" id="nonEffectivesBtn_{$result.id}" title="Log a non-effective call for this post" onclick="javascript:logNonEffective('{$result.post_id}', '{$result.post_initiative_id}');return false;"><img src="{$APP_URL}app/view/images/icons/status_offline.png" /></a>
							<a style="cursor: pointer" id="EffectivesBtn_{$result.id}" title="Log an effective call for this post" onclick="javascript:logCommunication({$result.id}, {$result.post_id}, {$result.post_initiative_id}, 8);return false;"><img src="{$APP_URL}app/view/images/icons/status_offline.png" /></a>
						{/if}
					</td>

				</tr>

				

				<script language="javascript">

					var row = new Object;

					row.company_id = "{$result.id}";

					row.post_id = "{$result.post_id}";

					row.post_initiative_id = "{$result.post_initiative_id}";

					colln.add(row);

					//alert("added company_id: " + {$result.id} + " | post_id: " + {$result.post_id});

				</script>

			{/foreach}

		</tbody>

	</table>

	

</div>



{include file="footer2.tpl"}
{/strip}