<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>{$APP_NAME} | Login</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="robots" content="noindex,nofollow" />
		<meta name="keywords" content="alchemis" />
		<meta name="description" content="{$APP_NAME} - industry leading prospect relationship management software" />
		<link href="{$APP_URL}app/view/styles/login.css" rel="stylesheet" type="text/css">

		<script language="JavaScript" type="text/javascript">
		</script>

	</head>

	<body>
		<div id="ctr" align="center">
			{if $feedbackString}
			<div class="error">
				<p>{$feedbackString}</p>
				<p>Please remember that passwords are case sensitive.</p>
				<p>Please check your login details and try again.</p>
			</div>
			{/if}
			<div class="login">
				<div class="login-form">
					<span class="logintitle">Login</span>

					<form class="loginForm" name="loginForm" action="index.php?cmd=Login" method="post">
						<input type="hidden" name="submitted" value="true" />
						<input type="hidden" name="redirect" value='{$redirect}' />
						<div class="form-block">
							<div class="inputlabel">Username</div>
							<div><input name="username" type="text" class="inputbox" size="15" value="{$username|escape}" /></div>
							<div class="inputlabel">Password</div>
							<div><input name="password" type="password" class="inputbox" size="15" value="" /></div>
							<div align="left"><input type="submit" name="go" class="button" value="Login" /></div>
						</div>
					</form>

				</div>
				<div class="login-text">
					<div class="ctr">
						<img src="{$APP_URL}app/view/images/keys.gif" width="48" height="48" alt="security" />
					</div>
					<p>Welcome to {$APP_NAME}</p>
					<p>Please use a valid username and password to gain access to the system. Unauthorised access is prohibited.</p>
					<p>{$APP_VERSION}</p>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<div id="break"></div>
		<noscript>!Warning! Javascript must be enabled for proper operation of this site</noscript>
		<div class="footer" align="center">
			<div align="center">Copyright &copy; 2006{if $smarty.now|date_format:"%Y" > 2006}&ndash;{$smarty.now|date_format:"%Y"}{/if} Alchemis Ltd. All rights reserved.</div>
		</div>
		<div id="break"></div>
	</body>
</html>