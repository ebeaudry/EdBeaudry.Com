   <?php
           require_once('login.php');
        $db_server = mysql_pconnect( $db_hostname, $db_username, $db_password);
		mysql_select_db($db_database);
        if(!$db_server)
        {
            die("Unable to connect");
        }
?>
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
    function AddExcursion()
    {
        window.setTimeout( document.mainForm.submit(), 1000);
    }
	function Retrieve()
	{
    	var locationVal;
        var city = document.mainForm.city.value;

    	for(i = 0; i < document.mainForm.location.length; i++)
        {
        	if(document.mainForm.location[i].checked==true)
            {
            	locationVal = document.mainForm.location[i].value;
            }
        }
        if(locationVal == "new" && city != "")
        {
            var geocoder = new GClientGeocoder();  
            var ge = null;
            geocoder.getLatLng(    
                city,     
                function(point) 
                {
                    if (!point) {
                        alert("Location: " + city + " not found");
                    }
                    else
                    {
                        document.mainForm.lat.value = point.y;
                        document.mainForm.lng.value = point.x;
                        window.setTimeout( document.mainForm.submit(), 1000);
                    }
                }  
            );
        }
	}
	function setupMarkers()
    { 
        try
        {
            markerManager = new MarkerManager(map);
            markers = [];
			<?php
			  		$query = 'SELECT Location.`LocationID`, `Location`.`Place` , `Location`.`Lat` , `Location`.`Lng` , Count( `Excursion`.`When` ) AS Excursions'
							. ' FROM Location'
							. ' LEFT JOIN Excursion ON Location.LocationID = Excursion.LocationID'
							. ' GROUP BY `Location`.`Place`'
							. ' ORDER BY `Location`.`Place` ASC'
							. ' LIMIT 0 , 30'; 
					$result = mysql_query($query);
					if(!$result) die("Database Access failed: " . mysql_error());
					$rowCount = mysql_num_rows($result);
					if($rowCount > 0)
					{
						$index = 0;
						while($row=mysql_fetch_row($result))
						{							
							$excursionQuery = "SELECT * FROM `Excursion` WHERE LocationID = '$row[0]'";
							$excursionResult = mysql_query($excursionQuery);
							$excursionRowCount = mysql_num_rows($excursionResult);
							echo <<<VARIABLESET
							var when = [];
							var siteLink = [];
VARIABLESET;
							if($excursionRowCount > 0)
							{
								$excIndex = 0;
								while($excursionRow=mysql_fetch_row($excursionResult))
								{
									echo <<<EXCURSIONLOAD
									when.push("$excursionRow[2]");
									siteLink.push("$excursionRow[3]");
									
EXCURSIONLOAD;
								}
							}
							$locID[$index] = $row[0];
							echo <<<MYFETCH
							var place = "$row[1]";
							var lat = "$row[2]";
							var lng = "$row[3]";
							var excursionCount = "$row[4]";
							
		
							var marker = createMarker(place,lng,lat,when,siteLink,excursionCount);
							markers.push(marker);    
MYFETCH;
							$index++;
						}
					}
			?>
			markerManager.addMarkers(markers, 0);
			markerManager.refresh();
		}
        catch(e)
        {
            alert(e.message);
            return;
        } 
    }
	function createMarker(place, lng, lat, when, siteLink, excursionCount)
    {    
        var marker = new GMarker(new GLatLng(lat, lng), {title: place, icon: G_DEFAULT_ICON });
        var contents = "<font face=\"Arial\" size=\"3\">" + place + "</font><br><br>";
		for(var index = 0; index < excursionCount; index++)
		{
              contents = contents + "<font face=\"Arial\" size=\"2\">"
    		  if(siteLink[index] != null && siteLink[index] != "")
              	contents = contents +  "<a href=\"" + siteLink[index] + "\" target=\"_blank\">"
              contents += when[index];
      		  if(siteLink[index] != null && siteLink[index] != "")
				contents = contents +  "</a>";         
              contents = contents + "</font><BR>";
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