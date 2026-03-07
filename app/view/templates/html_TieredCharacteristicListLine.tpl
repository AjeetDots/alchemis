{*								<td style="text-align: center">{$characteristic->getId()}</td>*}

								{if $characteristic->getParent()}
									<td>{*$characteristic->getParent()*}</td>
									<td>{$characteristic->getValue()}</td>
								{else}
									<td colspan="2">{$characteristic->getValue()}</td>
								{/if}
								
{*								<td>{$characteristic->getCategory()}</td>*}
								<td style="text-align: center; vertical-align: middle">
									<a id="viewBtn_{$characteristic->getId()}" title="Edit" href="#" onclick="javascript:editCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_edit.png" alt="Edit" title="Edit" /></a>&nbsp;
									<a id="deleteBtn_{$characteristic->getId()}" title="Delete" href="#" onclick="javascript:deleteCharacteristic({$characteristic->getId()});return false;"><img src="{$APP_URL}app/view/images/icons/plugin_delete.png" alt="Delete" title="Delete" /></a>
								</td>