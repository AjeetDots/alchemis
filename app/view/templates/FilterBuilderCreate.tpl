{include file="header2.tpl" title="Filter Builder"}

<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/yahoo/yahoo.js"></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/event/event.js" ></script> 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/dom/dom.js" ></script> 
	 
<script type="text/javascript" src="{$APP_URL}app/view/js/yui/build/calendar/calendar.js"></script> 
<link type="text/css" rel="stylesheet" href="{$APP_URL}app/view/js/yui/build/calendar/assets/calendar.css">  

<script language="JavaScript" type="text/javascript">
{literal}

/* --- Ajax calling functions --- */
function getDataScreenHtml(group_level, field_type)
{
	
	// set the current data entry fields to blank
	$('div_field_data').hide();
	//alert($('div_field_data').style.display);
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = '0';
	//set the field/value pairs - eg telephone/0121....
	ill_params.field_type = field_type;
	ill_params.group_level = group_level;
	
	getAjaxData("AjaxFilterBuilder", "", "get_data_screen_html", ill_params, "Saving...")
}

function getCharacteristicDataScreenHtml(characteristic_id)
{
	// set the current data entry fields to blank
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = characteristic_id;
	ill_params.field_type = 'blank';

	getAjaxData("AjaxFilterBuilder", "", "get_characteristic_data_screen_html", ill_params, "Saving...")
}

function getCharactersticElementDataScreenHtml(element_id)
{
	// set the current data entry fields to blank
	// need to set the innerHTML to "" since when selecting elements we add to the innerHTML
	// rather than just overwriting it. So if we don't blank it here we will just keep adding to it
	if ($('where_elements') != undefined)
	{
		$('where_elements').remove();
	}
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = element_id;
	ill_params.field_type = 'blank';

	getAjaxData("AjaxFilterBuilder", "", "get_characteristic_element_data_screen_html", ill_params, "Saving...")
}


function getFieldList(group_level)
{
	// set the current data entry fields to blank
	$('div_field_data').hide();
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = '0';
	//set the field/value pairs - eg telephone/0121....
	ill_params.group_level = group_level;
	
	getAjaxData("AjaxFilterBuilder", "", "get_field_list", ill_params, "Saving...")
}

