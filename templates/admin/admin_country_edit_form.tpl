{include file="$admingentemplates/admin_top.tpl"}
{assign var="help_name" value="edit_"|cat:$sel_edit}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.references} | {$lang.menu.references_countries} | {$lang.content[$help_name]} {$country_name}{if $region_name}, {$region_name}{/if}{if $city_name}, {$city_name}{/if}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help_edit}</div></td>
	</tr>
	<tr>
		</td>
			<table border=0 cellspacing=3 cellpadding=0 style="margin-bottom: 10px;">
				<form method="post" action="{$form.action}" enctype="multipart/form-data" name="city_form">
				{$form.hiddens}
					<tr>
						<td class="main_header_text">{$lang.content[$sel_edit]}:&nbsp;</td>
						<td><input type="text" name="name" value="{$data.name}" size=30></td>
					</tr>
			</table>
			<input type="button" value="{$lang.buttons.back}" class="button" onclick="javascript: location.href='{$form.back}'">
			<input type="button" value="{$lang.buttons.save}" class="button" onclick="javascript:document.city_form.submit();">
		</td>
	</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}