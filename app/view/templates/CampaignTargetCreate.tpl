{include file="header2.tpl" title="Campaign Target Create"}

{if $success}



{else}

	<script type="text/javascript">
	{literal}
	
	
	function submitform(pressbutton, form_name)
	{
		var frm = $(form_name);
		frm.task.value = pressbutton;
		try 
		{
			frm.onsubmit();
		}
		catch(e)
		{}
		frm.submit();
	}
	
	
	function submitbutton(pressbutton, form_name)
	{
		if (pressbutton == 'save') 
		{
			if (validation())
			{
				submitform(pressbutton, form_name);
			}
			return;
		}
	}
	
	function validation()
	{
		var form = $('frm_targets')
		var i = form.getInputs('text') // -> only text inputs
		var results = [];
		
		i.each(function(item) 
		{
			if (item.value.strip() == '' || isNaN(item.value))
			{
				 results.push(item.name + ' is not a number;\n');
			}
			else
			{
				if (Number(item.value) < 0)
				{
					results.push(item.name + ' is less than zero;\n');
				}
			}
		});

		var results = $A(results);
		var msg = '';
		results.each(function(item) 
		{
			msg += item;
		});
		
		if (msg != '')
		{
			msg = 'Please correct the following errors\n\n' + msg;
			alert(msg);
			return false;
		}
		else
		{
			return true;
		}
		
		
	}
	
	{/literal}
	</script>
	<form action="index.php?cmd=CampaignTargetCreate" method="post" name="frm_targets" id="frm_targets" autocomplete="off">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="campaign_id" value="{$campaign_id}" />
	
			
		<fieldset class="adminform">
			<legend>Campaign</legend>
			
		</fieldset>
		<p></p>
		
		<fieldset class="adminform">
				<legend>Add post</legend>
				<table id="tbl_targets_list" class="adminlist">
					<thead>
						<tr>
							<th style="width: 3%">Year/Month</th>
							<th>Calls</th>
							<th>Effectives</th>
							<th>Set</th>
							<th>Attended</th>
							<th>Opportunities</th>
							<th>Wins</th>
						</tr>
					</thead>
			
					{foreach name=targets_loop from=$months item=month}
					<tr id="tr_{$month.year_month}">
						<td>{$month.month_display}</td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_calls" value="0" style="width:25px" /></td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_effectives" value="0" style="width:25px" /></td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_set" value="0" style="width:25px" /></td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_attended" value="0" style="width:25px" /></td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_opportunities" value="0" style="width:25px" /></td>
						<td style="text-align:center"><input type="text" name="target{$month.year_month}_wins" value="0" style="width:25px" /></td>
					</tr>
					{/foreach}
				</table>
			</fieldset>
		</div>
		<p></p>
		<input type="button" value="Submit" onclick="javascript:submitbutton('save', 'frm_targets')" />&nbsp;|&nbsp;<input type="button" value="Reset" onclick="javascript:if (confirm('Clear all values?')){literal}{$('frm_targets').reset(); return false;}{/literal}" />
	</form>
{/if}
{include file="footer2.tpl"}