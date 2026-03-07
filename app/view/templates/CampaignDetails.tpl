{include file="header2.tpl" title="Campaign Details"}

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
		iframeLocation(parent.ifr_info, src);
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
						Project or on-going
					</th>
					<td>
						{$campaign->getTypeName()}
					</td>
				</tr>
				<tr>
					<th>
						Campaign start month
					</th>
					<td>
						{$campaign->getStartYearMonth()}
					</td>
				</tr>
				<tr>
					<th>
						Initial fee
					</th>
					<td>
						{$campaign->getInitialFee()}
					</td>
				</tr>
				<tr>
					<th>
						Current fee (view notes)
					</th>
					<td>
						{$campaign->getCurrentFee()}
					</td>
				</tr>
				<tr>
					<th>
						Billing terms
					</th>
					<td>
						{$campaign->getBillingTerms()}
					</td>
				</tr>
				<tr>
					<th>
						Payment terms
					</th>
					<td>
						{$campaign->getPaymentTerms()}
					</td>
				</tr>
				<tr>
					<th>
						Payment method
					</th>
					<td>
						{$campaign->getPaymentMethod()}
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 50%">
			<table class="ianlist">
				<tr>
					<th>
						Date contract sent out
					</th>
					<td>
						{$campaign->getContractSentDate()|date_format:"%d %B %Y"}
					</td>
				</tr>
				<tr>
					<th>
						Date contract received
					</th>
					<td>
						{$campaign->getContractReceivedDate()|date_format:"%d %B %Y"}
					</td>
				</tr>
				<tr>
					<th>
						So form received
					</th>
					<td>
						{$campaign->getSoFormReceivedDate()|date_format:"%d %B %Y"}
					</td>
				</tr>
				<tr>
					<th>
						Minimum campaign duration
					</th>
					<td>
						{if $campaign->getMinimumDuration()}
							{if $campaign->getMinimumDuration() == 1}
								{$campaign->getMinimumDuration()} month
							{else}
								{$campaign->getMinimumDuration()} months
							{/if}
						{/if}
					</td>
				</tr>
				<tr>
					<th>
						Notice period after min period
					</th>
					<td>
						{if $campaign->getNoticePeriod()}
							{if $campaign->getNoticePeriod() == 1}
								{$campaign->getNoticePeriod()} month
							{else}
								{$campaign->getNoticePeriod()} months
							{/if}
						{/if}
					</td>
				</tr>
				<tr>
					<th>
						Additional terms/Side letter
					</th>
					<td>
						{if $campaign->getAdditionalTermsExist()}
							<img src="{$APP_URL}app/view/images/icons/tick.png" alt="Additional terms/Side letter exist" title="Additional terms/Side letter exist" />
						{else}
							<img src="{$APP_URL}app/view/images/icons/cross.png" alt="Additional terms/Side letter do not exist" title="Additional terms/Side letter do not exist" />
						{/if}
					</td>
				</tr>
				<tr>
					<th>
						Notice given date
					</th>
					<td>	
						{$campaign->getNoticeDate()|date_format:"%d %B %Y"}
					</td>
				</tr>
				<tr>
					<th>
						Final month of activity
					</th>
					<td>
						{$campaign->getEndYearMonth()}
					</td>
				</tr>
			</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="javascript: openInfoPane('index.php?cmd=CampaignDetailsEdit&amp;id={$campaign->getId()}');" title="Edit campaign details">[Edit]</a>
		</td>
	</tr>
</table>


{include file="footer2.tpl"}