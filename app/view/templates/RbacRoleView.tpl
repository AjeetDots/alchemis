{include file="header.tpl" title="RBAC Role View"}

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
						<td colspan="2">Role Details</td>
					</tr>
					<tr>
						<td style="width: 50%; vertical-align: top">
							<table class="ianlist">
								<tr>
									<th style="width: 25%">ID</th>
									<td style="width: 75%">{$role->getId()}</td>
								</tr>
								<tr>
									<th style="width: 25%">Role</th>
									<td style="width: 75%">{$role->getName()}</td>
								</tr>
							</table>
						</td>
						<td style="width: 50%; vertical-align: top">
							<table class="ianlist">
								<tr>
									<th style="width: 25%; vertical-align: top">Command</th>
									<td style="width: 75%">
										<form name="form1" action="index.php?cmd=RbacRoleView" method="GET">
											<input type="hidden" id="cmd" name="cmd" value="RbacRoleView" />
											<input type="hidden" id="role_id" name="role_id" value="{$role->getId()}" />
											<select name="command_id" style="width: 100%" onchange="javascript:submit_form();">
												{html_options options=$commands selected=$command_id}
											</select>
										</form>
									</td>
								</tr>
								<tr>
									<th style="width: 30%; vertical-align: top">Permissions</th>
									<td style="width: 70%">
									{foreach name=perm from=$permissions item=permission}
										<input type="checkbox" name="permission_{$permission->getId()}" checked="checked" /> {$permission->getName()}<br />
									{/foreach}
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td>Users</td>
					</tr>
					<tr valign="top">
						<td>

							<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
								<thead>
									<tr>
										<th style="width: 3%">#</th>
										<th style="width: 52%; text-align: center">Handle</th>
										<th style="width: 20%; text-align: center">Last Login</th>
										<th style="width: 10%; text-align: center">ID</th>
										<th style="width: 15%; text-align: center">&nbsp;</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="5"></td>
									</tr>
								</tfoot>
								<tbody>
									{foreach name=usr from=$role->getUsers() item=user}
									<tr>
										<td>{$smarty.foreach.usr.iteration}</td>
										<td>{$user->getHandle()}</td>
										<td>{$user->getLastLogin()}</td>
										<td>{$user->getId()}</td>
										<td style="text-align: center; background-color: #F3F3F3">
											<div class="button2-left">
												<div class="page"><a id="viewBtn_{$command}" title="Details" href="index.php?cmd=RbacRoleView&amp;role_id={$role->getId()}">Details</a></div>
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


			<h1>RBAC Role View</h1>
			
			<a id="cmd"></a>
			<h2>Commands</h2>
			
			<p>Commands inherit from the class <strong>app_command_Command</strong>. Commands are executed by the 
			<strong>app_controller_Controller</strong> and return should return a status (e.g. 'CMD_OK' and 'CMD_ERROR'). 
			The status controls any forward navigation through the system.</p>
			
			<p>Each command must have one or more <a href="#permissions">permissions</a> associated with it.</p>
			
			<p>In the simple case a permission maybe <strong>read</strong> and which is granted to the 
			<strong>anonymous</strong> role.</p>


			<a id="permissions"></a>
			<h2>Permissions</h2>

			<p>Permissions are defined on a <a href="#cmd">command</a> and only make sense in the 
			context of that <a href="#cmd">command</a>.</p>


			<a id="roles"></a>
			<h2>Roles</h2>

			<p>Roles are a way of grouping command-permission associations for ease of maintenance. 
			Roles can then be assigned to users.</p>
			
			<p>Roles determine which <a href="#cmd">command</a> and <a href="#task">tasks</a> the user can perform.</p>
			
{*			<p>Where a task is not specified, it is equivalent to specifying <strong>task=all</strong>. [<span {popup text="Would this be safer 
			inversed? I.e. if not specified then assume <strong>task=none</strong>."}>OVERLIB</span>]
*}
			<p>Roles are named to ease management. Example roles in this system might be 
			<em>NBM</em> and <em>Client Services Director</em>.</p>
			
			<p>Roles can include / exclude the right to use each permission-command relationship.</p>



			<a id="users"></a>
			<h2>Users</h2>
			
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