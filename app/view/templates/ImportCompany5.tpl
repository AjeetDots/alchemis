{config_load file="example.conf"}

{include file="header2.tpl" title="Import Company 5 "}

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
    <form action="index.php?cmd=ImportCompany5" method="post" name="adminForm" autocomplete="off">
        <input type="hidden" name="task" value="" />
        <table id="tbl_county" style="width:100%;">
			<tbody style="vertical-align:top">
				<tr>
					<td>
                        Client:
                    </td>
					<td>
					    <select id="client_id" name="client_id">
					       {html_options options=$client_initiative_lkp_data}
					    </select>
					</td>
           		</tr>
			</tbody>
		</table>
		
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		      
		</form>
	</div>
</div>
{include file="footer.tpl"}