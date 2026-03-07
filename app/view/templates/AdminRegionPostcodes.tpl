{include file="header2.tpl" title="Characteristic List"}

<script language="JavaScript" type="text/javascript">
{literal}
function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.form_new_postcode.task.value=pressbutton;
	
	try 
	{
		document.form_new_postcode.onsubmit();
	}
	
	catch(e)
	{}
	
	document.form_new_postcode.submit();
}


function submitbutton(pressbutton)
{
//	alert('submitbutton(' + pressbutton + ')');
	
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}

function getResults(search_item)
{
	var ill_params = new Object;
	ill_params['search_item'] = search_item.value;
	getAjaxData("AjaxRegion", "", "get_postcodes_start_with", ill_params, "Saving...");

}

function deleteRegionPostcode(postcode_id, postcode)
{
	if (confirm("Confirm delete postcode '" + postcode + "' from region '" + $F("region_name") + "'?"))
	{
		var ill_params = new Object;
		ill_params['region_id'] = $F('id');
		ill_params['postcode_id'] = postcode_id;
		getAjaxData("AjaxRegion", "", "delete_region_postcode", ill_params, "Saving...");
	}
}

function AjaxRegion(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "get_postcodes_start_with":
				var results = t.results;
				var msg = "<table>";
				for (x = 1; x < results.length + 1; x++) 
				{
					msg = msg + "<tr id='tr_" + results[x-1]["id"] 
					+ "'><td style='vertical-align: top'><input type='checkbox' id='chk_id_" + results[x-1]["id"] 
					+ "' name='chk_id_" + results[x-1]["id"] + "'/></td><td><strong>" + results[x-1]["postcode"] 
					+ "</strong></td></tr>";
				}
				msg = msg + "</table>";
				$("div_results").innerHTML = msg;
				break;
			case "delete_region_postcode":
				deleteRow('tr_' + t.postcode_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

/* --- Helper functions --- */
// The following functions are used by the Ajax return data handlers to process the current HTML page.

function doSearch(search_item)
{
	if (search_item.value == "")
	{
		$("div_results").innerHTML = "You must enter a new postcode in order to search";
		$("div_results").style.display = "block";
		return;
	}
	// show the searching message
	$("div_results").innerHTML = "Please wait... search in progress";
	$("div_results").style.display = "block";
	
	// do the search
	getResults(search_item);
	
}

function deleteRow(item_id)
{
	var tbl = document.getElementById('tbl_postcode_list');
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


<div style="height:630px; overflow-x: hidden; overflow-y: y:auto">
	<div id="div_region_postcode_menu" >
		Postcodes for region: <strong>{$region->getName()}</strong> &nbsp;&nbsp;|&nbsp;&nbsp;
		{assign var=postcodes value=$region->getPostcodes()}
		<span style="text-align: right"><strong>{$postcodes|@count}</strong> record{if $postcodes|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
		<input type="button" id="add_new_postcode" name="add_new_postcode" value="Add New Postcode to {$region->getName()} region" onclick="javascript:$('div_new_postcode').show();$('search_postcode').focus();" />
		<div id="div_new_postcode" style="display: none; margin-top: 10px">
			<form id="form_new_postcode" name="form_new_postcode" action="index.php?cmd=AdminRegionPostcodes" method="post">
				<input type="hidden" name="task" value="" />
				<input type="hidden" id="id" name="id" value="{$region->getId()}" />
				<input type="hidden" id="region_name" name="region_name" value="{$region->getName()}" />
				
				<fieldset class="adminform">
					<legend>New postcode</legend>
					<table class="ianlist">
						<tr>
							<th style="width: 20%">Starting with</th>
							<td style="width: 60%"><input type="text" style="width: 100%" id="search_postcode" name="search_postcode"  tabindex="1" /></td>
							<td style="width: 20%; text-align: center">
								<a href="#" style="cursor: pointer" id="searchBtn_1" title="Search" tabindex="2" onclick="javascript:doSearch(form_new_postcode.search_postcode);""><img src="{$APP_URL}app/view/images/icons/magnifier.png" alt="Search" title="Search" /></a>
							</td>
						</tr>
					</table>
					<div id="div_results" style="display: none">
					</div>		
				</fieldset>

				<div>
					<input type="button" id="cancel_postcode" name="cancel_postcode" tabindex="3" value="Cancel" onclick="javascript:$('form_new_postcode').reset(); $('div_new_postcode').hide();" />&nbsp;
					<input type="button" id="reset_postcode" name="reset_postcode" tabindex="4" value="Reset" onclick="javascript:$('form_new_postcode').reset(); return false;" />&nbsp;
					<input type="button" id="save_postcode" name="save_postcode" tabindex="5" value="Save" onclick="javascript:submitbutton('save');" />
				</div>

			</form>
		</div>
	</div>
	<br />
	<br />
	<table id="tbl_postcode_list" class="adminlist sortable">
		<thead>
			<tr class="sortable" id="sortable_1">
				<th>Postcode</th>
				<th style="width: 10%; text-align: center">&nbsp;</th>
			</tr>
		</thead>
						
		{foreach name=postcode_loop from=$postcodes item=postcode}
		<tr id="tr_{$postcode.postcode_id}">
			<td>{$postcode.postcode}</td>
			<td style="text-align: center; vertical-align: middle">
				<a id="deleteBtn_{$postcode.postcode_id}" title="Delete" href="#" onclick="javascript:deleteRegionPostcode({$postcode.postcode_id}, '{$postcode.postcode}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Delete" title="Delete" /></a>
			</td>
		</tr>
		{/foreach}
	</table>



{include file="footer.tpl"}