{include file="header.tpl" title="Workspace Filter"}

{if !isset($company_id) || $company_id == null || $company_id == ""}
	No filter loaded. Please click on the <a href="#" onclick="javascript:parent.loadTab(9, 'FilterList');">Filters</a> tab to load a filter.
{else}
	<script type="text/javascript" src="{$APP_URL}app/view/templates/WorkspaceFilter.js"></script>
	<script type="text/javascript" src="{$APP_URL}app/view/templates/Workspace.js?date=03Sept2021"></script>
	<table class="adminform" style="width: 100%;">
		<tr>
			<td{* width="800px"*} style="vertical-align: top;">
				<!-- hold company, info pane and prospect screens -->
				<table border="0" style="{*border-color:green; border-width:medium;*} width: 100%;">
					<tr class="keyRow" style="vertical-align: top;">
						<!-- width on following td should be 400px -->
						<td style="vertical-align: top; width: 350px;">
							<!-- / Company Info -->
							<table id="remDev" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<div id="div_company_menu" style="width: 350px; border: 1px solid #999;background-color: #eee; height: 18px; padding: 3px" >
											<span style="float: right; padding-right: 5px;">
												<a href="#" onclick="javascript:goPreviousCompanyId();return false;" title="Go to previous company in search results" ><img src="{$APP_URL}app/view/images/icons/resultset_previous.png" alt="Previous company" /></a>
												<a href="#" onclick="javascript:goNextCompanyId();return false;" title="Go to next company in search results" ><img src="{$APP_URL}app/view/images/icons/resultset_next.png" alt="Next company" /></a>
											</span>
											<span style="padding-left: 0px; vertical-align: center; width: 100%">
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=ObjectTieredCharacteristics&amp;parent_object_type=app_domain_Company&amp;parent_object_id=' + $F('company_id')); return false;" title="Go to categories for this company" ><img src="{$APP_URL}app/view/images/icons/plugin.png" alt="Categories" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=WorkspaceCompanyBrands&amp;company_id=' + $F('company_id') + '&amp;category_id=1');return false;" title="Go to brands for this company" ><img src="{$APP_URL}app/view/images/icons/basket.png" alt="Brands" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=ObjectCharacteristics&amp;id=' + $F('company_id') + '&amp;type=company&initiative_id={$initiative_id}');return false;" title="Go to characteristics for this company" ><img src="{$APP_URL}app/view/images/icons/tag_blue.png" alt="Characteristics" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=CompanyNotes&amp;company_id=' + $F('company_id'));return false;" title="Go to notes for this company" ><img src="{$APP_URL}app/view/images/icons/comments.png" alt="Notes" /></a>
												(<span id="company_note_count">{$company_note_count}</span>)
												&nbsp;&nbsp;
												<select id="company_menu" name="company_menu" onchange="javascript:doMenuItem(this.options[this.selectedIndex].value);">
													<option selected value="">... more options</option>
													<option value="CompanyTags">View Tags</option>
													<option value="CompanyNoteCreate">Add Note</option>
													{if $session_user->hasPermission('permission_delete_company')}
														<option value="CompanyDelete">Delete Company</option>
													{/if}
												</select>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<!-- full width should be 350px -->
									<td class="keyRow" style="width: 350px; vertical-align:top; height: 150px">
										<!-- Company Screen -->
										<div id="div_company">
											{$workspace_company_screen}
										</div>
										<!-- / Company Screen -->
									</td>
								</tr>
								<tr>
									<td>
										<div id="div_post_menu" style="width: 350px; border: 1px solid #999;background-color: #eee; height: 18px; padding: 3px" >
											<span style="float: right; padding-right: 5px;">
												<a href="#" onclick="javascript:goPreviousPostId();return false;" title="Go to previous post in search results"><img src="{$APP_URL}app/view/images/icons/resultset_previous.png" alt="Previous post" /></a>
												<a href="#" onclick="javascript:goNextPostId();return false;" title="Go to next post in search results"><img src="{$APP_URL}app/view/images/icons/resultset_next.png" alt="Next post" /></a>
											</span>
											<span style="padding-left: 0px; vertical-align: center">
												<a href="#" onclick="javascript:$('popup_posts').style.display='';$('popup_posts').popup.show();goToHash('popup_posts', 'tr_post_' + $F('post_id'));return false;" ><img src="{$APP_URL}app/view/images/icons/group.png" alt="Posts" title="View all posts at this company" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=PostDisciplines&amp;post_id=' + $F('post_id'));return false;" title="Go to disciplines for this post" ><img src="{$APP_URL}app/view/images/icons/folder_user.png" alt="Discplines" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=ObjectCharacteristics&amp;id=' + $F('post_id') + '&amp;type=post&initiative_id={$initiative_id}');return false;" title="Go to characteristics for this post" ><img src="{$APP_URL}app/view/images/icons/tag_blue.png" alt="Characteristics" /></a>
												&nbsp;&nbsp;
												<a href="#" onclick="javascript:openInfoPane('index.php?cmd=PostNotes&amp;post_id=' + $F('post_id'));return false;" title="Go to notes for this post" ><img src="{$APP_URL}app/view/images/icons/comments.png" alt="Notes" /></a>
												(<span id="post_note_count">{$post_note_count}</span>)
											</span>
											<span style="margin-left: 10px; padding-left: 0px; vertical-align: right">
												<select id="post_menu" name="post_menu" onchange="javascript:doMenuItem($F('post_menu'));">
													<option value="">... more options</option>
													{if $session_user->hasPermission('permission_edit_post_record')}
														<option value="PostEdit">Edit Post</option>
														<option value="PostEditLocation">Change Location</option>
													{/if}
													{if $session_user->hasPermission('permission_create_post')}
														<option value="PostCreate">Create Post</option>
													{/if}
													{if $session_user->hasPermission('permission_add_notes')}
														<option value="PostNoteCreate">Create Note</option>
													{/if}
													<option value="PostTags">View Tags</option>
													{if $session_user->hasPermission('permission_delete_post')}
														<option value="PostDelete">Delete Post</option>
													{/if}
												</select>
											</span>
										</div>
									</td>
								</tr>
								<tr class="keyRow">
									<td style="width: 350px; vertical-align:top; height: 100px">
										<!-- Post Screen -->
										<div id="div_post">
											{$workspace_post_screen}
										</div>
										<!-- / Post Screen -->
									</td>
								</tr>
							</table>
							<!-- / Company Info -->
						</td>
						<td class="keyRow">
							<!-- Post Initiative Screen -->
							<table id="remDev" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											<!-- following div s/be 350px -->
											<div id="div_post_initiative_menu" style="width: 350px; border: 1px solid #999;background-color: #eee; height: 18px; padding: 3px" >
												<span style="padding-left: 0px; vertical-align: center">
													<a href="#" onclick="javascript:logCommunication(7);return false;" title="Call this post for the selected initiative"><img src="{$APP_URL}app/view/images/icons/user_comment_add.png" alt="Call" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:logNonEffective();return false;" title="Make a non-effective call to this post for the selected initiative"><img src="{$APP_URL}app/view/images/icons/user_comment.png" alt="Non-effective call" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:addPostInitiative($F('post_id'), top.$F('initiative_list'));return false;" title="Add a record for the default client to this post"><img src="{$APP_URL}app/view/images/icons/user_add.png" alt="Add default client" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=Meetings&post_initiative_id=' + $F('post_initiative_id') + '&referrer_type=workspace');}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Displays meetings for this post initiative"><img src="{$APP_URL}app/view/images/icons/date_go.png" alt="alt="View meetings"" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id=' + $F('post_initiative_id') + '&referrer_type=workspace&type_id=2');}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Displays actions for this post initiative"><img src="{$APP_URL}app/view/images/icons/script_go.png" alt="alt="View information requests"" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=ObjectCharacteristics&amp;id=' + $F('post_initiative_id') + '&amp;type=post_initiative&initiative_id={$initiative_id}');}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Go to characteristics for this post initiative" ><img src="{$APP_URL}app/view/images/icons/tag_blue.png" alt="Characteristics" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=PostInitiativeActions&post_initiative_id=' + $F('post_initiative_id') + '&referrer_type=workspace');}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Displays actions for this post initiative"><img src="{$APP_URL}app/view/images/icons/flag_green.png" alt="Actions" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:deleteLastCall();return false;" title="Delete last communication for the selected initiative"><img src="{$APP_URL}app/view/images/icons/delete.png" alt="Non-effective call" /></a>
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=PostInitiativeEdit&id=' + $F('post_initiative_id') + '&parent_tab=' + tab_id);}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Enables editing of this post initiative"><img src="{$APP_URL}app/view/images/icons/user_edit.png" alt="Send e-mail" /></a>
													{if $session_user->hasPermission('permission_email_to_prospect')}
													&nbsp;&nbsp;
													<a href="#" onclick="javascript:if ($('post_initiative_id')){literal}{openInfoPane('index.php?cmd=CommunicationEmailCreate&post_initiative_id=' + $F('post_initiative_id'));}{/literal}else{literal}{alert('Action not available');}{/literal}return false;" title="Send an e-mail to this contact"><img src="{$APP_URL}app/view/images/icons/email_add.png" alt="Send e-mail" /></a>
													{/if}
												</span>
											</div>
										</td>
									</tr>
									<tr class="keyRow">
										<!-- following width s/be 350px -->
										<td style="width: 350px; height: 350px">
											<div id="div_post_initiative" style="height:320px;">
												{$workspace_post_initiative_screen}
											</div>
										</td>
									</tr>
							</table>
							<!-- / Post Initiative Screen -->
						</td>
					</tr>
					<tr> <!-- company initiatives screen -->
						<td colspan="2">
							<table width="100%">
								<tbody id="network">
									<tr >
										<td>
											<!-- width on following s/be 700px -->
											<div id="div_company_initiatives" style="border: solid 1px #ccc; padding: 2px; width: 700px; height: 300px; overflow: auto;">
												{$workspace_company_initiatives_screen}
											</div>	
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr> <!-- / company initiatives screen -->
				</table>
			</td>
			<!-- width on following cell s/be 350px -->
			<td width="450px" valign="top">
				<!-- notes screen -->
				<table id="remDev" border="0" cellpadding="0" cellspacing="0">
					<tr class="keyRow">
						<td valign="top" width="450px">
							<!-- width on following s/be 350px -->
							<iframe id="ifr_notes" name="ifr_notes" src="index.php?cmd=WorkspaceNotes&post_id={$post_id}&initiative_id={$initiative_id}&post_initiative_id={$post_initiative_id}" scrolling="yes" border="0" frameborder="no" style="height: 704px; width: 100%; overflow-x: hidden; overflow-y: y:auto">
							</iframe>
						</td>
					</tr>
				</table>
				<!-- / notes screen -->
			</td>
			<td valign="top" width="500px"> <!-- info pane -->
				<!--Info Pane-->
				<iframe id="ifr_info" name="ifr_info" src="" scrolling="yes" border="0" frameborder="no" style="height: 704px; width: 100%; overflow-x: hidden; overflow-y: y:auto">
				</iframe>
			</td>
		</tr>
	</table>
{/if}

