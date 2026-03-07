{include file="header2.tpl" title="Edit Characteristic"}

<script language="JavaScript" type="text/javascript">
{literal}

// Switch the type icon dependant upon the user's selection
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


function loadElement(id, name, data_type)
{
	if (id == -1)
	{
		addElement('element');
	}
	else
	{
		addElement('element', id);
	}
	
	var count = $F('element_count');
	$('element_name_' + count).value = name;

	for (var i = 0; i < $('element_data_type_' + String(count)).options.length; i++)
	{
		if ($('element_data_type_' + String(count)).options[i].value == data_type)
		{
			$('element_data_type_' + String(count)).selectedIndex = i;
			break;
		}
	}
}


function addElement(type, dbId)
{
	// Get the next div iteration for the new element
	var count = Number($F(type + '_count')) + 1;
	
	// Create containing div and set its id
	var c = document.createElement('div');
	c.id = type + '_' + count;

	// Get the appropriate HTML for the type concerned
	switch(type)
	{
		case 'element':
			c.innerHTML = getElementHtml(count, dbId);
			break;
		default:
			return;
	}		

	// Add to container div
	$(type + '_container').appendChild(c);

	// Increment counter
	$(type + '_count').value = count;
}


function removeElement(div)
{
	var parent = $(div).up();
	parent.removeChild($(div));
}

function getElementHtml(id, dbId)
{
	var disabled_string = '';
	if (!$F('attributes'))
	{
		disabled_string = ' disabled="disabled"';
	}
	
	{/literal}
	if (!dbId) dbId = '';
	var html = 	'<div>' +
				'Name <input type="hidden" id="element_dbId_' + id + '" name="element_dbId_' + id + '" value="' + dbId + '" />' +
				'<input type="text" id="element_name_' + id + '" name="element_name_' + id + '"value="" size="30" />&nbsp;&nbsp;&nbsp;&nbsp;' +
				'Data Type <select class="element_datatype" id="element_data_type_' + id + '" name="element_data_type_' + id + '"' + disabled_string + '>' +
				{foreach name=data_type_loop from=$data_types item=data_type}
					'<option value="{$data_type|escape:'javascript'}">{$data_type|escape:'javascript'}</option>' +
				{/foreach}
				'</select>&nbsp;&nbsp;&nbsp;&nbsp;' +
				'<a id="deleteBtn_{$characteristic->getId()}" title="Delete" href="#" onclick="removeElement(\'element_' + id + '\'); return false;"><img src="{$APP_URL}app/view/images/icons/cog_delete.png" alt="Delete" title="Delete" /></a>' +
				'</div>';
	return html;
	{literal}
}


function removeElementContainer()
{
	if (!$F('attributes') && !$F('options'))
	{
		removeElement('element_container');
	}
	
	if (!$F('options'))
	{
		$('multiple_select').checked = false;
	}

	$('characteristic_form').submit();
}


function toggleCharacteristicDataType()
{
	if ($F('attributes') || $F('options'))
	{
		if ($('div_datatype').style.display == 'block' || $('div_datatype').style.display == '') 
		{
			new Effect.BlindUp($('div_datatype'), {duration: 0.3});
		}
		
		if ($('div_elements').style.display == 'none') 
		{
			new Effect.BlindDown($('div_elements'), {duration: 0.3});
		} 
	}
	else
	{
		if ($('div_datatype').style.display == 'none') 
		{
			new Effect.BlindDown($('div_datatype'), {duration: 0.3});
		}
		
		if ($('div_elements').style.display == 'block' || $('div_elements').style.display == '') 
		{
			new Effect.BlindUp($('div_elements'), {duration: 0.3});
		}
	}
	return false;
}


function toggleElementAttributes()
{
	if ( $F('attributes') || $F('options') )
	{
		var disabled = !Boolean($F('attributes'));
		var elements = $('div_elements').getElementsByClassName('element_datatype');
		for (var i = 0; i < elements.length; i++)
		{
			elements[i].disabled = disabled;
			if (elements[i].disabled)
			{
				elements[i].selectedIndex = 0;
			}
		} 
	}
}

function addInitiative() {
	var $selectedInitiatives = $('selected_initiatives');
	var init_id = $F('client_initiatives_list');
	var $clientInitiativesList = $('client_initiatives_list');
    if ($selectedInitiatives.value.indexOf(init_id) === -1) {
		var displayText = $clientInitiativesList.options[$clientInitiativesList.selectedIndex].text;

		$selectedInitiatives.value += init_id + ',';

		var p = document.createElement('p');
		p.innerHTML = displayText + ' <a href="javascript:;" onclick="removeInitiative(' + init_id + ', this)">X</a>';

		$('campaignList').appendChild(p);
	}
	$clientInitiativesList.value = '';

}

