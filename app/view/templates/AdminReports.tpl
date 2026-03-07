{include file="header.tpl" title="Administer Reports"}

<script language="JavaScript" type="text/javascript">
{literal}

// need tab_id here as used later in scripts to determine which tab has caused an action
// NOTE: needs to be local to this page - DON'T remove tab_id in favour of parent.tab_id. That will break things!
var tab_id = 11;

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="100%" valign="top">

			{if $last_run_date && $last_run_date.execution_time > 0}
				<p>Report data statistics were last compiled on <strong>{$last_run_date.start|date_format:$smarty.config.FORMAT_DATETIME_LONG}</strong>
				and took <strong>{$last_run_date.execution_time} seconds</strong> to process.</p>
				<p><a href="{$APP_URL}batch/batch.php" target="_blank">Compile data statistics.</a></p>
			{elseif $last_run_date}
				<p>Report data statistics are <strong>currently being compiled</strong>. The compilation process 
				started on <strong>{$last_run_date.start|date_format:$smarty.config.FORMAT_DATETIME_LONG}</strong>.</p>
			{else}
				<p>No record of the report data statistics being compiled could be found.</p>
				<p><a href="{$APP_URL}batch/batch.php" target="_blank">Compile data statistics.</a></p>
			{/if}

		</td>
	</tr>
</table>

{include file="footer.tpl"}