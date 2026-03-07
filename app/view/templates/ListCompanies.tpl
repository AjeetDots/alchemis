{include file="header.tpl" title="List Companies"}

<fieldset class="adminform">

	<legend>Companies</legend>

	<p><a href="index.php?cmd=CompanyCreate">Add Company</a></p>

	<table id="table1" class="adminlist" cellspacing="1"{*class="sortable"*}{* border="0" cellpadding="0" cellspacing="1" width="100%"*}>
		<thead>
			<tr>
				<th width="5">#</th>
				<th style="width: 70%; text-align: center">Company</th>
				<th style="width: 10%; text-align: center">ID</th>
				<th style="width: 20%; text-align: center"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<del class="container">
						<div class="pagination">
							<div class="limit">Display #
								<select name="limit" id="limit" class="inputbox" size="1" onchange="document.adminForm.submit();">
									<option value="5" >5</option>
									<option value="10" >10</option>
									<option value="15" >15</option><option value="20"  selected="selected">20</option>
									<option value="25" >25</option><option value="30" >30</option>
									<option value="50" >50</option><option value="100" >100</option>
								</select>
							</div>
							<div class="button2-right off">
								<div class="start"><span>Start</span></div>
							</div>
							<div class="button2-right off">
								<div class="prev"><span>Prev</span></div>
							</div>
							<div class="button2-left">
								<div class="page"><a title="1" onclick="javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;">1</a><a title="2" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">2</a><a title="3" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">3</a></div>
							</div>
							<div class="button2-left">
								<div class="next"><a title="Next" onclick="javascript: document.adminForm.limitstart.value=20; document.adminForm.submit();return false;">Next</a></div>
							</div>
							<div class="button2-left">
								<div class="end"><a title="End" onclick="javascript: document.adminForm.limitstart.value=40; document.adminForm.submit();return false;">End</a></div>
							</div>
							<div class="limit">page 1 of 3</div>
							<input type="hidden" name="limitstart" value="0" />
						</div>
					</del>
				</td>
			</tr>
		</tfoot>
	
		<tbody>
			{foreach name=com from=$companies item=company}
			<tr>
				<td>{$smarty.foreach.com.iteration}</td>
				<td>{$company->getName()}</td>
				<td>{$company->getId()}</td>
				<td>
					<a id="editBtn_{$company->getId()}" href="#{*javascript:myController({$company->getId()});*}" title="Edit company">Edit</a>
					<a id="cancelBtn_{$company->getId()}" style="display: none" href="#{*javascript:cancelEditable({$company->getId()});*}" title="Cancel">Cancel</a>
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>

</fieldset>

{include file="footer.tpl"}