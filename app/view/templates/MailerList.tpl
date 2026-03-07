{config_load file="example.conf"}

{include file="header.tpl" title="User Filter List"}

<script language="JavaScript" type="text/javascript">
{literal}

/* --- Standard functions --- */
// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(10))
{
	parent.tab_colln.add(10);
}
var tab_id = 10;

function openInfoPane(src)
{
	//alert(src);

	if (iframe1 == undefined)
	{
		alert("Going into popup");
		popupWindow(src);
	}
	else
	{
		//alert("Here1");
iframeLocation(		iframe1, src);
	}
		
}


// Following variable holds the id of the filter row which had its background changed to highlighted. We need this so we can set it
// back to normal when a new filter is selected
var last_filter_class_change_id = "";

function editMailer(mailer_id)
{
	//check if we actually need to reload the filter builder
	var reload = true;
	
	var win = iframeWindow(iframe1);
	
	if (win.location != 'about:blank')
	{
		if (win.$("mailer_id"))
		{
			//now check that the filter id being requested matches the id of the filter currently being built
			if (mailer_id == win.$F("mailer_id"))
			{
				reload = false;
			}
		}
		else
		{
			reload = true;
		}
	}
	else
	{
		reload = true;
	}
	
	if (reload)
	{
		iframeLocation(iframe1, "index.php?cmd=MailerItemList&mailer_id=" + mailer_id);
	}
	
	$("iframe1").show();
	setActiveRow(mailer_id);
}

function showStatistics(mailer_id)
{
	iframeLocation(	iframe1, "index.php?cmd=MailerStatistics&id=" + mailer_id);
}

var last_mailer_class_change_id = "";

function setActiveRow(mailer_id)
{
	//set the background of the selected row
	$("tr_" + mailer_id).className="current";
	
	// now set the previously selected items to a normal background
	if (last_mailer_class_change_id != "" && last_mailer_class_change_id != mailer_id)
	{
		$("tr_" + last_mailer_class_change_id).className="";
	}
	last_mailer_class_change_id = mailer_id;
}

function addMailer()
{

	openInfoPane('index.php?cmd=MailerCreate');	
/*	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = "";
	//set the field/value pairs - eg telephone/0121....
	//ill_params['filter_name'] = filter_name;
	
	getAjaxData("AjaxMailer", "", "add_mailer", ill_params, "Saving...")
*/
}


function updateMailer(mailer_id)
{
	openInfoPane('index.php?cmd=MailerEdit&id=' + mailer_id);	
}

function refreshMailerList(display)
{
	location.href = 'index.php?cmd=MailerList&display=' + display;	
}


/* --- Ajax return data handlers --- */
function AjaxMailer(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "add_mailer":
				$("div_add_mailer").innerHTML = t.template;
				pop = new Popup('div_add_mailer',null,{position:'1,1',trigger:'click',duration:'0.25',show_delay:'100'});
				pop.show();
				return;
				break;
			
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

function addNewLine(id, html)
{
	var tbl = $('tbl_filter_list');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", "tr_" + id);
	alert("row.getAttribute: " + row.getAttribute("id"));
	row.innerHTML = html;
	$("new_filter_name").value = "";
	$("span_new_filter_name").hide();
}

function deleteMailer(id)
{
	if (confirm("Confirm delete?"))
	{
		$("iframe1").hide();
		var ill_params = new Object;
		ill_params.item_id = id;
		ill_params.blank = 0;
		getAjaxData("AjaxFilterBuilder", "", "delete_filter", ill_params, "Saving...")
	}
}

function deleteRow(item_id)
{
	var tbl = document.getElementById('tbl_filter_list');
	var lastRow = tbl.rows.length;
	for (var i = 0; i < lastRow; i++)
	{
		var tempRow = tbl.rows[i];
		if (tempRow.getAttribute("id") == "tr_" + item_id)
		{
			tbl.deleteRow(i);
			break;
		}
	}
}

function exportMailer(mailer_id)
{
	top.responderFadeIn();
	location.href = "index.php?cmd=MailerExport&id=" + mailer_id;
	setActiveRow(mailer_id);
	top.responderFadeOut();
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="34%" valign="top">
			<div style="height:720px; overflow-x: hidden; overflow-y: y:auto">
				<table id="" style="width:100%" class="adminlist" border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td style="vertical-align: middle">
							<a href="#" onclick="javascript:addMailer();return false;"><img src="{$APP_URL}app/view/images/icons/email_add.png" alt="Add Mailer" title="Add a new mailer" /></a>
							| <a href="#" onclick="javascript:refreshMailerList('archived');return false;">Show Archived</a>
							| <a href="#" onclick="javascript:refreshMailerList('current');return false;">Show Current</a>
							<div id="div_add_mailer" class="popup" style="display: none; height: 400px; width: 500px; overflow-x: hidden; overflow-y: y:auto"></div>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<table style="width:100%" id="tbl_mailer_list" class="adminlist">
								<thead>
									<tr>
										<th style="width: 5%">ID</th>
										<th style="width: 50%; text-align: center">Name</th>
										{*<th style="width: 40%; text-align: center">Description</th>
										<th style="width: 5%; text-align: center">Type</th>
										<th style="width: 10%; text-align: center">Response Group</th>*}
										<th style="width: 17%; text-align: center">Created On</th>
										<th style="text-align: center">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									
									{foreach name=mail from=$mailers item=mailer}
									<tr id="tr_{$mailer->getId()}">
										<td>{$mailer->getId()}</td>
										<td>
											{$mailer->getName()}
										</td>
{*										<td>{$mailer->getDescription()}</td>
										<td>{$mailer->getTypeName()}</td>
										<td>{$mailer->getResponseGroupName()}</td>*}
										<td>{$mailer->getCreatedAt()|date_format:"`$smarty.config.format_date_short`"}</td>
										<td style="text-align: center; vertical-align: middle; background-color: #F3F3F3">
										
											<a id="btn_update_{$mailer->getId()}" title="Update Mailer" href="#" onclick="javascript:updateMailer({$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Update mailer details" title="Update mailer details" /></a>&nbsp;
											<a id="btn_recipients_{$mailer->getId()}" title="Edit Mailer" href="#" onclick="javascript:editMailer({$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_open.png" alt="Edit Recipients" title="Edit/view recipients" /></a>&nbsp;
											{*<a id="btn_edit_{$mailer->getId()}" title="Edit Mailer" href="#" onclick="javascript:editMailer({$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Edit Mailer" title="Edit/view mailer details" /></a>&nbsp;*}
											{*<a id="btn_export_{$mailer->getId()}" title="Export Mailer" href="index.php?cmd=MailerExport&id={$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export mailer" /></a>&nbsp;*}
											<a id="btn_export_{$mailer->getId()}" title="Export Mailer" href="#" onclick="javascript:exportMailer({$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/page_white_excel.png" alt="Export" title="Export mailer" /></a>&nbsp;
											
											<a id="btn_statistics_{$mailer->getId()}" title="Export Mailer" href="#" onclick="javascript:showStatistics({$mailer->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/chart_pie.png" alt="View Statistics" title="Mailer statistics" /></a>&nbsp;
										</td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</td>
		
		<td width="66%" valign="top">
			<div style="height:730px;">
				<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 720px; width: 100%; "></iframe>
			</div>
		</td>
	</tr>
</table>


{include file="footer.tpl"}