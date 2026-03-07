{include file="header2.tpl" title="Task Summary"}

<table id="table1" class="adminlist" cellspacing="1"{*class="sortable"*}{* border="0" cellpadding="0" cellspacing="1" width="100%"*}>
	<thead>
		<tr>
			<th style="width: 1%; text-align: center">#</th>
			<th style="width: 53%; text-align: left">Task</th>
			<th style="width: 25%; text-align: left">Date</th>
			<th style="width: 10%; text-align: center">Priority</th>
			<th style="width: 10%; text-align: center">Completed</th>
			<th style="width: 1%">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6" nowrap="nowrap">
				<del class="container">
					<div class="pagination">
						<div class="button2-right off">
							<div class="start"><span>Start</span></div>
						</div>
						<div class="button2-right off">
							<div class="prev"><span>Prev</span></div>
						</div>
						<div class="button2-left">
							<div class="page"><a title="1" onclick="javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;">1</a></div>
						</div>
						<div class="button2-left">
							<div class="next"><a title="Next" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">Next</a></div>
						</div>
						<div class="button2-left">
							<div class="end"><a title="End" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">End</a></div>
						</div>
						<div class="limit">page 1 of 1</div>
						<input type="hidden" name="limitstart" value="0" />
					</div>
				</del>
			</td>
		</tr>
	</tfoot>
	<tbody>
		{foreach name=loop1 from=$tasks item=task}
		<tr>
			<td style="text-align: center">{$smarty.foreach.loop1.iteration}</td>
			<td>{$task.task}</td>
			<td>{$task.date|date_format:"%d %b %y"}</td>
			<td style="text-align: center"><img src="{$APP_URL}app/view/images/{$task.priority}.png" alt="{$task.priority}" /></td>
			<td style="text-align: center">{if $task.completed}<img src="{$APP_URL}app/view/images/tick.png" alt="Yes" />{/if}</td>
			<td>
				<div class="button2-left">
					<div class="page"><a id="editBtn_1" title="Edit" onclick="{*javascript:myController({$campaign->getId()});*}">Edit</a></div>
				</div>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="6" style="text-align: center">&mdash;</td>
		{/foreach}
	</tbody>
</table>

{include file="footer2.tpl"}