function validate()
{
	// validation error variables
	var msg_error = "";
	var msg_error_count = 0;
	
	// Used in validation to check there are an equal number of opening an closing brackets have been added
	var include_opening_bracket_count = 0
	var exclude_opening_bracket_count = 0
	// Used in validation to check there are an equal number of opening an closing brackets have been added
	var include_closing_bracket_count = 0
	var exclude_closing_bracket_count = 0

	// validation warning variables
	var msg_warning = "";
	var msg_warning_count = 0;
	
	if ($F("filter_name") == "")
	{
		msg_error_count++;
		msg_error += msg_error_count + ". You must specify a name for this filter.\n";
	}
	else
	{
		filter_name_length = $F("filter_name").length;
		if (filter_name_length > 100 )
		{
			msg_error_count++;
			msg_error += msg_error_count + ". Filter names cannot be longer than 100 characters. You have entered " + filter_name_length + " characters.\n";
		}
	}
	
	if ($F("type_id") == "0") 
	{
		msg_error_count++;
		msg_error += msg_error_count + ". You must specify a type for this filter.\n";
	}
	
	if ($F("type_id") == "2" && $F("campaign_id") == 0) 
	{
		msg_error_count++;
		msg_error += msg_error_count + ". This filter is of type 'Campaign' but you have not selected an associated campaign.\n";
	}
	
	if ($("is_report_source").checked && $F("type_id") != 2) 
	{
		msg_error_count++;
		msg_error += msg_error_count + ". Only filters of type 'Campaign' can be used as a report source.\n";
	}
	
	if ($F("results_format") == "0") 
	{
		msg_error_count++;
		msg_error += msg_error_count + ". You must specify a results format for this filter.\n";
	}
	
	// put this check in here as no point compiling the filter lines if there are other errors
	if (msg_error == "")
	{
		var t = prepareSubmit();
		
		if (t.line_items_include == "")
		{
			msg_error_count++;
			msg_error += msg_error_count + ". You must specify at least one 'include' parameter.\n";
		}
		
		var t_include = t.line_items_include;
		t_include.each	(function(item)
			{
				include_opening_bracket_count += item.bracket_open.strip().length;
				include_closing_bracket_count += item.bracket_close.strip().length;
			}
		);
		
		var t_exclude = t.line_items_exclude;
		t_exclude.each	(function(item)
			{
				exclude_opening_bracket_count += item.bracket_open.strip().length;
				exclude_closing_bracket_count += item.bracket_close.strip().length;
			}
		);
		
		
		if (include_opening_bracket_count != include_closing_bracket_count) 
		{
			msg_error_count++;
			msg_error += msg_error_count + ". There are an unequal number of opening and closing brackets in the include parameters.\n";
		}
		
		if (exclude_opening_bracket_count != exclude_closing_bracket_count) 
		{
			msg_error_count++;
			msg_error += msg_error_count + ". There are an unequal number of opening and closing brackets in the exclude parameters.\n";
		}

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
			if (confirm("Please check the following suggestions:\n\n" + msg_warning + "\n\If you still wish to save this filter click 'OK', otherwise click 'Cancel' and you will be able to amend the communication details."))
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

function saveFilter()
{
	if (validate())
	{
		var t = prepareSubmit();
		
		var ill_params = new Object;
		ill_params.item_id = 0;
		//set the field/value pairs - eg telephone/0121....
		
		ill_params.line_items_include = t.line_items_include;
		ill_params.line_items_exclude = t.line_items_exclude;
		ill_params.type_id = $F("type_id");
		ill_params.results_format = $F("results_format");
		ill_params.filter_name = $F("filter_name");
		ill_params.campaign_id = $F("campaign_id");
		ill_params.is_report_source = $("is_report_source").checked;
		ill_params.report_parameter_description = $F("report_parameter_description");
		
		getAjaxData("AjaxFilterBuilder", "", "save_filter", ill_params, "Saving...")
	}
}

function getFilterStatistics(filter_id)
{
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = filter_id;
	
	getAjaxData("AjaxFilterBuilder", "", "get_filter_statistics", ill_params, "Saving...", true)
}

/* --- Ajax return data handlers --- */
function AjaxFilterBuilder(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "get_data_screen_html":
				$("additional_data").hide();
				// we may need to run scripts on certain types of screen display
				switch (t.data_screen_type)
				{
					case "date":
						$("div_field_data").innerHTML  = t.data_screen_html;
						$('div_field_data').show();
						calendar_init();
						break;
					case "company characteristic":
					case "post characteristic":
					case "post initiative characteristic":
						$("additional_data").show();
						makeSelectOptions("additional_data", t.data_screen_html);
						break;
					default:
						$("div_field_data").innerHTML  = t.data_screen_html;
						$('div_field_data').show();
						break;
				}
				break;
			case "get_characteristic_data_screen_html":
				// we may need to run scripts on certain types of screen display
				switch (t.data_screen_type)
				{
					case "date":
						$("div_field_data").innerHTML  = t.data_screen_html;
						$('div_field_data').show();
						calendar_init();
						break;
					default:
						$("div_field_data").innerHTML  = t.data_screen_html;
						$('div_field_data').show();
						break;
				}
				break;
			case "get_characteristic_element_data_screen_html":
				// we may need to run scripts on certain types of screen display
				// need to set the id of the element drop down back to the item chosen by the user
				$("div_field_data").innerHTML  += t.data_screen_html;
				$('div_field_data').show();
				
				sel = $("span_where_label");
				for (var i=0; i < sel.options.length; i++)
				{
			    	if (sel.options[i].value == t.item_id)
			    	{
			        	sel.options[i].selected=true;
			      	}
			   }
				
				switch (t.data_screen_type)
				{
					case "date":
						calendar_init();
						break;
					default:
						break;
				}
				break;
			case "get_field_list":
				makeSelectOptions("fields", t.field_list);
				$('div_field_data').show();
				break;
			case "get_filter_statistics":
				$('stats_company_count').innerHTML = t.company_count;
				$('stats_post_count').innerHTML = t.post_count;
				updateFilterListRow(t.item_id);
				break;
			case "save_filter":
				parent.addNewLine('sortable_' + $F('type_id'), t.item_id, t.line_html);
				parent.setActiveRow(t.item_id);
				parent.$('filter_type_' + $F('type_id') + '_count').innerHTML += '&nbsp;&nbsp;&nbsp;<font color="red">New filter added</font>';
				getFilterStatistics(t.item_id);
				alert("New filter saved");
				location.href = "index.php?cmd=FilterBuilder&id=" + t.item_id;
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function updateFilterListRow(filter_id)
{
	parent.$("span_company_count_" + t.item_id).innerHTML = t.company_count;
	parent.$("span_post_count_" + t.item_id).innerHTML = t.post_count;
	parent.$("span_name_" + t.item_id).innerHTML = t.name;
	parent.$("span_results_format_" + t.item_id).innerHTML = t.results_format;
	if (parent.$("span_campaign_" + t.item_id))
	{
		parent.$("span_campaign_" + t.item_id).innerHTML = t.campaign;
	}
}

function makeSelectOptions(dataElement, optionData)
{
	var nodeList = $(dataElement);
	//empty the existing options
	nodeList.options.length=0;
	
	for (i = 0; i < optionData.length + 1; i++) 
	{
		t = optionData[i];
		nodeList.options[i]=new Option(t.text, t.value, true, false);
	}
}
	
function showReportParameterDescription(display)
{
	if (display) {
		$("div_report_parameter_description").show();
	} else {
		$("div_report_parameter_description").hide();
	}
}

function isArray(obj) 
{
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}


function addToWhereTable(include)
{
	var where_data = $("where_data");
	var group_level = $F("group_level");
	var where_operator = $F("where_operator");
	
	if ($F('where_data') == '')
	{
		alert ("No parameter value spacified. Please enter or select a value for this parameter.");
		return;
	}
	
	if ($("additional_data").style.display != "none")
	{
		if ($("span_where_label") == undefined) 
		{
			// this means we havent got any elements showing so need to use the characteristic list as span_where_label
			sel_additional_data = $("additional_data");
			var where_field = $F("fields") + "." + sel_additional_data.options[sel_additional_data.selectedIndex].text; 			
		}
		else
		{
			sel_where = $("span_where_label");
			sel_additional_data = $("additional_data");
			var where_field = $F("fields") + "." + sel_additional_data.options[sel_additional_data.selectedIndex].text + "." + sel_where.options[sel_where.selectedIndex].text;
		}
		
	}
	else
	{
		var where_field = $F("fields");
	}
	
/*	if ($("span_where_label").tagName == 'SELECT')
	{
		sel_where = $("span_where_label");
		sel_additional_data = $("additional_data");
		var where_field = $F("fields") + "." + sel_additional_data.options[sel_additional_data.selectedIndex].text + "." + sel_where.options[sel_where.selectedIndex].text;
	}
	else
	{
		//var where_field = $("span_where_label").innerHTML;
		var where_field = $F("fields");
	}
*/
	makeWhereData(group_level, where_field, where_operator, where_data, include);
}

sections = ['div_where_include', 'div_where_exclude'];
lineitems_include = new Array();
lineitems_exclude = new Array();

function makeWhereData(where_table, where_field, where_operator, where_data, include)
{
//	alert("where_data = "  + where_data.type);

	var where_data_display = "";
	switch(where_data.type)
	{
		case 'select-multiple':
			var where_text = new Array();
//			var where_value = new Array();
			var where_value = "";
			var options = where_data.getElementsByTagName('option');
//			alert(options.length-1);
			
			for (var i=0; i < options.length; i++) 
			{
				if (options[i].selected) 
				{
					//alert(options[i].text + " is selected");
					where_text.push(options[i].text + ", ");
//					where_value.push(options[i].value + ",");
					where_value += options[i].value + ",";
				}
			}
//			where_data = where_value;
//			where_data = $F("where_data");
			where_data = where_value.substr(0, where_value.length-1);
			
//			alert(where_data);
			where_data_display = where_text;
			break;
			
		case 'text':
		default:
			where_data = $F(where_data);
			where_data_display = where_data;
			break;
		
	}
	
	//alert(where_data);
	
	createNewLineItem(where_table, where_field, where_operator, where_data, where_data_display, include, "and");
	
}

function createNewLineItem(where_table, where_field, where_operator, where_data, where_data_display, include, concatenator, bracket_open, bracket_close)
{

	if (include == 'include')
	{
		line_item_id = lineitems_include.length + 1;
	}
	else //assume exclude
	{
		line_item_id = lineitems_exclude.length + 1;
	}
	
	var open_bracket_span = Builder.node('span', {id: include + '_bracket_open_' + line_item_id, className: 'bracket_open'}, bracket_open);
	var close_bracket_span = Builder.node('span', {id: include + '_bracket_close_' + line_item_id, className: 'bracket_close'}, bracket_close);

	var newDiv = Builder.node('div', {id: 'where_item_' + include + '_' + line_item_id, className: 'lineitem_' + include, style: 'cursor: move; font-family: courier; display:none; padding: 1px; background-color:#d4d2d2; margin:3px' });
	
	table = Builder.node('table', {width:'100%',cellpadding:'2',cellspacing:'0',border:'0'});
 	tbody = Builder.node('tbody');
 	tr = Builder.node('tr', {style: 'padding: 8px; height: 25px; vertical-align: top; background-color:#f9f9f9; border: thin dotted #d4d2d2'});
  		
	var newAnchor1 = document.createElement("a");
	newAnchor1.setAttribute("title", "Add opening bracket");
	newAnchor1.setAttribute("href", "javascript:addBracket('" + include + "', '" + include + "_bracket_open_" + line_item_id + "', '(')");	
	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_" + line_item_id);
	newImg.setAttribute("src", "app/view/images/icons/add.png");
	newImg.setAttribute("style", "vertical-align:middle; padding:5px");
	newAnchor1.appendChild(newImg);
	newImg = null;
	
	td = Builder.node('td', {style: 'width: 15px'});
	td.appendChild(newAnchor1);
	tr.appendChild(td);
	td = null

	newAnchor1 = null;
	
	var newAnchor1 = document.createElement("a");
	newAnchor1.setAttribute("title", "Remove opening bracket");
	newAnchor1.setAttribute("href", "javascript:removeBracket('" + include + "', '" + include + "_bracket_open_" + line_item_id + "', '(')");	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_" + line_item_id);
	newImg.setAttribute("src", "app/view/images/icons/delete.png");
	newImg.setAttribute("style", "vertical-align:middle; padding:5px");
	newAnchor1.appendChild(newImg);
	newImg = null;

	td = Builder.node('td', {style: 'width: 15px'});
	td.appendChild(newAnchor1);
	tr.appendChild(td);
	td = null
	
	newAnchor1 = null;
	var where_table_span = Builder.node('span', {id: 'span_where_table_' + line_item_id, className: 'where_table', style: 'display: none'}, where_table);
	var fields = {
		'parent company': 'company',
		'company': 'site'
	};
	var display_table_span = Builder.node('span', {id: 'span_display_table_' + line_item_id, className: 'display_table'}, fields[where_table] || where_table);
	var where_field_span = Builder.node('span', {id: 'span_field_data_' + line_item_id, className: 'where_field'}, where_field);
	var where_operator_span = Builder.node('span', {id: 'span_operator_data_' + line_item_id, className: 'where_operator'}, where_operator);
	var where_data_span = Builder.node('span', {id: 'span_where_data_' + line_item_id, style: 'display: none;', className: 'where_data'}, where_data);
	var where_data_display_span = Builder.node('span', {id: 'span_where_data_display' + line_item_id, className: 'where_data_display'}, where_data_display);
	
	td = Builder.node('td', {style: 'width: 10px'});
	td.appendChild(open_bracket_span);
	tr.appendChild(td);
	td = null
	
	td = Builder.node('td', {style: 'width: 80px'});
	td.appendChild(where_table_span);
	td.appendChild(display_table_span);
	tr.appendChild(td);
	td = null
	
	td = Builder.node('td', {style: 'width: 95px; text-align:left'});
	td.appendChild(where_field_span);
	tr.appendChild(td);
	td = null
	
	td = Builder.node('td', {style: 'width: 95px'});
	td.appendChild(where_operator_span);
	tr.appendChild(td);
	td = null
	
	td = Builder.node('td');
	td.appendChild(where_data_span);
	tr.appendChild(td);
	td = null
	
	td = Builder.node('td');
	td.appendChild(where_data_display_span);	
	tr.appendChild(td, {style: 'width: 275px'});
	td = null
	
	td = Builder.node('td');
	td.appendChild(close_bracket_span);
	tr.appendChild(td, {style: 'width: 10px'});
	td = null
	
	var newAnchor1 = document.createElement("a");
	newAnchor1.setAttribute("title", "Add closing bracket");
	newAnchor1.setAttribute("href", "javascript:addBracket('" + include + "', '" + include +  "_bracket_close_" + line_item_id + "', ')')");	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_" + line_item_id);
	newImg.setAttribute("src", "app/view/images/icons/add.png");
	newImg.setAttribute("style", "vertical-align:middle; padding:5px");
	newAnchor1.appendChild(newImg);
	newImg = null;
	
	td = Builder.node('td');
	td.appendChild(newAnchor1);
	tr.appendChild(td, {style: 'width: 15px'});
	td = null
	
	newAnchor1 = null;
	var newAnchor1 = document.createElement("a");
	newAnchor1.setAttribute("title", "Remove closing bracket");
	newAnchor1.setAttribute("href", "javascript:removeBracket('" + include + "', '" + include + "_bracket_close_" + line_item_id + "', ')')");	
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_" + line_item_id);
	newImg.setAttribute("src", "app/view/images/icons/delete.png");
	newImg.setAttribute("style", "vertical-align:middle; padding:5px");
	newAnchor1.appendChild(newImg);
	newImg = null;
	
	td = Builder.node('td');
	td.appendChild(newAnchor1);
	tr.appendChild(td, {style: 'width: 15px'});
	td = null
		
	// create and/or concat list
	var sel = Builder.node("select", {id: 'sel_concatenator_' + line_item_id, className: 'concatenator'});
	var opt = document.createElement("option");
	opt.value = "and";
	opt.text = "and";
	if (concatenator == "and")
	{
		opt.selected = true;
	}
	sel.options.add(opt);
	opt = null;
	var opt = document.createElement("option");
	opt.value = "or";
	opt.text = "or";
	if (concatenator == "or")
	{
		opt.selected = true;
	}
	sel.options.add(opt);
	opt = null;
	
	td = Builder.node('td', {style: 'width: 30px'});
	td.appendChild(sel);
	tr.appendChild(td);
	td = null
	
	var newAnchor = document.createElement("a");
	newAnchor.setAttribute("title", "Delete row");
	newAnchor.setAttribute("href", "javascript:deleteRow(" + line_item_id + ", '" + include + "', 'div_where_" + include + "');");
	var newImg = document.createElement("img");
	newImg.setAttribute("id", "img_delete_" + line_item_id);
	newImg.setAttribute("src", "app/view/images/delete.png");
	newImg.setAttribute("style", "vertical-align:middle; padding:5px");
	newAnchor.appendChild(newImg);
	
	td = Builder.node('td', {style: 'width: 15px'});
	td.appendChild(newAnchor);
	
	tbody.appendChild(tr);
	table.appendChild(tbody);
	tr.appendChild(td);
	td = null
	
	newDiv.appendChild(table);
	
	if (include == 'include')
	{
		lineitems_include.push(newDiv.id);
		$('div_where_include').appendChild(newDiv);
	}
	else //assume exclude
	{
		lineitems_exclude.push(newDiv.id);
		$('div_where_exclude').appendChild(newDiv);
	}
	
	
	Effect.Appear(newDiv.id);
	destroyLineItemSortables();
	createLineItemSortables();
		
}

function createLineItemSortables() 
{
	Sortable.create('div_where_include',{tag:'div',dropOnEmpty: true, only: 'lineitem_include', delay: 200});
	Sortable.create('div_where_exclude',{tag:'div',dropOnEmpty: true, only: 'lineitem_exclude', delay: 200});
}

function destroyLineItemSortables() {
	for(var i = 0; i < sections.length; i++) 
	{
		Sortable.destroy(sections[i]);
	}
}

function deleteRow(item_id, include, section)
{
//	alert(item_id + " : " + section);
	var parent = $(section);
	parent.removeChild($("where_item_" + include + "_" + item_id));
}

function prepareSubmit()
{
	// DO INCLUDE ITEMS
	var line_items = document.getElementsByClassName('lineitem_include');
//	var str = "";

	var obj_line_items_include = [];
	var i =0;
	
	for (i = 0; i < line_items.length; i++)
	{
         obj_line_items_include[i] = parseLineItem(line_items[i]);
    }

//	line_items.each	(function(item)
//		{
//		   obj_line_items_include[i] = parseLineItem(item);
//			i++;
//		}
//	);
	
//	var jsonRequest = JSON.stringify(obj_line_items_include);
//	alert(jsonRequest);
	

	// DO EXCLUDE ITEMS
	var line_items = document.getElementsByClassName('lineitem_exclude');
 
	var obj_line_items_exclude = [];
	var i = 0;
	for (i = 0; i < line_items.length; i++)
    {
         obj_line_items_exclude[i] = parseLineItem(line_items[i]);
    }
    
//	line_items.each	(function(item)
//		{
//			obj_line_items_exclude[i] = parseLineItem(item);
//			i++;
//		}
//	);
	
//	var jsonRequest = JSON.stringify(obj_line_items_exclude);
//	alert("jsonRequest = " + jsonRequest);
	
	var obj = new Object;
	obj.line_items_include = obj_line_items_include;
	obj.line_items_exclude = obj_line_items_exclude;
	
	return obj;
	
}

function parseLineItem(element)
{

	var obj_line_item = new Object;
	
	// get any opening brackets
	var children = element.getElementsByClassName('bracket_open');
//	children.each (function(item){obj_line_item.bracket_open = item.innerHTML;});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.bracket_open = children[i].innerHTML;
    }
	
	
	// get table data
	var children = element.getElementsByClassName('where_table');
//	children.each (function(item){obj_line_item.where_table = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.where_table = children[i].innerHTML;
    }
    
	// get field data
	var children = element.getElementsByClassName('where_field');
//	children.each (function(item){obj_line_item.where_field = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.where_field = children[i].innerHTML;
    }
    
	// get operator data
	var children = element.getElementsByClassName('where_operator');
//	children.each (function(item){obj_line_item.where_operator = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.where_operator = children[i].innerHTML;
    }
    
	// get where data
	var children = element.getElementsByClassName('where_data');
