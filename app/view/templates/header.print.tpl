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
	<meta name="robots" content="noindex,nofollow" />
	<meta name="keywords" content="alchemis" />
	<meta name="description" content="{$APP_NAME} - {$APP_DESCRIPTION}" />
	{*<link rel="shortcut icon" href="{$APP_URL}favicon.ico" />*}

	<link rel="stylesheet" type="text/css" media="all" href="{$APP_URL}app/view/styles/general.css" />
	<link rel="stylesheet" type="text/css" media="all" href="{$APP_URL}app/view/styles/print.css"/>

	
	<!--[if lte IE 6]>
	<link href="{$APP_URL}app/view/styles/ie.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	

</head>

