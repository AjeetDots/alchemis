<table class="paginate">
	<tr>
		<td style="text-align: left; width: 33%">
			Items {$paginate.first}-{$paginate.last} of {$paginate.total} total
		</td>
		<td style="text-align: center; width: 34%">
			{paginate_first} {paginate_prev} {paginate_middle} {paginate_next} {paginate_last}
		</td>
		<td style="text-align: right; width: 33%">
			Items per page:
{*
			{foreach name=paginate_items_loop from=$paginate_items item=paginate_item}
				{if $paginate.limit != $paginate_item}<a href="index.php?cmd={$cmd}&amp;limit={$paginate_item}&next={$paginate.current_item}">{/if}10{if $paginate.limit != $paginate_item}</a>{/if}
			{/foreach}
*}
			{if $paginate.limit != 10}<a href="{$url}&amp;limit=10&next={$paginate.current_item}">{/if}10{if $paginate.limit != 10}</a>{/if}
			{if $paginate.limit != 25}<a href="{$url}&amp;limit=25&next={$paginate.current_item}">{/if}25{if $paginate.limit != 25}</a>{/if}
			{if $paginate.limit != 50}<a href="{$url}&amp;limit=50&next={$paginate.current_item}">{/if}50{if $paginate.limit != 50}</a>{/if}
			{if $paginate.limit != 100}<a href="{$url}&amp;limit=100&next={$paginate.current_item}">{/if}100{if $paginate.limit != 100}</a>{/if}
			{if $paginate.limit != $paginate.total}<a href="{$url}&amp;limit={$paginate.total}&next={$paginate.current_item}">{/if}All{if $paginate.limit != $paginate.total}</a>{/if}
		</td>
	</tr>
</table>