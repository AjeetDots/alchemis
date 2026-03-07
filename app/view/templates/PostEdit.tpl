{include file="header2.tpl" title="Edit Post"}

{if $success}

	<p>Company details are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
		{literal}
		
		if (parent.$('post_initiative_id'))
		{
			var post_initiative_id = parent.$F('post_initiative_id');
		}
		else
		{
			var post_initiative_id = null;
		}
		
		if (parent.$('initiative_id'))
		{
			var initiative_id = parent.$F('initiative_id');
		}
		else
		{
			var initiative_id = null;
		}
		{/literal}
		parent.loadPost({$post->getId()}, initiative_id, post_initiative_id)
		
	</script>

{else}

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
	
	function clearContactDetails()
	{
		$("title").value = "";
		$("first_name").value = "";
		$("surname").value = "";
		$("contact_telephone_mobile").value = "";
		$("email").value = "";
	}
	
	{/literal}
	</script>
	
	<form action="index.php?cmd=PostEdit" method="post" name="adminForm" autocomplete="off">
	
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="company_id" value="{$company_id}" />
		<input type="hidden" name="id" value="{$post->getId()}" />
		{* <input type="hidden" name="contact_id" value="{$contact->getId()}" /> *}
	
		<fieldset class="adminform">
			<legend>Company</legend>
			{$company_name}
		</fieldset>
		<p></p>
		
		<fieldset class="adminform">
				<legend>First post</legend>
				<table>
					<tr>
						<td style="width:80px">Job Title *</td>
						<td><input type="text" name="app_domain_Post_job_title" id="app_domain_Post_job_title" style="width:200px" value="{$post->getJobTitle()}" maxlength="255" /></td>
					</tr>
					<tr>
						<td>Telephone 1</td>
						<td><input type="text" name="app_domain_Post_telephone_1" id="app_domain_Post_telephone_1" style="width:200px" value="{$post->getTelephone1()}" maxlength="255" /></td>
					</tr>
					<tr>
						<td>Telephone 2</td>
						<td><input type="text" name="app_domain_Post_telephone_2" id="app_domain_Post_telephone_2" style="width:200px" value="{$post->getTelephone2()}" maxlength="255" /></td>
					</tr>
					<tr>
						<td>Switchboard</td>
						<td><input type="text" name="app_domain_Post_telephone_switchboard" id="app_domain_Post_telephone_switchboard" style="width:200px" value="{$post->getTelephoneSwitchboard()}" maxlength="255" /></td>
					</tr>
					<tr>
						<td>Fax</td>
						<td><input type="text" name="app_domain_Post_telephone_fax" id="app_domain_Post_telephone_fax" style="width:200px" value="{$post->getTelephoneFax()}" maxlength="255" /></td>
					</tr>
					
          <tr>
						<td>Data Source</td>
						<td>
              <select type="text" name="app_domain_Post_data_source_id" id="app_domain_Post_data_source_id" style="width:200px" />
                {html_options options=$post_data_source_options selected=$post->getDataSourceId()}
              </select
					</tr>
					
					<tr>
						<td colspan="2">
						{if $contact == ""}
							Add a post holder?
							<input type="checkbox" id="chk_display_contact" name="chk_display_contact" onchange="javascript: new Effect.toggle($('div_display_contact'), 'blind', {literal}{duration: 0.3}{/literal});return false;" />
						{else}
							Change post holder?
							<input type="checkbox" id="chk_change_contact" name="chk_change_contact" onchange="javascript:clearContactDetails()" />
						{/if}
						</td>
					</tr>
					
					
				</table>
				<div id="div_display_contact" style="display: {if $contact}block{else}none{/if}">
					<table>	
						<tr>
							<td style="width:80px">Title</td>
							<td><input type="text" name="app_domain_Contact_title" id="app_domain_Contact_title" style="width:200px" value="{if $contact}{$contact->getTitle()}{/if}" maxlength="255" /></td>
						</tr>
						<tr>
							<td>First Name</td>
							<td><input type="text" name="app_domain_Contact_first_name" id="app_domain_Contact_first_name" style="width:200px" value="{if $contact}{$contact->getFirstName()}{/if}" maxlength="255" /></td>
						</tr>
						<tr>
							<td>Surname</td>
							<td><input type="text" name="app_domain_Contact_surname" id="app_domain_Contact_surname" style="width:200px" value="{if $contact}{$contact->getSurname()}{/if}" maxlength="255" /></td>
						</tr>
						<tr>
							<td>Mobile</td>
							<td><input type="text" name="app_domain_Contact_telephone_mobile" id="app_domain_Contact_telephone_mobile" style="width:200px" value="{if $contact}{$contact->getTelephoneMobile()}{/if}" maxlength="255" /></td>
						</tr>
						<tr>
							<td>E-mail</td>
							<td><input type="text" name="app_domain_Contact_email" id="app_domain_Contact_email" style="width:200px" value="{if $contact}{$contact->getEmail()}{/if}" maxlength="255" /></td>
						</tr>
					</table>
				</div>
			</fieldset>
		</div>
		<p></p>
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
	</form>
	
{/if}

{include file="footer2.tpl"}