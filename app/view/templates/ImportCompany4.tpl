{config_load file="example.conf"}

{include file="header2.tpl" title="Import Company 3 "}

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
    <form action="index.php?cmd=ImportCompany4" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        {if $tiered_characteristic_data|@count > 0}
		<table id="tbl_county" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th style="width: 25%">Unknown Sub-category</th>
					<th>New Sub-category</th>
				</tr>
			</thead>
			<tbody style="vertical-align:top">
			    {foreach name=tiered_characteristic_data from=$tiered_characteristic_data item=line_item}
				<tr>
					<td>
                        {$line_item.sub_category}
                    </td>
					<td>
					    <select id="new_sub_category_{$line_item.sub_category}" name="new_sub_category_{$line_item.sub_category}">
					       {html_options options=$tiered_characteristic_lkp_data}
					    </select>
					</td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		{else}
		No unknown entries found for field sub_category
		{/if}      
		</form>
	</div>
</div>
{include file="footer.tpl"}