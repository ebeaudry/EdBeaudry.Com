    function load() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map1"));
        map.setCenter(new GLatLng(0, 0), 2);
        map.enableScrollWheelZoom();
        map.addControl(new GMapTypeControl());
        map.addControl(new GLargeMapControl3D());
        map.addMapType(G_PHYSICAL_MAP);
        map.addMapType(G_SATELLITE_3D_MAP);
        map.setMapType(G_HYBRID_MAP);
        overviewMap = new GOverviewMapControl();
        map.addControl(overviewMap);
        overviewMap.setMapType(G_HYBRID_MAP);
        window.setTimeout(setupMarkers, 0);
      }
    }
	function Retrieve()
	{
		var geocoder = new GClientGeocoder();  
		var ge = null;
		var location = document.mainForm.city.value;
		geocoder.getLatLng(    
			location,     
			function(point) 
			{
				if (!point) {
			 		alert("Location: " + location + " not found");
				}
				else
				{
					document.mainForm.lat.value = point.x;
					document.mainForm.lng.value = point.y;
					window.setTimeout( document.mainForm.submit(), 1000);
				}
			}  
		);
	}
	function setupMarkers()
    { 
        try
        {
            markerManager = new MarkerManager(map);
            markers = [];
            if (window.ActiveXObject)
            {
                xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
				if(xmlDoc != null)
				{
					xmlDoc.async="false";
					xmlDoc.load("PlacesList.xml");
					}
					markerManager.addMarkers(markers, 0);
					markerManager.refresh();
				}
			}
        catch(e)
        {
            alert(e.message);
            return;
        } 
    }

	function createMarker(locID, place, lng, lat, exc)
    {
    
        var marker = new GMarker(new GLatLng(lat, lng), {title: place, icon: G_DEFAULT_ICON });
        var contents = "<font face=\"Arial\" size=\"3\">" + place + "</font><br><br>";
        for(var excursion = 0; excursion < exc.length; excursion++)
        { 
            contents =  contents + "<font face=\"Arial\" size=\"2\"><a href=\"" + exc[excursion].getElementsByTagName("Website")[0].childNodes[0].nodeValue + "\" target=\"_blank\">" + exc[excursion].getElementsByTagName("When")[0].childNodes[0].nodeValue + "</a></font><BR>";
        }
        var infoTabs = [
            new GInfoWindowTab(place, contents)
        ];
        GEvent.addListener(marker, "click", function() {
           		    map.updateInfoWindow(marker);
            marker.openInfoWindowHtml(contents);
        });
        return marker;
    }