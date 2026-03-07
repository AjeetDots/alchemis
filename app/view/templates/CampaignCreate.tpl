{include file="header.tpl" title="Campaign Create"}

<div class="cfg">

	<table border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr">
			<td colspan="3">Create Client</td>
		</tr>
		<tr valign="top">
			<td width="50%" class="lcol">
				<table id="remDev" border="0" cellpadding="0" cellspacing="0">


					<tbody>
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Campaign Details</div>
									</div>
								</div>
							</td>
						</tr>
						<tr class="keyRow">
							<td>
								<div class="module_content">
									<table class="ianlist">
										<tr>
											<th style="width: 20%">Name</th>
											<td><input type="text" name="campaign_name" value="{$campaign_name}" maxlength="255" /></td>
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
											<th>Call Name</th>
											<td><input type="text" name="email" maxlength="255" /></td>
										</tr>
										<tr>
											<th>Email</th>
											<td><input type="text" name="email" maxlength="255" /></td>
										</tr>
									</table>
									<p><em>Ability to add multiple NBMs?</em></p>
									<p><em>Upon NBM selection should load email, telephone and mobile details from NNBM profile.</em></p>
								</div>
							</td>
						</tr>
					</tbody>


				</table>
			</td>
			<td class="vr">{*<img src="{$APP_URL}app/view/images/spacer.gif">*}</td>
			<td width="50%" class="rcol">
				<table id="remDev" border="0" cellpadding="0" cellspacing="0">


					<tbody id="network">
						<tr class="devRow">
							<td>
								<div class="module_tab">
									<div>
										<div>Financials</div>
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
											var total_fee = $('fee').value;
											var alchemis_percentage = $('alchemis_share').value;
											var alchemis_fee = total_fee * (alchemis_percentage / 100);
											$('fee_split_1').value = alchemis_fee;
											$('fee_split_2').value = total_fee - alchemis_fee;
										}
											
									{/literal}
									</script>

									<table class="ianlist">
										<tr>
											<th style="width: 20%">Fee</th>
											<td style="width: 80%"><input type="text" name="fee" id="fee" maxlength="255" /></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<input type="radio" name="fee_type" id="fee_type" value="normal" onChange="javascript:handleFee(this.value);" />Normal<br />
												<input type="radio" name="fee_type" id="fee_type" value="network" onChange="javascript:handleFee(this.value);" />Network<br />
											</td>
										</tr>
									</table>
	
									<div id="network_fee" style="border: 0px solid red; display: none">
										<table class="ianlist">
											<tr>
												<th>Alchemis Percentage</th>
												<td colspan="2"><input type="text" name="alchemis_percentage" id="alchemis_share" maxlength="5" />%</td>
												<td><a href="javascript:calculateFeeSplit()">Calculate</a></td>
											</tr>
											<tr>
												<th>Alchemis Value</th>
												<td><input type="text" id="fee_split_1" value="" disabled="disabled" ></td>
												<th>Network Agent Value</th>
												<td><input type="text" id="fee_split_2" value="" disabled="disabled" ></td>
											</tr>
										</table>
									</div>

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