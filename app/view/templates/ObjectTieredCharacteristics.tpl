{include file="header2.tpl" title="Tiered Characteristics"}

<script language="JavaScript" type="text/javascript">

// array to hold which tiered characteristics have been downloaded
var top_level = new Array();
{foreach name=root_loop from=$root_tiered_characteristics item=char}
top_level[{$smarty.foreach.root_loop.iteration-1}] = {$char.id};
{/foreach}


{literal}

function handleTier(tiered_characteristic_id)
{
//	alert('handleTier(' + tiered_characteristic_id + ')');
//	alert(top_level.indexOf(tiered_characteristic_id));
	if (top_level.indexOf(tiered_characteristic_id) == -1)
	{
		$('div_tier').show();
		$('div_tier_none').hide();
		$('tier').disabled = false;
	}
	else
	{
		$('div_tier').hide();
		$('div_tier_none').show();
		$('tier').selectedIndex = 0;
		$('tier').disabled = true;
	}
}

function handleTierParent(tiered_characteristic_id)
{
//	alert('handleTier(' + tiered_characteristic_id + ')');
//	alert(top_level.indexOf(tiered_characteristic_id));
	if (top_level.indexOf(tiered_characteristic_id) == -1)
	{
		$('div_parent_tier').show();
		$('div_parent_tier_none').hide();
		$('parent_tier').disabled = false;
	}
	else
	{
		$('div_parent_tier').hide();
		$('div_parent_tier_none').show();
		$('parent_tier').selectedIndex = 0;
		$('parent_tier').disabled = true;
	}
}



function addTieredCharacteristic(id)
{
//	alert('addTieredCharacteristic(' + id + ')');
//	alert($('div_tier').visible());
	if ($('div_tier').visible()) 
	{
		var_tier  = $F('tier');
	}
	else
	{
		var_tier = 0;
	}
	
	var ill_params = new Object;
	ill_params.item_id = null;
	ill_params.parent_object_type       = $F('parent_object_type');
	ill_params.parent_object_id         = $F('parent_object_id');
	ill_params.tiered_characteristic_id = $F('tiered_characteristic_id');
	ill_params.tier  = var_tier;
	
	getAjaxData('AjaxObjectTieredCharacteristic', '', 'add_object_tiered_characteristic', ill_params, 'Adding...')
}

function addTieredCharacteristicParent(id)
{
//	alert('addTieredCharacteristic(' + id + ')');
//	alert($('div_tier').visible());
	if ($('div_parent_tier').visible()) 
	{
		var_tier  = $F('parent_tier');
	}
	else
	{
		var_tier = 0;
	}
	
	var ill_params = new Object;
	ill_params.item_id = null;
	ill_params.parent_object_type       = $F('parent_object_type');
	ill_params.parent_object_id         = $F('parent_object_id');
	ill_params.parent_company = $F('parent_company_id');
	ill_params.tiered_characteristic_id = $F('parent_tiered_characteristic_id');
	ill_params.tier  = var_tier;
	
	getAjaxData('AjaxObjectTieredCharacteristic', '', 'add_parent_object_tiered_characteristic', ill_params, 'Adding...')
}

// Deletes the association of a given characteristic with this object.
function deleteTieredCharacteristic(id)
{
//	alert('deleteTieredCharacteristic(' + id + ')');
	if (!confirm('Are you sure you wish to remove the association of this category?'))
	{
		return
	}
	var ill_params = new Object;
	ill_params.item_id            = id;
	ill_params.tiered_characteristic_id  = id;
	ill_params.parent_object_type = $F('parent_object_type');
	ill_params.parent_object_id   = $F('parent_object_id');
	getAjaxData('AjaxObjectTieredCharacteristic', '', 'delete_object_tiered_characteristic', ill_params, 'Adding...')
}

function deleteTieredCharacteristicParent(id)
{
//	alert('deleteTieredCharacteristic(' + id + ')');
	if (!confirm('Are you sure you wish to remove the association of this category?'))
	{
		return
	}
	var ill_params = new Object;
	ill_params.item_id            = id;
	ill_params.tiered_characteristic_id  = id;
	ill_params.parent_object_type = $F('parent_object_type');
	ill_params.parent_object_id   = $F('parent_object_id');
	ill_params.parent_company = $F('parent_company_id');
	getAjaxData('AjaxObjectTieredCharacteristic', '', 'delete_parent_object_tiered_characteristic', ill_params, 'Adding...')
}

