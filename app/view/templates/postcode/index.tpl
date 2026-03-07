{include file="header.tpl" title="Postcode"}

<form action="index.php?cmd=Postcode&action=store" method="post" name="adminForm" autocomplete="off">
 <fieldset class="adminform">
    <legend>Add Postcode</legend>
    <table>
      <tr>
        <td style="width: 80px" {if $errors.postcode} class="key_error" title="{ $errors.postcode.0 }"{else}class="key"{/if}>
          <label for="postcode">Postcode *</label>
        </td>
        <td><input type="text" name="postcode" id="postcode" style="width: 200px" value="{$input.postcode}" /></td>
      </tr>
   </table>
 </fieldset>
  <input type="submit" value="Submit" />
</form>

<table class="adminlist sortable" id="sortable_1" cellspacing="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Postcode</th>
      </tr>
    </thead>
    <tbody>
    {foreach from=$postcodes item=item}
      <tr>
        <td>{$item->id}</td>
        <td>{$item->postcode}</td>
      </tr>
      {/foreach}
    </tbody>
  </table>


{include file="footer.tpl"}