{include file="header2.tpl" title="Post Delete"}

{if $success}

<p>You are being redirected to the company of the post you have just deleted.</p>
<script language="JavaScript" type="text/javascript">
	parent.getCompanyDetail({$company_id}, null, null);
	
	// check if post needs to be removed from search results
	{literal}
	if (top.iframe_6.location.href != 'about:blank')
	{
		var result_colln = top.iframe_6.iframe1.colln;
		result_colln.goToCompanyId({/literal}{$company_id}{literal});
		top.iframe_6.iframe1.$('tr_post_{/literal}{$id}{literal}').hide();
	}
	
	if (top.iframe_8.location.href != 'about:blank')
	{
		var result_colln = top.iframe_8.colln;
		result_colln.goToCompanyId({/literal}{$company_id}{literal});
		top.iframe_8.$('tr_post_{/literal}{$id}{literal}').hide();
	}
	
	{/literal}
</script>

{else}

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
	
	{if $post_count == 1}
		<p>{$post->getJobTitle()} {if $post->getContactName()} - {$post->getContactName()}{/if} is the last post at this company.</p>
		<p>You may not delete the last post at a company. If you wish to delete this post, 
		please <a href="index.php?cmd=PostCreate&company_id={$company_id}">create another post</a> first.<p>
	{else}
	
		<form action="index.php?cmd=PostDelete" method="post" name="adminForm" autocomplete="off">
			<input type="hidden" name="task" value="" />
			<input type="hidden" id="id" name="id" value="{$id}" />
			<input type="hidden" id="company_id" name="company_id" value="{$company_id}" />
			<input type="hidden" id="source_tab" name="source_tab" value="{$source_tab}" />
			
			
			<fieldset class="adminform">
				<legend>Post Deletion</legend>
				<p>Please note: post deletion may not be necessary - you may wish to consider 
				<a href="index.php?cmd=PostEdit&id={$id}">changing the post holder</a> 
				or 
				<a href="index.php?cmd=PostEdit&id={$id}">renaming the job title</a>.</p>
				
				<p style="font-weight:bold;">Only delete a post if the post no longer exists and has not been renamed.<p>
				
				<p>Confirm you wish to delete the following post by checking the box below.</p>
				
				<strong>{$post->getJobTitle()} {if $post->getContactName()} - {$post->getContactName()}{/if}</strong>
				<br />
				<br />
				at
				<br />
				<br />
				{$company->getName()}
				<br />
				{$company->getSiteAddress("","paragraph")}
				
				<p>All associated information, including all client related information will be deleted.</p>
				
				{*<p>Currently there are {$client_count} client records associated with this post.</p>*}
				
				<p>Confirm deletion &nbsp;<input type="checkbox" id="app_domain_Post_deleted" name="app_domain_Post_deleted" /></p>
			</fieldset>
			
			<p><input type="button" value="Submit" onclick="javascript:submitbutton('save')" /></p>
		
		</form>
	{/if}
{/if}

{include file="footer2.tpl"}