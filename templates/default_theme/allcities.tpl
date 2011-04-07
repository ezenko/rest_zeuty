{include file="$gentemplates/site_top.tpl"}
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>
<script type="text/javascript">
    window.onload = function () {ldelim}
        var map = new YMaps.Map(document.getElementById("YMapsID"));
        map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 4);
        
        map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
        map.addControl(new YMaps.MiniMap());
        map.addControl(new YMaps.ScaleLine());
        {foreach item=c from=$map_cities}
        map.addOverlay(new YMaps.Placemark(new YMaps.GeoPoint({$c.lon}, {$c.lat})));
        {/foreach}
{rdelim}
</script>
<div id="middle-container">


<h2 style="height:24px;">Карта</h2>
<br />
<center>
    <div id="YMapsID" style="width:700px;height:500px">
    </div>
</center>
</div>
{include file="$gentemplates/site_footer.tpl"}