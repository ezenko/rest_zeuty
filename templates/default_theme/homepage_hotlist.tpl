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
								{if $user_type != 1 || ($user_type == 1 && $rental_menu[m].name != "agents")}
									{if ($rental_menu[m].name != "agents" || ($rental_menu[m].name == "agents" && $user_type == 2 && $use_agent_user_type)) && ($rental_menu[m].name != "realtor" || ( $rental_menu[m].name == "realtor" && $user_type == 3 && $use_agent_user_type))}								
								<tr><td><a href="{$rental_menu[m].href}" {if {$rental_menu[m].onclick} onclick="{$rental_menu[m].onclick}" {/if} {if $submenu == $rental_menu[m].name} class="user_menu_link_active" {/if} >{$rental_menu[m].value}</a>{if $new_agents !=0 && $rental_menu[m].name == "agents"}&nbsp;({$new_agents}){/if}{if $new_company !=0 && $rental_menu[m].name == "realtor"}&nbsp;({$new_company}){/if}</td></tr>
									{/if}
								{/if}
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
		{if $place_num>0}
		<tr>
			<td valign="top" style="padding-top: 16px; padding-left: 10px;">
			<table cellpadding="3" cellspacing="2" border="0">
				<tr>
					<td>{if $place_id>0}<a href="rentals.php?sel=my_ad&id_ad={$place_id}">{/if}{$lang.default_select.link_text_1}{if $place_id>0}</a>{/if}</td>
				</tr>
				<tr>
					<td>{$lang.default_select.link_text_2}&nbsp;{$place_num}&nbsp;{$lang.default_select.link_text_3}</td>
				</tr>
				<tr>
					<td>{if $place_link eq 1}<a href='homepage.php?sel=visited_ad&amp;id_ad={$place_id}'>{/if}{$lang.default_select.link_text_4}{if $place_link eq 1}</a>{/if}</td>
				</tr>
				<tr>
					<td>{$place_day} - {$lang.default_select.link_text_5}</td>
				</tr>
				<tr>
					<td>{$place_month} - {$lang.default_select.link_text_6}</td>
				</tr>
				{if !$mhi_services}
				<tr>
					<td><a href='services.php?sel=top_search_ad&amp;type=rent&amp;id_ad={$place_id}'>{$lang.default_select.link_text_7}</a></td>
				</tr>
				{/if}
			</table>
			</td>
		</tr>
		{/if}
	{/if}
	<tr>
		<td valign="top" align="left" {if $registered}style="padding-top: 12px;"{/if}>
			{include file="$gentemplates/testimonials_post_block.tpl"}
		</td>
	</tr>
	{*if !$registered}
		{include file="$gentemplates/comparison_menu.tpl"}
	{/if*}
	</table>