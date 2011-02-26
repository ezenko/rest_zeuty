<link rel="stylesheet" type="text/css" href="{$site_root}{$template_root}{$template_css_root}/jquery.pagination.css" />
<script type="text/javascript" src="{$site_root}{$template_root}/js/jquery.pagination.min.js"></script>
<script type="text/javascript">
var cur_page = {$page-1};
{literal}
    $(document).ready(function(){
        $("#pg").pagination({/literal}{$search_size}{literal},
          {
            callback: pageFilterCallback,
            items_per_page: 5,
            num_display_entries: 5,
            link_to: '#catalog',
            num_edge_entries: 2,
            prev_text: '<',
            next_text: '>',
            current_page: cur_page
          });
		
    });   
    
    function pageFilterCallback(page_index) {
        if(page_index != cur_page) {
            $('#search_res_form').find('input[name=page]').val(page_index+1);
            $('#search_res_form').submit();
        }
        return false;
    } 
{/literal}
</script>
<form id="search_res_form" style="display:none" action="/quick_search.php">
    <input type="hidden" name="sel" value="{$sel}"/>
    <input type="hidden" name="choise" value="{$choise}"/>
    <input type="hidden" name="page" value="{$page}"/>
</form>
<h3>&nbsp;&nbsp;&nbsp;Краткая информация:</h3>
  	<div id="catalog">
    
	{section name=u loop=$search_result}
    <div class="item">
        <img alt="" src="img/fake/img1.jpg" class="img-border" />
        <div class="desc">
        	<h4>Египет, Шарм-Эль-Шейх</h4>
          <span>Отель: Антигуа</span>
          <span>Звезд: 5</span>
          <span>Питание: DBL</span>
          <span>Стоимость: от 670$</span>
        </div>
      </div>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom: 10px;margin-top: 0px;">
	<tr>
		<td width="15">&nbsp;</td>
		<td width="{$thumb_width+10}" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
						<img src="{$site_root}{$search_result[u].image}" alt="{$search_result[u].alt}" style="border: none; cursor: pointer;" onclick="document.location.href='{$search_result[u].viewprofile_link}';">
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" style="padding-left: 7px;">
			<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr valign="top">
					<td valign="top"><a href="{$search_result[u].viewprofile_link}">{$search_result[u].login}</a>,&nbsp;
					<strong>
					{if $search_result[u].id_type eq 1}{$lang.content.i_need}
					{elseif $search_result[u].id_type eq 2}{$lang.content.i_have}
					{elseif $search_result[u].id_type eq 3}{$lang.content.i_buy}
					{elseif $search_result[u].id_type eq 4}{$lang.content.i_sell}
					{/if}
					{$search_result[u].realty_type}
					</strong>
					</td>
				{if $search_result[u].country_name || $search_result[u].region_name || $search_result[u].city_name}
				<tr>
					<td>{$search_result[u].country_name}{if $search_result[u].region_name},&nbsp;{$search_result[u].region_name}{/if}{if $search_result[u].city_name},&nbsp;{$search_result[u].city_name}{/if}</td>
				</tr>
				{/if}
				<tr>
					<td>
					{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}:&nbsp;<strong>
					{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 3}
						{$lang.content.from}&nbsp;{$search_result[u].min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$search_result[u].max_payment_show}
					{else}
						{$search_result[u].min_payment_show}
					{/if}</strong>,&nbsp;{if $search_result[u].auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}
					</td>
				</tr>
				{*<tr>
					<td>
					{if $search_result[u].movedate}
						{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 3}
							{$lang.content.move_in_date}
						{else}
							{$lang.content.available_date}
						{/if}
						:&nbsp;<strong>{$search_result[u].movedate}</strong>{if $search_result[u].id_type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$search_result[u].id_ad}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}
					{/if}
					</td>
				</tr>
				{if $search_result[u].reserve.is_reserved}
					<tr>
						<td>					
							{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$search_result[u].reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$search_result[u].reserve.reserved_end_period}</strong>
							&nbsp;<a href='{$site_root}/viewprofile.php?id={$search_result[u].id_ad}&view=calendar'>{$lang.default_select.other_period}</a>
						</td>
					</tr>
				{/if}
				{if $search_result[u].phone && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
				<tr>
					<td>{$lang.default_select.call_him}: {$search_result[u].phone}</td>
				</tr>
				{elseif ($group_type ne 1 && $registered eq 1)}
				<tr>
					<td>{$lang.default_select.group_err_1}<a href="services.php?sel=group">{$lang.default_select.group_err_2}</a>{$lang.default_select.group_err_3}</td>
				</tr>
				{elseif ($registered eq 0)}
				<tr id="mhi_registration" style="display: {$mhi_registration}">
					<td>{$lang.default_select.contact_for_reg}.&nbsp;<a href="registration.php">{$lang.content.reg_now_text}</a></td>
				</tr>
				{/if}
				*}
				{if $search_result[u].headline}
				<tr>
					<td>{$search_result[u].headline}</td>
				</tr>
				{/if}
				{if $file_name != "index.php"}
				<tr>
					<td id="listing_add_to_comparison_{$search_result[u].id_ad}">
					<a href="#" onclick="javascript: AddToComparisonList('{$search_result[u].id_ad}', 'listing_add_to_comparison_{$search_result[u].id_ad}');">{$lang.default_select.add_to_comparison_list}</a>
					</td>
				</tr>
				{/if}
			</table>
		</td>
		<td align="left" valign="top" width="10">
			<table cellpadding="0" cellspacing="0">
			<tr>
				{if !$mhi_services}
					{if $search_result[u].show_topsearch_icon}
					<td><img alt="{$lang.default_select.star_alt} {$search_result[u].topsearch_date_begin}" title="{$lang.default_select.star_alt} {$search_result[u].topsearch_date_begin}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1"></td>
					{/if}
					{if $search_result[u].slideshowed}
					<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>
					{/if}
					{if $search_result[u].featured}
					<td><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>
					{/if}
				{/if}
				{if $search_result[u].issponsor}
				<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
				{/if}
				{if $use_sold_leased_status}
					{if $search_result[u].sold_leased_status && ($search_result[u].id_type eq '2' || $search_result[u].id_type eq '4')}
					<td><img alt="{if $search_result[u].id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $search_result[u].id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $search_result[u].id_type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
					{/if}
				{/if}
				<td>&nbsp;</td>
			</tr>
			</table>
		</td>
		</tr>
	</tr>

	</table>
	{/section}
    </div>
    <center>
      <div id="pg" class="clearfix">
      
      </div>
	  <br/><br/><br/>
	  <!--div id="YMapsID" style="width:600px;height:400px"></div-->
    </center>
    {*
	{if $last_ads && $search_result}

	<table cellpadding="2" cellspacing="2" border="0" width="100%">
		<tr>
			<td align="right" style="padding-right: 10px;"><a href="quick_search.php?sel=last_ads">{$lang.default_select.more_link_text}</a></td>
		</tr>
	</table>
	{else}
		{if $links}
				{foreach item=item from=$links}
				<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
				{/foreach}
		{/if}
	{/if}
	<!--<hr width="100%">-->
    *}

{if $search_result && $use_maps_in_search_results && $sel && $map.name == "google"}
{include file="$gentemplates/search_viewmap.tpl"}
{/if}
