{strip}
<div>
{if !$company}
<p>No search results</p>
{else}

<input type="hidden" id="company_id" name="company_id" value="{$company->getId()}" />
<input type="hidden" id="post_id" name="post_id" value="{if $post}{$post->getId()}{/if}" />


<div id="popup_doCall" class="popup" style="display: none; height: 70px; width: 320px; overflow-x: hidden; overflow-y: auto;">
	<span style="position: absolute; top: 5px; right: 5px; z-index: 100;"><a href="#" class="popup_closebox">Close</a></span>
	<div id="call-to-dialog"></div>
</div>
<script type="text/javascript">
	{literal}
	var var_popup_doCall = null;
	window.setTimeout(function(){
		var_popup_doCall = new Popup('popup_doCall','popup_doCall_link',{position:'395,110',trigger:'click',duration:'0.25',show_delay:'100'});
	},600); 
	// set the page level var 'last_post_class_change_id' equal to the selected post - otherwise when another post
	//is selected from the 'popup_posts' div, the style of the original row is not reset to ''
	{/literal}
</script>

<div id="company_do_not_call_alert" style="text-align: center; width:350px; background-color:#ffd; padding: 3px; border: thin solid red; display: {if $company_do_not_call}block{else}none{/if}">
	<span style="color: red; font-weight: bold;">DO NOT CALL</span>
</div>
				
