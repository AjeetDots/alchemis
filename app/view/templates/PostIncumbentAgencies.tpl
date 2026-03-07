{include file="header2.tpl" title="Change Post Location"}

<script type="text/javascript">
{literal}

function doSearch(search_item)
{
	if (search_item.value == "")
	{
		$("div_results").innerHTML = "You must enter an agency name in order to search";
		$("div_results").style.display = "block";
		return;
	}
	// show the searching message
	$("div_results").innerHTML = "Please wait... search in progress";
	$("div_results").style.display = "block";
	
	// clear the hidden field which holds new_company_id
	$("app_domain_PostIncumbentAgency_new_company_id").value = ""
	
	// do the search
	getNewLocations(search_item);
	
}

function setNewLocation(id)
{
	alert(id);
	// set the hidden field which holds new agency company id
	$("app_domain_PostIncumbentAgency_new_company_id").value = id;
	alert($("app_domain_PostIncumbentAgency_new_company_id").value);

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
	
	addIncumbent();
	
}

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

function addIncumbent()
{
	var company_id = $F('app_domain_PostIncumbentAgency_new_company_id');
	if (company_id == '')
	{
		alert("No incumbent selected");
		return false;
	}
	
	var ill_params = new Object;
	ill_params.post_id = $F('post_id');
	ill_params.discipline_id = $F('discipline_id');
	ill_params.agency_company_id = company_id;
	
	getAjaxData("AjaxPostIncumbentAgency", "", "add_incumbent", ill_params, "Saving...")
}

function confirmIncumbent(id, name)
{
	if (confirm("Confirm update the incumbent '" + name + "' from this post?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxPostIncumbentAgency", "", "confirm_incumbent", ill_params, "Saving...")
	}
}

function deleteIncumbent(id, name)
{
	if (confirm("Confirm delete the incumbent '" + name + "' from this post?"))
	{
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxPostIncumbentAgency", "", "delete_incumbent", ill_params, "Saving...")
	}
}

function addCompany()
{
	var company = $F('company_name');
	if (company == '')
	{
		alert("No new company name entered");
		return false;
	}
	
	var ill_params = new Object;
	ill_params.post_id = $F('post_id');
	ill_params.discipline_id = $F('discipline_id');
	ill_params.agency_company_name = company;
	
	getAjaxData("AjaxPostIncumbentAgency", "", "add_incumbent_agency_company", ill_params, "Saving...")
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
//				alert(msg);
				$("div_results").innerHTML = msg;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function AjaxPostIncumbentAgency(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_incumbent":
				if (t.currently_exists)
				{
					alert("This incumbent has already been added");
				}
				else
				{
					addNewLine('tbl_incumbent_list', 'tr_incumbent_' + t.post_incumbent_agency_id, t.row_html);
					var temp = "tr_company_id_" + t.agency_company_id;
					$(temp).style.display = "none";
				}
				
				break;
			case "confirm_incumbent":
				$('incumbent_confirmed_' + t.item_id).src = t.img_html;
				break;
			case "delete_incumbent":
				deleteRow('tbl_incumbent_list','tr_incumbent_' + t.item_id);
				break;
			case "add_incumbent_agency_company":
//				alert(t.company_count);
				if (t.currently_exists)
				{
					alert("A company with this name already exists. Please use the 'Search' facility to locate and add this company.");
				}
				else
				{
					addNewLine('tbl_incumbent_list', 'tr_incumbent_' + t.post_incumbent_agency_id, t.row_html);
				}
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function addNewLine(table_name, id, html)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", id);
	row.innerHTML = html;
}

function deleteRow(table_name, item_id)
{
	var tbl = $(table_name);
	var lastRow = tbl.rows.length;
	
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

{/literal}
</script>

<form action="index.php?cmd=PostIncumbentAgencies" method="post" name="searchForm" autocomplete="off">

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="post_id" id="post_id" value="{$post->getId()}" />
	<input type="hidden" name="discipline_id" id="discipline_id" value="{$discipline_id}" />
	<input type="hidden" name="app_domain_PostIncumbentAgency_new_company_id" id="app_domain_PostIncumbentAgency_new_company_id" value="" />

	<fieldset class="adminform">
		<legend>{$discipline|upper} incumbent agencies</legend>
		<strong>{$post->getJobTitle()}</strong>
		<br />
		{$post->getContactName()}
	</fieldset>
	
	<p></p>

	<fieldset class="adminform">
		<legend>New agency</legend>
		<table class="ianlist">
			<tr>
				<th style="width: 40%">Search for company starting with</th>
				<td style="width: 50%"><input type="text" style="width: 100%" name="company_start" /></td>
				<td style="width: 10%; text-align: center">
					<div class="button2-left">
						<div class="page"><a id="searchBtn_1" title="Search" onclick="javascript:doSearch(searchForm.company_start);">Search</a></div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<strong>OR</strong>
				</td>
			</tr>
			<tr>
				<th style="width: 40%">Add new company</th>
				<td style="width: 50%"><input type="text" style="width: 100%" id="company_name" name="company_name" /></td>
				<td style="width: 10%; text-align: center">
					<div class="button2-left">
						<div class="page"><a id="searchBtn_1" title="Search" onclick="javascript:addCompany();">Add</a></div>
					</div>
				</td>
			</tr>
		</table>
		<div id="div_results" style="display: none">
		</div>		
	</fieldset>
	
	<p></p>
	
	<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">

		<tr valign="top">
			<td>
				<table id="tbl_incumbent_list" class="adminlist">
					<thead>
						<tr style="text-align:left">
							<th>Name</th>
							<th>Address</th>
							<th>Last<br />Confirmed</th>
							<th style="width: 25px; text-align: center">Valid?</th>
							<th style="width: 25px; text-align: center">&nbsp;</th>
						</tr>
					</thead>
					
					{* NOTE: Any changes to lines in the following foreach loop also need to made in html_PostIncumbentAgencyLine.tpl *}
					{foreach name=incumbents_loop from=$incumbents item=incumbent}
					<tr id="tr_incumbent_{$incumbent.id}">
						<td>{$incumbent.name}</td>
						<td style="text-align: left">{$incumbent.address}</td>
						<td style="text-align: left">{$incumbent.last_updated_at|date_format:"%d/%m/%Y"}</td>
						<td style="text-align: center; vertical-align: middle">
							<a id="confirmBtn_{$incumbent.id}" title="Confirm incumbent agency" href="#" onclick="javascript:confirmIncumbent({$incumbent.id}, '{$incumbent.name}');return false;"><img src="{$APP_URL}app/view/images/icons/help.png" id="incumbent_confirmed_{$incumbent.id}" alt="Confirm incumbent agency" title="Confirm incumbent agency is still used by this post" /></a>&nbsp;
						</td>
						<td style="text-align: center; vertical-align: middle">
							<a id="deleteBtn_{$incumbent.id}" title="Remove incumbent agency" href="#" onclick="javascript:deleteIncumbent({$incumbent.id}, '{$incumbent.name}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove incumbent agency" title="Remove incumbent agency from this post (does NOT delete the company)" /></a>&nbsp;
						</td>
					</tr>
					{/foreach}
				</table>

			</td>
		</tr>
	</table>
</form>

{include file="footer2.tpl"}