//	children.each (function(item){obj_line_item.where_data = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.where_data = children[i].innerHTML;
    }
    
	// get where data display
	var children = element.getElementsByClassName('where_data_display');
//	children.each (function(item){obj_line_item.where_data_display = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.where_data_display = children[i].innerHTML;
    }
    
	// get any closing brackets
	var children = element.getElementsByClassName('bracket_close');
//	children.each (function(item){obj_line_item.bracket_close = item.innerHTML});
	var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.bracket_close = children[i].innerHTML;
    }
    
	// get any concatenators
	var children = element.getElementsByClassName('concatenator');
//	children.each (function(item){obj_line_item.concatenator = item.value});
    var index = 0;
    for (i = 0; i < children.length; i++)
    {
         obj_line_item.concatenator = children[i].value;
    }
    
	return obj_line_item;
}

function addBracket(direction, row_item, text)
{
	new Insertion.Top(row_item, text);
/*	if (text.strip() == ')')
	{
		switch (direction)
		{
			case 'include':
				include_closing_bracket_count ++;
				break;
			case 'exclude':
				exclude_closing_bracket_count ++;
			break;
		}

	}
	else if (text.strip() == '(')
	{
		switch (direction)
		{
			case 'include':
				include_opening_bracket_count ++;
				break;
			case 'exclude':
				exclude_opening_bracket_count ++;
			break;
		}
	}
*/	
//	alert("opening_bracket_count = " + opening_bracket_count + "\n\n" + "closing_bracket_count = " + closing_bracket_count);
	
}


