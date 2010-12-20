{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top" width="80%">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.news_feeds} | {if $par eq 'add'}{$lang.content.page_news_add_subheader}{elseif $par eq 'edit'}{$lang.content.page_news_edit_subheader}{/if}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.news_help}</div></td>
		</tr>
		<tr>
			<td style="padding-bottom: 5px;" class="main_header_text">{if $par eq 'add'}{$lang.content.add_site_news}{elseif $par eq 'edit'}{$lang.content.edit_site_news}{/if}</td>
		</tr>
		<tr>
			<td>
				<form method="post" action="admin_news.php?sel={if $par eq 'edit'}edit_n{else}add_n{/if}" enctype="multipart/form-data" name="news" id="news">
				<input type="hidden" value="{$data.id_news}" name="id_news" id="id_news">
				<input type="hidden" value="{$current_lang_id}" name="language_id">
				{$tinymce}
				<table cellpadding="0" cellspacing="0" class="form_table">
				<tr>
					<td>{$lang.content.title}:&nbsp;<input type="text" name="title" id="title" value="{$data.title}" size="40" maxlength="255"></td>
					<td><input type="checkbox" name="status" id="status" value="1" {if $data.status eq 1}checked{/if}></td>
					<td>{$lang.content.to_publish}</td>
				</tr>
				</table>
				<table cellpadding="0" cellspacing="0" border="0" class="form_table" width="100%">
                    <tr>
	                    <td>
				        	<textarea id="news_text" name="news_text" rows="23" cols="40" style="width: 100%">{$data.news_text}</textarea>
						</td>
					</tr>
				</table>				
				<input type="button" value="{$lang.buttons.back}" onclick="document.location.href='admin_news.php?language_id={$current_lang_id}'" class="button_3">
				<input type="submit" value="{$lang.buttons.save}" class="button_3">
				</form>
			</td>
		</tr>
	</table>
	</td></tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}