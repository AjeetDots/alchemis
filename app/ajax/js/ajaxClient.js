// in the following function, the synchronous parameter is optional. If passed in (eg as true) then the ajax xall is made synchronously.
function getAjaxData(cmd, action, cmd_action, parameters, message, synchronous, done)
{
//	alert(cmd_action);

	if (cmd == "" || cmd == null || cmd == undefined)
	{
		alert("Exiting function as no command submitted");
	}

	if (action == "" || action == null || action == undefined)
	{
		var action = 'post';
	}

	if (cmd_action == "" || cmd_action == null || cmd_action == undefined)
	{
		alert("Exiting function as no cmd_action submitted");
	}

	if (message == "" || message == null || message == undefined)
	{
		var message = 'Processing...';
	}

	//add cmd_action into the params object so that we can access it on the server side
	parameters['cmd_action'] = cmd_action;

	doAjaxRequest(cmd, action, cmd_action, parameters, synchronous, done)
}

function doAjaxRequest(cmd, action, cmd_action, params, synchronous, done)
{
//	alert("params = " + params);
	if (params == 'undefined')
	{
		alert(" No params defined");
		return;
	}
	var jsonRequest = Object.toJSON(params);
//	alert(jsonRequest);
//	alert(encodeURIComponent(jsonRequest));
//	alert("cmd = " + cmd + "\naction = " + action + "\ncmd_action = " + cmd_action + "\nparams = " + jsonRequest);

//	var url = 'http://localhost/index.php?cmd=' + cmd;
	var url = window.location.pathname + '?cmd=' + cmd;

	if (synchronous)
	{
		var myAjax = new Ajax.Request(url, {method: action,
    	                             	parameters: 'ajaxRequest='+ encodeURIComponent (jsonRequest),
    	                             	command: cmd,
    	                             	command_action: cmd_action,
    	                             	asynchronous: false,
                                   		onSuccess: function (response, json, cmd, cmd_action) {
                                   			onSuccess(response, json, cmd, cmd_action);
                                   			if(done instanceof Function) done();
                                   		},
                                   		onFailure: onFailure});
	}
	else
	{
		var myAjax = new Ajax.Request(url, {method: action,
    	                             	parameters: 'ajaxRequest='+ encodeURIComponent (jsonRequest),
    	                             	command: cmd,
    	                             	command_action: cmd_action,
                                   		onSuccess: function (response, json, cmd, cmd_action) {
                                   			onSuccess(response, json, cmd, cmd_action);
                                   			if(done instanceof Function) done();
                                   		},
                                   		onFailure: onFailure});
	}
//    responders_in_progress_count --;
}

function onSuccess(response, json, cmd, cmd_action)
{
//	alert("Raw response.responseText = " + response.responseText);

	try
	{
		var jsonResponse = response.responseText.evalJSON();
	}
	catch(e)
	{
		alert("Failed to parse response text\n\n" + response.responseText );
	}

//	alert("Parsed jsonResponse.responseText = " + jsonResponse);

	// Test for presence of warning object - if server side operations passed back a warning
	// then we process the warnings and stop execution
//	alert("jsonResponse.data.length : " +  jsonResponse.data.length );
	if (jsonResponse.warnings.length > 0)
	{
		processWarnings(jsonResponse.warnings);
	}
	else //server side operation must have succeeded
	{
		if (jsonResponse.notices.length > 0)
		{
			//handle notices
			processNotices(jsonResponse.warnings);
		}
		else
		{
//			alert(cmd_action + cmd);
			processData(cmd, jsonResponse.data);
		}
	}
}

function onFailure(response)
{
	alert("in failure");
}

function processWarnings(warnings)
{
	// get warnings
	var msg = '';

	for (i = 0; i < warnings.length; i++)
	{
		ts = warnings[i];
		msg += ts;
	}
	if (msg != '')
	{
		alert("Warnings from server:\n" + msg);
	}

	//return false;
}

function processNotices(notices)
{
	// get notices
	for (i = 0; i < notices.length; i++)
	{
		ts = notices[i];
		msg += ts;
	}

	if (msg != '')
	{
		alert("Notices from notices:\n" + msg);
	}
//	return false;
}

function processData(cmd, data)
{
	// Stringify object into JSON format to be passed as function argument
	var jsonRequest = Object.toJSON(data);

	// Dynamically create and call the function name
//	alert("Process data is calling: " + cmd + "(" + jsonRequest + ");");
	eval(cmd + "(" + jsonRequest + ");");
}

//
// --- Helper Functions
//
function showMessage(msg)
{
	// TO DO: need to make image location flexible
//	alert('Start showMessage(' + msg + ')');
//	$('result').innerHTML = msg;
//	$('result').style.display = 'block';
//	$('resultContainer').style.display = 'block';
	$('notification').innerHTML = '<img src="ajax_loader.gif" width="16" height="16" align="absmiddle">&nbsp;' + msg;
	$('notification').style.display = 'block';
//	alert('End showMessage(' + msg + ')');
}