<script language="JavaScript" type="text/javascript">
{literal}
	
	// hide relevant menu bars
	hideMenus();
	loadCompanyCharacteristics();

	// number of the tab (SearchWorkspace will be 5) - we may need to pass this into child windows
	var tab_id = 7;
	
	function loadCompanyCharacteristics()
	{
		var defaultView = undefined;
		{/literal}defaultView = '{$defaultView}';{literal}
		// load company characteristics into info pane
		if ($('initiative_id'))
		{
			if ($F('initiative_id') == 1 || defaultView == 'characteristics') //alchemis
			{
				str = "index.php?cmd=ObjectCharacteristics&id=" + $F('company_id') + "&type=company&initiative_id={/literal}{$initiative_id}{literal}";
			}
			else
			{
				str = "index.php?cmd=ObjectTieredCharacteristics&parent_object_type=app_domain_Company&parent_object_id=" + $F('company_id') + "&initiative_id={/literal}{$initiative_id}{literal}";
			}
		}
		else
		{
			if (top.$F("initiative_list") == 1 || defaultView == 'characteristics') //alchemis
			{
				str = "index.php?cmd=ObjectCharacteristics&id=" + $F('company_id') + "&type=company&initiative_id={/literal}{$initiative_id}{literal}";
			}
			else
			{
				str = "index.php?cmd=ObjectTieredCharacteristics&parent_object_type=app_domain_Company&parent_object_id=" + $F('company_id') + "&initiative_id={/literal}{$initiative_id}{literal}";
			}
		}
		
		openInfoPane(str);
	}
		
	function screenSize()
	{
		var parent_height = Math.floor((window.innerHeight - 70));
		var company_height = $('ifr_company').style.height;
		company_height = Math.floor(company_height.substring(0, company_height.length - 2));
		var post_height = $('ifr_post').style.height;
		post_height = Math.floor(post_height.substring(0, post_height.length - 2));
		
//		$('ifr_company_initiatives').style.height =  (parent_height - (company_height + post_height)) + 'px';
//		$('ifr_notes').style.height = parent_height + 'px';
//		$('ifr_info').style.height = parent_height + 'px';
	}

{/literal}
</script>

{include file="footer.tpl"}
