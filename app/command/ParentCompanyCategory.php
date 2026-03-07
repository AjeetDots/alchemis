<?php

use app_controller_Response as Response;

class app_command_ParentCompanyCategory extends app_command_ResourceCommand
{

	public function lists()
	{
		$categories = app_model_ParentCompanyCategory::where('parent_company_id', 0)->get();

		return Response::json($categories);
	}

	public function listSubCategories()
	{
		$id = $this->request->input->get('id');
		$subcategories = app_model_ParentCompanyCategory::where('parent_company_id', $id)->get();
		return Response::json($subcategories);
	}

}
