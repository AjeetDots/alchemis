{strip}
{if $post}
<input type="hidden" id="post_contact_title" name="post_contact_title" value="{if $post->getContact()}{$post->getContactTitle()}{else}{/if}" />
<input type="hidden" id="post_contact_first_name" name="post_contact_first_name" value="{if $post->getContact()}{$post->getContactFirstName()}{else}{/if}" />
<input type="hidden" id="post_contact_surname" name="post_contact_surname" value="{if $post->getContact()}{$post->getContactSurname()}{else}{/if}" />

<!-- div for displaying post info -->
<div id="div_display_post" style="display: {if $post}block{else}none{/if}">
	<table class="ianlist">
		<tr>
			<th style="width: 1%; vertical-align:top">
				<a href="javascript: openInfoPane('index.php?cmd=PostEdit&amp;id={if $post}{$post->getId()}{/if}}');">
					<img id="img_edit_post" src="{$APP_URL}app/view/images/icons/user_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit post">
				</a>
			</th>
			<td style="width: 99%;">
				<span style="font-size: 11px; font-weight: bold; margin: 1px; padding-bottom: 5px;">{if $post}{$post->getJobTitle()}{else}Job title: <span style="color: red">UNKNOWN</span>{/if}</span>
				<br />
				{if $post->getContactName()}{$post->getContactTitle()}&nbsp;{$post->getContactName()}{else}Post holder: <span id="span_contact_name" style="color: red">POST VACANT</span>{/if}
				<br />
				<img src="{if $post}{$APP_URL}app/view/images/propensity_{$post->getPropensity()}.gif{/if}" style="vertical-align:middle" 
					alt="Propensity {$post->getPropensity()}" title="Propensity {$post->getPropensity()}" />
			</td>
		</tr>
		<tr>
			<th style="width: 1%;">
				<img id="img_edit_telephone_1" src="{$APP_URL}app/view/images/icons/telephone_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit direct line">
			</th>
			<td style="width: 99%;">
				{* <span id="edit_telephone_1">{if $post}{$post->getTelephone1()}{else}Telephone unknown{/if}</span>&nbsp;&nbsp;
				{if $post && $post->getTelephone1() != ""}
				<a href="voispeed:{$post->getTelephone1()|replace:' ':''}">
					[Dial]
				</a>
				{/if} *}
				{if isset($postTelephoneTpsStatus) }
				{foreach from=$postTelephoneTpsStatus item=item key=index}
					<a rel="post" onclick="javascript:checkTPS('{$item.number|replace:' ':''}',this);" rel="{$item.number|replace:' ':''}" href="javascript:void(0);" title="TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if}" style="{$item.style}">{$item.number}</a> <small>(TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if})</small>
					{*&nbsp;&nbsp;&nbsp;
					<a href="voispeed:{$item.number|replace:' ':''}" title="TPS: {$item.status|ucwords} {if $item.updated_at} On {$item.updated_at|date_format:"%d/%b/%Y, %H:%M"} {/if}" style="{$item.style}">[Dial]</a>*}
					<br />
				{/foreach}
				{else}
                    {if $post && $post->getTelephone1() != ""}
                    <a rel="post" onclick="javascript:checkTPS('{$post->getTelephone1()|replace:' ':''}',this);" rel="{$post->getTelephone1()|replace:' ':''}" href="javascript:void(0);" title="TPS: Not Checked">{$post->getTelephone1()}</a> <small class="not-checked">(TPS: Not Checked)</small>
                    {/if}
                {/if}
				<br />
				<span id="edit_telephone_1" style="display:none;">{if $post}{$post->getTelephone1()}{else}Telephone unknown{/if}</span>
                <script type="text/javascript">
                var var_edit_telephone_1 = new Ajax.InPlaceEditor('edit_telephone_1', '', {literal}{externalControl: 'img_edit_telephone_1', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_telephone_1', ill_item_id: {/literal}{if $post}{$post->getId()}{/if}{literal}, ill_field: 'telephone_1'}{/literal});
                </script>
			</td>
		</tr>
		<tr>
			<th style="width: 1%;">
				<img id="img_edit_email" src="{$APP_URL}app/view/images/icons/email_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit email address">
			</th>
			<td style="width: 99%;">
				<span id="edit_email">{if $contact}{$contact->getEmail()}{else}Email unknown{/if}</span>&nbsp;&nbsp;{if $contact && $contact->getEmail() != ""}<a href="#" onclick="javascript:sendMail('edit_email');return false">E-mail</a>{/if}</td>
                <script type="text/javascript">
                var var_edit_email = new Ajax.InPlaceEditor('edit_email', '', {literal}{externalControl: 'img_edit_email', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_email', ill_item_id: {/literal}{if $post}{$post->getId()}{/if}{literal}, ill_field: 'email'}{/literal});
                </script>
                <form  NAME="post_email" ACTION="mailto:" METHOD="post"  ENCTYPE="multipart/form-data" onSubmit="">
                </form>
			</td>
		</tr>
        <tr>
            <th><img id="post_edit_additional_info" src="{$APP_URL}app/view/images/icons/building_edit.png" style="cursor:pointer; vertical-align:middle" title="Edit Additional Infomation" /></th>
            <td>
                <span id="edit_post_additional_info">{$post->getAdditionalInfo()}</span>
                <script type="text/javascript">
                    var var_post_edit_additional_info = new Ajax.InPlaceEditor('edit_post_additional_info', '', {literal}{externalControl: 'post_edit_additional_info', ill_cmd: 'AjaxPost', ill_cmd_action: 'update_additional_info', ill_item_id: {/literal}{$post->getId()}{literal}, ill_field: 'additional_info'}{/literal});
                </script>
            </td>
        </tr>
		<tr>
			<th style="width: 1%;">
				<img id="img_edit_telephone_mobile" src="{$APP_URL}app/view/images/icons/telephone_edit.png" style="cursor:pointer; vertical-align:middle; width:16px; height:16px;" title="Edit Mobile profile">
			</th>
		  <td style="width: 99%;">
				<span id="edit_telephone_mobile">{if $contact}{$contact->getTelephoneMobile()}{else}Mobile unknown{/if}</span>&nbsp;&nbsp;{if $contact && $contact->getTelephoneMobile() != ""}<a href="#" onclick="javascript:sendMobile('edit_telephone_mobile');return false">Mobile</a>{/if}</td>
                <script type="text/javascript">
                var var_edit_telephone_mobile = new Ajax.InPlaceEditor('edit_telephone_mobile', '', {literal}{externalControl: 'img_edit_telephone_mobile', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_telephone_mobile', ill_item_id: {/literal}{if $post}{$post->getId() }{/if}{literal}, ill_field: 'telephone_mobile'}{/literal});
                </script>
              <form  NAME="post_telephone_mobile" ACTION="tel:" METHOD="post"  ENCTYPE="multipart/form-data" onSubmit="">
                </form>
			</td>
		</tr>
		<tr>
			<th style="width: 1%;">
				<img id="img_edit_linked_in" src="{$APP_URL}app/view/images/icons/linkedin.png" style="cursor:pointer; vertical-align:middle; width:16px; height:16px;" title="Edit LinkedIn profile">
			</th>
			<td style="width: 99%;">
                <span id="edit_linked_in" {if $contact && $contact->getLinkedIn() != ""}style="display:none"{/if}>{if $contact && $contact->getLinkedIn() != ""}{$contact->getLinkedIn()}{else}No profile available{/if}</span>{if $contact && $contact->getLinkedIn() != ""}<a href="#" onclick="makeHrefFromElement('edit_linked_in');">Go to LinkedIn</a>{/if}
                <script type="text/javascript">
                var var_edit_linked_in = new Ajax.InPlaceEditor('edit_linked_in', '', {literal}{externalControl: 'img_edit_linked_in', ill_cmd: 'AjaxPost', ill_cmd_action: 'edit_linked_in', ill_item_id: {/literal}{if $post}{$post->getId()}{/if}{literal}, ill_field: 'linked_in', ill_hide_on_save: true}{/literal});
                </script>
			</td>
		</tr>

    <tr>
      <th style="width: 1%;"></th>
      <td style="width: 99%;">
        <strong>Data Source</strong><br>
        {$post->getDataSource()} ({$post->getDataSourceChangedDate()|date_format:"%d %B %Y at %H:%M"})
      </td>
    </tr>
	</table>
	
</div>
{else}

<!-- div for displaying NO post message -->
<div id="div_display_no_post" style="display: {if $post}none{else}block{/if}">
<input type="hidden" id="post_contact_title" name="post_contact_title" value="" />
<input type="hidden" id="post_contact_first_name" name="post_contact_first_name" value="" />
<input type="hidden" id="post_contact_surname" name="post_contact_surname" value="" />
</div>
{/if}
{/strip}
<script type="text/javascript">
	{literal}
		function makeHrefFromElement(elementId) {
			var url = $(elementId).innerHTML; 
			if (url.substr(0, 7) != 'http://') {
				if (url.substr(0, 8) != 'https://') {
					url = "http://" + url;
				} else {
					// do nothing
				}
			} else {
				// do nothing
			}
			return window.open(url,'linked_in');
		}
	{/literal}
</script>		