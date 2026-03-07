<?php

use app_controller_Response as Response;

class app_command_FilterController extends app_command_BaseCommand
{

  public function deleted()
  {
    $session = Auth_Session::singleton();
    $user = $session->getSessionUser();

    $deleted_filters_personal = app_domain_Filter::findDeletedPersonalByUserId($user['id']);
    $deleted_filters_campaign = app_domain_Filter::findDeletedCampaignFiltersByUserId($user['id']);
    $deleted_filters_global = app_domain_Filter::findDeletedGlobalFilters();

    return Response::view('FilterListDeleted', [
      'deleted_filters_personal' => $deleted_filters_personal,
      'deleted_filters_personal_count' => count($deleted_filters_personal->toRawArray()),
      'deleted_filters_campaign' => $deleted_filters_campaign,
      'deleted_filters_campaign_count' => count($deleted_filters_campaign->toRawArray()),
      'deleted_filters_global' => $deleted_filters_global,
      'deleted_filters_global_count' => count($deleted_filters_global->toRawArray()),
      'user' => $user
    ]);

  }


  public function restore()
  {
    $id = $this->request->input->get('id');
    $filter = app_model_Filter::find($id);
    $filter->deleted = 0;
    $filter->save();
    return Response::redirect(['url' => $this->request->referrer]);
  }


  public function delete()
  {
    $data = $this->request->input->all();

    foreach($data as $key => $val){
      if(strpos($key, 'filter_') !== false){
        $id = str_replace('filter_', '', $key);
        $filter = app_model_Filter::find($id);
        if(!$filter) continue;
        $filter->deleted = true;
        $filter->save();
      }
    }

    return Response::redirect(['url' => $this->request->referrer]);
  }

}
