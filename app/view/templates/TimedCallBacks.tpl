{include file="header_js_popup.tpl" title="Timed Call Backs"}

<link href="{$APP_URL}app/view/styles/dashboard.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar.css" rel="stylesheet" type="text/css" />
<link href="{$APP_URL}app/view/styles/calendar_day.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script language="JavaScript" type="text/javascript">
{literal}
	
	var iframe5 = opener.top.frames['iframe_5'];
	if(iframe5.constructor.name !== 'Window'){
		iframe5 = iframe5.contentWindow;
	}
	
	function showPost(company_id, post_id, initiative_id)
	{
		// set page_isloaded to true so we can check in header_js.loadTab whether we need to higlight/navigate to any lines in the results set.
		// This need only occurs when we navigate back to the results set from the filter workspace.
		// On page creation this variable is set to false - so we don't need to highlight/navigate to anything the first time this page is loaded
		page_isloaded = true;
		iframe5.location.href = "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id;
		opener.top.loadTab(5,"");
		self.close();
	}

	function submitform(pressbutton)
	{
		document.frm_priority_callbacks.task.value = pressbutton;
		
		try
		{
			document.frm_priority_callbacks.onsubmit();
		}
		catch(e)
		{}
		
		document.frm_priority_callbacks.submit();
	}

	function submitbutton(pressbutton)
	{
		if (pressbutton == 'save')
		{
			submitform(pressbutton);
			return;
		}
	}

	{/literal}

{if $success}
//alert(' in success');
opener.top.$("span_callback_count").innerHTML = "Today's Callbacks: " + {$scoreboard->getCallBackCount()} + "(" + {$scoreboard->getPriorityCallBackCount()} + ")";
{/if}

</script>

<div class="panel">
<form id="frm_priority_callbacks" name="frm_priority_callbacks" action="index.php?cmd=TimedCallBacks" method="post">
	<input type="hidden" name="task" value="" />
	<h3><span>Call Backs</span></h3>
	<div>
	
		<p style="margin-left: 10px">You have <strong>{$call_back_count}</strong> call back{if $call_back_count != 1}s{/if} due today</p>
		
		{if $call_backs}
		<table id="table1" class="adminlist sortable" id="sortable_{$result.id}"cellspacing="1">
			<thead>
				<tr class="sortable" id="sortable_{$result.id}">
					<th style="width: 1%; text-align: center">#</th>
					<th style="text-align: left">Company</th>
					<th style="text-align: left">Job Title</th>
					<th style="text-align: left">Post Holder</th>
					<th style="text-align: left">Date &amp; Time</th>
					<th style="text-align: left">Priority</th>
					<th>Client &amp Last Effective</th>
					<th>Dismiss</th>
					<th style="width: 5%"></th>
				</tr>
			</thead>
			<tbody>
				{assign var=timed_call_back_count value=$call_backs|@count}
				{foreach name="timed_call_back_loop" from=$call_backs item=result}
					<tr id="tr_post_{$result.post_id}" style="vertical-align:top">
						<td>{$smarty.foreach.timed_call_back_loop.iteration}</td>
						<td>
							<span id="client_{$result.id}"><strong>{$result.company_name}</strong></span>
							<br />
							{$result.company_telephone}
							<br />
							{assign var="website" value=$result.website}
							{if $website != ""}
								<a href="{$website}" target="_new">{$website}</a>
							{/if}
						</td>
						<td><strong>{$result.job_title}</strong>
							<br />
							<img id="img_propensity" src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align: middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}" />
							<br />
							{$result.telephone_1}
						</td>
						<td><strong>{$result.full_name}</strong></td>
						</td>
						<td>
							<strong>{$result.next_communication_date|date_format:$smarty.config.FORMAT_TIME_SHORT}</strong>
						</td>
						<td style="text-align:center;">
							{if $result.priority_callback}
							<img src="{$APP_URL}app/view/images/tick.png" style="" alt="Priority Callback" title="Priority Callback" />
							{/if}
						</td>
			 			<td>
			 				<strong>{$result.client_name}</strong>
			 				<br />
							{$result.status}
							<br />
			 				<em>{$result.last_effective_communication_date|date_format:$smarty.config.FORMAT_DATETIME_SHORT}</em>
			 			</td>
			 			<td>
			 			{if $result.priority_callback}
			 				Postpone until
			 				<br />
			 				<select name="select_post_initiative_id_{$result.post_initiative_id}" id="select_post_initiative_id_{$result.post_initiative_id}">
			 					<option value="0">-- select --</option>
			 					<option value="10mins">10 mins</option>
			 					<option value="2hours">2 hours</option>
			 					<option value="4hours">4 hours</option>
			 					<option value="next_working_day">Next working day</option>
			 				</select>
			 				<br /> or dismiss
			 				<input type="checkbox" name="chk_post_initiative_id_{$result.post_initiative_id}" id="chk_post_initiative_id_{$result.post_initiative_id}" />
			 				{/if}	
			 			</td>
						<td style="text-align: center; background-color: #F3F3F3">
							<a id="detailsBtn_{$result.id}" title="Edit" href="#" onclick="javascript:showPost({$result.company_id}, {$result.post_id}, {$result.initiative_id});return false;"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>
						</td>
					</tr>
				{/foreach}
				<tr>
					<td colspan="9">
						<span style="float: right">
							<input type="button" value="Save"  onclick="javascript:submitbutton('save')"  />
						</span>
					</td>
				</tr>
				
			</tbody>
		</table>
		{/if}
		
	</div>
</form>
</div>

<script type="text/javascript">init_moofx();</script>

{include file="footer.tpl"}
