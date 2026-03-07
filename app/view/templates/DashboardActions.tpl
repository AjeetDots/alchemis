{include file="header2.tpl" title="Actions"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
{literal}

//need tab_id here as used later in scripts to determine which tab has caused an action
//NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 4;

function addAction()
{
iframeLocation(	iframe1, 'index.php?cmd=ActionCreate&referrer=DashboardActions');
}

function editAction(action_id)
{
//	alert('index.php?cmd=ActionCreate&referrer=DashboardActions&action_id=' + action_id);
iframeLocation(	iframe1, 'index.php?cmd=ActionCreate&referrer=DashboardActions&action_id=' + action_id);
}

function deleteAction(action_id)
{
//	alert('index.php?cmd=ActionDelete&referrer=DashboardActions&action_id=' + action_id);
iframeLocation(	iframe1, 'index.php?cmd=ActionDelete&referrer=DashboardActions&action_id=' + action_id);
}

{/literal}
</script>

<table class="adminform" border="0" cellpadding="0" cellspacing="0"{* style="border: 1px solid blue"*}>
	<tr>
		<td width="75%" valign="top">

			<div style="margin: 0 0 10px 0">
				<input type="button" id="add_action" name="add_action" value="Add Action" href="#" onclick="javascript:addAction(); return false;" />
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
									<th style="width: 15%; text-align: center">Completed</th>
									<th style="width: 5%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=action_loop from=$actions item=action}
							{*<tr id="tr_{$action->getId()}"{if $action->isOverdue()} class="highlight_negative"{/if}>*}
							<tr id="tr_{$action->getId()}" class="odde{if $action->isOverdue()} highlight_negative{/if}">
								<td style="text-align: center">{$action->getId()}</td>
								<td>{$action->getSubject()}</td>
								<td>{$action->getDueDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td>{$action->getReminderDate()|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</td>
								<td style="text-align: center; vertical-align: middle">{if $action->isCompleted()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if} {$action->isCompleted()}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$action->getId()}" title="Edit" href="#" onclick="javascript:editAction({$action->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$action->getId()}" title="Delete" href="#" onclick="javascript:deleteAction({$action->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
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