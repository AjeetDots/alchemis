{include file="header2.tpl" title="Change Post Location"}

<script type="text/javascript">
{literal}

/* --- Ajax calling functions --- */
/*
Each of the following functions collects the data to the sent to a server side ajax command object.
All the functions end with the getAjaxData function call which invokes procedures in the ajaxClient.js
page which in turn invokes the Ajax handlers in prototype.js
Note: data updated via the prototype.InPlaceEditor functionality in controls.js does not need a separate 
calling function like the ones below as it make the call to getAjaxData directly from controls.js
*/

function getNewLocations(search_item)
{
	var ill_params = new Object;
	ill_params['search_item'] = search_item.value;
	getAjaxData("AjaxCompany", "", "get_results_start_with", ill_params, "Saving...");

}

/* --- Ajax return data handlers --- */
// Each javascript page which calls an server side ajax command object requires a function whose name
// is the same as the server side ajax command object being used.
// This function handles all the return information from the server side ajax command object.
// The function handles this return information by using the cmd_action switch.
function AjaxCompany(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case "get_results_start_with":
				var results = t.results;
				var msg = "<table>";
				for (x = 1; x < results.length + 1; x++) 
				{
					msg = msg + "<tr id='tr_company_id_" + results[x-1]["id"] 
					+ "'><td style='vertical-align: top'><input type='button' id='company_id_" + results[x-1]["id"] 
					+ "' value='Select' onclick=\"javascript:setNewLocation('" + results[x-1]["id"] + "');\" /></td><td><strong>" + results[x-1]["name"] 
					+ "</strong><br /><em>" 
					+ results[x-1]["address"] + "	</em></td></tr>";
				}
				msg = msg + "</table>";
				$("div_results").innerHTML = msg;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}
function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.searchForm.task.value=pressbutton;
	
	try 
	{
		document.searchForm.onsubmit();
	}
	
	catch(e)
	{}
	
	document.searchForm.submit();
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

function doSearch(search_item)
{
	if (search_item.value == "")
	{
		$("div_results").innerHTML = "You must enter a new location in order to search";
		$("div_results").style.display = "block";
		return;
	}
	// show the searching message
	$("div_results").innerHTML = "Please wait... search in progress";
	$("div_results").style.display = "block";
	
	// clear the hidden field which holds new_company_id
	$("app_domain_Post_new_company_id").value = ""
	
	// do the search
	getNewLocations(search_item);
	
}

function setNewLocation(id)
{
//	alert(id);
	// set the hidden field which holds new_company_id
	$("app_domain_Post_new_company_id").value = id;
//	alert($("app_domain_Post_new_company_id").value);

	// get all buttons on the form
	var t = document.searchForm.getInputs('button');
	
	// hide each button if != "company_id_" + id
	t.each( function(inputItem)
			{
				var item = "company_id_" + id;
				if (inputItem.id != item)
				{
					var temp = "tr_" + inputItem.id;
					$(temp).style.display = "none";
				}
			});
	
}

{/literal}
</script>

<form action="index.php?cmd=PostEditLocation" method="post" name="searchForm" autocomplete="off">

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="company_id" value="{$company->getId()}" />
	<input type="hidden" name="id" value="{$post->getId()}" />
	<input type="hidden" name="app_domain_Post_new_company_id" id="app_domain_Post_new_company_id" value="" />

	<fieldset class="adminform">
		<legend>Post</legend>
		<strong>{$post->getJobTitle()}</strong>
		<br />
		{$post->getContactName()}
	</fieldset>
	
	<p></p>
	
	<fieldset class="adminform">
		<legend>Current location</legend>
		<strong>{$company->getName()}</strong>
		<br />
		{$company->getSiteAddress(null, 'paragraph')}
	</fieldset>
	<p></p>
	
	<fieldset class="adminform">
		<legend>New location</legend>
		<table class="ianlist">
			<tr>
				<th style="width: 20%">Starting with</th>
				<td style="width: 60%"><input type="text" style="width: 100%" name="company_start" /></td>
				<td style="width: 20%; text-align: center">
					<div class="button2-left">
						<div class="page"><a id="searchBtn_1" title="Search" onclick="javascript:doSearch(searchForm.company_start);">Search</a></div>
					</div>
				</td>
			</tr>
		</table>
		<div id="div_results" style="display: none">
		</div>		
	</fieldset>
	
	<p></p>
	
	<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />&nbsp;|&nbsp;<input type="reset" value="Reset" />
</form>

{include file="footer2.tpl"}