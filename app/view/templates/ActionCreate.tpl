{include file="header2.tpl" title="Action Create"}

{if $success}

	<p>The action has been created.</p>
{*	<input type="button" id="btn_add_action" value="Add another action" onclick="javascript:document.location.href='index.php?cmd=ActionCreate&amp;date={$app_domain_Action_due_date}'" />*}

	{if $client_id}
		{* Redirect to client calendar *}
		<p>Redirect to 'index.php?cmd={$referrer}&client_id={$client_id}&date={$app_domain_Action_due_date}'</p>
	{elseif $nbm_id}
		{* Redirect to NBM calendar *}
		<p>Redirect to 'index.php?cmd={$referrer}&nbm_id={$nbm_id}&date={$app_domain_Action_due_date}'</p>
	{else}
		{* Redirect to global calendar *}
		<p>Redirect to 'index.php?cmd={$referrer}&date={$app_domain_Action_due_date}'</p>
	{/if}

	<script language="JavaScript" type="text/javascript">

		{if $client_id}
			{* Redirect to client calendar *}
			parent.location = 'index.php?cmd={$referrer}&client_id={$client_id}&date={$app_domain_Action_due_date}';
		{elseif $nbm_id}
			{* Redirect to NBM calendar *}
			parent.location = 'index.php?cmd={$referrer}&nbm_id={$nbm_id}&date={$app_domain_Action_due_date}';
		{else}
			{* Redirect to global calendar *}
			parent.location = 'index.php?cmd={$referrer}&date={$app_domain_Action_due_date}';
		{/if}

	</script>

{else}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script>

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script>
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">

<!--<script type="text/javascript" src="{$APP_URL}app/view/js/date/date.js"></script> -->

<script language="JavaScript" type="text/javascript">
{literal}
function clearDate(obj)
{
	$(obj).value = null;
}


// function below pads a string
// Usage: 	l = length to pad to
//			s = string to pad with
//			t = where padding occurs (0 = in front, 1 = behind, 2 = both)
String.prototype.pad = function(l, s, t)
{
	return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
		+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
		+ this + s.substr(0, l - t) : this;
};

//--- Recall calendar functions ---
// Handles input from recall calendar - updates recall_date text field

function handleSelect(input, type, args, obj)
{
	var dates = args[0];
	var date = dates[0];

	// Convert incoming params to string type so we can pad the day and month values later on in the function
	var year = date[0].toString(), month = date[1].toString(), day = date[2].toString();

	var txtDate1 = $(input);
	txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year;
	Effect.toggle($(input + '_calendar_display'), 'blind', {duration: 0.3});
}

function handleSelectDueDate(type, args, obj)
{
	handleSelect('app_domain_Action_due_date', type, args, obj);
}

