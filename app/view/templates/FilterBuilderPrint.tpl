{include file="header2.tpl" title="Filter Builder Print"}

<script language="JavaScript" type="text/javascript">
{literal}

{/literal}
</script>
<p style="text-align: left"><a href="#" onclick="javascript:window.print();"><img src="{$APP_URL}app/view/images/icons/printer.png" alt="Print" title="Print" /> Print</a></p>
								
<table class="adminform" style="width: 100%">
	<tr>
		<td valign="top">
			<div class="module_content">
				<div id="filter">
					<h3>Filter details</h3>
					<table class="adminlist" style="width:100%">
						<tr>
							<th style="width:30%">
								Name
							</th>
							<td>
								{$filter->getName()}
							</td>
						</tr>
						<tr>
							<th>
								Description
							</th>
							<td>
								{$filter->getDescription()|default:'-- None found --'}
							</td>
						</tr>
						<tr>
							<th>
								Type
							</th>
							<td>
								{$filter->getType()}
							</td>
						</tr>
						<tr>
							<th>
								Campaign (if applicable)
							</th>
							<td>
								{$filter->getCampaignName()|default:'-- Not applicable --'}
							</td>
						</tr>
						<tr>
							<th>
								Results Format
							</th>
							<td>
								{$filter->getResultsFormat()}
							</td>
						</tr>
						<tr>
							<th>
								Companies : Posts
							</th>
							<td>
								<span id="stats_company_count">{$filter->getCompanyCount()}</span> : <span id="stats_post_count">{$filter->getPostCount()}</span>
							</td>
						</tr>
						<tr>
							<th>
								Created By
							</th>
							<td>
								{$filter->getCreatedByName()}
							</td>
						</tr>
						<tr>
							<th>
								Created On
							</th>
							<td>
								{$filter->getCreatedAt()|date_format:"%d/%m/%y %H:%M"}
							</td>
						</tr>
						<tr>
							<th>
								Printed On
							</th>
							<td>
								{$smarty.now|date_format:"%d/%m/%y %H:%M"}
							</td>
						</tr>
					</table>
					<p></p>
					
				</div>
				<h3>Include parameters</h3>
				<table class="adminlist" style="width:100%">
					{foreach name=fl from=$filter_lines item=filter_line}
						{if $filter_line.direction == 'include'}
					<tr>
						<td style="vertical-align: top; width: 2.5%">
							{$filter_line.bracket_open}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.table_name}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.field_name}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.operator}
						</td>
						<td>
							{$filter_line.params_display}
						</td>
						<td style="vertical-align: top; width: 5%">
							{$filter_line.concatenator}
						</td>
						<td style="vertical-align: top; width: 2.5%">
							{$filter_line.bracket_close}
						</td>
					</tr>
						{/if}
					{/foreach}
				</table>
				<h3>Exclude parameters</h3>
				<table class="adminlist" style="width:100%">
					{foreach name=fl from=$filter_lines item=filter_line}
						{if $filter_line.direction == 'exclude'}
					<tr>
						<td style="vertical-align: top; width: 2.5%">
							{$filter_line.bracket_open}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.table_name}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.field_name}
						</td>
						<td style="vertical-align: top; width: 10%">
							{$filter_line.operator}
						</td>
						<td>
							{$filter_line.params_display}
						</td>
						<td style="vertical-align: top; width: 5%">
							{$filter_line.concatenator}
						</td>
						<td style="vertical-align: top; width: 2.5%">
							{$filter_line.bracket_close}
						</td>
					</tr>
						{/if}
					{/foreach}
				</table>	
			</div>
		</td>
		
	</tr>
</table>

{include file="footer2.tpl"}