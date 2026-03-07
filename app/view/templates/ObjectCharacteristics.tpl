{include file="header2.tpl" title="Characteristic List"}

<script language="JavaScript" type="text/javascript">
{literal}

// Associates a given characteristic with this object.
function addCharacteristic(id)
{
//	alert('addCharacteristic(' + id + ')');
	var ill_params = new Object;
	ill_params.characteristic_id  = id;
	ill_params.parent_object_type = $F('parent_object_type');
	ill_params.parent_object_id   = $F('parent_object_id');
	getAjaxData('AjaxObjectCharacteristic', '', 'add_object_characteristic', ill_params, 'Adding...')
}

// Deletes the association of a given characteristic with this object.
function deleteCharacteristic(id)
{
	if (!confirm('Are you sure you wish to remove the association of this characteristic?'))
	{
		return;
	}
	var ill_params = new Object;
	ill_params.item_id            = id;
	ill_params.characteristic_id  = id;
	ill_params.parent_object_type = $F('parent_object_type');
	ill_params.parent_object_id   = $F('parent_object_id');
	getAjaxData('AjaxObjectCharacteristic', '', 'delete_object_characteristic', ill_params, 'Adding...')
}

/**
 * Toggles between the view / edit elements for a given characteristic.
 * @param integer id
 */
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

function toggleMultipleSelectItem(chk_radio_id, value_field_id, type)
{
//	alert ('In toggleMultipleSelectItem(chk_radio_id, value_field_id, type) = ' + chk_radio_id + ',' + value_field_id + ',' + type);
	chk_radio = $(chk_radio_id);

	switch (type)
	{
		case 'date':
			for (var i=1;i<=3;i++)
			{
				alert(i);
				switch (i)
				{
					case 1:
						extra = 'Day';
						break;
					case 2:
						extra = 'Month';
						break;
					case 3:
						extra = 'Year';
						break;
				}
//				alert(value_field_id + type + '_' + extra);
				value_field = $(value_field_id + type + '_' + extra);
				// cycle between calling value_field.disable() and value_field.enable()
				if (chk_radio.checked)
					value_field.enable();
				else
					value_field.disable();
			}
			break;

		case 'text':
		default:
			value_field = $(value_field_id + type);
			// cycle between calling value_field.disable() and value_field.enable()
			if (chk_radio.checked)
				value_field.enable();
			else
				value_field.disable();
			break;
	}
}

