<td style="text-align: center">{$region->getId()}</td>
<td>{$region->getName()}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="deleteBtn_{$region->getId()}" title="Remove region from campaign" href="#" onclick="javascript:deleteRegion({$region->getId()}, '{$region->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove region from campaign" title="Remove region from campaign" /></a>&nbsp;
</td>
	