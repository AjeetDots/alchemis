{config_load file="example.conf"}

{include file="header2.tpl" title="Import Company 1_3"}

<script language="JavaScript" type="text/javascript">
{literal}
function submitform(pressbutton)
{
	document.adminForm.task.value = pressbutton;
	
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
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
		return;
	}
}


{/literal}
</script>
    <form action="index.php?cmd=ImportCompany1_3" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
		<table id="tbl_addresses" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th>Address 1</th>
					<th>Address 2</th>
					<th>Town</th>
					<th>City</th>
					<th>County</th>
					<th>Postcode</th>
					<th>Country</th>
					
				</tr>
			</thead>
			<tbody style="vertical-align:top">
			    {assign var='row_counter' value=0}
			    {counter start=1 assign='row_counter'}
			    {$row_counter}
			    {foreach name=address_data from=$address_data item=line_item}
				<tr>
					<td>
                        <input type="text" id="{$line_item.row_id}_site_address_1" name="{$line_item.row_id}_site_address_1" value="{$line_item.address_1}" />
                    </td>
                    <td>
                    	<input type="text" id="{$line_item.row_id}_site_address_2" name="{$line_item.row_id}_site_address_2" value="{$line_item.address_2}" />
                    </td>
                    <td>
                    	<input type="text" id="{$line_item.row_id}_site_town" name="{$line_item.row_id}_site_town" value="{$line_item.town}" />
                	</td>
                	<td>
                		<input type="text" id="{$line_item.row_id}_site_city" name="{$line_item.row_id}_site_city" value="{$line_item.city}" />
                	</td>
                	<td>
                		<input type="text" id="{$line_item.row_id}_site_county" name="{$line_item.row_id}_site_county" value="{$line_item.county}" />
            		</td>
                	<td>
                		<input type="text" id="{$line_item.row_id}_site_postcode" name="{$line_item.row_id}_site_postcode" value="{$line_item.postcode}" />
                	</td>
                	<td>
                		<input type="text" id="{$line_item.row_id}_site_country" name="{$line_item.row_id}_site_country" value="{$line_item.country}" />
            		</td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		      
		</form>
	</div>
</div>
{include file="footer.tpl"}