{include file="header2.tpl" title="Edit Tiered Characteristic"}

<form action="index.php?cmd=TieredCharacteristicEdit" method="post">

	<input type="hidden" id="parent_object_type" value="{$parent_object_type}" />
	<input type="hidden" id="parent_object_id" value="{$parent_object_id}" />
	<input type="hidden" id="category_id" value="{$category_id}" />

	<h2>Tiered Characteristic: {$characteristic->getId()} {$characteristic->getValue()}</h2>
	
	<table class="ianlist">
		<tr>
			<th style="vertical-align: top; width: 20%">Parent Category</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<select id="parent_id" name="parent_id">
					<option value="0">- None -</option>
					{html_options options=$parents selected=$characteristic->getParentId()}
				</select>
			</td>
		</tr>
		<tr>
			<th style="vertical-align: top; width: 20%">Category</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<input type="text" id="value" name="value" value="{$characteristic->getValue()}" style="width: 250px" />
			</td>
		</tr>
{*		<tr>
			<th style="vertical-align: top; width: 20%">Type</th>
			<td colspan="2" style="vertical-align: top; width: 80%">
				<select id="category_id" name="category_id">
					{html_options options=$categories}
				</select>
			</td>
		</tr>
*}	</table>

	<input type="hidden" id="category_id" name="category_id" value="1" />
	<input type="hidden" id="task" name="task" value="save" />
	<input type="hidden" id="id" name="id" value="{$characteristic->getId()}" />
	<input type="submit" value="Save" onclick="removeElementContainer();" />
	
</form>

{include file="footer2.tpl"}