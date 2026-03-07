
<div id="div_company_menu" style="width: 97%; border: 1px solid #999;background-color: #eee; height: 18px; padding: 3px" >
	<span style="padding-left: 0px; vertical-align: center; width: 100%">
		{*<a href="#" onclick="javascript:getWorkspaceNotesByCompany($F('company_id'), $F('initiative_id'));return false;" title="View all notes for this company for the default initiative" ><img src="{$APP_URL}app/view/images/icons/building.png" alt="Company/initiative notes" /></a>
		&nbsp;&nbsp;*}
		<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{getWorkspaceNotes($F('post_id'), $F('initiative_id'), $F('post_initiative_id'));}{/literal}return false;" title="View notes for this post for the default initiative" ><img src="{$APP_URL}app/view/images/icons/group.png" alt="Post/initiative notes" /></a>
		&nbsp;&nbsp;
		<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=PostInitiativeNoteCreate&amp;post_id=' + $F('post_id') + '&initiative_id=' + $F('initiative_id') + '&post_initiative_id=' + $F('post_initiative_id'));return false;}{/literal}" title="Add note for this post initiative" ><img src="{$APP_URL}app/view/images/icons/comments_add.png" alt="Notes" /></a>
	</span>
</div>
									
{if $notes|@count == 0}
	<p style="text-align: center"><em>&lt;&mdash; No notes found &mdash;&gt;</em></p>
{else}
	<table style="width: 100%" class="default">
		<tbody>
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
					{$result.status}{if $result.old_status} ({$result.old_status}){/if}
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
						<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MeetingEdit&id={$result.meeting_id}&company_id={$result.company_id}');" title="Meeting set for {$result.meeting_date|date_format:"%d/%m/%y %H:%M"}">
							<img src="{$APP_URL}app/view/images/icons/date.png" style="vertical-align: middle" />
						</a>
						{/if}
						{if $result.information_request_id != ""}
						<a href="#" onclick="javascript:openInfoPane('index.php?cmd=InformationRequestEdit&id={$result.information_request_id}&company_id={$result.company_id}');" title="Displays information request detail in info pane">
							<img src="{$APP_URL}app/view/images/icons/script.png" style="vertical-align:middle" />
						</a>
						{/if}
						
						{if $result.next_communication_date != ""}
						<img src="{$APP_URL}app/view/images/icons/calendar_view_day.png" style="vertical-align:middle" title="Call back scheduled for {$result.next_communication_date|date_format:"%d/%m/%y %H:%M"}" />
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
		
			{*
			{if $result.comments != ""}
				<tr>
					<td>Comment:
						<span id="edit_comment_{$result.id}"></em>{$result.comments}</em></span>
						{if $result.communication_type != ''}
							<img id="img_edit_comment_{$result.id}" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align:middle" onclick="javascript:makeInPlaceCommentEditor('{$result.id}');" />
						{/if}
					</td>
				</tr>
			{/if}
			*}
			{if $result.note != ""}
				<tr>
					<td>
						<span id="edit_note_{$result.note_id}">{$result.note|nl2br}</span>
						<br />
						{*{if $result.communication_type != ''}*}
							<img id="img_edit_note_{$result.note_id}" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align:middle" onclick="javascript:makeInPlaceEditor('{$result.note_id}');" />
						{*{/if}*}
					</td>
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
