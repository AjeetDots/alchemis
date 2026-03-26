{include file="header2.tpl" title="Mailer Create"}

<script type="text/javascript">
{literal}

function submitform(pressbutton)
{
	var doc = iframe1.document || iframe1.contentWindow.document;
	var frm = doc.mailer_results || doc.frm_results || doc.form_results;
	if (!frm)
	{
		alert("Mailer results are not loaded yet. Please build/reload the selected filter first.");
		return false;
	}

	// Ensure a hidden 'task' field exists.
	var taskEl = frm.elements['task'];
	if (!taskEl)
	{
		taskEl = document.createElement('input');
		taskEl.type = 'hidden';
		taskEl.name = 'task';
		frm.appendChild(taskEl);
	}
	taskEl.value = pressbutton;
	
	try
	{
		if (frm.onsubmit)
		{
			frm.onsubmit();
		}
	}
	catch(e)
	{}
	
	frm.submit();
}

function submitbutton(pressbutton)
{
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
		return;
	}
}

function loadFilter(action)
{
	var filter_id = $F("filter_id");
	
iframeLocation(	iframe1, "index.php?cmd=FilterResults&id=" + filter_id + "&action=" + action);
	
}

function addPosts()
{
	var doc = iframe1.document || iframe1.contentWindow.document;
	var frm = doc.mailer_results || doc.frm_results || doc.form_results;
	if (!frm)
	{
		alert("Mailer results are not loaded yet. Please build/reload the selected filter first.");
		return false;
	}

	function setHiddenField(frmRef, name, value)
	{
		var el = frmRef.elements[name];
		if (!el)
		{
			el = document.createElement('input');
			el.type = 'hidden';
			el.name = name;
			frmRef.appendChild(el);
		}
		el.value = value;
	}

	frm.action = "index.php?cmd=MailerItemCreate";
	{/literal}
	setHiddenField(frm, 'mailer_id', {$mailer_id});
	setHiddenField(frm, 'initiative_id', {$initiative_id});
	{literal}
	submitform('save');
}

{/literal}
</script>

{if $feedback == "Mailer added successfully"}
	<p>
		<a href="index.php?cmd=MailerItemCreate&mailer_id={$mailer->getId()}&initiative_id={$mailer->getClientInitiativeId()}">
			Add new recipients to this mailer
		</a>
	</p>
	
	<p><a href="index.php?cmd=MailerList" target="_parent">Refresh the mailer list</a></p>
	
{else}
	<div id="div_filter_list">	
		<span style="float: left">
		<table class="adminlist" style="width:500px">
			<tr>	
				<th>Available filters</th>
				<td>
					<select style="width: 100%" id="filter_id" name="filter_id">
						<option value="0">&ndash; Select &ndash;</option>
						{html_options options=$filters}
					</select>
				</td>
				<td>
					{*<a id="btn_display_filter" title="Display current results" href="#" onclick="javascript:loadFilter('reload');return false;"><img src="{$APP_URL}app/view/images/icons/table_go.png" alt="Display" title="Display currently saved results" /></a>&nbsp;*}
					<a id="btn_refresh_filter" title="Rebuild filter using saved parameters" href="#" onclick="javascript:loadFilter('build');return false;"><img src="{$APP_URL}app/view/images/icons/table_refresh.png" alt="Re-generate" title="Re-generate filter from database and display results" /></a>&nbsp;
					<a id="btn_filter_list_refresh" title="Refresh list of available filters" href="#" onclick="javascript:window.location.href=window.location.href;"><img src="{$APP_URL}app/view/images/refresh.png" alt="Display" title="Refresh list of available filters" /></a>&nbsp;
				</td>
			</tr>
		</table>
		</span>
		<span style="float: right">
			<input type="button" value="Add selected posts to mailer" id="btn_add" onclick="javascript:addPosts();return false;" />
		<span>
	</div>
	<div class="module_content" style="height: 690px; width: 100%;">
		<iframe id="iframe1" name="iframe1" src="" scrolling="yes" border="0" frameborder="no" style="height: 99%; width: 99%; overflow-x: hidden; overflow-y: auto"></iframe>
	</div>
	
{/if}
{include file="footer2.tpl"}