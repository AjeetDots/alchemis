// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
var tab_id = 7;

if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(tab_id))
{
	parent.tab_colln.add(tab_id);
}

// need this next var to indicate that the parent frame should check for div scrolling positions to be restored. Has to be variable
// since we can't check for the presence of function if this window is not yet loaded - javascript just falls over. If the window isn't loaded then we can
// check for the variable from the Home page and 'undefined' is returned.
top.parent.scroll_positions[6] = "true";

// use the following array to hold the ids of the divs we want to retain scrollings positions for
//var divs_with_scroll = ['div_notes_screen'];
var divs_with_scroll = [];

function saveScrollPositions(array_pos)
{
//	alert("in saveScrollPositions(" + array_pos + ")");
	var strTemp = "";
	
	
/*	// use the following section of javascript if need to save scroll positions for all divs on this page

	//NOTE: may need to adjust the following line to point at the relevant location of the div to save - eg it may be within a child iframe
	var myDivs = document.getElementsByTagName("div");
    for(var i = 0; i < myDivs.length; i++)
    {
    	if(myDivs[i].id.length > 0 && myDivs[i].scrollTop > 0)
        {
        	strTemp += myDivs[i].id + "=" + myDivs[i].scrollTop + ";"; 
		}
	}*/
	
//	alert(divs_with_scroll.length);
	
    for(var i = 0; i < divs_with_scroll.length; i++)
    {
    	//NOTE: may need to adjust the following line to point at the relevant location of the div to save - eg it may be within a child iframe
    	var div = $(divs_with_scroll[i]);
//   	alert("div.id = " + div.id);
    	if(div.id.length > 0)
        {
        	strTemp += div.id + "=" + div.scrollTop + ";"; 
		}
	}
	
//	alert("strTemp = " + strTemp);
	
	if (strTemp != "")
	{
		top.scroll_positions[array_pos] = strTemp;
	}
	
//	alert("top.scroll_positions[" + array_pos + "] = " + top.scroll_positions[array_pos]);
}

function setScrollPositions(array_pos)
{
//	alert("in setScrollPositions(" + array_pos + ")");
	var strDivScroll = top.scroll_positions[array_pos];
//	alert("strDivScroll = " + strDivScroll);
	if(strDivScroll.length > 0)
    {
//  	alert("strDivScroll.search(/;/i) = " + strDivScroll.search(/;/i));
        	
    	if (strDivScroll.search(/;/i) > 0)
    	{
    		var item = strDivScroll.split(";");
//	   	    alert("item = " + item);
    	    for(var i = 0; i < item.length; i++)
	        {
    		    var mySplit = item[i].split("=");
        		try
        		{
        			//alert("mySplit[0] = " + mySplit[0] + " : mySplit[1] = " + mySplit[1]);
            		if (mySplit[1] > 0)
    	    		{
//   	    			alert("setting " + mySplit[0] + " to " + mySplit[1]);
						//NOTE: need to adjust the following line to point at the relevant location of the div to save - eg it may be within a child iframe
    	        		document.getElementById(mySplit[0].replace(" ", "")).scrollTop = mySplit[1];
    	    		}
    			}
    			catch(e)
    			{	
    			}
    		}
		}
	}
}

function goNextCompanyId()
{
	colln = parent.iframe_8.contentWindow.colln;
	var t = colln.getCurrent();
	var current_id = t.company_id;
	do
	{
		var t = colln.getNext();
	}
	while (t.company_id == current_id);
	
	if (t === false)
	{
		alert("Reached end of recordset");
	}
	else
	{
		//alert("colln - t.company_id = " + t.company_id + " : t.post_id = " + t.post_id);
		if (t.post_initiative_id == "")
		{
			post_initiative_id = null;
		}
		else
		{
			post_initiative_id = t.post_initiative_id;
		}
		responders_total_count = 5;
		responders_in_progress_count = 5;
		getCompanyDetail(t.company_id, t.post_id, post_initiative_id);
	}
}

function goPreviousCompanyId()
{
	colln = parent.iframe_8.contentWindow.colln;
	var t = colln.getCurrent();
	var current_id = t.company_id;
	do
	{
		var t = colln.getPrevious();
	}
	while (t.company_id == current_id);
	
	if (t === false)
	{
		alert("Reached beginning of recordset");
	}
	else
	{
		//alert("t.company_id = " + t.company_id);
		if (t.post_initiative_id == "")
		{
			post_initiative_id = null;
		}
		else
		{
			post_initiative_id = t.post_initiative_id;
		}
		responders_total_count = 5;
		responders_in_progress_count = 5;
		getCompanyDetail(t.company_id, t.post_id, post_initiative_id);
	}
}

function goNextPostId()
{
	colln = parent.iframe_8.contentWindow.colln;
	var t = colln.getCurrent();
	var current_id = t.post_id;
	exit_loop = false;
	do
	{
		var t = colln.getNext();
		if (t.post_id != current_id && t.post_id != "")
		{
			exit_loop = true;
		}
	}
	while (exit_loop == false);
	
	if (t === false)
	{
		alert("Reached end of recordset");
	}
	else
	{
		// alert("colln - t.company_id = " + t.company_id + " : t.post_id = " + t.post_id + " : t.post_initiative_id = " + t.post_initiative_id);
		if (t.post_initiative_id == "")
		{
			post_initiative_id = null;
		}
		else
		{
			post_initiative_id = t.post_initiative_id;
		}
		responders_total_count = 5;
		responders_in_progress_count = 5;
		getCompanyDetail(t.company_id, t.post_id, post_initiative_id);
	}
}

function goPreviousPostId()
{
	colln = parent.iframe_8.contentWindow.colln;
	var t = colln.getCurrent();
	var current_id = t.post_id;
	exit_loop = false;
	do
	{
		var t = colln.getPrevious();
		if (t.post_id != current_id && t.post_id != "")
		{
			exit_loop = true;
		}
	}
	while (exit_loop == false);
	
	if (t === false)
	{
		alert("Reached beginning of recordset");
	}
	else
	{
		//alert("colln - t.company_id = " + t.company_id + " : t.post_id = " + t.post_id + " : t.post_initiative_id = " + t.post_initiative_id);
		if (t.post_initiative_id == "")
		{
			post_initiative_id = null;
		}
		else
		{
			post_initiative_id = t.post_initiative_id;
		}
		responders_total_count = 5;
		responders_in_progress_count = 5;
		getCompanyDetail(t.company_id, t.post_id, post_initiative_id);
	}
}

function updateCount () {
	// do nothing
}