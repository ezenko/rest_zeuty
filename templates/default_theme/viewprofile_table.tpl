{include file="$gentemplates/site_top.tpl"}
<style>
{literal}
.parent_desc {
    margin-top:-20px;
    padding-bottom:20px
    } 
.parent_desc .row {
    float:left;
    width: 266px;
    }
{/literal}
</style>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/jquery.lightbox-0.5.js"></script>
<script type="text/javascript">
{literal}
$(document).ready(function() {
			
			$("a.icon").tooltip({
				offset: [-6, 0],
				onShow: function() {
					this.getTrigger().fadeTo("slow", 0.8);
				}
			});
			
			$('.suit-header').each(function() {
				var suitHeader = $(this);
                $(suitHeader).next().slideUp();
                $('.status', suitHeader).addClass('close');
                
				$('.suit-header-block', this).toggle(
					function() {
					   $(suitHeader).next().slideDown();
					   $('.status', this).removeClass('close');	
					},
					function() {
					   $(suitHeader).next().slideUp();
					   $('.status', this).addClass('close');
						
					}
				);
                
			});
            $('.photo-gallery a').lightBox({
                {/literal}
                imageLoading: '{$server}{$site_root}{$template_root}/images/lightbox/lightbox-ico-loading.gif', // (string) Path and the name of the loading icon
                imageBtnPrev: '{$server}{$site_root}{$template_root}/images/lightbox/lightbox-btn-prev.gif', // (string) Path and the name of the prev button image
                imageBtnNext: '{$server}{$site_root}{$template_root}/images/lightbox/lightbox-btn-next.gif', // (string) Path and the name of the next button image
                imageBtnClose: '{$server}{$site_root}{$template_root}/images/lightbox/lightbox-btn-close.gif', // (string) Path and the name of the close btn
                imageBlank: '{$server}{$site_root}{$template_root}/images/lightbox/lightbox-blank.gif', // (string) Path and the n
                {literal}
            });
		});
{/literal}
</script>
	<script>
	//right vars values from lightbox.js
	var fileLoadingImage = "{$server}{$site_root}{$template_root}/images/lightbox/loading.gif";
	var fileBottomNavCloseImage = "{$server}{$site_root}{$template_root}/images/lightbox/closelabel.gif";

	</script>
    
	
	
<div id="middle-container">


<h2 style="height:24px;"><span style="float:left;font-size:20px">{$profile.headline_short} {$profile.country_name},{$profile.region_name},{$profile.city_name}</span><div style="float:right;">ID {$profile.id}</div></h2>

<div id="middle-holder">
<center>
    <img src="{$profile.photo_file[0]}" alt="{$profile.headline_short}" class="img-border" style="max-height:350px;max-width:700px" />
</center>
{if $profile.comment}
	<h3>{$lang.content.add_comments}</h3>
    <p>
    	{$profile.comment}
    </p>
{/if}

<h3>Фото-обзор варианта</h3>
<div class="photo-gallery">
    {section name=ph loop=$profile.photo_id}
	<div class="item">{if $profile.photo_view_link[ph]}<a href="{$profile.photo_file[ph]}" rel="lightbox[profile_photo_main]" title="{$profile.photo_user_comment[ph]}">{/if}<img src='{$profile.photo_thumb_file[ph]}' class='img-border' alt="{$profile.photo_user_comment[ph]}">{if $profile.photo_view_link[ph]}</a>{/if}	</div>
    {/section}
</div>

