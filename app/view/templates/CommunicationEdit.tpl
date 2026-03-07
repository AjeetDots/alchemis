{include file="header2.tpl" title="Edit Last Communication Status"}

<script type="text/javascript">
{literal}


function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.adminForm.task.value=pressbutton;
	
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
//	alert('submitbutton(' + pressbutton + ')');
	
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}

{/literal}


{if $success}
	var source_frame = "iframe_{$parent_tab}";
	var s = top.window[source_frame].contentWindow.loadPost({$post_initiative->getPostId()}, {$post_initiative->getInitiativeId()}, {$post_initiative->getId()});
{/if}


</script>

<form action="index.php?cmd=CommunicationEdit" method="post" name="adminForm" autocomplete="off">

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="{$id}" />
	<input type="hidden" name="parent_tab" value="{$parent_tab}" />
	<p><h2>Edit last communication status</h2></p>
	<fieldset class="adminform">
		<legend>Status</legend>
		<table>
			<tr>
				{if $status_options}
				{*<td style="width:80px">New Status </td>*}
				<td>
					<select style="width: 100%" id="status_id" name="status_id">
						{html_options options=$status_options selected=$status_id}
					</select>
				</td>
				{else}
				<td>
					Meetings exist for this post initiative. <br /><br />Status cannot be changed.
				</td>
				{/if}

			</tr>
		</table>
	</fieldset>
	<p></p>
	{if $status_options}
		<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
	{/if}
</form>

{include file="footer2.tpl"}