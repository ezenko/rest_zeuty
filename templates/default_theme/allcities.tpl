{include file="$gentemplates/site_top.tpl"}
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>
<script type="text/javascript">
{literal}
    function createPlaceMark(id, name, lat, lot, desc) {
        var pl = new YMaps.Placemark(new YMaps.GeoPoint(lot, lat), {style : "default#campingIcon"});
        pl.name = '<a href="/viewprofile.php?id=' + id + '">' + name + '</a>';
        pl.description = '<div style="width:200px">' + desc + '</div>';
        return pl;
    }
    {/literal}
    window.onload = function () {ldelim}
        var map = new YMaps.Map(document.getElementById("YMapsID"));
        map.setCenter(new YMaps.GeoPoint(39.722271,43.582795), 8);
        
        map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
        map.addControl(new YMaps.MiniMap());
        map.addControl(new YMaps.ScaleLine());
        var searchControl = new YMaps.SearchControl({ldelim}
            resultsPerPage: 5,  // Количество объектов на странице
            useMapBounds: 1     // Объекты, найденные в видимой области карты 
                                // будут показаны в начале списка
        {rdelim});
        map.addControl(searchControl);
        map.enableScrollZoom();
        {foreach item=c from=$map_cities}
        map.addOverlay(createPlaceMark({$c.id}, '{$c.name}', {$c.lat}, {$c.lon}, '{$c.desc}'));
        {/foreach}
{rdelim}
</script>
<div id="middle-container">


<h2 style="height:24px;">Карта</h2>
<br />
<center>
    <div id="YMapsID" style="width:100%;height:500px">
    </div>
</center>
</div>
{include file="$gentemplates/site_footer.tpl"}