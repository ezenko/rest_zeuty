<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if $page_title}{$page_title}{else}{$lang.title}{/if}</title>
    
    <meta name="description" content="{if $page_description}{$page_description}{else}{$lang.description}{/if}" />
	<meta name="keywords" content="{if $page_keywords}{$page_keywords}{else}{$lang.keywords}{/if}" />
    
  <link rel="stylesheet" type="text/css" href="{$site_root}{$template_root}{$template_css_root}/core.css" />
  <link rel="stylesheet" type="text/css" href="{$site_root}{$template_root}{$template_css_root}/jquery.linkselect.css" />
  <link href="{$site_root}{$template_root}{$template_css_root}/greybox.css" rel="stylesheet" type="text/css" media="all"/>
	<link href="{$site_root}{$template_root}{$template_css_root}/core.css" rel="stylesheet" type="text/css" media="all"/>
	<link href="{$site_root}{$template_root}{$template_css_root}/lightbox.css" rel="stylesheet" type="text/css" media="screen"/>
  
  {if $head_add}
	{foreach from=$head_add item=add_code}
		{$add_code}
	{/foreach}
  {/if}
  
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://cdn.jquerytools.org/1.2.5/tiny/jquery.tools.min.js"></script>
  <script type="text/javascript" src="{$site_root}{$template_root}/js/jquery.linkselect.min.js"></script>
  <script type="text/javascript" src="{$site_root}{$template_root}/js/jquery.rotate.js"></script>
  
    <script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/location.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/comparison_list.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/md5.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/AmiJS.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/greybox.js"></script>	
	<script language="JavaScript" type="text/javascript">
		var GB_IMG_DIR = "{$site_root}{$template_root}{$template_images_root}/greybox/";
	</script>
    
  {literal}
  <script type="text/javascript">
  	$(document).ready(function() {
			$('select').linkselect();
			
			$('#special-block').list_ticker({speed:5000, effect:'fade'});
			$("ul.tabs").tabs("div.panes > div");
			$("#right-container .scrollable").scrollable({ vertical: true, mousewheel: true });
			$("#pre-footer .scrollable").scrollable({ vertical: false, mousewheel: true });
		});
		
		
  	window.onresize = function() { resizeContent(); };
		
		function resizeContent() {
			var blockWidth = $('#widgets > .centered-content').width();
			var serachBlockWidth = $('#w-search').width();
			$('#w-wheather').width(blockWidth - serachBlockWidth - 35);
		}
  </script>
  	
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

