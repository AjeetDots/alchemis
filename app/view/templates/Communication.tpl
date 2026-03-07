{include file="header.tpl" title="Communication"}

<script type="text/javascript"> 
{literal}

// Maintain global tab collection (tab_colln) 
// If this page has been loaded then we don't want to reload it when the tab is clicked
if (parent.tab_colln !== undefined && !parent.tab_colln.goToValue(4))
{
	parent.tab_colln.add(4);
}

{/literal}
</script>
{if $company_id == null || $company_id == ""}
	No company/post information specified - unable to log a communication. 
	<br />
	<br />
	Please click a Call link from either the  Please click on either the <a href="#" onclick="javascript:parent.loadTab(5, 'WorkspaceSearch');">Search Workspace</a> or the <a href="#" onclick="javascript:parent.loadTab(7, 'FilterWorkspace');">Filter Workspace</a> tabs
{else}
	<table class="adminform">
		<tr>
			<td width="66%" valign="top">
				<div class="module_content" >
					<iframe id="iframe1" name="iframe1" src="index.php?cmd=CommunicationCreate&company_id={$company_id}&post_id={$post_id}&post_initiative_id={$post_initiative_id}&initiative_id={$initiative_id}&source_tab={$source_tab}" scrolling="yes" border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
				</div>
			</td>
			<td width="34%" valign="top">
				<div class="module_content">
					<iframe id="ifr_info" name="ifr_info" src="" scrolling="yes" border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
				</div>
			</td>
		</tr>
	</table>
{/if}

<script type="text/javascript"> 
{literal}

top.$('notification').innerHTML = '<img src="app/view/images/ajax_loader.gif" width="16" height="16" align="absmiddle">&nbsp;Working...';
top.Effect.Appear('notification',{duration: 0.25, queue: 'end'});

{/literal}
</script>


{include file="footer.tpl"}