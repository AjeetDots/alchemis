{include file="header.tpl" title="Tiered Characteristic List"}

<script language="JavaScript" type="text/javascript">
{literal}

function editCharacteristic(id)
{
iframeLocation(	iframe1, "index.php?cmd=TieredCharacteristicEdit&id=" + id);
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
	ill_params.value       = $F('value');
	ill_params.parent_id   = $F('parent_id');
	ill_params.category_id = $F('category_id');
//	alert('saveCharacteristic() 2');
	getAjaxData('AjaxTieredCharacteristic', '', 'add_tiered_characteristic', ill_params, 'Adding...')
}


/**
 * Ajax return data handlers
 */
function AjaxTieredCharacteristic(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case 'add_tiered_characteristic':
//				addNewLine(t.item_id, t.line_html);
//				$('form_new_characteristic').reset();
//				$('div_new_characteristic').hide();
				self.location.reload(true);
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

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Tiered Characteristics &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$characteristics|@count}</strong> record{if $characteristics|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_characteristic" name="add_new_characteristic" value="Add New Tiered Characteristic" onclick="javascript:$('div_new_characteristic').show();" />
						<div id="div_new_characteristic" style="display: none; margin-top: 10px">
							<form id="form_new_characteristic" name="form_new_characteristic">
							

								<table class="ianlist">
									<tr>
										<th style="vertical-align: top; width: 20%">Parent Category</th>
										<td colspan="2" style="vertical-align: top; width: 80%">
											<select id="parent_id" name="parent_id">
												<option value="0">- None -</option>
												{html_options options=$parents}
											</select>
										</td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Category</th>
										<td colspan="2" style="vertical-align: top; width: 80%"><input type="text" id="value" style="width: 250px;" /></td>
									</tr>
{*									<tr>
										<th style="vertical-align: top; width: 20%">Category</th>
										<td colspan="2" style="vertical-align: top; width: 80%">
											<select id="category_id" name="category_id">
												{html_options options=$categories}
											</select>
										</td>
									</tr>
*}								</table>

								<input type="hidden" id="category_id" name="category_id" value="1" />
							
								<div>
									<input type="button" id="cancel_characteristic" name="cancel_characteristic" value="Cancel" onclick="javascript:$('form_new_characteristic').reset(); $('div_new_characteristic').hide();" />&nbsp;
									<input type="button" id="reset_characteristic" name="reset_characteristic" value="Reset" onclick="javascript:$('form_new_characteristic').reset(); return false;" />&nbsp;
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
{*									<th style="width: 3%">ID</th>*}
									<th style="text-align: left">Category</th>
									<th style="text-align: left">Sub-Category</th>
{*									<th style="width: 10%; text-align: center">Category</th>*}
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=char_loop from=$characteristics item=characteristic}
							<tr id="tr_{$characteristic->getId()}">
{*								<td style="text-align: center">{$characteristic->getId()}</td>*}

								{if $characteristic->getParent()}
									<td>{*$characteristic->getParent()*}</td>
									<td>{$characteristic->getValue()}</td>
								{else}
									<td colspan="2">{$characteristic->getValue()}</td>
								{/if}
								
{*								<td>{$characteristic->getCategory()}</td>*}
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$characteristic->getId()}" title="Edit" href="#" onclick="javascript:editCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$characteristic->getId()}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_delete.png" alt="Delete" title="Delete" /></a>
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

{include file="footer.tpl"}