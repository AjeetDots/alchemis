<td style="text-align: center">{$characteristic->getId()}</td>
<td>{$characteristic->getName()}</td>
<td>{$characteristic->getDescription()}</td>
<td style="text-align: center; vertical-align: middle">
{if $characteristic->getType() == 'company'}
	<img src="{$APP_URL}app/view/images/icons/building.png" alt="Company" title="Company" />
{elseif $characteristic->getType() == 'post'}
	<img src="{$APP_URL}app/view/images/icons/group.png" alt="Post" title="Post" />
{elseif $characteristic->getType() == 'post_initiative'}
	<img src="{$APP_URL}app/view/images/icons/user_comment.png" alt="Post Initiative" title="Post Initiative" />
{else}
	{$characteristic->getType()|capitalize}
{/if}
</td>
<td style="text-align: center; vertical-align: middle">{if $characteristic->hasAttributes()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
<td style="text-align: center; vertical-align: middle">{if $characteristic->hasOptions()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
<td style="text-align: center; vertical-align: middle">{if $characteristic->hasMultipleSelect()}<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />{/if}</td>
<td style="text-align: center; vertical-align: middle">{$characteristic->getDataType()|capitalize}</td>
<td style="text-align: center; vertical-align: middle">
	<a id="viewBtn_{$characteristic->getId()}" title="Edit" href="#" onclick="javascript:editCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
	<a id="deleteBtn_{$characteristic->getId()}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/tag_blue_delete.png" alt="Delete" title="Delete" /></a>
</td>