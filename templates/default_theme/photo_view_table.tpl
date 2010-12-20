<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{$form.login}::{$form.comment}</title>
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
<body onload="javascript:resizeIM()" style="margin:0; padding:0;">
<table width="100%" cellpadding="3" cellspacing="0">
<tr valign=middle>
	<td align=center>{$form.image_path}</td>
</tr>
<tr>
	<td align="center"><input type="button" class="btn_small" value="{$lang.buttons.close}" onclick="javascript: opener.focus(); window.close();"></td>
</tr>
</table>
{literal}
<script language="JavaScript" type="text/javascript">
function resizeIM(){
	el = document.images[0];
	height= el.height+80;
	width = el.width+80;
	document.images[0].border=1;
	window.resizeTo(width, height);
}
</script>
{/literal}
</body>
</html>