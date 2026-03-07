{include file="header2.tpl" title="Client"}

<script language="JavaScript">
{literal}
	function popupWindow(target)
	{
	alert('Not here popupWindow('+target+')');
//		alert($('iframe1').style);
//		$('iframe1').style.overflow-y = 'hidden';
//		$('iframe2').style.overflow-y = 'hidden';
//		$('iframe3').style.overflow-y = 'hidden';
//		$('iframe4').style.overflow-y = 'hidden';
		showPopWin(target, 800, 500, null);
	}

	function openInfoPane(src)
	{
		//alert("parent.frames[0].src = " + parent.iframe5.location.href);
		if (parent.information == undefined)
		{
			//alert("Here");
			parent.popupWindow(src);
			//alert("Here");
		}
		else
		{
iframeLocation(			parent.information, src);
		}
		
	}

{/literal}
</script>

<table class="ianlist">
	
	<tr>
		<th width="50%">Last Effective:&nbsp;<strong>10 Nov 2006 (JD Pink)<strong></th>
		<th></strong>Next Recall:&nbsp;<strong>12 Jan 2007 (Peter Pan)</strong></th>
	</tr>
</table>
<br />
{*<p><a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoProspectDetail');">View Prospect Detail</a></p>*}

<table class="adminlist">
	<tr>
		<th>Post contacted</th>
		{*<th></th>
		<th></th>*}
		<th>Propensity</th>
		<th>Last Eff</th>
		<th>Next Call</th>
		<th>Actions</th>
	</tr>
	<tr>
		<td style="background-color: #BDD0F2">
			JD Pink - Marketing Director&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a>
		</td>
		{*<td style="background-color: #BDD0F2">Hot</td>
		<td style="background-color: #BDD0F2">Short term</td>*}
		<td style="background-color: #BDD0F2">[****]</td>
		<td style="background-color: #BDD0F2">10 Nov 2006</td>
		<td style="background-color: #BDD0F2">12 Jan 07</td>
		<td style="background-color: #BDD0F2"><a href="#link_meetings" onmouseover="return overlib('Meeting on 14 Jan 07', CAPTION, 'Meetings', CAPCOLOR, '#000000', FGCOLOR, '#E3E7F2', BGCOLOR, '#C9D0E6');" onmouseout="return nd();"><img src="{$APP_URL}app/view/images/icon_meeting.gif" border="0" style="margin-top:5px;" /></a></td>
	</tr>
	<tr>
		<td>Mary Poppins - Finance Director&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a></td>
		{*<td>Receptive</td>
		<td>Medium term</td>*}
		<td>[**]</td>
		<td>17 May 2006</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Peter Pan - Marketing Director&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a></td>
		{*<td>Hot</td>
		<td>Short term</td>*}
		<td>[****]</td>
		<td>10 Nov 2006</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Jemima Puddle-Duck - Managing Director&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a></td>
		{*<td>Receptive</td>
		<td>Medium term</td>*}
		<td>[*]</td>
		<td>17 May 2006</td>
		<td></td>
		<td></td>
		
	</tr>
	<tr>
		<td>Tom Kitten - Sales Exec&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a></td>
		{*<td>Hot</td>
		<td>Short term</td>*}
		<td>[***]</td>
		<td>10 Nov 2006</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Jeremy Fisher - Sales Manager&nbsp;&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Detail]</a>&nbsp;<a href="javascript: openInfoPane('index.php?cmd=WorkspaceInfoPostDetail');">[Notes]</a></td>
		{*<td>Receptive</td>
		<td>Medium term</td>*}
		<td>[**]</td>
		<td>17 May 2006</td>
		<td></td>
		<td></td>
	</tr>
</table>



<script type="text/javascript">init_moofx();</script>

{include file="footer2.tpl"}