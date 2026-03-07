{include file="header2.tpl" title="Workspace | Information Pane | Company Sites"}


{*
next | previous | edit | call
Add new companmy site
add note
del;ete company
add company site
*}


<script language="JavaScript">
{literal}
	function popupWindow(target)
	{
	alert('popupWindow('+target+')');
//		alert($('iframe1').style);
//		$('iframe1').style.overflow-y = 'hidden';
//		$('iframe2').style.overflow-y = 'hidden';
//		$('iframe3').style.overflow-y = 'hidden';
//		$('iframe4').style.overflow-y = 'hidden';
		showPopWin(target, 800, 500, null);
	}

	

{/literal}
</script>
 
<div class="cfg">

	<table border="0" cellpadding="0" cellspacing="0">
		<tr class="hdr">
			<td colspan="3">Sites for Company: {$company->getName()}</td>
		</tr>
		<tr>
			<td>
				<div id="content-pane" class="pane-sliders" style="width: 100%">
					{foreach name="result_loop" from=$sites item=result}
					<div class="panel">
						<h3 class="moofx-toggler title" id="cpanel-panel"><span>{$result->getName()}&nbsp;&nbsp;({$result->getTelephone()})</span></h3>
						<div class="moofx-slider content">
							<table>
								<tr style="vertical-align: top; ">
									<td style="text-align: left">
										<span onclick="javascript: 'new Effect.Move($('site_list'),{literal}{ x: 0, y: 100, mode: 'relative'}{/literal});new Effect.Appear($('site_list')); ">[edit]</span>
									</td>
									<td style="vertical-align: top">
										<strong>{$result->getAddress('string')}</strong>
									</td>
								</tr>
							</table>
						
							<table>
								{foreach name="result_loop" from=$company_posts_first_name item=result}
								<tr style="background-color: {cycle values="#eeeeee,#d0d0d0"}">
									<td>
										<a href="#" onclick="javascript: new Effect.BlindDown($('site_list_{$result.id}'), {literal}{duration: 0.3}{/literal}); return false;">[edit]</a>
									</td>
									<td>
										{$result.first_name} {$result.surname}
									</td>
									<td>
										{$result.job_title}
									</td>
								</tr>
								<tr>
									<td colspan="3" padding="0">
										<div id="site_list_{$result.id}" style="background-color: white; float: none; display: none">
										 	Here is some info
										 	<br /> 
										 	<br />
										 	<a href="#" onclick="new Effect.BlindUp($('site_list_{$result.id}'), {literal}{duration: 0.3}{/literal}); return false;">[close]</a>
										 </div>
									</td>
								</tr>
								
								{/foreach}
								
							</table>
						</div>	
					</div>
					{/foreach}
				</div>
			</td>
		</tr>
	</table>
</div>
			
<script language="JavaScript">
{literal}

function screenSize()
{
	var height = window.innerHeight;
	var iframe_height = Math.floor((height - 280) / 2);
	$('iframe1').style.height = iframe_height + 'px';
	$('iframe2').style.height = iframe_height + 'px';
	$('iframe3').style.height = iframe_height + 'px';
	$('iframe4').style.height = iframe_height + 'px';
}

screenSize();

{/literal}
</script>

<script type="text/javascript">init_moofx();</script>

{include file="footer2.tpl"}