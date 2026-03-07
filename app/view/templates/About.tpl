{include file="header.tpl" title="About" tab="tabWorkspace"}

<div class="cfg">

	<table border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr">
			<td>About</td>
		</tr>
	</table>

	<table class="ianlist">
		<tr>
			<th style="width: 10%; vertical-align: top">Application</th>
			<td style="width: 90%">{$APP_NAME} {$APP_VERSION}</td>
		</tr>
		<tr>
			<th style="width: 10%; vertical-align: top">Copyright</th>
			<td style="width: 90%">Copyright &copy; 2006{if $smarty.now|date_format:"%Y" > 2006}&ndash;{$smarty.now|date_format:"%Y"}{/if} Alchemis Ltd.</td>
		</tr>
		<tr>
			<th style="width: 10%; vertical-align: top">Credits</th>
			<td style="width: 90%">
				David Carter<br />
				Ian Forbes<br />
				Ian Munday<br />
				Dave Newman<br />
				Jim Piper<br />
			</td>
		</tr>
	</table>

</div>

{include file="footer.tpl"}