<select name="{$html_select_name}" id="{$html_select_id}" style="{$html_select_style}"  onchange="{$html_select_onchange}" {if $html_select_multiple}multiple size="{$html_select_size}"{else}size="1"{/if}	>
	{if $html_select_values}
	
		{html_options 
		values = $html_select_values 
		output = $html_select_output}
	
	{else}
		{html_options options=$html_select_options}
	{/if}	
</select>