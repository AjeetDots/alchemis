{include file="header2.tpl" title="Characteristic Elements"}

<script language="JavaScript">
{literal}

/* --- Ajax calling functions --- */
// Each of the following functions collects the data to the sent to a server side ajax command object.
// All the functions end with the getAjaxData function call which invokes procedures in the ajaxClient.js
// page which in turn invokes the Ajax handlers in prototype.js
// Note: data updated via the prototype.InPlaceEditor functionality in controls.js does not need a separate 
// calling function like the ones below as it makes the call to getAjaxData directly from controls.js

function addElement(characteristic_id)
{
//	alert('addElement(' + characteristic_id + ')');
	var ill_params = new Object;
//	alert('here0');
	ill_params.item_id = null;
//	alert('here1');
	ill_params.value = $('add_element').value;
//	alert('here2');
	ill_params.characteristic_id = characteristic_id;
//	alert('here3');
	getAjaxData('AjaxCharacteristicElement', '', 'insert', ill_params, 'Saving...')
//	alert('here4');
	$('add_element').value = '';
}

function deleteElement(element_id)
{
	alert('deleteElement(' + element_id + ')');
//	var parent_object_id = $("parent_object_id").value;
	if (confirm('Confirm delete?'))
	{
		var ill_params = new Object;
		ill_params.item_id = element_id;
//		ill_params.parent_object_id = parent_object_id;
		getAjaxData('AjaxCharacteristicElement', '', 'delete', ill_params, 'Deleting...')
	}
}

/* --- Ajax return data handlers --- */
// Each javascript page which calls an server side ajax command object requires a function whose name
// is the same as the server side ajax command object being used.
// This function handles all the return information from the server side ajax command object.
// The function handles this return information by using the cmd_action switch.
function AjaxCharacteristicElement(data)
{
	alert('AjaxCharacteristicElement(' + data + ')');
	
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.value + "\nt.cmd_action = " + t.cmd_action);
		switch (t.cmd_action)
		{
			case 'insert':
				alert('in insert');
				insertRow($('tbl_elements'), t.item_id, t.value)
				$('add_element').focus();
				break;
			
			case 'delete':
				alert('in delete');
				deleteRow($('tbl_elements'), t.item_id);
				break;
			
			default:
				alert('No cmd_action specified');
				break;
		}
	}
}

/* --- Helper functions --- */
// The following functions are used by the Ajax return data handlers to process the current HTML page.

/**
 * @param tbl the table to delete a row from
 * @param item_id
 * @param value
 */
function insertRow(tbl, item_id, value)
{
	var lastRow = tbl.rows.length;
	
	// if there is no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	
	row.setAttribute("id", item_id);
	
	// left cell
	var cellLeft= row.insertCell(0);
	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_edit_tag");
	newImg.setAttribute("src", "app/view/images/icon_edit.jpg");
	newImg.setAttribute("style", "vertical-align:middle");
	
	cellLeft.appendChild(newImg);
	
	var newSpan = document.createElement("span");
	newSpan.setAttribute("id", "edit_tag_" + item_id);
    var textNode = document.createTextNode(value);
	newSpan.appendChild(textNode);
	cellLeft.appendChild(newSpan);
	
	var newScript = document.createElement("script");
	newScript.setAttribute("type", "text/javascript");
	
	var text = "new Ajax.InPlaceEditor('edit_tag_" + item_id + "', '', {externalControl: 'img_edit_tag', ill_cmd: 'AjaxTag', ill_cmd_action: 'update', ill_item_id: " + item_id + ", ill_field: 'value'});";
	var scriptText = document.createTextNode(text);
	
	newScript.appendChild(scriptText);
	cellLeft.appendChild(newScript);
	
	// right cell
	var cellRight = row.insertCell(1);
	var newAnchor = document.createElement("a");
	newAnchor.setAttribute("title", "Delete tag");
	newAnchor.setAttribute("href", "javascript:deleteTag(" + item_id +");");
	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_tag");
	newImg.setAttribute("src", "app/view/images/delete.png");
	newImg.setAttribute("style", "vertical-align:middle");

	newAnchor.appendChild(newImg);
	cellRight.appendChild(newAnchor);
	
}

/**
 * Deletes the given row from the table.
 * @param tbl the table to delete a row from
 * @param item_id element ID of the row
 */
function deleteRow(tbl, item_id)
{
	var lastRow = tbl.rows.length;
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute('id') == item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

{/literal}
</script>

<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
<strong>{$characteristic->getName()}</strong>

<table id="tbl_elements" class="adminlist" cellspacing="1"{*class="sortable"*}{* border="0" cellpadding="0" cellspacing="1" width="100%"*}>
	<thead>
		<tr>
			{*<th width="5%">#</th>*}
			<th colspan="2"style="width: 95%; text-align: left">
				<input type="text" id="add_element" name="add_element" value="" style="width: 200px" />
				<a href="#" onclick="javascript:addElement({$characteristic->getId()});">[Add]</a>
			</th>
		</tr>
	</thead>
	<tbody>
		{foreach name=element_loop from=$elements item=element}
		<tr id="{$element->getId()}">
{*			<td>{$smarty.foreach.element_loop.iteration}</td>*}
			<td>
				<img id="img_edit_element_{$element->getId()}" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align: middle" />
				<span id="edit_tag_{$element->getId()}">{$element->getValue()}</span>
				<script type="text/javascript">
					new Ajax.InPlaceEditor('edit_characteristic_element_{$element->getId()}', '', {literal}{externalControl: 'img_edit_characteristic_element', ill_cmd: 'AjaxCharacteristicElement', ill_cmd_action: 'update', ill_item_id: {/literal}{$element->getId()}{literal}, ill_field: 'value'}{/literal});
				</script>
			</td>
			<td>
				<a href="javascript:deleteElement({$element->getId()});" title="Delete characteristic element"><img id="img_delete_element_{$element->getId()}" src="{$APP_URL}app/view/images/delete.png" style="vertical-align:middle"></a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>


{include file="footer2.tpl"}