<table class="ianlist">
	<tr>
		<td colspan="2">
			<span style="float: right;" id="company_count"></span>
			<ul class="parent-companies">
				{foreach name="parents" from=$parent_companies item=parent_company}
					<li style="padding-left:{$smarty.foreach.parents.index}0px">
						<img id="img_edit_parent_company_name_{$parent_company.id}" src="{$APP_URL}app/view/images/icons/building_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit parent company name" />
						<span id="edit_parent_company_name_{$parent_company.id}">{$parent_company.name}</span>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('edit_parent_company_name_{$parent_company.id}', '', {literal}{{/literal}externalControl: 'img_edit_parent_company_name_{$parent_company.id}', ill_cmd: 'ParentCompany', ill_cmd_action: 'updateName', ill_item_id: {$parent_company.id}{literal}, ill_field: 'name'}{/literal});
						</script>
					</li>
				{/foreach}
			</ul>
		</td>
	</tr>
	<tr>
		<th style="width: 1%;"><img id="img_edit_company_name" src="{$APP_URL}app/view/images/icons/building_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit company name" /></th>
		<td style="width: 99%;"><span id="edit_company_name" style="font-size: 11px; font-weight: bold">{$company->getName()|escape}</span></td>
		<script type="text/javascript">
			var var_edit_company_name = new Ajax.InPlaceEditor('edit_company_name', '', {literal}{externalControl: 'img_edit_company_name', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_name', ill_item_id: {/literal}{$company->getId()}{literal}, ill_field: 'name'}{/literal});
		</script>
	</tr>
	<tr>
		<th style="width: 1%;"><img id="img_edit_company_telephone" src="{$APP_URL}app/view/images/icons/telephone_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit company telephone" /></th>
		<td style="width: 99%;">
		
		{*<td style="width: 99%; {if $company->getTelephoneTps()}color: red;{/if}"><span id="edit_company_telephone">{$company->getTelephone()}</span>*}{*&nbsp;&nbsp;<a href="#">Dial</a>*}{*}
		&nbsp;&nbsp;&nbsp;
		<a href="voispeed:{$company->getTelephone()|replace:' ':''}">
			[Dial]
		</a>
		&nbsp;|&nbsp;
		<a href="#" onclick="javascript:setCompanyTelephoneTps({$company->getId()});">
			<span id="sp_telephone_tps">[Make {if $company->getTelephoneTps()}Non {/if}TPS]</span>
		</a>*}
		{foreach from=$companyTelephoneTpsStatus item=item key=index}
			<a rel="company" onclick="javascript:checkTPS('{$item.number|replace:' ':''}',this);" rel="{$item.number|replace:' ':''}" href="javascript:void(0);" title="TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if}" style="{$item.style}">{$item.number}</a> <small>(TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if})</small>
			{*&nbsp;&nbsp;&nbsp;
			<a href="voispeed:{$item.number|replace:' ':''}" title="TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if}" style="{$item.style}">[Dial]</a>*}
			<br />
		{/foreach}
		<br />
		<span id="edit_company_telephone" style="display:none;">{$company->getTelephone()}</span>
		{*
		<hr />
		<a href="javascript:refreshCompanyDetail({$company->getId()}, {if $post}{$post->getId()}{else}null{/if});">[Refresh TPS]</a>*}
		</td>
		<script type="text/javascript">
			var var_edit_company_telephone = new Ajax.InPlaceEditor('edit_company_telephone', '', {literal}{externalControl: 'img_edit_company_telephone', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_telephone', ill_item_id: {/literal}{$company->getId()}{literal}, ill_field: 'telephone'}{/literal});
		</script>
	</tr>
	<tr>
		<th style="width: 1%;"><img id="img_edit_website" src="{$APP_URL}app/view/images/icons/world_edit.png" style="cursor:pointer; vertical-align:top" title="Edit website"></th>
		<td style="width: 99%"><span id="edit_website">{$company->getWebsite()}</span>&nbsp;&nbsp;<a href="{$company->getWebsite()}" target="_new">Go</a></td>
		<script type="text/javascript">
			var var_edit_website = new Ajax.InPlaceEditor('edit_website', '', {literal}{externalControl: 'img_edit_website', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_website', ill_item_id: {/literal}{$company->getId()}{literal}, ill_field: 'website'}{/literal});
		</script>
	</tr>
	<tr>
		<th><img id="img_edit_additional_info" src="{$APP_URL}app/view/images/icons/building_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit Additional Infomation" /></th>
		<td>
			<span id="edit_additional_info">{$company->getAdditionalInfo()}</span>
			<script type="text/javascript">
				var var_img_edit_additional_info = new Ajax.InPlaceEditor('edit_additional_info', '', {literal}{externalControl: 'img_edit_additional_info', ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_additional_info', ill_item_id: {/literal}{$company->getId()}{literal}, ill_field: 'additional_info'}{/literal});
			</script>
		</td>
	</tr>
	<tr>
		{assign var="address" value=$company->getSiteAddress(null, 'paragraph')}
		<th style="width: 1%; vertical-align: top;">
		
			{*{if $address == ""}*}
			{if $company->getSiteId() == null || $company->getSiteId() == ''}
				<a href="javascript: openInfoPane('index.php?cmd=SiteCreate&amp;company_id={$company->getId()}');"><img id="img_edit_company_telephone" src="{$APP_URL}app/view/images/icons/application_form_edit.png" style="vertical-align:middle" title="Add address" /></a>
			{else}
				<a href="javascript: openInfoPane('index.php?cmd=SiteEdit&amp;id={$company->getSiteId()}&amp;company_id={$company->getId()}');"><img id="img_edit_company_telephone" src="{$APP_URL}app/view/images/icons/application_form_edit.png" style="vertical-align:middle" title="Edit address"></a>
			{/if}
		</th>
		<td style="width: 99%">
		{if $address == ""}
			<em>No address found</em>
		{else}
			{$address}	
			<br />
		{/if}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			{if $company_posts_job_title}
				<input type="hidden" name="post_list_by_post" id="post_list_by_post" value="{$post->getId()}" />
				<a href="#" id="popup_posts_link"></a>
				
				<div id="popup_posts" class="popup" style="display: none; height: 250px; width: 800px; overflow-x: hidden; overflow-y: y:auto">
					<span style="position: fixed; top: 220px; left: 45px; z-index: 100;"><a href="#" class="popup_closebox">Close</a></span>
			    	<table class="sortable" id="sortable_{$company->getId()}">
			    	<thead>
						<tr>
							<th>Job Title</th>
							<th>Post Holder</th>
							<th>Telephone</th>
							<th>Propensity</th>
						</tr>
					</thead>
			    	<tbody>
				 	{foreach name="result_loop" from=$company_posts_job_title item=result}
				 		<tr id="tr_post_{$result.id}" {if $post->getId() == $result.id}class="current"{/if} >
				 			<td><a href="#" class="popup_closebox" onclick="javascript:highlightSelectedPost({$result.id});loadPost({$result.id}, null, null);return false;">{$result.job_title}<a/></td>
				 			<td>{$result.first_name}&nbsp;{$result.surname}</td>
				 			<td>{$result.telephone_1}</td>
				 			<td style="text-align: center">
				 				<span style="display: none">{$result.propensity}</span>
				 				<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
				 			</td>
				 		</tr>
				 	{/foreach}
				 	</tbody>
					</table>
			  	</div>
			  	<script type="text/javascript">
			  		var var_popup_posts = new Popup('popup_posts','popup_posts_link',{literal}{position:'20,240',trigger:'click',duration:'0.25',show_delay:'100'}{/literal});
			  		// set the page level var 'last_post_class_change_id' equal to the selected post - otherwise when another post
					//is selected from the 'popup_posts' div, the style of the original row is not reset to ''
					last_post_class_change_id = {$post->getId()};
			    </script>
			{else}
				<!--Need to create a hidden text box to act as a dummy container for the non-existent post
				   This is because some menu items expect a value from a field called "post_list_by_post"
				   and will fail if this control does not exist. -->
				<input type="hidden" name="post_list_by_post" id="post_list_by_post" value="" />
				<em>No posts exist for this company</em>&nbsp;<a href="javascript:openInfoPane('index.php?cmd=PostCreate&amp;company_id={$company->getId()}');"><img src="{$APP_URL}app/view/images/icons/group_add.png" style="cursor:pointer; vertical-align:top" title="Add post" /></a>		
			{/if}
			
		</td>
	</tr>
</table>

    <script language="JavaScript">
	{if $company->getTelephoneTps()}
		var company_telephone_tps = true;	
	
		{literal}
		if (company_telephone_tps)
		{
			new Effect.Pulsate($('edit_company_telephone'), {duration: 5});
		}
		
		{/literal}
	{/if}
	</script>

{/if}
	
</div>
{/strip}
