{if (($profile.type eq 2  || $profile.type eq 4) && $view eq 'map')||($profile.country_name && $view eq 'general'&&(($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg || $profile.company_data))||(($data.user_type eq 2 || $data.user_type eq 3) && $data.id_country<>0)||$data.section=='admin' && $data.user_type eq 2}

	{if $map.name == "yahoo"}
	<script type="text/javascript" src="http://api.maps.yahoo.com/ajaxymap?v=3.8&appid={$map.app_id}"></script>
	{elseif $map.name == "google"}
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$map.app_id}" type="text/javascript"></script>
	{elseif $map.name == "mapquest"}
	<script src="http://web.openapi.mapquest.com/oapi/transaction?request=script&key={$map.app_id}" type="text/javascript"></script>
	{elseif $map.name == "microsoft"}
	<script type="text/javascript" src="http://dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.1"></script>
	{/if}	
{/if}
{if $map.name}
{literal}
<script type="text/javascript">
	function getMapGlobal(map_name, map_container, adress, city_name, region_name, country_name, lat, lon){				
		if (map_name == "microsoft"){			
			getMicrosoftMap(map_container, adress, city_name, region_name, country_name, lat, lon);
		}else if (map_name == "yahoo"){			
			getYahooMap(map_container, adress, city_name, region_name, country_name, lat, lon);
		}else if (map_name == "google"){
			getGoogleMap(map_container, adress, city_name, region_name, country_name, lat, lon);
		}
	}
</script>
{/literal}
{/if}
{if $map.name == "yahoo"}
		{literal}
		<script type="text/javascript">
		function getYahooMap(map_container, adress, city_name, region_name, country_name, lat, lon){
			if(window.opera)alert("{/literal}{$lang.errors.map_error}{literal}"); else{
			// Create a lat/lon object
			var myPoint = "";
			if (adress){
				myPoint = adress+",";
			}
			if (city_name){
				myPoint = myPoint + city_name+",";
			}
			if (region_name){
				myPoint = myPoint + region_name+",";
			}				
			myPoint = myPoint + country_name;
			//YSize(width, height)
			map_size = new YSize(600, 400);
			// Create a map object
			var map = new YMap(document.getElementById(map_container), YAHOO_MAP_REG, map_size );
			// Add a slider zoom control
			map.addZoomLong();
			// Display the map centered on a latitude and longitude
			map.drawZoomAndCenter(myPoint, 3);
		
			// Create a marker positioned at a lat/lon
			var marker = new YMarker(myPoint);
		
			marker.addAutoExpand(myPoint);
		
			//display the marker
			map.addOverlay(marker);}
		}

		</script>
		{/literal}
	{elseif $map.name == "google"}
		{literal}
		<script type="text/javascript">
		function getGoogleMap(map_container, adress, city_name, region_name, country_name, lat, lon){
		    //<![CDATA[
		    if (GBrowserIsCompatible()) {
		      	var map = new GMap2(document.getElementById(map_container),
		            { size: new GSize(600,400) });
		        map.setCenter(new GLatLng(lat, lon), 15);
				map.enableInfoWindow();
	
		        var mapTypeControl = new GMapTypeControl();
		        var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
		        var bottomRight = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,10));
		        map.addControl(mapTypeControl, topRight);
		        GEvent.addListener(map, "dblclick", function() {
		          map.removeControl(mapTypeControl);
		          map.addControl(new GMapTypeControl(), bottomRight);
		        });
		        map.addControl(new GSmallMapControl());
	
		        //place a marker based on address
				var geocoder = new GClientGeocoder();
				var address = "";				
				if (adress){
					address = adress+",";
				}
				if (city_name){
					address = address + city_name+",";
				}
				if (region_name){
					address = address + region_name+",";
				}				
				address = address + country_name;				
				geocoder.getLatLng(address,
					function(point) {
						if (!point) {
							alert("{/literal}{$lang.default_select.address}{literal} '" + address + "' {/literal}{$lang.default_select.was_not_found}{literal}!");
						} else {
							map.setCenter(point, 15);
				            var marker = new GMarker(point);
				            map.addOverlay(marker);
				            marker.openInfoWindowHtml(address);
						}
					});
		    }
		}
	    //]]>
	    </script>
	    {/literal}
	{elseif $map.name == "mapquest"}
		{literal}
		<script type="text/javascript">
		
		function getMap(address, city, state_province, country) {
				var mq = new MQMap("map_container");
				var loc1 = new MQLocation();
				loc1.setName("POI Set by Address with a server side icon");
				loc1.setAddress("{/literal}{$profile.adress}{literal}");				
				loc1.setCity("{/literal}{$profile.city_name}{literal}");
				loc1.setStateProvince("{/literal}{$profile.region_name}{literal}");
				loc1.setCountry("{/literal}{$profile.country_name}{literal}");
				loc1.setIconId(3);
				mq.locations.add(loc1);
				mq.getMap();					
		}
		</script>
		{/literal}
	{elseif $map.name == "microsoft"}	
	
		{literal}
		<script type="text/javascript">
		function getMicrosoftMap(map_container, adress, city_name, region_name, country_name, lat, lon) {						
	       try {	   	       	
				var map = new VEMap(map_container);				
				var address = "";				
				if (adress){
					address = adress+",";
				}
				if (city_name){
					address = address + city_name+",";
				}
				if (region_name){
					address = address + region_name+",";
				}				
				address = address + country_name;
				
				map.LoadMap(new VELatLong(lat, lon), 14);

				map.Find(null, address, VEFindType.Businesses, null, null, null, null, null, null, true, onfound);

	       } catch(exception)  {	       	
		   		
		   }
		   function onfound(layer, resultsArray, places, hasMore, veErrorMessage) {
			     if (places) {
			        var shape = new VEShape(VEShapeType.Pushpin,places[0].LatLong);
			        shape.SetDescription(address);
			        map.AddShape(shape);
			     }
		 	}
		}
		</script>
		{/literal}
	{/if}
