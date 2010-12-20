<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{$lang.title}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<meta name="Description" content="{$lang.description}">
	<meta name="Keywords" content="{$lang.keywords}">
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css" media="all">
</head>
<body {if $upload_file.upload_type eq "f"} onload="javascript:resizeIM()"  {/if} style="margin:0; padding:0;">
<table cellpadding="0" cellspacing="0" width="90%" border="0">
<tr valign="top"><td>
	<!-- central part -->
	<td width="100%" valign=top align=center>
    <div align="center">
		<table borderColor="#FFFFFF" cellSpacing="0" cellPadding="0" border="0" width="100%">
        {if $upload_file.upload_type eq "f"}
		<tr>
			<td align="center" width="100%" style="padding-left: 5px; padding-top: 5px;">
			<img border=1 bordercolor=1 src="{$upload_file.file_path}" alt="">
			</td>
		</tr>
		{else}
		<tr>		
			<td align="center" width="100%" class="home_text_main">
			
			{if $is_flv eq 1}			
				<object type="application/x-shockwave-flash" data="{$server}{$site_root}{$mail_template_root}/flash/FlowPlayer.swf" width="{$upload_file.width}" height="{$upload_file.height}" id="FlowPlayer">
				<param name="allowScriptAccess" value="always" />
				<param name="movie" value="{$server}{$site_root}{$mail_template_root}/flash/FlowPlayer.swf" />
				<param name="quality" value="high" />
				<param name="scaleMode" value="showAll" />
				<param name="allowfullscreen" value="false" />
				<param name="wmode" value="transparent" />
				<param name="allowNetworking" value="all" />
				{literal}
				<param name="flashvars" value="config={
					showVolumeSlider:false,					
					autoPlay: true,
					loop: false,
					initialScale: 'scale',
					showLoopButton: false,
					showPlayListButtons: false,
					showFullScreenButton: false,
					showMenu: false,				
					controlBarBackgroundColor: '0x11cc11',	
					timeDisplayFontColor: '0xff0000',					
					progressBarColor1: '0xff0000',	
					bufferingAnimationColor: '0000FF', 		
					showVolumeSlider: true,
					controlsOverVideo: 'ease',	
					controlBarGloss: 'high',	
					playList: [
						{ url: '{/literal}{$upload_file.icon_path}{literal}' },
						{ url: '{/literal}{$upload_file.file_path}{literal}' },
						{ url: '', type: 'swf' }
					]
					}" />
				{/literal}			
				</object>
			{else}	
				<OBJECT id="Player" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-lld3-B153-OOC04F79FAA6" standby="Loading MicrosoftR WindowsR Media Player components..." width="320" height="240" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/ nsmp2inf.cab#Version=6.4.7.1112">
				<PARAM NAME="AutoStart" VALUE="True">
				<PARAM NAME="FileName" VALUE="{$upload_file.file_path}">
				<PARAM NAME="ShowControls" VALUE="True">
				<PARAM NAME="ShowStatusBar" VALUE="true">
				<EMBED type="application/x-mplayer2"  pluginspage="http://www.microsoft.com/windows/windowsmedia/en/download/default.asp" SRC="{$upload_file.file_path}" name="MediaPlayer1" autostart=1 showcontrols=1></EMBED>
				</OBJECT>
			{/if}	
			</td>
		</tr>
		{/if}
		{if $upload_file.user_comment}
		<tr>
			<td align="center" class="text_strong" style="padding-top: 5px; padding-bottom: 5px;">{$upload_file.user_comment}</td>
		</tr>
		{/if}
        <tr>
        	<td align="center" style="padding-top: 5px; padding-bottom: 5px;"><input type="button" class="btn_small" value="{$lang.buttons.close}" onclick="javascript: window.opener.focus(); window.close();"></td>
        </tr>
		</table>
	</div>
	</td>
	<!-- /central part -->
</tr>
<tr><td colspan=2 height="100%">&nbsp;</td></tr>
</table>
{literal}
<script language="JavaScript" type="text/javascript">
function resizeIM(){
	el = document.images[0];
	height= el.height+80;
	width = el.width+25;
	document.images[0].border=1;
	window.resizeTo(width, height);
}
</script>
{/literal}
</body>
</html>