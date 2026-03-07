{include file="header2.tpl" title="Edit User"}

{if $success}

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=User';
	</script>

{else}

<form action="index.php?cmd=UserEdit" method="post">

	<input type="hidden" id="user_id" name="user_id" value="{$user->getId()}" />

	<h2>User: {$user->getId()} {$user->getName()}</h2>
	
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%"{if $errors.app_domain_RbacUser_name} class="key_error" title="{$errors.app_domain_RbacUser_name->getTip()}"{/if}>Name</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="app_domain_RbacUser_name" name="app_domain_RbacUser_name" value="{$app_domain_RbacUser_name}" maxlength="255" style="width: 250px" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%"{if $errors.app_domain_RbacUser_handle} class="key_error" title="{$errors.app_domain_RbacUser_handle->getTip()}"{/if}>Username</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="app_domain_RbacUser_handle" name="app_domain_RbacUser_handle" value="{$app_domain_RbacUser_handle}" maxlength="100" style="width: 250px" />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%"{if $errors.app_domain_RbacUser_email} class="key_error" title="{$errors.app_domain_RbacUser_email->getTip()}"{/if}>Email</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="app_domain_RbacUser_email" name="app_domain_RbacUser_email" value="{$app_domain_RbacUser_email}" maxlength="100" style="width: 250px" />
			</td>
		</tr>
	</table>

	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Change Password?</th>
			<td style="vertical-align: top; width: 80%">
				<input type="checkbox" id="chk_change_password" name="chk_change_password" onchange="new Effect.toggle($('div_password'), 'blind', {literal}{duration: 0.3}{/literal}); return false;" />
			</td>
		</tr>
	</table>
	<div id="div_password" style="display: none; padding: 0px">
		<table class="ianlist" style="border-collapse: collapse; padding: 0px">
			<tr>
				<th style="vertical-align: top; width: 20%"{if $errors.app_domain_RbacUser_password} class="key_error" title="{$errors.app_domain_RbacUser_password->getTip()}"{/if}>Password</th>
				<td><input type="text" id="app_domain_RbacUser_password" name="app_domain_RbacUser_password" value="{$app_domain_RbacUser_password}" maxlength="32" style="width: 250px" /></td>
			</tr>
		</table>
	</div>
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Active</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" id="app_domain_RbacUser_is_active" name="app_domain_RbacUser_is_active"{if $app_domain_RbacUser_is_active} checked="checked"{/if} />
			</td>
		</tr>
	</table>
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Client</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<select id="app_domain_RbacUser_client_id" name="app_domain_RbacUser_client_id">
					<option selected value="0">&mdash; Alchemis User &mdash;</option>
					{foreach name="result_loop" from=$clients item=client}
						<option value="{$client.id}" {if $app_domain_RbacUser_client_id == $client.id}selected{/if}>{$client.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
	
	<h3>Permissions</h3>
	
	<table class="ianlist">
		{*<tr>
			<th style="vertical-align: top; width: 20%">Add Call Name</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_add_call_name"{if $user->permission_add_call_name} checked="checked"{/if} />
			</td>
		</tr>*}
		<tr>
			<th style="vertical-align: top; width: 20%">Add Client Record</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_add_client_record"{if $user->permission_add_client_record} checked="checked"{/if} />
			</td>
		</tr>
		{*<tr>
			<th style="vertical-align: top; width: 20%">Add Company Site</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_add_company_site"{if $user->permission_add_company_site} checked="checked"{/if} />
			</td>
		</tr>*}
		<tr>
			<th style="vertical-align: top; width: 20%">Add Notes</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_add_notes"{if $user->permission_add_notes} checked="checked"{/if} />
			</td>
		</tr>
			<tr>
			<th style="vertical-align: top; width: 20%">Add Bulk References</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_add_bulk_ref"{if $user->permission_add_bulk_ref} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Change Location</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_change_location"{if $user->permission_change_location} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Change Occupier</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_change_occupier"{if $user->permission_change_occupier} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Create Post</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_create_post"{if $user->permission_create_post} checked="checked"{/if} />
			</td>
		</tr>
		{*<tr>
			<th style="vertical-align: top; width: 20%">Dedupe Posts</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_dedupe_posts"{if $user->permission_dedupe_posts} checked="checked"{/if} />
			</td>
		</tr>*}
		<tr>
			<th style="vertical-align: top; width: 20%">Delete Client</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_delete_client"{if $user->permission_delete_client} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Delete Company</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_delete_company"{if $user->permission_delete_company} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Delete Last Call</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_delete_last_call"{if $user->permission_delete_last_call} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Delete Post</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_delete_post"{if $user->permission_delete_post} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Edit Client Record</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_edit_client_record"{if $user->permission_edit_client_record} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Edit Company Record</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_edit_company_record"{if $user->permission_edit_company_record} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Edit Company Site</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_edit_company_site"{if $user->permission_edit_company_site} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Edit Post Record</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_edit_post_record"{if $user->permission_edit_post_record} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Email to Prospect</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_email_to_prospect"{if $user->permission_email_to_prospect} checked="checked"{/if} />
			</td>
		</tr>
		{*<tr>
			<th style="vertical-align: top; width: 20%">Maintain Agencies</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_maintain_agencies"{if $user->permission_maintain_agencies} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Maintain Review Dates</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_maintain_review_dates"{if $user->permission_maintain_review_dates} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Move Client</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_move_client"{if $user->permission_move_client} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Undelete Post</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_undelete_posts"{if $user->permission_undelete_posts} checked="checked"{/if} />
			</td>
		</tr>*}
		<tr>
			<th style="vertical-align: top; width: 20%">View Global Calendar</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_view_global_calendar"{if $user->permission_view_global_calendar} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Delete/Restore Filters</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_deleted_restored_filters"{if $user->permission_deleted_restored_filters} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Bypass IP Whitelist</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_bypass_whitelist"{if $user->permission_bypass_whitelist} checked="checked"{/if} />
			</td>
		</tr>
	</table>
	
	<h3>Admin Permissions</h3>
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Users</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_users"{if $user->permission_admin_users} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Messages</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_messages"{if $user->permission_admin_messages} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Nbm Teams</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_nbm_teams"{if $user->permission_admin_nbm_teams} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Teams</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_teams"{if $user->permission_admin_teams} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Client Management by NBM</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_clients_nbm_admin"{if $user->permission_admin_clients_nbm_admin} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Client Campaigns</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_client_campaigns"{if $user->permission_admin_client_campaigns} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin NBM Monthly Planner</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_nbm_monthly_planner"{if $user->permission_admin_nbm_monthly_planner} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Characteristics</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_characteristics"{if $user->permission_admin_characteristics} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Regions</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_regions"{if $user->permission_admin_regions} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Reports</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_reports"{if $user->permission_admin_reports} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin IP Whitelist</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_whitelist"{if $user->permission_admin_whitelist} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Admin Postcodes</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="checkbox" name="permission_admin_postcode"{if $user->permission_admin_postcode} checked="checked"{/if} />
			</td>
		</tr>

	</table>

	<input type="hidden" id="task" name="task" value="save" />
	<input type="submit" value="Save" onclick="removeElementContainer();" />
	
</form>

{/if}

{include file="footer2.tpl"}