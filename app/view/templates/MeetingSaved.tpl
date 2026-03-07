{include file="header2.tpl" title="Meeting Saved"}

Meeting successfully saved!

<script language="JavaScript" type="text/javascript">

var source_tab = "{$source_tab}";
{literal}
function showMeetings(post_initiative_id, company_id)
{
	//alert(post_initiative_id + " : " + company_id);
	
	var ill_params = new Object;
	//set item_id - the id of the object we are dealing with
	ill_params.item_id = post_initiative_id;
	ill_params.company_id = company_id;
		
	getAjaxData("AjaxPostInitiative", "", "display_meetings", ill_params, "Saving...")
}

/* --- Ajax return data handlers --- */
function AjaxPostInitiative(data)
{
	for (i = 1; i < data.length + 1; i++) 
	{
		t = data[i-1];
		switch (t.cmd_action)
		{
			case "display_meetings":
				switch (source_tab)
				{
					case '4':
						parent.iframe1.$('popup_meetings').innerHTML = t.meetings_list['template'];
						parent.iframe1.$('meeting_count').innerHTML = t.meetings_list['meeting_count'];
						parent.iframe1.sortables_init();
						break;
					case '5':
					case '7':
						parent.$('popup_meetings').innerHTML = t.meetings_list['template'];
						sortables_init();
						break;
					default:
						break;
				}
				break;
			default:
				alert("No cmd_action specified");
				break;
		}
	}
}
{/literal}
{if $source_tab != ''}
	//showMeetings({$post_initiative_id}, {$company_id});
{/if}

{if $refresh_screen}
	{literal}
	top.refreshTab(source_tab);
	top.loadTab(source_tab, "", false);
	{/literal}
{/if}
</script>

{include file="footer2.tpl"}