{include file="header.tpl" title="Admin Characteristics"}

<script type="text/javascript"> 
{literal}

	// Maintain global tab collection (tab_colln)
	// If this page has been loaded then we don't want to reload it when the tab is clicked
	if (parent.tab_colln !== undefined && ! parent.tab_colln.goToValue(12))
	{
		parent.tab_colln.add(8);
	}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<div id="content-pane" class="pane-sliders">

				<div class="panel">
					<h3 class="moofx-toggler title" id="cpanel-panel"><span>Company</span></h3>
					<div class="moofx-slider content">
						<div class="module_content">
							<iframe id="iframe1" name="iframe1" src="index.php?cmd=CharacteristicList" scrolling="yes" 
								border="0" frameborder="no" style="height: 720px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
						</div>
					</div>
				</div>
			</div>
		</td>
		<td width="50%" valign="top">
			<div id="filter_results"></div>
		</td>
	</tr>
</table>

{include file="footer.tpl"}