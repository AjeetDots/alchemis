<?php

/**
 * Defines the app_view_WorkspaceSearch class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceSearch extends app_view_View
{
	protected function doExecute()
	{
		$company_id = $this->request->getObject('company_id');
		if ($company_id == '' || $company_id === null)
		{
			$this->smarty->assign('company_id', null);
		}
		else
		{
			// general vars
			$this->smarty->assign('company_id', $this->request->getObject('company_id'));
			$this->smarty->assign('company', $this->request->getObject('company'));
			$this->smarty->assign('post_id', $this->request->getObject('post_id'));
			$this->smarty->assign('post', $this->request->getObject('post'));
			$this->smarty->assign('contact_id', $this->request->getObject('contact_id'));
			$this->smarty->assign('contact', $this->request->getObject('contact'));

			// workspace company screen vars
			$this->smarty->assign('company_note_count', $this->request->getProperty('company_note_count'));
			$this->smarty->assign('company_posts_job_title', $this->request->getObject('company_posts_job_title'));
			$this->smarty->assign('company_posts_first_name', $this->request->getObject('company_posts_first_name'));
			$this->smarty->assign('company_do_not_call', $this->request->getProperty('company_do_not_call'));
			$company_id = $this->request->getObject('company_id');
			$company = app_model_Company::find($company_id);
			$parent_companies = $company->parents();
			$this->smarty->assign('parent_companies', $parent_companies);


			// workspace post screen vars
			$this->smarty->assign('post_note_count', $this->request->getProperty('post_note_count'));
			$this->smarty->assign('post_initiatives_options', $this->request->getObject('post_initiatives_options'));
			$this->smarty->assign('post_initiatives_selected_option', $this->request->getObject('post_initiatives_selected_option'));
			$this->smarty->assign('client_initiatives_options', $this->request->getObject('client_initiatives_options'));
			$this->smarty->assign('client_initiatives_selected_option', $this->request->getObject('client_initiatives_selected_option'));

			// workspace post initiative screen vars
			$initID = $this->request->getObject('initiative_id');
            $initiative = app_model_Initiatives::with('campaign')->find($initID);
			$this->smarty->assign('initiative_id', $initID);
			if(isset($initiative->campaign)){
				$this->smarty->assign('defaultView', $initiative->campaign->getCampaignSetting(app_model_CampaignSetting::CAMPAIGN_SETTING_DEFAULT_VIEW));
			}
			$this->smarty->assign('post_initiative',  $this->request->getObject('post_initiative'));
			$this->smarty->assign('project_refs',  $this->request->getObject('project_refs'));
			$this->smarty->assign('meetings',  $this->request->getObject('meetings'));
			$this->smarty->assign('actions',  $this->request->getProperty('actions'));
			$this->smarty->assign('overdue_actions',  $this->request->getProperty('overdue_actions'));

			$this->smarty->assign('information_requests',  $this->request->getObject('information_requests'));

			// workspace company initiatives screen vars
			$this->smarty->assign('posts', $this->request->getObject('posts'));
			$this->smarty->assign('initiative_name', $this->request->getProperty('initiative_name'));

			// workspace notes screen vars
			$this->smarty->assign('notes', $this->request->getObject('notes'));

			$this->smarty->assign('tab', 'WorkspaceSearch');

			$this->smarty->assign('companyTelephoneTpsStatus', (array)$this->request->getProperty('company_telephone_tps_status'));

			$workspace_company_screen = $this->smarty->fetch('WorkspaceCompany.tpl');
			$this->smarty->assign('workspace_company_screen', $workspace_company_screen);
            try{
                if($this->request->getProperty('post_telephone_tps_status')){
                    $this->smarty->assign('postTelephoneTpsStatus', (array)$this->request->getProperty('post_telephone_tps_status'));
                }
            }
            catch(\Exception $e){}
			$workspace_post_screen = $this->smarty->fetch('WorkspacePost.tpl');
			$this->smarty->assign('workspace_post_screen', $workspace_post_screen);

			$workspace_post_initiative_screen = $this->smarty->fetch('WorkspacePostInitiative.tpl');
			$this->smarty->assign('workspace_post_initiative_screen', $workspace_post_initiative_screen);

			$workspace_company_initiatives_screen = $this->smarty->fetch('WorkspaceCompanyInitiatives.tpl');
			$this->smarty->assign('workspace_company_initiatives_screen', $workspace_company_initiatives_screen);

			$workspace_notes_screen = $this->smarty->fetch('WorkspaceNotes.tpl');
			$this->smarty->assign('workspace_notes_screen', $workspace_notes_screen);
		}
		$this->smarty->display('WorkspaceSearch.tpl');
	}
}

?>