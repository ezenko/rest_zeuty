<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>{if $page_title}{$page_title}{else}{$lang.title}{/if}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<meta name="description" content="{if $page_description}{$page_description}{else}{$lang.description}{/if}">
	<meta name="keywords" content="{if $page_keywords}{$page_keywords}{else}{$lang.keywords}{/if}">
	<link href="{$site_root}{$template_root}{$template_css_root}/greybox.css" rel="stylesheet" type="text/css" media="all">
	<link href="{$site_root}{$template_root}{$template_css_root}/core.css" rel="stylesheet" type="text/css" media="all">
	<link href="{$site_root}{$template_root}{$template_css_root}/lightbox.css" rel="stylesheet" type="text/css" media="screen">
{if $head_add}
	{foreach from=$head_add item=add_code}
		{$add_code}
	{/foreach}
{/if}
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/location.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/comparison_list.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/md5.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/AmiJS.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/greybox.js"></script>	
	<script language="JavaScript" type="text/javascript">
		var GB_IMG_DIR = "{$site_root}{$template_root}{$template_images_root}/greybox/";
	</script>
	{literal}
<script language="JavaScript" type="text/javascript">
	var _GET_Keys;
	var _GET_Values;
	var _GET_Count = 0;
	var _GET_Default = '';

function get_parseGET() {
	get = new String(window.location);
	x = get.indexOf('?');
	if(x!=-1) {
		l = get.length;
		get = get.substr(x+1, l-x);
		l = get.split('&');
		x = 0;
		_GET_Count  = l.length;
		_GET_Keys   = new Array(_GET_Count);
		_GET_Values = new Array(_GET_Count);
		for(i in l)
			{
				if (typeof(l[i]) != "function") { //for lightbox
					get = l[i].split('=');
				   _GET_Keys[x] = get[0];
				   _GET_Values[x] = get[1];
				   x++;
				}
			}
		if (_GET_Keys[1] == 'thento' && _GET_Values[1] == 'editsubscribe' && _GET_Keys[2] == 'login' && _GET_Values[2].length>1 ) {
			return GB_show('', './login.php?from=subscribe&login='+_GET_Values[2], 230, 400);
		} else if (_GET_Keys[1] == 'thento' && _GET_Values[1] == 'viewmail' && _GET_Keys[2] == 'login' && _GET_Values[2].length>1) {
			return GB_show('', './login.php?from=mailto&login='+_GET_Values[2], 230, 400);
		}
	} else ;
	return;
}

function InComparisonList() {

	{/literal}{foreach from=$comparison_ids item=cid}{literal}
	var elem = document.getElementById('listing_add_to_comparison_' + '{/literal}{$cid.id}{literal}');
	if (elem) {
		elem.innerHTML = "{/literal}<b>{$lang.default_select.in_your_comparison_list}</b>{literal}";
	}
	{/literal}{/foreach}{literal}
}

</script>
{/literal}
</head>

<body style="margin: 0px;" onload="get_parseGET(); InComparisonList();{if (($data.user_type eq 2 || ($data.user_type eq 3 && $data.agency_approve eq 1)) && $data.id_country && $data.in_base && $use_maps_in_account)||($use_maps_in_viewprofile && $profile.country_name && $view eq 'general' && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) && ($profile.in_base || $profile.company_data.in_base))||(($profile.type eq 2  || $profile.type eq 4) && $view eq 'map' && $data.in_base && $use_maps_in_viewprofile)} getMapGlobal(&quot;{$map.name}&quot;, &quot;map_container&quot;, &quot;{$profile.adress}&quot;, &quot;{$profile.city_name}&quot;, &quot;{$profile.region_name}&quot;, &quot;{$profile.country_name}&quot;, &quot;{$profile.lat}&quot;, &quot;{$profile.lon}&quot;);{/if}">

