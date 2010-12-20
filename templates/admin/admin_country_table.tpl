{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.references} | {$lang.menu.references_countries}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help_countries_intro}&nbsp;<a href="{$server}{$site_root}/install/countries/index.php">{$lang.content.help_countries_install}</a>{$lang.content.help_countries}</div></td>
	</tr>
	<tr>
		<td>
			<table cellspacing="3" cellpadding="0" >
				<form method="post" action="{$form.action}"  enctype="multipart/form-data" name=add_form>
				{$form.hiddens}
					<tr>
						<td align="right" class="main_header_text">
						&nbsp;{$lang.content.name}:&nbsp;
						</td>
						<td class="main_content_text" align="left"><input type="text" name="name" value="{$name}" size=30></td>
						<td class="main_content_text" align="left"><input type="button" value="{$lang.buttons.add}" class="button" onclick="javascript: document.add_form.submit();"></td>
					</tr>
					</form>
			</table>
			<br>
			<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="100%">
				{if $links}
				<tr>
					<td height="20"  colspan=3 align="left"  class="main_content_text" >
					{foreach item=item from=$links}
						<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
					{/foreach}
					</td>
				</tr>
				{/if}
				<tr class="table_header">
					<td class="main_header_text" align="center" width="20">{$lang.content.number}</td>
					<td class="main_header_text" align="center">{$lang.content.country}</td>
					<td class="main_header_text" align="center" width="100">{$lang.default_select.title_edit}</td>
					<td class="main_header_text" align="center" width="100">{$lang.default_select.title_delete}</td>
				</tr>
				{if $countries}
				{section name=spr loop=$countries}
				<tr>
					<td class="main_content_text" align="center">{$countries[spr].number}</td>
					<td class="main_content_text" align="center"><a href="{$countries[spr].regionslink}">{$countries[spr].name}</a></td>
					<td class="main_content_text" align="center"><input type="button" value="{$lang.buttons.edit}" class="button" onclick="javascript: document.location.href='{$countries[spr].editlink}';"></td>

					<td class="main_content_text" align="center"><input type="button" value="{$lang.buttons.delete}" class="button" onclick="javascript: if(confirm('{$lang.content.confirm_delete}')){literal}{{/literal}location.href='{$countries[spr].deletelink}'{literal}}{/literal}"></td>
				</tr>
				{/section}
				{else}
				<tr height="40">
					<td class="main_error_text" align="left" colspan="4">{$lang.content.empty}</td>
				</tr>
				{/if}
				{if $links}
				<tr>
					<td height="20"  colspan=3 align="left"  class="main_content_text" >
					{foreach item=item from=$links}
						<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
					{/foreach}
					</td>
				</tr>
				{/if}
			</table>
		</td>
	</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}