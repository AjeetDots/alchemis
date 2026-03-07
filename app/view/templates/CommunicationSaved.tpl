{include file="header.tpl" title="Communication Saved"}

Communication successfully saved!

<script type="text/javascript">

// repopulate the scoreboard figures
top.$("communication_count").innerHTML = "Calls: " + {$scoreboard->getCommunicationCount()};
top.$("effective_count").innerHTML = "Effectives: " + {$scoreboard->getEffectiveCount()};
top.$("span_callback_count").innerHTML = "Today's Callbacks: " + {$scoreboard->getCallBackCount()} + "(" + {$scoreboard->getPriorityCallBackCount()} + ")";				
// unset the global var indicating a communication is not loaded
top.communication_loaded = false;

top.refreshCallBackPopup();

// clear anything in the info pane
parent.$('ifr_info').hide();

//set the local var to indicate which tab we should renavigate to
var source_tab = {$source_tab};

switch (source_tab)
{literal}
{
{/literal}
	case 5: //search workspace
	case 7: //filter workspace
		var source_frame = "iframe_" + source_tab;
		var s = top.window[source_frame].contentWindow.loadPost({$post_id}, {$initiative_id}, {$post_initiative_id});
		break;
	default:
		break
{literal}
}
{/literal}

top.loadTab(source_tab, "", false);

</script>

{include file="footer2.tpl"}