function toggleSingleSelectItem(form, chk_radio_id, valuefield, type)
{
//	alert ('In toggleSingleSelectItem(chk_radio_id, valuefield, type) = ' + chk_radio_id + ',' + valuefield + ',' + type);
	chk_radio = $(chk_radio_id);

	var form = $(form);

	var buttons = form.getInputs('radio');
	// -> only radio buttons

	buttons.each(function(item) // NOTE: variables outside this fuction can only be accessed if they don't have any _ in the variable name
	{
// 		alert("item.id : chk_radio_id : type : valuefield = " + item.id + " : " + chk_radio_id + ' : ' + type + ' : ' + valuefield);
// 		alert("type=" + type);
  		if (item.id == chk_radio_id)
  		{
  			switch (type)
			{
				case 'date':
					for (var i=1;i<=3;i++)
					{
//						alert(i);
						switch (i)
						{
							case 1:
								extra = 'Day';
								break;
							case 2:
								extra = 'Month';
								break;
							case 3:
								extra = 'Year';
								break;
						}
//						alert(valuefield + type + '_' + extra);
						value_field = $(valuefield + type + '_' + extra);
						// cycle between calling value_field.disable() and value_field.enable()
						//if (chk_radio.checked)
							value_field.enable();
						//else
						//	value_field.disable();
					}

					break;
				case 'text':
				default:
//					alert("valuefield + type = " + valuefield + type);
					value_field = $(valuefield + type);
					if (value_field)
					{
					// cycle between calling value_field.disable() and value_field.enable()
					//if (chk_radio.checked)
						value_field.enable();
					//else
					//	value_field.disable();
					}
//					alert("ending value field");
					break;
			}
  		}
  		else //make all other radios false and disable their associated value fields
  		{
//  			alert("Here = " + item.checked)
  			item.checked = false;

  			// need to get id of value field associated with this button
//  			alert("item.id.substr(0,7) = " + item.id.substr(0,7));

  			if (item.id.substr(0,7) == 'ignore_')
  			{
  				// trim off the 'ignore_' so that we have the id of the associated value field
  				var value_field_id = item.id.slice(7);
//  				alert("value_field_id = " + value_field_id);
//  				alert("type = " + type);
  				//$(value_field_id).disable();
  				var new_type = newType(item.id);
//  				alert("new_type = " + new_type);
  				switch (new_type)
				{
					case 'date':
						for (var i=1;i<=3;i++)
						{
//							alert(i);
							switch (i)
							{
								case 1:
									extra = 'Day';
									break;
								case 2:
									extra = 'Month';
									break;
								case 3:
									extra = 'Year';
									break;
							}
//							alert("value_field_id = " + value_field_id + '_' + extra);
							value_field = $(value_field_id + '_' + extra);
//							alert("value_field = " + value_field);
							// cycle between calling value_field.disable() and value_field.enable()
							//if (chk_radio.checked)
								value_field.disable();
							//else
							//	value_field.disable();
						}

						break;
					case 'text':
					default:
//						alert("value_field_id = " + value_field_id);
						value_field = $(value_field_id);
//						alert("value_field = " + value_field);
						//alert(value_field);
						// cycle between calling value_field.disable() and value_field.enable()
						//if (chk_radio.checked)
							value_field.disable();
						//else
						//	value_field.disable();
						break;
				}
  			}

  		}
	});



}

function newType(id)
{
	if (id.indexOf('boolean') > -1)
	{
		return 'boolean';
	}
	else if(id.indexOf('date') > -1)
	{
		return 'date';
	}
	else if (id.indexOf('text') > -1)
	{
		return 'text';
	}
	else
		return false;

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
//	alert(Object.toJSON(t));
//	alert(Form.getInputs(frm));

//	alert("parent_object_type = " + $F("parent_object_id"));


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
	getAjaxData('AjaxObjectCharacteristic', '', 'save_object_characteristic', ill_params, 'Adding...')
}


function AjaxObjectCharacteristic(data)
{
//	alert('AjaxObjectCharacteristic(' + data + ')');

	for (i = 1; i < data.length + 1; i++)
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action + "\nt.characteristic_screen = " + t.characteristic_screen);

		switch (t.cmd_action)
		{
			case 'add_object_characteristic':
				self.location.reload(true);
				break;

			case 'delete_object_characteristic':
				self.location.reload(true);
				break;

			case 'save_object_characteristic':
//					$('div_edit_characteristic_' + t.item_id).style.display = 'none';
//				$('div_show_characteristic_' + t.item_id).innerHTML = t.characteristic_screen;
//				$('div_show_characteristic_' + t.item_id).style.display = '';
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
	alert("row.getAttribute: " + row.getAttribute("id"));
	row.innerHTML = html;
}

{/literal}
</script>

<input type="hidden" name="parent_object_type" id="parent_object_type" value="{$parent_object_type}" />
<input type="hidden" name="parent_object_id" id="parent_object_id" value="{$parent_object_id}" />

{* NOTE: following span required in case this page is displayed in a popup box. Without this span the
popup would close as soon as the mouse rolled off, rather than when the user clicks another link to close *}
<span style="display:none;><a href="#" class="popup_closebox">Close</a></span>

