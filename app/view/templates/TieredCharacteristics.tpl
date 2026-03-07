{include file="header2.tpl" title="Tiered Characteristics"}

<script language="JavaScript">
{literal}

// array to hold which tiered characteristics have been downloaded
var arr_downloaded = new Array();

/* --- Ajax calling functions --- */
// Each of the following functions collects the data to the sent to a server side ajax command object.
// All the functions end with the getAjaxData function call which invokes procedures in the ajaxClient.js
// page which in turn invokes the Ajax handlers in prototype.js
// Note: data updated via the prototype.InPlaceEditor functionality in controls.js does not need a separate 
// calling function like the ones below as it makes the call to getAjaxData directly from controls.js

function getSubCharacteristics(parent_object_type, parent_object_id, category_id, parent_id)
{
	//check if this parent_id has already been downloaded. If so, then just show the relevant div
//	alert("Here");
	var bln_downloaded = true;
//	alert(arr_downloaded.length);
	
	bln_downloaded = arr_downloaded.find(function(element)
									{
										return (element == parent_id);
									});
	
	
	if (!bln_downloaded)
	{
		var ill_params = new Object;
		ill_params.item_id = null;
		ill_params.parent_object_type = parent_object_type;
		ill_params.parent_object_id = parent_object_id;
		ill_params.category_id = category_id;
		ill_params.parent_id = parent_id;
		
		getAjaxData("AjaxTieredCharacteristic", "", "get_sub_characteristics", ill_params, "Saving...")
	}
	else
	{
		// do nothing - already downloded
	}
}

function addTieredCharacteristic(parent_object_type, parent_object_id, category_id, parent_id)
{
	//populate the select item "select_children_of_" + parent_id
	
	var ill_params = new Object;
	ill_params.item_id = null;
	ill_params.parent_object_type = parent_object_type;
	ill_params.parent_object_id = parent_object_id;
	ill_params.category_id = category_id;
	ill_params.parent_id = parent_id;
		
	getAjaxData("AjaxTieredCharacteristic", "", "get_sub_characteristics_options", ill_params, "Saving...")
}


function addTopLevelCategory(parent_object_type, parent_object_id)
{
//	alert("Here");
	alert($F("select_top_level_characteristic"));
	var tiered_characteristic_id = $F("select_top_level_characteristic");
	//var tier = $F("select_top_level_tier");
	var tier = 0;
	
//	alert('tiered_characteristic_id = ' + tiered_characteristic_id + " : tier = " + tier);
	
//	alert("tiered_characteristic_id: " + tiered_characteristic_id + " | tier: " + tier);

//	if (tier == 0)
//	{
//		alert("Please select a tier value for the new {/literal}{$category}{literal}");
//		return;
//	}
	
	if (tiered_characteristic_id == "" || tiered_characteristic_id == null)
	{
		alert("Please select a {/literal}{$category}{literal}");
		return;
	}
	
	var ill_params = new Object;
	ill_params.item_id = null;
	ill_params.tiered_characteristic_id = tiered_characteristic_id;
	ill_params.parent_object_type = parent_object_type;
	ill_params.parent_object_id = parent_object_id;
	ill_params.tier = tier;
		
	getAjaxData("AjaxTieredCharacteristic", "", "add_top_level_category", ill_params, "Saving...")
}

function addParentObjectTieredCharacteristic(parent_object_type, parent_object_id, category_id, parent_id)
{


	tiered_characteristic_id = $F("select_children_of_" + parent_id);
	
	tier = $F("select_new_tier_" + parent_id);

//	alert("tiered_characteristic_id: " + tiered_characteristic_id + " | tier: " + tier);
	if (tier == 0)
	{
		alert("Please select a tier value for the new {/literal}{$category}{literal}");
		return;
	}

	new_value = $F("txt_new_child_value_of_" + parent_id);
			
	if (tiered_characteristic_id == 0 && new_value == "")
	{
		alert("Please enter a new sub {/literal}{$category}{literal}, or change to an existing option");
		return;
	}
	
//	alert(new_value);
	
	var ill_params = new Object;
	ill_params.item_id = null;
	ill_params.tiered_characteristic_id = tiered_characteristic_id;
	ill_params.parent_object_type = parent_object_type;
	ill_params.parent_object_id = parent_object_id;
	ill_params.category_id = category_id;
	ill_params.parent_id = parent_id;
	ill_params.tier = tier;
	ill_params.new_value = new_value;
	
//	alert("Here");
	
	getAjaxData("AjaxTieredCharacteristic", "", "add_parent_object_characteristic", ill_params, "Saving...")
}


