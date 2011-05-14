{include file="$gentemplates/site_top.tpl"}

<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/flowplayer-3.2.6.min.js"></script>

<div id="middle-container">

<h2 style="height:24px;">Видео презентация</h2>
<br />
<center>
    {if $section.video}
    <a href="/uploades/video/{$section.video}"
		 style="display:block;width:580px;height:380px"  
		 id="player"> 
	</a> 
	
	<script>
		flowplayer("player", "{$server}{$site_root}{$template_root}/flash/flowplayer-3.2.7.swf");
	</script>
    {/if}
</center>
</div>
{include file="$gentemplates/site_footer.tpl"}