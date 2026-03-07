{config_load file="example.conf"}

{* NOTE: Need to have the following closing form tag otherwise the form 'frm_mailer_item_XXXX' won't save. This is because
the page from which this html is called already has a form open when the html is inserted into the popup. This causes a conflict
since there is a form inside a form. So we need to close that form first (using the following </form> tag - then all is well*}
{* --DO NOT REMOVE THE FOLLOWING TAG -- *}
</form>
<form id="frm_mailer_item_{$mailer_item_id}" name="frm_mailer_item_{$mailer_item_id}" action="#" method="post">
	<table class="sortable" id="sortable_add_response">
		<tr>
			<th>Date</th>
			<td>{html_select_date 
				prefix           = "date_"
				time             = ""
				start_year       = "-1" 
				field_order      = "DMY"
				day_value_format = "%02d"}</td>
		</tr>
		<tr>	
			<th style="vertical-align:top">Response</th>
			<td>
				<table>
					{foreach name="responses" from=$responses item=response}
					<tr>
						<td><input type="checkbox" id="chk_{$response.id}" name="chk_{$response.id}" /></td>
						<td>{$response.description}</td>
					</tr>
					{/foreach}
				</table>
			</td>
		</tr>
		<tr>	
			<th>Note</th>
			<td><textarea id="note_textarea" name="note_textarea" rows="4" cols="50"></textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				<span style="float: right">
					<input type="button" value="Update" onclick="javascript:saveResponses({$mailer_item_id}); return false" />
					<input type="button" value="Cancel" class="popup_closebox" />
				</span>
			</td>
		</tr>
	
	</table>
</form>