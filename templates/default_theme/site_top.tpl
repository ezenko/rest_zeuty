<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{if $page_title}{$page_title}{else}{$lang.title}{/if}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<meta name="description" content="{if $page_description}{$page_description}{else}{$lang.description}{/if}">
	<meta name="keywords" content="{if $page_keywords}{$page_keywords}{else}{$lang.keywords}{/if}">
	<link href="{$site_root}{$template_root}{$template_css_root}/greybox.css" rel="stylesheet" type="text/css" media="all">
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css" media="all">
	<link href="{$site_root}{$template_root}{$template_css_root}/lightbox.css" rel="stylesheet" type="text/css" media="screen">
{if $head_add}
	{foreach from=$head_add item=add_code}
		{$add_code}
	{/foreach}
{/if}
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/location.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/comparison_list.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/md5.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/AmiJS.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/greybox.js"></script>	
	<script language="JavaScript" type="text/javascript">
		var GB_IMG_DIR = "{$site_root}{$template_root}{$template_images_root}/greybox/";
	</script>
	{literal}
<script language="JavaScript" type="text/javascript">
	var _GET_Keys;
	var _GET_Values;
	var _GET_Count = 0;
	var _GET_Default = '';

function get_parseGET() {
	get = new String(window.location);
	x = get.indexOf('?');
	if(x!=-1) {
		l = get.length;
		get = get.substr(x+1, l-x);
		l = get.split('&');
		x = 0;
		_GET_Count  = l.length;
		_GET_Keys   = new Array(_GET_Count);
		_GET_Values = new Array(_GET_Count);
		for(i in l)
			{
				if (typeof(l[i]) != "function") { //for lightbox
					get = l[i].split('=');
				   _GET_Keys[x] = get[0];
				   _GET_Values[x] = get[1];
				   x++;
				}
			}
		if (_GET_Keys[1] == 'thento' && _GET_Values[1] == 'editsubscribe' && _GET_Keys[2] == 'login' && _GET_Values[2].length>1 ) {
			return GB_show('', './login.php?from=subscribe&login='+_GET_Values[2], 230, 400);
		} else if (_GET_Keys[1] == 'thento' && _GET_Values[1] == 'viewmail' && _GET_Keys[2] == 'login' && _GET_Values[2].length>1) {
			return GB_show('', './login.php?from=mailto&login='+_GET_Values[2], 230, 400);
		}
	} else ;
	return;
}

function InComparisonList() {

	{/literal}{foreach from=$comparison_ids item=cid}{literal}
	var elem = document.getElementById('listing_add_to_comparison_' + '{/literal}{$cid.id}{literal}');
	if (elem) {
		elem.innerHTML = "{/literal}<b>{$lang.default_select.in_your_comparison_list}</b>{literal}";
	}
	{/literal}{/foreach}{literal}
}

</script>
{/literal}
</head>

