	<table style="padding-left: 25px;">

		{if $characteristic.attributes && !$characteristic.options}

			{foreach name=element_loop from=$characteristic.elements item=element}
			<tr style="height:20px">
				<td>1{$element.name}</td>
				<td>
					{if $element.data_type == 'boolean'}
						{if $element.value == 0}
							<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
						{else}
							<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
						{/if}
					{elseif $element.data_type == 'date'}
						{$element.value|date_format:"%d %B %Y"}
					{elseif $element.data_type == 'text'}
						{$element.value}
					{/if}
				<td>
			</tr>
			{/foreach}	

		{elseif $characteristic.attributes && $characteristic.options}

			{if $characteristic.multiple_select}
				{foreach name=element_loop from=$characteristic.elements item=element}
				<tr style="height:20px">
					<td>2a{$element.name}</td>
					<td>
						{if $element.data_type == 'boolean'}
							{if $element.value == "0" || $element.value == ""}
								<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
							{elseif $element.value == "1"}
								<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
							{else}
								&nbsp;
							{/if}
						{elseif $element.data_type == 'date'}
							{$element.value|date_format:"%d %B %Y"}
						{elseif $element.data_type == 'text'}
							{$element.value}
						{/if}
					<td>
				</tr>
				{/foreach}	
			{else}
				{foreach name=element_loop from=$characteristic.elements item=element}
				<tr style="height:20px">
					<td>2b{$element.name}</td>
					<td>
						{if $element.data_type == 'boolean'}
							{if $element.value == "0"}
								<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
							{elseif $element.value == "1"}
								<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
							{else}
								&nbsp;
							{/if}
						{elseif $element.data_type == 'date'}
							{$element.value|date_format:"%d %B %Y"}
						{elseif $element.data_type == 'text'}
							{$element.value}
						{/if}
					<td>
				</tr>
				{/foreach}	
			{/if}

		{elseif !$characteristic.attributes || $characteristic.options}

			<tr style="height: 15px">
				<td>3
				{if $characteristic.data_type == 'boolean'}
					{if $characteristic.value == 0}
						<img src="{$ROOT_PATH}app/view/images/icons/cross.png" alt="No" title="No" />
					{else}
						<img src="{$ROOT_PATH}app/view/images/icons/tick.png" alt="Yes" title="Yes" />
					{/if}
				{elseif $characteristic.data_type == 'date'}
					{$characteristic.value|date_format:"%d %B %Y"}
				{elseif $characteristic.data_type == 'text'}
					{$characteristic.value}
				{/if}
				</td>
			</tr>

		{/if}

	</table>