function handleSelectReminderDate(type, args, obj)
{
	handleSelect('app_domain_Action_reminder_date', type, args, obj);
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateDueDate()
{
	Effect.toggle($('app_domain_Action_due_date_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('app_domain_Action_due_date');

	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_app_domain_Action_due_date.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_app_domain_Action_due_date.render();
}

// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateReminderDate()
{
	Effect.toggle($('app_domain_Action_reminder_date_calendar_display'), 'blind', {duration: 0.3});
	var txtDate1 = $('app_domain_Action_reminder_date');

	if (txtDate1.value != '')
	{
		// Select the date typed in the field
		YAHOO.example.calendar.cal_app_domain_Action_reminder_date.select(txtDate1.value);
	}
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.render();
}

function init()
{
	// Due date
	YAHOO.example.calendar.cal_app_domain_Action_due_date = new YAHOO.widget.Calendar("cal_app_domain_Action_due_date", "div_cal_app_domain_Action_due_date");
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.selectEvent.subscribe(handleSelectDueDate, YAHOO.example.calendar.cal_app_domain_Action_due_date, true);
	YAHOO.example.calendar.cal_app_domain_Action_due_date.render();

	// Reminder date
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date = new YAHOO.widget.Calendar("cal_app_domain_Action_reminder_date", "div_cal_app_domain_Action_reminder_date");
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("START_WEEKDAY", 1);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("MDY_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("MDY_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("MDY_YEAR_POSITION", 3);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("MD_DAY_POSITION", 1);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.cfg.setProperty("MD_MONTH_POSITION", 2);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.selectEvent.subscribe(handleSelectReminderDate, YAHOO.example.calendar.cal_app_domain_Action_reminder_date, true);
	YAHOO.example.calendar.cal_app_domain_Action_reminder_date.render();
}

YAHOO.namespace("example.calendar");
YAHOO.util.Event.addListener(window, "load", init);


// The following variable used in validation. It will be set to true if we have a current meeting.
var has_current_meeting = false;

function submitbutton(pressbutton)
{

	if (pressbutton == 'save')
	{
		submitform(pressbutton);
	}
	else if (pressbutton == 'reset')
	{
		document.adminForm.reset();
	}
}

function submitform(pressbutton)
{
	document.adminForm.task.value = pressbutton;
	try
	{
		document.adminForm.onsubmit();
	}
	catch(e)
	{}
	document.adminForm.submit();
}

{/literal}
</script>

	<form action="index.php?cmd=ActionCreate" method="post" name="adminForm" autocomplete="off">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="referrer" value="{$referrer}" />
		<input type="hidden" name="client_id" value="{$client_id}" />
		<input type="hidden" name="nbm_id" value="{$nbm_id}" />
		<input type="hidden" name="app_domain_Action_id" value="{$app_domain_Action_id}" />

		<fieldset class="adminform">
			<legend>Action</legend>
			<table>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Action_subject} class="key_error" title="{$errors.app_domain_Action_subject->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Action_subject">Subject *</label>
					</td>
					<td><input type="text" name="app_domain_Action_subject" id="app_domain_Action_subject" style="width: 200px" value="{$app_domain_Action_subject}" maxlength="100" /></td>
				</tr>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Action_notes} class="key_error" title="{$errors.app_domain_Action_notes->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Action_notes">Notes</label>
					</td>
					<td><textarea name="app_domain_Action_notes" id="app_domain_Action_notes" rows="5" style="width: 200px">{$app_domain_Action_notes}</textarea></td>
				</tr>
				<tr>
					<td style="width: 110px" rowspan="2" {if $errors.app_domain_Action_due_date} class="key_error" title="{$errors.app_domain_Action_due_date->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Action_due_date">Due Date *</label>
					</td>
					<td>
						<input type="text" id="app_domain_Action_due_date" name="app_domain_Action_due_date" value="{$app_domain_Action_due_date|date_format:"%d/%m/%Y"}" />
						<input type="button" value="..." onclick="javascript:updateDueDate();" />
						<a href="#" onclick="javascript:clearDate('app_domain_Action_due_date');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('app_domain_Action_due_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						<div id="app_domain_Action_due_date_calendar_display" style="display: none">
							<div id="div_cal_app_domain_Action_due_date"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						{if $app_domain_Action_due_date}
							{assign var=due_date_time value=$app_domain_Action_due_date|date_format:'%H:%M'}
						{else}
							{assign var=due_date_time value="00:00"}
						{/if}
						{html_select_time
							prefix          = "due_date_time_"
							time            = $due_date_time
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5}
					</td>
				</tr>
				<tr>
					<td style="width: 110px" {if $errors.app_domain_Action_reminder} class="key_error" title="{$errors.app_domain_Action_reminder->getTip()}"{else}class="key"{/if}>
						<label for="app_domain_Action_reminder">Set Reminder?</label>

					</td>
					<td>
						<input type="checkbox" id="chk_display_reminder" name="chk_display_reminder"{if $chk_display_reminder} checked="checked"{/if} onchange="javascript: new Effect.toggle($('div_display_reminder'), 'blind', {literal}{duration: 0.3}{/literal});return false;" />
					</td>
				</tr>
			</table>

			<div id="div_display_reminder" style="display: {if $app_domain_Action_reminder_date}block{else}none{/if}">
				<table>
					<tr>
						<td style="width: 110px" rowspan="2" {if $errors.app_domain_Action_reminder_date} class="key_error" title="{$errors.app_domain_Action_reminder_date->getTip()}"{else}class="key"{/if}>
							<label for="app_domain_Action_reminder_date">Reminder Date *</label>
						</td>
						<td>
							<input type="text" id="app_domain_Action_reminder_date" name="app_domain_Action_reminder_date" value="{$app_domain_Action_reminder_date|date_format:"%d/%m/%Y"}" />
							<input type="button" value="..." onclick="javascript:updateReminderDate();" />
							<a href="#" onclick="javascript:clearDate('app_domain_Action_reminder_date');">[clear]</a> <a href="#" onclick="javascript:new Effect.BlindUp($('app_domain_Action_reminder_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
							<div id="app_domain_Action_reminder_date_calendar_display" style="display: none">
								<div id="div_cal_app_domain_Action_reminder_date"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							{if $app_domain_Action_reminder_date}
								{assign var=reminder_date_time value=$app_domain_Action_reminder_date|date_format:'%H:%M'}
							{else}
								{assign var=reminder_date_time value="00:00"}
							{/if}
							{html_select_time
								prefix          = "reminder_date_time_"
								time            = $reminder_date_time
								use_24_hours    = true
								display_seconds = false
								minute_interval = 5}
						</td>
					</tr>
				</table>
			</div>

			<table>
				<tr>
					<td style="width: 110px" class="key">
						<label for="app_domain_Action_is_completed">Completed?</label>
					</td>
					<td>
						<input type="checkbox" id="app_domain_Action_is_completed" name="app_domain_Action_is_completed"{if $app_domain_Action_is_completed} checked="checked"{/if} />
					</td>
				</tr>
			</table>

		</fieldset>

		<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /> | <input type="reset" value="Reset" /></p>

	</form>

{/if}

{include file="footer2.tpl"}