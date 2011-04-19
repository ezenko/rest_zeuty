{include file="$gentemplates/site_top.tpl"}
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>
<script type="text/javascript">

    var city_list = [
    {foreach from=$map_cities item=city}
    {ldelim}id: {$city.id}, name: '{$city.name}', lat : {$city.lat}, lon : {$city.lon}, show : {$city.show_on_map}, country: {$city.country}, region: {$city.region}{rdelim},
    {/foreach}
    ];
    
    var entertaiments = [
    {foreach from=$map_entertaiments item=e}
    {ldelim}id: {$e.id}, name: '{$e.caption}', lat : {$e.lat}, lon : {$e.lon}{rdelim},
    {/foreach}
    ];
    {literal}
    function createPlaceMark(id, name, lat, lot, desc, type) {
        var plStyle = "default#campingIcon";
        if(type==2)
            plStyle = "default#houseIcon";
        else if(type == 'city')
            plStyle = "default#hospitalIcon";
        else if(type == 'ent')
            plStyle = "default#metroIcon";
        var pl = new YMaps.Placemark(new YMaps.GeoPoint(lot, lat), {style : plStyle});
        pl.name = name;
        pl.description = '<div style="width:200px">' + desc + '</div>';
        return pl;
    }
    
    var map;
    window.onload = function () {
        map = new YMaps.Map(document.getElementById("YMapsID"));
        map.setCenter(new YMaps.GeoPoint(39.722271,43.582795), 8);
        
        map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
        map.addControl(new YMaps.MiniMap());
        map.addControl(new YMaps.ScaleLine());
        var searchControl = new YMaps.SearchControl({
            resultsPerPage: 5,  // Количество объектов на странице
            useMapBounds: 1     // Объекты, найденные в видимой области карты 
                                // будут показаны в начале списка
        });
        map.addControl(searchControl);
        map.enableScrollZoom();
        
        YMaps.Events.observe(map, map.Events.SmoothZoomEnd, function (obj) {
            map.removeAllOverlays();
            addMarkers();
        });
        addMarkers();
        
    }
    
    function addMarkers() {
        if(document.getElementById('map_city').checked) {
            for(i = 0; i < city_list.length && city_list[i]; i++) {
                if(city_list[i].show || map.getZoom() >= 10) {
                    map.addOverlay(createPlaceMark(city_list[i].id, city_list[i].name, city_list[i].lat, city_list[i].lon, '<a href="/quick_search.php?from_file=index&choise=1&city=' + city_list[i].id + '&sel=category&country=' + city_list[i].country + '&region=' + city_list[i].region + '">Отдых дикарем</a>', 'city'));
                }
            }
        }
        
        if(document.getElementById('map_active').checked && map.getZoom() >= 5) {
            {/literal}
            {foreach item=c from=$map_active_rest}
            map.addOverlay(createPlaceMark({$c.id}, '<a href="/viewprofile.php?id={$c.id}">{$c.name}</a>', {$c.lat}, {$c.lon}, '{$c.desc}', 1));
            {/foreach}
            {literal}
        }
        if(document.getElementById('map_myself').checked && map.getZoom() >= 13) {
            {/literal}
            {foreach item=c from=$map_myself_rest}
            map.addOverlay(createPlaceMark({$c.id}, '<a href="/viewprofile.php?id={$c.id}">{$c.name}</a>', {$c.lat}, {$c.lon}, '{$c.desc}', 2));
            {/foreach}
            {literal}
        }
        
        if(document.getElementById('map_entertaiment').checked && map.getZoom() >= 13) {
            for(i = 0; i < entertaiments.length && entertaiments[i]; i++) {
                map.addOverlay(createPlaceMark(entertaiments[i].id, entertaiments[i].name, entertaiments[i].lat, entertaiments[i].lon, '<a href="/entertainment.php?id=' + entertaiments[i].id + '">' + entertaiments[i].name + '</a>', 'ent'));
            }
        }
    }
    
    function updateMap() {
        map.removeAllOverlays();
        addMarkers();
    }
    {/literal}
</script>
<div id="middle-container">


<h2 style="height:24px;">Карта</h2>
<br />
<center>
    <input type="checkbox" name="map_city" id="map_city" checked="checked" onchange="updateMap()" /> Курорты
    <input type="checkbox" name="map_myself" id="map_myself" checked="checked" onchange="updateMap()" /> Объекты размещения (Отдых дикарем)
    <input type="checkbox" name="map_active" id="map_active" checked="checked" onchange="updateMap()" /> Активный отдых
    <input type="checkbox" name="map_entertaiment" id="map_entertaiment" checked="checked" onchange="updateMap()" /> Развлечения
    <input type="checkbox" name="map_realty" id="map_realty" checked="checked" onchange="updateMap()" /> Недвижимость
    <div id="YMapsID" style="width:100%;height:700px;margin-top:20px">
    </div>
</center>
</div>
{include file="$gentemplates/site_footer.tpl"}