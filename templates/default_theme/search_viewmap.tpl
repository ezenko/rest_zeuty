{if $search_result}
	{if $map.name == "google"}
		<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key={$map.app_id}" type="text/javascript"></script>
	{elseif $map.name == "microsoft_dont_work"}
		<script type="text/javascript" src="http://dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.1"></script>
	{/if}
{/if}
{if $map.name == "google"}
{literal}
	<script type="text/javascript">    
    var map = null;
    var geocoder = null;
    var marker = []; 
    var markerImage = [G_DEFAULT_ICON.image,"http://www.google.com/uds/samples/places/temp_marker.png"];

    function initialize() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map_container"), { size: new GSize(717,400) });
        map.removeMapType(G_HYBRID_MAP);
		var mapControl = new GMapTypeControl();
		map.addControl(mapControl);
		map.addControl(new GLargeMapControl());
        geocoder = new GClientGeocoder();
      }
    }
    function showAddress(address,id,image,type,cost,date) {
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
            } else {	            
        	  map.setCenter(point, 3);
              marker[id] = new GMarker(point);
              map.addOverlay(marker[id]);
		      GEvent.addListener(marker[id], "mouseover", function() {
		      marker[id].setImage(markerImage[1]);
			  marker[id].openInfoWindowHtml('<table><tr><td rowspan="4"><a href=viewprofile.php?id='+id+'><img src='+image+' height=70px style="border: 1px solid #cccccc; cursor: pointer;"></a></td><td  align="left"><a href=viewprofile.php?id='+id+'>'+address+'</a></td><tr><td  align="left">'+type+'</td></tr><tr><td  align="left">'+cost+'</td></tr><tr><td  align="left">'+date+'</td></tr></tr></table>');});     
              GEvent.addListener(marker[id], "mouseout", function() {
		      marker[id].setImage(markerImage[0]);});   
            }
          }
        );
      }
    }
    initialize();	    
    {/literal}    
	    {if $feature_ad}	    			
		    {literal}
			    showAddress('{/literal}{if $feature_ad.adress}{$feature_ad.adress}, {/if}{if $feature_ad.city_name}{$feature_ad.city_name}, {/if}{if $feature_ad.region_name}{$feature_ad.region_name}, {/if}{$feature_ad.country_name}{literal}','{/literal}{$feature_ad.id}{literal}','{/literal}{$site_root}{$feature_ad.image}{literal}','<b>{/literal}{$feature_ad.login} {if $feature_ad.id_type eq 1}{$lang.content.i_need}{elseif $feature_ad.id_type eq 2}{$lang.content.i_have}{elseif $feature_ad.id_type eq 3}{$lang.content.i_buy}{elseif $feature_ad.id_type eq 4}{$lang.content.i_sell}{/if} {$feature_ad.realty_type}{literal}</b>','{/literal}{if $feature_ad.id_type eq 1 || $feature_ad.id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}: {$feature_ad.min_payment_show}{literal}','{/literal}{$lang.content.available_date} {$feature_ad.movedate}{literal}');
		    {/literal}	
		{/if}	
		{section name=rows loop=$search_result}
	    {literal}
		    showAddress('{/literal}{if $search_result[rows].adress}{$search_result[rows].adress}, {/if}{if $search_result[rows].city_name}{$search_result[rows].city_name}, {/if}{if $search_result[rows].region_name}{$search_result[rows].region_name}, {/if}{$search_result[rows].country_name}{literal}','{/literal}{$search_result[rows].id}{literal}','{/literal}{$site_root}{$search_result[rows].image}{literal}','<b>{/literal}{$search_result[rows].login} {if $search_result[rows].id_type eq 1}{$lang.content.i_need}{elseif $search_result[rows].id_type eq 2}{$lang.content.i_have}{elseif $search_result[rows].id_type eq 3}{$lang.content.i_buy}{elseif $search_result[rows].id_type eq 4}{$lang.content.i_sell}{/if} {$search_result[rows].realty_type}{literal}</b>','{/literal}{if $search_result[rows].id_type eq 1 || $search_result[rows].id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}: {$search_result[rows].min_payment_show} {literal}','{/literal}{$lang.content.available_date} {$search_result[rows].movedate}{literal}');
	    {/literal}	    
		{/section}
	{literal}
    </script>  
{/literal}

{*The code on Virtual Earth is carried out only up to a point with the address on which the system has some variants*} 

{elseif $map.name == "microsoft_dont_work"}
{literal}
	<script type="text/javascript"> 
	var map = null;
	      var findPlaceResults = null;
	      function getMap() {
	         map = new VEMap('map_container');
	         map.LoadMap(null,5);	        
	      }
	     function GetCoordinates(layer, resultsArray, places, hasMore, veErrorMessage) {
	       findPlaceResults = places[0].LatLong;
	       var myShape = new VEShape(VEShapeType.Pushpin, findPlaceResults);
	       myShape.SetDescription(findPlaceResults.toString());
	       map.AddShape(myShape);
	     }     
	     function showAddress(address,id,image,type,cost,date){
	       map.Find(null,address, null, null, null, null, true, true, null, true, GetCoordinates);          
	     }     
	getMap();
{/literal}
	{section name=rows loop=$search_result}
	{literal}
		showAddress('{/literal}{if $search_result[rows].adress}{$search_result[rows].adress}, {/if}{if $search_result[rows].city_name}{$search_result[rows].city_name}, {/if}{if $search_result[rows].region_name}{$search_result[rows].region_name}, {/if}{$search_result[rows].country_name}{literal}','{/literal}{$search_result[rows].id}{literal}','{/literal}{$site_root}{$search_result[rows].image}{literal}','<b>{/literal}{$search_result[rows].login}{if $search_result[rows].id_type eq 1}{$lang.content.i_need}{elseif $search_result[rows].id_type eq 2}{$lang.content.i_have}{elseif $search_result[rows].id_type eq 3}{$lang.content.i_buy}{elseif $search_result[rows].id_type eq 4}{$lang.content.i_sell}{/if} {$search_result[rows].realty_type}{literal}</b>','{/literal}{if $search_result[rows].id_type eq 1 || $search_result[rows].id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}: {$search_result[rows].min_payment_show}{literal}','{/literal}{$lang.content.available_date} {$search_result[rows].movedate}{literal}');
	{/literal}	    
	{/section}
{literal}
	</script>
{/literal}
{/if}
