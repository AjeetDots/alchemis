{include file="header2.tpl" title="Campaign Document Add"}

{if $success}

	<p>The event has been created.</p>

	<script language="JavaScript" type="text/javascript">
		parent.location = 'index.php?cmd=CampaignDocuments&campaign_id={$campaign_id}';
	</script>

{else}

	<form name="document_add" action="index.php" method="post" enctype="multipart/form-data">
	
		<input type="hidden" name="cmd"         value="CampaignDocumentAdd" />
		<input type="hidden" name="campaign_id" value="{$campaign_id}" />
	
		<table class="ianlist">
			<tr>
				<th><label for="file">File</span></th>
				<td align="left" valign="top">
					<input type="file" name="file" size="45" />
				</td>
			</tr>
			<tr>
				<th><label for="document_description">Description</th>
				<td align="left" valign="top">
					<textarea name="document_description" rows="5" style="width: 100%"></textarea>
				</td>
			</tr>
		</table>
	
		<input type="submit" name="submit_button" value="Save" />
	
	</form>

{/if}

{include file="footer.tpl"}