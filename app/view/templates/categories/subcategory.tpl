{include file="header.tpl" title="Characteristic List"}
<a href="index.php?cmd=Categories">Back</a>

<a href="index.php?cmd=Categories&action=create&id={$id}">Add Subcategory</a>
<hr> 
<table class="adminlist sortable" id="sortable_1" cellspacing="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Subcategory</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
    {foreach from=$subcategories item=subcategory}
      <tr>
        <td>{$subcategory->id}</td>
        <td>{$subcategory->value}</td>
        <td><a href="index.php?cmd=Categories&action=edit&id={$subcategory.id}">Edit</a></td>
      </tr>
      {/foreach}
    </tbody>
  </table>


{include file="footer.tpl"}