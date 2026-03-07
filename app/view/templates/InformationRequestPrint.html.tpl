{config_load file="`$LOCALE`.conf"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Information Request Details</title>
		<style type="text/css">
		{literal}
			body, p, td, th
			{
				font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			
			h1
			{
				font-size: 16px;
				font-weight: bold;
				text-align: left;
/*				text-decoration: underline;*/
			}

			h2
			{
/*				color: #0B55C4;
				font-size: 12px;
				font-weight: bold;
				text-align: center;
*/
				font-size: 14px;
				font-weight: bold;
				margin-top: 25px;
				text-align: center;
/*				text-decoration: underline;*/
			}
			
			h3
			{
				font-size: 14px;
				font-weight: bold;
				text-align: center;
				text-decoration: underline;
			}

			#container, #footer
			{
				font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 9px;
				width: 100%;
			}

			.footer
			{
				margin-top: 25px;
			}
			
			#copyright
			{
				border-top: 1px solid black;
				margin-top: 10px;
				padding: 3px;
			}

			table
			{
/*				border: 1px solid black;*/
				margin: 0px 0;
				width: 100%;
			}

			td, th
			{
				padding: 1px;
				vertical-align: top;
			}
			
			th
			{
				text-align: left;
				font-style: normal;
				font-weight: bold;
				color: black;
			}

			th.header
			{
/*				border-bottom: 2px solid black;*/
				font-weight: bold;
				font-size: 14px;
				text-decoration: underline;
				padding-top: 5px;
				padding-bottom: 5px;
			}
		{/literal}
		</style>
	</head>
	<body>

		<div id="container">
			<h2 style="text-align: left">Information request for:</h2>
			<h1>{$company->getName()}</h1>
			
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
			</table>
			<!-- End of Company detail -->

			<!-- Information Request Details -->
			<h2>Information Request Details</h2>
			<table>
				<tr>
					<th width="150px">Contact:</th>
					<td>{$post->getContactName()} {if $post->getJobTitle() && $post->getContactName()}&nbsp;&nbsp;({$post->getJobTitle()}){else}{$post->getJobTitle()}{/if}</td>
				</tr>
				<tr>
					<th>Email:</th>
					<td>{if $contact}{$contact->getEmail()}{/if}</td>
				</tr>
				<tr>
					<th>Initiative:</th>
					<td>{$client->getName()}: {$initiative->getName()}</td>
				</tr>
			</table>
			<!-- /Information Request Details -->


			<!-- Confirmation Process -->
			<h2>Actions</h2>
			<table>
				<tr>
					<th>Due by:</th>
					<td>{$action->getDueDate()|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}</td>
				</tr>
				<tr>
					<th width="150px">Summary:</th>
					<td>{$action->getSubject()}</td>
				</tr>
				<tr>
					<th>To be sent by:</th>
					<td>{if $action->getActionedByClient()}{$client->getName()}{else}Alchemis{/if}</td>
				</tr>
				<tr>
					<th>How sent:</th>
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


			{*Removed 18/12/07 as per email from DN - to be rinstated in new year?*}
			{*
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
											No
										{else}
											Yes
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
												No
											{elseif $element.value == "1"}
												Yes
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
												No
											{elseif $element.value == "1"}
												Yes
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
										No
									{else}
										Yes
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
			*}

			<!-- Information Request Notes -->
			<h2>Information Request Notes</h2>
			{if $notes|@count == 0}
	
				<p style="text-align: center"><em>&mdash; No notes found &mdash;</em></p>
	
			{else}
	
				<table style="width: 100%" class="default">
					<tbody>
						{foreach name="note_loop" from=$notes item=result}
							<tr{if $result.effective == "effective"} class="current"{/if}>
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
									<span style="margin: 0 10px">Meeting</span>
									{/if}
									{if $result.information_request_id != ""}
									<span style="margin: 0 10px">Information Request</span>
									{/if}
									{if $result.decision_maker_type_id == 1}
									<span style="margin: 0 10px">Decision maker contacted</span>
									{/if}
									{if $result.next_communication_date != ""}
									<span style="margin: 0 10px">Call back scheduled</span>
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
			<!-- /Information Request Notes -->


		


			<div class="footer" align="center">
				<div align="center">Copyright &copy; 2006{if $smarty.now|date_format:"%Y" > 2006}&ndash;{$smarty.now|date_format:"%Y"}{/if} Alchemis Ltd. All rights reserved.</div>
			</div>
			
		</div>	

	</body>
</html>