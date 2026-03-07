<?php

use app_controller_Response as Response;

class app_command_Category extends app_command_ResourceCommand
{

	public function lists()
	{
		$categories = app_model_Category::where('parent_id', 0)->get();

		return Response::json($categories);
	}

	public function listSubCategories()
	{
		$id = $this->request->input->get('id');
		$subcategories = app_model_Category::where('parent_id', $id)->get();
		return Response::json($subcategories);
	}

}
