{include file="header.tpl" title="RBAC User View"}

<script language="javascript" type="text/javascript">
<!-- Hide script from old browsers
{literal}
	function submit_form() 
	{
		document.form1.submit();
		return true;
	}
{/literal}
// End hiding of script from old browsers -->
</script>

<table class="adminform">
	<tr>
		<td width="67%" valign="top">
		
			<div class="cfg">

				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td colspan="2">User Details</td>
					</tr>
					<tr>
						<td style="width: 50%; vertical-align: top">
							<table class="ianlist">
								<tr>
									<th style="width: 25%">ID</th>
									<td style="width: 75%">{$user->getId()}</td>
								</tr>
								<tr>
									<th style="width: 25%">Role</th>
									<td style="width: 75%">{$user->getHandle()}</td>
								</tr>
								<tr>
									<th style="width: 25%">Fullname</th>
									<td style="width: 75%">{$user->getHandle()}</td>
								</tr>
								<tr>
									<th style="width: 25%">Email</th>
									<td style="width: 75%">{$user->getHandle()}</td>
								</tr>
							</table>
						</td>
						<td style="width: 50%; vertical-align: top">
							<table class="ianlist">
								<tr>
									<th style="width: 25%; vertical-align: top">Active</th>
									<td style="width: 75%">
										<input type="checkbox" id="user_enabled" name="user_enabled"{if $user->isActive()} checked="checked"{/if} />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td>Roles</td>
					</tr>
					<tr valign="top">
						<td>

							<div id="add_role_1" style="display: inline">
								Add a role
							</div>
							
							<div id="add_role_2"  style="display: inline">
								<form name="form1" action="index.php" method="get">
									<input type="hidden" id="cmd" name="cmd" value="RbacUserView" />
									<input type="hidden" id="user_id" name="user_id" value="{$user->getId()}" />
									Role
									<select name="role_id">
										{html_options options=$available_roles}
									</select>
									
									<p class="submit">
										<input type="submit" value="Add this item" id="add_item_button_1205157" /> <span>or</span>
										<a class="admin" href="#" onclick="$(&quot;list_1205157_new_item&quot;).hide();
										$(&quot;link_to_add_child_list_1205157&quot;).show();; return false;">I'm done adding items</a>
									</p>
								</form>
							</div>
							
							<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
									<tr>
										<th style="width: 3%">#</th>
										<th style="width: 82%; text-align: center">Name</th>
										<th style="width: 15%; text-align: center">&nbsp;</th>
									</tr>
								</thead>
							</table>
							
							<br />
							
							<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
								<thead>
									<tr>
										<th style="width: 3%">#</th>
										<th style="width: 82%; text-align: center">Name</th>
										<th style="width: 15%; text-align: center">&nbsp;</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="3">
											<del class="container">
												<div class="pagination">
													<div class="limit">Display #
														<select name="limit" id="limit" class="inputbox" size="1" onchange="document.adminForm.submit();">
															<option value="5" >5</option>
															<option value="10" >10</option>
															<option value="15" >15</option><option value="20"  selected="selected">20</option>
															<option value="25" >25</option><option value="30" >30</option>
															<option value="50" >50</option><option value="100" >100</option>
														</select>
													</div>
													<div class="button2-right off">
														<div class="start"><span>Start</span></div>
													</div>
													<div class="button2-right off">
														<div class="prev"><span>Prev</span></div>
													</div>
													<div class="button2-left">
														<div class="page"><a title="1" onclick="javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;">1</a><a title="2" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">2</a><a title="3" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">3</a></div>
													</div>
													<div class="button2-left">
														<div class="next"><a title="Next" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">Next</a></div>
													</div>
													<div class="button2-left">
														<div class="end"><a title="End" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">End</a></div>
													</div>
													<div class="limit">page 1 of 3</div>
													<input type="hidden" name="limitstart" value="0" />
												</div>
											</del>
										</td>
									</tr>
								</tfoot>
								<tbody>
									{foreach name=role_loop from=$user->getRoles() item=role}
									<tr>
										<td>{$smarty.foreach.role_loop.iteration}</td>
										<td>{$role->getName()}</td>
										<td style="text-align: center; background-color: #F3F3F3">
											<div class="button2-left">
												<div class="page"><a id="viewBtn_{$role->getId()}" title="Details" href="index.php?cmd=RbacRoleView&amp;role_id={$role->getId()}">Details</a></div>
											</div>
											<div class="button2-left">
												<div class="page"><a id="removeBtn_{$role->getId()}" title="Remove" href="#{*index.php?cmd=RbacRoleView&amp;role_id={$role->getId()}*}">Remove</a></div>
											</div>
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
		<td width="33%" valign="top">

			<div class="cfg">

				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td>Permissions</td>
					</tr>
					<tr valign="top">
						<td style="padding-top: 3px">

							<div id="content-pane" class="pane-sliders">

								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Help</span></h3>
									<div class="moofx-slider content">
										<table class="adminlist">
											<tr>
												<td>
													<em>You can turn the display of Help and Advice off in your personal settings.</em>
												</td>
											</tr>
											<tr>
												<td>

			<h1>RBAC User View</h1>
			
			<p>This screen shows the roles that are asscoiated with a user.</p>
			
			<h3>Add a role</h3>
			
			<p>To add a role...</p>
			
			<h3>Remove a role</h3>
			
			<p>To remove a role...</p>
			
			<h3>Activate / Deactive a user</h3>
			
			<p><em>A user must be associated with at least one role.  This may be the Anonymous role.</em></p>
			
			<p><em>A user may be active or deactive. Only active users can use the system.</em></p>
			
			
			<p>By assigning a user to one or more <a href="#roles">roles</a>, the user can perfrom 
			actions on a <a href="#cmd">command</a>, according to the <a href="#permissions">permissions</a> 
			associated with those roles.</p>
			
			<p><strong>Unauthenticated users are given an explicit role name, which simply allows 
			the login action.</strong></p>
			
			
			<p><abbr title="Role-Based Access Control">RBAC</abbr> allows...</p>

			<ul>
				<li>Each person who is permitted to use the system is assigned a unique user account.</li>
				<li>The unique user name is usually an email address.</li>
			</ul>

			<p>Information stored as part of a user account includes:</p>

			<ul>
				<li>A user name which is unique across the system.</li>
				<li>An encrypted password.</li>
				<li>The user's real name.</li>
				<li>Whether the user is active or disabled. (Managed by an administrator.)</li>
				<li>One or more <a href="#roles">roles</a>, which determines the level of access the user has to the system.</li>
			</ul>



												</td>
											</tr>
										</table>
									</div>
								</div>

							</div>
							
							<script type="text/javascript">init_moofx();</script>

						</td>
					</tr>
				</table>
			</div>

		</td>
	</tr>
</table>

{include file="footer.tpl"}