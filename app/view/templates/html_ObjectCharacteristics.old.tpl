<script language="JavaScript" type="text/javascript">
{literal}

function editCharacteristic(id)
{
	if ($("div_edit_characteristic_" + id).style.display == "")
	{
		// do validation here
		$("div_edit_characteristic_" + id).style.display = "none";
		$("div_show_characteristic_" + id).style.display = "";
	}
	else
	{
		$("div_edit_characteristic_" + id).style.display = "";
		$("div_show_characteristic_" + id).style.display = "none";
	}
}

function doTest()
{
	alert("Here");
	var frm = $("frm_elements_101");
	var obj = frm.serialize();
	alert(obj);
}

function saveCharacteristic(characteristic_id, form_type)
{
//	alert('saveCharacteristic(' + characteristic_id + ', ' + form_type + ')');
//	alert("frm_" + form_type + "_" + characteristic_id);
	frm = $("frm_" + form_type + "_" + characteristic_id);

//frm = $("frm_elements_1");
//alert(frm);

//	alert(frm.getInputs());
	var t = frm.serialize(true);
	alert(Object.toJSON(t));
//	alert(Form.getInputs(frm));

	alert("parent_object_type = " + $F("parent_object_id"));
	
	
	var ill_params = new Object;

	//set item_id - the id of the object we are dealing with

	ill_params.item_id                          = characteristic_id;
	ill_params.form_data                        = t;
	ill_params.parent_object_type       		= $F('parent_object_type');
	ill_params.parent_object_id              	= $F('parent_object_id');

/*

	ill_params.characteristic_name              = $F('characteristic_name');
	ill_params.characteristic_description       = $F('characteristic_description');
	ill_params.characteristic_type              = $F('characteristic_type');
	ill_params.characteristic_multiple_elements = $F('characteristic_multiple_elements');
	ill_params.characteristic_multiple_select   = $F('characteristic_multiple_select');
	ill_params.characteristic_data_type         = $F('characteristic_data_type');
*/
//	alert(ill_params.filter_name);
	//set the field/value pairs - eg telephone/0121....


//	var t = prepareSubmit();
	
//	ill_params.line_items_include = t.line_items_include;
//	ill_params.line_items_exclude = t.line_items_exclude;
//	ill_params.results_format = $F("results_format");
	
//	alert('do ajax');
	getAjaxData('AjaxObjectCharacteristic', '', 'test', ill_params, 'Adding...')
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
				$('div_new_characteristic').hide();
				break;

			default:
				alert('No cmd_action specified');
				break;
		}
	}
}

function AjaxObjectCharacteristic(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case 'test':
				alert(t.test);
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
	alert("row.getAttribute: " + row.getAttribute("id"));
	row.innerHTML = html;
}

{/literal}
</script>

<input type="hidden" name="parent_object_type" id="parent_object_type" value="app_domain_{$parent_object_type}" />
<input type="hidden" name="parent_object_id" id="parent_object_id" value="{$parent_object_id}" />

