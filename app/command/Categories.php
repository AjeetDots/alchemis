<?php

use app_controller_Response as Response;

class app_command_Categories extends app_command_BaseCommand
{

	public function prepare()
	{
  	$request = $this->request;
  	$input = $request->input;

  	if(!$request->action){
  		$request->action = 'index';
  	}
  }

	public function index()
	{
    $categories = app_model_Category::where('parent_id', 0)->get();
  	return Response::view('categories.index', [
      'categories' => $categories,
      // Provide safe defaults so the template
      // doesn't emit notices on first load.
      'parent_id' => 0,
      'errors'    => [],
      'input'     => ['value' => ''],
    ]);
	}

  public function create()
  {
    $input = $this->request->input;
    $id = $input->get('id');
    if(!$id) $id = 0;
    return Response::view('categories.create', [
      'parent_id' => $id,
      // First load of the create form has
      // no validation errors or prior input.
      'errors'    => [],
      'input'     => ['value' => ''],
    ]);
  }

  public function store()
  {
    $input = $this->request->input;
    $data = $input->all();

    $validator = new Validator($data, [
      'value' => 'required',
     ]);

    if($validator->fails()){
      $messages = $validator->messages();
      return Response::view('categories.create', [
        'success' => false,
        'errors' => $messages->toArray(),
        'feedback' => implode('</li><li>', $messages->all()),
        'input' => $data
        ]);
       }

    $category = app_model_Category::create([
      'value' => $data['value'],
      'category_id' => 1,
      'parent_id' => $data['parent_id']
    ]);

    $redirect = 'Categories';
    if($category->parent_id != 0){
      $redirect .= '&action=subcategory&id=' . $category->parent_id;
    }
    return Response::redirect($redirect);
  }

  public function edit()
  {
    $input = $this->request->input;
    $id = $input->get('id');
    $category = app_model_Category::find($id);
    return Response::view('categories.edit', [
      'category' => $category
    ]);
  }

  public function update()
  {
    $input = $this->request->input;
    $id = $input->get('id');

    $category = app_model_Category::find($id);
    $category->value = $input->get('value');
    $category->save();

    $redirect = 'Categories';
    if($category->parent_id != 0){
      $redirect .= '&action=subcategory&id=' . $category->parent_id;
    }
    return Response::redirect($redirect);
  }
  

  public function subcategory()
  {
    $input = $this->request->input;
    $id = $input->get('id');

    $subcategories = app_model_Category::where('parent_id', $id)->get();
    
    return Response::view('categories.subcategory', [
      'subcategories' => $subcategories,
      'id' => $id
    ]);
  }

}
