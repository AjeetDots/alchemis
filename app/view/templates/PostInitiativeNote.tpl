{include file="header2.tpl" title="Post Note"}

<fieldset class="adminform">
	<legend>Full e-mail text</legend>
	{if $note[0]|@count == 0}
		<p style="text-align: center"><em>&lt;&mdash; Note could not be found &mdash;&gt;</em></p>
	{else}
		{assign var=attachment_count value=$attachments|@count}
		{if $attachments|@count > 0}
			<span style="color: #666">{$attachment_count} attachment{if $attachment_count > 1}s{/if} exist{if $attachment_count == 1}s{/if} at bottom of e-mail</span>
		{/if}
		<p>
			<span>{$note[0].note|nl2br|replace:"\n":''|replace:"\r":''|replace:"<br /> ":'<br />'}</span>
		</p>
		
		{if $attachment_count > 0}
		<h2>Email attachments</h2>
		<table style="width: 100%" class="default">
			{foreach name="attachments" from=$attachments item=attachment}
			<tr>
				<td>
					<a id="downloadBtn_{$attachment.document_id}" title="Download" href="index.php?cmd=Download&document_id={$attachment.document_id}">
					<img src="{$APP_URL}app/view/images/icons/page_white_put.png" alt="Download" title="Download" />
					{$attachment.filename}
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
		{/if}
	{/if}
</fieldset>

{include file="footer.tpl"}