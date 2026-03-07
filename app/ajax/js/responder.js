// JavaScript Document
/* Stack up window.onload events using this function from Simon Willison
 * http://www.sitepoint.com/blog-post-view.php?id=171578 */


function addLoadEvent(func)
{
	var oldonload = window.onload;
	if (typeof window.onload != 'function')
	{
		window.onload = func;
	}
	else
	{
		window.onload = function()
		{
			oldonload();
			func();
		}
	}
}

// set default value of 1 for the responder count as most javascript functions will only
// make one ajax call. This default value can be overridden from the parent js function by setting the 
// value of responders_total_count and responders_in_progress_count to something other than 1
var responders_total_count = 1;
var responders_in_progress_count = 1;
	
Ajax.Responders.register({
	onCreate: function()
	{
		if (top.$('notification') && Ajax.activeRequestCount > 0 && responders_total_count == responders_in_progress_count)
		{
			top.$('notification').innerHTML = '<img src="app/view/images/ajax_loader.gif" width="16" height="16" align="absmiddle">&nbsp;Working...';
			top.Effect.Appear('notification',{duration: 0.25, queue: 'end'});
		}
		responders_in_progress_count --;
	},
	onComplete: function()
	{
		if (top.$('notification') && Ajax.activeRequestCount == 0 && responders_in_progress_count <= 0)
		{
			setTimeout("responderFadeOut()", 250);
			// reset responders ready for next call
			responders_total_count = 1;
			responders_in_progress_count = 1;
		}
		else
		{
			responders_in_progress_count --;
		}
	}
});

function responderFadeOut()
{
	top.$('notification').innerHTML = '&nbsp;Done.';
	top.Effect.Fade('notification',{duration: 1.25, queue: 'end'});
	
}