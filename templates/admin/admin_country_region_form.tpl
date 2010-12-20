{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.references} | {$lang.menu.references_countries} | {$lang.content.list_city} {$country_name}, {$region_name}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help_cities}</div></td>
	</tr>
	<tr>
		<table border=0 cellspacing=3 cellpadding=0>
				<form method="post" action="{$form.action}" enctype="multipart/form-data" name="add_form">
				{$form.hiddens}
					<tr>
						<td class="main_header_text">{$lang.content.city}:</td>
						<td><input type="text" name="name" value="{$name}" size=30></td>
						<td><input type="button" value="{$lang.buttons.add}" class="button" onclick="javascript: document.add_form.submit();"></td>
					</tr>
				</form>
		</table>
		<br>
		<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="100%">
			{if $links}
			<tr>
				<td height="20" colspan=4 align="left"  class="main_content_text" >
				{foreach item=item from=$links}
					<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
				{/foreach}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="main_header_text" align="center" width="20">{$lang.content.number}</td>
				<td class="main_header_text" align="center">{$lang.content.city}</td>
				<td class="main_header_text" align="center" width="100">{$lang.default_select.title_edit}</td>
				<td class="main_header_text" align="center" width="100">{$lang.default_select.title_delete}</td>
			</tr>
			{if $cities}
			{section name=spr loop=$cities}
			<tr>
				<td class="main_content_text" align="center">{$cities[spr].number}</td>
				<td class="main_content_text" align="center">{$cities[spr].name}</td>
				<td class="main_content_text" align="center"><input type="button" value="{$lang.buttons.edit}" class="button" onclick="javascript: document.location.href='{$cities[spr].edit_link}';"></td>
				<td class="main_content_text" align="middle"><input type="button" value="{$lang.buttons.delete}" class="button" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.confirm_delete}'{literal})){location.href={/literal}'{$cities[spr].deletelink}'{literal}}{/literal}"></td>
			</tr>
			{/section}
			{else}
			<tr height="40">
				<td class="main_error_text" align="left" colspan="4">{$lang.content.empty_city}</td>
			</tr>
			{/if}
			{if $links}
			<tr>
				<td height="20"  colspan=4 align="left"  class="main_content_text" >
				{foreach item=item from=$links}
					<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
				{/foreach}
				</td>
			</tr>
			{/if}
		</table>
		<input type="button" value="{$lang.content.back_to_regions}" class="button" onclick="javascript: location.href='{$back_link}'">
	</td>
</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}