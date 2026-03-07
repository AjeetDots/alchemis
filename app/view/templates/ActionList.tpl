{include file="header.tpl" title="Characteristic List"}

<script language="JavaScript" type="text/javascript">
{literal}

function editCharacteristic(id)
{
	iframeLocation(iframe1, "index.php?cmd=CharacteristicEdit&type=company&id=" + id);
	$("iframe1").show();
	setActiveRow(id);
}

var last_filter_class_change_id = "";

function setActiveRow(id)
{
	// Set the background of the selected row
	$('tr_' + id).className = "current";
	
	// Set the previously selected items to a normal background
	if (last_filter_class_change_id != "" && last_filter_class_change_id != id)
	{
		$('tr_' + last_filter_class_change_id).className = "";
	}
	last_filter_class_change_id = id;
}

function saveCharacteristic()
{
//	alert('saveCharacteristic()');
	var ill_params = new Object;
	ill_params.name            = $F('name');
	ill_params.description     = $F('description');
	ill_params.type            = $F('type');
	ill_params.attributes      = $F('attributes');
	ill_params.options         = $F('options');
	ill_params.multiple_select = $F('multiple_select');
	ill_params.data_type       = $F('data_type');
	getAjaxData('AjaxCharacteristic', '', 'add_characteristic', ill_params, 'Adding...')
}


/**
 * Ajax return data handlers
 */
function AjaxCharacteristic(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case 'add_characteristic':
//				alert('Characteristic saved');
				addNewLine(t.item_id, t.line_html);
				$('form_new_characteristic').reset();
				toggleCharacteristicDataType();
				$('div_new_characteristic').hide();
				break;

			default:
				alert('No cmd_action specified');
				break;
		}
	}
}

function addNewLine(id, html)
{
	var tbl = $('tbl_characteristic_list');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", "tr_" + id);
//	alert("row.getAttribute: " + row.getAttribute("id"));
	row.innerHTML = html;
}

function switchTypeIcon()
{
	var sel = $F('type');
	switch (sel)
	{
		{/literal}
		case 'company':
			$('img_type').src   = '{$APP_URL}app/view/images/icons/building.png';
			$('img_type').alt   = 'Company';
			$('img_type').title = 'Company';
			break;

		case 'post':
			$('img_type').src   = '{$APP_URL}app/view/images/icons/group.png';
			$('img_type').alt   = 'Post';
			$('img_type').title = 'Post';
			break;

		case 'post initiative':
			$('img_type').src   = '{$APP_URL}app/view/images/icons/user_comment.png';
			$('img_type').alt   = 'Post Initiative';
			$('img_type').title = 'Post Initiative';
			break;

		default:
			$('img_type').src   = '';
			$('img_type').alt   = '';
			$('img_type').title = '';
			$('img_type').hide();
			break;
	{literal}
	}
}

