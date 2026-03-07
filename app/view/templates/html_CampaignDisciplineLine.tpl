<td style="text-align: center">{$discipline->getId()}</td>
<td>{$discipline->getDisciplineName()}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="deleteBtn_{$discipline->getId()}" title="Remove discipline from campaign" href="#" onclick="javascript:deleteDiscipline({$discipline->getId()}, '{$discipline->getDisciplineName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove discipline from campaign" title="Remove discipline from campaign" /></a>&nbsp;
</td>
	