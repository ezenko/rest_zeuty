	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	{if $registered}
	<tr><td width="100%" class="subheader"><b>{$lang.headers.rental_menu}</b></td>
	<tr>
		<td style="padding-top: 7px;">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" class="rental_menu">
				{if $content_name eq 'quick_search'}
				<tr>
					<td><a href="power_searchr.php">{$lang.content.menu_text_1}</a></td>
				</tr>
				{/if}
				{if $content_name eq 'power_searchr'}
				<tr>
					<td><a href="quick_search.php">{$lang.content.menu_text_2}</a></td>
				</tr>
				{/if}
				{section name=m loop=$search_menu}
					<tr><td>{if $search_menu[m].value ne ''}<a href="{$search_menu[m].href}" {if $submenu == $search_menu[m].name} class="user_menu_link_active" {/if} >{$search_menu[m].value}</a>{else}&nbsp;{/if}</td></tr>
				{/section}
				{if $content_name eq 'power_searchr'}
				<tr>
					<td style="padding-top: 10px;"><a href="#saved_search">{$lang.content.powersearch_by_saved_search}</a></td>
				</tr>
				<tr>
					<td><a href="#search_by_name">{$lang.content.powersearch_by_user_name}</a></td>
				</tr>
				{/if}
				{*include file="$gentemplates/comparison_menu.tpl"*}
				</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	{/if}
	<!-- keyword search-->
	<tr><td width="100%" class="subheader"><b>{$lang.default_select.keyword_search}</b></td>
	<tr>
		<td style="padding: 0px; padding-left: 12px; padding-top: 5px; ">
		<form action="{$server}{$site_root}/quick_search.php?sel=keyword" name="keyword_search" method="POST">
			<input type="hidden" name="from_file" value="{$from_file}">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td style="padding: 0px; padding-bottom: 5px;">{$lang.default_select.keyword_search_help}</td>
				<tr>
					<td style="padding: 0px;padding-bottom: 5px;"><input type="text" name="keyword"></td>
				</tr>
				<tr>
					<td style="padding: 0px; padding-bottom: 25px;"><input type="button" class="btn_small" value="{$lang.default_select.button_search}" onclick="document.keyword_search.submit();"></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<!-- /keyword search-->
	<tr>
		<td valign="top" align="left">
			{include file="$gentemplates/testimonials_post_block.tpl"}
		</td>
	</tr>
	{*if !$registered}
		{include file="$gentemplates/comparison_menu.tpl"}
	{/if*}
	</table>