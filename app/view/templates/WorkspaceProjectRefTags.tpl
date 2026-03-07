{include file="header2.tpl" title="Project Refs"}

<script language="JavaScript">
{literal}

/* --- Ajax calling functions --- */
// Each of the following functions collects the data to the sent to a server side ajax command object.
// All the functions end with the getAjaxData function call which invokes procedures in the ajaxClient.js
// page which in turn invokes the Ajax handlers in prototype.js
// Note: data updated via the prototype.InPlaceEditor functionality in controls.js does not need a separate 
// calling function like the ones below as it makes the call to getAjaxData directly from controls.js

function addTag(parent_object_id, category_id, value)
{
	if (value == '')
	{
		alert("No project ref specified");
		return;
	}
	var ill_params = new Object;
	ill_params.item_id = null;
//	ill_params.value = $("add_tag").value;
	ill_params.value = value;
	$("add_tag").value = "";
	ill_params.categoryId = category_id;
	ill_params.parent_object_id = parent_object_id;
	getAjaxData("AjaxTag", "", "insert{/literal}{$parent_object_type}{literal}Tag", ill_params, "Saving...", true)
}

function deleteTag(tag_id)
{
	var parent_object_id = $("parent_object_id").value;
	
	if (confirm("Confirm delete?"))
	{
		var ill_params = new Object;
		ill_params.item_id = tag_id;
		ill_params.parent_object_id = parent_object_id;
		getAjaxData("AjaxTag", "", "delete{/literal}{$parent_object_type}{literal}Tag", ill_params, "Saving...", true)
	}
}

/* --- Ajax return data handlers --- */
// Each javascript page which calls an server side ajax command object requires a function whose name
// is the same as the server side ajax command object being used.
// This function handles all the return information from the server side ajax command object.
// The function handles this return information by using the cmd_action switch.
function AjaxTag(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.value + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "insert{/literal}{$parent_object_type}{literal}Tag":
				insertRow(t.item_id, t.value)
				$("add_tag").focus();
				break;
			case "delete{/literal}{$parent_object_type}{literal}Tag":
				deleteRow(t.item_id);
				break;
//			case "update":
//				$("edit_tag_" + t.item_id).innerHTML  = t.value;
//				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

/* --- Helper functions --- */
// The following functions are used by the Ajax return data handlers to process the current HTML page.

function insertRow(item_id, value)
{
	var tbl = document.getElementById('tbl_tags');
	var lastRow = tbl.rows.length;
	
	// if there`s no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	
	row.setAttribute("id", item_id);
	
//	// left cell
	var cellLeft= row.insertCell(0);
	
//	var newImg = document.createElement("img");
//	newImg.setAttribute("id", "img_edit_tag");
//	newImg.setAttribute("src", "app/view/images/icon_edit.jpg");
//	newImg.setAttribute("style", "vertical-align:middle");
	
//	cellLeft.appendChild(newImg);
	
	var newSpan = document.createElement("span");
//	newSpan.setAttribute("id", "edit_tag_" + item_id);
    var textNode = document.createTextNode(value);
	newSpan.appendChild(textNode);
	cellLeft.appendChild(newSpan);
	
//	var newScript = document.createElement("script");
//	newScript.setAttribute("type", "text/javascript");
	
//	var text = "new Ajax.InPlaceEditor('edit_tag_" + item_id + "', '', {externalControl: 'img_edit_tag', ill_cmd: 'AjaxTag', ill_cmd_action: 'update', ill_item_id: " + item_id + ", ill_field: 'value'});";
//	var scriptText = document.createTextNode(text);
	
//	newScript.appendChild(scriptText);
//	cellLeft.appendChild(newScript);
	
	// right cell
	var cellRight = row.insertCell(1);
	var newAnchor = document.createElement("a");
	newAnchor.setAttribute("title", "Delete tag");
	newAnchor.setAttribute("href", "javascript:deleteTag(" + item_id +");parent.displayPostInitiativeProjectRefs();");
	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_tag");
	newImg.setAttribute("src", "app/view/images/delete.png");
	newImg.setAttribute("style", "vertical-align:middle");

	newAnchor.appendChild(newImg);
	cellRight.appendChild(newAnchor);
	
}

function deleteRow(item_id)
{
	var tbl = document.getElementById('tbl_tags');
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

<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
<strong>{$category|capitalize}s</strong>
<br />
<hr />
<br />
<table iclass="adminlist" cellspacing="1"{*class="sortable"*}{* border="0" cellpadding="0" cellspacing="1" width="100%"*}>
	<thead>
		<tr>
			<th width="5%">Current</th>
			<th style="text-align: left">
				<select style="width: 200px" id="initiative_tags" name="initiative_tags">
					<option value="">&ndash; Select &ndash;</option>
					{html_options options=$initiative_tags}
				</select>
				<a href="#" onclick="javascript:addTag({$parent_object_id}, {$category_id}, $F('initiative_tags'));parent.displayPostInitiativeProjectRefs();">[Add]</a>
			</th>
		</tr>
		<tr>
			<th width="5%">New</th>
			<th colspan="2" style="text-align: left">
				<input type="text" id="add_tag" value="" style="width: 200px" />
				<a href="#" onclick="javascript:addTag({$parent_object_id}, {$category_id}, $F('add_tag'));parent.displayPostInitiativeProjectRefs();">[Add]</a>
			</th>
		</tr>
		
	</thead>
	
</table>
<table id="tbl_tags" class="adminlist" cellspacing="1"{*class="sortable"*}{* border="0" cellpadding="0" cellspacing="1" width="100%"*}>

	<tbody>
		{foreach name=tags from=$tags item=tag}
		<tr id="{$tag->getId()}">
			{*<td>{$smarty.foreach.tags.iteration}</td>*}
			<td width="75%">
				{*<img id="img_edit_tag" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align:middle">*}
				<span id="edit_tag_{$tag->getId()}">{$tag->getValue()}</span>
				{*<script type="text/javascript">
				new Ajax.InPlaceEditor('edit_tag_{$tag->getId()}', '', {literal}{externalControl: 'img_edit_tag', ill_cmd: 'AjaxTag', ill_cmd_action: 'update', ill_item_id: {/literal}{$tag->getId()}{literal}, ill_field: 'value'}{/literal});
				</script>*}
			</td>
			
			<td>
        {if $tag->isDataSource() neq true}
          <a href="javascript:deleteTag({$tag->getId()});parent.displayPostInitiativeProjectRefs();" title="Delete tag">
            <img id="img_delete_tag" src="{$APP_URL}app/view/images/delete.png" style="vertical-align:middle">
          </a>
        {/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>


{include file="footer2.tpl"}