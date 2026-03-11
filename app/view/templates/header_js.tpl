{config_load file="`$LOCALE`.conf"}

<!DOCTYPE html>

<html lang="en-gb" dir="ltr" >
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
	<meta http-equiv="Permissions-Policy" content="unload=(self)" />
	<link rel="shortcut icon" href="{$APP_URL}favicon.ico" />
	
	<link href="{$APP_URL}app/ajax/styles/notification.css" rel="stylesheet" type="text/css">
	<link href="{$APP_URL}app/view/styles/template.css" rel="stylesheet" type="text/css" />
	<link href="{$APP_URL}app/view/styles/rounded.css" rel="stylesheet" type="text/css" />
	<!-- VMware -->
	<link href="{$APP_URL}app/view/styles/vmware.css" rel="stylesheet" type="text/css" />
	<!-- /VMware -->
	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/subModal/subModal.css" />

	<script src="{$APP_URL}app/view/components/jquery/dist/jquery.min.js"></script>
	<script src="{$APP_URL}app/view/components/angular/angular.min.js"></script>
	<script src="{$APP_URL}app/view/js/script.js"></script>
	<script src="{$APP_URL}app/view/js/bundle.js"></script>
	
	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla/common.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/js/joomla.javascript.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/menu.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/fat.js"></script>
{*	<script type="text/javascript" src="{$APP_URL}app/view/khepri/js/index.js"></script>*}

	<!-- Illumen data collection -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/collection/ill_data_collection.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/prototype.js"></script>
	
	{*
	<!--Prototype window classes-->
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/window.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/window_ext.js"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/effects.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/debug.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/prototypeWindow/extended_debug.js"></script>-->
	*}
	
	<!--<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/default.css" /> 
	<link rel="stylesheet" type="text/css" href="{$APP_URL}app/view/javascript/prototypeWindow/themes/mac_os_x.css" />-->

	<!-- Overlib -->
	{* popup_init must be called once at the top of the page *}
	{*{popup_init src="`$APP_URL`include/overlib/overlib.js"}*}
	<!-- /Overlib -->
	
	<!-- Sorttable -->
	<script type="text/javascript" src="{$APP_URL}app/view/js/sorttable/sorttable.js"></script>
	
	<!-- Ajax javascript -->
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/json.js"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/ajaxClient.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js?load=effects,controls,dragdrop"></script>-->
	<script type="text/javascript" src="{$APP_URL}app/ajax/js/scriptaculous.js"></script>
	<!--<script type="text/javascript" src="{$APP_URL}app/ajax/js/responder.js"></script>-->
	<!-- End Ajax javascript -->


