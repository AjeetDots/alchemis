{config_load file="`$LOCALE`.conf"}
Information request for:
{$company->getName()}


== Company Details ==

Company:  {$company->getName()}
Address:  {$company->getSiteAddress()}
Telephone:  {$company->getTelephone()}
Website:  {$company->getWebsite()}

== Information Request Details ==

Contact: {$post->getContactName()} {if $post->getJobTitle() && $post->getContactName()}({$post->getJobTitle()}){else}{$post->getJobTitle()}{/if}

Email: {if $contact}{$contact->getEmail()}{/if}

Initiative:{$client->getName()}


== Confirmation Process ==

{foreach name=actions_loop from=$actions item=action}
Action {$smarty.foreach.actions_loop.iteration}
Due by:  {$action->getDueDate()|date_format:"`$smarty.config.FORMAT_DATETIME_LONG`"}
Summary:  {$action->getSubject()}
To be sent by:  {if $action->getActionedByClient()}{$client->getName()}{else}Alchemis{/if}

How sent:  {$action->getCommunicationType()}
Resources to send:
{foreach name=resources_loop from=$action->getResources() item=resource}
{$resource.resource}
{/foreach}
Action detail:  {$action->getNotes()}
{/foreach}


== Agency User Information ==

{if $discipline_note == ''}-- No agency user information found --{else}{$discipline_note}{/if}

{*
== Company Characteristics ==

{if $characteristics|@count == 0}
-- No company characteristics found --
{else}

{foreach name=char_loop from=$characteristics item=characteristic}
					
= {$characteristic.name} =

{if $characteristic.attributes && !$characteristic.options}
{foreach name=element_loop from=$characteristic.elements item=element}
{$element.name}:  {if $element.data_type == 'boolean'}{if $element.value == 0}No{else}Yes{/if}{elseif $element.data_type == 'date'}{$element.value|date_format:"%d %B %Y"}{elseif $element.data_type == 'text'}{$element.value}{/if}
{/foreach}	
{elseif $characteristic.attributes && $characteristic.options}
{if $characteristic.multiple_select}
{foreach name=element_loop from=$characteristic.elements item=element}
{$element.name}:  {if $element.data_type == 'boolean'}{if $element.value == "0" || $element.value == ""}No{elseif $element.value == "1"}Yes{/if}{elseif $element.data_type == 'date'}{$element.value|date_format:"%d %B %Y"}{elseif $element.data_type == 'text'}{$element.value}{/if}
{/foreach}
{else}
{foreach name=element_loop from=$characteristic.elements item=element}
{$element.name}:  {if $element.data_type == 'boolean'}{if $element.value == "0"}No{elseif $element.value == "1"}Yes{/if}{elseif $element.data_type == 'date'}{$element.value|date_format:"%d %B %Y"}{elseif $element.data_type == 'text'}{$element.value}{/if}
{/foreach}
{/if}
{elseif !$characteristic.attributes || $characteristic.options}
{if $characteristic.data_type == 'boolean'}{if $characteristic.value == 0}No{else}Yes{/if}{elseif $characteristic.data_type == 'date'}{$characteristic.value|date_format:"%d %B %Y"}{elseif $characteristic.data_type == 'text'}{$characteristic.value}{/if}
{/if}
{/foreach}
{/if}
*}

== Information Request Notes ==

{if $notes|@count == 0}-- No meeting notes found --{else}
{foreach name="note_loop" from=$notes item=result}
{$result.communication_date|date_format:"%d/%m/%y %H:%M"} : {$result.user_client_alias}
{if $result.note != ""}{$result.note}{/if}


{/foreach}

{/if}


Copyright 2006{if $smarty.now|date_format:"%Y" > 2006}-{$smarty.now|date_format:"%Y"}{/if} Alchemis Ltd. All rights reserved.