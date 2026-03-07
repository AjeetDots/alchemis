{include file="header2.tpl" title="Import Company 2 "}

<script language="JavaScript" type="text/javascript">
{literal}
function submitform(pressbutton)
{
	document.adminForm.task.value = pressbutton;
	
	try
	{
		document.adminForm.onsubmit();
	}
	catch(e)
	{}
	
	document.adminForm.submit();
}

function submitbutton(pressbutton)
{
	if (pressbutton == 'save')
	{
		submitform(pressbutton);
		return;
	}
}


{/literal}
</script>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	</head>

	<body>
		<form name="adminForm" action="index.php?cmd=ImportCompany1_2" method="post">
		<input type="hidden" name="task" value="" />
			<div class="form-block">
				<div>Please enter the name of the csv file you wish to import. You must have added the file to /data/ direactory and must include the csv extension on the file name</div>
				<div>File name to import</div>
				<div><input name="file_name" type="text" value="" style="width:500px"/></div>
				<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
			</div>
		</form>
	</body>
</html>