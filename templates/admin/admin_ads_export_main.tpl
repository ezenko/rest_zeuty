{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
	<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.listings} | {$lang.menu.listings_feed}</font></td>
				</tr>
				<tr>
					<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.export_main_help}</div></td>
				</tr>
				
			</table>

			<!--Choosing of feed format	menu -->		
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="padding-bottom: 10px; padding-left:10px;">
					{strip}
					{foreach from=$feeds.feed_key item=feed_key}	
						{assign var=feed_type_cur value=feed_type_$feed_key}													
						{if $feed_type == $feed_key}<b>{else}<a href='{$file_name}?sel=main&feed_type={$feed_key}'>{/if}{$lang.content[$feed_type_cur]}{if $feed_type == $feed_key}</b>{else}</a>{/if}&nbsp;&nbsp;
					{/foreach}
					{/strip}
					</td>
				</tr>
			</table>
			<!-- end of menu-->
			
			{if $feed_type != 0}
				<form name="export_form" action="{$file_name}" method="POST">
				<input type="hidden" name="sel" value="{if $not_croned}create_feed{else}update_feed{/if}">
				<input type="hidden" name="feed_type" value="{$feed_type}">
			{/if}		
			<table cellpadding="3" cellspacing="0" width="80%" class="table_main" style="margin-top:10px;">
				<tr>
					<td align="left" width="20%">
						<b>{$lang.content.how_to}:</b>
					</td>
				</tr>	
					<td>
						{assign var=feed_help_cur value=feed_help_$feed_type}
						{$lang.content[$feed_help_cur]}
					</td>
				</tr>
			
				{if $feed_type == 1 || $feed_type == 2 || $feed_type == 3}
				<tr>
					<td align="left">
						<b>{$lang.content.choose_parameters}:</b>
					</td>
				</tr>	
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
								{$lang.content.site_name}:
								</td>
								<td>
								<input id="site_name" type="text" class="str" value="{$site_name}" name="site_name" style="margin:3px;"></input>
								</td>
							</tr>
							{if $feed_type == 3}
							<tr>
								<td>
								{$lang.content.title_rss}:
								</td>
								<td>
								<input id="title_rss" type="text" class="str" value="{$title_rss}" name="title_rss" style="margin:3px; width: 450px;"></input>
								</td>
							</tr>
							<tr>
								<td>
								{$lang.content.description_rss}:
								</td>
								<td>
								<textarea id="description_rss" class="str" rows="2" name="description_rss" style="margin:3px; width: 450px;">{$description_rss}</textarea>
								</td>
							</tr>
							<tr>
								<td>
								{$lang.content.ads_title_rss}:
								</td>
								<td>
								<input id="title_ads_rss" class="str" value="{$title_ads_rss}" name="title_ads_rss" style="margin:3px; width: 450px;"></input>
								</td>
							</tr>
							<tr>
								<td>
								{$lang.content.ads_description_rss}:
								</td>
								<td>
								<input id="description_ads_rss" class="str" value="{$description_ads_rss}" name="description_ads_rss" style="margin:3px; width: 450px;"></input>
								</td>
							</tr>
							{/if}
						</table>					
					</td>
				</tr>
				{/if}
				
				<tr>
					<td align="left">
						<b>{$lang.content.path_to_file}:</b>
					</td>
				</tr>
				<tr>	
					<td>
					{if $not_croned}{$lang.content.feed_not_found}{else}<a href='{$feeds.feed_url}' target="_blank">{$feeds.feed_url}</a>{/if}
					
						{if $feed_type!=3}
						<input type="button" class="button_3" value="{if $not_croned}{$lang.buttons.create_feed_file}{else}{$lang.buttons.update_feed_file}{/if}" onclick="document.location='{$file_name}?sel={if $not_croned}create_feed{else}update_feed{/if}&feed_type={$feed_type}{if $feed_type==1 || $feed_type==2}&site_name='+document.getElementById('site_name').value{else}'{/if};">
						{else}
						<input type="submit" value="{if $not_croned}{$lang.buttons.create_feed_file}{else}{$lang.buttons.update_feed_file}{/if}">
						{/if}
					</td>
				</tr>
				
				
			</table>
			{if $feed_type == 3}</form>	{/if}
			<!-- about format -->
			<table cellpadding="0" cellspacing="0" width="" align="right">
				<tr>
					<td>
						{assign var=feed_type_cur value=feed_type_$feed_type}
						{$lang.content.read_more}<b><a target="_blank" href='{$feeds.url_read_more[$feed_type]}'>{$lang.content[$feed_type_cur]}</a></b>{$lang.content.read_more_on}
					</td>
				</tr>			
			</table>
			<!--end section "about format" -->
	</td></tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}