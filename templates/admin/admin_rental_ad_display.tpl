{include file="$admingentemplates/admin_top.tpl"}
<script>
//right vars values from lightbox.js
var fileLoadingImage = "{$server}{$site_root}{$template_root}/images/lightbox/loading.gif";
var fileBottomNavCloseImage = "{$server}{$site_root}{$template_root}/images/lightbox/closelabel.gif";

</script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/scriptaculous.js?load=effects"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/lightbox.js"></script>
<!-- content-->
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="main" width="100%">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="header">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.realestate_listings} | {$lang.content.rental_ad_edit}</font></td>
			</tr>	
			<tr>
				<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.realestate_listings_help}</div></td>
			</tr>			
		</table>
		<table cellpadding="5" cellspacing="0" border="0">
		<tr>
			<td>{$lang.content.list_text_1}:&nbsp;<b>{if $profile.account.user_type eq 1}{$lang.content.user_type_1}{else}{$lang.content.user_type_2}{/if}</b></td>
		</tr>
		</table>
		<hr class="listing">		
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="27" align="right" style="padding-right: 20px;"><a href="#" onclick="if (confirm('{$lang.content.true_delete_ad}')) document.location.href='{$file_name}?sel=del&amp;id_ad={$profile.id}';">{$lang.buttons.delete_ad}</a>
		</td></tr>
		</table>				
		<hr class="listing">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">		
		{if $no_ad ne 1}
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0">
					<tr>
						<td colspan="2" style="font-size: 14px;">
							<b>{if $profile.type eq '1'}{$lang.content.category_wild}
							{elseif $profile.type eq '2'}{$lang.content.category_tours}
							{elseif $profile.type eq '3'}{$lang.content.category_realty}
							{elseif $profile.type eq '4'}{$lang.content.category_active}
							{/if}</b>
						</td>
					</tr>
					<tr>
						<td width="200" height="15"><b>{$lang.content.location}:</b>&nbsp;</td>
						<td>{$profile.country_name}{if $profile.region_name},&nbsp;&nbsp;{$profile.region_name}{/if}{if $profile.city_name},&nbsp;&nbsp;{$profile.city_name}{/if}</td>
					</tr>
				</table>
        <hr class="listing">
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=step_1&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		{else}
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.location}:</b>&nbsp;</td>
						<td class="error">{$lang.content.no_ad}</td>
					</tr>
				</table>
        <hr class="listing">
 			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=add_rent">{$lang.buttons.change}</a></td>
		</tr>
		{/if}
    
    
      <tr><td colspan="2"><hr class="listing"></td></tr>
  		<tr>			
  			<td>
  				<table cellpadding="3" cellspacing="0">
  					<tr>
  						<td height="15" width="200"><b>{$lang.content.offer_type}:</b></td>
  						<td>
                {if !$profile.parent_id}
                  {$lang.content.type_parent}
                  {if $profile.kids}
                    <br />
                    <b>{$lang.content.kids}:</b><br />
                    {foreach from=$profile.kids item=kid}
                      {if $kid.headline}{$kid.headline}{else}{$lang.content.noname}{/if}: <a href="{$file_name}?sel=my_ad&amp;id_ad={$kid.id}">{$lang.buttons.change}</a> |
                        {if $kid.status eq 1}
              					   <a href="{$file_name}?sel=deactivate_ad&amp;id_ad={$kid.id}">{$lang.content.deactivate}</a>
              				  {else}
                          <a href="{$file_name}?sel=activate_ad&amp;id_ad={$kid.id}">{$lang.content.activate}</a>            
             					  {/if}
              				<br />                    
                    {/foreach}
                  {/if}
                  <br /><a href="{$file_name}?sel=add_child&amp;id_ad={$profile.id}">{$lang.content.add_child}</a>
                {/if}
                {if $profile.parent_id}
                  {$lang.content.type_child}
                  <br />{$lang.content.parent}: {$profile.parent} <a href="{$file_name}?sel=my_ad&amp;id_ad={$profile.parent_id}">{$lang.buttons.change}</a><br />
                {/if}
              </td>						
  					</tr>					
  				</table>
  			</td>
  			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=step_type&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
  		</tr>
 
        
		<tr><td colspan="2"><hr class="listing"></td></tr>
		<tr>			
			<td>
				<table cellpadding="3" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.headline}:</b></td>
						{if $profile.headline}
						  <td>{$profile.headline}</td>						
						{else}
						  <td class="error">{$lang.content.noheadline}</td>
						{/if}						
					</tr>					
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=step_8&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		<tr><td colspan="2"><hr class="listing"></td></tr>
    <tr>			
			<td>
				<table cellpadding="3" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.general_info}:</b></td>
						{if $profile.comment}
						  <td>{$profile.comment}</td>						
						{else}
						  <td class="error">{$lang.content.general_info}</td>
						{/if}						
					</tr>					
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=step_6&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		<tr><td colspan="2"><hr class="listing"></td></tr>
		{if $no_ad ne 1}
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
				{if ($profile.type eq '2' || $profile.type eq '4' || $profile.type eq '1' || $profile.type eq '3')}
				<!-- have/sell realty -->
					{if $profile.min_payment>0 || $profile.price}
						{if $profile.type eq 2 || $profile.type eq 4 || $profile.type eq 3}
            <tr>
							<td colspan="2">{$lang.content.ad_text_1}</td>
						</tr>
						<tr>
							<td width="200"><b>{if $profile.type eq 2}{$lang.content.price_season}{else}{$lang.content.price}{/if}:&nbsp;</b></td>
							<td>{$profile.min_payment_show}</td>
						</tr>
            {/if}
            {if $profile.type eq 1}
							<!-- payment by month -->
							<tr>
  							<td width="200"><b>{$lang.content.price_by_month}:&nbsp;</b></td>
  							<td>
                  <table>
                    <tr>
                      <td>{$lang.content.january}</td>
                      <td>{$lang.content.february}</td>
                      <td>{$lang.content.march}</td>
                      <td>{$lang.content.april}</td>
                      <td>{$lang.content.may}</td>
                      <td>{$lang.content.june}</td>
                      <td>{$lang.content.july}</td>
                      <td>{$lang.content.august}</td>
                      <td>{$lang.content.september}</td>
                      <td>{$lang.content.october}</td>
                      <td>{$lang.content.november}</td>
                      <td>{$lang.content.december}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>{$profile.price.january}</td>
                      <td>{$profile.price.february}</td>
                      <td>{$profile.price.march}</td>
                      <td>{$profile.price.april}</td>
                      <td>{$profile.price.may}</td>
                      <td>{$profile.price.june}</td>
                      <td>{$profile.price.july}</td>
                      <td>{$profile.price.august}</td>
                      <td>{$profile.price.september}</td>
                      <td>{$profile.price.october}</td>
                      <td>{$profile.price.november}</td>
                      <td>{$profile.price.december}</td>
                      <td>{$cur}</td>
                    </tr>
                  </table>
                </td>
  						</tr>
							<!-- /payment by month -->
						{/if}
						{if $profile.type eq 2}
							<!-- payment not season -->
							<tr>
  							<td width="200"><b>{$lang.content.price_not_season}:&nbsp;</b></td>
  							<td>{$profile.payment_not_season_show}</td>
  						</tr>
							<!-- /payment not season -->
						{/if}
						{if $profile.min_deposit > 0}
						<tr>
							<td><b>{$lang.content.deposit}:&nbsp;</b></td>
							<td>{$profile.min_deposit}</td>
						</tr>
						{/if}
						{if $profile.movedate}
						<tr>
							<td><b>{$lang.content.available_date}:&nbsp;</b></td>
							<td>{$profile.movedate}</td>
						</tr>
						{/if}
						<!-- realty type -->
						{section name=b loop=$profile.realty_type}
							<tr>
								<td><b>{$profile.realty_type[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.realty_type[b].fields}{$profile.realty_type[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
						{/section}
						<!-- /realty type -->
						{if $profile.min_year_build > 0}
						<tr>
							<td><b>{$lang.content.year_build}:&nbsp;</b></td>
							<td>{$profile.min_year_build}</td>
						</tr>
						{/if}
						<!-- description -->
						{section name=b loop=$profile.description}
							<tr>
								<td><b>{$profile.description[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.description[b].fields}{$profile.description[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
						{/section}
						<!-- /description -->
						{if $profile.min_live_square > 0}
						<tr>
							<td><b>{$lang.content.live_square}:&nbsp;</b></td>
							<td>{$profile.min_live_square}&nbsp;{$sq_meters}</td>
						</tr>
						{/if}
						{if $profile.min_total_square > 0}
						<tr>
							<td><b>{$lang.content.total_square}:&nbsp;</b></td>
							<td>{$profile.min_total_square}&nbsp;{$sq_meters}</td>
						</tr>
						{/if}
						{if $profile.min_land_square > 0}
						<tr>
							<td><b>{$lang.content.land_square}:&nbsp;</b></td>
							<td>{$profile.min_land_square}&nbsp;{$sq_meters}</td>
						</tr>
						{/if}
						{if $profile.floor}
						<tr>
							<td><b>{$lang.content.floor}:&nbsp;</b></td>
							<td>{$profile.floor}</td>
						</tr>
						{/if}
						{if $profile.floor_num}
						<tr>
							<td><b>{$lang.content.floors}:&nbsp;</b></td>
							<td>{$profile.floors}</td>
						</tr>
						{/if}
						{if $profile.ceil_height}
						<tr>
							<td><b>{$lang.content.ceil_height}:&nbsp;</b></td>
							<td>{$profile.ceil_height}</td>
						</tr>
						{/if}
            {if $profile.sea_distance}
						<tr>
							<td><b>{$lang.content.sea_distance}:&nbsp;</b></td>
							<td>{$profile.sea_distance}</td>
						</tr>
						{/if}
            {if $profile.parking}
						<tr>
							<td><b>{$lang.content.parking}:&nbsp;</b></td>
							<td>{$profile.parking}</td>
						</tr>
						{/if}
            {if $profile.total_square}
						<tr>
							<td><b>{$lang.content.total_square}:&nbsp;</b></td>
							<td>{$profile.total_square}</td>
						</tr>
						{/if}
						<!-- info -->
						{section name=b loop=$profile.info}
							<tr>
								<td><b>{$profile.info[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.info[b].fields}{$profile.info[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
						{/section}
						<!-- /info -->
            <!-- theme rest -->
						{section name=b loop=$profile.theme_rest}
							<tr>
								<td><b>{$profile.theme_rest[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.theme_rest[b].fields}{$profile.theme_rest[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
						{/section}
						<!-- /theme rest -->
					{else}
						<tr>
							<td height="15" width="200"><b>{$lang.content.ad_text_1}:</b>&nbsp;</td>
							<td class="error">{$lang.content.not_filled}</td>
						</tr>
					{/if}
				{/if}
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=step_3&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		{else}
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{if $profile.type eq 2 || $profile.type eq 4}{$lang.content.ad_text_1}{else}{$lang.content.ad_text_1_2}{/if}:</b>&nbsp;</td>
						<td class="error">{$lang.content.no_ad}</td>
					</tr>
				</table>
 			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=add_rent">{$lang.buttons.change}</a></td>
		</tr>
		{/if}
		<tr><td colspan="2"><hr class="listing"></td></tr>
		{if $profile.type eq 2 || $profile.type eq 4}
		<tr><td colspan="2"><hr class="listing"></td></tr>
	{/if}
		
		{if $profile.type eq 1 || $profile.type eq 2 || $profile.type eq 4 || $profile.type eq 3}
		{if $no_ad ne 1}
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
					<tr><td>
							{if $profile.photo_id}
							{$lang.content.ad_text_4}:&nbsp;
							<table cellpadding="3" cellspacing="3" border="0">
								{section name=ph loop=$profile.photo_id}
								{if $smarty.section.ph.index is div by 3}<tr>{/if}
								<td valign="top">
									<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; vertical-align: middle;" align="center">
											{if $profile.photo_view_link[ph]}<a href="{$profile.photo_file[ph]}" rel="lightbox[profile_photo]"title="{$profile.photo_user_comment[ph]}">{/if}
											<img src='{$profile.thumb_file[ph]}' style="border: none;" align="absmiddle">
											{if $profile.photo_view_link[ph]}</a>{/if}
										</td>
									</tr>
									<tr>
										<td width="{$thumb_width+10}" style="padding-top: 2px;">{strip}
											{if $profile.photo_user_comment[ph]}{$profile.photo_user_comment[ph]}<br>{/if}
											{if $profile.photo_status[ph]==1}<!--{$lang.content.active_file}<br>-->{else}<font class="error_small">- {$lang.content.inactive_file}</font><br>{/if}
											{if $profile.use_photo_approve}
												{if $profile.photo_admin_approve[ph] == 0}
													<font class="error_small">- {$lang.content.admin_approve_not_complete}</font>
												{elseif $profile.photo_admin_approve[ph] == 1}
													<!--{$lang.content.admin_approve_acepted}-->
												{elseif $profile.photo_admin_approve[ph] == 2}
													<font class="error_small">- {$lang.content.admin_approve_decline}</font>
												{/if}
											{/if}
											{/strip}
										</td>
									</tr>
									</table>
								</td>
								{/section}
								{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
							</table>
							{else}
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="200"><b>{$lang.content.ad_text_4}:</b></td>
								<td style="padding-left: 6px;"><font class="error">{$lang.content.no_photos}</font></td>
							</tr>
							</table>
							{/if}
					</td></tr>
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a {if ($profile.type eq 1 || $profile.type eq 2 || $profile.type eq 4)} href="{$file_name}?sel=step_4&amp;id_ad={$profile.id}" {else} href="{$file_name}?sel=step_5&amp;id_ad={$profile.id}" {/if}>{$lang.buttons.change}</a></td>
		</tr>
		{else}
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.ad_text_4}:</b>&nbsp;</td>
						<td class="error">{$lang.content.no_ad}</td>
					</tr>
				</table>
 			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=edit_ad">{$lang.buttons.change}</a></td>
		</tr>
		{/if}
		<tr><td colspan="2"><hr class="listing"></td></tr>
		{/if}	
		<!-- video -->
		{if $profile.type eq 1 || $profile.type eq 2 || $profile.type eq 4}
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
					<tr><td>
							{if $profile.video_id}
							{$lang.content.ad_text_video}:&nbsp;
								<table cellpadding="3" cellspacing="3" border="0">
									{section name=ph loop=$profile.video_id}
									{if $smarty.section.ph.index is div by 3}<tr>{/if}
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; vertical-align: middle;" align="center">
											{if $profile.video_view_link[ph]}<a href="#" onclick="javascript:var top_pos = (window.screen.height - {$profile.height[ph]+70})/2; var left_pos = (window.screen.width - {$profile.width[ph]})/2; window.open('{$profile.video_view_link[ph]}','video_view','top='+top_pos+', left='+left_pos+', menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$profile.width[ph]}, height={$profile.height[ph]+70}');return false;" title="{$profile.video_user_comment[ph]}">{/if}<img src='{$profile.video_icon[ph]}' class='upload_thumb' style="border:0px;" alt="{$profile.video_user_comment[ph]}">{if $profile.video_view_link[ph]}</a>{/if}									
											</td>
										</tr>
										<tr>
											<td width="{$thumb_width+10}" style="padding-top: 2px;">{strip}
												{if $profile.video_user_comment[ph]}{$profile.video_user_comment[ph]}<br>{/if}
												{if $profile.video_status[ph]==1}<!--{$lang.content.active_file}<br>-->{else}<font class="error_small">- {$lang.content.inactive_file}</font><br>{/if}
												{if $profile.use_video_approve}
													{if $profile.video_admin_approve[ph] == 0}
														<font class="error_small">- {$lang.content.admin_approve_not_complete}</font>
													{elseif $profile.video_admin_approve[ph] == 1}
														<!--{$lang.content.admin_approve_acepted}-->
													{elseif $profile.video_admin_approve[ph] == 2}
														<font class="error_small">- {$lang.content.admin_approve_decline}</font>
													{/if}
												{/if}
												{/strip}
											</td>
										</tr>
										</table>
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
								</table>
							{else}
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="200"><b>{$lang.content.ad_text_video}:</b></td>
								<td style="padding-left: 6px;"><font class="error">{$lang.content.no_video}</font></td>
							</tr>
							</table>
							{/if}
					</td></tr>
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="{$file_name}?sel=upload_video&amp;id_ad={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		<tr><td colspan="2"><hr class="listing"></td></tr>
		{/if}
		<!-- account info -->
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
						<tr>
							<td colspan="2">{if $profile.account.user_type eq 1 || $profile.account.user_type eq 3}{$lang.content.account_about_me}{else}{$lang.content.account_about_us}{/if}</td>
						</tr>
						<tr>
							<td width="200"><b>{$lang.content.fname}:</b></td>
							<td>{$profile.account.fname}</td>
						</tr>
						<tr>
							<td width="200"><b>{$lang.content.sname}:</b></td>
							<td>{$profile.account.sname}</td>
						</tr>
						{if $profile.agent_photo_path !='' && $profile.agent_photo_admin_approve && $profile.account.user_type eq 3}
						<tr>
							<td><b>{$lang.content.photo}:</b></td>
							<td>
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td width="{$thumb_height+10}" height="{$thumb_height+10}" style="border-style: solid; border-width: 1px; border-color: #cccccc;"><img src="{$profile.agent_photo_path}" style="padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></td>
							</tr>
							</table>
							</td>							
							
						</tr>
						{/if}
						<tr>
							<td width="200"><b>{$lang.content.email}:</b></td>
							<td>{$profile.account.email}</td>
						</tr>
						{if $profile.account.phone}
						<tr>
							<td width="200"><b>{$lang.content.phone}:</b></td>
							<td>{$profile.account.phone}</td>
						</tr>
						{/if}
						{if $profile.account.user_type eq 2}
						{* realtor *}
							<tr>
								<td width="200"><b>{$lang.content.company_name}:</b></td>
								<td>{$profile.account.company_name}</td>
							</tr>
							{if $profile.account.company_url != ""}
							<tr>
								<td width="200"><b>{$lang.content.company_url}:</b></td>
								<td>{$profile.account.company_url}</td>
							</tr>
							{/if}
							<tr>
								<td width="200"><b>{$lang.content.our_logo}:</b></td>
								<td>{if $profile.account.logo_path !=''}<img src="{$profile.account.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{else}<font class="error">{$lang.content.no_photos}</font>{/if}</td>
							</tr>
							
							{if $profile.account.country_name}
							<tr>
								<td width="200"><b>{$lang.content.location}:</b></td>
								<td>{$profile.account.country_name}{if $profile.account.region_name}, {$profile.account.region_name}{/if}{if $profile.account.city_name}, {$profile.account.city_name}{/if}{if $profile.account.address}, {$profile.account.address}{/if}</td>
							</tr>
							{/if}
							
							{if $profile.account.postal_code != ""}
							<tr>
								<td width="200"><b>{$lang.content.zip_code}:</b></td>
								<td>{$profile.account.postal_code}</td>
							</tr>
							{/if}
							{if $profile.account.weekday}
							<tr>
								<td width="200"><b>{$lang.content.work_days}:</b></td>
								<td>
								{foreach name=week key=key item=item from=$week}
									{if $profile.account.weekday.$key eq $item.id }{$item.name}{/if}
								{/foreach}
								</td>
							</tr>
							{/if}
							{if $profile.account.work_time_begin > 0 || $profile.account.work_time_end > 0}
							<tr>
								<td width="200"><b>{$lang.content.work_time}:</b></td>
								<td>{$lang.content.time_begin}&nbsp;
								{foreach item=item from=$time_arr}
									{if $profile.account.work_time_begin eq $item.value}{$item.value}{/if}
								{/foreach}
								&nbsp;{$lang.content.time_end}&nbsp;
								{foreach item=item from=$time_arr}
									{if $profile.account.work_time_end eq $item.value}{$item.value}{/if}
								{/foreach}
								</td>
							</tr>
							{/if}
							{if $profile.account.lunch_time_begin > 0 || $profile.account.lunch_time_end > 0}
							<tr>
								<td width="200"><b>{$lang.content.lunch_time}:</b></td>
								<td>{$lang.content.time_begin}&nbsp;
								{foreach item=item from=$time_arr}
									{if $profile.account.lunch_time_begin eq $item.value}{$item.value}{/if}
								{/foreach}
								&nbsp;{$lang.content.time_end}&nbsp;
								{foreach item=item from=$time_arr}
									{if $profile.account.lunch_time_end eq $item.value}{$item.value}{/if}
								{/foreach}
								</td>
							</tr>
							{/if}

						{elseif $profile.account.user_type eq 1}
						{* private person *}
							<tr>
								<td width="200"><b>{$lang.content.birthday}:</b></td>
								<td>{$profile.account.birth_month}.{$profile.account.birth_day}.{$profile.account.birth_year}</td>
							</tr>
							{section name=b loop=$profile.gender}
							<tr>
								<td width="200"><b>{$profile.gender[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.gender[b].fields}{$profile.gender[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
							{/section}
							{section name=b loop=$profile.people}
							<tr>
								<td width="200"><b>{$profile.people[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.people[b].fields}{$profile.people[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
							{/section}
							{section name=b loop=$profile.language}
							<tr>
								<td width="200"><b>{$profile.language[b].name}:&nbsp;</b></td>
								<td>{section name=c loop=$profile.language[b].fields}{$profile.language[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
								</td>
							</tr>
							{/section}
						{elseif $profile.account.user_type eq 3}
							{* agent of company *}	
							{if $company_data.company_name}		
							<tr>
								<td colspan="3" style="padding-top:15px;">{$lang.content.about_company}</td>
							</tr>							
																				
							<tr>
								<td><b>{$lang.content.company_name}:</b></td>
								<td colspan="2">{$company_data.company_name}</td>
							</tr>
							{/if}
							
								{if $company_data.company_url != ""}
								<tr>
									<td><b>{$lang.content.company_url}:</b></td>
									<td colspan="2"><a href="{$company_data.company_url}" target="_blank">{$company_data.company_url}</a></td>
								</tr>
								{/if}
							
							{if $company_data.logo_path !='' && $company_data.admin_approve==1}
								<tr>
									<td><b>{$lang.content.company_logo}:</b></td>
									<td width="{$thumb_width+10}" height="{$thumb_height+10}" valign="middle" align="center" style="border-style: solid; border-width: 1px; border-color: #cccccc;"><img src="{$company_data.logo_path}" style="padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></td>
									<td></td>
								</tr>
							{/if}	
							
								{if (($company_data.country_name)||($company_data.region_name)||($company_data.city_name)||($company_data.address))}
								<tr>
									<td><b>{$lang.content.location}:</b></td>
									<td colspan="2">
										{$company_data.country_name}{if $company_data.region_name}, {$company_data.region_name}{/if}{if $company_data.city_name}, {$company_data.city_name}{/if}{if $company_data.address}, {$company_data.address}{/if}
									</td>
								</tr>
								{/if}

								{if $company_data.postal_code}
								<tr>
									<td><b>{$lang.content.zipcode}:</b></td>
									<td colspan="2">
										{$company_data.postal_code}
									</td>
								</tr>
								{/if}
								{if $profile.country_name && $data.in_base && $use_maps_in_viewprofile}
									
									<tr>
										<td colspan="3">									
											<div id="map_container" {if $map.name == "mapquest"} style="width: 550px; height: 550px;" {elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
										</td>
									</tr>
								{/if}
								{if $company_data.weekday}
								<tr>
									<td><b>{$lang.content.work_days}:</b></td>
									<td colspan="2">
									{foreach name=week key=key item=item from=$week}
										{if $company_data.weekday.$key eq $item.id }{$item.name}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
								{if $company_data.work_time_begin > 0 || $company_data.work_time_end > 0}
								<tr>
									<td><b>{$lang.content.work_time}:</b></td>
									<td colspan="2">{$lang.content.time_begin}&nbsp;
									{foreach item=item from=$time_arr}
										{if $company_data.work_time_begin eq $item.value}{$item.value}{/if}
									{/foreach}
									&nbsp;{$lang.content.time_end}&nbsp;
									{foreach item=item from=$time_arr}
										{if $company_data.work_time_end eq $item.value}{$item.value}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
								{if $company_data.lunch_time_begin > 0 || $company_data.lunch_time_end > 0}
								<tr>
									<td><b>{$lang.content.lunch_time}:</b></td>
									<td colspan="2">{$lang.content.time_begin}&nbsp;
									{foreach item=item from=$time_arr}
										{if $company_data.lunch_time_begin eq $item.value}{$item.value}{/if}
									{/foreach}
									&nbsp;{$lang.content.time_end}&nbsp;
									{foreach item=item from=$time_arr}
										{if $company_data.lunch_time_end eq $item.value}{$item.value}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
							
						{/if}
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;"><a href="admin_settings.php?section=admin&redirect={$profile.id}">{$lang.buttons.change}</a></td>
		</tr>
		<!-- /account info -->
		</table>
	</td>
</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}