function removeBracket(direction, row_item, text)
{
	var t = $(row_item).innerHTML;
	t = t.strip();
	if (t.length > 0)
	{
		$(row_item).innerHTML = t.substring(0,t.length-1)
/*		if (text.strip() == ')')
		{
			switch (direction)
			{
				case 'include':
					include_closing_bracket_count --;
					break;
				case 'exclude':
					exclude_closing_bracket_count --;
				break;
			}
		}
		else if (text.strip() == '(')
		{
			switch (direction)
			{
				case 'include':
					include_opening_bracket_count --;
					break;
				case 'exclude':
					exclude_opening_bracket_count --;
				break;
			}
		}
*/
	}
	
//	alert("opening_bracket_count = " + opening_bracket_count + "\n\n" + "closing_bracket_count = " + closing_bracket_count);
	
}


// ====== CALENDAR FUNCTIONS ======= //
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

// handles input from 'to date' calendar - updates to_date text field
function handleSelect(type,args,obj) 
{ 
    var dates = args[0];  
    var date = dates[0]; 
	//convert incoming params to string type so we can pad the day and month values later on in the function
    var year = date[0].toString(), month = date[1].toString(), day = date[2].toString(); 
     
    var txtDate1 = $("where_data"); 
    txtDate1.value = day.pad(2,"0",0) + "/" + month.pad(2,"0",0) + "/" + year; 
    
    Effect.toggle($("calendar_display"), 'blind', {duration: 0.3});
} 

