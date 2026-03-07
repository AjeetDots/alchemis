{include file="header2.tpl" title="Characteristic List"}

<script language="JavaScript" type="text/javascript">
{literal}
function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.form_new_region.task.value=pressbutton;
	
	try 
	{
		document.form_new_region.onsubmit();
	}
	
	catch(e)
	{}
	
	document.form_new_region.submit();
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


function deleteRegion(id, name)
{
	if (confirm("Confirm delete region '" + name + "'?"))
	{
		var ill_params = new Object;
		ill_params['item_id'] = id;
		getAjaxData("AjaxRegion", "", "delete_region", ill_params, "Saving...");
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
			case "delete_region":
				deleteRow('tr_' + t.item_id);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}


function editRegion(id)
{
	iframeLocation(iframe1, "index.php?cmd=AdminRegionsEdit&id=" + id);
	$("iframe1").show();
	setActiveRow(id);
}

function editRegionPostcodes(id)
{
	iframeLocation(iframe1, "index.php?cmd=AdminRegionPostcodes&id=" + id);
	$("iframe1").show();
	setActiveRow(id);
}

var last_region_class_change_id = "";

function setActiveRow(id)
{
	// Set the background of the selected row
	$('tr_' + id).className = "current";
	
	// Set the previously selected items to a normal background
	if (last_region_class_change_id != "" && last_region_class_change_id != id)
	{
		$('tr_' + last_region_class_change_id).className = "";
	}
	last_region_class_change_id = id;
}

function deleteRow(item_id)
{
	var tbl = document.getElementById('tbl_region_list');
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

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Regions &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$regions|@count}</strong> record{if $regions|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_region" name="add_new_region" value="Add New Region" onclick="javascript:$('div_new_region').show();$('region_name').focus();" />
						<div id="div_new_region" style="display: none; margin-top: 10px">
							<form id="form_new_region" name="form_new_region" action="index.php?cmd=AdminRegions" method="post">
								<input type="hidden" name="task" value="" />
								
								<table class="ianlist">
									<tr>
										<th style="vertical-align: top; width: 20%">Name</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="region_name" name="region_name" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Description</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="region_description" name="region_description" style="width: 250px;" /></td>
									</tr>
								</table>

								<div>
									<input type="button" id="cancel_region" name="cancel_region" value="Cancel" onclick="javascript:$('form_new_region').reset(); $('div_new_region').hide();" />&nbsp;
									<input type="button" id="reset_region" name="reset_region" value="Reset" onclick="javascript:$('form_new_region').reset(); return false;" />&nbsp;
									<input type="button" id="save_region" name="save_region" value="Save" onclick="javascript:submitbutton('save');" />
								</div>

							</form>
						</div>

					</td>
				</tr>

				<tr valign="top">
					<td>

						<table id="tbl_region_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Region</th>
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=region_loop from=$regions item=region}
							<tr id="tr_{$region->getId()}">
								<td style="text-align: center">{$region->getId()}</td>
								<td><span id="span_region_name_{$region->getId()}">{$region->getName()}<span></td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$region->getId()}" title="Go to postcodes" href="#" onclick="javascript:editRegionPostcodes({$region->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/world_go.png" alt="Region postcodes" title="Go to region postcodes" /></a>&nbsp;
									<a id="editBtn_{$region->getId()}" title="Edit" href="#" onclick="javascript:editRegion({$region->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/world_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$region->getId()}" title="Delete" href="#" onclick="javascript:deleteRegion({$region->getId()}, '{$region->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/world_delete.png" alt="Delete" title="Delete" /></a>&nbsp;
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<div style="height:670px;">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" 
				style="height: 670px; width: 100%; "></iframe>
			</div>
		</td>
	</tr>
</table>


{include file="footer.tpl"}