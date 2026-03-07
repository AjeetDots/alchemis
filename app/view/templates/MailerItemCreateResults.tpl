{include file="header2.tpl" title="Search Results"}

<script language="JavaScript" type="text/javascript">
{literal}
var page_isloaded = false;

function showCompany(company_id)
{
// iframeLocation(top.frames["iframe_5"], "index.php?cmd=WorkspaceSearch&id=" + company_id);
//	top.loadTab(5,"");
iframeLocation(	top.frames["iframe_7"], "index.php?cmd=WorkspaceFilter&id=" + company_id);
	top.loadTab(7,"");
}
		
function selectAll()
{
	var select_all = $("chk_select_all");
	var is_checked = select_all.checked;
	var form = $('mailer_results')
	var buttons = form.getInputs('checkbox')
	buttons.each(function(item) 
		{
  			item.checked = is_checked;
		}
	)
}

var last_row_class_change_id = "";

function highlightSelectedRow(row_id)
{
	$("tr_" + row_id).className="current";
	
	// now set the previously selected items to a normal background
	if (last_row_class_change_id != "" && last_row_class_change_id != row_id)
	{
		$("tr_" + last_row_class_change_id).className="";
	}
	last_row_class_change_id = row_id;
}

{/literal}

//display filter name in menu bar
top.$('loaded_filter_name').innerHTML = "{$filter->getName()}";

</script>

<p>Results for the filter <strong>{$filter->getName()}</strong></p>
<p>Companies: {$filter->getCompanyCount()} &nbsp;|&nbsp;
Posts: {$filter->getPostCount()} &nbsp;|&nbsp;
{*Communications: {$filter->getCommunicationCount()} &nbsp;|&nbsp;
Effectives: {$filter->getEffectiveCount()}</p>*}

<form id="mailer_results" name="mailer_results" action="" method="post">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="mailer_id" value="" />
	<input type="hidden" name="initiative_id" value="" />
	
	<table id="table1" class="sortable" cellspacing="1" border="0" cellpadding="0" cellspacing="1" style="width:100%">
		<thead>
			<tr>
				<th style="width: 1%; text-align: center">#</th>
				<th style="width: 25%; text-align: left">Company</th>
				<th style="width: 25%; text-align: left">Address</th>
				<th style="width: 5%; text-align: left">Company Cleaned</th>
				<th style="width: 20%; text-align: left">Job Title</th>
				<th style="width: 20%; text-align: left">Name</th>
				<th style="width: 5%; text-align: left">Post Cleaned</th>
				<th style="width: 5%" class="no_sort"><input type="checkbox" id="chk_select_all" name="chk_select_all" onchange="javascript:selectAll();return false;"/></th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			{foreach name="result_loop" from=$results item=result}
				<tr id="tr_{$result.post_id}" style="vertical-align:top" onfocus="javascript:highlightSelectedRow({$result.post_id});">
					<td>
						{$smarty.foreach.result_loop.iteration}</td>
					<td>
						<span id="client_{$result.id}" style="font-weight:bold">{$result.name}</span>
						{assign var="telephone" value=$result.telephone}
						{if $telephone != ""}
						<br />
							{$telephone}&nbsp;&nbsp;<a href="#" tabindex="-1" >Dial</a>
						{/if}
						{assign var="website" value=$result.website}
						{if $website != ""}
						<br />
							<a href="{$website}" target="_new" tabindex="-1">{$website}</a>
						{/if}
					</td>
					<td>{$result.site_address}</td>
					<td>{$result.company_cleaned_date}</td>
					<td>{$result.job_title}
		 				{$result.telephone_1}
		 				{if $result.telephone_1 != ''}&nbsp;&nbsp;<a href="#" tabindex="-1">Dial</a>{/if}
					</td>
					<td>{$result.first_name}&nbsp;{$result.surname}</td>
					<td>{$result.post_cleaned_date}</td>
					<td style="text-align: center; background-color: #F3F3F3" nowrap="nowrap">
						<div style="vertical-align: top">
							<a style="cursor: pointer" id="detailsBtn_{$result.id}" title="Go to details for this record" onclick="javascript:showCompany({$result.id});return false;" tabindex="-1"><img src="{$APP_URL}app/view/images/icons/database_table.png" alt="Details" title="Details" /></a>&nbsp;
						</div>
						<br />
						<div style="vertical-align: bottom;">
							<input style="vertical-align: bottom;" type="checkbox" id="chk_post_id_{$result.post_id}" name="chk_post_id_{$result.post_id}" />
						</div>
					</td>
				</tr>
				
			{/foreach}
		</tbody>
	</table>
</form>

{include file="footer2.tpl"}