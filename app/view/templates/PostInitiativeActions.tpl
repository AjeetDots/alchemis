{include file="header2.tpl" title="Post Initiative Actions"}

<fieldset class="adminform">

	<legend>{if $action_type}{$action_type} a{else}A{/if}ctions for<br />
	{$post->getJobTitle()}</legend>
	<br />
	<strong>Post holder: {if $post->getContactName()}{$post->getContactName()}{else}Unknown{/if}</strong>
	<br /><br />
	<strong>Client: {$initiative_name}</strong>
	<br /><br />
	<input type="hidden" id="post_initiative_id" name="post_initiative_id" value={$post_initiative_id}" />
	<input type="hidden" id="type_id" name="type_id" value="{$type_id}" />
	<a href="#" onclick="javascript:document.location.href='index.php?cmd=PostInitiativeActionEdit&post_initiative_id={$post_initiative_id}&referrer_type={$referrer_type}{if $type_id}&type_id={$type_id}{/if}'">
		Add new action
	</a>
	{if $actions->toArray()|@count > 0}
		<table id="table1" class="adminlist sortable" border="0" cellpadding="0" cellspacing="1" width="100%">
			<thead>
				<tr>
					<th style="width: 50%; text-align:left">Type</th>
					<th style="width: 30%; text-align:left">Due Date</th>
					<th style="width: 20%;">&nbsp;</th>
				</tr>
			<tfoot>
				<tr>
					<th colspan="5">&nbsp;</th>
				</tr>
			</tfoot>
			<tbody>
				{foreach name=actions_loop from=$actions item=action}
					<tr>
						<td{if $action->isOverdue() && !$action->getIsCompleted()} style="color:red"{elseif $action->getIsCompleted()} style="text-decoration:line-through"{/if}>{$action->getTypeName()}</td>
						<td{if $action->isOverdue() && !$action->getIsCompleted()} style="color:red"{elseif $action->getIsCompleted()} style="text-decoration:line-through"{/if}>{$action->getDueDate()|date_format:"%d %B %Y"}</td>
						<td>
						{if !$action->getIsCompleted()}
							<a href="#" onclick="javascript:document.location.href='index.php?cmd=PostInitiativeActionEdit&post_initiative_id={$post_initiative_id}&referrer_type={$referrer_type}&action_id={$action->getId()}&type_id={$type_id}'">
								Edit
							</a>
							{if $action->getTypeId() == 2&& $referrer_type == 'workspace'}
							<a href="index.php?cmd=InformationRequestPrint&amp;id={$action->getId()}" target="_blank" title="Print information request" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a>
							<a href="index.php?cmd=InformationRequestEmail&amp;id={$action->getId()}" target="_blank" title="Email information request" ><img src="{$APP_URL}app/view/images/icons/email.png" alt="Email" /></a>		
							{/if}
						{/if}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{else}
		<p><em>&lt;&mdash; No actions found &mdash;&gt;</em></p>
	{/if}
</fieldset>

{include file="footer2.tpl"}