{* NOTE: following span required in case this page is displayed in a popup box. Without this span the 
popup would close as soon as the mouse rolled off, rather than when the user clicks another link to close *}			
<span style="display:none;><a href="#" class="popup_closebox">Close</a></span>

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
							<th style="vertical-align: top; width: 30%">Type</th>
							<td style="vertical-align: top; width: 70%">
								<select id="characteristic_type" name="characteristic_type">
									{html_options values=$types output=$types}
								</select>
							</td>
						</tr>
							<th style="vertical-align: top; width: 30%">Characteristic</th>
							<td style="vertical-align: top; width: 70%">
								<select id="characteristic" name="characteristic">
									{html_options options=$available_for_selection}
								</select>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</td>
	</tr>
	<tr valign="top">
		<td>
			{foreach name=char_loop from=$characteristic_array item=characteristic}
				
				<table id="tbl_characteristic_list" class="adminlist">
					<thead>
						<tr>
							<th style="background-color:#eee">
								<span style="float: left; padding-right: 5px;">
									{$characteristic.name}
								</span>
							{*<td style="text-align: center; vertical-align: middle">*}
								<span style="float: right; padding-right: 5px;">
									<a id="viewBtn_{$characteristic.id}" title="Edit" href="#" onclick="javascript:editCharacteristic({$characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$characteristic.id}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
								</span>
							</th>
						</tr>
					<thead>
					<tbody>
						<tr>
							<td colspan="8">
								<div id="div_show_characteristic_{$characteristic.id}">
									<table style="padding-left: 25px;">
										{if $characteristic.multiple_elements}
											{foreach name=element_loop from=$characteristic.elements item=element}
											<tr style="height:20px">
												<td>{$element.name}</td>
												<td>
													{if $element.data_type == 'boolean'}
														{if $element.value == 0}
															<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
														{else}
															<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
														{/if}
													{elseif $element.data_type == 'date'}
														{$element.value|date_format:"%d %B %Y"}
													{elseif $element.data_type == 'text'}
														{$element.value}
													{/if}
												<td>
											</tr>
											{/foreach}	
										{else}
											<tr style="height:15px">
												<td>
												{if $characteristic.data_type == 'boolean'}
													{if $characteristic.value == 0}
														<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
													{else}
														<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
													{/if}
												{elseif $characteristic.data_type == 'date'}
													{$characteristic.value|date_format:"%d %B %Y"}
												{elseif $characteristic.data_type == 'text'}
													{$characteristic.value}
												{/if}
												</td>
											</tr>
										{/if}
									</table>
								</div><!-- end of div_show_characteristic-->
								<div id="div_edit_characteristic_{$characteristic.id}" style="display:none;">
									
								{if $characteristic.multiple_elements}
									<form id="frm_elements_{$characteristic.id}" name="frm_elements_{$characteristic.id}" action="#">
									
									{*<input type="hidden" name="characteristic_id" id="characteristic_id" value="{$characteristic.id}" />*}
									{*<input type="hidden" name="characteristic_data_type" id="characteristic_data_type" value="{$characteristic.data_type}" />*}
									
									<table class="adminlist" style="width:100%;">
												
										{* NOTE: we need to two loops since multiple select boolean needs one select one one row,
										but muliple select for date and text needs one row per item*}
										{assign var="boolean_count" value="0"}
										{foreach name=element_loop from=$characteristic.elements item=element}
											{if $element.data_type == 'boolean'}
												{if $boolean_count == "0"}
											<tr class="current">
											<td style="width:100%">
											<select id="{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_boolean" name="{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_boolean" size="6"{if $characteristic.multiple_select} multiple{/if} style="width: 99%">
												{/if}
												<option value="{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_boolean_{$element.id}" {if $element.value}selected{/if}>{$element.name}</option>
												{assign var="boolean_count" value="1"}
											{/if}		
										{/foreach}
									{if $boolean_count == "1"}
											</select>
										</td>
									</tr>
									{/if}
									{* Now check if we need to process the loop again - ie if this characteristic wasn't a boolean then we'll need
									to - otherwise skip*}		
									{if $boolean_count == "0"}
										{foreach name=element_loop from=$characteristic.elements item=element}
									<tr class="current">
											{if $element.data_type == 'date'}
											<td>{$element.name}</td>
											<td style="width:100%">	
													{html_select_date 
														prefix           = "`$smarty.foreach.element_loop.iteration`_`$element.object_characteristic_id`_`$element.object_characteristic_element_id`_date_"
														time             = "`$element.value`"
														start_year       = "-1" 
														field_order      = "DMY"
														day_value_format = "%02d"}
												{if $element.value == ""}* new value{/if}
											</td>
											{elseif $element.data_type == 'text'}
											<td>{$element.name}</td>
											<td style="width:100%">	
												<input type="text" id="{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_{$element.data_type}" name="{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_{$element.data_type}" value="{$element.value}" style="width:90%"/>
											</td>
											{/if}
									</tr>
										{/foreach}
									{/if}
									<tr class="current">
										<td colspan="2" >
											<span style="float: right">
												<input type="button" value="Update" onclick="javascript:saveCharacteristic({$characteristic.id}, 'elements'); return false" />&nbsp;|&nbsp;
												<input type="button" value="Cancel" onclick="javascript:editCharacteristic({$characteristic.id}); return false" />
											</span>
										</td>
									</tr>
									</table>
									</form>	
									
								{else}
									<form id="frm_characteristic_{$characteristic.id}" name="frm_characteristic_{$characteristic.id}">
									
										{*<input type="hidden" name="characteristic_id" id="characteristic_id" value="{$characteristic.id}" />*}
										<input type="hidden" name="characteristic_data_type" id="characteristic_data_type" value="{$characteristic.data_type}" />
										<table class="adminlist" style="width:100%;">
											<tr class="current">
												<td>
													{if $characteristic.data_type == 'boolean'}
															<input type="radio" id="{$characteristic.id}_{$object_characteristic_value_id}_boolean" name="{$characteristic.id}_{$object_characteristic_value_id}_boolean" value="1" {if $characteristic.value == 1}checked{/if} />Yes
															<input type="radio" id="{$characteristic.id}_{$object_characteristic_value_id}_boolean" name="{$characteristic.id}_{$object_characteristic_value_id}_boolean" value="0" {if $characteristic.value == 0}checked{/if} />No
													{elseif $characteristic.data_type == 'date'}
															{html_select_date 
																prefix           = "`$characteristic.id`_`$object_characteristic_value_id`_"
																time             = "`$characteristic.value`"
																start_year       = "2001" 
																field_order      = "DMY"
																day_value_format = "%02d"}
													{elseif $characteristic.data_type == 'text'}
														<input type="text" id="{$characteristic.id}_{$object_characteristic_value_id}_text" name="id_{$characteristic.id}_{$object_characteristic_value_id}_text" value="{$characteristic.value}" />
													{/if}
												</td>
											</tr>
											<tr class="current">
												<td colspan="2" >
													<span style="float: right">
														<input type="button" value="Update" onclick="javascript:saveCharacteristic({$characteristic.id}, 'characteristic'); return false" />&nbsp;|&nbsp;
														<input type="button" value="Cancel" onclick="javascript:editCharacteristic({$characteristic.id}); return false" />
													</span>
												</td>
											</tr>
										</table>
									</form>	
								{/if}
								</div><!-- end of div_edit_characteristic-->
							</td>
						</tr>
					</table>
				{/foreach}
			</td>
		</tr>
	</tbody>
</table>