<body style="margin: 0px;" onload="get_parseGET(); InComparisonList();{if (($data.user_type eq 2 || ($data.user_type eq 3 && $data.agency_approve eq 1)) && $data.id_country && $data.in_base && $use_maps_in_account)||($use_maps_in_viewprofile && $profile.country_name && $view eq 'general' && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) && ($profile.in_base || $profile.company_data.in_base))||(($profile.type eq 2  || $profile.type eq 4) && $view eq 'map' && $data.in_base && $use_maps_in_viewprofile)} getMapGlobal(&quot;{$map.name}&quot;, &quot;map_container&quot;, &quot;{$profile.adress}&quot;, &quot;{$profile.city_name}&quot;, &quot;{$profile.region_name}&quot;, &quot;{$profile.country_name}&quot;, &quot;{$profile.lat}&quot;, &quot;{$profile.lon}&quot;);{/if}">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr valign="top">
	<td align="center">
		<noscript><div style="padding-top: 10px;"><font class="error">{$lang.default_select.enable_javascript}</font><div></noscript>
		<table cellpadding="0" cellspacing="0" class={if $section_name == "index"}"index_page"{else}"page"{/if} border="0">
		<tr>
			<td height="48" align="left">
				<a href='{$server}{$site_root}/{if $registered}homepage.php{else}index.php{/if}' class="home_link">{$lang.default_select.home_page}</a>
				<!--{section name=m loop=$lang_menu}
				{if $lang_menu[m].vis eq 1}
				<a class="lang_menu{if $lang_menu[m].id_lang eq $lang_code}_active{/if}" href="{$file_name}?lang_code={$lang_menu[m].id_lang}{$add_to_lang}">{$lang_menu[m].value}</a>&nbsp;
				{/if}
				{/section}-->
			</td>
			<td height="48" align="right">
				{strip}
				<font class="hidden">
				{if $registered}
					<font class="text"><b>{$user[1]}</b></font>&nbsp;&nbsp;<font class="text">|</font>&nbsp;
					<span id="comparison_str" {if !$comparison_ids_cnt}style="display: none;"{/if}>
					&nbsp;<a href="{$server}{$site_root}/compare.php">{$lang.default_select.comparison_list} ({$comparison_ids_cnt})</a>
					&nbsp;&nbsp;<font class="text">|</font>&nbsp;
					</span>							
					{if $lang_menu}
						<select onchange="document.location.href = '{$file_name}?lang_code='+this.value+'{$add_to_lang}'">
						{section name=m loop=$lang_menu}
							{if $lang_menu[m].vis eq 1}
								<option value="{$lang_menu[m].id_lang}" {if $lang_menu[m].id_lang eq $lang_code}selected{/if}>{$lang_menu[m].value}</option>
							{/if}
						{/section}
						</select>					
						&nbsp;&nbsp;<font class="text">|</font>
					{/if}
					{if !$mhi_services}
						{if $period_rest>0}{if $lang_menu}&nbsp;&nbsp;{/if}<font class="text">{$lang.headers.period_rest}:</font>&nbsp;<font class="text"><a href="services.php?sel=group">{$period_rest}&nbsp;{if $day_id eq 1}{$lang.default_select.days_1}{elseif $day_id eq 2}{$lang.default_select.days_2}{elseif $day_id eq 3}{$lang.default_select.days_3}{/if}</a>&nbsp;&nbsp;<font class="text">|</font>{if !$lang_menu}&nbsp;&nbsp;{/if}{/if}
						{if $lang_menu}&nbsp;&nbsp;{/if}<font class="text">{$lang.headers.user_account}:</font>&nbsp;<a href="./services.php" {if $section_name == 'services'}  class="user_menu_link_active" {/if}>{$cur_symbol} {$user_account}</a>&nbsp;&nbsp;<font class="text">|</font>
					{/if}
					{section name=m loop=$homepage_user_menu}
						{if $smarty.section.m.index}&nbsp;&nbsp;<font class="text">|</font>{/if}&nbsp;&nbsp;<a href="{$homepage_user_menu[m].href}" {if $homepage_user_menu[m].onclick} onClick="{$homepage_user_menu[m].onclick}" {/if} {if $section_name == $homepage_user_menu[m].name} class="user_menu_link_active" {/if}>{$homepage_user_menu[m].value}</a>
						{if $homepage_user_menu[m].new>0}&nbsp;<img src="{$site_root}{$template_root}{$template_images_root}/mail_alert.gif" border="0" vspace="0" hspace="0" alt="{$lang.default_select.unread_messages}">{/if}
					{/section}
				{else}			
					<span id="comparison_str" {if !$comparison_ids_cnt}style="display: none;"{/if}>
					<a href="{$server}{$site_root}/compare.php">{$lang.default_select.comparison_list} ({$comparison_ids_cnt})</a>
					&nbsp;&nbsp;<font class="text">|</font>&nbsp;&nbsp;
					</span>		
					{if $lang_menu}
						<select onchange="document.location.href = '{$file_name}?lang_code='+this.value+'{$add_to_lang}'">
						{section name=m loop=$lang_menu}
							{if $lang_menu[m].vis eq 1}
								<option value="{$lang_menu[m].id_lang}" {if $lang_menu[m].id_lang eq $lang_code}selected{/if}>{$lang_menu[m].value}</option>
							{/if}
						{/section}
						</select>												
						{if $index_user_menu}			
						&nbsp;&nbsp;<font class="text">|</font>&nbsp;&nbsp;
						{/if}
					{/if}
					{section name=m loop=$index_user_menu}
						{if $smarty.section.m.index}&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;{/if}<a href="{$index_user_menu[m].href}" {if $index_user_menu[m].onclick} onClick="{$index_user_menu[m].onclick}" {/if}>{$index_user_menu[m].value}</a>
					{/section}
				{/if}
				</font>
				{/strip}
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr valign="top">
	<td width="100%" height="9" class="top_header_line">&nbsp;</td>
</tr>
<tr valign="top">
	<td align="center">
		<table cellpadding="0" cellspacing="0" class={if $section_name == "index"}"index_page"{else}"page"{/if} border="0">
		<tr><td height="48">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="left" align="left" valign="middle">
					<!--//logo-->
					<a href="{$server}{$site_root}/"><img src="{$site_root}{$template_root}{$template_images_root}/{$logo_settings.logotype.img}" border="0" alt="{$logo_settings.logotype.alt}"></a>
				</td>
				<td class="delimiter">&nbsp;</td>
				<td class={if $section_name == "index"}"index_main"{else}"main"{/if}>
					<!--//top menu-->
					<table cellpadding="0" cellspacing="0" width="100%" border="0">
						<tr valign="top">
							{if $registered}
								{assign var="menu_name" value=$homepage_top_menu}
							{else}
								{if $section_name == "index"}
									{assign var="menu_name" value=$index_page_menu}
								{else}
									{assign var="menu_name" value=$index_top_menu}
								{/if}
							{/if}
							{assign var="total" value="100"}
							{section name=m loop=$menu_name}
								<td align="center" width="{$total/$smarty.section.m.total}%">
									<table cellpadding="0" cellspacing="0" width="100%" border="0">
									<tr><td height="14" align="center" {if $section_name == $menu_name[m].name}class="top_menu_item_active"{/if}>&nbsp;</td></tr>
									<tr><td height="23" align="center" {if $section_name == $menu_name[m].name}class="top_menu_item_active"{elseif $smarty.section.m.first && $section_name == $menu_name[m.index_next].name}class="top_menu_item_left"{elseif $smarty.section.m.first && $section_name != $menu_name[m.index_next].name}class="top_menu_item_first"{elseif $section_name != $menu_name[m.index_next].name}class="top_menu_item_right"{/if} style="padding: 0px 10px 0px 10px;"><a class="top_menu_link{if $section_name == $menu_name[m].name}_active{/if}" href="{$menu_name[m].href}">{$menu_name[m].value}</a></td></tr>
									<tr><td height="11" align="center" {if $section_name == $menu_name[m].name}class="top_menu_item_active"{/if}>&nbsp;</td></tr>
									</table>
								</td>
							{/section}
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="3" height="{if $section_name == "index"}4{else}25{/if}"></td></tr>
			{if $section_name != "index"}
			<tr>
				<td height="4" class="left_header_line">&nbsp;</td>
				<td height="4" class="empty_line">&nbsp;</td>
				<td height="4" class="main_header_line">&nbsp;</td>
			</tr>
			<tr><td colspan="3" height="4" class="empty_line">&nbsp;</td></tr>
			{/if}
			</table>
		</td></tr>
		<tr><td align="left">