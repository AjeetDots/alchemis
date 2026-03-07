{include file="header.tpl" title="Help RBAC"}

<table class="adminform">
	<tr>
		<td width="67%" valign="top">
			
			<h1>Role-Based Access Control (RBAC)</h1>
			


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

<script type="text/javascript">init_moofx();</script>

{include file="footer.tpl"}