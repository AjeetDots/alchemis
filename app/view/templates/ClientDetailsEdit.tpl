{include file="header2.tpl" title="Edit Client Detail"}

{if $success}

	<p>Client details are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
iframeLocation(		parent.ifr_client_details, 'index.php?cmd=ClientDetails&id={$id}');
	</script>

{else}

	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
	<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
	<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  
	
	<script type="text/javascript">
		
	{literal}
	
	
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
	
	
	function submitbutton(pressbutton)
	{
		if (pressbutton == 'save') 
		{
			submitform( pressbutton );
			return;
		}
	}
	
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
	
	//--- financial year calendar functions ---
	// Handles input from contract sent date calendar - updates contract_sent_date text field
	function handleSelect_financial_year_start_date(type,args,obj) 
	{ 
	    var dates = args[0];  
	    var date = dates[0]; 
		//convert incoming params to string type so we can pad the day and month values later on in the function
	    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
	
	    var txtDate1 = $("financial_year_start_date"); 
	    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
		Effect.toggle($("financial_year_start_date_calendar_display"), 'blind', {duration: 0.3});
	     
	} 
	 
	// Handles input to the contract sent calendar from financial year start date text field when the ... button is clicked
	function updateFinancialYearStartDateCal() 
	{ 
		Effect.toggle($("financial_year_start_date_calendar_display"), 'blind', {duration: 0.3});
	    var txtDate1 = $("financial_year_start_date"); 
	 
	 	if (txtDate1.value != "")
	 	{
	 		// Select the date typed in the field 
	    	YAHOO.example.calendar.cal_financial_year_start_date.select(txtDate1.value);  
	 	}
	   	       
	   YAHOO.example.calendar.cal_financial_year_start_date.render(); 
	 
	} 
	
	function init() 
	{ 
	    YAHOO.example.calendar.cal_financial_year_start_date = new YAHOO.widget.Calendar("cal_financial_year_start_date","div_cal_financial_year_start_date"); 
	    YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("START_WEEKDAY", 1); 
	 	YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("MDY_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("MDY_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("MDY_YEAR_POSITION", 3); 
		YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("MD_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_financial_year_start_date.cfg.setProperty("MD_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_financial_year_start_date.selectEvent.subscribe(handleSelect_financial_year_start_date, YAHOO.example.calendar.cal_financial_year_start_date, true); 
	    YAHOO.example.calendar.cal_financial_year_start_date.render(); 
	} 
	
	YAHOO.namespace("example.calendar"); 
	YAHOO.util.Event.addListener(window, "load", init); 
	
	{/literal}
	</script>
	<form action="index.php?cmd=ClientDetailsEdit" method="post" name="adminForm" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="{$client->getId()}" />
	
		<fieldset class="adminform">
			<legend>Client</legend>
			{$client->getName()}
		</fieldset>
		<fieldset class="adminform">
			<legend>Edit Client Details</legend>
			<table>
				<tr>
					<td>Name</td>
					<td>
						<input type="text" name="client_name" id="client_name" style="width:200px" value="{$client->getName()}" maxlength="100" />
					</td>
				</tr>
				<tr>
					<td>Is Current
					<td>
						<input type="checkbox" name="is_current" id="is_current" {if $client->getIsCurrent()}checked{/if} />
					</td>
				</tr>
				<tr>
					<td>Telephone</td>
					<td>
						<input type="text" name="telephone" id="telephone" style="width:200px" value="{$client->getTelephone()}" maxlength="50" />
					</td>
				</tr>
				<tr>
					<td>Fax</td>
					<td>
						<input type="text" name="fax" id="fax" style="width:200px" value="{$client->getFax()}" maxlength="50" />
					</td>
				</tr>
				
				<tr>
					<td>Website</td>
					<td>
						<input type="text" name="website" id="website" style="width:200px" value="{$client->getWebsite()}" maxlength="100" />
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Financial year start date</th>
					<td style="width: 70%;">
						<input type="text" value="{$client->getFinancialYearStart()|date_format:"%d/%m/%Y"}" id="financial_year_start_date" name="financial_year_start_date" />
						<span id="span_financial_year_start_date_calendar_controls">
							<input type="button" value="..." onclick="javascript:updateFinancialYearStartDateCal();" /> 
							<a href="#" onclick="javascript:clearDate('financial_year_start_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('financial_year_start_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="financial_year_start_date_calendar_display" style="display: none;">
							<div id="div_cal_financial_year_start_date">
							</div> 
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<th colspan="2">Invoice address</th>
				</tr>
				<tr>
					<td>Address 1</td>
					<td><input type="text" name="address_1" id="address_1" style="width:200px" value="{$client->getAddress1()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Address 2</td>
					<td><input type="text" name="address_2" id="address_2" style="width:200px" value="{$client->getAddress2()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Address 3</td>
					<td><input type="text" name="address_3" id="address_3" style="width:200px" value="{$client->getAddress3()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Town</td>
					<td><input type="text" name="town" id="town" style="width:200px" value="{$client->getTown()}" maxlength="100" /></td>
				</tr>
				<tr>
					<td>County</td>
					<td>
						<select name="county_id" id="county_id" style="width: 100%">
							{html_options options=$counties_options selected=$client->getCountyId()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Postcode</td>
					<td><input type="text" name="postcode" id="postcode" style="width:200px" value="{$client->getPostcode()}" maxlength="100" /></td>
				</tr>
				<tr>
					<td>Country</td>
					<td>
						<select name="country_id" id="country_id" style="width: 100%">
							{html_options options=$countries_options selected=$client->getCountryId()}
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<th colspan="2">Primary contact</th>
				</tr>
				<tr>
					<td>Name</td>
					<td><input type="text" name="primary_contact_name" id="primary_contact_name" style="width:200px" value="{$client->getPrimaryContactName()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Job Title</td>
					<td><input type="text" name="primary_contact_job_title" id="primary_contact_job_title" style="width:200px" value="{$client->getPrimaryContactJobTitle()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Telephone</td>
					<td><input type="text" name="primary_contact_telephone" id="primary_contact_telephone" style="width:200px" value="{$client->getPrimaryContactTelephone()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Email</td>
					<td><input type="text" name="primary_contact_email" id="primary_contact_email" style="width:200px" value="{$client->getPrimaryContactEmail()}" maxlength="100" /></td>
				</tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<th colspan="2">Secondary contact</th>
				</tr>
				<tr>
					<td>Name</td>
					<td><input type="text" name="secondary_contact_name" id="secondary_contact_name" style="width:200px" value="{$client->getSecondaryContactName()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Job Title</td>
					<td><input type="text" name="secondary_contact_job_title" id="secondary_contact_job_title" style="width:200px" value="{$client->getSecondaryContactJobTitle()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Telephone</td>
					<td><input type="text" name="secondary_contact_telephone" id="secondary_contact_telephone" style="width:200px" value="{$client->getSecondaryContactTelephone()}" maxlength="100" /></td>
				</tr>
				
				<tr>
					<td>Email</td>
					<td><input type="text" name="secondary_contact_email" id="secondary_contact_email" style="width:200px" value="{$client->getSecondaryContactEmail()}" maxlength="100" /></td>
				</tr>
				
			</table>
		</fieldset>
	
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
		
	</form>
{/if}
{include file="footer2.tpl"}