{include file="header2.tpl" title="Post Initiative Notes"}
{strip}
<script language="JavaScript" type="text/javascript">
{literal}
// this array used to hold the inPlaceEditor objects for notes
note_inplace_editor_ids = new Array();

function makeInPlaceEditor(id)
{
	if (note_inplace_editor_ids.length > 0)
	{
		var exists = note_inplace_editor_ids.find( function(editor){
				return (editor == id);
			});

		if (exists)
		{	
			// in place editor for this id already exists
			return;
		}
	}	
		
	var in_place_editor = new Ajax.InPlaceEditor('edit_note_' + id , '', {rows:15, cols:60, externalControl: 'img_edit_note_' + id, ill_cmd: 'AjaxCommunication', ill_cmd_action: 'update_note', ill_item_id: id, ill_field: 'note'});
	note_inplace_editor_ids.push(id);
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
}

function AjaxCommunication(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "update_note":
				$("edit_note_" + t.item_id).innerHTML = t.note;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

{/literal}
</script>

<div id="div_company_menu" style="width: 97%; border: 1px solid #999;background-color: #eee; height: 18px; padding: 3px" >
	<span style="padding-left: 0px; vertical-align: center; width: 100%">
		{*<a href="#" onclick="javascript:getWorkspaceNotesByCompany($F('company_id'), $F('initiative_id'));return false;" title="View all notes for this company for the default initiative" ><img src="{$APP_URL}app/view/images/icons/building.png" alt="Company/initiative notes" /></a>
		&nbsp;&nbsp;*}
		{if $post_initiative_id != '' || $post_initiative_id != null}
			<a href="#" onclick="javascript:if (parent.$('post_initiative_id')){literal}{parent.getWorkspaceNotes(parent.$F('post_id'), parent.$F('initiative_id'), parent.$F('post_initiative_id'));}{/literal}return false;" title="View notes for this post for the default initiative" ><img src="{$APP_URL}app/view/images/icons/group.png" alt="Post/initiative notes" /></a>
			&nbsp;&nbsp;
			<a href="#" onclick="javascript:if (parent.$('post_initiative_id')){literal}{parent.openInfoPane('index.php?cmd=PostInitiativeNoteCreate&amp;post_id=' + parent.$F('post_id') + '&initiative_id=' + parent.$F('initiative_id') + '&post_initiative_id=' + parent.$F('post_initiative_id'));return false;}{/literal}" title="Add note for this post initiative" ><img src="{$APP_URL}app/view/images/icons/comments_add.png" alt="Notes" /></a>
		{/if}
	</span>
</div>
									
{if $notes|@count == 0}
	<p style="text-align: center"><em>&lt;&mdash; No notes found &mdash;&gt;</em></p>
{else}
	<table style="width: 100%" class="default">
		<tbody>
		
		{assign var="note_count" value="0"}
		{foreach name="result_loop" from=$notes item=result}
			{if $result.communication_type != ''}
			<tr class="{$result.communication_type} {if $result.effective == "effective"} current{/if}">
			<td>
				<strong>{$result.note_created_at|date_format:"%d/%m/%y %H:%M"} : {$result.user_client_alias}</strong>
				&nbsp;<span style="color: #666">({$result.user_name})</span>
			</td>
			</tr>
			<tr>
				<td style="color: {if $result.post_deleted}#ff3333{else}#999{/if}">
					{$result.job_title}{if $result.full_name} - {$result.full_name}{/if}
					{if $result.post_deleted}<img src="{$APP_URL}app/view/images/icons/group_delete.png" style="vertical-align: middle" title="Post deleted" />{/if}
				</td>
			</tr>
			<tr>
				<td style="color: #0B55C4">
					<strong>{$result.status}</strong>
					{if $note_count == 0}
						&nbsp;&nbsp;
						<a href="#" style="color: black" onclick="parent.openInfoPane('index.php?cmd=CommunicationEdit&id={$result.id}&parent_tab=' + parent.tab_id);" title="Edit status for this communication">
							[change status of this call]
						</a>
						{assign var="note_count" value=`$note_count+1`}	
					{/if}
					{if $result.old_status} ({$result.old_status}){/if}
					{if $result.decision_maker_type_id == 1}
						<img src="{$APP_URL}app/view/images/icons/key.png" style="vertical-align:middle" title="Decision maker contacted" />
					{/if}
				</td>
			</tr>
				{if $result.meeting_id != "" || $result.information_request_id != "" || $result.next_communication_date != ""}
				<tr>
					<td style="color:#ff3333">
						Actions: 
						{if $result.meeting_id != ""}
						<a href="#" onclick="javascript:parent.openInfoPane('index.php?cmd=MeetingEdit&id={$result.meeting_id}&company_id={$result.company_id}');" title="Meeting set for {$result.meeting_date|date_format:"%d/%m/%y %H:%M"}">
							<img src="{$APP_URL}app/view/images/icons/date.png" style="vertical-align: middle" />
						</a>
						&nbsp;|&nbsp;
						{/if}
						{if $result.information_request_id != ""}
						<a href="#" onclick="javascript:parent.openInfoPane('index.php?cmd=PostInitiativeActionEdit&post_initiative_id={$result.post_initiative_id}&referrer_type=workspace&action_id={$result.information_request_id }&type_id=');" title="Displays information request detail in info pane">
							<img src="{$APP_URL}app/view/images/icons/script.png" style="vertical-align:middle" />
						</a>
						&nbsp;|&nbsp;
						{/if}
						{if $result.next_communication_date != ""}
						<img src="{$APP_URL}app/view/images/icons/calendar_view_day.png" style="vertical-align:middle" title="Call back scheduled for {$result.next_communication_date|date_format:"%d/%m/%y %H:%M"}" />
						&nbsp;|&nbsp;
						{/if}
					</td>
				</tr>
				{/if}
				{if $result.effective == "effective"}
				<tr>
					<td>
						<strong>{$result.effective|capitalize}</strong>&nbsp;
					</td>
				</tr>
				{/if}
			{elseif $result.post_initiative_note_type == "system_email"}
			<tr class="system_email">
				<td>
					<strong>{$result.note_created_at|date_format:"%d/%m/%y %H:%M"} : {$result.user_name}</strong>
				</td>
			</tr>
			<tr>
				<td style="color: #666">
					Auto e-mail received
				</td>
			</tr>
			{else}
			<tr class="system">
				<td>
					<strong>{$result.note_created_at|date_format:"%d/%m/%y %H:%M"} : {$result.user_name}</strong>
				</td>
			</tr>
			<tr>
				<td style="color: #666">
					System update
				</td>
			</tr>
			{/if}
			{if $result.communication_type == 'mailer' || $result.communication_type == 'email'}
				{if $result.comments != ""}
					<tr>
						<td><span id="edit_comment_{$result.id}"></em>{$result.comments}</em></span></td>
					</tr>
				{/if}
			{/if}
			{if $result.note != ""}
				<tr>
					<td>
						
				{if $result.communication_type == 'email'}
					<span id="edit_note_{$result.note_id}">{$result.note|nl2br|replace:"\n":''|replace:"\r":''|replace:"<br /> ":'<br />'}</span>
				{elseif $result.post_initiative_note_type == 'system_email'}
					<span id="edit_note_{$result.note_id}">{if substr($result.summary,0,3) == "Re:"}{$result.summary|replace:'Re:':''}{else}{$result.summary}{/if}</span>
					<br />
					<span style="float:right"><a href="#" onclick="javascript:parent.openInfoPane('index.php?cmd=PostInitiativeNote&post_initiative_note_id={$result.note_id}');" title="Show the entire content of this e-mail">
						View e-mail
					</a></span>
				{else}
						<span id="edit_note_{$result.note_id}">{$result.note|nl2br|replace:"\n":''|replace:"\r":''|replace:"<br /> ":'<br />'}</span>
						<br />
						<img id="img_edit_note_{$result.note_id}" src="{$APP_URL}app/view/images/icons/application_edit.png" style="vertical-align:middle" onclick="javascript:makeInPlaceEditor('{$result.note_id}');" />
				{/if}
		
					</td>
				</tr>
			{else}
				{*add an add note to communication link if there isn't already a note for this communication*}
					
				<tr>
					<td>
				{if $result.id != ''}
					{if $result.communication_type == 'email'}
					{elseif $result.post_initiative_note_type == 'system_email'}
					{else}
						<a href="#" onclick="javascript:parent.openInfoPane('index.php?cmd=PostInitiativeNoteCreate&post_initiative_id={$result.post_initiative_id}&communication_id={$result.id}');" title="Add a note">
							<img src="{$APP_URL}app/view/images/icons/application_add.png" style="vertical-align:middle" />
						</a>
					{/if}	
					</td>
				</tr>
					
				{/if}
			{/if}
			{if $result.communication_type == 'email' && $result.has_attachment}
				
				<tr>
					<td><a href="#" onclick="javascript:parent.openInfoPane('index.php?cmd=CommunicationAttachments&communication_id={$result.id}')">View attachments</a></td>
				</tr>
				
			{/if}
			<tr>
				<td>
					<hr style="border: 0px solid #ccc; border-top-width: 1px; height: 0px" />
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
{/if}
{/strip}
{include file="footer2.tpl"}