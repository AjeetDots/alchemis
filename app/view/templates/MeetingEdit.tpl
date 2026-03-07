{include file="header2.tpl" title="Edit Meeting"}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script language="JavaScript">
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
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}

{/literal}
// End hiding of script from old browsers -->
</script>
{*
{if !$allow_edit}
<div id="div_overlay" style="background-color: whitesmoke; position: absolute; top: 0; left: 0; width: 100%; height:500px;"></div>
{/if}
*}
	<strong>Edit Meeting</strong>
	
	{*<p><em>Required fields are marked with an asterisk (*).</em></p>*}
	
	<form action="index.php?cmd=MeetingEdit" method="post" name="adminForm" autocomplete="off">
	 
	 	<input type="hidden" name="post_initiative_id"  value="{$post_initiative_id}" />
		<input type="hidden" name="task" 				value="" />
		<input type="hidden" name="id" 					value="{$meeting->getId()}" />
		<input type="hidden" name="company_id" 			value="{$company_id}" />
		<input type="hidden" name="source_tab" 			value="{$source_tab}" />
		<!-- Start of Main -->
		<table class="adminlist">
			<tr>
				<th style="width: 150px">Company</th>
				<td>{$company_name}</td>
			</tr>
			<tr>
				<th style="width: 150px; vertical-align:top">Post/Contact</th>
				<td>
					{$post->getJobTitle()}
					<br />
					{$post->getContactName()}
				</td>
			</tr>
			
			<tr>
				<th>Status</th>
				<td>
					{$meeting->getStatus()}
				</td>
			</tr>
			
			<tr>
				<th style="vertical-align: top;">Date/Time</th>
				<td style="width: 70%;">
					{$meeting->getDate()|date_format:"%d/%m/%Y at %H:%M"}
				</td>
			</tr>
			<tr>
				<th>NBM Prediction</th>
				<td>
					{$meeting->getNbmPredictedRating()}
				</td>
			</tr>
			<tr>
				<th style="background-color: {#editColor1#}; width: 150px">Created On</th>
				<td style="background-color: {#editColor2#}">{$meeting->getCreatedAt()|date_format:"%A %e %B, %Y"}</td>
			</tr>
		
			<tr>
				<th style="background-color: {#editColor1#}; width: 150px">Created By</th>
				<td style="background-color: {#editColor2#}">{$meeting->getCreatedByName()}</td>
			</tr>

			{if $meeting->getStatusId() >= 24}			
			<tr>
				<th colspan="2">Meeting feedback</th>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Client feedback rating</th>
				<td>
					<select id="feedback_rating" name="feedback_rating" style="width: 40%;">
						<option value="0">-- Select --</option>
						{html_options options=$feedback_rating_options selected=$feedback_rating_selected}
					</select>
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Decision maker</th>
				<td>
					<input type="checkbox" id="feedback_decision_maker" name="feedback_decision_maker" {if $meeting->getFeedbackDecisionMaker()}checked{/if} />
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Agency User/ intention to commission</th>
				<td>
					<input type="checkbox" id="feedback_agency_user" name="feedback_agency_user" {if $meeting->getFeedbackAgencyUser()}checked{/if} />
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Budget available/ available funds to commission</th>
				<td>
					<input type="checkbox" id="feedback_budget_available" name="feedback_budget_available" {if $meeting->getFeedbackBudgetAvailable()}checked{/if} />
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Receptive to meeting</th>
				<td>
					<input type="checkbox" id="feedback_receptive" name="feedback_receptive" {if $meeting->getFeedbackReceptive()}checked{/if} />
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Agreed targeting criteria</th>
				<td>
					<input type="checkbox" id="feedback_targeting" name="feedback_targeting" {if $meeting->getFeedbackTargeting()}checked{/if} />
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Meeting length (minutes)</th>
				<td>
					<select id="feedback_meeting_length" name="feedback_meeting_length" style="width: 40%;">
						<option value="0">-- Select --</option>
						{html_options options=$feedback_meeting_length_options selected=$feedback_meeting_length_selected}
					</select>
				</td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Comments</th>
				<td>
					<textarea id="feedback_comments" name="feedback_comments" rows="5" style="width: 99%">{$meeting->getFeedbackComments()}</textarea>
				</td>
			</tr>
			
			<tr>
				<th style="vertical-align: top; width: 150px">Next steps</th>
				<td>
					<textarea id="feedback_next_steps" name="feedback_next_steps" rows="5" style="width: 99%">{$meeting->getFeedbackNextSteps()}</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: left">
					<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="button" value="Cancel" onclick="javascript:submitbutton('cancel')" />
				</td>
			</tr>
			{/if}
			
		</table>
		
	
	</form>
{*
{if !$allow_edit}
<script language="javascript">
	//alert("Here");
	new Effect.Opacity('div_overlay', {literal}{duration:0.5, from:1.0, to:0.1}{/literal});
</script>
{/if}
*}

{include file="footer2.tpl"}