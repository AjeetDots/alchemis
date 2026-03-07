{include file="header.tpl" title="RBAC Command View"}

<div class="cfg">

	<table border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr">
			<td colspan="3">Campaign View</td>
		</tr>
		<tr valign="top">
			<td width="50%" class="lcol">
				<table id="remDev" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Command Details</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									<table class="ianlist">
										<tr>
											<th style="width: 20%">Campaign ID</th>
											<td colspan="2" style="width: 80%">{$command->getId()}</td>
										</tr>
										<tr>
											<th style="width: 20%">Name</th>
											<td colspan="2" style="width: 80%">{$command->getName()}</td>
											
										</tr>
										<tr>
											<th style="width: 20%">Description</th>
											<td style="width: 60%">{$command->getDescription()}</td>
											<td style="width: 20%">
												<div class="button2-left">
													<div class="page"><a id="editBtn_{$command->getId()}" title="Edit" href="index.php?cmd=CampaignHistory&amp;campaign_id={$command->getId()}">History</a></div>
												</div>
											</td>
										</tr>
									</table>
									<p><em>Ability to add multiple NBMs?</em></p>
									<p><em>Upon NBM selection should load email, telephone and mobile details from NNBM profile.</em></p>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody id="network">
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Alchemis Team Details</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									<table class="ianlist">
										<tr>
											<th style="width: 20%">New Business Manager</th>
											<td style="width: 80%">
												<select name="nbm_id">
													<option value="">&ndash; Select &ndash;</option>
													<option value="1">John Cofie</option>
													<option value="2">Philip Henry</option>
												</select>
											</td>
										</tr>
										<tr>
											<th style="width: 20%">Call Name</th>
											<td style="width: 80%"><input type="text" name="email" maxlength="255" /></td>
										</tr>
										<tr>
											<th style="width: 20%">Email</th>
											<td style="width: 80%"><input type="text" name="email" maxlength="255" /></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Objectives and Expectations</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									<table class="ianlist">
										<tr>
											<td style="border-bottom: 1px solid #E6E6E6; width: 20%">&nbsp;</td>
											<td style="border-bottom: 1px solid #E6E6E6; text-align: center; width: 20%">Jan / Feb / Mar</td>
											<td style="border-bottom: 1px solid #E6E6E6; text-align: center; width: 20%">Apr / May / Jun</td>
											<td style="border-bottom: 1px solid #E6E6E6; text-align: center; width: 20%">Jul / Aug / Sep</td>
											<td style="border-bottom: 1px solid #E6E6E6; text-align: center; width: 20%">Oct / Nov / Dec</td>
										</tr>
										<tr>
											<td>Meetings Set</td>
											<td style="text-align: center">4 / 4</td>
											<td style="text-align: center">4 / 6 <img src="{$APP_URL}app/view/images/arrow_down.png" alt="Below" /></td>
											<td style="text-align: center">4 / 4</td>
											<td style="text-align: center">5 / 4 <img src="{$APP_URL}app/view/images/arrow_up.png" alt="Above" /></td>
										</tr>
										<tr>
											<td>Meetings Attended</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>Opportunities</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>Wins</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody id="iantest">
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div onclick="javascript:alert('test')">Communication and Reporting</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									<ul>
										<li>Weeky call</li>
										<li>Review after 3 months</li>
										<li>Monthly conversation report</li>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody id="network">
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Financial</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">

	<script language="JavaScript">
	{literal}

		function handleFee(val)
		{
			if (val == 'normal')
			{
				// hide network fee split
				$('network_fee').style.display = 'none';
			}
			else if (val == 'network')
			{
				// show network fee split
				$('network_fee').style.display = 'block';
			}
		}
		
		function calculateFeeSplit()
		{
//			alert('Start calculateFeeSplit()');
			var total_fee = $('fee').value;
			var alchemis_percentage = $('alchemis_share').value;
			var alchemis_fee = total_fee * (alchemis_percentage / 100);
			$('fee_split_1').value = alchemis_fee;
			$('fee_split_2').value = total_fee - alchemis_fee;
//			alert('End calculateFeeSplit()');
		}
			
	{/literal}
	</script>

	<table class="ianlist">
		<tr>
			<td>Fee</td>
			<td><input type="text" name="fee" id="fee" maxlength="255" /></td>
			<td>
				<input type="radio" name="fee_type" id="fee_type" value="normal" onChange="javascript:handleFee(this.value);" />Normal
				<input type="radio" name="fee_type" id="fee_type" value="network" onChange="javascript:handleFee(this.value);" />Network
			</td>
		</tr>
	</table>
	
	<div id="network_fee" style="border: 1px solid red; display: none">
		<table>
			<tr>
				<td>Alchemis Percentage</td>
				<td colspan="2"><input type="text" name="alchemis_percentage" id="alchemis_share" maxlength="5" />%</td>
				<td><a href="javascript:calculateFeeSplit()">Calculate</a></td>
			</tr>
			<tr>
				<td>Alchemis Value</td>
				<td><input type="text" id="fee_split_1" value="" disabled="disabled" ></td>
				<td>Network Agent Value</td>
				<td><input type="text" id="fee_split_2" value="" disabled="disabled" ></td>
			</tr>
		</table>
	</div>

									<table class="ianlist">
										<tr>
											<th style="width: 20%">Expected Revenue</th>
											<td style="width: 80%">{$total_revenue}</td>
										</tr>
									</table>

	<table class="adminlist">
		<tr>
			<th>#</th>
			<th>Month</th>
			<th>Revenue</th>
		</tr>
		{foreach name=rev key=key from=$revenues item=revenue}
		<tr>
			<td>{$smarty.foreach.rev.iteration}</td>
			<td style="text-align: center">{$key}</td>
			<td style="text-align: center">{$revenue}</td>
		</tr>
		{/foreach}
	</table>

								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td class="vr">{*<img src="{$APP_URL}app/view/images/spacer.gif">*}</td>
			<td width="50%" class="rcol">
				<table id="remDev" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>The Pitch</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">{$pitch}</div>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Case Histories</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									{foreach name=forcase key=key from=$cases item=case}
									Case #{$smarty.foreach.forcase.iteration}
									<table class="ianlist">
										<tr>
											<th style="width: 10%">Brief</th>
											<td style="width: 90%">{$case.brief}</td>
										</tr>
										<tr>
											<th style="width: 10%">Activity</th>
											<td style="width: 90%">{$case.activity}</td>
										</tr>
										<tr>
											<th style="width: 10%">Result</th>
											<td style="width: 90%">{$case.result}</td>
										</tr>
									</table>
									{/foreach}
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
</div>

{include file="footer.tpl"}