<body onload="resizeContent();get_parseGET(); InComparisonList();{if (($data.user_type eq 2 || ($data.user_type eq 3 && $data.agency_approve eq 1)) && $data.id_country && $data.in_base && $use_maps_in_account)||($use_maps_in_viewprofile && $profile.country_name && $view eq 'general' && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) && ($profile.in_base || $profile.company_data.in_base))||(($profile.type eq 2  || $profile.type eq 4) && $view eq 'map' && $data.in_base && $use_maps_in_viewprofile)} getMapGlobal(&quot;{$map.name}&quot;, &quot;map_container&quot;, &quot;{$profile.adress}&quot;, &quot;{$profile.city_name}&quot;, &quot;{$profile.region_name}&quot;, &quot;{$profile.country_name}&quot;, &quot;{$profile.lat}&quot;, &quot;{$profile.lon}&quot;);{/if}">
	<div id="container">
  	<div id="header">
    	<div id="top-decorate-line">
      </div>
      <div id="navi-container">
      	<div id="navi" class="centered-content">
        	<div class="cont free-space clearfix">
          	<a id="logo"><img alt="" src="{$site_root}{$template_root}/img/core/logo.png" /></a>
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
                <ul id="menu" class="clearfix">
                {section name=m loop=$menu_name}
                    <li><a {if $section_name == $menu_name[m].name}class="active"{/if} href="{$menu_name[m].href}" {if $menu_name[m].onclick neq ''}onclick="{$menu_name[m].onclick}"{/if}>{$menu_name[m].value}</a></li>
                    {if !$smarty.section.m.last}
                    <li><span>|</span></li>
                    {/if}
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
          	<div class="r_item clearfix">
              <img alt="" src="img/fake/img1.jpg" />
              <a class="specials">
                <span class="title">1Египет от</span>
                <br />
                <span class="price">278$&nbsp;&nbsp;&nbsp;5*&nbsp;&nbsp;&nbsp;7/8</span>
              </a>
              <a class="more-specials">
                ещё больше акций
              </a>
            </div>
          	<div class="r_item clearfix">
              <img alt="" src="img/fake/img1.jpg" />
              <a class="specials">
                <span class="title">2Египет от</span>
                <br />
                <span class="price">278$&nbsp;&nbsp;&nbsp;5*&nbsp;&nbsp;&nbsp;7/8</span>
              </a>
              <a class="more-specials">
                ещё больше акций
              </a>
            </div>
          	<div class="r_item clearfix">
              <img alt="" src="img/fake/img1.jpg" />
              <a class="specials">
                <span class="title">3Египет от</span>
                <br />
                <span class="price">278$&nbsp;&nbsp;&nbsp;5*&nbsp;&nbsp;&nbsp;7/8</span>
              </a>
              <a class="more-specials">
                ещё больше акций
              </a>
            </div>
          	<div class="r_item clearfix">
              <img alt="" src="img/fake/img1.jpg" />
              <a class="specials">
                <span class="title">4Египет от</span>
                <br />
                <span class="price">278$&nbsp;&nbsp;&nbsp;5*&nbsp;&nbsp;&nbsp;7/8</span>
              </a>
              <a class="more-specials">
                ещё больше акций
              </a>
            </div>
        	</div>
        </div>
        <div class="free-space">
        	<div id="tours-block" class="clearfix">
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
      	<ul class="tabs clearfix">
        	<li class="m1"><a href="#">Отдых дикарем</a></li>
        	<li class="m2"><a href="#">Активный отдых</a></li>
        	<li class="m3"><a href="#">Туры</a></li>
        	<li class="m4"><a href="#">Недвижимость</a></li>
        </ul>
        <div id="w-search" class="glass-block panes">
        	<div>
            <div class="free-space">
              <div class="clearfix">
                <span class="title">Поиск</span>
                <a class="action">Искать</a>
              </div>
              <div class="content clearfix">
                <div class="row row-first">
                  <div class="field">
                    <label for="t1">Откуда</label>
                    <select id="t1">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="t2">Поиск по ID</label>
                    <select id="t2">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                  <!--
                  <div class="field last">
                    <label>Стоимость</label><br/ >
                    <input type="text" id="ff" class="from" value="от" />
                    <input type="text" id="tt" class="to" value="до" />
                  </div>
                  -->
                </div>
                <div class="row">
                  <div class="field">
                    <label for="y1">Куда</label>
                    <select id="y1">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="y2">Регион</label>
                    <select id="y2">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                  <!--
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
                  -->
                </div>
                <div class="row">
                  <div class="field">
                    <label for="c1">Курорт</label>
                    <select id="c1">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="c2">Расположение</label>
                    <select id="c2">
                      <option>Hello</option>
                      <option>Hello 1</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
          	another content 2
          </div>
          <div>
          	another content 3
          </div>
          <div>
          	another content 4
          </div>
        </div>
        <div id="w-wheather" class="glass-block">
        	<table border="0">
          	<tr>
            	<td>
                <div class="city">
                  <div class="free-space">
                    <div class="clearfix">
                      <span class="title">Популярные</span>
                    </div>
                    <div class="content cities">
                      <div class="cities-container clearfix">
                        <div class="cities-cell">
                          <a class="weather-city sunny">Новороссийск</a>
                          <a class="weather-city cloud">Туапсе</a>
                        </div>
                        <div class="cities-cell">
                          <a class="weather-city cold">Сочи</a>
                          <a class="weather-city shine">Туапсе</a>
                        </div>
                        <div class="cities-cell">
                          <a class="weather-city cold">Сочи</a>
                          <a class="weather-city shine">Туапсе</a>
                        </div>
                        <div class="cities-cell">
                          <a class="weather-city cold">Сочи</a>
                          <a class="weather-city shine">Туапсе</a>
                        </div>
                        <div class="cities-cell">
                          <a class="weather-city cold">Сочи</a>
                          <a class="weather-city shine">Туапсе</a>
                        </div>
                        <div class="cities-cell">
                          <a class="weather-city cold">Сочи</a>
                          <a class="weather-city shine">Туапсе</a>
                        </div>
                      </div>
                      <a class="all-cities">Все курорты</a>
                    </div>
                  </div>
                </div>
          		</td>
          		<td width="234px">
                <div class="weather">
                  <div class="free-space">
                    <div class="content clearfix">
                      <img alt="" src="img/icons/Sun.png" />
                      <img alt="" src="img/icons/Wind.png" />
                      <div class="clearfix">
                        <select id="w_city">
                          <option>Сочи</option>
                        </select>
                      </div>
                      <div class="clearfix">
                        <label>Сегодня, днем +38, ночью +23</label>
                      </div>
                    </div>
                  </div>
                </div>
          		</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div id="main-content" class="clearfix">
    	<div class="centered-content clearfix">
        <div id="left-container">
			<div id="entertainment" class="clearfix">
				<h2>Тематический отдых</h2>
				<ul>
                    {foreach from=$rest item=r}
                      {if $r.id eq 1}
                        {foreach from=$r.opt item=opt}
                	    <li><a class="folder-item">{$opt.name}</a></li>
                	    {/foreach}
                	  {/if}
                    {/foreach}
                </ul>
                </div>
        </div>
        <div id="right-container">
        	<div id="tourist" class="clearfix">
            <h2>Активный отдых</h2>
            <ul>
                {foreach from=$rest item=r}
                  {if $r.id eq 2}
                    {foreach from=$r.opt item=opt}
            	    <li><a class="folder-item">{$opt.name}</a></li>
            	    {/foreach}
            	  {/if}
                {/foreach}
            </ul>
          </div>
          <div id="hot-tours" class="clearfix">
          	<h2 class="gold">Горящие предложения</h2>
            <a class="prev"></a>
            <div class="clearfix scrollable vertical">
              <div class="items-container">
              	<div>
                  <div class="item">
                    <img alt="" src="img/fake/img1.jpg" />
                    <span class="title">Сочи</span>
                    <span class="price">Антигуа: от 670$</span>
                    <a class="order">Заказать</a>
                  </div>
                  <div class="item">
                    <img alt="" src="img/fake/img1.jpg" />
                    <span class="title">Сочи</span>
                    <span class="price">Антигуа: от 670$</span>
                    <a class="order">Заказать</a>
                  </div>
                </div>
              	<div>
                  <div class="item">
                    <img alt="" src="img/fake/img1.jpg" />
                    <span class="title">Сочи 3333</span>
                    <span class="price">Антигуа: от 670$</span>
                    <a class="order">Заказать</a>
                  </div>
                  <div class="item">
                    <img alt="" src="img/fake/img1.jpg" />
                    <span class="title">Сочи 444</span>
                    <span class="price">Антигуа: от 670$</span>
                    <a class="order">Заказать</a>
                  </div>
                </div>
              </div>
            </div>
            <a class="next"></a>
          </div>
        </div>


