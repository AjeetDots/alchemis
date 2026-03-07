	function setProjectRefDropdowns()
	{
		objSel = top.$('initiative_list');
		nodes = $A(objSel.options);
		new_nodes = $('client_list').options;
		nodes.each(function(node)
			{
				var new_node = new Option;
				new_node.value = node.value;
				new_node.text = node.text;
				new_nodes.add(new_node);
			});
		$('client_list').selectedIndex = objSel.selectedIndex;

		//now use ajax to populate proj ref options
		ajaxGetProjectRefs();
	}

	function showNewProjectRef()
	{
		new Effect.toggle($('client_tag'), 'appear', {duration: 0.1});
		new Effect.toggle($('project_refs'), 'appear', {duration: 0.1});
		new Effect.toggle($('span_new_project_ref_link'), 'appear', {duration: 0.1});
		new Effect.toggle($('span_existing_project_ref_link'), 'appear', {duration: 0.1});
	}

	function selectAll(select_all_item, record_type)
	{
		//NOTE: need to have two function parameters - can't just use record_type alone as this
		//doesn't give enough flexibility in where we can choose to select all records from
		var select_all = $("chk_select_all_" + select_all_item);
		var is_checked = select_all.checked;
		var form = $('frm_results');
		var buttons = form.getInputs('checkbox');
		var chk_element = "chk_" + record_type + "_";
		buttons.each(function(item)
			{
				var s = item.name;
				if (s.slice(0, chk_element.length) == chk_element)
	  				item.checked = is_checked;
	  		}
		)
	}

	function ajaxGetProjectRefs()
	{
		var ill_params = new Object;
		ill_params.item_id = $F('client_list')
		getAjaxData("AjaxClientInitiative", "", "get_project_ref_tags", ill_params, "Saving...")
	}

	function submitToAjax(action_type)
	{
		frm = $("frm_results");
		var frm_data = frm.serialize(true);
		//submitToAjax('test', Object.toJSON(frm_data));

		//document.write(Object.toJSON(frm_data));

		var ill_params = new Object;
		ill_params.company_tag = $F('company_tag');
		ill_params.post_tag = $F('post_tag');

		if ($('project_refs').style.display != 'none')
		{
			project_ref = $F('project_refs')
		}
		else
		{
			project_ref = $F('client_tag');
		}

		if (project_ref == '')
		{
			alert('Project ref cannot be blank');
			return false;
		}

		ill_params.client_tag = project_ref;
		ill_params.initiative_id = $F('client_list');
		ill_params.action_type = action_type;

		ill_params.form_data = frm_data;
		getAjaxData("AjaxFilterResults", "", "bulk_update", ill_params, "Saving...")
	}


	/* --- Ajax return data handlers --- */
	function AjaxFilterResults(data)
	{
		for (i = 1; i < data.length + 1; i++)
		{
			t = data[i-1];
			switch (t.cmd_action)
			{
				case "bulk_update":
					console.log(t.return_data);
					// alert("Records successfully tagged!");
					console.log("Records successfully tagged!");
					break;
				default:
					alert("No cmd_action specified");
					break;
			}
		}
	}

	function AjaxClientInitiative(data)
	{
		for (i = 1; i < data.length + 1; i++)
		{
			t = data[i-1];
			switch (t.cmd_action)
			{
				case "get_project_ref_tags":
					var span = $("span_project_ref_html");
					span.innerHTML = t.project_ref_html;
					// check the 'add new' textbox and the 'use existing project ref' link are
					// hidden and that the 'use new project ref' link is visible
					// now that we have changed client
					$('client_tag').hide();
					$('span_new_project_ref_link').show();
					$('span_existing_project_ref_link').hide();
					break;
				default:
					alert("No cmd_action specified");
					break;
			}
		}
	}