{*
<div class="parent_desc">
    {if $profile.min_payment_show}
    <div class="row">
          <h3>Стоимость</h3>
          <span class="desc">{$profile.min_payment_show}</span>
    </div>
    {/if}
    {if $profile.payment_not_season}
    <div class="row">
          <h3>Стоимость вне сезона</h3>
          <span class="desc">{$profile.payment_not_season} {$cur}</span>
    </div>
    {/if}
    {foreach from=$profile.description item=desc}
    <div class="row">
      <h3>{$desc.name}:&nbsp;</h3>
      <span class="desc">{foreach from=$desc.fields name=desc_f item=d_f}{$d_f} {/foreach}</span>
    </div>
    {/foreach}
    {foreach from=$profile.theme_rest item=desc}
    <div class="row">
      <h3>{$desc.name}</h3>
      <p></p>{foreach from=$desc.fields name=theme_rest_loop item=d_f}{$d_f}{if !$smarty.foreach.theme_rest_loop.last}, {/if}{/foreach}</p>
    </div>
    {/foreach}
    {foreach from=$profile.info item=info}
    <div class="row">
      <h3>{$info.name}:&nbsp;</h3>
      <span class="desc">{foreach from=$info.fields name=info_loop item=d_f}{$d_f}{if !$smarty.foreach.info_loop.last}, {/if}{/foreach}</span>
    </div>
    {/foreach}
    {foreach from=$profile.realty_type item=info}
    <div class="row">
      <h3>{$info.name}:&nbsp;</h3>
      <span class="desc">{foreach from=$info.fields name=info_loop item=d_f}{$d_f}{if !$smarty.foreach.info_loop.last}, {/if}{/foreach}</span>
    </div>
    {/foreach}
    {if $profile.floor}
    <div class="row">
      <h3>Этаж:&nbsp;</h3>
      <span class="desc">{$profile.floor}</span>
    </div>
    {/if}
    {if $profile.floors}
    <div class="row">
      <h3>Всего этажей:&nbsp;</h3>
      <span class="desc">{$profile.floors}</span>
    </div>
    {/if}
    {if $profile.ceil_height}
    <div class="row">
      <h3>Высота потолков:&nbsp;</h3>
      <span class="desc">{$profile.ceil_height}</span>
    </div>
    {/if}
    {if $profile.sea_distance}
    <div class="row">
      <h3>Расстояние до моря:&nbsp;</h3>
      <span class="desc">{$profile.sea_distance}</span>
    </div>
    {/if}
    {if $profile.term}
    <div class="row">
      <h3>Срок сдачи:&nbsp;</h3>
      <span class="desc">{$profile.term}</span>
    </div>
    {/if}
    {if $profile.investor}
    <div class="row">
      <h3>Инвестор:&nbsp;</h3>
      <span class="desc">{$profile.investor}</span>
    </div>
    {/if}
    {if $profile.parking}
    <div class="row">
      <h3>Паркинг:&nbsp;</h3>
      <span class="desc">{$profile.parking}</span>
    </div>
    {/if}
    {if $profile.total_square}
    <div class="row">
      <h3>Общая площадь:&nbsp;</h3>
      <span class="desc">{$profile.total_square}</span>
    </div>
    {/if}
    <div class="row">
      <h3>Кол-во просмотров:&nbsp;</h3>
      <span class="desc">{$profile.visits}</span>
    </div>
    <div class="row">
      <h3>Дата публикации:&nbsp;</h3>
      <span class="desc">{$profile.movedate}</span>
    </div>
    <div class="row">
      <h3>Тур-оператор:&nbsp;</h3>
      <span class="desc">{$profile.account.company_name}</span>
    </div>
    <div class="row">
      <h3>Уникальный идентификатор объекта (ID):&nbsp;</h3>
      <span class="desc">{$profile.id}</span>
    </div>
</div>
*}
{foreach from=$profile.childs item=child key=k}
<div class="suit">
  <div class="suit-header">

  	<div class="suit-header-block">
    	<div class="clearfix">
      	<a class="status"></a>
        <span class="s_id">{$child.headline}</span>
        <span class="s_name">от {$child.min_payment} {$cur}</span>
      </div>
    </div>
    <div class="s_preview">

      <div class="photo-gallery">
        {section name=ph_ch loop=$child.photo_id}
            {if $smarty.section.ph_ch.index < 4}
        	<div class="item">{if $child.photo_view_link[ph_ch]}<a href="{$child.photo_file[ph_ch]}" rel="lightbox[profile_photo]" title="{$child.photo_user_comment[ph_ch]}">{/if}<img src='{$child.photo_thumb_file[ph_ch]}' class='img-border' alt="{$child.photo_user_comment[ph_ch]}">{if $child.photo_view_link[ph_ch]}</a>{/if}	</div>
            {/if}
        {/section}
      </div>
    </div>
  </div>
  <div class="suit-body">
    <ul class="tabs clearfix">

      <li><a href="#">Информация</a></li>
      <li><a href="#">Фото / Видео</a></li>
      <li><a href="#">На карте</a></li>
      <li><a href="#">3D-Тур</a></li>
      <li><a href="#">Бронь</a></li>
    </ul>

    <div class="glass-block suit-description panes">
    	<div>
        {foreach from=$child.description item=desc}
        <div class="row">
          <label class="title">{$desc.name}:&nbsp;</label>
          <span class="desc">{foreach from=$desc.fields name=desc_f item=d_f}{$d_f} {/foreach}</span>
        </div>
        {/foreach}
        {foreach from=$child.theme_rest item=desc}
        <div class="row">
          <label class="title">{$desc.name}:&nbsp;</label>
          <span class="desc">{foreach from=$desc.fields name=theme_rest_loop item=d_f}{$d_f}{if !$smarty.foreach.theme_rest_loop.last}, {/if}{/foreach}</span>
        </div>
        {/foreach}
        {foreach from=$child.info item=info}
        <div class="row">
            {if $info.name eq 'Удобства'}
              <label class="title">Удобства:&nbsp;</label>
              <div class="icons-holder">
              {foreach from=$info.fields name=child_info_loop item=d_f}
              {if $d_f eq 'Ванная' or $d_f eq 'Ванная комната в номере'}
                <a class="icon bath" title="Ванная"></a>
              {elseif $d_f eq 'Кондиционер'}  
                <a class="icon condition" title="Кондиционер"></a>
              {elseif $d_f eq 'ТВ' or $d_f eq 'Телевизор'} 
                <a class="icon tv" title="ТВ"></a>
              {elseif $d_f eq 'Холодильник'} 
                <a class="icon fridge" title="Холодильник"></a>
              {elseif $d_f eq 'Wi-Fi' or $d_f eq 'Интернет'} 
                <a class="icon wifi" title="Wi-Fi"></a>
              {elseif $d_f eq 'Снег'} 
                <a class="icon ice" title="Снег"></a>
              {elseif $d_f eq 'Баня'} 
                <a class="icon bana" title="Баня"></a>
              {elseif $d_f eq 'Питание' or $d_f eq 'Ресторан'} 
                <a class="icon eat" title="Питание"></a>
              {elseif $d_f eq 'Бассейн'} 
                <a class="icon pool" title="Бассейн"></a>
              {elseif $d_f eq 'Карта'} 
                <a class="icon map" title="Карта"></a>
              {elseif $d_f eq 'Парковка'} 
                <a class="icon parking" title="Парковка"></a>
              {else}
                нет иконки для {$d_f}
              {/if}
              {/foreach}
                
              </div>
            {else}
            <label class="title">{$info.name}:&nbsp;</label>
            <span class="desc">{foreach from=$info.fields name=child_info_loop item=d_f}{$d_f}{if !$smarty.foreach.child_info_loop.last}, {/if}{/foreach}</span>
            {/if}
        </div>
        {/foreach}
        
        <div class="row">
          
        </div>
        {if $child.hotel}
        <div class="row">
          <label class="title">Отель:&nbsp;</label>
          <span class="desc">{$child.hotel}</span>
        </div>
        {/if}
        {if $child.days}
        <div class="row">
          <label class="title">Количество дней:&nbsp;</label>
          <span class="desc">{$child.days}</span>
        </div>
        {/if}
        {if $child.facilities}
        <div class="row">
          <label class="title">Другие услуги:&nbsp;</label>
          <span class="desc">{$child.facilities}</span>
        </div>
        {/if}
        {if $child.meals}
        <div class="row">
          <label class="title">Питание:&nbsp;</label>
          <span class="desc">{$child.meals}</span>
        </div>
        {/if}
        {if $child.route}
        <div class="row">
          <label class="title">Точки маршрута:&nbsp;</label>
          <span class="desc">{$child.route}</span>
        </div>
        {/if}
        
        {if $child.floor}
        <div class="row">
          <label class="title">Этаж:&nbsp;</label>
          <span class="desc">{$child.floor}</span>
        </div>
        {/if}
        {if $child.floors}
        <div class="row">
          <label class="title">Всего этажей:&nbsp;</label>
          <span class="desc">{$child.floors}</span>
        </div>
        {/if}
        {if $child.ceil_height}
        <div class="row">
          <label class="title">Высота потолков:&nbsp;</label>
          <span class="desc">{$child.ceil_height}</span>
        </div>
        {/if}
        
        {if $child.sea_distance}
        <div class="row">
          <label class="title">Расстояние до моря:&nbsp;</label>
          <span class="desc">{$child.sea_distance}</span>
        </div>
        {/if}
        {if $child.term}
        <div class="row">
          <label class="title">Срок сдачи:&nbsp;</label>
          <span class="desc">{$child.term}</span>
        </div>
        {/if}
        {if $child.investor}
        <div class="row">
          <label class="title">Инвестор:&nbsp;</label>
          <span class="desc">{$child.investor}</span>
        </div>
        {/if}
        {if $child.parking}
        <div class="row">
          <label class="title">Паркинг:&nbsp;</label>
          <span class="desc">{$child.parking}</span>
        </div>
        {/if}
        {if $child.total_square}
        <div class="row">
          <label class="title">Общая площадь:&nbsp;</label>
          <span class="desc">{$child.total_square}</span>
        </div>
        {/if}
        <div class="row">

          <label class="title">Стоимость {if $child.type == 4}проживания{/if} ({$cur}):&nbsp;</label>
          {if $child.type == 1}
          <span class="desc">{$child.min_payment_show}</span>
          
          <table cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <td>Январь</td>
                <td>Февраль</td>

                <td>Март</td>
                <td>Апрель</td>
                <td>Май</td>
                <td>Июнь</td>
                <td>Июль</td>
                <td>Август</td>
                <td>Сентябрь</td>
                <td>Октябрь</td>
                <td>Ноябрь</td>
                <td>Декабрь</td>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td>{$child.prices.january} </td>
                <td>{$child.prices.february} </td>
                <td>{$child.prices.march} </td>
                <td>{$child.prices.april} </td>
                <td>{$child.prices.may} </td>
                <td>{$child.prices.june} </td>
                <td>{$child.prices.july} </td>
                <td>{$child.prices.august} </td>
                <td>{$child.prices.september} </td>
                <td>{$child.prices.october} </td>
                <td>{$child.prices.november} </td>
                <td>{$child.prices.december} </td>
              </tr>
            </tbody>
          </table>
          
          {else}
          <span class="desc">{$child.min_payment_show} </span>
          {/if}
        </div>
        {if $child.payment_not_season}
        <div class="row">
              <label class="title">Стоимость вне сезона: </label>
              <span class="desc">{$child.payment_not_season} {$cur}</span>
        </div>
        {/if}
        {if $child.furniture}
        <div class="row">
          <label class="title">Мебель:&nbsp;</label>
          <span class="desc">{$child.furniture}</span>

        </div>
        {/if}
        {if $child.comment}
        <div class="row">
          <label class="title">Информация:&nbsp;</label>
          <span class="desc">{$child.comment}</span>

        </div>
        {/if}
        <!--
        <div class="row">
          <label class="title">Фотографии номера:&nbsp;</label>
          <a class="all">Посмотреть все</a>
        </div>
        -->
      </div>
      <div>
      	    <div class="photo-gallery">
            {section name=ph_gal loop=$child.photo_id}
                {if $smarty.section.ph_gal.index > 3}
            	<div class="item">{if $child.photo_view_link[ph_gal]}<a href="{$child.photo_file[ph_gal]}" rel="lightbox[profile_photo]" title="{$child.photo_user_comment[ph_gal]}">{/if}<img src='{$child.photo_thumb_file[ph_gal]}' class='img-border' alt="{$child.photo_user_comment[ph_gal]}">{if $child.photo_view_link[ph_gal]}</a>{/if}	</div>
                {/if}
            {/section}
      </div>    
      </div>

      <div>
      	<div>
            <img src="http://maps.google.com/maps/api/staticmap?zoom=11&markers=color:green|label:L|{$child.city_name},{$child.country_name}&size=490x382&maptype=roadmap&sensor=false" border="0" alt="Map" width="490" height="382" id="map" />  
        </div>
      </div>
      <div>
      	asdasd
      </div>
      <div>
      	123fdhfgh123
      </div>
    </div>

  </div>
