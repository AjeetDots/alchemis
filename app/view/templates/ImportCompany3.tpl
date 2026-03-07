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
    <form action="index.php?cmd=ImportCompany3" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        {if $country_data|@count > 0}
		<table id="tbl_county" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th style="width: 25%">Unknown Country</th>
					<th>New Country</th>
				</tr>
			</thead>
			<tbody style="vertical-align:top">
			    {foreach name=country_data from=$country_data item=line_item}
				<tr>
					<td>
                        {$line_item.site_country}
                    </td>
					<td>
					    <select id="new_country_{$line_item.site_country}" name="new_country_{$line_item.site_country}">
					       {html_options options=$country_lkp_data}
					    </select>
					</td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		{else}
		No unknown countries found
		{/if}      
		</form>
	</div>
</div>
{include file="footer.tpl"}