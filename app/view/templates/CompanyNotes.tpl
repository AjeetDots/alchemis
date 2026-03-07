{include file="header2.tpl" title="Company Notes"}

<!--<script type="text/javascript" src="{$APP_URL}app/view/templates/Workspace.js"></script>-->

<script language="JavaScript" type="text/javascript">
{literal}

function submitform(pressbutton){
//	alert('submitform(' + pressbutton + ')');
	document.add_company_note.task.value=pressbutton;
	
	try 
	{
		document.add_company_note.onsubmit();
	}
	
	catch(e)
	{}
	
	document.add_company_note.submit();
}


function submitbutton(pressbutton)
{
//	alert('submitbutton(' + pressbutton + ')');
	
	if (pressbutton == 'save') 
	{
		submitform( pressbutton );
		return;
	}
}
	
// this array used to hold the inPlaceEditor objects
note_inplace_editor_ids = new Array();

function makeInPlaceEditor(note_id)
{
	if (note_inplace_editor_ids.length > 0)
	{
		var id = note_inplace_editor_ids.find( function(editor){
				return (editor == note_id);
			});
			
		if (id)
		{	
			// in place editor for this id already exists
			return;
		}
	}	
		
	var in_place_editor = new Ajax.InPlaceEditor('edit_note_' + note_id , '', {rows:15, cols:40, externalControl: 'img_edit_note_' + note_id, ill_cmd: 'AjaxCompany', ill_cmd_action: 'update_note', ill_item_id: note_id, ill_field: 'note'});
	note_inplace_editor_ids.push(note_id);
	in_place_editor.enterEditMode('click');
	in_place_editor = null;
}

function AjaxCompany(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "update_note":
				$("edit_note_" + t.item_id).innerHTML = t.note;
				break;
			
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}

{/literal}
parent.$('company_note_count').innerHTML={$notes|@count};
</script>

<div id="div_company_notes_screen" class="module_content" style="border: solid 1px #ccc; padding: 2px; overflow: auto;">

	<h2>Company Notes</h2>
	<a href="#" onclick="javascript:new Effect.toggle($('div_add_new_note'), 'blind', {literal}{duration: 0.3}{/literal});return false;">Add new note</a>
	
	<div id="div_add_new_note" style="display: none">
		<form action="index.php?cmd=CompanyNotes" method="post" name="add_company_note" autocomplete="off">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="company_id" value="{$company_id}" />
			
			<textarea id="note" name="note" cols="59" rows="10"></textarea>
			<br />
			<input type="button" value="Submit" onclick="javascript:submitbutton('save')" />
		</form>
	</div>
	{if $notes|@count == 0}

		<p style="text-align: center"><em>&lt;&mdash; No notes found &mdash;&gt;</em></p>

	{else}

		<table style="width: 100%" class="default">
			{foreach name="result_loop" from=$notes item=result}
			<tr>
				<td>
					<strong>{$result.created_at|date_format:$smarty.config.FORMAT_DATETIME_SHORT} : {$result.handle}</strong>
				</td>
			</tr>
			<tr>
				<td>
					<img id="img_edit_note_{$result.id}" src="{$APP_URL}app/view/images/icon_edit.jpg" style="vertical-align: middle" onclick="javascript:makeInPlaceEditor('{$result.id}');" />
					<span id="edit_note_{$result.id}">{$result.note|nl2br}</span>
				</td>
			</tr>
			<tr>
				<td>
					<hr style="border: 0px solid #ccc; border-top-width: 1px; height: 0px" />
				</td>
			</tr>
			{/foreach}
		</table>
	{/if}
</div>

{include file="footer.tpl"}