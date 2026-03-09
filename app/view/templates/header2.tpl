{config_load file="`$LOCALE`.conf"}
{strip}
<!DOCTYPE html>

<html lang="en-gb" dir="ltr" ng-app="alchemis">
<head>
	<title>{$APP_NAME}{if $title} | {$title}{/if}</title>
	{assign var='use_utf8' value='true'}
	{if $use_utf8 == 'true'}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{else}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	{/if}
	<meta http-equiv="Expires" content="Sat, 1 Jan 2000 08:00:00 GMT" />

	<meta name="robots" content="noindex,nofollow" />
	<meta name="keywords" content="alchemis" />
	<meta name="description" content="{$APP_NAME} - {$APP_DESCRIPTION}" />
  <meta name="referrer" content="no-referrer" />
	{*<link rel="shortcut icon" href="{$APP_URL}favicon.ico" />*}

	{popup_init src="`$APP_URL`app/view/js/overlib/Mini/overlib_mini.js"}

	{*<link href="{$APP_URL}app/view/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />*}
	<link href="{$APP_URL}app/view/styles/general.css" rel="stylesheet" type="text/css" />
	<link href="{$APP_URL}app/view/js/popup/popup.css" rel="stylesheet" type="text/css" />
	<!-- VMware -->
	<link href="{$APP_URL}app/view/styles/vmware.css" rel="stylesheet" type="text/css" />
	<!-- /VMware -->

	<link href="{$APP_URL}app/view/styles/style.css" rel="stylesheet" type="text/css" />

	<script src="{$APP_URL}app/view/components/jquery/dist/jquery.min.js"></script>
	<script src="{$APP_URL}app/view/components/angular/angular.min.js"></script>
	<script src="{$APP_URL}app/view/js/script.js"></script>
	<script src="{$APP_URL}app/view/js/bundle.js"></script>

	<script type="text/javascript" src="{$APP_URL}app/view/js/ListItems.js"></script>

	<!-- Illumen data collection -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/collection/ill_data_collection.js"></script>

	<script type="text/javascript" src="{$APP_URL}app/ajax/js/prototype.js"></script>

	<!-- Sorttable -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/sorttable/sorttable.js"></script>

	<!-- Overlib -->
	{* popup_init must be called once at the top of the page *}
	{*{popup_init src="`$APP_URL`include/overlib/overlib.js"}*}
	<!-- /Overlib -->

	<!-- Ajax javascript -->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/ajaxClient.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/responder.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/effects.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/dragdrop.js?v=1"></script>
	<!-- End Ajax javascript -->

	<!-- Popup -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/popup/popup.js"></script>


<!--  -->
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.pack.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.slide.js"></script>
<!--  -->

<script language="JavaScript" type="text/javascript">
{literal}
function init()
{
	top.responderFadeOut();
}
{/literal}
</script>

</head>

<body onload="javascript:init()">


{if isset($feedback) && $feedback}
<dl id="system-message" class="message fade">
	<dt class="message">{#MESSAGE#}</dt>
	<dd class="message">
		<ul>
			<li>{$feedback}</li>
		</ul>
	</dd>
</dl>
{/if}
{/strip}
