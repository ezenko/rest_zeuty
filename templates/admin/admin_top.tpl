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
	<script type="text/javascript" src="{$site_root}{$template_root}/js/yahoo.js"></script>
	<script type="text/javascript" src="{$site_root}{$template_root}/js/treeview.js"></script>
	<script type="text/javascript" src="{$site_root}{$template_root}/js/rounded-corners.js"></script>
	<script type="text/javascript" src="{$site_root}/admin/admin_menu.php?js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/location.js"></script>
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css">
	<link href="{$site_root}/admin/admin_menu.php?css" rel="stylesheet" type="text/css">
</head>

<body style="margin:0px;" bgcolor="#FFFFFF" onload="{if $data.section=='admin' && $data.in_base && $data.id_country && $data.user_type eq 2 && $use_maps_in_account}{if $map.name == 'microsoft' || $map.name == 'mapquest'} getMap();{/if}{/if}">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
        <td width="100%" height="28px" background="{$site_root}{$template_root}/images/menu/header_top.gif" style="padding-left:10px; padding-right:10px;">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td  background="{$site_root}{$template_root}/images/menu/header_top.gif"><img src="{$site_root}{$template_root}/images/admin_title.gif" border="0"></td>
			</tr>
			</table>
	</td>
</tr>
<tr>
        <td width="100%" height="25px" background="{$site_root}{$template_root}/images/menu/header_bottom.gif" align="right" class="top_menu"><div style="padding-right:10px">
		{if $file_name != "index.php"}
			<table cellpadding="0" cellspacing="0" align="right" border="0">
			<tr>
				<td width="8px" style="padding-right:5px"><img src="{$site_root}{$template_root}/images/menu/language.gif" border="0"></td>
				<td style="padding-right:5px">
					<select onchange="document.location.href = '{$file_name}?lang_code='+this.value+'{$add_to_lang}'">
					{section name=m loop=$admin_lang_menu}
						{if $admin_lang_menu[m].vis eq 1}
							<option value="{$admin_lang_menu[m].id_lang}" {if $admin_lang_menu[m].id_lang eq $lang_code} selected {/if}>{$admin_lang_menu[m].value}</option>
						{/if}
					{/section}
					</select>
				</td>
				<td width="8px" style="padding-left: 3px;"><img src="{$site_root}{$template_root}/images/menu/dots.gif" border="0"></td>
				<td><a href="{$site_root}/index.php?sel=logoff" class="top_menu"><img src="{$site_root}{$template_root}/images/menu/log_off.gif" border="0"></a></td>
				<td><a href="{$site_root}/index.php?sel=logoff" class="top_menu">&nbsp;&nbsp;{$lang.buttons.logoff}</a></td>
			</tr>
			</table>
		{/if}
	</div></td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="10px">
<tr>
	{if $file_name != "index.php"}
        <td width="200px" valign="top">
        	<!-- Left panel -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
					<td><a href="{$site_root}/admin/admin_homepage.php" style="border: none;"><img src="{$site_root}{$template_root}/images/admin_logo.gif" border="0"></a></td>
				</tr>
				<tr>
                    <td width="100%">
		<div class="expander"><a href="javascript:tree.expandAll()"><img src="{$site_root}{$template_root}/images/menu/expand_all.gif" onMouseOver="this.src='{$site_root}{$template_root}/images/menu/expand_all_over.gif'" onMouseOut="this.src='{$site_root}{$template_root}/images/menu/expand_all.gif'" border="0" alt="Expand All"></a><img src="{$site_root}{$template_root}/images/menu/expand_spacer.gif"><a href="javascript:tree.collapseAll()"><img src="{$site_root}{$template_root}/images/menu/collapse_all.gif" border="0" onMouseOver="this.src='{$site_root}{$template_root}/images/menu/collapse_all_over.gif'" onMouseOut="this.src='{$site_root}{$template_root}/images/menu/collapse_all.gif'" alt="Collapse All"></a></div>
		<div id="content" class="content"><div id="treeDiv1"></div></div>
		<div class="expander"><img src="{$site_root}{$template_root}/images/menu/container_bottom.gif"></div>
		<script language="javascript">menuInit();</script>
                    </td>
                </tr>
            </table>
        </td>
        <td width="100%" valign="top">
        {else}
        <td colspan=2 width="100%" valign="top">
        {/if}
        
		{if $error}
                <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                        <tr><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topleft.gif" width="9" height="9" border="0"></td>
                        <td width="100%" height="8px" class="line_bw_top">&nbsp;</td><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topright.gif" width="9" height="9"></td>
                        </tr>
                        <tr><td width="9px" class="line_bw_left">&nbsp;</td>
                        <td width="100%" height="100%" class="white_block" style="padding-left:10px">
				<b><font class="error">{foreach item=item from=$error}&nbsp;*&nbsp;{$item}<br>{/foreach}</font></b>
                        </td><td width="9px" class="line_bw_right">&nbsp;</td></tr>
                        <tr><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_bottomleft.gif" width="9" height="9" border="0"></td>
                        <td width="100%" height="8px" class="line_bw_bottom">&nbsp;</td><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_bottomright.gif" width="9" height="9"></td>
                        </tr>
                </table><br>
		{/if}
                <!-- Center panel -->
                <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topleft.gif" width="9" height="9" border="0"></td>
			                        <td width="100%" height="8px" class="line_bw_top">&nbsp;</td>
						<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topright.gif" width="9" height="9"></td>
                        </tr>
                        <tr>
						<td width="9px" class="line_bw_left">&nbsp;</td>
						<td width="100%" height="100%" class="white_block">

                        <table width="100%" border=0 cellspacing="3px" cellpadding="10px">
                                <tr>
                                        <td width="100%" class="blue_block" valign="top"><font class="text">