//handles input to the info_req_calendar from to_date text field when the ... button is clicked
function updateCal() 
{ 
	Effect.toggle($("calendar_display"), 'blind', {duration: 0.3});
    var txtDate1 = $("where_data"); 
    
 	if (txtDate1.value != "")
 	{
 		// Select the date typed in the field 
    	YAHOO.example.calendar.cal.select(txtDate1.value);  
 	} 
     
  	YAHOO.example.calendar.cal.render(); 
} 

function calendar_init() 
{ 
//	alert("here");
    YAHOO.example.calendar.cal = new YAHOO.widget.Calendar("cal","calendar"); 
    
    YAHOO.example.calendar.cal.cfg.setProperty("START_WEEKDAY", 1);
 	YAHOO.example.calendar.cal.cfg.setProperty("MDY_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal.cfg.setProperty("MDY_MONTH_POSITION", 2); 
	YAHOO.example.calendar.cal.cfg.setProperty("MDY_YEAR_POSITION", 3); 
	 
	YAHOO.example.calendar.cal.cfg.setProperty("MD_DAY_POSITION", 1); 
	YAHOO.example.calendar.cal.cfg.setProperty("MD_MONTH_POSITION", 2); 

	YAHOO.example.calendar.cal.selectEvent.subscribe(handleSelect, YAHOO.example.calendar.cal, true); 
    YAHOO.example.calendar.cal.render(); 
    
} 

YAHOO.namespace("example.calendar"); 

{/literal}
</script>

<table class="adminform" style="width: 100%">
	<tr>
		<td valign="top">
			<div class="module_content">
				<form action="index.php?cmd=FilterBuilder" method="post" name="adminForm" autocomplete="off">
					<input type="hidden" name="task" value="" />
					<div id="filter">
						<h3>Filter details</h3>
						<table style="width:75%">
							<tr>
								<td>
									Name
								</td>
								<td>
									<input type="text" id="filter_name" name="filter_name" value="" style="width:100%" />
								</td>
							</tr>
							<tr>
								<td style="width: 100px">
									Type
								</td>
								<td>
									<select id="type_id" name="type_id" style="width: 200px">
										{html_options 	values = $type_values
														output = $type_output
														selected = $type_selected}
									</select>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top">
									Campaign 
								</td>
								<td>
									<select id="campaign_id" name="campaign_id" style="width: 90%">
										<option selected value="0">-- Select campaign --</option>
										{foreach name="campaign_loop" from=$campaigns item=campaign}
											<option value="{$campaign->getId()}">{$campaign->getClientName()}</option>
										{/foreach}
									</select>
								</td>
							</tr>
							<tr>
								<td style="width: 100px">
									Results Format
								</td>
								<td>
									<select id="results_format" style="width: 200px">
										{html_options 	values = $results_format_values
														output = $results_format_output
														selected = $results_format_selected}
									</select>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;width: 100px">
									Use as Report Source
								</td>
								<td>
									<input type="checkbox" id="is_report_source" onchange="javascript:showReportParameterDescription(this.checked);"/>
									<div id="div_report_parameter_description" style="display:none">
										<br />
										Report Parameter Description
										<br />
										<textarea id="report_parameter_description" name="report_parameter_description" rows="4" style="width:100%;"></textarea>
									</div>
								</td>
							</tr>
						</table>
						<p></p>
						
					</div>
					<h3>Parameter selection</h3>
					<table>
						<tr>
							<td style="vertical-align: top;">
								Data level
							</td>
							<td>
								Field
							</td>
							<td>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td style="vertical-align: top;">
								<select name="group_level" id="group_level" style="width: 200px;"  onchange="javascript:getFieldList(this.options[this.selectedIndex].value);">
									{html_options 
										options = $group_options}
								</select>
							</td>
							<td>
								<select name="fields" id="fields" style="width: 200px;" onchange="javascript:getDataScreenHtml($F('group_level'), this.options[this.selectedIndex].value);">
									{html_options 
										options = $field_options}
								</select>
							</td>
							<td style="width:200px;">
								<select name="additional_data" id="additional_data" style="display: none; width: 200px;" onchange="javascript:getCharacteristicDataScreenHtml(this.options[this.selectedIndex].value);">
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="vertical-align: top; ">
								<div id="div_field_data" style="width: 100%; vertical-align: top;">
									{* intentionally blank - populated dynamically by user via ajax*}						
								</div>
							</td>
						</tr>
						<tr>
							<td style="vertical-align: top;">
								<input type="button" value="Add to include" onclick="javascript:addToWhereTable('include');" />
								&nbsp;|&nbsp;
								<input type="button" value="Add to exclude" onclick="javascript:addToWhereTable('exclude');"/>
							</td>
						</tr>
					</table>
					<div id="page">
						<p></p>
						<h3>Selected parameters</h3>
						<table>
							<tr>
								<td colspan="2">
									Include items:
									<div id="div_where_include" class="sections">
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									Exclude items:
									<div id="div_where_exclude" class="sections">
									</div>
								</td>
							</tr>
						</table>
					{*<div id="page">*}
					</div>
					<input type="button" id="save" name="save" value="Save" onclick="javascript:saveFilter();"/>
				</form>
			</div>
		</td>
		{*<td width="50%" valign="top">
			<div id="filter_results">
			</div>
		</td>*}
	</tr>
</table>



<script type="text/javascript">
{literal}
	// <![CDATA[
//	Sortable.create('div_where_include',{tag:'div',dropOnEmpty: true, only: 'lineitem_include'});
//	Sortable.create('div_where_exclude',{tag:'div',dropOnEmpty: true, only: 'lineitem_exclude'});
	Sortable.create('page',{tag:'div',only:'section',handle:'handle'});
	// ]]>
{/literal}
 </script>
 
 {* loop through all filter lines for this filter and call the createNewLineItem javascript function
  to set up the filter *}
<script type="text/javascript"> 
{foreach name=fl from=$filter_lines item=filter_line}
	createNewLineItem("{$filter_line.table_name}", "{$filter_line.field_name}", "{$filter_line.operator}", "{$filter_line.params}", "{$filter_line.params_display}", "{$filter_line.direction}", "{$filter_line.concatenator}", "{$filter_line.bracket_open}", "{$filter_line.bracket_close}");
{/foreach}
</script>


{include file="footer2.tpl"}