{config_load file="example.conf"}

<span style="display:none;><a href="#" class="popup_closebox">Close</a></span>

<table class="sortable" id="sortable_information_requests_{if $post_initiative}{$post_initiative->getId()}{/if}">
	{assign var='has_current_information_request' value='false'}
	{if $information_requests && $information_requests->count() > 0}
		<thead>
			<tr>
				<th style="width: 20%">Date</th>
				<th>Status</th>
				<th>Note</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		{assign var='has_current_information_request' value='true'}
		{foreach name="information_request_loop" from=$information_requests item=information_request}
			<tr id="tr_information_request_{$information_request->getId()}">
				<td>{$information_request->getDate()|date_format:"%d %b %Y"}</td>
				<td>{$information_request->getStatus()}</td>
				<td>{$information_request->getNotes()}</td>
				<td><a href="#" onclick="javascript:openInfoPane('index.php?cmd=InformationRequestEdit&id={$information_request->getId()}&company_id={$company->getId()}&post_id={$post->getId()}&source_tab={$source_tab}');highlightSelectedInformationRequest('{$information_request->getId()}');return false;" title="Displays information request detail in info pane">Detail/Edit</a></td>
			</tr>
		{/foreach}
		</tbody>
	{else}
		<tr>
			<td colspan="4" style="text-align: center">
				<em>&lt;&mdash; No information requests found &mdash;&gt;</em>
			</td>
		</tr>
	{/if}
</table>
	

<span id="has_current_information_request" style="display:none">{$has_current_information_request}</span>