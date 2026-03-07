{include file="header.tpl" title="Characteristic List"}
 <form action="index.php?cmd=Categories&action=update" method="post" name="adminForm" autocomplete="off">
  
  <fieldset class="adminform">
     <legend>Category</legend>
     <table>
      <tr>
        <td style="width: 80px" {if $errors.value} class="key_error" title="{ $errors.value.0 }"{else}class="key"{/if}>
           <label for="value">Value *</label>
        </td>
         <td><input type="text" name="value" id="value" style="width: 200px" value="{$category.value}" maxlength="255"  ac-value="id" ac-text="value" /></td>
      </tr> 
    </table>
  </fieldset>
  <input type="hidden" name="id" value="{$category.id}">
  <input type="submit" value="Save" />&nbsp;|&nbsp;
    <input type="reset" value="Reset" onclick="window.location='index.php?cmd=Categories';"/>
  </form>