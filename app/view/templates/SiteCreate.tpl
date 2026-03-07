{include file="header2.tpl" title="Create Site"}

{if $success}

	<p>Company details are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
		parent.getCompanyDetail({$company_id}, null, null);
	</script>

{else}

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
	
	{/literal}
	</script>
	<form action="index.php?cmd=SiteCreate" method="post" name="adminForm" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="company_id" value="{$company_id}" />
	
		<fieldset class="adminform">
			<legend>Company</legend>
				{$company_name}
		</fieldset>
		<p></p>
		<fieldset class="adminform">
			<legend>Add Address</legend>
			<table>
				<tr>
					<td style="width:80px">Address 1</td>
					<td><input type="text" name="app_domain_Site_address_1" id="app_domain_Site_address_1" style="width:200px" value="{$app_domain_Site_address_1}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>Address 2</td>
					<td><input type="text" name="app_domain_Site_address_2" id="app_domain_Site_address_2" style="width:200px" value="{$app_domain_Site_address_2}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>Address 3</td>
					<td><input type="text" name="app_domain_Site_town" id="app_domain_Site_town" style="width:200px" value="{$app_domain_Site_town}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>City</td>
					<td><input type="text" name="app_domain_Site_city" id="app_domain_Site_city" style="width:200px" value="{$app_domain_Site_city}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>County</td>
					<td>
						<select name="app_domain_Site_county_id" id="app_domain_Site_county_id" style="width: 100%">
							{html_options options=$site_counties_options selected=$site_counties_selected}
						</select>
					</td>
				</tr>
				<tr>
					<td>Postcode</td>
					<td><input type="text" name="app_domain_Site_postcode" id="app_domain_Site_postcode" style="width:200px" value="{$app_domain_Site_city}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>Country</td>
					<td>
						<select name="app_domain_Site_country_id" id="app_domain_Site_country_id" style="width: 100%">
							{html_options options=$site_countries_options selected=$site_countries_selected}
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
			
		<p></p>
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
	
	</form>
{/if}
{include file="footer2.tpl"}