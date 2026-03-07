{config_load file="`$LOCALE`.conf"}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
	<title>{$APP_NAME}{if $title} | {$title}{/if}</title>
	{assign var='use_utf8' value='true'}
	{if $use_utf8 == 'true'}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{else}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	{/if}
	<meta name="robots" content="noindex,nofollow" /
	<meta name="keywords" content="alchemis" />
	<meta name="description" content="{$APP_NAME} - {$APP_DESCRIPTION}" />
  <meta name="referrer" content="no-referrer" />
	{*<link rel="shortcut icon" href="{$APP_URL}favicon.ico" />*}
	
	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla/common.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla.javascript.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/menu.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/fat.js"></script>


	<!-- Illumen data collection -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/collection/ill_data_collection.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/prototype.js"></script>
	
	<!--<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/default.css" /> 
	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/mac_os_x.css" />-->
	
	<!-- Sorttable -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/sorttable/sorttable.js"></script>
	
	<!-- Ajax javascript -->
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/json.js"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/ajaxClient.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js?load=effects,controls,dragdrop"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js"></script>
	<!-- End Ajax javascript -->

	<!-- Ajax css elements -->
	<!-- Notification bar css -->
	<link href="{$APP_URL}app/ajax/styles/notification.css" rel="stylesheet" type="text/css">
	<!-- Scriptaculous effects css -->
	<!--<link href="{$APP_URL}app/ajax/styles/screen.css" rel="stylesheet" type="text/css">-->
	<!-- End Ajax css elements -->
	
	<link href="{$APP_URL}app/view/styles/template.css" rel="stylesheet" type="text/css" />
	
	<link href="{$APP_URL}app/view/styles/rounded.css" rel="stylesheet" type="text/css" />

<!-- -->
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.pack.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.slide.js"></script>
<!-- -->

	<!-- VMware -->
	<link href="{$APP_URL}app/view/styles/vmware.css" rel="stylesheet" type="text/css" />
	<!-- /VMware -->

	<!-- subModal -->
	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/subModal/subModal.css" />
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/subModal/common.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/subModal/subModal.js"></script>
	<!-- /subModal -->

	
{literal}
	<script language="JavaScript" type="text/javascript">
	</script>
{/literal}
</head>


