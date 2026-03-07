<td>{$discipline}</td>
<td>
	<select style="width: 100px" id="decision_maker_type_id_{$discipline_id}" name="decision_maker_type_id_{$discipline_id}" onchange="javascript:$('decision_maker_confirmed_{$discipline_id}').checked = true;">
		<option value="">Select...</option>
	{foreach name=dm_types from=$decison_maker_options item=dm_types_item}
		<option value="{$dm_types_item.id}">{$dm_types_item.description}</option>
	{/foreach}
	</select>
	<br />
	<input type="checkbox" id="decision_maker_confirmed_{$discipline_id}" name="decision_maker_confirmed_{$discipline_id}" />
	&nbsp;&nbsp;
	<span class="label" style="font-size:7pt">(n/a)</span>
	</td>	
<td>
	<select style="width: 125px" id="agency_user_type_id_{$discipline_id}" name="agency_user_type_id_{$discipline_id}" onchange="javascript:$('agency_user_confirmed_{$discipline_id}').checked = true;">
		<option value="">Select...</option>
	{foreach name=agency_user_types from=$agency_user_options item=agency_user_types_item}
		<option value="{$agency_user_types_item.id}">{$agency_user_types_item.description}</option>
	{/foreach}
	</select>
	<br />
	<input type="checkbox" id="agency_user_confirmed_{$discipline_id}" name="agency_user_confirmed_{$discipline_id}" />
	&nbsp;&nbsp;
	<span class="label" style="font-size:7pt">(n/a)</span>
</td>	
<td>
		{html_select_date time=0000-00-00  
						start_year='-5' 
						end_year='+5' 
						display_days=false 
						prefix=$discipline_id 
						year_empty='Select...' 
						month_empty='Select...' 
						all_extra="onchange=\"javascript:$('review_date_confirmed_`$discipline_id`').checked = true;\""}
	<br />
	<input type="checkbox" id="review_date_confirmed_{$discipline_id}" name="review_date_confirmed_{$discipline_id}" />
	&nbsp;&nbsp;
	<span class="label" style="font-size:7pt">(n/a)</span>
</td>	