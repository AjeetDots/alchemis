{include file="header.tpl" title="RBAC User List"}

{config_load file="example.conf"}

<table class="adminform">
	<tr>
		<td width="67%" valign="top">
		
			<div class="cfg">

				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td>Users</td>
					</tr>
					<tr valign="top">
						<td>

							<table id="table1" class="adminlist"{* class="sortable" border="0" cellpadding="0" cellspacing="1" width="100%"*}>
								<thead>
									<tr>
										<th style="width: 3%">#</th>
										<th style="width: 42%; text-align: center">Username</th>
										<th style="width: 10%; text-align: center">ID</th>
										<th style="width: 30%; text-align: center">Last Login</th>
										<th style="width: 15%; text-align: center">&nbsp;</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="5">
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
									{foreach name=com from=$users item=user}
									<tr>
										<td>{$smarty.foreach.com.iteration}</td>
										<td>{$user->getHandle()}</td>
										<td style="text-align: center">{$user->getId()}</td>
										<td style="text-align: center">{$user->getLastLogin()|date_format:"`$smarty.config.format_datetime_short`"}</td>
										<td style="text-align: center; background-color: #F3F3F3">
											<div class="button2-left">
												<div class="page"><a id="viewBtn_{$command}" title="Details" href="index.php?cmd=RbacUserView&amp;user_id={$user->getId()}">Details</a></div>
											</div>
										</td>
									</tr>
									{/foreach}
								</tbody>
							</table>

						</td>
					</tr>
				</table>
			</div>

		</td>
		<td width="33%" valign="top">

			<div class="cfg">

				<table border="0" cellpadding="0" cellspacing="0">
					<tr class="hdr">
						<td>Task Pane</td>
					</tr>
					<tr valign="top">
						<td style="padding-top: 3px">

							<div id="content-pane" class="pane-sliders">
{*
								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Log Communication</span></h3>
									<div class="moofx-slider content">
										<iframe id="iframe5" src="index.php?cmd=CommunicationCreate" scrolling="yes" border="0" frameborder="no" height="600" width="100%" style="overflow-x: hidden; overflow-y: y:auto"></iframe>
									</div>
								</div>
*}
								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>User Details</span></h3>
									<div class="moofx-slider content">
										<table class="ianlist">
											<tr>
												<th style="width: 30%">User</th>
												<td style="width: 70%">ian / Ian Munday</td>
											</tr>
											<tr>
												<th style="width: 30%; vertical-align: top">Roles</th>
												<td style="width: 70%">
													<select name="roles[]" style="width: 100%" size="15" multiple="multiple">
														<option value="0">All</option>
														<option value="-999">----</option>
														<option value="-999">None</option>
														<option value="-999">----</option>
														<option value="1">Anonymous</option>
														<option value="2" selected="selected">Administrators</option>
														<option value="3">NBMs</option>
													</select>
												</td>
											</tr>
										</table>
									</div>
								</div>
								
								<div class="panel">
									<h3 class="moofx-toggler title" id="cpanel-panel"><span>Help</span></h3>
									<div class="moofx-slider content">
										<table class="adminlist">
											<tr>
												<td>
													<em>You can turn the display of Help and Advice off in your personal settings.</em>
												</td>
											</tr>
											<tr>
												<td>
													<p><strong>Consetetur Sadipscing</strong></p>
													<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse 
													molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros 
													et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril 
													delenit augue duis dolore te feugait nulla facilisi.</p>
													<dl>
														<dt>1. Accumsan et iusto</dt>
														<dd>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt 
														ut labore et dolore magna aliquyam erat, sed diam voluptua.</dd>
														<dt>2. Velit esse</dt>
														<dd>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy 
														eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam 
														voluptua.</dd>
													</dl>
													<p><strong>Client-Specific Advice</strong></p>
													<p>Aoccdrnig to a rscheearch at an Elingsh uinervtisy, it deosn't mttaer 
													in waht oredr the ltteers in a wrod are, the olny iprmoetnt tihng is taht 
													frist and lsat ltteer is at the rghit pclae. The rset can be a toatl mses 
													and you can sitll raed it wouthit porbelm. Tihs is bcuseae we do not raed 
													ervey lteter by itslef but the wrod as a wlohe.</p>
												</td>
											</tr>
										</table>
									</div>
								</div>

				
							</div>
							
							<script type="text/javascript">init_moofx();</script>

						</td>
					</tr>
				</table>
			</div>

		</td>
	</tr>
</table>

{include file="footer.tpl"}