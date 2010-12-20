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
				<td class="header"><b>{$lang.headers.sitemap}</b></td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td><hr></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 0px; margin-left: 15px; ">
		<tr>
			<td>{strip}
				<ul class="site_map">
				{foreach from=$map item=map}
					<li>{if $map.link}<a href={$map.link}>{/if}{$map.name}{if $map.link}</a>{/if}
						{if $map.subsection}
						<ul class="sm_subsection">
							{foreach from=$map.subsection item=map_sub}
							<li>{if $map_sub.link}<a href={$map_sub.link}>{/if}{$map_sub.name}{if $map_sub.link}</a>{/if}
								{if $map_sub.subsection}
								<ul class="sm_item">
									{foreach from=$map_sub.subsection item=map_item}
									<li>{if $map_item.link}<a href={$map_item.link}>{/if}{$map_item.name}{if $map_item.link}</a>{/if}</li>
									{/foreach}
								</ul>
								{/if}
							</li>
							{/foreach}
						</ul>
						{/if}
					</li>
				{/foreach}
				</ul>{/strip}
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}