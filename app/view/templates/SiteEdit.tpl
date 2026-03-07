{include file="header2.tpl" title="Edit Site"}

{if $success}

	<p>Company details are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
		{literal}
		if (parent.$('post_id'))
		{
			var post_id = parent.$F('post_id');
		}
		else
		{
			var post_id = null;
		}
		
		if (parent.$('post_initiative_id'))
		{
			var post_initiative_id = parent.$F('post_initiative_id');
		}
		else
		{
			var post_initiative_id = null;
		}
		
		{/literal}
		parent.getCompanyDetail({$company_id}, post_id, post_initiative_id);
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
	//	var form = document.adminForm;
	//	var type = form.type.value;
		
		if (pressbutton == 'save') 
		{
			submitform( pressbutton );
			return;
		}
	}
	
	{/literal}
	</script>
	<fieldset class="adminform">
		<legend>Company</legend>
		{$parent_company.name}
		<a href="" ng-click="showform = true" ng-hide="showform">edit</a>
      <a href="" ng-click="showform = false" ng-show="showform">cancel</a>
      <form ng-show="showform" action="index.php?cmd=Company&action=addParentCompany&id={$company.id}" method="POST">
        <label for="">Add Parent Company</label>
        <br>
        <input type="text" auto-complete="ParentCompany" ac-value="id" ac-text="name" ac-hidden="parent_company_id" ac-hint="0">
        <input class="btn" type="submit" value="Submit">
      </form>
	</fieldset>

	<form action="index.php?cmd=SiteEdit" method="post" name="adminForm" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="{$site->getId()}" />
	<input type="hidden" name="company_id" value="{$company_id}" />	

		<fieldset class="adminform">
			<legend>Site</legend>
				{$company_name}
		</fieldset>
		<fieldset class="adminform">
			<legend>Edit Address</legend>
			<table>
				<tr>
					<td>Address 1</td>
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
							{html_options options=$site_counties_options selected=$app_domain_Site_county_id}
						</select>
					</td>
				</tr>
				<tr>
					<td>Postcode</td>
					<td><input type="text" name="app_domain_Site_postcode" id="app_domain_Site_postcode" style="width:200px" value="{$app_domain_Site_postcode}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>Country</td>
					<td>
						<select name="app_domain_Site_country_id" id="app_domain_Site_country_id" style="width: 100%">
							{html_options options=$site_countries_options selected=$app_domain_Site_country_id}
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
	
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
		
	</form>
{/if}

{include file="footer2.tpl"}