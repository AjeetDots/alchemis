{include file="header.tpl" title="User List"}

<script language="JavaScript" type="text/javascript">
{literal}

function editUser(id)
{
iframeLocation(	iframe1, "index.php?cmd=UserEdit&user_id=" + id);
	$("iframe1").show();
	setActiveRow(id);
}

var last_filter_class_change_id = "";

function setActiveRow(id)
{
	// Set the background of the selected row
	$('tr_' + id).className = "current";
	
	// Set the previously selected items to a normal background
	if (last_filter_class_change_id != "" && last_filter_class_change_id != id)
	{
		$('tr_' + last_filter_class_change_id).className = "";
	}
	last_filter_class_change_id = id;
}

function saveUser()
{
//	alert('saveUser()');
	var ill_params = new Object;
	ill_params.name     = $F('name');
	ill_params.handle   = $F('handle');
	ill_params.password = $F('password');
	ill_params.active   = $F('active');
	ill_params.client_id   = $F('client_id');
	getAjaxData('AjaxUser', '', 'add_user', ill_params, 'Adding...')
}

/**
 * Ajax return data handlers
 */
function AjaxUser(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
//		alert("t.item_id = " + t.item_id + "\nt.value = " + t.telephone + "\nt.cmd_action = " + t.cmd_action);
		
		switch (t.cmd_action)
		{
			case 'add_user':
//				alert('User saved');
				addNewLine(t.item_id, t.line_html);
				$('form_new_user').reset();
				$('div_new_user').hide();
				break;

			default:
				alert('No cmd_action specified');
				break;
		}
	}
}

function addNewLine(id, html)
{
	var tbl = $('tbl_user_list');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow);
	row.setAttribute("id", "tr_" + id);
//	alert("row.getAttribute: " + row.getAttribute("id"));
	row.innerHTML = html;
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Users &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$users|@count}</strong> record{if $users|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_user" name="add_new_user" value="Add New User" onclick="javascript:$('div_new_user').show();" />
						<div id="div_new_user" style="display: none; margin-top: 10px">
							<form id="form_new_user" name="form_new_user">
								<table class="ianlist">
									<tr>
										<th style="vertical-align: top; width: 20%">Name</th>
										<td style="vertical-align: top; width: 80%"><input type="text" id="name" maxlength="255" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Username</th>
										<td style="vertical-align: top; width: 80%"><input type="text" id="handle" maxlength="100" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Password</th>
										<td style="vertical-align: top; width: 80%"><input type="text" id="password" maxlength="32" style="width: 250px;" /></td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Active</th>
										<td style="vertical-align: top; width: 80%">
											<input type="checkbox" id="active" name="active" />
										</td>
									</tr>
									<tr>
										<th style="vertical-align: top; width: 20%">Client</th>
										<td style="vertical-align: top; width: 80%">
											<select name="client_id" id="client_id">
												
											</select>
										</td>
									</tr>
								</table>
								<div>
									<input type="button" id="cancel_user" name="cancel_user" value="Cancel" onclick="javascript:$('form_new_user').reset(); $('div_new_user').hide(); return false;" />&nbsp;
									<input type="button" id="reset_user"  name="reset_user"  value="Reset"  onclick="javascript:$('form_new_user').reset(); return false;" />&nbsp;
									<input type="button" id="save_user"   name="save_user"   value="Save"   onclick="javascript:saveUser();" />
								</div>
							</form>
						</div>
					</td>
				</tr>

				<tr valign="top">
					<td>
						<table id="tbl_user_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Name</th>
									<th>Username</th>
									<th>Active</th>
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>
					
							{foreach name=user_loop from=$users item=user}
							<tr id="tr_{$user->id}">
								<td style="text-align: center">{$user->id}</td>
								<td>{$user->name}</td>
								<td>{$user->handle}</td>
								<td style="text-align: center; vertical-align: middle">{if $user->is_active}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$user->id}" title="Edit" href="#" onclick="javascript:editUser({$user->id}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$user->id}" title="Delete" href="#" onclick="javascript:deleteUser({$user->id}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="yes" border="0" frameborder="no" style="height: 680px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

{include file="footer.tpl"}