<div id="container">
  	<div id="header">
    	<div id="top-decorate-line">
      </div>
      <div id="navi-container">
      	<div id="navi" class="centered-content">
        	<div class="free-space clearfix">
        		<ul class="clearfix">
            	<!--
            	<li><a id="logo"><img alt="" src="img/core/logo.png" /></a></li>
              -->
                {if $registered}
                    {assign var="menu_name" value=$homepage_top_menu}
                {else}
                    {if $section_name == "index"}
                        {assign var="menu_name" value=$index_page_menu}
                    {else}
                        {assign var="menu_name" value=$index_top_menu}
                    {/if}
                {/if}
                {assign var="total" value="100"}
                {section name=m loop=$menu_name}
                    <li><a {if $section_name == $menu_name[m].name}class="active"{/if} href="{$menu_name[m].href}" {if $menu_name[m].onclick neq ''}onclick="{$menu_name[m].onclick}"{/if}>{$menu_name[m].value}</a></li>
                {/section}
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="intro">
    	<div class="centered-content">
      	<div class="free-space">
        	<div id="special-block" class="clearfix">
        		<img alt="" src="img/fake/img1.jpg" />
            <a class="specials">
            	<span class="title">Египет от</span>
              <br />
              <span class="price">278$&nbsp;&nbsp;&nbsp;5*&nbsp;&nbsp;&nbsp;7/8</span>
            </a>
            <a class="more-specials">
            	ещё больше акций
            </a>
          </div>
        </div>
        <div class="free-space">
        	<div id="tours-block">
          	<div class="tour-item">
            	<a class="specials">
              	<span class="title">Анталия от</span>
                <span class="price">285$</span>
              </a>
            </div>
            <div class="tour-item">
            	<a class="specials">
              	<span class="title">ОАЭ от</span>
                <span class="price">285$</span>
              </a>
            </div>
            <div class="tour-item">
            	<a class="specials">
              	<span class="title">Турция от</span>
                <span class="price">285$</span>
              </a>
            </div>
            <div class="tour-item">
            	<a class="specials">
              	<span class="title">Тунис от</span>
                <span class="price">285$</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="widgets" class="clearfix">
    	<div class="centered-content clearfix">
        <div id="w-search" class="glass-block">
          <div class="free-space">
            <div class="clearfix">
            	<span class="title">Поиск</span>
              <a class="action">Искать</a>
            </div>
            <div class="content clearfix">
            	<div class="row">
              	<div class="field">
                  <label for="t1">Курорт, город, страна</label>
                  <input type="text" id="t1" />
                </div>
                <div class="field">
                	<label for="t2">Категория</label>
                  <input type="text" id="t2" />
                </div>
                <div class="field last">
                	<label>Стоимость</label><br/ >
                  <input type="text" id="ff" class="from" value="от" />
                  <input type="text" id="tt" class="to" value="до" />
                </div>
              </div>
              <div class="row row-last">
              	<div class="field">
                  <label for="y1">Поиск по объекту</label>
                  <input type="text" id="y1" />
                </div>
                <div class="field">
                	<input type="radio" id="r1" />
                  <label for="r1">летний отдых</label>
                  <br/>
                	<input type="radio" id="r2" />
                  <label for="r2">зимний отдых</label>
                  <br/>
                	<input type="radio" id="r3" />
                  <label for="r3">только с фото</label>
                  <br/>
                  <input type="radio" id="r4" />
                  <label for="r4">без посредников</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="w-wheather" class="glass-block">
        	<div class="city">
          	<div class="free-space">
            	<div class="clearfix">
              	<span class="title">Популярные курорты</span>
              </div>
              <div class="content cities clearfix">
              	<div class="clearfix">
              	<a class="weather-city sunny">Сочи</a>
                <a class="weather-city sunny">Туапсе</a>
                <a class="weather-city cloud">Сочи</a>
                <a class="weather-city cloud">Туапсе</a>
                <a class="weather-city cold last">Сочи</a>
                <a class="weather-city shine last">Туапсе</a>
                </div>
                <a class="all-cities">Все курорты</a>
              </div>
            </div>
          </div>
          <div class="weather">
          	<div class="free-space">
            	<div class="clearfix">
              	<span class="title">Погода</span>
              </div>
              <div class="content clearfix">
              	<img alt="" src="img/icons/Sun.png" />
                <img alt="" src="img/icons/Wind.png" />
                <div class="clearfix">
                	<input type="text" value="Сочи" />
                </div>
                <div class="clearfix">
                	<label>Сегодня, днем +38, ночью +23</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="main-content" class="clearfix">
    	<div class="centered-content clearfix">
        <div id="left-container">
        	<!--
          <div class="glass-block">
            <div class="free-space">
            	<ul>
              	<li><a class="home-link">Главная</a></li>
                <li><a class="item-link">Санаторий</a></li>
                <li><a class="item-link">Пансионаты</a></li>
                <li><a class="item-link">Мини-гостиницы</a></li>
                <li><a class="item-link">Кемпинги</a></li>
                <li><a class="item-link">Гостевые дома</a></li>
                <li><a class="item-link">Жилье</a></li>
              </ul>
            </div>
          </div>
          -->
          <div id="entertainment" class="clearfix">
          	<h2>Тематический отдых</h2>
            <ul>
            	<li><a class="folder-item">Отдых дикарем</a></li>
            	<li><a class="folder-item">Пляжные туры</a></li>
            	<li><a class="folder-item">Праздничные туры</a></li>
            	<li><a class="folder-item">Автобусные туры</a></li>
            	<li><a class="folder-item">Бизнес туры</a></li>
            	<li><a class="folder-item">Паломнические туры</a></li>
            	<li><a class="folder-item">Горнолыжные туры</a></li>
            	<li><a class="folder-item">Детский отдых</a></li>
            	<li><a class="folder-item">Романтические туры</a></li>
            	<li><a class="folder-item">Эскурсионные туры</a></li>
            	<li><a class="folder-item">Круизные туры</a></li>
            	<li><a class="folder-item">Лечебные туры</a></li>
            	<li><a class="folder-item">Активный отдых</a></li>
              <li><a class="folder-item">Семейный отдых</a></li>
              <li><a class="folder-item">Ночные клубы</a></li>
              <li><a class="folder-item">Аквапарки</a></li>
              <li><a class="folder-item">Морские прогулки</a></li>
              <li><a class="folder-item">Эскурсии</a></li>
              <li><a class="folder-item">Советы</a></li>
              <li><a class="folder-item">Фотогалерея</a></li>
              <li><a class="folder-item">Достопримечательности</a></li>
            </ul>
          </div>
        </div>


