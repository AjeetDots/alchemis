{config_load file="example.conf"}

{include file="header2.tpl" title="Import Companies"}

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

function clearRadio(id)
{
    var chk = $(id);
    
    if (chk.checked)
    {
        chk.checked = !chk.checked;
    }
    return false;
    
}


{/literal}
</script>
    <form action="index.php?cmd=ImportCompany1_4" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        {if $company_selection|@count > 0}
        
		<table id="tbl_mailer_item_list" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th style="width: 5%">Import Row ID</th>
					<th>Import Company & Addres</th>
					<!-- <th style="width: 5%">Alchemis Company ID</th> -->
					<th>Existing Company & Address</th>
				</tr>
			</thead>
			<tbody style="vertical-align:top">
				{foreach name=company_selection from=$company_selection item=line_item}
				<tr id="tr_{$line_item.import_row_id}">
					<td>{$line_item.import_row_id}</td>
					<td>
                        {$line_item.import_company_name}
                        <br />
                        {$line_item.import_company_telehone}
                        <br />
                        {$line_item.import_company_website}
                        <br />
                        {$line_item.import_address}
                    </td>
                    
					<!-- <td>
                        {$line_item.alchemis_company_id}
                    </td> -->
					<td>
						{$line_item.alchemis_company_name} ({$line_item.alchemis_company_id})
						<br />
						{$line_item.alchemis_company_telephone}
                        <br />
                        {$line_item.alchemis_company_website}
                        <br />
						{$line_item.alchemis_address}
						<br />
						Use this as master: <input type="radio" id="chk_{$line_item.import_row_id}_{$line_item.alchemis_company_id}" value="{$line_item.alchemis_company_id}" name="chk_{$line_item.import_row_id}"   />
					    <br />
                        <a href="#" onclick="javascript:clearRadio('chk_{$line_item.import_row_id}_{$line_item.alchemis_company_id}');return false;">[clear selection]</a>
						
					</td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		{else}
			{if !$processed}	
				No duplicates found
			{/if}
		{/if}
				
		{if !$processed}	
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		{else}
		Companies processed - please proceed to the next stage
		{/if}
		</form>
	</div>
</div>
{include file="footer.tpl"}