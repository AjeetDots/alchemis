<td style="text-align: center">{$nbm->getId()}</td>
<td {if $nbm->isActive() == false}style="text-decoration:line-through"{/if}>{$nbm->getName()}</td>
<td style="text-align: center">{if $nbm->getIsLeadNbm()}<img src="{$APP_URL}app/view/images/icons/tick.png" alt="Lead NBM" title="Lead NBM" />{/if}</td>
<td>
	<span {if $nbm->isActive() == false}style="text-decoration:line-through"{/if} id="edit_nbm_call_name_{$nbm->getId()}">{$nbm->getUserAlias()}</span>
	{if $nbm->isActive()}
	<a id="editCallNameBtn_{$nbm->getId()}" title="Edit NBM call name" href="#" onclick="javascript:editNbmCallName({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/group_edit.png" alt="Change NBM call name" title="Change NBM call name" /></a>&nbsp;
	{/if}
</td>
<td {if $nbm->isActive() == false}style="text-decoration:line-through"{/if}>
	<span {if $nbm->isActive() == false}style="text-decoration:line-through"{/if} id="edit_nbm_email_{$nbm->getId()}">{$nbm->getUserEmail()}</span>
	{if $nbm->isActive()}
	<a id="editEmailBtn_{$nbm->getId()}" title="Edit NBM email address" href="#" onclick="javascript:editNbmEmail({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Change NBM email" title="Change NBM email" /></a>&nbsp;
	{/if}
</td>
<td>
{if $nbm->isActive() == false}	
	<span id="edit_nbm_deactivated_date_{$nbm->getId()}">{$nbm->getDeactivatedDate()}</span>
	<a id="editDeactivatedDateBtn_{$nbm->getId()}" title="Edit NBM deactivation date" href="#" onclick="javascript:editNbmDeactivatedDate({$nbm->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/email_edit.png" alt="Change NBM deactivation date" title="Change NBM deactivation date" /></a>&nbsp;
	{else}
		&nbsp;
	{/if}
</td>
<td style="text-align: left; vertical-align: middle">
	{if $nbm->isActive() && !$nbm->getIsLeadNbm()}
	<a id="makeLeadNbm_{$nbm->getId()}" title="Make Lead NBM" href="#" onclick="javascript:makeLeadNbm({$nbm->getId()}, '{$nbm->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/key_add.png" alt="Make Lead NBM" title="Make Lead NBM" /></a>&nbsp;
	<a id="deleteBtn_{$nbm->getId()}" title="Remove NBM from campaign" href="#" onclick="javascript:deleteNbm({$nbm->getId()}, '{$nbm->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/cross.png" alt="Remove NBM from campaign" title="Remove NBM from campaign" /></a>&nbsp;
	{elseif !$nbm->isActive()}
	<a id="reinstateBtn_{$nbm->getId()}" title="Reinstate NBM" href="#" onclick="javascript:reinstateNbm({$nbm->getId()}, '{$nbm->getName()}');return false;"><img src="{$APP_URL}app/view/images/icons/arrow_redo.png" alt="Reinstate NBM" title="Reinstate NBM" /></a>&nbsp;
	{/if}
</td>