<td style="text-align: center">{$user->getId()}</td>
<td>{$user->getName()}</td>
<td>{$user->getHandle()}</td>
<td style="text-align: center; vertical-align: middle">{if $user->isActive()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="viewBtn_{$user->getId()}" title="Edit" href="#" onclick="javascript:editUser({$user->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
	<a id="deleteBtn_{$user->getId()}" title="Delete" href="#" onclick="javascript:deleteUser({$user->getId()}); return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
</td>