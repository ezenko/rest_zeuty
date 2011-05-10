{include file="$gentemplates/site_top.tpl"}
    <div id="middle-container">
    {literal}
    <style>
    #middle-container .entertaiment_tbl td { border: none;}
    </style>
    {/literal}
    
    <script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>

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
	//right vars values from lightbox.js
	var fileLoadingImage = "{$server}{$site_root}{$template_root}/images/lightbox/loading.gif";
	var fileBottomNavCloseImage = "{$server}{$site_root}{$template_root}/images/lightbox/closelabel.gif";

	</script>
<table cellpadding="0" cellspacing="0" border="0" class="entertaiment_tbl">
<tr>
    <!--
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	-->
    
	<td class="main" valign="top">
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
		<tr><td class="header"><h2>{$section.caption}</h2></td></tr>
		{if $section.image}
      <tr><td style="text-align: center;"><img src="/uploades/entertaiments/{$section.image}" style="max-width: 350px;"/></td></tr>
    {/if}
    <tr><td><hr/></td></tr>
		<tr>
			<td class="page_content" style="text-align: left;">{$section.content}</td>
		</tr>
        <tr>
            <td>
                <br />
                <h3>Фото-галерея</h3>
                <div class="photo-gallery" style="margin-right:250px">
                    {foreach item=ph from=$images}
                	<div class="item"><a href="/uploades/photo/{$ph.image}" rel="lightbox[profile_photo_main]"><img src='/uploades/photo/thumb_{$ph.image}' class='img-border' alt=""></a></div>
                    {/foreach}
                </div>
                <div style="width:240px; float:right;">
                Адрес: {$section.address}
                <br />
                Контакты: {$section.contacts}
                </div>
            </td>
        </tr>
        <tr><td>
        
        <script type="text/javascript">
            var lat = '{$section.lat}';
            var lon = '{$section.lon}';
            var map, placemark;
        {literal}
            window.onload = function () {
            map = new YMaps.Map(document.getElementById("YMapsID"));
            map.setCenter(new YMaps.GeoPoint(lon, lat), 12);
            
            map.addControl(new YMaps.TypeControl());
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            //map.addControl(new YMaps.MiniMap());
            map.addControl(new YMaps.ScaleLine());
            map.enableScrollZoom();
            placemark = new YMaps.Placemark(new YMaps.GeoPoint(lon, lat));
            placemark.name = '{$section.caption}';
            map.addOverlay(placemark);
            
            }
        {/literal}
        </script>
        <div id="YMapsID" style="width:100%; height: 400px"> </div>
        </td></tr>
		</table>
	</td>
</tr>
</table>
</div>
{literal}
<script language="javascript">
	function ShowHideDiv(elem_id) {
		var subsections_array = new Array({/literal}{foreach from=$subsections item=subsection key=key name=subsection}"{$subsection.id}"{if !$smarty.foreach.subsection.last},{/if}{/foreach}{literal});

		var subsections_size = subsections_array.length;

		for (i=0; i<subsections_size; i++) {
			var name = "subsection_id_" + subsections_array[i];
			var elem = document.getElementById(name);
			if (name == elem_id) {
				if (elem.style.display == 'block') {
					elem.style.display = 'none';
				} else {
					elem.style.display = 'block';
				}
			} else {
				elem.style.display = 'none';
			}
		}
	}
</script>
{/literal}
{include file="$gentemplates/site_footer.tpl"}