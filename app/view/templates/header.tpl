{config_load file="`$LOCALE`.conf"}

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

	<link href="{$APP_URL}app/view/styles/general.css" rel="stylesheet" type="text/css" />

	<!-- VMware -->
	<link href="{$APP_URL}app/view/styles/vmware.css" rel="stylesheet" type="text/css" />
	<!-- /VMware -->
	<link href="{$APP_URL}app/view/js/popup/popup.css" rel="stylesheet" type="text/css" />
{*	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/default.css" />
	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/mac_os_x.css" />
*}
	<link href="{$APP_URL}app/view/styles/template.css" rel="stylesheet" type="text/css" />

	<link href="{$APP_URL}app/view/styles/rounded.css" rel="stylesheet" type="text/css" />

	<link href="{$APP_URL}app/view/styles/style.css" rel="stylesheet" type="text/css" />

	<script src="{$APP_URL}app/view/components/jquery/dist/jquery.min.js"></script>
	<script src="{$APP_URL}app/view/components/angular/angular.min.js"></script>
	<script src="{$APP_URL}app/view/js/script.js"></script>
	<script src="{$APP_URL}app/view/js/bundle.js"></script>

	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla/common.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla.javascript.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/menu.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/fat.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/index.js"></script>


	<!-- Illumen data collection -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/collection/ill_data_collection.js"></script>

	<script type="text/javascript" src="{$APP_URL}app/ajax/js/prototype.js"></script>

{*	<!--Prototype window classes-->
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/window.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/window_ext.js"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/effects.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/debug.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/extended_debug.js"></script>-->
*}
	<!-- Overlib -->
	{* popup_init must be called once at the top of the page *}
	{popup_init src="`$APP_URL`include/overlib/overlib.js"}
	<!-- /Overlib -->

	<!-- Ajax javascript -->
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/json.js"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/ajaxClient.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js?load=effects,controls,dragdrop"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/responder.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/effects.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/dragdrop.js?v=1"></script>
	<!-- End Ajax javascript -->

	<!-- Sorttable -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/sorttable/sorttable.js"></script>

	<!-- Popup -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/popup/popup.js"></script>

	<!-- Ajax css elements -->
	<!-- Notification bar css -->
	<!--<link href="{$APP_URL}app/ajax/styles/notification.css" rel="stylesheet" type="text/css">-->
	<!-- Scriptaculous effects css -->
	<!--<link href="{$APP_URL}app/ajax/styles/screen.css" rel="stylesheet" type="text/css">-->
	<!-- End Ajax css elements -->


<!-- -->
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.pack.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.slide.js"></script>
<!-- -->

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