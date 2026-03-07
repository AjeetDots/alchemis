{include file="header2.tpl" title="Post Disciplines"}

<script type="text/javascript">
{literal}


function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.adminForm.task.value=pressbutton;
	
	try 
	{
		document.adminForm.onsubmit();
	}
	
	catch(e)
	{}
	
	document.adminForm.submit();
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

/* --- Ajax calling functions --- */
function makeDisciplineRow(discipline_id, discipline, index)
{
	if (discipline_id == '')
	{
		alert('Please select a valid discipline');
		return false;
	}
	else
	{
		removeOption('available_disciplines', index)
	}
	
	var ill_params = new Object;
	ill_params['discipline_id'] = discipline_id;
	ill_params['discipline'] = discipline;
	
	getAjaxData("AjaxCommunication", "", "make_discipline_row", ill_params, "Saving...")
}

/* --- Ajax return data handlers --- */
function AjaxCommunication(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		//alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		switch (t.cmd_action)
		{
			case "make_discipline_row":
//				alert(t.result['template']);
				insertRow('discipline_grid', '<td colspan="5"><hr /></td>');
				insertRow('discipline_grid', t.result['template']);
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function insertRow(table_id, html)
{
	var tbl = document.getElementById(table_id);
	var lastRow = tbl.rows.length;
	
	// if there`s no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	row.innerHTML = html;	
}

function removeOption(select_id, index_to_remove)
{
	$(select_id).remove(index_to_remove);
}


{/literal}
</script>

<form action="index.php?cmd=PostDisciplines" method="post" name="adminForm" autocomplete="off">

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="post_id" value="{$post_id}" />
	<table width="100%" id="discipline_grid">
		<thead>
			<tr>
				<td colspan="5" style="width: 100%">
					<hr />
				</td>
			</tr>
			<tr>
				<th style="width: 50px">Discipline</th>
				<th style="width: 100px">Decision<br />Maker</th>
				<th style="width: 130px">Agency<br />User</th>
				<th style="width: 190px">Review<br />Date</th>
				<th>Incumbents</th>
			</tr>
		</thead>
		<tbody>
			{foreach name=discplines_grid from=$disciplines_grid item=grid_item}
			<tr>
				<td colspan="5">
					<hr />
				</td>
			</tr>
			<tr>
				<td>{$grid_item.discipline}</td>
				<td>
					<select style="width: 100px" id="decision_maker_type_id_{$grid_item.discipline_id}" name="decision_maker_type_id_{$grid_item.discipline_id}" onchange="javascript:$('decision_maker_confirmed_{$grid_item.discipline_id}').checked = true;">
						<option value="">Select...</option>
					{foreach name=dm_types from=$decison_maker_options item=dm_types_item}
						<option value="{$dm_types_item.id}" {if $dm_types_item.id == $grid_item.decison_maker_type_id}selected{/if}>{$dm_types_item.description}</option>
					{/foreach}
					</select>
					<br />
					<input type="checkbox" id="decision_maker_confirmed_{$grid_item.discipline_id}" name="decision_maker_confirmed_{$grid_item.discipline_id}" />
					&nbsp;
					<span class="label" style="font-size:7pt">({if $grid_item.dm_last_updated}{$grid_item.dm_last_updated|date_format:"%d/%m/%Y"}{else}n/a{/if})</span>
					</td>	
				<td>
					<select style="width: 125px" id="agency_user_type_id_{$grid_item.discipline_id}" name="agency_user_type_id_{$grid_item.discipline_id}" onchange="javascript:$('agency_user_confirmed_{$grid_item.discipline_id}').checked = true;">
						<option value="">Select...</option>
					{foreach name=agency_user_types from=$agency_user_options item=agency_user_types_item}
						<option value="{$agency_user_types_item.id}" {if $agency_user_types_item.id == $grid_item.agency_user_type_id}selected{/if}>{$agency_user_types_item.description}</option>
					{/foreach}
					</select>
					<br />
					<input type="checkbox" id="agency_user_confirmed_{$grid_item.discipline_id}" name="agency_user_confirmed_{$grid_item.discipline_id}" />
					&nbsp;
					<span class="label" style="font-size:7pt">({if $grid_item.agency_user_last_updated}{$grid_item.agency_user_last_updated|date_format:"%d/%m/%Y"}{else}n/a{/if})</span>
				</td>	
				<td>
					{if $grid_item.review_date}
						{html_select_date 	time=$grid_item.review_date 
										start_year='-5' 
										end_year='+5' 
										display_days=false 
										prefix=$grid_item.discipline_id 
										year_empty='Select...' 
										month_empty='Select...' 
										all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
					{else}
						{html_select_date time=0000-00-00  
										start_year='-5' 
										end_year='+5' 
										display_days=false 
										prefix=$grid_item.discipline_id 
										year_empty='Select...' 
										month_empty='Select...' 
										all_extra="onchange=\"javascript:$('review_date_confirmed_`$grid_item.discipline_id`').checked = true;\""}
					{/if}
					
					
					<br />
					<input type="checkbox" id="review_date_confirmed_{$grid_item.discipline_id}" name="review_date_confirmed_{$grid_item.discipline_id}" />
					&nbsp;
					<span class="label" style="font-size:7pt">({if $grid_item.review_date_last_updated}{$grid_item.review_date_last_updated|date_format:"%d/%m/%Y"}{else}n/a{/if})</span>
				</td>	
				<td>
					{if $grid_item.incumbent_count}
						{$grid_item.incumbent_count}
					{else}
						0
					{/if}
					<br />
					<a href="index.php?cmd=PostIncumbentAgencies&post_id={$post_id}&discipline_id={$grid_item.discipline_id}">Edit</a>
				</td>
			</tr>
			{/foreach}
			<tr>
				<td colspan="5">
					<hr />
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<select style="width: 125px" id="available_disciplines" name="available_disciplines">
						<option value="">- Select -</option>
						{html_options options=$available_disciplines}
					</select>
					<a href="#" onclick="javascript:makeDisciplineRow($F('available_disciplines'), $('available_disciplines').options[$('available_disciplines').selectedIndex].text, $('available_disciplines').selectedIndex)">Add new discipline</a>
				</td>
			</tr>
		</tbody>
	</table>
	<p></p>
	<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
</form>

{include file="footer2.tpl"}