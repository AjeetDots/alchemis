{include file="header.tpl" title="Whitelist"}

<form action="index.php?cmd=Whitelist&action=store" method="post" name="adminForm" autocomplete="off">
 <fieldset class="adminform">
    <legend>Add IP to Whitelist</legend>
   <table>
      <tr>
        <td style="width: 80px" {if isset($errors.ip)} class="key_error" title="{$errors.ip.0}"{else}class="key"{/if}>
          <label for="ip">IP *</label>
        </td>
        <td><input type="text" name="ip" id="ip" style="width: 200px" value="{$input.ip|default:''}" /></td>
      </tr>
      <tr>
        <td style="width: 80px" {if isset($errors.description)} class="key_error" title="{$errors.description.0}"{else}class="key"{/if}>
          <label for="description">Description</label>
        </td>
        <td><input type="text" name="description" id="description" style="width: 200px" value="{$input.description|default:''}" /></td>
      </tr>
   </table>
 </fieldset>
  <input type="submit" value="Submit" />
</form>

<table class="adminlist sortable" id="sortable_1" cellspacing="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>IP</th>
        <th>Description</th>
        <th>Last Login</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    {foreach from=$whitelist item=item}
      <tr>
        <td>{$item->id}</td>
        <td>{$item->ip}</td>
        <td>{$item->description}</td>
        <td>{$item->last_login}</td>
        <td>
          <a href="index.php?cmd=Whitelist&action=destroy&id={$item->id}">Delete</a>
        </td>
      </tr>
      {/foreach}
    </tbody>
  </table>


{include file="footer.tpl"}