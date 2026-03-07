{include file="header2.tpl" title="Company Create"}

{if $success}

	<p>You are being redirected to the new site.</p>
	<input type="button" id="btn_add_company" value="Add another site" onclick="javascript:document.location.href='index.php?cmd=CompanyCreate'" />
	<script language="JavaScript" type="text/javascript">
		parent.document.forms.siteSearchForm.company_equal.value = "{$name}";
		parent.doSearch(parent.document.forms.siteSearchForm.company_equal);
	</script>

	
{else}

	<script type="text/javascript">
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
	<form action="index.php?cmd=CompanyCreate" method="post" name="adminForm" autocomplete="off" ng-controller="CompanyCreateController">
	<input type="hidden" name="task" value="" />
	
		<fieldset class="adminform">
			<legend>Site</legend>
			<table>
				<tr>
					<td style="width: 80px" {if $errors.app_domain_Company_parent_company}class="key_error" title="{$errors.app_domain_Company_parent_company->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Company_parent_company">Select Parent Company *</label>
					</td>
					<td><input type="text" name="parent_company" id="app_domain_Company_parent_company" style="width: 200px" value="{$parent_company}" maxlength="255" auto-complete="ParentCompany" ac-value="id" ac-text="name" ac-hidden="app_domain_Company_parent_company" ac-init-value="{$app_domain_Company_parent_company}" ac-hint="0" ac-on-change="companyChange(model)" /></td>
				</tr>
				<tr>
					<td style="width: 80px" {if $errors.app_domain_Company_name} class="key_error" title="{$errors.app_domain_Company_name->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Company_name">Name *</label>
					</td>
					<td><input type="text" name="app_domain_Company_name" id="app_domain_Company_name" style="width: 200px" value="{$app_domain_Company_name}" maxlength="255" auto-complete="Company" ac-value="id" ac-text="name" /></td>
				</tr>
				<tr>
					<td style="width: 80px" {if $errors.app_domain_Company_telephone}class="key_error" title="{$errors.app_domain_Company_telephone->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Company_telephone">Telephone</label>
					</td>
					<td><input type="text" name="app_domain_Company_telephone" id="app_domain_Company_telephone" style="width: 200px" value="{$app_domain_Company_telephone}" maxlength="50" /></td>
				</tr>
				<tr>
					<td style="width: 80px" {if $errors.app_domain_Company_website}class="key_error" title="{$errors.app_domain_Company_website->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Company_website">Website</label>
					</td>
					<td><input type="text" name="app_domain_Company_website" id="app_domain_Company_website" style="width: 200px" value="{$app_domain_Company_website}" maxlength="255" /></td>
				</tr>
			</table>
		</fieldset>


		<fieldset class="adminform">
				<legend>Sub Category</legend>
				<table>
					<tr>
						<td style="vertical-align: top; width: 30%"{if $errors.app_domain_Company_category_id} class="key_error" title="{ $errors.category_id.0 }"{else}class="key"{/if}>Category </td>
						<td style="vertical-align: top; width: 70%">
							<select style="width: 200px;" ng-model="category" ng-change="categoryChange()" ng-options="c.value for c in categories track by c.id"></select>
						</td>
					</tr>
					<tr>
						 <td style="vertical-align: top; width: 30%" {if $errors.app_domain_Company_subcategory_id} class="key_error" title="{ $errors.sub_category_id.0 }"{else}class="key"{/if}>Sub Category *</td>
						<td style="vertical-align: top; width: 70%">
							<select style="width: 200px;" ng-model="subcategory" ng-options="c.value for c in subcategories"></select>
							<input type="hidden" name="tiered_characteristic_id" value="#(subcategory.id)">
						</td>
					</tr>
					<tr>
						<th style="vertical-align: top; width: 30%">Tier</th>
						<td style="vertical-align: top; width: 70%">
							<div id="div_tier">
								<select id="tier" name="tier">
									<option value="1" {if $tier==1}selected{/if}>1</option>
									<option value="2" {if $tier==2}selected{/if}>2</option>
									<option value="3" {if $tier==3}selected{/if}>3</option>
								</select>
							</div>
						</td>
					</tr>
				</table>
			</fieldset>
		
		<label for="chk_display_site">Add address?</label> <input type="checkbox" id="chk_display_site" name="chk_display_site"{if $chk_display_site} checked="checked"{/if} onchange="javascript: new Effect.toggle($('div_display_site'), 'blind', {literal}{duration: 0.3}{/literal});return false;" />
		
		<div id="div_display_site" style="display: {if $chk_display_site}block{else}none{/if}">
			<br />
			<fieldset class="adminform">
				<legend>Address</legend>
				<table>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Site_address_1} class="key_error" title="{$errors.app_domain_Site_address_1->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_address_1">Address 1</label>
						</td>
						<td><input type="text" name="app_domain_Site_address_1" id="app_domain_Site_address_1" style="width: 200px" value="{$app_domain_Site_address_1}" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_address_2} class="key_error" title="{$errors.app_domain_Site_address_2->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_address_2">Address 2</label>
						</td>
						<td><input type="text" name="app_domain_Site_address_2" id="app_domain_Site_address_2" style="width: 200px" value="{$app_domain_Site_address_2}" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_city} class="key_error" title="{$errors.app_domain_Site_city->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_town">Address 3</label>
						</td>
						<td><input type="text" name="app_domain_Site_town" id="app_domain_Site_town" style="width: 200px" value="{$app_domain_Site_town}" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_city} class="key_error" title="{$errors.app_domain_Site_city->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_city">City</label>
						</td>
						<td><input type="text" name="app_domain_Site_city" id="app_domain_Site_city" style="width: 200px" value="{$app_domain_Site_city}" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_county_id} class="key_error" title="{$errors.app_domain_Site_county_id->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_county_id">County</label>
						</td>
						<td>
							<select name="app_domain_Site_county_id" id="app_domain_Site_county_id" style="width: 100%">
								<option value="0">-- select if required --</option>
								{html_options options=$site_counties_options selected=$app_domain_Site_county_id}
							</select>
						</td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_postcode} class="key_error" title="{$errors.app_domain_Site_postcode->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_postcode">Postcode</label>
						</td>
						<td><input type="text" name="app_domain_Site_postcode" id="app_domain_Site_postcode" value="{$app_domain_Site_postcode}" style="width:200px" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px"{if $errors.app_domain_Site_country_id} class="key_error" title="{$errors.app_domain_Site_country_id->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Site_country_id">Country</label>
						</td>
						<td>
							<select name="app_domain_Site_country_id" id="app_domain_Site_country_id" style="width: 100%">
								{html_options options=$site_countries_options selected=$app_domain_Site_country_id}
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	
		<br />
		
		<label for="chk_display_post">Add first post?</label> <input type="checkbox" id="chk_display_post" name="chk_display_post"{if $chk_display_post} checked="checked"{/if} onchange="javascript: new Effect.toggle($('div_display_post'), 'blind', {literal}{duration: 0.3}{/literal});return false;" />
		
		<div id="div_display_post" style="display: {if $chk_display_post}block{else}none{/if}">
			<br />
			<fieldset class="adminform">
				<legend>First post</legend>
				<table>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Post_job_title}class="key_error" title="{$errors.app_domain_Post_job_title->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Post_job_title">Job Title *</label>
						</td>
						<td><input type="text" name="app_domain_Post_job_title" id="app_domain_Post_job_title" style="width: 200px" value="{$app_domain_Post_job_title}" maxlength="255" /></td>
					</tr>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Post_telephone_1}class="key_error" title="{$errors.app_domain_Post_telephone_1->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Post_telephone_1">Telephone 1</label>
						</td>
						<td><input type="text" name="app_domain_Post_telephone_1" id="app_domain_Post_telephone_1" style="width: 200px" value="{$app_domain_Post_telephone_1}" maxlength="50" /></td>
					</tr>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Post_telephone_2}class="key_error" title="{$errors.app_domain_Post_telephone_2->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Post_telephone_2">Telephone 2</label>
						</td>
						<td><input type="text" name="app_domain_Post_telephone_2" id="app_domain_Post_telephone_2" style="width: 200px" value="{$app_domain_Post_telephone_2}" maxlength="50" /></td>
					</tr>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Post_switchboard}class="key_error" title="{$errors.app_domain_Post_switchboard->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Post_switchboard">Switchboard</label>
						</td>
						<td><input type="text" name="app_domain_Post_switchboard" id="app_domain_Post_switchboard" style="width: 200px" value="{$app_domain_Post_switchboard}" maxlength="50" /></td>
					</tr>
					<tr>
						<td style="width: 80px" {if $errors.app_domain_Post_fax}class="key_error" title="{$errors.app_domain_Post_fax->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Post_fax">Fax</label>
						</td>
						<td><input type="text" name="app_domain_Post_fax" id="app_domain_Post_fax" style="width: 200px" value="{$app_domain_Post_fax}" maxlength="50" /></td>
					</tr>
          <tr>
						<td style="width: 80px">
							<label for="app_domain_Post_data_source_id">Data Source</label>
						</td>
						<td>
              <select type="text" name="app_domain_Post_data_source_id" id="app_domain_Post_data_source_id" style="width:200px" />
                {html_options options=$post_data_source_options selected=$app_domain_Post_data_source_id}
              </select
					</tr>
					<tr>
						<td colspan="2">
							<label for="chk_display_contact">Add a post holder?</label>
							<input type="checkbox" id="chk_display_contact" name="chk_display_contact"{if $chk_display_contact} checked="checked"{/if} onchange="javascript: new Effect.toggle($('div_display_contact'), 'blind', {literal}{duration: 0.3}{/literal});return false;" />
						</td>
					</tr>
				</table>
				<div id="div_display_contact" style="display: {if $chk_display_contact}block{else}none{/if}">
					<table>	
						<tr>
							<td style="width: 80px" {if $errors.app_domain_Contact_title}class="key_error" title="{$errors.app_domain_Contact_title->getTip()}"{else}class="key"{/if}>
								<label for="app_domain_Contact_title">Title</label>
							</td>
							<td><input type="text" name="app_domain_Contact_title" id="app_domain_Contact_title" style="width: 200px" value="{$app_domain_Contact_title}" maxlength="25" /></td>
						</tr>
						<tr>
							<td style="width: 80px" {if $errors.app_domain_Contact_first_name}class="key_error" title="{$errors.app_domain_Contact_first_name->getTip()}"{else}class="key"{/if}>
								<label for="app_domain_Contact_first_name">First Name</label>
							</td>
							<td><input type="text" name="app_domain_Contact_first_name" id="app_domain_Contact_first_name" style="width: 200px" value="{$app_domain_Contact_first_name}" maxlength="50" /></td>
						</tr>
						<tr>
							<td style="width: 80px" {if $errors.app_domain_Contact_surname}class="key_error" title="{$errors.app_domain_Contact_surname->getTip()}"{else}class="key"{/if}>
								<label for="app_domain_Contact_surname">Surname</label>
							</td>
							<td><input type="text" name="app_domain_Contact_surname" id="app_domain_Contact_surname" style="width: 200px" value="{$app_domain_Contact_surname}" maxlength="50" /></td>
						</tr>
						<tr>
							<td style="width: 80px" {if $errors.app_domain_Contact_telephone_mobile}class="key_error" title="{$errors.app_domain_Contact_telephone_mobile->getTip()}"{else}class="key"{/if}>
								<label for="app_domain_Contact_telephone_mobile">Mobile</label>
							</td>
							<td><input type="text" name="app_domain_Contact_telephone_mobile" id="app_domain_Contact_telephone_mobile" style="width: 200px" value="{$app_domain_Contact_telephone_mobile}" maxlength="100" /></td>
						</tr>
						<tr>
							<td style="width: 80px" {if $errors.app_domain_Contact_email}class="key_error" title="{$errors.app_domain_Contact_email->getTip()}"{else}class="key"{/if}>
								<label for="app_domain_Contact_email">Email</label>
							</td>
							<td><input type="text" name="app_domain_Contact_email" id="app_domain_Contact_email" style="width: 200px" value="{$app_domain_Contact_email}" maxlength="100" /></td>
						</tr>
					</table>
				</div>
			</fieldset>
		</div>
		<br />
		
	
	
		<p></p>
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
	
	</form>

{/if}
{include file="footer2.tpl"}