/* --- Ajax return data handlers --- */
// Each javascript page which calls an server side ajax command object requires a function whose name
// is the same as the server side ajax command object being used.
// This function handles all the return information from the server side ajax command object.
// The function handles this return information by using the cmd_action switch.
function AjaxTieredCharacteristic(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.value + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "get_sub_characteristics":
//				alert(t.parent_id);
				$("div_tier_detail_parent_id_" + t.parent_id).style.display = "block";
				$("div_tier_loading_" + t.parent_id).style.display = "none";
				for (x = 0; x < t.sub_tier.length; x++) 
				{
//					alert(t.sub_tier[x].id);
					insertRow('tbl_tier_detail_parent_id_' + t.parent_id, t.parent_id, t.sub_tier[x].id, t.sub_tier[x].value,  t.sub_tier[x].tier, false, true);
					arr_downloaded[arr_downloaded.length] = t.parent_id;
				}
				break;
			case "get_sub_characteristics_options":
//				alert("In get_sub_characteristics_options");
				makeSelectOptions("select_children_of_" + t.parent_id, t.sub_tier_options, "id", "value");
				showAddCustomChildElement(t.parent_id);
				$("tr_tier_detail_parent_id_" +  t.parent_id).style.visibility="visible";
				$("tr_tier_detail_add_" +  t.parent_id).style.visibility="collapse";
				break;
			case "add_parent_object_characteristic":
				
				insertRow('tbl_tier_detail_parent_id_' + t.parent_id, t.parent_id, t.tiered_characteristic[0].id, t.tiered_characteristic[0].value,  t.tiered_characteristic[0].tier, false, true);
				arr_downloaded[arr_downloaded.length] = t.parent_id;
				$("tr_tier_detail_parent_id_" +  t.parent_id).style.visibility="collapse";
				$("txt_new_child_value_of_" +  t.parent_id).value = "";
				$("select_new_tier_" +  t.parent_id).selectedIndex = 0;
				$("tr_tier_detail_add_" +  t.parent_id).style.visibility="visible";
				$("select_children_of_" + t.parent_id).innerHTML = "";
				break;
			case "add_top_level_category":
				alert("Here" + t.tiered_characteristic_id);
				deleteOption('select_top_level_characteristic', t.tiered_characteristic_id);
				alert("Here2");
				$("select_top_level_characteristic").selectedIndex = 0;
				alert($("select_top_level_characteristic").options.length);
				if ($("select_top_level_characteristic").options.length == 0)
				{
					$('div_add_top_level_characteristic', 'span_add_top_level_category').invoke('hide');
				}
				insertRow("tbl_top_level_characteristics", 0, t.tiered_characteristic[0].id, t.tiered_characteristic[0].value,  t.tiered_characteristic[0].tier, true, false);
				makeSubCatContainerRow("tbl_top_level_characteristics", t.tiered_characteristic[0].id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

/* --- Helper functions --- */
// The following functions are used by the Ajax return data handlers to process the current HTML page.

function deleteOption(sel_name, option_id)
{
	var sel = $(sel_name);
	var options = sel.getElementsByTagName('option');
	options = $A(options);
	var opt = options.find( function(item){
			
			return (item.value == option_id);
		});
	sel.remove(opt.index);
	
//	alert(opt.index); //displays the employee name
	
}
function makeSelectOptions(select_name, data, value_field_name, text_field_name)
{
	var sel = $(select_name);
	if (data.length > 0)
	{
		sel.options.length = 0;
		// make new options
		for (i = 1; i < data.length + 1; i++) 
		{
			var op=document.createElement("option");
	        op.value=data[i-1][value_field_name];
	        op.text=data[i-1][text_field_name];
			sel.options.add(op);
		}
		
	}
	// add 'new sub category' option
	var op=document.createElement("option");
    op.value=0;
    op.text="Add new sub category";
	sel.options.add(op);
}

function showAddCustomChildElement(parent_id)
{
	var sel = $("select_children_of_" + parent_id);
	var txt = $("txt_new_child_value_of_" + parent_id);
	var sel_value = $F(sel);
//	alert(sel.options.length);
	if (sel.options.length == 1)
	{
		txt.style.display = "block";
	}
	else
	{
//		alert("sel_value = " + sel_value);
		if (sel_value == 0)
		{
			txt.style.display = "block";
		}
		else
		{
			txt.style.display = "none";
		}
	}
}

function insertRow(table_name, parent_id, characteristic_id, value, tier, show_children, show_delete)
{

	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
//	alert(lastRow);

	var cell_count = 0;
	
	// use this variable to control the colspan attribute later on in case we skip adding some cells - eg may not include a show children column
	var colspan_count = 0;

	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", characteristic_id);
//	alert("Starting cell1");
	// 1st cell
	var cell = row.insertCell(cell_count);
	cell_count += 1;
	
    var textNode = document.createTextNode(value);
	cell.appendChild(textNode);
//	alert("Starting cell1");	
	// 2nd cell
	var cell = row.insertCell(cell_count);
	cell_count += 1;
	
//	cell.style.width = "500"
    var textNode = document.createTextNode(tier);
	cell.appendChild(textNode);

//	alert("Starting cell1");
//	alert("show_children = " + show_children);
	if (show_children)
	{
		// show children cell
		var cell = row.insertCell(cell_count);
		cell_count += 1;
		var textNode = document.createTextNode("0 ");
		cell.appendChild(textNode);
		var newAnchor = document.createElement("a");
		newAnchor.setAttribute("title", "View children");
		var href = "javascript:$('tr_tier_detail_" + characteristic_id + "').style.visibility ='visible';new Effect.BlindDown($('div_tier_detail_" + characteristic_id + "'), {duration: 0.3});getSubCharacteristics('app_domain_" + $('parent_object_type').value + "'," + $('parent_object_id').value + ", " + $('category_id').value + ", " + characteristic_id + ");";
		newAnchor.setAttribute("href", href);
		newAnchor.innerHTML = "[add]";
		cell.appendChild(newAnchor);
	}
	else
	{
		colspan_count += 1;
	}
	
	if (show_delete)
	{
		// show delete cell
		var cell = row.insertCell(cell_count);
		cell.setAttribute("colspan", colspan_count+1);
		
		var newAnchor = document.createElement("a");
		newAnchor.setAttribute("title", "Delete tag");
		//newAnchor.setAttribute("href", "javascript:deleteTag(" + characteristic_id +");");
		alert(characteristic_id + " : " + parent_id);
		newAnchor.setAttribute("href", "javascript:deleteTag('" + table_name + "', " + characteristic_id +");");
		
		var newImg = document.createElement("img");
		newImg.setAttribute("id", "img_delete_tag");
		newImg.setAttribute("src", "app/view/images/delete.png");
		newImg.setAttribute("style", "vertical-align:middle");
	
		newAnchor.appendChild(newImg);
		cell.appendChild(newAnchor);
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

function makeSubCatContainerRow(table_name, characteristic_id)
{
//	alert(table_name);
	var parent_object_type = $("parent_object_type").value;
	var parent_object_id = $("parent_object_id").value;
	var category_id = $("category_id").value;
	
	
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
//	alert(lastRow);

	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", "tr_tier_detail_" + characteristic_id);
	row.setAttribute("style", "visibility: collapse");
	
	var scr =  "<td colspan=\"3\">";
	scr +=  "<div id=\"div_tier_detail_" + characteristic_id + "\" style=\"background-color: white; float: none; display: none\">";
	scr +=  "<div id=\"div_tier_loading_" + characteristic_id + "\">Loading information... please wait</div>";
	scr +=  "<div id=\"div_tier_detail_parent_id_" + characteristic_id + "\" style=\"display: none\">";
	scr +=  "<table id=\"tbl_tier_detail_parent_id_" + characteristic_id + "\" class=\"adminlist\">";
	scr +=  "<tr id=\"tr_tier_detail_add_" + characteristic_id  + "\">";
	scr +=  "<td colspan=\"1\" style=\"text-align: left\">";
	scr +=  "<a href=\"#\" onclick=\"javascript:addTieredCharacteristic('app_domain_" + parent_object_type +"', " + parent_object_id + ", " + category_id + ", " + characteristic_id + ");\">[Add new sub-cat]</a>";
	scr +=  "</td>";
	scr +=  "<td colspan=\"2\" style=\"text-align: right\">";
	scr +=  "<a href=\"#\" onclick=\"javascript:new Effect.BlindUp($('div_tier_detail_" + characteristic_id + "'), {duration: 0.3});$('tr_tier_detail_" + characteristic_id + "').style.visibility ='collapse';return false;\">[close]</a>";
	scr +=  "</td>";
	scr +=  "</tr>"
	scr +=  "<tr id=\"tr_tier_detail_parent_id_" + characteristic_id + "\" style=\"visibility: collapse\">";
	scr +=  "<td>";
	scr +=  "<select id=\"select_children_of_" + characteristic_id + "\" name=\"select_children_of_" + characteristic_id + "\" onchange=\"javascript:showAddCustomChildElement(" + characteristic_id + ");\">";
	scr +=  "</select>";
	scr +=  "<br />";
	scr +=  "<input type=\"text\" id=\"txt_new_child_value_of_" + characteristic_id + "\" name=\"txt_new_child_value_of_" + characteristic_id + "\" style=\"display: none; width: 100%\" />";
	scr +=  "</td>";
	scr +=  "<td>";
	scr +=  "<select id=\"select_new_tier_" + characteristic_id + "\" name=\"select_new_tier_" + characteristic_id + "\">";
	scr +=  "<option value=\"0\">-- select --</option>";
	scr +=  "<option value=\"1\">1</option>";
	scr +=  "<option value=\"2\">2</option>";
	scr +=  "<option value=\"3\">3</option>";
	scr +=  "</select>";
	scr +=  "</td>";
	scr +=  "<td>";
	scr +=  "<a href=\"#\" onclick=\"javascript:addParentObjectTieredCharacteristic('app_domain_" + parent_object_type + "', " + parent_object_id + ", " + category_id + ", " + characteristic_id + ");\">[Save]</a>";
	scr +=  "</td>";
	scr +=  "</tr>";
	scr +=  "</table>";
	scr +=  "</div>";
//	scr +=  "<a href=\"#\" onclick=\"javascript:new Effect.BlindUp($('div_tier_detail_" + characteristic_id + "'), {duration: 0.3});$('tr_tier_detail_" + characteristic_id + "').style.visibility ='collapse';return false;\">[close]</a>";
	scr +=  "</div>";
	scr +=  "</td>";

	
	row.innerHTML = scr;


}

{/literal}
</script>

<input type="hidden" id="parent_object_type" value="{$parent_object_type}" />
<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
<input type="hidden" id="category_id" value="{$category_id}" />

<span style="float: left"><strong>{$category}</strong></span>
{if $unused_top_level_tiered_characteristics}
<span id="span_add_top_level_category" style="float: right">
	<a href="#" onclick="javascript:Effect.toggle($('div_add_top_level_characteristic'), 'blind', {literal}{duration: 0.3}{/literal});return false; ">[Add new category]</a>
</span>
{/if}
<br />
	<br />
<div id="div_add_top_level_characteristic" style="display: none">
	
	<span style="float: left">
		<select id="select_top_level_characteristic" name="select_top_level_characteristic" style="width: 175px">
			{html_options options=$unused_top_level_tiered_characteristics}
 		</select>
	</span>
{*	<span>
		&nbsp;&nbsp;
		Tier:&nbsp;
		<select id="select_top_level_tier" name="select_top_level_tier" style="width: 75px">
 			<option value="0">-- select --</option>
 			<option value="1">1</option>
 			<option value="2">2</option>
 			<option value="3">3</option>
 		</select>
 	</span>
 *}
 	<br />
	<span style="float: left">
		<a href="#" onclick="javascript:addTopLevelCategory('app_domain_{$parent_object_type}', {$parent_object_id});">[Save]</a>
	</span>
	<br />
	<br />
</div>


<table id="tbl_top_level_characteristics" class="adminlist">	

	<thead>
		<tr>
			<th style="text-align:left;">Category</th>
			{*<th style="text-align:left;">Tier</th>*}
			<th style="text-align:left;">Sub-cats</th>
		</tr>
	</thead>
	
{foreach name=tiered_characteristics from=$tiered_characteristics item=tiered_characteristic}
	
	<tr>
		<td style="width: 70%">
			{$tiered_characteristic.value}
		</td>
		{*<td style="width: 10%">
			{$tiered_characteristic.tier}
		</td>*}
		<td style="width: 25%">
			{$tiered_characteristic.children_count}&nbsp;
			<a href="#" onclick="javascript:if ($('tr_tier_detail_{$tiered_characteristic.id}').style.visibility !='visible') {literal}{{/literal}$('tr_tier_detail_{$tiered_characteristic.id}').style.visibility ='visible'{literal}} else {{/literal}$('tr_tier_detail_{$tiered_characteristic.id}').style.visibility ='collapse'{literal}}{/literal};new Effect.toggle($('div_tier_detail_{$tiered_characteristic.id}'), 'blind', {literal}{duration: 0.3}{/literal});getSubCharacteristics('app_domain_{$parent_object_type}',{$parent_object_id}, {$category_id}, {$tiered_characteristic.id});return false; ">
				{if $tiered_characteristic.children_count == 0}
					[add]
				{else}
					[view]
				{/if}
			</a>
		</td>
	</tr>
	<tr id="tr_tier_detail_{$tiered_characteristic.id}" style="visibility: collapse">
		<td colspan="3">
			<div id="div_tier_detail_{$tiered_characteristic.id}" style="background-color: white; float: none; display: none">
 				<div id="div_tier_loading_{$tiered_characteristic.id}">Loading information... please wait</div>
 				<div id="div_tier_detail_parent_id_{$tiered_characteristic.id}" style="display: none">
 					<table id="tbl_tier_detail_parent_id_{$tiered_characteristic.id}" class="adminlist">
 						<tr id="tr_tier_detail_add_{$tiered_characteristic.id}">
 							<td colspan="1" style="text-align: left">
 								<a href="#" onclick="javascript:addTieredCharacteristic('app_domain_{$parent_object_type}', {$parent_object_id}, {$category_id}, {$tiered_characteristic.id});">[Add new sub-cat]</a>
 							</td>
 							<td colspan="2" style="text-align: right">
 								<a href="#" onclick="javascript:new Effect.BlindUp($('div_tier_detail_{$tiered_characteristic.id}'), {literal}{duration: 0.3}{/literal});$('tr_tier_detail_{$tiered_characteristic.id}').style.visibility ='collapse';return false;">[close]</a>
 							</td>
 						</tr>
 						<tr id="tr_tier_detail_parent_id_{$tiered_characteristic.id}" style="visibility: collapse">
 							<td>
 								<select id="select_children_of_{$tiered_characteristic.id}" name="select_children_of_{$tiered_characteristic.id}" onchange="javascript:showAddCustomChildElement({$tiered_characteristic.id});">
 								</select>
 								<br />
 								<input type="text" id="txt_new_child_value_of_{$tiered_characteristic.id}" name="txt_new_child_value_of_{$tiered_characteristic.id}" style="display: none; width: 100%" />
 							</td>
 							<td>
 								<select id="select_new_tier_{$tiered_characteristic.id}" name="select_new_tier_{$tiered_characteristic.id}">
 									<option value="0">-- select --</option>
 									<option value="1">1</option>
 									<option value="2">2</option>
 									<option value="3">3</option>
 								</select>
 								
 							</td>
 							<td>
 								<a href="#" onclick="javascript:addParentObjectTieredCharacteristic('app_domain_{$parent_object_type}', {$parent_object_id}, {$category_id}, {$tiered_characteristic.id});">[Save]</a>
 							</td>
 						</tr>
 					</table>
 				</div>
			 	{*<a href="#" onclick="javascript:new Effect.BlindUp($('div_tier_detail_{$tiered_characteristic.id}'), {literal}{duration: 0.3}{/literal});$('tr_tier_detail_{$tiered_characteristic.id}').style.visibility ='collapse';return false;">[close]</a>*}
			 </div>
		</td>
	</tr>
{/foreach}




{include file="footer2.tpl"}