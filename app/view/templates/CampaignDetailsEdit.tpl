{include file="header2.tpl" title="Edit Campaign Detail"}

{if $success}

	<p>Campaign details are being refreshed.</p>
	<script language="JavaScript" type="text/javascript">
		iframeLocation(parent.ifr_campaign_details, 'index.php?cmd=CampaignDetails&id={$id}');
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
	
	//--- Contract sent date calendar functions ---
	// Handles input from contract sent date calendar - updates contract_sent_date text field
	function handleSelect_contract_sent_date(type,args,obj) 
	{ 
	    var dates = args[0];  
	    var date = dates[0]; 
		//convert incoming params to string type so we can pad the day and month values later on in the function
	    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
	
	    var txtDate1 = $("contract_sent_date"); 
	    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
		Effect.toggle($("contract_sent_date_calendar_display"), 'blind', {duration: 0.3});
	     
	} 
	 
	// Handles input to the contract sent calendar from contract_sent_date text field when the ... button is clicked
	function updateContractSentDateCal() 
	{ 
		Effect.toggle($("contract_sent_date_calendar_display"), 'blind', {duration: 0.3});
	    var txtDate1 = $("contract_sent_date"); 
	 
	 	if (txtDate1.value != "")
	 	{
	 		// Select the date typed in the field 
	    	YAHOO.example.calendar.cal_contract_sent_date.select(txtDate1.value);  
	 	}
	   	       
	   YAHOO.example.calendar.cal_contract_sent_date.render(); 
	 
	} 
	
	//--- Contract received date calendar functions ---
	// Handles input from contract received date calendar - updates contract_received_date text field
	function handleSelect_contract_received_date(type,args,obj) 
	{ 
	    var dates = args[0];  
	    var date = dates[0]; 
		//convert incoming params to string type so we can pad the day and month values later on in the function
	    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
	
	    var txtDate1 = $("contract_received_date"); 
	    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
		Effect.toggle($("contract_received_date_calendar_display"), 'blind', {duration: 0.3});
	     
	} 
	 
	// Handles input to the contract received calendar from contract_received_date text field when the ... button is clicked
	function updateContractReceivedDateCal() 
	{ 
		Effect.toggle($("contract_received_date_calendar_display"), 'blind', {duration: 0.3});
	    var txtDate1 = $("contract_received_date"); 
	 
	 	if (txtDate1.value != "")
	 	{
	 		// Select the date typed in the field 
	    	YAHOO.example.calendar.cal_contract_received_date.select(txtDate1.value);  
	 	}
	   	       
	   YAHOO.example.calendar.cal_contract_received_date.render(); 
	 
	} 
	
	//--- SO form received date calendar functions ---
	// Handles input from so form received date calendar - updates so_form_received_date text field
	function handleSelect_so_form_received_date(type,args,obj) 
	{ 
	    var dates = args[0];  
	    var date = dates[0]; 
		//convert incoming params to string type so we can pad the day and month values later on in the function
	    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
	
	    var txtDate1 = $("so_form_received_date"); 
	    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
		Effect.toggle($("so_form_received_date_calendar_display"), 'blind', {duration: 0.3});
	     
	} 
	 
	// Handles input to the so form received calendar from so_form_received_date text field when the ... button is clicked
	function updateSoFormReceivedDateCal() 
	{ 
		Effect.toggle($("so_form_received_date_calendar_display"), 'blind', {duration: 0.3});
	    var txtDate1 = $("so_form_received_date"); 
	 
	 	if (txtDate1.value != "")
	 	{
	 		// Select the date typed in the field 
	    	YAHOO.example.calendar.cal_so_form_received_date.select(txtDate1.value);  
	 	}
	   	       
	   YAHOO.example.calendar.cal_so_form_received_date.render(); 
	 
	} 
	
	//--- SO form received date calendar functions ---
	// Handles input from notice date calendar - updates notice_date text field
	function handleSelect_notice_date(type,args,obj) 
	{ 
	    var dates = args[0];  
	    var date = dates[0]; 
		//convert incoming params to string type so we can pad the day and month values later on in the function
	    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
	
	    var txtDate1 = $("notice_date"); 
	    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
		Effect.toggle($("notice_date_calendar_display"), 'blind', {duration: 0.3});
	     
	} 
	 
	// Handles input to the notice_date calendar from notice_date text field when the ... button is clicked
	function updateNoticeDateCal() 
	{ 
		Effect.toggle($("notice_date_calendar_display"), 'blind', {duration: 0.3});
	    var txtDate1 = $("notice_date"); 
	 
	 	if (txtDate1.value != "")
	 	{
	 		// Select the date typed in the field 
	    	YAHOO.example.calendar.cal_notice_date.select(txtDate1.value);  
	 	}
	   	       
	   YAHOO.example.calendar.cal_notice_date.render(); 
	 
	} 
	
	
	
	
	function init() 
	{ 
	    YAHOO.example.calendar.cal_contract_sent_date = new YAHOO.widget.Calendar("cal_contract_sent_date","div_cal_contract_sent_date"); 
	    YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("START_WEEKDAY", 1); 
	 	YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("MDY_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("MDY_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("MDY_YEAR_POSITION", 3); 
		YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("MD_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_contract_sent_date.cfg.setProperty("MD_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_contract_sent_date.selectEvent.subscribe(handleSelect_contract_sent_date, YAHOO.example.calendar.cal_contract_sent_date, true); 
	    YAHOO.example.calendar.cal_contract_sent_date.render(); 
	    
	    YAHOO.example.calendar.cal_contract_received_date = new YAHOO.widget.Calendar("cal_contract_received_date","div_cal_contract_received_date"); 
	    YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("START_WEEKDAY", 1); 
	 	YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("MDY_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("MDY_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("MDY_YEAR_POSITION", 3); 
		YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("MD_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_contract_received_date.cfg.setProperty("MD_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_contract_received_date.selectEvent.subscribe(handleSelect_contract_received_date, YAHOO.example.calendar.cal_contract_received_date, true); 
	    YAHOO.example.calendar.cal_contract_received_date.render(); 
	    
	    YAHOO.example.calendar.cal_so_form_received_date = new YAHOO.widget.Calendar("cal_so_form_received_date","div_cal_so_form_received_date"); 
	    YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("START_WEEKDAY", 1); 
	 	YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("MDY_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("MDY_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("MDY_YEAR_POSITION", 3); 
		YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("MD_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_so_form_received_date.cfg.setProperty("MD_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_so_form_received_date.selectEvent.subscribe(handleSelect_so_form_received_date, YAHOO.example.calendar.cal_so_form_received_date, true); 
	    YAHOO.example.calendar.cal_so_form_received_date.render(); 
	    
	    YAHOO.example.calendar.cal_notice_date = new YAHOO.widget.Calendar("cal_notice_date","div_cal_notice_date"); 
	    YAHOO.example.calendar.cal_notice_date.cfg.setProperty("START_WEEKDAY", 1); 
	 	YAHOO.example.calendar.cal_notice_date.cfg.setProperty("MDY_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_notice_date.cfg.setProperty("MDY_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_notice_date.cfg.setProperty("MDY_YEAR_POSITION", 3); 
		YAHOO.example.calendar.cal_notice_date.cfg.setProperty("MD_DAY_POSITION", 1); 
		YAHOO.example.calendar.cal_notice_date.cfg.setProperty("MD_MONTH_POSITION", 2); 
		YAHOO.example.calendar.cal_notice_date.selectEvent.subscribe(handleSelect_notice_date, YAHOO.example.calendar.cal_notice_date, true); 
	    YAHOO.example.calendar.cal_notice_date.render(); 
	    
	} 
	
	YAHOO.namespace("example.calendar"); 
	YAHOO.util.Event.addListener(window, "load", init); 
	
	
	
	{/literal}
	</script>
	<form action="index.php?cmd=CampaignDetailsEdit" method="post" name="adminForm" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="{$campaign->getId()}" />
	
		<fieldset class="adminform">
			<legend>Client</legend>
				{$campaign->getClientName()}
		</fieldset>
		<fieldset class="adminform">
			<legend>Edit Campaign Details</legend>
			<table>
				<tr>
					<td>Campaign Type</td>
					<td>
						<select id="campaign_type" name="campaign_type" style="width: 50%;">
							<option value="0">-- Select --</option>
							{html_options options=$campaign_type_options selected=$campaign->getTypeId()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Campaign start month</td>
					<td>
						{if $start_selected}
							{html_select_date 	time=$start_selected
											start_year='-5' 
											end_year='+5' 
											display_days=false 
											prefix=start_ 
											year_empty='Select...' 
											month_empty='Select...'}
						{else}
							{html_select_date time=0000-00-00  
											start_year='-5' 
											end_year='+5' 
											display_days=false 
											prefix=start_
											year_empty='Select...' 
											month_empty='Select...'}
						{/if}
					</td>
				</tr>
				
				<tr>
					<td>Billing terms</td>
					<td>
						<select id="billing_terms" name="billing_terms" style="width: 50%;">
							<option value="0">-- Select --</option>
							{html_options options=$billing_terms_options selected=$campaign->getBillingTermsId()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Payment terms</td>
					<td>
						<select id="payment_terms" name="payment_terms" style="width: 50%;">
							<option value="0">-- Select --</option>
							{html_options options=$payment_terms_options selected=$campaign->getPaymentTermsId()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Payment method</td>
					<td>
						<select id="payment_method" name="payment_method" style="width: 50%;">
							<option value="0">-- Select --</option>
							{html_options options=$payment_method_options selected=$campaign->getPaymentMethodId()}
						</select>
					</td>
				</tr>
				
				<tr>
					<td style="vertical-align: top;">Contract sent date</th>
					<td style="width: 70%;">
						<input type="text" value="{$campaign->getContractSentDate()|date_format:"%d/%m/%Y"}" id="contract_sent_date" name="contract_sent_date" />
						<span id="span_contract_sent_date_calendar_controls">
							<input type="button" value="..." onclick="javascript:updateContractSentDateCal();" /> 
							<a href="#" onclick="javascript:clearDate('contract_sent_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('contract_sent_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="contract_sent_date_calendar_display" style="display: none;">
							<div id="div_cal_contract_sent_date">
							</div> 
						</div>
					</td>
				</tr>
				
				<tr>
					<td style="vertical-align: top;">Contract received date</th>
					<td style="width: 70%;">
						<input type="text" value="{$campaign->getContractReceivedDate()|date_format:"%d/%m/%Y"}" id="contract_received_date" name="contract_received_date" />
						<span id="span_contract_received_date_calendar_controls">
							<input type="button" value="..." onclick="javascript:updateContractReceivedDateCal();" /> 
							<a href="#" onclick="javascript:clearDate('contract_received_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('contract_received_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="contract_received_date_calendar_display" style="display: none;">
							<div id="div_cal_contract_received_date">
							</div> 
						</div>
					</td>
				</tr>
				
				<tr>
					<td style="vertical-align: top;">SO received date</th>
					<td style="width: 70%;">
						<input type="text" value="{$campaign->getSoFormReceivedDate()|date_format:"%d/%m/%Y"}" id="so_form_received_date" name="so_form_received_date" />
						<span id="span_so_form_received_date_calendar_controls">
							<input type="button" value="..." onclick="javascript:updateSoFormReceivedDateCal();" /> 
							<a href="#" onclick="javascript:clearDate('so_form_received_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('so_form_received_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="so_form_received_date_calendar_display" style="display: none;">
							<div id="div_cal_so_form_received_date">
							</div> 
						</div>
					</td>
				</tr>
				
				<tr>
					<td>Initial monthly fee</td>
					<td><input type="text" name="initial_fee" id="initial_fee" style="width:200px" value="{$campaign->getInitialFee()}" maxlength="10" /></td>
				</tr>
				
				<tr>
					<td>Current monthly fee</td>
					<td><input type="text" name="current_fee" id="current_fee" style="width:200px" value="{$campaign->getCurrentFee()}" maxlength="10" /></td>
				</tr>
				
				<tr>
					<td>Minimum campaign duration</td>
					<td>
						<select style="width: 50%" id="minimum_duration" name="minimum_duration">
							<option value="0">-- Select --</option>
							{html_options options=$minimum_duration_options selected=$campaign->getMinimumDuration()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Notice period</td>
					<td>
						<select style="width: 50%" id="notice_period" name="notice_period">
							<option value="0">-- Select --</option>
							{html_options options=$notice_period_options selected=$campaign->getNoticePeriod()}
						</select>
					</td>
				</tr>
				<tr>
					<td>Additional terms/side letter exist
					<td>
						<input type="checkbox" name="additional_terms" id="additional_terms" {if $campaign->getAdditionalTermsExist()}checked{/if} />
					</td>
				</tr>
				
				<tr>
					<td style="vertical-align: top;">Notice given date</th>
					<td style="width: 70%;">
						<input type="text" value="{$campaign->getNoticeDate()|date_format:"%d/%m/%Y"}" id="notice_date" name="notice_date" />
						<span id="span_notice_date_calendar_controls">
							<input type="button" value="..." onclick="javascript:updateNoticeDateCal();" /> 
							<a href="#" onclick="javascript:clearDate('notice_date');">[clear]</a> | <a href="#" onclick="javascript:new Effect.BlindUp($('notice_date_calendar_display'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
						</span>
						<div id="notice_date_calendar_display" style="display: none;">
							<div id="div_cal_notice_date">
							</div> 
						</div>
					</td>
				</tr>
				<tr>
					<td>Campaign end month</td>
					<td>
						{if $end_selected}
							{html_select_date 	time=$end_selected
											start_year='-5' 
											end_year='+5' 
											display_days=false 
											prefix=end_
											year_empty='Select...' 
											month_empty='Select...'}
						{else}
							{html_select_date time=0000-00-00  
											start_year='-5' 
											end_year='+5' 
											display_days=false 
											prefix=end_
											year_empty='Select...' 
											month_empty='Select...'}
						{/if}
					</td>
				</tr>
			</table>
		</fieldset>
	
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
		
	</form>
{/if}
{include file="footer2.tpl"}