function removeInitiative(id, element) {
	$(element).up().remove();
	var $selectedInitiatives = $('selected_initiatives');
	$selectedInitiatives.value = $selectedInitiatives.value.replace(id + ',', '');
}

{/literal}
</script>


<form action="index.php?cmd=CharacteristicEdit" method="post" id="characteristic_form">

	<input type="hidden" id="parent_object_type" value="{$parent_object_type}" />
	<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
	<input type="hidden" id="category_id" value="{$category_id}" />

	<h2>Characteristic: {$characteristic->getId()} {$characteristic->getName()}</h2>
	
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Name</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="name" name="name" value="{$characteristic->getName()}" style="width: 250px" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Description</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="description" name="description" value="{$characteristic->getDescription()}" style="width: 250px" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Type</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<select id="type" name="type" onchange="javascript:switchTypeIcon();">
					{html_options values=$types output=$types selected=$type}
				</select>
				&nbsp;&nbsp;&nbsp;<img id="img_type" src="" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Attributes</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" id="attributes" name="attributes"{if $characteristic->hasAttributes()} checked="checked"{/if} 
					onchange="toggleCharacteristicDataType(); toggleElementAttributes(); return false;" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Options</th>
			<td style="vertical-align: top; width: 40%">
				<input type="checkbox" id="options" name="options"{if $characteristic->hasOptions()} checked="checked"{/if} 
					onchange="new Effect.toggle($('div_multiple_select'), 'blind', {literal}{duration: 0.3}{/literal}); toggleCharacteristicDataType(); toggleElementAttributes(); return false;" />
			</td>
			<td style="vertical-align: top; width: 40%; padding: 0px">
				<div id="div_multiple_select" style="display: {if $characteristic->hasOptions()}block{else}none{/if}; padding: 0px">
					<table style="border-collapse: collapse; padding: 0px">
						<tr>
							<th>Multiple Select</th>
							<td><input type="checkbox" id="multiple_select" name="multiple_select"{if $characteristic->hasMultipleSelect()} checked="checked"{/if} /></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>

	<div id="div_datatype" style="display: {if $characteristic->hasAttributes() || $characteristic->hasOptions()}none{else}block{/if}">
		<table class="ianlist">
			<tr>
				<th style="vertical-align: top; width: 20%">Data Type</th>
				<td style="vertical-align: top; width: 80%">
					<select id="data_type" name="data_type">
						{html_options values=$data_types output=$data_types selected=$characteristic->getDataType()}
					</select>
				</td>
			</tr>
		</table>
	</div>

	<div>
		<h2>Default for Campaign</h2>

		<select name="client_initiatives_list" id="client_initiatives_list">
			<option value="">&mdash; Select Initiative &mdash;</option>
			{foreach name="result_loop" from=$client_initiatives item=result}
				<option value="{$result.campaign_id}">{$result.client_initiative_display}</option>
			{/foreach}
		</select>
		<input type="text" name="selected_initiatives" id="selected_initiatives" value="{$campaignList}" style="display: none;">

		<p><a href="javascript:;" onclick="addInitiative()"><img src="{$APP_URL}app/view/images/icons/cog_add.png"
																 alt="Add" title="Add"/> Add campaign</a></p>

		<div id="campaignList">
			{foreach name="result_loop" from=$campaignCharacteristics item=char}
				<p>{$char->displayText()} <a href="javascript:;"
										   onclick="removeInitiative({$char->campaign_id}, this)">X</a>
				</p>
			{/foreach}
		</div>
	</div>

	<div id="div_elements" style="display: {if $characteristic->hasAttributes() || $characteristic->hasOptions()}block{else}none{/if}">
		<h2>Characteristic Elements</h2>
		<input type="hidden" id="element_count" value="0" />
		<div id="element_container"></div>
		<p><a href="javascript:;" onclick="addElement('element'); return false;"><img src="{$APP_URL}app/view/images/icons/cog_add.png" alt="Add" title="Add" /> Add Element</a></p>
	</div>

	<input type="hidden" id="task" name="task" value="save" />
	<input type="hidden" id="id" name="id" value="{$characteristic->getId()}" />
	<input type="submit" value="Save" onclick="removeElementContainer();" />
	
</form>

<script language="JavaScript" type="text/javascript">

	switchTypeIcon();

{if $elements}
	{foreach name=element_loop from=$elements item=element}
	loadElement('{$element.id|escape:'javascript'}', '{$element.name|escape:'javascript'}', '{$element.data_type|escape:'javascript'}');
	{/foreach}
	toggleElementAttributes();
{/if}

</script>

{include file="footer2.tpl"}