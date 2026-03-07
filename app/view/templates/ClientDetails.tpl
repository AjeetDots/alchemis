{include file="header2.tpl" title="Client Details"}

<script language="JavaScript" type="text/javascript">
{literal}
function openInfoPane(src)
{
	//alert('openInfoPane(' + src + ')');

	if (parent.ifr_info == undefined)
	{
		popupWindow(src);
	}
	else
	{
iframeLocation(		parent.ifr_info, src);
	}
}
{/literal}
</script>

<table class="adminlist">
	<tr>
		<td style="width: 50%">
			<table class="ianlist">
				<tr>
					<th>
						Client name
					</th>
					<td>
						{$client->getName()}
					</td>
				</tr>
				<tr>
					<th>
						Current client
					</th>
					<td>
						{if $client->getIsCurrent()}
							<img src="{$APP_URL}app/view/images/icons/tick.png" alt="Client is current" title="Client is current" />
						{else}
							<img src="{$APP_URL}app/view/images/icons/cross.png" alt="Client is not current" title="Client is not current" />
						{/if}
					</td>
				</tr>
				<tr>
					<th>
						Telephone
					</th>
					<td>
						{$client->getTelephone()}
					</td>
				</tr>
				<tr>
					<th>
						Fax
					</th>
					<td>
						{$client->getFax()}
					</td>
				</tr>
				<tr>
					<th>
						Website
					</th>
					<td>
						{$client->getWebsite()}
					</td>
				</tr>
				<tr>
					<th>
						Financial Year Start
					</th>
					<td>
						{$client->getFinancialYearStart()|date_format:"%d %B"}
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 50%">
			<table class="ianlist">
				<tr>
					<th style="vertical-align: top;">
						Invoice Address
					</th>
					<td>
						{$client->getAddress('paragraph')}
					</td>
				</tr>
				<tr>
					<th>
						Primary contact name<br />
						Job title<br />
						Telephone<br />
						Email
					</th>
					<td>
						{$client->getPrimaryContactName()}
						<br />
						{$client->getPrimaryContactJobTitle()}
						<br />
						{$client->getPrimaryContactTelephone()}
						<br />
						{$client->getPrimaryContactEmail()}
					</td>
				</tr>
				<tr>
					<th>
						Secondary contact name<br />
						Job title<br />
						Telephone<br />
						Email
					</th>
					<td>
						{$client->getSecondaryContactName()}
						<br />
						{$client->getSecondaryContactJobTitle()}
						<br />
						{$client->getSecondaryContactTelephone()}
						<br />
						{$client->getSecondaryContactEmail()}
					</td>
				</tr>
				
			</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="javascript: openInfoPane('index.php?cmd=ClientDetailsEdit&amp;id={$client->getId()}');" title="Edit client details">[Edit]</a>
		</td>
	</tr>
</table>


{include file="footer2.tpl"}