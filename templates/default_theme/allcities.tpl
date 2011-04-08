{include file="$gentemplates/site_top.tpl"}
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>
<script type="text/javascript">
{literal}
    function createPlaceMark(id, country, region, name, lat, lot) {
        var pl = new YMaps.Placemark(new YMaps.GeoPoint(lot, lat));
        pl.name = name;
        pl.description = '<a href="/quick_search.php?from_file=index&choise=1&city=' + id + '&sel=category&country=' + country + '&region=' + region + '">Отдых дикарем</a> <a href="/quick_search.php?from_file=index&choise=4&city=' + id + '&sel=category&country=' + country + '&region=' + region + '">Активный отдых</a>';
        return pl;
    }
    {/literal}
    window.onload = function () {ldelim}
        var map = new YMaps.Map(document.getElementById("YMapsID"));
        map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 4);
        
        map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
        map.addControl(new YMaps.MiniMap());
        map.addControl(new YMaps.ScaleLine());
        {foreach item=c from=$map_cities}
        map.addOverlay(createPlaceMark({$c.id}, {$c.id_country}, {$c.id_region}, '{$c.name}', {$c.lat}, {$c.lon}));
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