{config_load file="example.conf"}

{include file="header2.tpl" title="Import Company 2 "}

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
    <form action="index.php?cmd=ImportCompany2" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        {if $county_data|@count > 0}
		<table id="tbl_county" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th style="width: 25%">Unknown County</th>
					<th>New County</th>
				</tr>
			</thead>
			<tbody style="vertical-align:top">
			    {foreach name=county_data from=$county_data item=line_item}
				<tr>
					<td>
                        {$line_item.site_county}
                    </td>
					<td>
					    <select id="new_county_{$line_item.site_county}" name="new_county_{$line_item.site_county}">
					       {html_options options=$county_lkp_data}
					    </select>
					</td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		{else}
		No unknown counties found
		{/if}
		</form>
	</div>
</div>
{include file="footer.tpl"}