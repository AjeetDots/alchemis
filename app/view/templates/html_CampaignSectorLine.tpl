<td >{$sector->getId()}</td>
<td >{$sector->getSectorName()}</td>
<td >{$sector->getWeighting()}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="deleteBtn_{$sector->getId()}" title="Remove sector from campaign" href="#" onclick="javascript:deleteSector({$sector->getId()}, '{$sector->getSectorName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove sector from campaign" title="Remove sector from campaign" /></a>&nbsp;
</td>