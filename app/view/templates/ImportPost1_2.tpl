{config_load file="example.conf"}

{include file="header2.tpl" title="ImportPort1_2"}

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

    <form action="index.php?cmd=ImportPost1_2" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        {if $post_selection|@count > 0}
		<table id="tbl_mailer_item_list" class="sortable" style="width:100%;">
			<thead>
				<tr>
					<th style="width: 5%">Import Row ID</th>
					<th>Existing Company & Address</th>
					<th>Import Post/Contact details</th>
					<th>Existing Post/Contact details</th>
				</tr>
			</thead>
			<tbody style="vertical-align:top">
				{foreach name=post_selection from=$post_selection item=line_item}
				<tr id="tr_{$line_item.import_row_id}">
					<td>{$line_item.import_row_id}</td>
					<td>
						{$line_item.alchemis_company_name} ({$line_item.alchemis_company_id})
						<br />
						{$line_item.alchemis_company_telephone}
                        <br />
                        {$line_item.alchemis_company_website}
                        <br />
						{$line_item.alchemis_address}
						
					</td>
					<td>
					   {$line_item.import_post_name}
					   <br />
					   {$line_item.import_post_job_title}
                       <br />
                       {$line_item.import_post_telephone}
                       <br />
                       {$line_item.import_post_email}
                       <br />
					</td>
					<td>
					   {$line_item.alchemis_post_name}
                       <br />
                       {$line_item.alchemis_post_job_title}
                       <br />
                       {$line_item.alchemis_post_telephone}
                       <br />
                       {$line_item.alchemis_post_email}
                       <br />
                       Use this as master: <input type="radio" id="chk_{$line_item.import_id}_{$line_item.alchemis_post_id}" value="{$line_item.alchemis_post_id}" name="chk_{$line_item.import_id}"   />
                       <br />
                       <a href="#" onclick="javascript:clearRadio('chk_{$line_item.import_id}_{$line_item.alchemis_post_id}');return false;">[clear selection]</a>
                        
                    </td>
           		</tr>
				{/foreach}
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		{else}
		No possible duplicates found. Please proceed to next stage
		{/if}
		</form>
	</div>
</div>
{include file="footer.tpl"}