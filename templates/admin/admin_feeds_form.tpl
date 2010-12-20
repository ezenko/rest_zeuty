{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top" width="80%">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.news_feeds} | {if $par eq 'add'}{$lang.content.page_feeds_add_subheader}{elseif $par eq 'edit'}{$lang.content.page_feeds_edit_subheader}{/if}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.feeds_help}</div></td>
		</tr>
		<tr>
			<td style="padding-bottom: 5px;" class="main_header_text">{if $par=='add'}{$lang.content.add_rss}{elseif $par=='edit'}{$lang.content.edit_rss}{/if}</td>
		</tr>
		<tr>
			<td>
				<form method="post" action="admin_news.php?sel={if $par eq 'edit'}save_f{else}save_fn{/if}" enctype="multipart/form-data" name="feeds" id="feeds">
				<input type="hidden" value="{$data.id_feed}" name="id_feed" id="id_feed">
				<input type="hidden" value="{$current_lang_id}" name="language_id">
				<table cellpadding="0" cellspacing="0" border="0" class="form_table">
					<tr>
						<td height="27">{$lang.content.rss_link}:</td>
						<td><input type="text" name="link" id="link" value="{$data.link}" size="60"></td>
					</tr>
					<tr>
						<td height="27" style="padding-right: 5px;">{$lang.content.max_news}:</td>
						<td valign="bottom"><input type="text" name="max_news" id="max_news" value="{$data.max_news}" size="5"> <span id="min_feeds_news">({$lang.content.min_feeds_news}: {$min_feeds_news_cnt})</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.feeds_status}:</td>
						<td><input type="checkbox" name="status" id="status" value="1" {if $data.status eq 1} checked {/if}></td>
					</tr>
				</table>				
				<input type="button" value="{$lang.buttons.back}" onclick="document.location.href='admin_news.php?language_id={$current_lang_id}'" class="button_3">
				<input type="submit" value="{$lang.buttons.save}" class="button_3" onclick="return CheckNewsCnt();">
				{if $total_news>0}
					<input type="button" value="{$lang.content.update_feed}" onclick="document.location.href='admin_news.php?sel=update_feed&id_feed={$data.id_feed}'" class="button_3">
				{/if}
				</form>
			</td>
		</tr>
		{if $par=='edit'}
			<!--<tr>
				<td class="main_header_text" style="padding-top: 25px;">{$lang.content.feeds_news}</td>
			</tr>
			{if $total_news>0}
			<tr>
				<td class="main_header_text" style="padding-bottom: 5px;">{$lang.content.total_news}: {$total_news}</td>
			</tr>
			{/if}
			<tr>
				<td valign="top">
					<table cellpadding="5" cellspacing="1" width="100%" border="0" class="table_main">
						<tr class="table_header">
							<td class="main_header_text" width="20" align="center">{$lang.content.number}</td>
							<td class="main_header_text" width="150" align="center">{$lang.content.date}</td>
							<td class="main_header_text" align="center">{$lang.content.title}</td>
							<td class="main_header_text" width="15" align="center">{$lang.content.status}</td>
							<td class="main_header_text" width="100" align="center">&nbsp;</td>
						</tr>
						{if $news}
						{foreach item=item from=$news}
						<tr>
							<td class="main_content_text" align="center">{$item.number}</td>
							<td class="main_content_text" align="center">{$item.date}</td>
							<td class="main_content_text" align="center">{$item.title}</td>
							<td class="main_content_text" align="center">{$item.status}</td>
							<td class="main_content_text" align="center"><input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="{literal}javascript: if(confirm('{/literal}{$lang.content.del_confirm}{literal}')) document.location.href='{/literal}{$item.deletelink}{literal}';{/literal}"></td>
						</tr>
						{/foreach}
						{else}
						<tr>
							<td colspan="5" class="error" align="center">{$lang.content.no_site_news}</td>
						</tr>
						{/if}
					</table>
				</td>
			</tr>-->
		{/if}
	</table>
	</td></tr>
</table>
{literal}
<script language="javascript">
function CheckNewsCnt(){
	var min_feeds_news_cnt = {/literal}{$min_feeds_news_cnt}{literal};

	if (document.getElementById("max_news").value < min_feeds_news_cnt) {
		document.getElementById("min_feeds_news").style.color = "#E6300C";
		return false;
	} else {
		document.getElementById("min_feeds_news").style.color = "#000000";
		return true;
	}
}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}