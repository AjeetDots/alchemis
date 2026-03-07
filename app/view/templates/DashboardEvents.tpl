{include file="header2.tpl" title="Events"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
{literal}

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

function addEvent()
{
iframeLocation(	iframe1, 'index.php?cmd=EventCreate&referrer=DashboardEvents');
}

function editEvent(event_id)
{
//	alert('index.php?cmd=EventCreate&referrer=DashboardEvents&action_id=' + event_id);
iframeLocation(	iframe1, 'index.php?cmd=EventCreate&referrer=DashboardEvents&event_id=' + event_id);
}

function deleteEvent(event_id)
{
//	alert('index.php?cmd=EventDelete&referrer=DashboardEvents&event_id=' + event_id);
iframeLocation(	iframe1, 'index.php?cmd=EventDelete&referrer=DashboardEvents&event_id=' + event_id);
}

{/literal}
</script>

<table class="adminform" border="0" cellpadding="0" cellspacing="0"{* style="border: 1px solid blue"*}>
	<tr>
		<td width="75%" valign="top">

			<div style="margin: 0 0 10px 0">
				<input type="button" id="add_event" name="add_event" value="Add Event" href="#" onclick="javascript:addEvent(); return false;" />
			</div>

			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Subject</th>
									<th style="width: 15%; text-align: center">Due</th>
									<th style="width: 15%; text-align: center">Reminder</th>
									<th style="width: 15%; text-align: center">Type</th>
									<th style="width: 5%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=event_loop from=$events item=event}
							<tr id="tr_{$event->getId()}"{if $event->isOverdue()} class="highlight_negative"{/if}>
								<td style="text-align: center">{$event->getId()}</td>
								<td>{$event->getSubject()}</td>
								<td style="text-align: center">{$event->getDate()|date_format:$smarty.config.FORMAT_DATE_SHORT}</td>
								<td style="text-align: center">{$event->getReminderDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td style="text-align: center; vertical-align: middle">{$event->getType()}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$event->getId()}" title="Edit" href="#" onclick="javascript:editEvent({$event->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$event->getId()}" title="Delete" href="#" onclick="javascript:deleteEvent({$event->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>

		</td>
		<td width="25%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

{include file="footer.tpl"}