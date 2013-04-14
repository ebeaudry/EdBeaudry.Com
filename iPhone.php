<?php
//require_once('RetrieveAllLocations.php');
echo <<<_TOP
	<!DOCTYPE html>
<html>
  <head>
    <title>Where We've Been</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <link href="maps.css"
        rel="stylesheet" type="text/css">
    <script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBaodiJBTasScRpDF3OtDR8hcf5Te8XWVE&sensor=true">
    </script>
    <script type="text/javascript">
      function initialize() {
        var myOptions = {
          center: new google.maps.LatLng(0, 0),
          zoom: 1,
          zoomControlOptions: {
          	style: google.maps.ZoomControlStyle.SMALL 
          },
          mapTypeControlOptions: {
      		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    		},
          mapTypeId: google.maps.MapTypeId.ROADMAP
		  
        };
        var map = new google.maps.Map(document.getElementById('map_canvas'),
            myOptions);
		    
		marker = new google.maps.Marker({
		  map:map,
		  draggable:true,
		  animation: google.maps.Animation.DROP,
		  position: new google.maps.LatLng(0, 0)
	    });
    
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body onload="initialize()">

          		 <div id="map_canvas"></div>

  </body>
</html>
_TOP;

?>