<!-- -->
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.pack.js"></script>
<script type="text/javascript" src="{$APP_URL}app/view/js/moofx/moo.fx.slide.js"></script>
<!-- -->


	<!-- subModal -->
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/subModal/common.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/javascript/subModal/subModal.js"></script>
	<!-- /subModal -->

	
{literal}
	<script language="JavaScript" type="text/javascript">
	
	var popupWindow = null;
	
	var callBackCount = 0;
	
	// global vars
	var current_tab_id;
	// hold scroll positions currently is use on tab iframes
	var scroll_positions = new Array(12);
	
	monitorCallBacks();
			
	function showScoreboard()
	{
		scoreboardWindow = window.open('index.php?cmd=Scoreboard', 'Scoreboard', 'width=600,height=650,toolbar=no,scrollbars=yes');
		scoreboardWindow.focus();
		event.cancelBubble = true;
	}
    
	function showCallBacks()
	{
		
		if (popupWindow && !popupWindow.closed) {
			popupWindow.focus();
		} else {
			popupWindow = window.open('index.php?cmd=TimedCallBacks', 'CallBacks', 'width=600,height=650,toolbar=no,scrollbars=yes');
		}
		      
		if (typeof event != 'undefined') {
			event.cancelBubble = true;
		}

	}
	
	function refreshCallBackPopup() {
		
//		console.log('in refreshCallBackPopup');
		if (popupWindow && !popupWindow.closed) {
//			console.log('about to refresh popup window href');
iframeLocation(			popupWindow, 'index.php?cmd=TimedCallBacks');
			popupWindow.focus();
		} 
	}
	function monitorCallBacks()
	{
	
//		console.log('in monitorCallBacks');
//		console.log('callBackCount = ' + callBackCount);
		var ill_params = new Object;
		ill_params['callBackCount'] = callBackCount;
		callBackCount ++;
		
		getAjaxData("AjaxDashboard", "", "call_backs_due_in_interval", ill_params, "Saving...");
		setTimeout("monitorCallBacks()", 600000);
//		console.log('After AJAX call');

	}

	function AjaxDashboard(data)
	{
		for (i = 1; i < data.length + 1; i++) 
		{
			t = data[i-1];
			
			switch (t.cmd_action)
			{
				case "call_backs_due_in_interval":
//					console.log('callback_count: ' + t.callback_count);
//					console.log('callBackCount: ' + t.callBackCount);
//					console.log('session_obj: ' + t.session_obj);
//					console.log('date: ' + t.date);
//					console.log('next_date: ' + t.next_date);
 
					if (t.callback_count > 0) {
						showCallBacks();
					}
					break;
				default:
					alert("No cmd_action specified");
					break;
			}
		}
	}
	
	function loadTab(tabToShow, UrlToLoad, forceLoad)
	{
		
		// check if a communication is loaded
		if (communication_loaded)
		{
			var x = confirm("You have a communication screen loaded - do you want to move away?");
			if (x)
			{
				communication_loaded = false;
			}
			else
			{
				return;
			}
		}
		
		//deal with saving scroll positions - if appropriate
		if(scroll_positions[current_tab_id-1]) //means we must have something in the scroll_positions array
		{
			top.frames[current_tab_id - 1].saveScrollPositions(current_tab_id - 1);
		}
		else
		{
			// do nothing
		}

		//enter number of tabs
		for (x=1;x<=12;x++)
  		{
  			var iframe_element = $('iframe_' + x);
  			var refresh_element = $('ref_' + x);
  			var tab_element = $('tab_' + x);
  			
  			if (iframe_element)
  			{
	  			if (x == tabToShow)
	  			{
					// set scroll positions on newly show screen
					if (scroll_positions[tabToShow-1]) //means we must have something in the scroll_postions array
					{
						top.frames[tabToShow - 1].setScrollPositions(tabToShow-1);
					}
					else
					{
						// do nothing
					}
					
					// positioning of these two lines is important - DO NOT JUST MOVE THEM!
					iframe_element.style.display = 'inline';
	 				refresh_element.style.display = 'inline';
	 				
					//in this section we do any tab specific actions 
					switch (tabToShow)
					{
						case 6: //search results
						var frame = iframeWindow(top.iframe_6);
							if (frame.iframe1 != undefined)
							{
								if (frame.iframe1.page_isloaded && frame.iframe1.colln != undefined)
								{
									var obj = frame.iframe1.colln.getCurrent();
									if (obj.post_initiative_id) {
										var post_initiative_id = obj.post_initiative_id;
									} else {
										var post_initiative_id = '';
									}
									var post_id = obj.post_id;
									var company_id = obj.company_id;
									if (post_initiative_id != "")
									{
										frame.iframe1.goToHash("tr_post_initiative_" + post_initiative_id);
									}
									else if (post_id != "")
									{
										// if we have a hidden post list then need to show it before we can view it using location.hash
										if (frame.iframe1.$("post_list_" + company_id) != undefined)
										{
											//alert("Here");
											frame.iframe1.$("post_list_" + company_id).show();
										}
										frame.iframe1.goToHash("tr_post_" + post_id);
									}
									else
									{
										frame.iframe1.goToHash("tr_" + company_id);
									}
									frame.iframe1.highlightSelectedRow(company_id, post_id, post_initiative_id);
								}
							}
							break;
							
						case 8: //filter results
							var frame = iframeWindow(top.iframe_8); 
							if (frame.page_isloaded && frame.colln != undefined)
							{
								var obj = frame.colln.getCurrent();
								console.log(obj);
								if (obj.post_initiative_id) {
									var post_initiative_id = obj.post_initiative_id;
								} else {
									var post_initiative_id = '';
								}
								var post_id = obj.post_id;
								var company_id = obj.company_id;
								
								if (post_initiative_id != "")
								{
									frame.goToHash("tr_post_initiative_" + post_initiative_id);
								}
								else if (post_id != "")
								{
									// if we have a hidden post list then need to show it before we can view it using location.hash
									if (frame.$("post_list_" + company_id) != undefined)
									{
										//alert("Here");
										frame.$("post_list_" + company_id).show();
									}
									frame.goToHash("tr_post_" + post_id);
								}
								else
								{
									frame.goToHash("tr_" + company_id);
								}
								frame.highlightSelectedRow(company_id, post_id, post_initiative_id);
								
							}
							break;
						default:
							break;	
					}
	 				tab_element.className = 'TabSlct';
	 				
	 				//store current iframe id - may need to know which iframe is currently on view
	  				current_tab_id = x;
	  			}
	  			else
	  			{
					iframe_element.style.display = 'none';
					if (refresh_element)
					{
						refresh_element.style.display = 'none';
					}
					tab_element.className = 'TabDsbl';
				}
			}
		}

  		if (UrlToLoad != "")
  		{
			// Note: we check the frame src here because this property is checked when the user clicks
			// on the WorkspaceSearch tab. If its still set to index.php?cmd=Home then we assume that the
			// target tab has no content we we try and load some - ie the UrlToLoad input parameter
			if (! tab_colln.goToValue(tabToShow))
			{
				responderFadeIn();
				$("iframe_" + tabToShow).src = "index.php?cmd=" + UrlToLoad;
			}
			else
			{
				if (forceLoad)
				{
					responderFadeIn();
					$("iframe_" + tabToShow).src = "index.php?cmd=" + UrlToLoad;
				}
			}
			
		}
	}
	
	function refreshTab(tabToShow)
	{
		
		responderFadeIn();
		var currFrame = "iframe_" + tabToShow;
		iframeReload(top.frames[currFrame]);
	}
	
	function responderFadeIn()
	{
		if (top != null) {
			var notification = $('notification');
			if (!notification) {
				return;
			}
			notification.innerHTML = '<img src="app/view/images/ajax_loader.gif" width="16" height="16" align="absmiddle">&nbsp;Working...';
			Effect.Appear('notification',{duration: 0.25, queue: 'end'});
		}
	}
	
	function responderFadeOut()
	{
		if (top != null) {
			var notification = $('notification');
			if (!notification) {
				return;
			}
			notification.innerHTML = '&nbsp;Done.';
			Effect.Fade('notification',{duration: 1.25, queue: 'end'});
		}
	}	
	
	function left(str, n)
	{
		if (n <= 0)
		    return "";
		else if (n > String(str).length)
		    return str;
		else
		    return String(str).substring(0,n);
	}
	
	function right(str, n)
	{
	    if (n <= 0)
	       return "";
	    else if (n > String(str).length)
	       return str;
	    else {
	       var iLen = String(str).length;
	       return String(str).substring(iLen, iLen - n);
	    }
	}

	</script>
{/literal}
</head>


