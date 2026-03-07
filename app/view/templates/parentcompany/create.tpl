{include file="header2.tpl" title="Company Create"}

{if $success}

  <p>You are being redirected to the new company.</p>
  <input type="button" id="btn_add_parent_company" value="Add another company" onclick="javascript:document.location.href='index.php?cmd=ParentCompany'" />
  <script language="JavaScript" type="text/javascript">
    parent.document.forms.companySearchForm.ByName.value = "{$company.name}";
    parent.doCompanySearch(parent.document.forms.companySearchForm.ByName);
  </script>

  
{else}

  <form action="index.php?cmd=ParentCompany" method="post" name="adminForm" autocomplete="off" ng-controller="ParentCompanyCreateController">
  
    <fieldset class="adminform">
      <legend>Company</legend>
      <table>
        <tr>
          <td style="width: 80px" {if $errors.name} class="key_error" title="{ $errors.name.0 }"{else}class="key"{/if}>
            <label for="name">Name *</label>
          </td>
          <td><input type="text" name="name" id="name" style="width: 200px" value="{$input.name}" maxlength="255" auto-complete="ParentCompany" ac-value="id" ac-text="name" /></td>
        </tr>
        <tr>
          <td style="width: 80px" {if $errors.parent_company} class="key_error" title="{ $errors.parent_company.0 }"{else}class="key"{/if}>
            <label for="parent_company">Select Parent Company (optional)</label>
          </td>
          <td><input type="text" name="parent_company" id="parent_company" style="width: 200px" value="{$input.parent_company}" auto-complete="ParentCompany" ac-value="id" ac-text="name" ac-hidden="parent_company_id" ac-init-value="{$input.parent_company_id}" /></td>
        </tr>
      </table>
    </fieldset>
    
    
    <br>
    <fieldset class="adminform">
        <legend>Category</legend>
        <table>
          <tr>
            <td style="vertical-align: top; width: 30%" {if $errors.category_id} class="key_error" title="{ $errors.category_id.0 }"{else}class="key"{/if}>Category *</td>
            <td style="vertical-align: top; width: 100%">
              <select style="width: 200px;" ng-model="category" ng-change="categoryChange()" ng-options="c.value for c in categories"></select>
              <input  type="hidden" name="category_id" value="#(category.id)">
            </td>
          </tr>
          <tr>
            <td style="vertical-align: top; width: 30%" {if $errors.sub_category_id} class="key_error" title="{ $errors.sub_category_id.0 }"{else}class="key"{/if}>Sub Category</td>
            <td style="vertical-align: top; width: 100%">
              <select style="width: 200px;" ng-model="subcategory" ng-options="c.value for c in subcategories"></select>
              <input type="hidden" name="subcategory_id" value="#(subcategory.id)">
            </td>
          </tr>
          <tr>
            <td style="vertical-align: top; width: 30%" class="key">Tier</td>
            <td style="vertical-align: top; width: 100%">
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
    <br>
    <label for="add_site">Add site?</label> <input type="checkbox" id="add_site" name="add_site"{if $input.add_site} ng-init="addsite = true"{/if} ng-model="addsite" value="1" />

    <fieldset class="adminform" ng-show="addsite">
      <legend>Site</legend>
      <table>
        <tr>
          <td style="width: 80px" {if $errors.site_name} class="key_error" title="{$errors.site_name.0}"{else}class="key"{/if}>
            <label for="site_name">Name *</label>
          </td>
          <td><input type="text" name="site_name" id="site_name" style="width: 200px" value="{$input.site_name}" maxlength="255" ng-model="site_name" auto-complete="Company" ac-value="id" ac-text="name" /></td>
        </tr>
        <tr>
          <td style="width: 80px" {if $errors.site_telephone} class="key_error" title="{$errors.site_telephone.0}"{else}class="key"{/if}>
            <label for="site_telephone">Telephone</label>
          </td>
          <td><input type="text" name="site_telephone" id="site_telephone" style="width: 200px" value="{$input.site_telephone}" maxlength="50" /></td>
        </tr>
        <tr>
          <td style="width: 80px" {if $errors.site_website} class="key_error" title="{$errors.site_website.0}"{else}class="key"{/if}>
            <label for="site_website">Website</label>
          </td>
          <td><input type="text" name="site_website" id="site_website" style="width: 200px" value="{$input.site_website}" maxlength="255" /></td>
        </tr>
        <tr>
          <td style="width: 80px" {if $errors.sub_category_id} class="key_error" title="{ $errors.sub_category_id.0 }"{else}class="key"{/if}>
            <label for="site_sub_category">SubCategory*</label>
          </td>
          <td>
            <select style="width: 200px;" ng-model="subcategory" ng-options="c.value for c in subcategories"></select>
          </td>
        </tr>
      </table>
      <label for="add_address">Add address?</label> <input type="checkbox" id="add_address" name="add_address"{if $input.add_address} checked="checked"{/if} onchange="javascript: new Effect.toggle($('div_display_site'), 'blind', {literal}{duration: 0.3}{/literal});return false;" value="1" />
          
          <div id="div_display_site" style="display: {if $input.add_address}block{else}none{/if}">
            <br />
            <fieldset class="adminform">
              <legend>Address</legend>
              <table>
                <tr>
                  <td style="width: 80px" {if $errors.address_1} class="key_error" title="{$errors.address_1.0}"{else}class="key"{/if}>
                    <label for="address_1">Address 1</label>
                  </td>
                  <td><input type="text" name="address_1" id="address_1" style="width: 200px" value="{$input.address_1}" maxlength="255" /></td>
                </tr>
                <tr>
                  <td style="width: 80px"{if $errors.address_2} class="key_error" title="{$errors.address_2.0}"{else}class="key"{/if}>
                    <label for="address_2">Address 2</label>
                  </td>
                  <td><input type="text" name="address_2" id="address_2" style="width: 200px" value="{$input.address_2}" maxlength="255" /></td>
                </tr>
                <tr>
                  <td style="width: 80px"{if $errors.town} class="key_error" title="{$errors.town.0}"{else}class="key"{/if}>
                    <label for="town">Town</label>
                  </td>
                  <td><input type="text" name="town" id="town" style="width: 200px" value="{$input.town}" maxlength="255" /></td>
                </tr>
                <tr>
                  <td style="width: 80px"{if $errors.county_id} class="key_error" title="{$errors.county_id.0}"{else}class="key"{/if}>
                    <label for="county_id">County</label>
                  </td>
                  <td>
                    <select name="county_id" id="county_id" style="width: 100%">
                      {html_options options=$counties selected=$input.county_id}
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="width: 80px"{if $errors.postcode} class="key_error" title="{$errors.postcode.0}"{else}class="key"{/if}>
                    <label for="postcode">Postcode</label>
                  </td>
                  <td><input type="text" name="postcode" id="postcode" value="{$input.postcode}" style="width:200px" maxlength="255" /></td>
                </tr>
                <tr>
                  <td style="width: 80px"{if $errors.country_id} class="key_error" title="{$errors.country_id.0}"{else}class="key"{/if}>
                    <label for="country_id">Country</label>
                  </td>
                  <td>
                    <select name="country_id" id="country_id" style="width: 100%">
                      {html_options options=$countries selected=$input.country_id}
                    </select>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
    </fieldset>

    <br>
    
    
    <p></p>

    <input type="submit" value="Submit" />&nbsp;|&nbsp;
    <input type="reset" value="Reset" />
  
  </form>

  <script>
  {literal}
  jQuery('#add_site').change(function () {
    jQuery('#site_name').val(jQuery('#name').val());
  });
  {/literal}
  </script>

{/if}
{include file="footer2.tpl"}