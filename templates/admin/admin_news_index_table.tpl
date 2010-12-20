{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top" width="80%">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.news_feeds}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.news_feeds_help}</div></td>
		</tr>
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="{$site_root}{$template_root}/images/arrow_down.gif"></td>
						<td class="fast_nav_title"><a class="fast_nav_link" href="#" onclick="javascript: ShowHideDiv('fast_menu');">{$lang.default_select.fast_navigation}</a></td>
					</tr>
					<tr>
						<td colspan="2" class="fast_menu_nav">
						<ul class="fast_navigation" id="fast_menu" style="display: none;">
							<li><a href="#site_news">{$lang.content.site_news}</a></li>
							<li><a href="#site_feeds">{$lang.content.site_feeds}</a></li>
						</ul>&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table cellpadding="3" cellspacing="1" border="0" width="100%">
				<tr>
					<td>
					<div style="padding-bottom: 10px;">{strip}
						{$lang.default_select.interface_lang}:
						{section name=m loop=$admin_lang_menu}
							<span class="space">
								{if $admin_lang_menu[m].id_lang == $current_lang_id}
									<b>{$admin_lang_menu[m].value}</b>
								{else}
									<a href="#" onclick="javascript: document.location.href='{$file_name}?section={$section}&edit={$edit}&xml={$xml_file}&language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
								{/if}
							</span>
							{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
						{/section}
					{/strip}</div>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td id="site_news" class="main_header_text" style="padding-bottom: 5px;">{$lang.content.site_news}</td>
		</tr>
		<tr>
			<td valign="top">
				<table cellpadding="3" cellspacing="1" width="100%" border="0" class="table_main">
					<tr class="table_header">
						<td class="main_header_text" width="20" align="center">{$lang.content.number}</td>
						<td class="main_header_text" width="150" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?page={$page}&sorter_news=1&order_news={$order_news}&sorter_feeds={$sorter_feeds}&order_feeds={$order_feeds_old}&language_id={$current_lang_id}';">{$lang.content.date}{if $sorter_news==1}{$news_order_icon}{/if}</div>
						</td>
						<td class="main_header_text" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?page={$page}&sorter_news=2&order_news={$order_news}&sorter_feeds={$sorter_feeds}&order_feeds={$order_feeds_old}&language_id={$current_lang_id}';">{$lang.content.title}{if $sorter_news==2}{$news_order_icon}{/if}</div>
						</td>
						<td width="5%" class="main_header_text" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?page={$page}&sorter_news=3&order_news={$order_news}&sorter_feeds={$sorter_feeds}&order_feeds={$order_feeds_old}&language_id={$current_lang_id}';">{$lang.content.status}{if $sorter_news==3}{$news_order_icon}{/if}</div>
						</td>
						<td width="5%" class="main_header_text" align="center">{$lang.content.edit_header}</td>
					</tr>
					{if $news}
					{foreach item=item from=$news}
					<tr>
						<td class="main_content_text" align="center">{$item.number}</td>
						<td class="main_content_text" align="center">{$item.date}</td>
						<td class="main_content_text" align="center">{$item.title}</td>
						<td class="main_content_text" align="center" nowrap>{if $item.status}{$lang.content.published}{else}{$lang.content.not_published}{/if}</td>
						<td class="main_content_text" align="center" nowrap>
							<input type="button" class="button_2" value="{$lang.buttons.edit}" onclick="javascript: document.location.href='{$item.editlink}';">
							<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="{literal}javascript: if(confirm('{/literal}{$lang.content.del_confirm}{literal}')) document.location.href='{/literal}{$item.deletelink}{literal}';{/literal}">
						</td>
					</tr>
					{/foreach}
					{else}
					<tr>
						<td colspan="5" class="error" align="center">{$lang.content.no_site_news}</td>
					</tr>
					{/if}
				</table>
				{if $links}
				<table cellpadding="2" cellspacing="2" border="0" style="margin: 0px; margin-bottom: 10px; ">
					<tr>
						{foreach item=item from=$links}
						<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a></td>
						{/foreach}
						</td>
					</tr>
				</table>
				{/if}
			</td>
		</tr>
		<tr>
			<td><input type="button" class="button_2" value="{$lang.content.add_news}" onclick="javascript: document.location.href='{$add_link}';"></td>
		</tr>
		<tr>
			<td id="site_feeds" class="main_header_text" style="padding-top: 20px; padding-bottom: 5px;">{$lang.content.site_feeds}</td>
		</tr>
		<tr>
			<td valign="top">
				<table cellpadding="3" cellspacing="1" width="100%" border="0" class="table_main">
					<tr class="table_header">
						<td class="main_header_text" width="20" align="center">{$lang.content.number}</td>
						<td class="main_header_text" width="150" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter_news={$sorter_news}&order_news={$order_news_old}&sorter_feeds=1&order_feeds={$order_feeds}&language_id={$current_lang_id}';">{$lang.content.rss_date}{if $sorter_feeds==1}{$feeds_order_icon}{/if}</div>
						</td>
						<td class="main_header_text" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter_news={$sorter_news}&order_news={$order_news_old}&sorter_feeds=2&order_feeds={$order_feeds}&language_id={$current_lang_id}';">{$lang.content.rss_link}{if $sorter_feeds==2}{$feeds_order_icon}{/if}</div>
						</td>
						<td width="5%" class="main_header_text" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter_news={$sorter_news}&order_news={$order_news_old}&sorter_feeds=3&order_feeds={$order_feeds}&language_id={$current_lang_id}';">{$lang.content.news_count}{if $sorter_feeds==3}{$feeds_order_icon}{/if}</div>
						</td>
						<td width="5%" class="main_header_text" align="center">
							<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter_news={$sorter_news}&order_news={$order_news_old}&sorter_feeds=4&order_feeds={$order_feeds}&language_id={$current_lang_id}';">{$lang.content.rss_status}{if $sorter_feeds==4}{$feeds_order_icon}{/if}</div>
						</td>
						<td width="5%" class="main_header_text" align="center">{$lang.content.edit_header}</td>
					</tr>
					{if $feeds}
					{foreach item=item from=$feeds}
					<tr>
						<td class="main_content_text" align="center">{$item.number}</td>
						<td class="main_content_text" align="center">{$item.date}</td>
						<td class="main_content_text" align="center">{$item.link}</td>
						<td class="main_content_text" align="center">{$item.news_count}</td>
						<td class="main_content_text" align="center" nowrap>{if $item.status}{$lang.content.published}{else}{$lang.content.not_published}{/if}</td>
						<td class="main_content_text" align="center" nowrap>
							<input type="button" class="button_2" value="{$lang.buttons.edit}" onclick="javascript: document.location.href='{$item.editlink}';">
							<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="{literal}javascript: if(confirm('{/literal}{$lang.content.del_feed_confirm}{literal}')) document.location.href='{/literal}{$item.deletelink}{literal}';{/literal}">
						</td>
					</tr>
					{/foreach}
					{/if}
				</table>
			</td>
		</tr>
		<tr>
			<td>
			<input type="button" class="button_2" value="{$lang.content.add_feed}" onclick="javascript: document.location.href='{$rss_add_link}';">
			<input type="button" class="button_2" value="{$lang.content.update_feeds}" onclick="javascript: document.location.href='{$rss_update_link}';">
			</td>
		</tr>
	</table>
	</td></tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}