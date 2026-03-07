{include file="header2.tpl" title="Messages"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

{*
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js"></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script type="text/javascript" src="{$APP_URL}app/view/js/date/date.js"></script> 
*}


<script language="JavaScript" type="text/javascript">

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

{literal}



function toggleReminderDate()
{
	if ($F('set_reminder'))
	{
		if ($('div_reminder_date').style.display == 'block' || $('div_reminder_date').style.display == '') 
		{
			new Effect.BlindUp($('div_reminder_date'), {duration: 0.3});
		}
	}
	else
	{
		if ($('div_reminder_date').style.display == 'none') 
		{
			new Effect.BlindDown($('div_reminder_date'), {duration: 0.3});
		}
	}
	return false;
}

function doFrameItem(location)
{
//	alert('doMenuItem(' + location + ')');
	if (location == '')
	{
		return false;
	}
	else
	{
iframeLocation(		iframe1, location);
	}
}

function editMessage(message_id)
{
iframeLocation(	iframe1, 'index.php?cmd=MessageCreate&message_id=' + message_id);
}

function deleteMessage(message_id)
{
iframeLocation(	iframe1, 'index.php?cmd=MessageDelete&message_id=' + message_id);
}

{/literal}
</script>

<table class="adminform" border="0" cellpadding="0" cellspacing="0"{* style="border: 1px solid blue"*}>
	<tr>
		<td width="75%" valign="top">

			<div style="margin: 0 0 10px 0">
				<input type="button" id="add_message" name="add_message" value="Add Message" href="#" onclick="javascript:doFrameItem('index.php?cmd=MessageCreate&amp;client_id={$client_id}')" />
			</div>

			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Message</th>
									<th style="width: 15%; text-align: center">Timestamp</th>
									<th style="width: 15%; text-align: center">User ID</th>
									<th style="width: 15%; text-align: center">Published</th>
									<th style="width: 5%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=action_loop from=$messages item=message}
							<tr id="tr_{$message->getId()}">
								<td style="text-align: center">{$message->getId()}</td>
								<td>{$message->getMessage()}</td>
								<td style="width: 15%; text-align: center">{$message->getTimestamp()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td>{$message->getUserId()}</td>
								<td style="text-align: center; vertical-align: middle">{if $message->isPublished()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$message->getId()}" title="Edit" href="#" onclick="javascript:editMessage({$message->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$message->getId()}" title="Delete" href="#" onclick="javascript:deleteMessage({$message->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
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