/* --- Ajax return data handlers --- */
// Each javascript page which calls an server side ajax command object requires a function whose name
// is the same as the server side ajax command object being used.
// This function handles all the return information from the server side ajax command object.
// The function handles this return information by using the cmd_action switch.
function AjaxObjectTieredCharacteristic(data)
{
//	alert('AjaxObjectTieredCharacteristic()');
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.value + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "add_object_tiered_characteristic":
			case "add_parent_object_tiered_characteristic":
			case "delete_object_tiered_characteristic":
			case "delete_parent_object_tiered_characteristic":
				self.location.reload(true);
				break;

			case "get_sub_characteristics":
//				alert(t.parent_id);
				$("div_tier_detail_parent_id_" + t.parent_id).style.display = "block";
				$("div_tier_loading_" + t.parent_id).style.display = "none";
				for (x = 0; x < t.sub_tier.length; x++) 
				{
//					alert(t.sub_tier[x].object_characteristic_id);
					insertRow('tbl_tier_detail_parent_id_' + t.parent_id, t.parent_id, t.sub_tier[x].id, t.sub_tier[x].value, t.sub_tier[x].object_characteristic_id, t.sub_tier[x].tier, false, true);
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
				
				insertRow('tbl_tier_detail_parent_id_' + t.parent_id, t.parent_id, t.tiered_characteristic[0].id, t.tiered_characteristic[0].value, t.sub_tier[x].object_characteristic_id, t.tiered_characteristic[0].tier, false, true);
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
				insertRow("tbl_top_level_characteristics", 0, t.tiered_characteristic[0].id, t.tiered_characteristic[0].value, t.sub_tier[x].object_characteristic_id, t.tiered_characteristic[0].tier, true, false);
				makeSubCatContainerRow("tbl_top_level_characteristics", t.tiered_characteristic[0].id);
				break;
			case "delete_sub_characteristic":
				alert("Here" + t.parent_id + " : " + t.item_id);
				deleteRow('tbl_tier_detail_parent_id_' + t.parent_id, t.item_id)
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

{/literal}
</script>

<div ng-controller="WorkspaceCategoryController">
<input type="hidden" name="parent_object_type" id="parent_object_type" value="{$parent_object_type}" />
<input type="hidden" name="parent_object_id" id="parent_object_id" value="{$parent_object_id}" />
{if $company->parent_company_id}
	<input type="hidden" name="parent_company_id" id="parent_company_id" value="{$company->parent_company_id}" />
{/if}
{*
<input type="hidden" id="parent_object_type" value="{$parent_object_type}" />
<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
<input type="hidden" id="category_id" value="{$category_id}" />
*}



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
		<select id="select_top_level_characteristic" name="select_top_level_characteristic" style="width: 75px">
			{html_options options=$unused_top_level_tiered_characteristics}
 		</select>
	</span>
 	<br />
	<span style="float: left">
		<a href="#" onclick="javascript:addTopLevelCategory('app_domain_{$parent_object_type}', {$parent_object_id});">[Save]</a>
	</span>
	<br />
	<br />
</div>

{if $company->parent_company_id}
<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
	<tr class="hdr">
		<td>
			Company Categories &nbsp;&nbsp;|&nbsp;&nbsp;
			<span style="text-align: right"><strong>{$parent_object_tiered_characteristics|@count}</strong> record{if $parent_object_tiered_characteristics|@count != 1}s{/if}</span>
			{*if $available_for_selection*}
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<input type="button" id="add_new_parent_characteristic" name="add_new_parent_characteristic" value="Add New Category" onclick="javascript:$('div_new_parent_characteristic').show();" />
			<div id="div_new_parent_characteristic" style="display: none; margin-top: 10px">
				<form id="form_new_parent_characteristic" name="form_new_parent_characteristic">
					<input type="hidden" id="parent_characteristic_type" name="parent_characteristic_type" value="{$type}" />

					<table class="ianlist">
						<tr>
					            <th style="vertical-align: top; width: 30%" {if $errors.category_id} class="key_error" title="{ $errors.category_id.0 }"{else}class="key"{/if}>Category*</th>
					            <td style="vertical-align: top; width: 100%">
					              <select style="width: 200px;" ng-model="company_category" ng-change="companyCategoryChange()" ng-options="c.value for c in company_categories"></select>
					            </td>
					          </tr>
					          <tr>
					            <th style="vertical-align: top; width: 30%">Sub Category</th>
					            <td style="vertical-align: top; width: 100%">
					              <select style="width: 200px;" ng-model="company_subcategory" ng-options="c.value for c in company_subcategories"></select>
					              <input type="hidden" id="parent_tiered_characteristic_id" name="parent_tiered_characteristic_id" value="#(company_subcategory.id)">
					            </td>
					          </tr>
					          <tr>
					            <th style="vertical-align: top; width: 30%">Tier</th>
					            <td style="vertical-align: top; width: 100%">
					              <div id="div_parent_tier">
					                <select id="parent_tier" name="parent_tier">
					                  <option value="1" {if $tier==1}selected{/if}>1</option>
					                  <option value="2" {if $tier==2}selected{/if}>2</option>
					                  <option value="3" {if $tier==3}selected{/if}>3</option>
					                </select>
					              </div>
					              <div id="div_parent_tier_none">
									<input type="hidden" id="parent_no_tier" name="parent_no_tier" value="0" />
								</div>
					            </td>
					          </tr>
						<tr>
							<th style="vertical-align: top; width: 30%">&nbsp;</th>
							<td style="vertical-align: top; width: 70%">
								<input type="button" id="add_parent_characteristic" name="add_parent_characteristic" value="Add" onclick="javascript:addTieredCharacteristicParent($F('parent_tiered_characteristic_id')); return false;" />
								<input type="button" id="cancel_parent_characteristic" name="cancel_parent_characteristic" value="Cancel" onclick="javascript:$('form_new_parent_characteristic').reset(); $('div_new_parent_characteristic').hide(); return false;" />
							</td>
						</tr>
					</table>
				</form>
			</div>
			{*/if*}
		</td>
	</tr>
	<tr valign="top">
		<td>

			{* List tiered characteristics associated with this object (company) *}
			<table id="tbl_top_level_parent_characteristics" class="adminlist">
				<thead>
					<tr>
						<th style="text-align: left">Category</th>
						<th style="text-align: left">Sub-Category</th>
						<th style="text-align: center">Tier</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				{foreach name=loop_parent_object_tiered_characteristics from=$parent_object_tiered_characteristics item=parent_object_tiered_characteristic}
					<tr>


{*						<td>{$object_tiered_characteristic.parent_value}</td>
						<td>{$object_tiered_characteristic.value}</td>
*}
								{if $parent_object_tiered_characteristic.parent_value}
									<td>{*$characteristic->getParent()*}</td>
									<td>{$parent_object_tiered_characteristic.value}</td>
								{else}
									<td colspan="2">{$parent_object_tiered_characteristic.value}</td>
								{/if}


						<td style="width: 10%; text-align: center">{if $parent_object_tiered_characteristic.tier != 0}{$parent_object_tiered_characteristic.tier}{/if}</td>
						<td  style="width: 10%; text-align: center">
							{if !$parent_object_tiered_characteristic.has_children}
{*								<a id="viewBtn_{$object_tiered_characteristic.id}" title="Edit" href="#" onclick="javascript:editCharacteristic({$object_tiered_characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_edit.png" alt="Edit" title="Edit" /></a>&nbsp;*}
								<a id="deleteBtn_{$parent_object_tiered_characteristic.id}" title="Delete" href="#" onclick="javascript:deleteTieredCharacteristicParent({$parent_object_tiered_characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_delete.png" alt="Delete" title="Delete" /></a>
							{/if}
						</td>
					</tr>
				{/foreach}
			</table>
		
		</td>
	</tr>
</table>
{/if}


<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
	<tr class="hdr">
		<td>
			Site Categories &nbsp;&nbsp;|&nbsp;&nbsp;
			<span style="text-align: right"><strong>{$object_tiered_characteristics|@count}</strong> record{if $object_tiered_characteristics|@count != 1}s{/if}</span>
			{*if $available_for_selection*}
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<input type="button" id="add_new_characteristic" name="add_new_characteristic" value="Add New Category" onclick="javascript:$('div_new_characteristic').show();" />
			<div id="div_new_characteristic" style="display: none; margin-top: 10px">
				<form id="form_new_characteristic" name="form_new_characteristic">
					<input type="hidden" id="characteristic_type" name="characteristic_type" value="{$type}" />
					<table class="ianlist">
						<tr>
					            <th style="vertical-align: top; width: 30%" {if $errors.category_id} class="key_error" title="{ $errors.category_id.0 }"{else}class="key"{/if}>Category*</th>
					            <td style="vertical-align: top; width: 100%">
					              <select style="width: 200px;" ng-model="site_category" ng-change="siteCategoryChange()" ng-options="c.value for c in site_categories"></select>
					              <input  type="hidden" name="category_id" value="#(site_category.id)">
					            </td>
					          </tr>
					          <tr>
					            <th style="vertical-align: top; width: 30%">Sub Category</th>
					            <td style="vertical-align: top; width: 100%">
					              <select style="width: 200px;" ng-model="site_subcategory" ng-options="c.value for c in site_subcategories"></select>
					              <input type="hidden" id="tiered_characteristic_id" name="tiered_characteristic_id" value="#(site_subcategory.id)">
					            </td>
					          </tr>
					          <tr>
					            <th style="vertical-align: top; width: 30%">Tier</th>
					            <td style="vertical-align: top; width: 100%">
					              <div id="div_tier">
					                <select id="tier" name="tier">
					                  <option value="1" {if $tier==1}selected{/if}>1</option>
					                  <option value="2" {if $tier==2}selected{/if}>2</option>
					                  <option value="3" {if $tier==3}selected{/if}>3</option>
					                </select>
					              </div>
					              <div id="div_tier_none">
									<input type="hidden" id="no_tier" name="no_tier" value="0" />
									n/a
								</div>
					            </td>
					          </tr>
						<tr>
							<th style="vertical-align: top; width: 30%">&nbsp;</th>
							<td style="vertical-align: top; width: 70%">
								<input type="button" id="add_characteristic" name="add_characteristic" value="Add" onclick="javascript:addTieredCharacteristic($F('tiered_characteristic_id')); return false;" />
								<input type="button" id="cancel_characteristic" name="cancel_characteristic" value="Cancel" onclick="javascript:$('form_new_characteristic').reset(); $('div_new_characteristic').hide(); return false;" />
							</td>
						</tr>
					</table>
				</form>
			</div>
			{*/if*}
		</td>
	</tr>
	</table>
	<tr valign="top">
		<td>

			{* List tiered characteristics associated with this object (company) *}
			<table id="tbl_top_level_characteristics" class="adminlist">
				<thead>
					<tr>
						<th style="text-align: left">Category</th>
						<th style="text-align: left">Sub-Category</th>
						<th style="text-align: center">Tier</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				{foreach name=loop_object_tiered_characteristics from=$object_tiered_characteristics item=object_tiered_characteristic}
					<tr>


{*						<td>{$object_tiered_characteristic.parent_value}</td>
						<td>{$object_tiered_characteristic.value}</td>
*}
								{if $object_tiered_characteristic.parent_value}
									<td>{*$characteristic->getParent()*}</td>
									<td>{$object_tiered_characteristic.value}</td>
								{else}
									<td colspan="2">{$object_tiered_characteristic.value}</td>
								{/if}


						<td style="width: 10%; text-align: center">{if $object_tiered_characteristic.tier != 0}{$object_tiered_characteristic.tier}{/if}</td>
						<td  style="width: 10%; text-align: center">
							{if !$object_tiered_characteristic.has_children}
{*								<a id="viewBtn_{$object_tiered_characteristic.id}" title="Edit" href="#" onclick="javascript:editCharacteristic({$object_tiered_characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_edit.png" alt="Edit" title="Edit" /></a>&nbsp;*}
								<a id="deleteBtn_{$object_tiered_characteristic.id}" title="Delete" href="#" onclick="javascript:deleteTieredCharacteristic({$object_tiered_characteristic.id});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_delete.png" alt="Delete" title="Delete" /></a>
							{/if}
						</td>
					</tr>
				{/foreach}
			</table>
		
		</td>
	</tr>
</table>
</div>
<script language="JavaScript" type="text/javascript">
	handleTier($F('tiered_characteristic_id'));
	handleTierParent($F('parent_tiered_characteristic_id'));
</script>

{include file="footer2.tpl"}