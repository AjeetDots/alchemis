{strip}
{include file="header2.tpl" title="Search Results"}

<script language="JavaScript" type="text/javascript">
{literal}
  function showCompany(company_id, parent_id)
  {
    page_isloaded = true;
    var href = "index.php?cmd=WorkspaceSearch&id=" + company_id + "&initiative_id=" + top.$F("initiative_list") + "&parent_id=" + parent_id;
    iframeLocation(top.frames["iframe_5"], href);
    top.loadTab(5,"");
  }

  page_isloaded = false;

  collns = {};
  companies = [];
  
{/literal}
</script>

<p><strong>{$parent_companies|@count}</strong> result{if $parent_companies|@count != 1}s{/if}</p>

{if $parent_companies}
  <div id="div_results" class="cfg" style="border: solid 0px #ccc; padding: 2px; width: 100%; height: 715px; overflow: auto">
    <table id="table1" class="adminlist" style"border-collapse: collapse; border-spacing: 0px; empty-cells: none">
      <thead>
        <tr>
          <th style="width: 1%; text-align: center">#</th>
          <th style="width: 1%; text-align: center">&nbsp;</th>
          <th style="width: 1%; text-align: center">ID</th>
          <th style="width: 28%; text-align: left">Company</th>
          <th style="width: 50%; text-align: left">Address</th>
          <th style="width: 15%; text-align: left">Parent Company</th>
        </tr>
      </thead>
      <tbody>
      {foreach name="result_loop" from=$parent_companies item=parent_company}
        {assign var='i' value=$smarty.foreach.result_loop.iteration}
        <tr id="tr_{$parent_company.id}" style="vertical-align: top">
          <td>{$i}</td>
          <td style="text-align: center"><a href="#" onclick="javascript:new Effect.toggle($('site_list_{$parent_company.id}'), 'blind', {literal}{duration: 0.3}{/literal});return false; "><img style="vertical-align:middle" src="{$APP_URL}app/view/images/icons/building.png" alt="sites" title="Number of sites at this company" />({$parent_company.sites|@count})</a></td>
          <td>{$parent_company.id}</td>
          <td>
            <span id="client_{$parent_company.id}" style="font-weight:bold">{$parent_company.name}</span>
            {if $parent_company.address.telephone}
            <br />
              <span>{$parent_company.address.telephone}</span>
            {/if}
          </td>
          <td>
            {$parent_company.address.address_1}<br>
            {$parent_company.address.address_2}<br>
            {$parent_company.address.town}<br>
            {$parent_company.address.county.name}<br>
            {$parent_company.address.postcode}
          </td>
          <td ng-init="item_{$i}.showform = false">
            {if $parent_company.parent}
              {$parent_company.parent.name}<br>
            {/if}
            <a href="" ng-click="item_{$i}.showform = true" ng-hide="item_{$i}.showform">edit</a> 
            {if $parent_company.parent}
              <form ng-show="item_{$i}.showform" action="index.php?cmd=ParentCompany&action=removeParentCompany&id={$parent_company.id}" method="POST">
                <a href="#" onclick="$(this).closest('form').submit()">remove</a>
              </form>
            {/if}
            <a href="" ng-click="item_{$i}.showform = false" ng-show="item_{$i}.showform">cancel</a>
            <form ng-show="item_{$i}.showform" action="index.php?cmd=ParentCompany&action=addParentCompany&id={$parent_company.id}" method="POST">
              <label for="">Select Parent Company</label>
              <input type="text" auto-complete="ParentCompany" ac-value="id" ac-text="name" ac-hidden="parent_company_id" ac-ignore="{$parent_company.id}" ac-hint="0">
              <input class="btn" style="width: 100px;" type="submit" value="Submit">
            </form> 
          </td>
        </tr>
        <tr>
          <td colspan="6" style="height: 0px">
            <div id="site_list_{$parent_company.id}" style="background-color: #f9f9f9; float: none; display: none; margin: 0px 0px 0px 0px">
              <table class="sortable" id="sortable_{$parent_company.id}">
                <thead>
                  <tr>
                    <th style="width: 1%; text-align: center">#</th>
                    <th style="width: 1%; text-align: center">&nbsp;</th>
                    <th style="width: 1%; text-align: center">ID</th>
                    <th style="width: 28%; text-align: left">Site</th>
                    <th style="width: 65%; text-align: left">Address</th>
                    <th style="width: 5%">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                {foreach name="companies_loop" from=$parent_company.sites item=company}
                  <tr id="tr_company_{$company.id}">
                    <td>{$smarty.foreach.companies_loop.iteration}</td>
                    <td style="text-align: center"><img style="vertical-align:middle" src="{$APP_URL}app/view/images/icons/group.png" alt="Posts" title="Number of posts at this company" />({$company->posts|@count})</td>
                    <td>{$company.id}</td>
                    <td>
                      <span id="client_{$company.id}" style="font-weight:bold">{$company.name}</span>
                      {if $company.telephone != ""}
                      <br />
                        <span{if $company.telephone_tps == 1} style="color:red"{/if}>{$company.telephone}</span>{*&nbsp;&nbsp;<a href="#">Dial</a>*}
                      {/if}
                      {if $company.website != ""}
                      <br />
                        <a href="{$company.website}" target="_new">{$company.website}</a>
                      {/if}
                    </td>
                    <td>
                      {$company.address.address_1}<br>
                      {$company.address.address_2}<br>
                      {$company.address.town}<br>
                      {$company.address.county.name}<br>
                      {$company.address.postcode}
                    </td>
                    <td class="button">
                      <a id="detailsBtn_{$company.id}" title="Details" onclick="javascript:showCompany({$company.id}, {$parent_company.id}); return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
                    </td>
                  </tr>
                {/foreach}
                </tbody>
               </table>
               <br />
               <a href="#" onclick="new Effect.BlindUp($('site_list_{$parent_company.id}'), {literal}{duration: 0.3}{/literal}); return false;">[Close]</a>
            </div>
          </td>
        </tr>
        <script>
          collns['{$parent_company.id}'] = new ill_Data_Collection();

          {foreach from=$parent_company.sites item=company}
            companies.push({$company.id});
            var row = new Object;
            row.company_id = {$company.id};
            row.post_id = "";
            row.post_initiative_id = "";
            collns['{$parent_company.id}'].add(row);

            {foreach from=$company.posts item=post}
              var row = new Object;
              row.company_id = {$company.id};
              row.post_id = {$post.id};
              row.post_initiative_id = "";
              collns['{$parent_company.id}'].add(row);
            {/foreach}
          {/foreach}
        </script>
        {/foreach}
      </tbody>
    </table>
  </div>

{/if}

{include file="footer2.tpl"}
{/strip}