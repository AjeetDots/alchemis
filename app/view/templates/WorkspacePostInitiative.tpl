{strip}	
{*{if $post_initiative}*}
<div style="height:350px; overflow-x: hidden; overflow-y: y:auto">
	{*post initiative id: {if $post_initiative}{$post_initiative->getId()}{/if}*}
	<input type="hidden" id="initiative_id" name="initiative_id" value="{if $initiative_id}{$initiative_id}{/if}" />
	<input type="hidden" id="post_initiative_id" name="post_initiative_id" value="{if $post_initiative}{$post_initiative->getId()}{/if}" />
	
	<table class="adminlist" style="100%">
		<tr>
			<th style="vertical-align: top" colspan="2">
				<!-- div for displaying post initiatives if they exist -->
				<div id="div_display_post_initiatives" style="display: {if $post_initiatives_options}block{else}none{/if}">
					<select style="width: 100%" id="post_initiatives" onchange="javascript:loadAssociatedPosts({$post_id}, this.options[this.selectedIndex].value);">
							{html_options options=$post_initiatives_options selected=$post_initiatives_selected_option}
						</select>
					<br />
				</div>
				
				<!-- div for displaying NO post initiatives message -->
				<div id="div_display_no_post_initiatives" style="display: {if $post_initiatives_options}none{else}block{/if}">
					<br />
					<em>No client intiatives exist for this post</em>
					<br />
					{*<select name="client_initiatives" id="client_initiatives" style="width: 100%">
						{html_options options=$client_initiatives_options selected=$client_initiatives_selected_option}
					</select>*}
				</div>
				
				{* Meeting warnings *}
				<div id="meeting_alert" style="text-align: center; width:99%; background-color:#ffd; padding: 3px; border: thin solid red; display: {if $meetings}block{else}none{/if}">
					<span style="color: red; font-weight: bold;">Meetings exist</span>
				</div>
				{if $meetings}
				<br />
				<script language="JavaScript">
				{literal}
					new Effect.Pulsate($('meeting_alert'), {duration: 5});
				{/literal}
				</script>
				{/if}
				
				{* action warnings *}
				<div id="action_alert" style="text-align: center; width:99%; background-color:#ffd; padding: 3px; border: thin solid {if $overdue_actions}red{elseif $actions}green{/if}; display: {if $actions || $overdue_actions}block{else}none{/if}">
					<a href="#" onclick="javascript:openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id={if $post_initiative}{$post_initiative->getId()}{/if}&referrer_type=workspace');return false;" title="Displays actions">
						<span style="color: {if $overdue_actions}red{elseif $actions}green{/if}; font-weight: bold;">{if $overdue_actions}Overdue {/if} Actions exist</span>
					</a>	
				</div>
				<script language="JavaScript">
				{if $overdue_actions || $actions}
				{literal}
					new Effect.Pulsate($('action_alert'), {duration: 5});
				{/literal}
				{/if}
				</script>
				
				
			</th>
		</tr>
		{if $post_initiative}
		<tr>
			<th style="vertical-align: top">Comment</th>
			<td>
				<span id="span_comment" style="display: {if $post_initiative}inline{else}none{/if}">
					{if $post_initiative}{$post_initiative->getComment()}{/if}
				</span>
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Next Action By</th>
			<td>
				<span id="span_next_action_by" style="display: {if $post_initiative}inline{else}none{/if}">
					{if $post_initiative}{$post_initiative->getNextActionByName()}{/if}
				</span>
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Last contact</th>
			<td>
				<span id="span_post_initiative_last_communication_date" style="display: {if $post_initiative}inline{else}none{/if}">
					{if $post_initiative}{$post_initiative->getLastCommunicationDate()|date_format:"%d %B %Y"}{/if}
				</span>
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Last effective</th>
			<td>
				<span id="span_post_initiative_last_effective_date" style="display: {if $post_initiative}inline{else}none{/if}">
					{if $post_initiative}{$post_initiative->getLastEffectiveCommunicationDate()|date_format:"%d %B %Y"}{/if}
				</span>
			</td>
		</tr>
		<tr>
			<th>Next call due</th>
			<td>
				<span id="span_post_initiative_next_communication_date" style="display: {if $post_initiative}inline{else}none{/if}">
					{if $post_initiative}
						{$post_initiative->getNextCommunicationDate()|date_format:"%d %B %Y at %H:%M"}
						&nbsp;&nbsp;&nbsp;
						{if $post_initiative->getPriorityCallBack() == true}
							<img src="{$APP_URL}app/view/images/tick.png" style="" alt="Priority Callback" title="Priority Callback" />
						{/if}
					{/if}
				</span>
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Call Name</th>
			<td>
				{if $post_initiative}{$post_initiative->getLastCommunicationUserClientAlias()}{/if}
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Lead Source</th>
			<td>
				{if $post_initiative}{$post_initiative->getLeadSource()}{/if}
			</td>
		</tr>
    <tr>
			<th style="vertical-align: top">Data Source</th>
			<td>
				{if $post_initiative}{$post_initiative->getDataSource()} ({$post_initiative->getDataSourceChangedDate()|date_format:"%d %B %Y at %H:%M"}){/if}
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top">
				Project Refs<br />
				<a href="javascript:openInfoPane('index.php?cmd=WorkspaceProjectRefTags&parent_object_type=app_domain_PostInitiative&parent_object_id={if $post_initiative}{$post_initiative->getId()}{/if}&category_id=3&initiative_id=' + $F('initiative_id'));">
				{if $project_refs|@count == 0}
				[Add]
				{else}				
				[Add/Edit]
				{/if}
				</a>
				{*<br />
				<a href="javascript:displayPostInitiativeProjectRefs();">[Refresh]</a>*}
			</th>
			<td>
				<div id="div_project_refs">
				{if $project_refs}
				{foreach name="project_ref_loop" from=$project_refs item=project_ref}
					{$project_ref.value}<br />
				{/foreach}
				{/if}
				</div>
			</td>
		</tr>
		{/if}
	</table>
	   
    {* activate the do not call div on the company page *}
    <script language="JavaScript">
	{if $company_do_not_call}
		var company_do_not_call = true;	
	{else}
		var company_do_not_call = false;		
	{/if}
		{literal}
		if (company_do_not_call)
		{
			new Effect.Pulsate($('company_do_not_call_alert'), {duration: 20});
		}
		else
		{
			$('company_do_not_call_alert').hide();
		}
		{/literal}
	</script>
	
</div>
{*{else}*}
	{*No client initiatives exist for this post*}
{*{/if}*}
{/strip}
