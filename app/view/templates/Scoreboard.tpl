{include file="header2.tpl" title="User Scoreboard"}

{config_load file="example.conf"}
<script language="JavaScript" type="text/javascript">
{literal}
function showPost(company_id, post_id, initiative_id)
{
iframeLocation(	opener.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id + "&post_id=" + post_id + "&initiative_id=" + initiative_id);
	opener.loadTab(5,"");
	opener.focus();
}
{/literal}
</script>

<h2>Today's scoreboard for {$user.name}</h2>

<h3><a href="#" onclick="javascript:Effect.toggle($('div_tbl_effectives'), 'blind', {literal}{duration: 0.3}{/literal});">Effectives ({$scoreboard->getEffectiveCount()})</a></h3>
	
<div id="div_tbl_effectives" style="display:none">
	<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
		
		{assign var=client_name value=""}
		{assign var=first_loop value=true}
		{assign var=client_count value=0}
		{foreach name="effectives_loop" from=$effectives item=effective}
			{if $client_name != $effective.client_name}
				{if $first_loop == false}
					<tr>
						<th colspan="3"></th>
						<th colspan="2" style="width:10%; text-align:right;">Total: {$client_count}</th>
					</tr>
					{assign var=client_count value=0}
				{else}
					{assign var=first_loop value=false}
				{/if}
				{math equation="x + y"
				x=$client_count
				y=$effective.comm_count
				assign=client_count} 
				{assign var=client_name value=$effective.client_name}
					<tr>
						<th colspan="5">{$effective.client_name}:&nbsp;{$effective.initiative_name}</th>
					</tr>
			{/if}
			<tr>
				<td style="width:50px">&nbsp;</td>
				<td style="width:30%">{$effective.company_name}</td>
				<td style="width:30%">
					<a href="#" onclick="javascript:showPost({$effective.company_id}, {$effective.post_id}, {$effective.initiative_id})">
						{$effective.job_title} ({$effective.full_name})
					</a>
				</td>
				<td style="width:30%">{$effective.status}</td>
				<td style="width:30%">{$effective.comm_count}</td>
			</tr>
		{/foreach}
		<!-- write in last total -->}
		{if $effectives|@count > 0}
		<tr>
			<th colspan="3"></th>
			<th colspan="2" style="width:10%; text-align:right;">Total: {$client_count}</th>
		</tr>
		{/if}
		<tr>
			<td colspan="5"><hr /></td>
		</tr>
	</table>
</div>

<h3><a href="#" onclick="javascript:Effect.toggle($('div_tbl_non_effectives'), 'blind', {literal}{duration: 0.3}{/literal});">Non-effectives ({$scoreboard->getNonEffectiveCount()})</a></h3>
<div id="div_tbl_non_effectives" style="display:none">

	<table id="" class="adminlist" id="tbl_non_effectives" border="0" cellpadding="0" cellspacing="0">
		{foreach name="non_effectives_loop" from=$non_effectives item=non_effective}
		<tr>
			<td colspan="2" style="width:50px">&nbsp;</td>
			<td>{$non_effective.client_name}:&nbsp;{$non_effective.initiative_name}</td>
			<td style="width:10%">{$non_effective.effective_count}</td>
		</tr>
		{/foreach}
			<tr>
			<td colspan="4"><hr /></td>
		</tr>
	</table>
</div>

<h3><a href="#" onclick="javascript:Effect.toggle($('div_tbl_meetings_set'), 'blind', {literal}{duration: 0.3}{/literal});">Meetings set ({$meetings_set|@count})</a></h3>
<div id="div_tbl_meetings_set" style="display:">
	<table id="" class="adminlist" id="tbl_meetings_set" border="0" cellpadding="0" cellspacing="0">
		{assign var=client_name value=""}
		{assign var=first_loop value=true}
		{assign var=client_count value=0}
		{foreach name="effectives_loop" from=$meetings_set item=meeting}
			{if $client_name != $meeting.client_name}
				{if $first_loop == false}
					<tr>
						<th colspan="3"></th>
						<th style="width:10%">Total: {$client_count}</th>
					</tr>
				{else}
					{assign var=first_loop value=false}
				{/if}
				{counter start=0 assign=client_count}
					{assign var=client_name value=$meeting.client_name}
					<tr>
						<th colspan="4">{$meeting.client_name}:&nbsp;{$meeting.initiative_name}</th>
					</tr>
			{/if}
			{counter assign=client_count}
			<tr>
				<td style="width:50px">&nbsp;</td>
				<td style="width:30%">{$meeting.company_name}</td>
				<td style="width:30%">
					<a href="#" onclick="javascript:showPost({$meeting.company_id}, {$meeting.post_id}, {$meeting.initiative_id})">
						{$meeting.job_title} ({$meeting.full_name})
					</a>
				</td>
				<td style="width:30%">{$meeting.status}</td>
			</tr>
		{/foreach}
		{* write in last total*}
		{if $meetings_set|@count > 0}
		<tr>
			<th colspan="3"></th>
			<th style="width:10%">Total: {$client_count}</th>
		</tr>
		{/if}
		<tr>
			<td colspan="4"><hr /></td>
		</tr>
	</table>
</div>
<h3><a href="#" onclick="javascript:Effect.toggle($('div_tbl_information_requests'), 'blind', {literal}{duration: 0.3}{/literal});">Information requests ({$information_requests|@count})</a></h3>
<div id="div_tbl_information_requests" style="display:">

	<table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
	
		{assign var=client_name value=""}
		{assign var=first_loop value=true}
		{assign var=client_count value=0}
		{foreach name="effectives_loop" from=$information_requests item=information_request}
			{if $client_name != $information_request.client_name}
				{if $first_loop == false}
					<tr>
						<th colspan="3"></th>
						<th style="width:10%">Total: {$client_count}</th>
					</tr>
				{else}
					{assign var=first_loop value=false}
				{/if}
				{counter start=0 assign=client_count}
					{assign var=client_name value=$information_request.client_name}
					<tr>
						<th colspan="4">{$information_request.client_name}:&nbsp;{$information_request.initiative_name}</th>
					</tr>
			{/if}
			{counter assign=client_count}
			<tr>
				<td style="width:50px">&nbsp;</td>
				<td style="width:30%">{$information_request.company_name}</td>
				<td style="width:30%">
					<a href="#" onclick="javascript:showPost({$information_request.company_id}, {$information_request.post_id}, {$information_request.initiative_id})">
						{$information_request.job_title} ({$information_request.full_name})
					</a>
				</td>
				<td style="width:30%">{$information_request.status}</td>
			</tr>
		{/foreach}
		{* write in last total*}
		{if $information_requests|@count > 0}
		<tr>
			<th colspan="3"></th>
			<th style="width:10%">Total: {$client_count}</th>
		</tr>
		{/if}
	
		<tr>
			<td colspan="4"><hr /></td>
		</tr>
		
	</table>
</div>
{include file="footer2.tpl"}