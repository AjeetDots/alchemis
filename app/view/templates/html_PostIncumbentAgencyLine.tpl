<td>{$name}</td>
<td style="text-align: left">{$address}</td>
<td style="text-align: left">{$last_updated_at|date_format:"%d/%m/%Y"}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="confirmBtn_{$id}" title="Confirm incumbent agency" href="#" onclick="javascript:confirmIncumbent({$id}, '{$incumbent.name}');return false;"><img src="{$APP_URL}app/view/images/icons/help.png" alt="Confirm incumbent agency" title="Confirm incumbent agency is still used by this post" /></a>&nbsp;
</td>
<td style="text-align: center; vertical-align: middle">
	<a id="deleteBtn_{$id}" title="Remove incumbent agency" href="#" onclick="javascript:deleteIncumbent({$id}, '{$incumbent_name}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove incumbent agency" title="Remove incumbent agency from this post (does NOT delete the company)" /></a>&nbsp;
</td>
