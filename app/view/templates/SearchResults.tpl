{strip}
{include file="header2.tpl" title="Search Results"}

<script language="JavaScript" type="text/javascript">
{literal}
	

	function popupWindow(target)
	{
		showPopWin(target, 800, 500, null);
	}

	function openInfoPane(src)
	{
		if (parent.information == undefined)
		{
			//alert("Here");
			parent.popupWindow(src);
			//alert("Here");
		}
		else
		{
			iframeLocation(parent.information, src);
		}
	}

	function showWindow()
	{
		parent.showWindow();
	}
	
	function showPost(company_id, post_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
		iframeLocation(top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
		top.loadTab(5,"");
		colln.goToPostId(post_id);
		highlightSelectedRow(company_id, post_id);
	}
	
	function showCompany(company_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
		colln.goToCompanyId(company_id);
		var t = colln.getCurrent();
		var post_id = t.post_id;
//		alert(top.$F("initiative_list"));
		iframeLocation(top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + top.$F("initiative_list"));
		top.loadTab(5,"");
		highlightSelectedRow(company_id, post_id);
	}
	
	// these two variables hold the ids of the company or post rows which had their backgrounds changed to highlighted. We need this so we can set them
	// back to normal when a new company and/or post is selected
	var last_company_class_change_id = "";
	var last_post_class_change_id = "";
		
	function highlightSelectedRow(company_id, post_id, post_initiative_id)
	{
		//set the background of the selected row
		$("tr_" + company_id).className="current";
		
		if (post_id != "")
		{
			$("tr_post_" + post_id).className="current";
		}
		
		// now set the previously selected items to a normal background
		if (last_company_class_change_id != "" && last_company_class_change_id != company_id)
		{
			$("tr_" + last_company_class_change_id).className="";
		}
		last_company_class_change_id = company_id;
		
		if (last_post_class_change_id != "" && last_post_class_change_id != post_id)
		{
			$("tr_post_" + last_post_class_change_id).className="";
		}
		last_post_class_change_id = post_id;
	}

	function goToHash(hash_location)
	{
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
	
	// set page_isloaded to false so we can check in header_js.loadTab whether we need to highlight/navigate to any lines in the results set.
	// This need only occurs when we navigate back to the results set from the filter workspace.
	// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
	page_isloaded = false;
	
	
	
{/literal}



</script>

<p><strong>{$search_results|@count}</strong> result{if $search_results|@count != 1}s{/if} for <strong>{$object_type}</strong> which <strong>{$search_type_friendly}</strong> the text <strong>{$search_param}</strong></p>

{if $search_results}
	<form id="frm_search_results" name="frm_search_results">
	<div id="div_results" class="cfg" style="border: solid 0px #ccc; padding: 2px; width: 100%; height: 715px; overflow: auto">
		<table id="table1" class="adminlist" style"border-collapse: collapse; border-spacing: 0px; empty-cells: none">
			<thead>
				<tr>
					<th style="width: 1%; text-align: center">#</th>
					<th style="width: 1%; text-align: center">&nbsp;</th>
					<th style="width: 1%; text-align: center">ID</th>
					<th style="width: 28%; text-align: left">Company</th>
					<th style="width: 65%; text-align: left">Address</th>
					<th style="width: 5%">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			{foreach name="result_loop" from=$search_results item=result}
				{assign var="post_count" value=$result.posts|@count}
				<tr id="tr_{$result.id}" style="vertical-align: top">
					<td>{$smarty.foreach.result_loop.iteration}</td>
					<td style="text-align: center"><a href="#" onclick="javascript:new Effect.toggle($('post_list_{$result.id}'), 'blind', {literal}{duration: 0.3}{/literal});return false; "><img style="vertical-align:middle" src="{$APP_URL}app/view/images/icons/group.png" alt="Posts" title="Number of posts at this company" />({$post_count})</a></td>
					<td>{$result.id}</td>
					<td>
						<span id="client_{$result.id}" style="font-weight:bold">{$result.name}</span>
						{if $result.telephone != ""}
						<br />
							<span{if $result.telephone_tps == 1} style="color:red"{/if}>{$result.telephone}</span>{*&nbsp;&nbsp;<a href="#">Dial</a>*}
						{/if}
						{if $result.website != ""}
						<br />
							<a href="{$result.website}" target="_new">{$result.website}</a>
						{/if}
					</td>
					<td>{$result.site_address}</td>
					<td class="button">
						<a id="detailsBtn_{$result.id}" title="Details" onclick="javascript:showCompany({$result.id}); return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
						<input type="checkbox" id="chk_company_{$result.id}" name="chk_company_{$result.id}" />
					</td>
				</tr>
				<tr>
					<td colspan="6" style="height: 0px">
						<div id="post_list_{$result.id}" style="background-color: #f9f9f9; float: none; display: none; margin: 0px 0px 0px 0px">
							<table class="sortable" id="sortable_{$result.id}">
								<thead>
									<tr>
										<th>Job Title</th>
										<th>Post Holder</th>
										<th>Telephone</th>
										<th>Propensity</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
							 	{foreach name="posts_loop" from=$result.posts item=post}
							 		<tr id="tr_post_{$post.id}">
							 			<td>{$post.job_title}</td>
							 			<td>{$post.full_name}</td>
							 			<td>{$post.telephone_1}</td>
							 			<td style="text-align: center">
							 				<span style="display: none">{$post.propensity}</span>
							 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$post.propensity}.gif" style="vertical-align: middle" alt="Propensity {$post.propensity}" title="Propensity {$post.propensity}" />
							 			</td>
							 			<td class="button">
											<a id="detailsBtn_{$post.id}" title="Details" onclick="javascript:showPost({$result.id}, {$post.id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a><br />
											<input type="checkbox" id="chk_{$post.id}" name="chk_{$post.id}" />
										</td>
							 		</tr>
							 		<script language="JavaScript" type="text/javascript">
										var row = new Object;
										row.company_id = {$result.id};
										row.post_id = {$post.id};
										row.post_initiative_id = "";
										colln.add(row);
									</script>
							 	{/foreach}
							 	</tbody>
							 </table>
							 <br />
							 <a href="#" onclick="new Effect.BlindUp($('post_list_{$result.id}'), {literal}{duration: 0.3}{/literal}); return false;">[Close]</a>
						</div>
					</td>
				</tr>
				<script language="JavaScript" type="text/javascript">
					var row = new Object;
					row.company_id = {$result.id};
					row.post_id = "";
					row.post_initiative_id = "";
					colln.add(row);
					
					{* if just one company in the results then redirect to that company*}
					{if $search_results|@count == 1}
						showCompany({$result.id});
					{else}
						// hide the responder div
						top.responderFadeOut();
					{/if}
					

				</script>
				{/foreach}
			</tbody>
		</table>
	</div>
	</form>

{/if}

{include file="footer2.tpl"}
{/strip}