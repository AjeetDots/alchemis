{include file="header2.tpl" title="Email Meeting Notes"}

{if !$email_sent}

	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
	<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

	<script language="JavaScript" type="text/javascript">
	{literal}
	
	function submitbutton(pressbutton)
	{
		if (pressbutton == 'save' && validate())
		{
			if (confirm("Are you sure you wish to send this email?"))
			{
				submitform(pressbutton);
				return;
			}
		}
	}

	function submitform(pressbutton)
	{
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

	// Checks whether a string s is empty
	function isEmpty(s)
	{
		re = /^\s*$/;
		if ( s.length == 0 || s == null || re.test(s) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Checks whether a string s is a valid email address  
	function isEmail(s)
	{
		var re = /^.+@.+\..{2,3}$/;
		
		if (isEmpty(s))
		{
			return false;
		}
		else
		{
			return re.test(s);
		}
	}
	
	function validate()
	{
		// validation error variables
		var msg_error = "";
		var msg_error_count = 0;
		
		// validation warning variables
		var msg_warning = "";
		var msg_warning_count = 0;
	
		if (isEmpty($F('from_name')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". From name must be completed\n";
		}

		if (isEmpty($F('from_email')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". From email address must be completed\n";
		}
		else if (!isEmail($F('from_email')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". From email address must be a valid e-mail address.\n";
		}

		if (isEmpty($F('to_name')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". To name must be completed\n";
		}

		if (isEmpty($F('to_email')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". To email address must be completed\n";
		}
		else if (!isEmail($F('to_email')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". To email address must be a valid e-mail address.\n";
		}

		if (isEmpty($F('subject')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Subject must be completed\n";
		}
		
		if (isEmpty($F('body')))
		{
			msg_error_count ++;
			msg_error += msg_error_count + ". Body must be completed\n";
		}

		if (msg_error != "")
		{
			alert("Please complete or correct the following items:\n\n" + msg_error);
			return false;
		}
		else
		{
			if (msg_warning != "")
			{
				if (confirm("Please check the following suggestions:\n\n" + msg_warning + "\n\If you still wish to log this communication click 'OK', otherwise click 'Cancel' and you will be able to amend the communication details."))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return true;
			}
		}
	}

	{/literal}
	</script>

{/if}

<style type="text/css">
{literal}
	#container
	{
		background-color: #FFF;
		border: 1px solid #E7E7E7;
		margin-top: 20px;
		padding: 10px;
	}
	
	#container p,
	#container td,
	#container th
	{
		font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	
	#container h1
	{
		color: #000;
		font-size: 16px;
		font-weight: bold;
		text-align: left;
/*		text-decoration: underline;*/
	}

	#container h2
	{
		color: #000;
		font-size: 14px;
		font-weight: bold;
		margin-top: 25px;
		text-align: center;
	}
	
	#container h3
	{
		color: #000;
		font-size: 14px;
		font-weight: bold;
		text-align: center;
		text-decoration: underline;
	}

	#container,
	#container #footer
	{
		font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 9pt;
	}

	#container .footer
	{
		margin-top: 25px;
	}
	
	#container #copyright
	{
		border-top: 1px solid black;
		margin-top: 10px;
		padding: 3px;
	}

	#container table
	{
/*		border: 1px solid black;*/
		margin: 0px 0;
		width: 100%;
	}

	#container td,
	#container th
	{
		padding: 1px;
		vertical-align: top;
	}
	
	#container th
	{
		text-align: left;
		font-style: normal;
		font-weight: bold;
		color: black;
	}

	#container th.header
	{
		font-weight: bold;
		font-size: 14px;
		text-decoration: underline;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	
{/literal}
</style>


<h2>Email Meeting Notes</h2>
	
{if !$email_sent}

	{*<p><em>Required fields are marked with an asterisk (*).</em></p>*}
	
	<form action="index.php?cmd=MeetingEmail" method="post" name="adminForm" autocomplete="off">
	 
	 	<input type="hidden" name="post_initiative_id"  value="{$post_initiative_id}" />
		<input type="hidden" name="task" 				value="" />
		<input type="hidden" name="id" 					value="{$meeting->getId()}" />
		<input type="hidden" name="company_id" 			value="{$company_id}" />
		<input type="hidden" name="source_tab" 			value="{$source_tab}" />

		<table class="adminlist">
		<tr>
				<th style="vertical-align: top; width: 150px">From Name</th>
				<td><input type="text" class="text" id="from_name" name="from_name" value="{$nbm_name}" maxlength="255" size="50" /></td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">From Email Address</th>
				<td><input type="text" class="text" id="from_email" name="from_email" value="{$nbm_email}" maxlength="255" size="50" /></td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">To Name</th>
				<td><input type="text" class="text" id="to_name" name="to_name" value="{$client->getPrimaryContactName()}" maxlength="255" size="50" /></td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">To Email Address</th>
				<td><input type="text" class="text" id="to_email" name="to_email" value="{$client->getPrimaryContactEmail()}" maxlength="255" size="50" /></td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Subject</th>
				<td><input type="text" class="text" id="subject" name="subject" value="New meeting set with {$company->getName()}" maxlength="255" size="50" /></td>
			</tr>
			<tr>
				<th style="vertical-align: top; width: 150px">Body Comments</th>
				<td>
					<textarea class="text" id="body" name="body" rows="10" style="width: 99%">Please see following section for more details.</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: left">
					<input type="button" value="Submit" onclick="javascript:submitbutton('save');" /> | <input type="button" value="Cancel" onclick="javascript:submitbutton('cancel');" />
				</td>
			</tr>
		</table>
	</form>

{else}

<p><a href="javascript:window.close();">Close Window</a></p>
	<table class="adminlist">
		<tr>
			<th style="vertical-align: top; width: 150px">From</th>
			<td>{$from_name} &lt;{$from_email}&gt;</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 150px">To</th>
			<td>{$to_name} &lt;{$to_email}&gt;</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 150px">Date</th>
			<td>{$smarty.now|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 150px">Subject</th>
			<td>{$subject}</td>
		</tr>
	</table>

{/if}

	<div id="container">
		
		{if $email_sent}
			<div style="border-bottom: 1px solid #000; font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 9pt; margin-bottom: 20px; padding-bottom: 20px">
				{$body|nl2br}
			</div>
		{/if}
		
		<h2 style="text-align: left">Meeting notes for:</h2>
		<h1>{$company->getName()} on {$meeting->getDate()|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}</h1>
		
		<!-- Start of Company detail -->
		<h2>Company Details</h2>
		<table>
			<tr>
				<th width="150px">Company:</th>
				<td>{$company->getName()}</td>
			</tr>

			<tr>
				<th>Address:</th>
				<td>{$company->getSiteAddress(null, 'paragraph')}</td>
			</tr>
			<tr>
				<th>Telephone:</th>
				<td>{$company->getTelephone()}</td>
			</tr>
			<tr>
				<th>Website:</th>
				<td>{$company->getWebsite()}</td>
			</tr>
			<tr>
				<th>Owner:</th>
				<td>Not assigned</td>
			</tr>
		</table>
		<!-- End of Company detail -->

		<!-- Meeting Details -->
		<h2>Meeting Details</h2>
		<table>
			<tr>
				<th width="150px">Contact:</th>
				<td>{$post->getContactName()} {if $post->getJobTitle() && $post->getContactName()}&nbsp;&nbsp;({$post->getJobTitle()}){else}{$post->getJobTitle()}{/if}</td>
			</tr>
			<tr>
				<th>Date:</th>
				<td>{$meeting->getDate()|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}</td>
			</tr>
			<tr>
				<th>Email:</th>
				<td>{if $contact}{$contact->getEmail()}{/if}</td>
			</tr>
			<tr>
				<th>Initiative:</th>
				<td>{$client->getName()}: {$initiative->getName()}</td>
			</tr>
			<tr>
				<th>Status:</th>
				<td>{$meeting->getStatus()}</td>
			</tr>
			<tr>
				<th>Type:</th>
				<td>{$meeting->getType()}</td>
			</tr>
		</table>
		<!-- /Meeting Details -->


		<!-- Confirmation Process -->
		<h2>Confirmation Process</h2>
		<table>
			{foreach name=actions_loop from=$actions item=action}
			<tr>
				<th width="150px" colspan="2" style="text-align:center">Action {$smarty.foreach.actions_loop.iteration}</th>
			</tr>
			<tr>
				<th>Due by:</th>
				<td><strong>{$action->getDueDate()|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}</strong></td>
			</tr>
			<tr>
				<th width="150px">Summary:</th>
				<td>{$action->getSubject()}</td>
			</tr>
			<tr>
				<th>To be confirmed by:</th>
				<td>{if $action->getActionedByClient()}{$client->getName()}{else}Alchemis{/if}</td>
			</tr>
			<tr>
				<th>How confirmed:</th>
				<td>{$action->getCommunicationType()}</td>
			</tr>
			<tr>
				<th>Resources to send:</th>
				<td>
					{foreach name=resources_loop from=$action->getResources() item=resource}
						&rArr;&nbsp;{$resource.resource}<br />
					{/foreach}
				</td>
			</tr>
			<tr>
				<th>Action detail:</th>
				<td>{$action->getNotes()}</td>
			</tr>
			<tr>
				<th colspan="2"><br /></th>
			</tr>
			{/foreach}
		</table>
		<!-- /Confirmation Process -->


		<!-- Agency User Information -->
		<h2>Agency User Information</h2>
		{if $discipline_note == ''}
			<p style="text-align: center"><em>&mdash; No agency user information found &mdash;</em></p>
		{else}
			<p>{$discipline_note}</p>
		{/if}
		<!-- /Agency User Information -->


		<!-- Company Characteristics -->
		<h2>Company Characteristics</h2>
		{if $characteristics|@count == 0}
			
			<p style="text-align: center"><em>&mdash; No company characteristics found &mdash;</em></p>

		{else}

			<table style="width: 100%" class="default">
				<tbody>

			{foreach name=char_loop from=$characteristics item=characteristic}
				
					<tr>
						<td colspan="2"><strong>{$characteristic.name}</strong></td>
					</tr>
					{if $characteristic.attributes && !$characteristic.options}

						{foreach name=element_loop from=$characteristic.elements item=element}
						<tr style="height: 20px">
							<td>{$element.name}</td>
							<td>
								{if $element.data_type == 'boolean'}
									{if $element.value == 0}
										<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
									{else}
										<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
									{/if}
								{elseif $element.data_type == 'date'}
									{$element.value|date_format:"%d %B %Y"}
								{elseif $element.data_type == 'text'}
									{$element.value}
								{/if}
							</td>
						</tr>
						{/foreach}	

					{elseif $characteristic.attributes && $characteristic.options}

						{if $characteristic.multiple_select}
							{foreach name=element_loop from=$characteristic.elements item=element}
							<tr style="height: 20px">
								<td>{$element.name}</td>
								<td>
									{if $element.data_type == 'boolean'}
										{if $element.value == "0" || $element.value == ""}
											<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
										{elseif $element.value == "1"}
											<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
										{/if}
									{elseif $element.data_type == 'date'}
										{$element.value|date_format:"%d %B %Y"}
									{elseif $element.data_type == 'text'}
										{$element.value}
									{/if}
								</td>
							</tr>
							{/foreach}	
						{else}
							{foreach name=element_loop from=$characteristic.elements item=element}
							<tr style="height: 20px">
								<td>{$element.name}</td>
								<td>
									{if $element.data_type == 'boolean'}
										{if $element.value == "0"}
											<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
										{elseif $element.value == "1"}
											<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
										{/if}
									{elseif $element.data_type == 'date'}
										{$element.value|date_format:"%d %B %Y"}
									{elseif $element.data_type == 'text'}
										{$element.value}
									{/if}
								</td>
							</tr>
							{/foreach}	
						{/if}

					{elseif !$characteristic.attributes || $characteristic.options}

						<tr style="height: 15px">
							<td colspan="2">
							{if $characteristic.data_type == 'boolean'}
								{if $characteristic.value == 0}
									<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
								{else}
									<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
								{/if}
							{elseif $characteristic.data_type == 'date'}
								{$characteristic.value|date_format:"%d %B %Y"}
							{elseif $characteristic.data_type == 'text'}
								{$characteristic.value}
							{/if}
							</td>
						</tr>

					{/if}
					{if !$smarty.foreach.char_loop.last}
					<tr>
						<td colspan="2">
							<hr style="border: 0px solid #ccc; border-top-width: 1px; height: 0px" />
						</td>
					</tr>
					{/if}

				{/foreach}

				</tbody>
			</table>
		{/if}
		<!-- /Company Characteristics -->


		<!-- Meeting Notes -->
		<h2>Meeting Notes</h2>
		{if $notes|@count == 0}

			<p style="text-align: center"><em>&mdash; No meeting notes found &mdash;</em></p>

		{else}

			<table style="width: 100%" class="default">
				<tbody>
					{foreach name="note_loop" from=$notes item=result}
						<tr{*if $result.effective == "effective"} class="current"{/if*}>
							<td>
								<strong>{$result.communication_date|date_format:"%d/%m/%y %H:%M"} : {$result.user_client_alias}</strong>
							</td>
						</tr>
						<tr>
							<td style="color: #0B55C4">
								{$result.status}{if $result.old_status} ({$result.old_status}){/if}
							</td>
						</tr>
						<tr>
							<td>
								Actions: 
								{if $result.meeting_id != ""}
								<img src="{$APP_URL}app/view/images/icons/date.png" style="vertical-align: middle" title="Meeting" />
								{/if}
								{if $result.information_request_id != ""}
								<img src="{$APP_URL}app/view/images/icons/script.png" style="vertical-align: middle" title="Information Request" />
								{/if}
								{if $result.decision_maker_type_id == 1}
								<img src="{$APP_URL}app/view/images/icons/key.png" style="vertical-align: middle" title="Decision maker contacted" />
								{/if}
								{if $result.next_communication_date != ""}
								<img src="{$APP_URL}app/view/images/icons/calendar_view_day.png" style="vertical-align: middle" title="Call back scheduled" />
								{/if}
							</td>
						</tr>
						{if $result.effective == "effective"}
							<tr>
								<td><strong>{$result.effective|capitalize}</strong></td>
							</tr>
						{/if}
						{if $result.comments != ""}
							<tr>
								<td>Comment: <em>{$result.comments}</em></td>
							</tr>
						{/if}
						{if $result.note != ""}
							<tr>
								<td>{$result.note|nl2br}</td>
							</tr>
						{/if}
						{if !$smarty.foreach.note_loop.last}
							<tr>
								<td>
									<hr style="border: 0px solid #ccc; border-top-width: 1px; height: 0px" />
								</td>
							</tr>
						{/if}
					{/foreach}
				</tbody>
			</table>

		{/if}
		<!-- /Meeting Notes -->


		<!-- Meeting History -->
		<h2>Meeting History</h2>

		{if $meeting_history|@count == 0}

			<p style="text-align: center"><em>&mdash; No meeting history found &mdash;</em></p>

		{/if}
		<!-- /Meeting History -->

	</div>
	<!-- /container -->

{include file="footer2.tpl"}