function toggleCharacteristicDataType()
{
	if ($F('attributes') || $F('options'))
	{
		if ($('div_datatype').style.display == 'block' || $('div_datatype').style.display == '') 
		{
			new Effect.BlindUp($('div_datatype'), {duration: 0.3});
		}
	}
	else
	{
		if ($('div_datatype').style.display == 'none') 
		{
			new Effect.BlindDown($('div_datatype'), {duration: 0.3});
		}
	}
	return false;
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Characteristics &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$characteristics|@count}</strong> record{if $characteristics|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_characteristic" name="add_new_characteristic" value="Add New Characteristic" onclick="javascript:$('div_new_characteristic').show();" />
						<div id="div_new_characteristic" style="display: none; margin-top: 10px">
							<form id="form_new_characteristic" name="form_new_characteristic">

								<table class="ianlist">
									<tr>
										<th style="vertical-align: top; width: 20%">Name</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="name" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Description</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="description" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Type</th>
										<td colspan="2" style="vertical-align: top; width: 80%">
											<select id="type" name="type" onchange="javascript:switchTypeIcon();">
												{html_options values=$types output=$types}
											</select>
											&nbsp;&nbsp;&nbsp;<img id="img_type" src="" />
										</td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Attributes</th>
										<td colspan="2" style="vertical-align: top; width: 80%">
											<input type="checkbox" id="attributes" name="attributes" onchange="toggleCharacteristicDataType(); return false;" />
										</td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Options</th>
										<td style="vertical-align: top; width: 40%">
											<input type="checkbox" id="options" name="options" 
												onchange="new Effect.toggle($('div_multiple_select'), 'blind', {literal}{duration: 0.3}{/literal}); toggleCharacteristicDataType(); return false;" />
										</td>
										<td style="vertical-align: top; width: 40%; padding: 0px">
											<div id="div_multiple_select" style="display: none; padding: 0px">
												<table style="border-collapse: collapse; padding: 0px">
													<tr>
														<th>Multiple Select</th>
														<td><input type="checkbox" id="multiple_select" name="multiple_select" /></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
							
								<div id="div_datatype">
									<table class="ianlist">
										<tr>
											<th style="vertical-align: top; width: 20%">Data Type</th>
											<td style="vertical-align: top; width: 80%">
												<select id="data_type" name="data_type">
													{html_options values=$data_types output=$data_types}
												</select>
											</td>
										</tr>
									</table>
								</div>

								<div>
									<input type="button" id="cancel_characteristic" name="cancel_characteristic" value="Cancel" onclick="javascript:$('form_new_characteristic').reset(); toggleCharacteristicDataType(); $('div_new_characteristic').hide();" />&nbsp;
									<input type="button" id="reset_characteristic" name="reset_characteristic" value="Reset" onclick="javascript:$('form_new_characteristic').reset(); toggleCharacteristicDataType(); return false;" />&nbsp;
									<input type="button" id="save_characteristic" name="save_characteristic" value="Save" onclick="javascript:saveCharacteristic();" />
								</div>

							</form>
						</div>

					</td>
				</tr>

				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Characteristic</th>
									<th>Description</th>
									<th style="width: 10%; text-align: center">Type</th>
									<th style="width: 10%; text-align: center">Attributes</th>
									<th style="width: 10%; text-align: center">Options</th>
									<th style="width: 10%; text-align: center">Multiple Select</th>
									<th style="width: 10%; text-align: center">Data Type</th>
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=char_loop from=$characteristics item=characteristic}
							<tr id="tr_{$characteristic->getId()}">
								<td style="text-align: center">{$characteristic->getId()}</td>
								<td>{$characteristic->getName()}</td>
								<td>{$characteristic->getDescription()}</td>
								<td style="text-align: center; vertical-align: middle">
								{if $characteristic->getType() == 'company'}
									<img src="{$APP_URL}app/view/images/icons/building.png" alt="Company" title="Company" />
								{elseif $characteristic->getType() == 'post'}
									<img src="{$APP_URL}app/view/images/icons/group.png" alt="Post" title="Post" />
								{elseif $characteristic->getType() == 'post_initiative'}
									<img src="{$APP_URL}app/view/images/icons/user_comment.png" alt="Post Initiative" title="Post Initiative" />
								{else}
									{$characteristic->getType()|capitalize}
								{/if}
								</td>
								<td style="text-align: center; vertical-align: middle">{if $characteristic->hasAttributes()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
								<td style="text-align: center; vertical-align: middle">{if $characteristic->hasOptions()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
								<td style="text-align: center; vertical-align: middle">{if $characteristic->hasMultipleSelect()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
								<td style="text-align: center; vertical-align: middle">{$characteristic->getDataType()|capitalize}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$characteristic->getId()}" title="Edit" href="#" onclick="javascript:editCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$characteristic->getId()}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

<script language="JavaScript" type="text/javascript">
	switchTypeIcon();
</script>

{include file="footer.tpl"}