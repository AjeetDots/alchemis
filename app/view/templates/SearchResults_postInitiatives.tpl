{include file="header2.tpl" title="Search Results"}

<script language="JavaScript" type="text/javascript">
{literal}
	function popupWindow(target)
	{
		alert('Not here popupWindow('+target+')');
//		alert($('iframe1').style);
//		$('iframe1').style.overflow-y = 'hidden';
//		$('iframe2').style.overflow-y = 'hidden';
//		$('iframe3').style.overflow-y = 'hidden';
//		$('iframe4').style.overflow-y = 'hidden';
		showPopWin(target, 800, 500, null);
	}

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

	function showPost(company_id, post_id, initiative_id, post_initiative_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
iframeLocation(		top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id);
		top.loadTab(5,"");
		colln.goToPostId(post_id);
		highlightSelectedRow(post_initiative_id);
	}

	// this variable holds the ids of the post initiative row which had its background changed to highlighted. 
	// We need this so we can set it back to normal when a new post initiative is selected
	var last_post_initiative_class_change_id = "";
		
	function highlightSelectedRow(post_initiative_id)
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
<div id="div_results" class="cfg" style="border: solid 1px #ccc; padding: 2px; width: 100%; height: 715px; overflow: auto;">
	<table id="table1" class="adminlist sortable" id="sortable_1"cellspacing="1">
		<thead>
			<tr class="sortable" {*id="sortable_{$result.id}"*}>
				<th style="width: 1%; text-align: center">#</th>
				{*<th style="width: 1%; text-align: center">ID</th>*}
				<th style="width: 21%; text-align: left">Company</th>
				<th style="width: 21%; text-align: left">Address</th>
				<th style="width: 21%; text-align: left">Name</th>
				<th style="width: 20%; text-align: left">Job Title</th>
				<th style="width: 10%">Propensity</th>
				<th style="width: 10%">Initiative</th>
				<th style="width: 10%">Status</th>
				<th style="width: 5%"></th>
			</tr>
		</thead>
		<tbody>
			{foreach name="result_loop" from=$search_results item=result}
				<tr id="tr_post_initiative_{$result.post_initiative_id}" style="vertical-align:top">
					<td>{$smarty.foreach.result_loop.iteration}</td>
					{*<td>{$result.id}</td>*}
					<td>
						<span id="client_{$result.post_initiative_id}">{$result.name}</span>
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
					<td>{$result.first_name}&nbsp;{$result.surname}</td>
					<td>{$result.job_title}</td>
		 			<td style="text-align: center">
		 				<span style="display: none">{$result.propensity}</span>
		 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
		 			</td>
		 			<td>{$result.client_name}: {$result.initiative_name}</td>
					<td>{$result.status}</td>
					<td style="text-align: center; background-color: #F3F3F3">
						<a id="detailsBtn_{$result.post_initiative_id}" title="Edit" href="#" onclick="javascript:showPost({$result.id}, {$result.post_id}, {$result.initiative_id}, {$result.post_initiative_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
					</td>
				</tr>
				<script language="JavaScript" type="text/javascript">
					var row = new Object;
					row.company_id = {$result.id};
					row.post_id = {$result.post_id};
					row.post_initiative_id = {$result.post_initiative_id};
					colln.add(row);
					
					{* if just one post in the results then redirect to that post*}
					{if $search_results|@count == 1}
						showPost({$result.id}, {$result.post_id}, {$result.post_initiative_id});
					{/if}
					
				</script>
			{/foreach}
		</tbody>
	</table>
</div>
{/if}

{include file="footer2.tpl"}