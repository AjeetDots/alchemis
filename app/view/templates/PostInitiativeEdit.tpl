{include file="header2.tpl" title="Edit Post Initiative"}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css"> 

<script type="text/javascript">

{literal}


function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.adminForm.task.value=pressbutton;
	
	try 
	{
		document.adminForm.onsubmit();
	}
	
	catch(e)
	{}
	
	document.adminForm.submit();
}


function submitbutton(pressbutton)
{
//	alert('submitbutton(' + pressbutton + ')');
	
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}

//--- Generic calendar support functions ---
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
function handleSelect_recall(type,args,obj) 
{ 
    var dates = args[0];  
    var date = dates[0]; 
	//convert incoming params to string type so we can pad the day and month values later on in the function
    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 

    var txtDate1 = $("next_communication_date"); 
    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
	Effect.toggle($("recall_calendar_display"), 'blind', {duration: 0.3});
     
   $("row_next_communication_date_reason_id").style.visibility = "visible";
} 
 
// Handles input to the calendar from recall_date text field when the ... button is clicked
function updateRecallCal() 
{ 
	Effect.toggle($("recall_calendar_display"), 'blind', {duration: 0.3});
    var txtDate1 = $("next_communication_date"); 
 	//alert(txtDate1.value);
 	if (txtDate1.value != "")
 	{
 		// Select the date typed in the field 
    	YAHOO.example.calendar.cal_recall.select(txtDate1.value);  
 	}
 	       
   YAHOO.example.calendar.cal_recall.render(); 
} 

function init() 
{ 
	YAHOO.example.calendar.cal_recall = new YAHOO.widget.Calendar("cal_recall","div_cal_recall"); 
	    
	YAHOO.example.calendar.cal_recall.cfg.setProperty("START_WEEKDAY", 1); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_MONTH_POSITION", 2); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MDY_YEAR_POSITION", 3); 
	 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MD_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal_recall.cfg.setProperty("MD_MONTH_POSITION", 2); 
	
	YAHOO.example.calendar.cal_recall.selectEvent.subscribe(handleSelect_recall, YAHOO.example.calendar.cal_recall, true); 
	YAHOO.example.calendar.cal_recall.render(); 
}

YAHOO.namespace("example.calendar"); 
YAHOO.util.Event.addListener(window, "load", init); 

{/literal}

{if $success}
	var source_frame = "iframe_{$parent_tab}";
	var s = top.window[source_frame].contentWindow.loadPost({$post_initiative->getPostId()}, {$post_initiative->getInitiativeId()}, {$id});
	top.$("span_callback_count").innerHTML = "Today's Callbacks: " + {$scoreboard->getCallBackCount()} + "(" + {$scoreboard->getPriorityCallBackCount()} + ")";
	top.refreshCallBackPopup();
{/if}
</script>

<form action="index.php?cmd=PostInitiativeEdit" method="post" name="adminForm" autocomplete="off">

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="{$id}" />
	<input type="hidden" name="parent_tab" value="{$parent_tab}" />
	<p><h2>Edit post initiative</h2></p>
	<fieldset class="adminform">
		<legend>Status</legend>
		<table>
			<tr>
				{if $status_options}
				<td>
					<select style="width: 100%" id="status_id" name="status_id">
						{html_options options=$status_options selected=$status_id}
					</select>
				</td>
				{else}
				<td>
					Meetings exist for this post initiative. <br /><br />Status can only be changed from inside the communication screen.
				</td>
				{/if}

			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend>Comment</legend>
		<table>
			<tr>
				<textarea rows="3" id="comment" name="comment" style="width: 99%">{$post_initiative->getComment()}</textarea>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend>Next communication date</legend>
		<table>
			<tr>
				<input type="text" id="next_communication_date" name="next_communication_date" value="{$post_initiative->getNextCommunicationDate()|date_format:"%d/%m/%Y"}"/>
				<input type="button" value="..." onclick="javascript:updateRecallCal();" />
				<a href="#" onclick="javascript:clearDate('next_communication_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('recall_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
				<div id="recall_calendar_display" style="display: none">
					<div id="div_cal_recall"></div> 
				</div>
				<br />
				<br />at 
				{html_select_time 
							prefix          = "next_communication_time_"
							time            = $post_initiative->getNextCommunicationDate()|date_format:"%H:%M"
							use_24_hours    = true
							display_seconds = false
							minute_interval = 5}
				
				Priority?&nbsp;<input type="checkbox" name="priority_callback" id="priority_callback" {if $post_initiative->getPriorityCallBack()} checked {/if}/>
			</tr>
		</table>
	</fieldset>
	<p></p>
	{if $status_options}
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
	{/if}
	
	
</form>

{include file="footer2.tpl"}