{include file="header.tpl" title="Campaign Documents"}

<script language="JavaScript" type="text/javascript">
{literal}

function addDocument(campaign_id)
{
	iframeLocation(iframe1, "index.php?cmd=CampaignDocumentAdd&campaign_id=" + campaign_id);
	$("iframe1").show();
}


function deleteDocument(document_id)
{
	iframeLocation(iframe1, "index.php?cmd=CampaignDocumentDelete&document_id=" + document_id);
	$("iframe1").show();
}

{/literal}
</script>

<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
				<tr class="hdr">
					<td>
						Documents &nbsp;&nbsp;|&nbsp;&nbsp;
						<span style="text-align: right"><strong>{$documents|@count}</strong> document{if $documents|@count != 1}s{/if}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
						<input type="button" id="add_new_document" name="add_new_document" value="Add New Document" onclick="javascript:addDocument({$campaign_id}); return false;" />
					</td>
				</tr>

				<tr valign="top">
					<td>

						<table id="tbl_characteristic_list" class="adminlist">
							<thead>
								<tr>
									<th style="width: 3%">ID</th>
									<th>Filename</th>
									<th>Description</th>
									<th style="width: 10%; text-align: center">Size</th>
									<th style="width: 25%; text-align: center">Created By<br />(Date)</th>
									<th style="width: 10%; text-align: center">&nbsp;</th>
								</tr>
							</thead>

							{foreach name=document_loop from=$documents item=document}
							<tr id="tr_{$document->getId()}">
								<td style="text-align: center">{$document->getId()}</td>
								<td>{$document->getFilename()}</td>
								<td>{$document->getDescription()}</td>
								<td style="text-align: center">{$document->getFriendlySize()}</td>
								<td style="text-align: center">{$document->getCreatedByName()}<br />({$document->getCreated()|date_format:"%d/%m/%y %H:%M"})</td>
								<td style="text-align: center; vertical-align: middle">
									<a id="downloadBtn_{$document->getId()}" title="Download" href="index.php?cmd=Download&document_id={$document->getId()}"><img src="{$APP_URL}app/view/images/icons/page_white_put.png" alt="Download" title="Download" /></a>&nbsp;
									<a id="deleteBtn_{$document->getId()}" title="Delete" href="#" onclick="javascript:deleteDocument({$document->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/page_white_delete.png" alt="Delete" title="Delete" /></a>
								</td>
							</tr>
							{/foreach}
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
		</td>
	</tr>
</table>

{include file="footer.tpl"}