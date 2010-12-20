<html>
<head>
	<title>{$lang.title}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	{if $script}<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/{$script}.js"></script>{/if}
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css">
</head>
<body style="margin:0px;" bgcolor="#FFFFFF" {if $on_load_action}onload="{$on_load_action}"{/if}>
<table width="780px" cellpadding="0" cellspacing="10px">
<tr>
	<td width="80%" valign="top">
		<!-- Center panel -->
		<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topleft.gif" width="9" height="9" border="0"></td>
			<td width="100%" height="8px" class="line_bw_top">&nbsp;</td>
			<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topright.gif" width="9" height="9"></td>
		</tr>
		<tr>
			<td height="8px" class="line_bw_left">&nbsp;</td>
			<td width="100%" height="100%" class="white_block">
				<table width="100%" cellspacing="3px" cellpadding="10px" class=table_main border="0">
					<tr>
						<td width="100%" class="blue_block" valign="top">
						<font class="header">{$header}</font><font class="subheader">{$subheader}</font>
						<br><br><br>