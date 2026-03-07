{include file="header2.tpl" title="Company"}

<script language="JavaScript">
{literal}

	function AjaxClient(data)
	{
		alert("AjaxClient: " + data[0].length);
		for (i = 1; i < data[0].length + 1; i++)
		{
			var t = data[0][i-1];
			alert("t.field = " + t.field + " : t.value = " + t.value);
			$("edit_" + t.field).innerHTML  = t.value;
		}
	}

{/literal}
</script>

<p style="font-size: 12px; font-weight: bold">{$client->getName()}</span></p>

<br />

<table class="ianlist">
	<tr>
		<th style="width: 10%">
		<img id="img_edit_name" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align: middle" /> Name</th>
		<td style="width: 49%">
			<span id="edit_name">{$client->getName()}</span>
			<script type="text/javascript">
				new Ajax.InPlaceEditor('edit_name', '', {literal}{externalControl: 'img_edit_name', ill_cmd: 'AjaxClient', ill_item_id: {/literal}{$client->getId()}{literal}, ill_field: 'name'}{/literal});
			</script>
		</td>
	</tr>
</table>

{include file="footer2.tpl"}