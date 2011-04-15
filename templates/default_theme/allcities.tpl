{include file="$gentemplates/site_top.tpl"}
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHKJnU0BAAAAQj_CXQMAlCv8AgcJmzaKCB7rVHM6ewsmexEAAAAAAAAAAACBYbbZI70vJEwOiGOSBjPB-v-wCQ==" type="text/javascript"></script>
<script type="text/javascript">
{literal}
    function createPlaceMark(id, name, lat, lot, desc, type) {
        var plStyle = "default#campingIcon";
        if(type==2)
            plStyle = "default#houseIcon";
        var pl = new YMaps.Placemark(new YMaps.GeoPoint(lot, lat), {style : plStyle});
        pl.name = '<a href="/viewprofile.php?id=' + id + '">' + name + '</a>';
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
        addMarkers();
        
    }
    
    function addMarkers() {
        if(document.getElementById('map_active').checked) {
            {/literal}
            {foreach item=c from=$map_active_rest}
            map.addOverlay(createPlaceMark({$c.id}, '{$c.name}', {$c.lat}, {$c.lon}, '{$c.desc}', 1));
            {/foreach}
            {literal}
        }
        if(document.getElementById('map_myself').checked) {
            {/literal}
            {foreach item=c from=$map_myself_rest}
            map.addOverlay(createPlaceMark({$c.id}, '{$c.name}', {$c.lat}, {$c.lon}, '{$c.desc}', 2));
            {/foreach}
            {literal}
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
    <input type="checkbox" name="map_active" id="map_active" checked="checked" onchange="updateMap()" /> Активный отдых
    <input type="checkbox" name="map_myself" id="map_myself" checked="checked" onchange="updateMap()" /> Отдых дикарем
    <input type="checkbox" name="map_entertaiment" id="map_entertaiment" checked="checked" onchange="updateMap()" /> Развлечения
    <div id="YMapsID" style="width:100%;height:500px">
    </div>
</center>
</div>
{include file="$gentemplates/site_footer.tpl"}