<div ng-controller="CharacteristicController" ng-init="characteristicsSetUp({$initiative_id}, '{$parent_object_type}', {$parent_object_id})">
	<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr">
			<td>
				Characteristics &nbsp;&nbsp;|&nbsp;&nbsp;
				<span style="text-align: right"><strong>{$characteristic_array|@count}</strong> record{if $characteristic_array|@count != 1}s{/if}</span>
				{if $available_for_selection}
					&nbsp;&nbsp;|&nbsp;&nbsp;
					<input type="button" id="add_new_characteristic" name="add_new_characteristic"
						   value="Add New Characteristic" onclick="javascript:$('div_new_characteristic').show();"/>
					<div id="div_new_characteristic" style="display: none; margin-top: 10px">
						<form id="form_new_characteristic" name="form_new_characteristic">
							<input type="hidden" id="characteristic_type" name="characteristic_type" value="{$type}"/>
							<table class="ianlist">
								{*						<tr>
                                                            <th style="vertical-align: top; width: 30%">Type</th>
                                                            <td style="vertical-align: top; width: 70%">
                                                                <select id="characteristic_type" name="characteristic_type">
                                                                    {html_options values=$types output=$types}
                                                                </select>
                                                            </td>
                                                        </tr>
                                *}
								<tr>
									<th style="vertical-align: top; width: 30%">Characteristic</th>
									<td style="vertical-align: top; width: 70%">
										<select id="characteristic_id" name="characteristic_id">
											{html_options options=$available_for_selection}
										</select>
									</td>
								</tr>
								<tr>
									<th style="vertical-align: top; width: 30%">&nbsp;</th>
									<td style="vertical-align: top; width: 70%">
										<input type="button" id="add_characteristic" name="add_characteristic"
											   value="Add"
											   onclick="javascript:addCharacteristic($F('characteristic_id'));"/>
										<input type="button" id="cancel_characteristic" name="cancel_characteristic"
											   value="Cancel"
											   onclick="javascript:$('form_new_characteristic').reset(); $('div_new_characteristic').hide();"/>
									</td>
								</tr>
							</table>
						</form>
					</div>
				{/if}
			</td>
		</tr>
		<tr valign="top">
			<td>

				<table id="tbl_characteristic_list" class="adminlist" ng-repeat="char in defaultCharacteristics">
					<thead>
					<tr>
						<th style="background-color: #eee">
							<span style="float: left; padding-right: 5px;">#(char.characteristic.name)</span>
								<span style="float: right; padding-right: 5px;">
									<a title="Edit" href="#"
									   ng-click="char.createCharacteristic()"><img
												src="{$APP_URL}app/view/images/icons/add.png" alt="Edit"
												title="Edit"/></a>&nbsp;
								</span>
						</th>
					</tr>
					<thead>
					<tbody>
					<tr>
						<td colspan="8">


						</td>

					</tr>
					</tbody>
				</table>


				{foreach name=char_loop from=$characteristic_array item=characteristic}
					<table id="tbl_characteristic_list" class="adminlist">
						<thead>
						<tr>
							<th style="background-color: #eee">
								<span style="float: left; padding-right: 5px;">{$characteristic.name}</span>
								<span style="float: right; padding-right: 5px;">
									<a id="viewBtn_{$characteristic.id}" title="Edit" href="#"
									   onclick="javascript:editCharacteristic({$characteristic.id});return false;"><img
												src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit"
												title="Edit"/></a>&nbsp;
									<a id="deleteBtn_{$characteristic.id}" title="Delete" href="#"
									   onclick="javascript:deleteCharacteristic({$characteristic.id});return false;"><img
												src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete"
												title="Delete"/></a>
								</span>
							</th>
						</tr>
						<thead>
						<tbody>
						<tr>
							<td colspan="8">

								<div id="div_show_characteristic_{$characteristic.id}">
									<table style="padding-left: 25px">

										{if $characteristic.attributes && !$characteristic.options}

											{foreach name=element_loop from=$characteristic.elements item=element}
												{if $element.data_type == 'boolean' && $element.value == "1"}
												<tr style="height: 20px">
												<td>
													{$element.name}
												</td>
												{elseif $element.data_type != 'boolean' && $element.value != ""}
												<tr style="height: 20px">
												<td>
													{$element.name}
												</td>
												{/if}

												{if $element.data_type == 'boolean'}
													{if $element.value == "1"}
														<td><img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" /></td>
													</tr>
													{/if}
												{elseif $element.data_type == 'date' && $element.value != ""}
													<td>{$element.value|date_format:"%d %B %Y"}</td>
												</tr>
												{elseif $element.data_type == 'text' && $element.value != ""}
													<td><em>{$element.value}</em></td>
												</tr>
												{/if}


											{/foreach}

										{elseif $characteristic.attributes && $characteristic.options}

											{if $characteristic.multiple_select}
												{foreach name=element_loop from=$characteristic.elements item=element}
													{if $element.data_type == 'boolean' && $element.value == "1"}
													<tr style="height: 20px">
														<td>
															{$element.name}
														</td>
													{elseif $element.data_type != 'boolean' && $element.value != ""}
													<tr style="height: 20px">
														<td>
															{$element.name}
														</td>
													{/if}


													{if $element.data_type == 'boolean'}
														{if $element.value == "1"}
															<td><img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" /></td>
														</tr>
														{/if}
													{elseif $element.data_type == 'date' && $element.value != ""}
														<td>{$element.value|date_format:"%d %B %Y"}</td>
													</tr>
													{elseif $element.data_type == 'text' && $element.value != ""}
														<td>{$element.value}</td>
													</tr>
													{/if}


												{/foreach}
											{else}
												{foreach name=element_loop from=$characteristic.elements item=element}
													{if $element.data_type == 'boolean' && $element.value == "1"}
														<tr style="height: 20px">
															<td>
																{$element.name}
															</td>
													{elseif $element.data_type != 'boolean' && $element.value != ""}
														<tr style="height: 20px">
															<td>
																{$element.name}
															</td>
													{/if}

													{if $element.data_type == 'boolean'}
														{if $element.value == "1"}
															<td><img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" /></td>
														</tr>
														{/if}
													{elseif $element.data_type == 'date' && $element.value != ""}
														<td>{$element.value|date_format:"%d %B %Y"}</td>
														</tr>
													{elseif $element.data_type == 'text' && $element.value != ""}
														<td>{$element.value}</td>
														</tr>
													{/if}


												{/foreach}
											{/if}

										{elseif !$characteristic.attributes || $characteristic.options}

											<tr style="height: 15px">
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

									{if $characteristic.attributes && !$characteristic.options}
										<form id="frm_elements_{$characteristic.id}"
											  name="frm_elements_{$characteristic.id}" action="#">

											<table class="adminlist" style="width: 100%">

												{foreach name=element_loop from=$characteristic.elements item=element}
													<tr class="current">
														{if $element.data_type == 'boolean'}
															<td>{$element.name}</td>
															<td>
																<input type="radio"
																	   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																	   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																	   value="1"{if $element.value == 1} checked="checked"{/if} />
																Yes
																<input type="radio"
																	   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																	   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																	   value="0"{if $element.value == 0} checked="checked"{/if} />
																No
															</td>
														{elseif $element.data_type == 'date'}
															<td>{$element.name}</td>
															<td style="width: 100%">
																{html_select_date
																prefix           = "`$smarty.foreach.element_loop.iteration`_`$characteristic.id`_`$element.id`_`$element.object_characteristic_id`_0_`$element.object_characteristic_element_id`_date_"
																time             = "`$element.value`"
																start_year       = "-1"
																end_year       = "+5"
																field_order      = "DMY"
																day_value_format = "%02d"}
																{if $element.value == ""}* new value{/if}
															</td>
														{elseif $element.data_type == 'text'}
															<td>{$element.name}</td>
															<td style="width: 100%">
																<input type="text"
																	   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																	   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																	   value="{$element.value}" style="width: 90%"/>
															</td>
														{/if}
													</tr>
												{/foreach}

												<tr class="current">
													<td colspan="3">
											<span style="float: right">
												<input type="button" value="Update"
													   onclick="javascript:saveCharacteristic({$characteristic.id}, 'elements'); return false"/>
												<input type="button" value="Cancel"
													   onclick="javascript:editCharacteristic({$characteristic.id}); return false"/>
											</span>
													</td>
												</tr>
											</table>
										</form>
									{elseif $characteristic.attributes && $characteristic.options}
										<form id="frm_elements_{$characteristic.id}"
											  name="frm_elements_{$characteristic.id}" action="#">
											<table class="adminlist" style="width:100%;">
												{if $characteristic.multiple_select}
													{foreach name=element_loop from=$characteristic.elements item=element}
														<tr class="current">
															{if $element.data_type == 'boolean'}
																<td><input type="checkbox"
																		   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																		   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"{if $element.value == 1} checked="checked"{/if} />
																</td>
																<td>{$element.name}</td>
																<td>&nbsp;</td>
															{elseif $element.data_type == 'date'}
																<td><input type="checkbox"
																		   id="ignore_{$characteristic.id}_{$smarty.foreach.element_loop.iteration}"
																		   name="ignore_{$characteristic.id}_{$smarty.foreach.element_loop.iteration}"{if $element.value != ""} checked="checked"{/if}
																		   onchange="javascript:toggleMultipleSelectItem(this.id, '{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_', 'date');return false;"/>
																</td>
																<td>{$element.name}</td>
																<td style="width: 100%">
																	{html_select_date
																	prefix           = "`$smarty.foreach.element_loop.iteration`_`$characteristic.id`_`$element.id`_`$element.object_characteristic_id`_0_`$element.object_characteristic_element_id`_date_"
																	time             = "`$element.value`"
																	start_year       = "-1"
																	end_year       = "+5"
																	field_order      = "DMY"
																	day_value_format = "%02d"}
																	{if $element.value == ""}* new value{/if}
																</td>
															{elseif $element.data_type == 'text'}
																<td><input type="checkbox"
																		   id="ignore_{$characteristic.id}_{$smarty.foreach.element_loop.iteration}"
																		   name="ignore_{$characteristic.id}_{$smarty.foreach.element_loop.iteration}"{if $element.value != ""} checked="checked"{/if}
																		   onchange="javascript:toggleMultipleSelectItem(this.id, '{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_', 'text');return false;"/>
																</td>
																<td>{$element.name}</td>
																<td style="width:100%">
																	<input type="text"
																		   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   value="{$element.value}" style="width: 90%"/>
																</td>
															{/if}
														</tr>
													{/foreach}
												{else}
													{foreach name=element_loop from=$characteristic.elements item=element}
														<tr class="current">
															{if $element.data_type == 'boolean'}
																<td><input type="radio"
																		   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"
																		   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_boolean"{if $element.value == 1} checked="checked"{/if}
																		   onchange="javascript:toggleSingleSelectItem('frm_elements_{$characteristic.id}', this.id, '{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_', 'boolean');return false;"/>
																</td>
																<td>{$element.name}</td>
																<td>&nbsp;</td>
															{elseif $element.data_type == 'date'}
																<td><input type="radio"
																		   id="ignore_{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   name="ignore_{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"{if $element.value != ""} checked="checked"{/if}
																		   onchange="javascript:toggleSingleSelectItem('frm_elements_{$characteristic.id}', this.id, '{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_', 'date');return false;"/>
																</td>
																<td>{$element.name}</td>
																<td style="width: 100%">
																	{if $element.value == ""}
																		{assign var="all_extra" value="disabled"}
																	{else}
																		{assign var="all_extra" value="enabled"}
																	{/if}
																	{html_select_date
																	prefix           = "`$smarty.foreach.element_loop.iteration`_`$characteristic.id`_`$element.id`_`$element.object_characteristic_id`_0_`$element.object_characteristic_element_id`_date_"
																	time             = "`$element.value`"
																	start_year       = "-1"
																	end_year       = "+5"
																	field_order      = "DMY"
																	day_value_format = "%02d"
																	all_extra         = $all_extra}
																	{if $element.value == ""}* new value{/if}
																</td>
															{elseif $element.data_type == 'text'}
																<td><input type="radio"
																		   id="ignore_{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   name="ignore_{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"{if $element.value != ""} checked="checked"{/if}
																		   onchange="javascript:toggleSingleSelectItem('frm_elements_{$characteristic.id}', this.id, '{$smarty.foreach.element_loop.iteration}_{$element.object_characteristic_id}_{$element.object_characteristic_element_id}_', 'text');return false;"/>
																</td>
																<td>{$element.name}</td>
																<td style="width: 100%">
																	<input type="text"
																		   id="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   name="{$smarty.foreach.element_loop.iteration}_{$characteristic.id}_{$element.id}_{$element.object_characteristic_id}_0_{$element.object_characteristic_element_id}_{$element.data_type}"
																		   value="{$element.value}"{if $element.value == ""} disabled="disabled"{/if}
																		   style="width: 90%"/>
																</td>
															{/if}
														</tr>
													{/foreach}
												{/if}
												<tr class="current">
													<td colspan="3">
											<span style="float: right">
												<input type="button" value="Update"
													   onclick="javascript:saveCharacteristic({$characteristic.id}, 'elements'); return false"/>
												<input type="button" value="Cancel"
													   onclick="javascript:editCharacteristic({$characteristic.id}); return false"/>
											</span>
													</td>
												</tr>
											</table>
										</form>
									{elseif $characteristic.attributes == 0 || $characteristic.options == 0}
										<form id="frm_characteristic_{$characteristic.id}"
											  name="frm_characteristic_{$characteristic.id}">
											{* NOTE: the input items in the following form are preceded by a 0 in order to make the input naming convention match the
                                            multiple elements form above *}
											<input type="hidden" name="characteristic_data_type"
												   id="characteristic_data_type" value="{$characteristic.data_type}"/>
											<table class="adminlist" style="width:100%;">
												<tr class="current">
													<td>
														{if $characteristic.data_type == 'boolean'}
															<input type="radio"
																   id="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_boolean"
																   name="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_boolean"
																   value="1"{if $characteristic.value == 1} checked="checked"{/if} />
															Yes
															<input type="radio"
																   id="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_boolean"
																   name="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_boolean"
																   value="0"{if $characteristic.value == 0} checked="checked"{/if} />
															No
														{elseif $characteristic.data_type == 'date'}
															{html_select_date
															prefix           = "0_`$characteristic.id`_0_0_`$characteristic.object_characteristic_value_id`_0_date_"
															time             = "`$characteristic.value`"
															start_year       = "-1"
															end_year       = "+5"
															field_order      = "DMY"
															day_value_format = "%02d"}
														{elseif $characteristic.data_type == 'text'}
															<input type="text"
																   id="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_text"
																   name="0_{$characteristic.id}_0_0_{$characteristic.object_characteristic_value_id}_0_text"
																   value="{$characteristic.value}"/>
														{/if}
													</td>
												</tr>
												<tr class="current">
													<td colspan="3">
													<span style="float: right">
														<input type="button" value="Update"
															   onclick="javascript:saveCharacteristic({$characteristic.id}, 'characteristic'); return false"/>
														<input type="button" value="Cancel"
															   onclick="javascript:editCharacteristic({$characteristic.id}); return false"/>
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
</div>

{include file="footer.tpl"}