{include file="header.tpl" title="Campaign Documents"}

<table class="adminform">
    <tr>
        <td width="50%" valign="top">
            <table id="" class="adminlist" border="0" cellpadding="0" cellspacing="0">
                <tr class="hdr">
                    <td>
                        Gui Settings &nbsp;&nbsp;|&nbsp;&nbsp;
                        {*<input type="button" id="add_new_document" name="add_new_document" value="Add New Document" onclick="javascript:addDocument({$campaign_id}); return false;" />*}
                    </td>
                </tr>

                <tr valign="top">
                    <td>
                        <form method="post" action="">
                            Campaign Default view:
                            <select name="campaign_view_default" id="campaign_view_default">
                                <option value="">-- select --</option>
                                <option value="category" {if $campaignDefaultView == 'category'}selected="selected"{/if}>Categories view</option>
                                <option value="characteristics" {if $campaignDefaultView == 'characteristics'}selected="selected"{/if}>Characteristic View</option>
                            </select>
                            <br>

                            <input type="submit" value="submit">
                        </form>

                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" valign="top">
            <iframe id="iframe1" name="iframe1" src="" scrolling="no" border="0" frameborder="no" style="height: 760px; width: 100%; overflow-x: hidden; overflow-y: y:auto"></iframe>
        </td>
    </tr>
</table>

{include file="footer.tpl"}