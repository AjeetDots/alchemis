{if $project_refs}
{foreach name="project_ref_loop" from=$project_refs item=project_ref}
	{$project_ref.value}<br />
{/foreach}
{/if}
