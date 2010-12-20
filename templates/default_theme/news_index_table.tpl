{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
	<!--  news CONTENT -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		{if $banner.center}
			<tr>
				<td>
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				 <!-- /banner center -->
				</td>
			</tr>
		{/if}	
			<tr valign="top">
				<td class="header"><b>{$lang.headers.news}</b></td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td><hr></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		{if $sel == "read"}
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr>
						<td class="subheader" align="left"><b>{$news.title}</b></td>
						<td style="padding-right: 25px;" class="subheader" align="right">{$news.date_add}</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-left: 15px; padding-top: 5px; padding-bottom: 10px; padding-right: 5px;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td></td>
							</tr>
							<tr>
								<td style="padding-top: 5px;">{$news.news_text}</td>
							</tr>
							<tr>
								<td style="padding-right: 20px; padding-top: 5px;" align="right"><a href="{$news.channel_link}" target="_blank">{$news.channel_name}</a></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				<a href="{$server}{$site_root}/news.php">{$lang.content.back_to_news}</a>
				</td>
			</tr>
		{else}
		<!-- not read-->
			<tr>
				<td>
				{section name=n loop=$news}
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="subheader">{$news[n].date_add}</td>
					</tr>
					<tr>
						<td style="padding-left: 15px; padding-top: 5px; padding-bottom: 10px; padding-right: 5px;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td><a href="{$news[n].news_link}" {if $news[n].id_channel != "0"}target="_blank"{/if}>{$news[n].title}</a></td>
							</tr>
							<tr>
								<td style="padding-top: 5px;">{$news[n].news_text}</td>
							</tr>
							<tr>
								<td style="padding-top: 5px;"><a href="{$news[n].channel_link}" target="_blank">{$news[n].channel_name}</a></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				{/section}
				</td>
			</tr>
			{if $links}
			<tr>
				<td>
					<table cellpadding="2" cellspacing="2" border="0">
						<tr>
							<td class="text">{$lang.default_select.pages}:
							{foreach item=item from=$links}
							<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
							{/foreach}
							</td>
						</tr>
					</table>
				</td>
			</tr>					
			{/if}
		<!-- /not read-->
		{/if}

		</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}