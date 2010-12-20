	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	{if $registered}
		<tr><td class="subheader"><b>{$lang.default_select.menu}</b></td></tr>
		<tr>
			<td style="padding-top: 7px; padding-bottom: 7px;">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="padding: 0px; padding-left: 10px;">
						<table cellpadding="0" cellspacing="0" border="0" class="rental_menu">
							{section name=m loop=$rental_menu}
								<tr><td><a href="{$rental_menu[m].href}" {if {$rental_menu[m].onclick} onclick="{$rental_menu[m].onclick}" {/if} {if $submenu == $rental_menu[m].name} class="user_menu_link_active" {/if} >{$rental_menu[m].value}</a></td></tr>
							{/section}
						</table>
					</td>
				</tr>
				<!--<tr>
					<td style="padding: 0px; padding-left: 15px;">
						<table cellpadding="0" cellspacing="0" border="0">
							{include file="$gentemplates/comparison_menu.tpl"}
						</table>
					</td>
				</tr>-->
				</table>
			</td>
		</tr>
		
	{/if}
	
	{*if !$registered}
		{include file="$gentemplates/comparison_menu.tpl"}
	{/if*}
	</table>