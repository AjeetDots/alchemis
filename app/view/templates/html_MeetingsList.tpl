{config_load file="example.conf"}

<span style="display:none;><a href="#" class="popup_closebox">Close</a></span>
{*
{if $allow_add_meeting == true}
<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MeetingCreate&post_initiative_id={$post_initiative_id}&company_id={$company_id}&source_tab=' + tab_id);return false;" title="Add a new meeting">
	<img id="popup_icon_meetings" src="{$APP_URL}app/view/images/icons/date_add.png" alt="Add meeting" />
</a>
<br />
{/if}
*}
<table class="adminlist"{if $meetings && $meetings->count() > 0} class="sortable" id="sortable_meetings_{$post_initiative_id}"{/if}>

{assign var='has_current_meeting' value='false'}
{if $meetings && $meetings->count() > 0}
	<tr>
		<th>Date</th>
		<th>Status</th>
		{*<th>Note</th>*}
		{*<th>&nbsp;</th>*}
		<th>&nbsp;</th>
	</tr>
	{foreach name="meeting_loop" from=$meetings item=meeting}
	{* NOTE: record whether we have at least one current meeting. *}
	{*{if $meeting->getStatusId() == 1}
		{assign var='has_current_meeting' value='true'}
		<script type="text/javascript">
			openInfoPane('index.php?cmd=MeetingEdit&id={$meeting->getId()}&company_id={$company_id}&source_tab=' + tab_id);
		</script>
	{/if}*}
	<tr>
		<td>{$meeting->getDate()|date_format:"%d %b %y at %H:%M"}</td>
		<td>{$meeting->getStatus()}</td>
		{*<td>{$meeting->getNotes()}</td>*}
		<td>
			<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MeetingEdit&id={$meeting->getId()}&company_id={$company_id}&source_tab=' + tab_id + '&allow_edit=false');return false;" title="Displays meeting detail">Detail</a>
			<br />
			<a href="#" onclick="javascript:if(tab_id==4){literal}{{/literal}openInfoPane('index.php?cmd=MeetingEdit&id={$meeting->getId()}&company_id={$company_id}&source_tab=' + tab_id);return false;{literal}}else{{/literal}top.loadTab(4,'Communication&company_id={$company_id}&post_id={$post_id}&post_initiative_id={$post_initiative_id}&initiative_id={$initiative_id}&source_tab=' + tab_id, true);return false;{literal}}{/literal}" title="Displays meeting detail and allows edit">Edit</a>
			<br />
			<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MeetingHistory&meeting_id={if $meetings}{$meeting->getId()}{/if}&company_id={$company_id}');return false;" title="Displays meeting history">History</a>
			<br />
			<a href="#" onclick="javascript:openInfoPane('index.php?cmd=MeetingActions&meeting_id={if $meetings}{$meeting->getId()}{/if}');return false;" title="Displays meeting actions">Actions</a>
			
		</td>
		<td><a href="index.php?cmd=MeetingPrint&amp;id={$meeting->getId()}" target="_blank" title="Print meeting" ><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" /></a></td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="4" style="text-align: center">
			<em>&lt;&mdash; No meetings found &mdash;&gt;</em>
		</td>
	</tr>
{/if}
</table>
<span id="has_current_meeting" style="display:none">{$has_current_meeting}</span>