</div>
{/foreach}
</div>

{* //old

{if $view eq 'photo'}
	<script>
	//right vars values from lightbox.js
	var fileLoadingImage = "{$server}{$site_root}{$template_root}/images/lightbox/loading.gif";
	var fileBottomNavCloseImage = "{$server}{$site_root}{$template_root}/images/lightbox/closelabel.gif";

	</script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/scriptaculous.js?load=effects"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/lightbox.js"></script>
{/if}

<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $banner.center}
		<tr>
			<td>
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				 <!-- /banner center -->
			</td>
		</tr>
		{/if}
		<tr>
			<td class="header"><b>{$lang.headers.viewprofile}</b></td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td><hr></td></tr>
		</table>
		{if $sect ne 'more_list'}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: 5px;">
		<tr>
			<td width="15">&nbsp;</td>
			{*<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
				<img src="{$profile.photo_thumb_file[0]}" alt="{$profile.photo_alt[0]}" style="border: none" alt="" >
			</td>* }
			<td height="{$thumb_big_height+10}" width="{$thumb_big_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
				<img src="{if $profile.photo_thumb_big_file[0]}{$profile.photo_thumb_big_file[0]}{else}{$profile.photo_thumb_file[0]}{/if}" alt="{$profile.photo_alt[0]}" title="{$profile.photo_alt[0]}" style="border: none">
			</td>
			<td valign="top" style="padding-left: 12px;" rowspan="2">
			{strip}
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="padding-bottom: 5px;">
						<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td valign="middle"><font class="vp_name">{$profile.account.fname}</font>, {$lang.default_select[$profile.type_name]} {$profile.realty_type_in_line}</strong>
							</td>
						</tr>
						{if $profile.country_name_t || $profile.region_name_t || $profile.city_name_t}
						<tr>
							<td>{$profile.country_name_t}{if $profile.region_name_t},&nbsp;{$profile.region_name_t}{/if}{if $profile.city_name_t},&nbsp;{$profile.city_name_t}{/if}</td>
						</tr>
						{/if}
						
						<tr>
							<td>
								{if $profile.type eq 1 || $profile.type eq 2}{$lang.content.month_payment_inline}
								{else}{$lang.content.price}{/if}:&nbsp;
								<font class="vp_price">
								{if $profile.type eq 2 || $profile.type eq 4}{$profile.min_payment_show}
								{else}{$profile.min_payment_show}-{$profile.max_payment_show}{/if}
								</font>						
								{if $profile.movedate}														
								{if $profile.type eq 1 || $profile.type eq 3}{$lang.content.move_in_date}
								{else}{$lang.content.available_date}{/if}:&nbsp;<strong>{$profile.movedate}</strong>
								{*
								{if $profile.type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$profile.id}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}{/if}
								* }					
								{/if}		
							</td>
						</tr>								
						{if $profile.headline_short}
						<tr>
							<td>{$profile.headline_short}</td>
						</tr>
						{/if}
						{*
						{if $profile.reserve.is_reserved}
						<tr>
							<td>					
								{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$profile.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$profile.reserve.reserved_end_period}</strong>
								{if $profile.type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$profile.id}&view=calendar'>{$lang.default_select.other_period}</a>{/if}
							</td>
						</tr>
						{/if}
						* }
						</table>
					</td>
					<td valign="top" align="right">						
						<table cellpadding="0" cellspacing="0">
							<tr>
							{if !$mhi_services}
								{if $profile.show_topsearch_icon}<td><img alt="{$lang.default_select.star_alt} {$profile.topsearch_date_begin}" title="{$lang.default_select.star_alt} {$profile.topsearch_date_begin}" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1" vspace="0"></td>{/if}
								{if $profile.slideshowed eq 1}<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>{/if}
								{if $profile.featured eq 1}<td><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}"  align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>{/if}
							{/if}
								{if $profile.issponsor}
								<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
								{/if}
								{if $use_sold_leased_status}
									{if $profile.sold_leased_status && ($profile.type eq '2' || $profile.type eq '4')}
									<td><img alt="{if $profile.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $profile.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $profile.type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
									{/if}
								{/if}
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="vp_contacts">
						<table cellpadding="3" cellspacing="0" border="0" width="100%">					
							{if $profile.account.phone && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
							<tr>
								<td>{$lang.default_select.call_him}: {$profile.account.phone}</td>
							</tr>
							{elseif ($group_type ne 1 && $registered eq 1)}
							<tr>
								<td>{$lang.default_select.group_err_1}<a href="services.php?sel=group">{$lang.default_select.group_err_2}</a>{$lang.default_select.group_err_3}</td>
							</tr>
							{elseif ($registered eq 0)}
							<tr id="mhi_registration" style="display: {$mhi_registration}">
								<td>{$lang.content.unregistered_group_err} <a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}', 230, 400)">{$lang.content.reg_users}</a>
								</td>
							</tr>
							{/if}						
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					{strip}
						<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: 10px;" class="vp_links">			
							<tr>	
							{assign var=links_cnt value=0}						
								{if $user.0 != $profile.id_user}									
										{if ($mhi_my_messages && !$mhi_registration && $registered eq 1)
										 || ($mhi_registration && $registered eq 0)
										 || ($mhi_registration && $mhi_my_messages)
										}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_contact.gif">
											</td>
											<td>
											<a href="#" onclick='javascript: window.open("contact.php?sel=contact_user&id_user={$profile.id_user}&id_ad={$profile.id}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=540, height=500, toolbar=no, menubar=no, left=0,top=0");'>{$lang.content.contact_with} {$profile.account.fname}</a>
											</td>
										{else}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_contact.gif">
											</td>
											<td>									
											{if $registered eq 1}<a href="#" onclick='javascript: window.open("mailbox.php?sel=chat_start&user_id={$profile.id_user}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=650, height=768, toolbar=no, menubar=no, left=0,top=0");' >{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.mail_link}&amp;mail=yes', 230, 400)">{/if}{$lang.content.contact_user}</a>
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}
										{if !$mhi_addfriend_link && $profile.addfriend_link}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_hotlist.png">
											</td>
											<td>
											{if $registered eq 1}<a href="{$profile.addfriend_link}">{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.addfriend_link}&amp;view=yes', 230, 400)">{/if}{$lang.content.add_to_hotlist}</a>
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}	
										{if !$mhi_blacklist_link && $profile.blacklist_link}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_blacklist.gif">
											</td>
											<td>										
											{if $registered eq 1}<a href="{$profile.blacklist_link}">{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.blacklist_link}&amp;view=yes', 230, 400)">{/if}{$lang.content.add_to_blacklist}</a>
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}	
										{if !$mhi_interest_link}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_interest.png">
											</td>
											<td>										
											{if $profile.interested ne 1}
												{if $registered eq 1}
													<a href="{$profile.interest_link}">
												{elseif $registered eq	0}
													<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.interest_link}&amp;view=yes', 230, 400)">
												{/if}
												{$lang.content.interest}</a>
											{else}
												<b>{$lang.content.interested}</b>
											{/if}
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}	
									{*
										{if !$mhi_my_messages && !($mhi_registration && $registered eq 0)}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_contact.gif">
											</td>
											<td>									
											{if $registered eq 1}<a href="#" onclick='javascript: window.open("mailbox.php?sel=chat_start&user_id={$profile.id_user}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=650, height=768, toolbar=no, menubar=no, left=0,top=0");' >{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.mail_link}&amp;mail=yes', 230, 400)">{/if}{$lang.content.contact_user}</a>
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}	
									* }
										{if !$mhi_complain_link}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_complain.png">
											</td>
											<td>										
											<noindex>{if $registered eq 1}<a href="{$profile.contact_link}"  target="_blank">{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.contact_link}&amp;contact=yes', 230, 400)">{/if}{$lang.content.contact_admin}</a></noindex>
											</td>
										{/if}
									{if $links_cnt is div by 2}</tr><tr>{/if}													
										{if $profile.more_link}
										{assign var=links_cnt value=$links_cnt+1}
											<td>
												<img src="{$site_root}{$template_root}{$template_images_root}/vp_all_listings.gif">
											</td>
											<td>		
											{if !$mhi_registration}								
												{if $registered eq 1}<a href="{$profile.more_link}">{elseif $registered eq	0}<a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}&qw={$profile.more_link}&view=yes', 230, 400)">{/if}{$lang.content.more_ad}</a>					
											{else}
												<a href="{$profile.more_link}">{$lang.content.more_ad_from} {$profile.account.fname}</a>					
											{/if}					
											</td>
										{/if}
																				
								{/if}
								{if $links_cnt is div by 2}</tr><tr>{/if}	
									{assign var=links_cnt value=$links_cnt+1}
									<td class="img">
										<img src="{$site_root}{$template_root}{$template_images_root}/vp_compare.png">
									</td>
									<td class="link">										
									<span id="listing_add_to_comparison_{$profile.id}">
										<a href="#" onclick="javascript: AddToComparisonList('{$profile.id}', 'listing_add_to_comparison_{$profile.id}');">{$lang.default_select.add_to_comparison_list}</a>
									</span>								
									</td>
								{if $links_cnt is div by 2}</tr><tr>{/if}	
									{assign var=links_cnt value=$links_cnt+1}
									<td class="img">
										<img src="{$site_root}{$template_root}{$template_images_root}/vp_print.png">
									</td>
									<td class="link">
									<a href="{$profile.print_link}" target="_blank">{$lang.content.print_profile}</a>
									</td>							
							</tr>
						</table>		
						{/strip}			
					</td>
				</tr>
				</table>
				{/strip}
			</td>			
		</tr>
		{if $links_cnt >= 3}
		<tr><td {if $links_cnt > 3 && $profile.headline_short}height="40"{/if}>&nbsp;</td></tr>		
		{/if}
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: {if $links_cnt >= 3}10{else}20{/if}px;">
		<tr>
			<td>
			{if $sect eq 'rent'}
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 0px; padding:0px;">
				<tr>
					<td align="left">
						<table cellpadding="0" cellspacing="0" border="0" style="margin: 0px; padding:0px;">
							<tr>
								<td width="15">&nbsp;</td>
								{if $view eq 'general'}<td><img src="{$site_root}{$template_root}{$template_images_root}/menu/menu_left_on.gif"></td>{else}<td><img src="{$site_root}{$template_root}{$template_images_root}/menu/menu_left.gif"></td>{/if}
								{section name=m loop=$menu_sections}
									<td {if $view eq $menu_sections[m]} bgcolor="#f2f2f2" {else} bgcolor="#adb0b5" {/if}  style="padding-left: 15px; padding-right: 15px;">{if $view eq $menu_sections[m]}<b>{/if}<a href="./viewprofile.php{$suffix_2}&view={$menu_sections[m]}" {if $view eq $menu_sections[m]} style="color: #656565; text-decoration: none;" {else} style="color: #ffffff;" {/if}>{if $menu_sections[m] eq 'general'}{$lang.content.general}{elseif $menu_sections[m] eq 'photo'}{$lang.content.photo}{elseif $menu_sections[m] eq 'video'}{$lang.content.video}{elseif $menu_sections[m] eq 'map'}{$lang.content.map}{elseif $menu_sections[m] eq 'calendar'}{$lang.content.calendar}{/if}</a>{if $view eq $menu_sections[m]}</b>{/if}</td>
									{if !($smarty.section.m.last)}
									<td><img {if $view eq $menu_sections[m]} src="{$site_root}{$template_root}{$template_images_root}/menu/menu_del_2.gif" {elseif $view eq $menu_sections[m.index_next]} src="{$site_root}{$template_root}{$template_images_root}/menu/menu_del_1.gif" {else} src="{$site_root}{$template_root}{$template_images_root}/menu/menu_del_3.gif" {/if}></td>
									{else}
									<td><img {if $view eq $menu_sections[m]} src="{$site_root}{$template_root}{$template_images_root}/menu/menu_right_on_last.gif" {else} src="{$site_root}{$template_root}{$template_images_root}/menu/menu_right_last.gif"{/if}></td>
									{/if}
								{/section}
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" bgcolor="#f2f2f2" width="100%" height="350">
				<tr>
					<td width="20" valign="top">&nbsp;</td>
					<td valign="top" style="padding-top: 20px;">
					<table cellpadding="7" cellspacing="0" border="0" width="100%">
					{if $view eq 'general'}

					<!-- about_realty -->
						<tr>
							<td colspan="2">{$lang.content.about_realty}</td>
						</tr>
						{if $profile.headline}						
						<tr>
							<td width="170"><b>{$lang.content.headline}:&nbsp;</b></td>
							<td>{$profile.headline}</td>
						</tr>
						{/if}
						<tr>
							<td width="170"><b>{$lang.content.location}:&nbsp;</b></td>
							<td>{$profile.country_name_t}{if $profile.region_name_t},&nbsp;&nbsp;{$profile.region_name_t}{/if}{if $profile.city_name_t},&nbsp;&nbsp;{$profile.city_name_t}{/if}</td>
						</tr>
						<!--{if $profile.subway_name}
						<tr>
							<td><b>{$lang.content.subway_name}:&nbsp;</b></td>
							<td>{$profile.subway_name}</td>
						</tr>
						{/if}-->
						{if $profile.type eq '2' || $profile.type eq '4'}
						<!-- have/sell realty-->
							{if $profile.zip_code_t}
							<tr>
								<td><b>{$lang.content.zipcode}:&nbsp;</b></td>
								<td>{$profile.zip_code_t}</td>
							</tr>
							{/if}
							{if $profile.street_1 && $profile.street_2}
							<tr>
								<td><b>{$lang.content.cross_streets}:&nbsp;</b></td>
								<td>{$profile.street_1}&nbsp;&&nbsp;{$profile.street_2}</td>
							</tr>
							{/if}
							{if $profile.adress_t}
							<tr>
								<td><b>{$lang.content.adress}:&nbsp;</b></td>
								<td>{$profile.adress_t}</td>
							</tr>
							{/if}
							<tr>
								<td><b>{if $profile.type eq 2}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;</b></td>
								<td>{$profile.min_payment_show},&nbsp;{if $profile.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}</td>
							</tr>
							{if $profile.type eq 2}
								<!-- period -->
								{section name=b loop=$profile.period}
									<tr>
										<td><b>{$profile.period[b].name}:&nbsp;</b></td>
										<td>{section name=c loop=$profile.period[b].fields}{$profile.period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
										</td>
									</tr>
								{/section}
								<!-- /period -->
							{/if}
							{if $profile.min_deposit > 0}
							<tr>
								<td><b>{$lang.content.deposit}:&nbsp;</b></td>
								<td>{$profile.min_deposit}&nbsp;{$cur}</td>
							</tr>
							{/if}
							{if $profile.movedate}
							<tr>
								<td><b>{$lang.content.available_date}:&nbsp;</b></td>
								<td>{$profile.movedate}
								{if $profile.type == '2'}<a href="{$file_name}?id={$profile.id}&view=calendar">{$lang.content.view_calendar}</a>{/if}</td>
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
							{if $profile.min_floor}
							<tr>
								<td><b>{$lang.content.property_floor_number}:&nbsp;</b></td>
								<td>{$profile.min_floor}&nbsp;{$lang.content.floor}</td>
							</tr>
							{/if}
							{if $profile.floor_num}
							<tr>
								<td><b>{$lang.content.total_floor_num}:&nbsp;</b></td>
								<td>{$profile.floor_num}&nbsp;{$lang.content.of_floors}</td>
							</tr>
							{/if}
							{if $profile.subway_min > 0}
							<tr>
								<td><b>{$lang.content.subway_min}:&nbsp;</b></td>
								<td>{$profile.subway_min}&nbsp;{$lang.content.minutes}</td>
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
						{else}
							<tr>
								<td><b>{if $profile.type eq 1}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;</b></td>
								<td>{$lang.content.from}&nbsp;{$profile.min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_payment_show},&nbsp;{if $profile.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}</td>
							</tr>
							{if $profile.type eq 2}
								<!-- period -->
								{section name=b loop=$profile.period}
									<tr>
										<td><b>{$profile.period[b].name}:&nbsp;</b></td>
										<td>{section name=c loop=$profile.period[b].fields}{$profile.period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
										</td>
									</tr>
								{/section}
								<!-- /period -->
							{/if}
							{if $profile.min_deposit > 0 || $profile.max_deposit > 0}
							<tr>
								<td><b>{$lang.content.deposit}:</b></td>
								<td>{if $profile.min_deposit > 0}{$lang.content.from}&nbsp;{$profile.min_deposit}&nbsp;{/if}
									{if $profile.max_deposit > 0}{$lang.content.upto}&nbsp;{$profile.max_deposit}&nbsp;{/if}{$cur}</td>
							</tr>
							{/if}
							{if $profile.movedate}
							<tr>
								<td width="200"><b>{$lang.content.move_date}:&nbsp;</b></td>
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
							{if $profile.min_year_build > 0 || $profile.max_year_build > 0}
							<tr>
								<td><b>{$lang.content.year_build}:</b></td>
								<td>{if $profile.min_year_build > 0}{$lang.content.from_1}&nbsp;{$profile.min_year_build}&nbsp;{/if}
									{if $profile.max_year_build > 0}{$lang.content.upto_1}&nbsp;{$profile.max_year_build}&nbsp;{/if}</td>
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
							{if $profile.min_live_square > 0 || $profile.max_live_square > 0}
							<tr>
								<td><b>{$lang.content.live_square}:&nbsp;</b></td>
								<td>{if $profile.min_live_square > 0}{$lang.content.from}&nbsp;{$profile.min_live_square}&nbsp;{/if}
									{if $profile.max_live_square > 0}{$lang.content.upto}&nbsp;{$profile.max_live_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $profile.min_total_square > 0 || $profile.max_total_square > 0}
							<tr>
								<td><b>{$lang.content.total_square}:&nbsp;</b></td>
								<td>{if $profile.min_total_square > 0}{$lang.content.from}&nbsp;{$profile.min_total_square}&nbsp;{/if}
									{if $profile.max_total_square > 0}{$lang.content.upto}&nbsp;{$profile.max_total_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $profile.min_land_square > 0 || $profile.max_land_square > 0}
							<tr>
								<td><b>{$lang.content.land_square}:&nbsp;</b></td>
								<td>{if $profile.min_land_square > 0}{$lang.content.from}&nbsp;{$profile.min_land_square}&nbsp;{/if}
									{if $profile.max_land_square > 0}{$lang.content.upto}&nbsp;{$profile.max_land_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $profile.min_floor > 0 || $profile.max_floor > 0}
							<tr>
								<td><b>{$lang.content.floor_variants}:&nbsp;</b></td>
								<td>{if $profile.min_floor > 0}{$lang.content.from}&nbsp;{$profile.min_floor}&nbsp;{/if}
									{if $profile.max_floor > 0}{$lang.content.upto}&nbsp;{$profile.max_floor}&nbsp;{/if}{$lang.content.floor}</td>
							</tr>
							{/if}
							{if $profile.floor_num}
							<tr>
								<td><b>{$lang.content.floor_num_max_limitation}:&nbsp;</b></td>
								<td>{$profile.floor_num}&nbsp;{$lang.content.of_floors}</td>
							</tr>
							{/if}
							{if $profile.subway_min > 0}
							<tr>
								<td><b>{$lang.content.subway_min}:&nbsp;</b></td>
								<td>{$profile.subway_min}&nbsp;{$lang.content.minutes}</td>
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
							{if $profile.with_photo eq '1'}
							<tr>
								<td><b>{$lang.content.photo_exist}:&nbsp;</b></td>
								<td>{$lang.content.only_with}</td>
							</tr>
							{/if}
							{if $profile.with_video eq '1'}
							<tr>
								<td><b>{$lang.content.video_exist}:&nbsp;</b></td>
								<td>{$lang.content.only_with}</td>
							</tr>
							{/if}
						{/if}

				{if ($profile.type eq 2 || $profile.type eq 4)}
					<!--look for-->
					<tr>
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td colspan="2">{$lang.content.look_for} ({if $profile.type eq 2}{$lang.content.about_leaser}{else if $profile.type eq 4}{$lang.content.about_buyer}{/if})</td>
					</tr>
					{if $profile.his_age_1}
						<tr>
							<td><b>{$lang.content.age}:&nbsp;</b></td>
							<td>{$profile.his_age_1}{if $profile.his_age_2>0}-{$profile.his_age_2}{/if}</td>
						</tr>
						{if $profile.gender_match}
						{section name=b loop=$profile.gender_match}
						<tr>
							<td><b>{$profile.gender_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$profile.gender_match[b].fields}{$profile.gender_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
						{if $profile.people_match}
						{section name=b loop=$profile.people_match}
						<tr>
							<td><b>{$profile.people_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$profile.people_match[b].fields}{$profile.people_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
						{if $profile.language_match}
						{section name=b loop=$profile.language_match}
						<tr>
							<td><b>{$profile.language_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$profile.language_match[b].fields}{$profile.language_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
					{else}
						<tr>
							<td>{$lang.content.not_filled}</td>
						</tr>
					{/if}
				{/if}

					<!--comment-->
					{if $profile.comment}
						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td width="170">{$lang.content.add_comments}:&nbsp;</td>
							<td>{$profile.comment}</td>
						</tr>
					{/if}

					<!--about me-->
						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td colspan="2">{if $profile.account.user_type eq 2}{$lang.content.account_about_us}{else}{$lang.content.account_about_me}{/if}</td>
						</tr>
						<tr>
							<td width="170"><b>{$lang.content.fname}:</b></td>
							<td>{$profile.account.fname}</td>
						</tr>
						<tr>
							<td><b>{$lang.content.sname}:</b></td>
							<td>{$profile.account.sname}</td>
						</tr>
						{if $profile.account.user_type eq 3 && $profile.company_data.photo_path != ''}
						<tr>
							<td><b>{$lang.content.photo}:</b></td>
							<td>{if $profile.company_data.photo_path !='' && $profile.company_data.photo_admin_approve==1}<img src="{$profile.company_data.photo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{else}<font class="error">{$lang.content.no_photos}</font>{/if}</td>
						</tr>
						{/if}	
						<!--<tr>
							<td><b>{$lang.content.email}:</b></td>
							<td>{$profile.account.email}</td>
						</tr>-->
					{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) }
						{if $profile.account.phone}
						<tr>
							<td><b>{$lang.content.phone}:</b></td>
							<td>{$profile.account.phone}</td>
						</tr>
						{/if}
					{/if}
						{if $profile.account.user_type eq 2}
							{* realtor * }
								<tr>
									<td><b>{$lang.content.company_name}:</b></td>
									<td>{$profile.account.company_name}</td>
								</tr>
							{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
								{if $profile.account.company_url != ""}
								<tr>
									<td><b>{$lang.content.company_url}:</b></td>
									<td><a href="{$profile.account.company_url}" target="_blank">{$profile.account.company_url}</a></td>
								</tr>
								{/if}
							{/if}
								<tr>
									<td><b>{$lang.content.our_logo}:</b></td>
									<td>{if $profile.account.logo_path !='' && $profile.account.admin_approve==1}<img src="{$profile.account.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{else}<font class="error">{$lang.content.no_photos}</font>{/if}</td>
								</tr>
							{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
								<!--{if $profile.account.company_rent_count != ""}
								<tr>
									<td><b>{$lang.content.company_rent_count}:</b></td>
									<td>{$profile.account.company_rent_count}</td>
								</tr>
								{/if}
								<tr>
									<td><b>{$lang.content.company_how_know}:</b></td>
									<td>{$profile.account.company_how_know}</td>
								</tr>
								<tr>
									<td><b>{$lang.content.company_quests_comments}:</b></td>
									<td>{$profile.account.company_quests_comments}</td>
								</tr>-->
								{if (($profile.account.country_name)||($profile.account.region_name)||($profile.account.city_name)||($profile.account.address))}
								<tr>
									<td><b>{$lang.content.location}:</b></td>
									<td>
										{$profile.account.country_name}{if $profile.account.region_name}, {$profile.account.region_name}{/if}{if $profile.account.city_name}, {$profile.account.city_name}{/if}{if $profile.account.address}, {$profile.account.address}{/if}
									</td>
								</tr>
								{/if}

								{if $profile.account.postal_code}
								<tr>
									<td><b>{$lang.content.zipcode}:</b></td>
									<td>
										{$profile.account.postal_code}
									</td>
								</tr>
								{/if}
								{if $profile.account.country_name && $profile.in_base && $use_maps_in_viewprofile}
								<tr>
									<td colspan="2" style="padding-top: 10px; padding-bottom: 10px;">
										<div id="map_container" {if $map.name == "mapquest"} style="width: 550px; height: 550px;" {elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
									</td>
								</tr>
								{/if}
								{if $profile.account.weekday}
								<tr>
									<td><b>{$lang.content.work_days}:</b></td>
									<td>
									{foreach name=week key=key item=item from=$week}
										{if $profile.account.weekday.$key eq $item.id }{$item.name}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
								{if $profile.account.work_time_begin > 0 || $profile.account.work_time_end > 0}
								<tr>
									<td><b>{$lang.content.work_time}:</b></td>
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
									<td><b>{$lang.content.lunch_time}:</b></td>
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
							{/if}

						{elseif $profile.account.user_type eq 1}
							{* private person * }
							{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) }
								{if $profile.account.birth_day != '00'}
									<tr>
										<td><b>{$lang.content.birthday}:</b></td>
										<td>{$profile.account.birth_month}.{$profile.account.birth_day}.{$profile.account.birth_year}</td>
									</tr>
								{/if}
								{section name=b loop=$profile.gender}
								<tr>
									<td><b>{$profile.gender[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$profile.gender[b].fields}{$profile.gender[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
								{/section}
								{section name=b loop=$profile.people}
								<tr>
									<td><b>{$profile.people[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$profile.people[b].fields}{$profile.people[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
								{/section}
								{section name=b loop=$profile.language}
								<tr>
									<td><b>{$profile.language[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$profile.language[b].fields}{$profile.language[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
								{/section}
							{/if}
						{elseif $profile.account.user_type eq 3}
							{* agent of company * }	
								{if $profile.company_data.company_name}		
								<tr>
									<td colspan="2">{$lang.content.about_company}</td>
								</tr>
								
																					
								<tr>
									<td><b>{$lang.content.company_name}:</b></td>
									<td>{$profile.company_data.company_name}</td>
								</tr>
								{/if}
								{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
									{if $profile.company_data.company_url != ""}
									<tr>
										<td><b>{$lang.content.company_url}:</b></td>
										<td><a href="{$profile.company_data.company_url}" target="_blank">{$profile.company_data.company_url}</a></td>
									</tr>
									{/if}
								{/if}
								{if $profile.company_data.logo_path !='' && $profile.company_data.admin_approve==1}
									<tr>
										<td><b>{$lang.content.our_logo}:</b></td>
										<td><img src="{$profile.company_data.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></td>
									</tr>
								{/if}	
							{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}		
								{if (($profile.company_data.country_name)||($profile.company_data.region_name)||($profile.company_data.city_name)||($profile.company_data.address))}
								<tr>
									<td><b>{$lang.content.location}:</b></td>
									<td>
										{$profile.company_data.country_name}{if $profile.company_data.region_name}, {$profile.company_data.region_name}{/if}{if $profile.company_data.city_name}, {$profile.company_data.city_name}{/if}{if $profile.company_data.address}, {$profile.company_data.address}{/if}
									</td>
								</tr>
								{/if}

								{if $profile.company_data.postal_code}
								<tr>
									<td><b>{$lang.content.zipcode}:</b></td>
									<td>
										{$profile.company_data.postal_code}
									</td>
								</tr>
								{/if}
								{if $profile.country_name && $profile.company_data.in_base && $use_maps_in_viewprofile}
									
									<tr>
										<td colspan="2" style="padding-top: 10px; padding-bottom: 10px;">						
											<div id="map_container" {if $map.name == "mapquest"} style="width: 550px; height: 550px;" {elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
										</td>
									</tr>
								{/if}
								{if $profile.company_data.weekday}
								<tr>
									<td><b>{$lang.content.work_days}:</b></td>
									<td>
									{foreach name=week key=key item=item from=$week}
										{if $profile.company_data.weekday.$key eq $item.id }{$item.name}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
								{if $profile.company_data.work_time_begin > 0 || $profile.company_data.work_time_end > 0}
								<tr>
									<td><b>{$lang.content.work_time}:</b></td>
									<td>{$lang.content.time_begin}&nbsp;
									{foreach item=item from=$time_arr}
										{if $profile.company_data.work_time_begin eq $item.value}{$item.value}{/if}
									{/foreach}
									&nbsp;{$lang.content.time_end}&nbsp;
									{foreach item=item from=$time_arr}
										{if $profile.company_data.work_time_end eq $item.value}{$item.value}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
								{if $profile.company_data.lunch_time_begin > 0 || $profile.company_data.lunch_time_end > 0}
								<tr>
									<td><b>{$lang.content.lunch_time}:</b></td>
									<td>{$lang.content.time_begin}&nbsp;
									{foreach item=item from=$time_arr}
										{if $profile.company_data.lunch_time_begin eq $item.value}{$item.value}{/if}
									{/foreach}
									&nbsp;{$lang.content.time_end}&nbsp;
									{foreach item=item from=$time_arr}
										{if $profile.company_data.lunch_time_end eq $item.value}{$item.value}{/if}
									{/foreach}
									</td>
								</tr>
								{/if}
							{/if}	
						{/if}
						{if $registered eq 1 && $group_type eq 0 && !$contact_for_free && !$contact_for_unreg}
							<tr>
								<td colspan="2"><font class="error">{$lang.default_select.group_err_1}<a href="services.php?sel=group">{$lang.default_select.group_err_2}</a>{$lang.default_select.group_err_3}</font></td>
							</tr>
						{/if}
						{if $registered eq 0 && !$mhi_registration && !$contact_for_unreg}
							<tr>
								<td colspan="2"><font class="error">{$lang.content.unregistered_group_err}</font> <a href="#"  onClick="return GB_show('', './login.php?lang_code={$lang_code}', 230, 400)">{$lang.content.reg_users}</a></td>
							</tr>
						{/if}
					<!-- /about me -->
					{elseif $view eq 'photo'}
						<tr>
							<td>{$lang.content.general_photo}</td>
						</tr>
						<tr>
							<td>
								{if $profile.photo_id}
								<table cellpadding="3" cellspacing="3" border="0">
									{section name=ph loop=$profile.photo_id}
									{if $smarty.section.ph.index is div by 3}<tr>{/if}
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0" align="center">
										<tr>
											<td valign="middle">
												{if $profile.photo_view_link[ph]}<a href="{$profile.photo_file[ph]}" rel="lightbox[profile_photo]" title="{$profile.photo_user_comment[ph]}">{/if}<img src='{$profile.photo_thumb_file[ph]}' class='upload_thumb' alt="{$profile.photo_user_comment[ph]}">{if $profile.photo_view_link[ph]}</a>{/if}									
											</td>
										</tr>
										<tr>
											<td>{$profile.photo_user_comment[ph]}</td>
										</tr>	
										</table>												
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
								</table>
								{else}
								<font class="error">{$lang.content.no_photos}</font>
								{/if}
							</td>
						</tr>
						{if $profile.plan_id && ($profile.type eq 2 || $profile.type eq 4)}
						<tr>
							<td>{$lang.content.planirovka}</td>
						</tr>
						<tr>
							<td>
								<table cellpadding="3" cellspacing="3" border="0">
									{section name=ph loop=$profile.plan_id}
									{if $smarty.section.ph.index is div by 3}<tr>{/if}
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
										<tr>
											<td>
												{if $profile.plan_view_link[ph]}<a href="{$profile.plan_file[ph]}" rel="lightbox[profile_plan]" title="{$profile.plan_user_comment[ph]}">{/if}<img src='{$profile.plan_thumb_file[ph]}' class='upload_thumb' alt="{$profile.plan_user_comment[ph]}">{if $profile.plan_view_link[ph]}</a>{/if}								
											</td>
										</tr>
										<tr>
											<td>{$profile.plan_user_comment[ph]}</td>
										</tr>	
										</table>												
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
								</table>
							</td>
						</tr>
						{/if}
					{elseif $view eq 'video'}					
						<tr>
							<td>
								{if $profile.video_id}
								<table cellpadding="3" cellspacing="3" border="0">
									{section name=ph loop=$profile.video_id}
									{if $smarty.section.ph.index is div by 3}<tr>{/if}
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												{if $profile.video_view_link[ph]}<a href="#" onclick="javascript:var top_pos = (window.screen.height - {$profile.video_height[ph]+70})/2; var left_pos = (window.screen.width - {$profile.video_width[ph]})/2; window.open('{$profile.video_view_link[ph]}','video_view','top='+top_pos+', left='+left_pos+', menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$profile.video_width[ph]}, height={$profile.video_height[ph]+70}');return false;" title="{$profile.video_user_comment[ph]}">{/if}<img src='{$profile.video_icon[ph]}' class='upload_thumb' alt="{$profile.video_user_comment[ph]}">{if $profile.video_view_link[ph]}</a>{/if}
											</td>
										</tr>
										<tr>
											<td>{$profile.video_user_comment[ph]}</td>
										</tr>	
										</table>												
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}		
								</table>
								{else}
								<font class="error">{$lang.content.no_video}</font>
								{/if}
							</td>
						</tr>
					{elseif $view eq 'map' && $profile.country_name && $data.in_base}
						<tr>
							<td>
							<div id="map_container" {if $map.name == "mapquest"}style="width: 650px; height: 550px;"{elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
							</td>
						</tr>
					{elseif $view eq 'calendar'}
						<tr>
							<td style="padding-bottom: 0px; padding-top:0px;">
								<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td>{$lang.content.calendar_name}</td>
									
									<td align="right" style="padding-left:3px;">
									<div style="background-color:gray; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.na_days}
									&nbsp;<div style="background-color:#FFD8D8; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.reserve_days}
									&nbsp;<div style="background-color:#CCFF99; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.empty_days}</td>
								<tr>
								</table>
								
							</td>											
						</tr>
						<tr>
							<td style="padding-top:2px;">
								<TABLE width="100%" cellpadding="2" cellspacing="0" border="0" align="center">
								<TR>
									<TD align="left" valign="middle" style="padding-left: 5px; padding-bottom:6px;" colspan="3">
									{strip}
										<A href="{$file_name}?view=calendar&id={$id_ad}&start_month={$date.display.prev_mon}&start_year={$date.display.prev_year}" class="calendar_back_next">
											&larr;&nbsp;{$lang.content.prev_year}
										</A>				
									{/strip}
									&nbsp;
									{strip}
										<A href="{$file_name}?view=calendar&id={$id_ad}&start_month={$date.display.prev_mon_1}&start_year={$date.display.prev_year_1}" class="calendar_back_next">
											&larr;&nbsp;{$lang.content.prev_month}
										</A>				
									{/strip}
									</TD>	
																	
									<TD align="right" valign="middle" style="padding-right: 15px;" colspan="3">
									{strip}
										<A href="{$file_name}?view=calendar&id={$id_ad}&start_month={$date.display.next_mon_1}&start_year={$date.display.next_year_1}" class="calendar_back_next">
											&rarr;&nbsp;{$lang.content.next_month}
										</A>				
									{/strip}
									&nbsp;
									{strip}
										<A href="{$file_name}?view=calendar&id={$id_ad}&start_month={$date.display.next_mon}&start_year={$date.display.next_year}" class="calendar_back_next">
											&rarr;&nbsp;{$lang.content.next_year}
										</A>											
									{/strip}																	
									</TD>
									
								</TR>
								{section name=calendar_row start=5 loop=14 step=4}								
								<TR>			
								<td></td>						
									{foreach from=$date.display.month item=item key=key name=month_header}
										{if $smarty.foreach.month_header.iteration < $smarty.section.calendar_row.index
										 && $smarty.foreach.month_header.iteration > $smarty.section.calendar_row.index-5}
										<TD align="center" valign="middle" width="" >								
										<b class="subheader">{$date.months[$item]}&nbsp;{$date.display.year[$key]}</b>
										</TD>										
										{/if}
									{/foreach}				
									<td></td>															
								</TR>
								
								<TR><!-- Calendar header -->
								<td></td>						
									{foreach from=$date.display.calendar.month item=item key=index_month name=month_data}
									{if $smarty.foreach.month_data.iteration < $smarty.section.calendar_row.index
										 && $smarty.foreach.month_data.iteration > $smarty.section.calendar_row.index-5}
										<td width="" valign="top" style="padding-top:5px" >
											<table align="center" style="background-color: #cccccc" cellpadding="2" cellspacing="1">
											<tr>
											{foreach from=$date.day_of_week item=day_of_week key=key}			
												<TD width="12%" align="center" style="background-color:white;">
													{assign var=cur value=$key+1 }																		
													{$date.day_of_week[$cur]}
													{if $cur == 7}
													{$date.day_of_week[0]}
													{/if}						
												</TD>				
											{/foreach}				
											
											</tr>
											{foreach from=$item item=week}
											<tr>		
												{foreach from=$date.day_of_week item=day_of_week key=key}				
													{assign var=cur value=$key+1 }	
													{if $cur == 7}
														{assign var=cur value=0}	
													{/if}
													<TD width="12%" align="center" style="
														{if $week[$cur].current_day == 'true'}border:1px solid red;padding:1px;{/if}
														{if $week[$cur].reserved_day == 'true'}background-color:#FFD8D8
														{elseif $week[$cur].reserved_day == 'not_available'}background-color:gray
														{else}background-color:#CCFF99{/if};
														{if $week[$cur].reserved_day == 'half_tf'} cursor: pointer; background-image: url('{$half_tf_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{elseif $week[$cur].reserved_day == 'half_ft'} cursor: pointer; background-image: url('{$half_ft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{elseif $week[$cur].reserved_day == 'half_tft'} cursor: pointer; background-image: url('{$half_tft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{else}"{/if}
														>							
														{if $week[$cur].wday == $cur && $week[$cur].mday>0}{$week[$cur].mday}{/if}																				
													</TD>				
												{/foreach}							
											</tr>				
											{/foreach}				
											</table>
											<table align="center">
											<tr>					
												<td id="choose_date" align="center"><div id='id_text_on_date{$index_month}' style="display:none;"></div></td>
											</tr>
											</table>
										</td>
									{/if}
									{/foreach}	
									<td></td>													
								</TR><!-- /Calendar header -->
								{/section}
								</TABLE>
							</td>
						</tr>
					{/if}	
					
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			{/if}
			</td>
		</tr>
		</table>
		{else}
			<!-- $sect eq 'more_list' -->
			{if $feature_ad}
				{include file="$gentemplates/feature_user.tpl"}
			{/if}
			{include file="$gentemplates/search_results_users.tpl"}
		{/if}
		{if $back_link}
		<table>
		<tr>
			<td width="15">&nbsp;</td>
			<td>
			<a href={$back_link}>{if $redirect == 1}{$lang.content.back_to_transfer}{elseif $redirect == 2}{$lang.content.back_to_choose_company}{elseif $redirect == 3}{$lang.content.back_to_choose_agent}{/if}</a>
			</td>
		</tr>
		{/if}
		{if $back_this_link}
		<tr>
			<td width="15">&nbsp;</td>
			<td>
			<a href={$back_this_link}>{if $redirect == 1}{$lang.content.back_to_this_transfer}{elseif $redirect == 2 && $search_result[0].company_name}{$lang.content.choose_company_as_agency}&nbsp;{$search_result[0].company_name}{elseif $redirect == 3}{$lang.content.choose_user_as_agent}&nbsp;{$search_result[0].login}{/if}</a>			
		</tr>
		</table>
		{/if}
	</td>
</tr>
</table>

{if (($profile.type eq 2  || $profile.type eq 4) && $view eq 'map' && $data.in_base && $profile.country_name)||($use_maps_in_viewprofile && ($profile.in_base || $profile.company_data.in_base) && $profile.country_name && $view eq 'general'&&(($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg || $profile.company_data))}

{include file="$gentemplates/viewmap.tpl"}
{/if}

*}
{include file="$gentemplates/site_footer.tpl"}
{literal}
<script>


function get_http(){
    var xmlhttp;	
	try{
		// Opera 8.0+, Firefox, Safari
		xmlhttp = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    return xmlhttp;
}

function GetHourByDate(day, mon, year, id_text){
	this.http = get_http();                           	
	url='{/literal}{$file_name}?sel=get_hour_by_date&id_ad={$id_ad}&id_user={$profile.id_user}&day={literal}'+day+'{/literal}&month={literal}'+mon+'{/literal}&year={literal}'+year;
	
		if (mon.length < 2){
		date = year+'-0'+mon;		
	}else{
		date = year+'-'+mon;
	}
	if (day.length < 2){
		date = date+'-0'+day;		
	}else{
		date = date+'-'+day;
	}
    if (this.http) {
        var http = this.http;       
        this.http.open("GET", url, true);                            
        this.http.onreadystatechange = function() {	
            if (http.readyState == 4) {            	            	
                show_text(id_text, date, http.responseText);                    
              }else{                   
              }
        }
        
        this.http.send(null);
    }
    if(!this.http){
          alert('Error XMLHTTP creating!')
    }	
}

function show_text(id_text,date,data){
	document.getElementById(id_text).innerHTML = "<b>"+date+"</b>";	
	document.getElementById(id_text).style.display = 'inline';	
	var arr = data.split('|');
	for(var i in arr){        
       document.getElementById(id_text).innerHTML = document.getElementById(id_text).innerHTML+"<br>"+arr[i];	                
    }
}

</script>
{/literal}