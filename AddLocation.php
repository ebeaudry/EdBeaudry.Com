<?php
$ip=$_SERVER['REMOTE_ADDR'];


echo <<<_HEADOFFILE

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My Map</title>
<link href="MyPlaces.css" rel="stylesheet" type="text/css" />
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA5W9ruZclIRl_fiBa9_5iuBQxwQGTEBR-fHSwFRO1OjEdBrj1jhSoE3usZ6dWu0z7nGP4oEyA7g8RFw"
      type="text/javascript"></script>
      <script src="http://gmaps-utility-library.googlecode.com/svn/trunk/markermanager/release/src/markermanager.js" type="text/javascript">
      </script>
    <script type="text/javascript" src="LocationAdder.php">
    </script>
    </head>
        <body class="body" onload="load()" onUnload="GUnload()" >
<table class="default" align="center" >

_HEADOFFILE;
		
        require_once('login.php');
        $db_server = mysql_pconnect( $db_hostname, $db_username, $db_password);
		mysql_select_db($db_database);
        if(!$db_server)
        {
            die("Unable to connect");
        }

if( isset($_POST['city']))
	$city = sanitizeString( $_POST['city'] );
if( isset($_POST['lng']))
	$lng = sanitizeString( $_POST['lng'] );
if( isset($_POST['lat'])) 
	$lat = sanitizeString( $_POST['lat'] );
if( isset($_POST['location']) && $city != "" && $lng != "" && $lat != "")
{
	$location = sanitizeString( $_POST['location'] );
	$query = "SELECT Place, Lng, Lat FROM Location WHERE Place = '$city' AND Lng = '$lng' AND Lat = '$lat'";
	$result = mysql_query($query);
    if(!$result){die("Database Access failed: " . mysql_error());}
    $rowCount = mysql_num_rows($result);
    if($rowCount == 0)
    {
        $query = "INSERT INTO Location(Place, Lng, Lat) VALUES('$city', '$lng', '$lat')";
        $result = mysql_query($query);
        if(!$result){die("Database Access failed: " . mysql_error());}
    }
    unset($_POST['location']);
}
if( isset($_POST['when']))
{
	$when = sanitizeString($_POST['when']);
    unset($_POST['when']);
}
if( isset($_POST['website']))
{
	$website = sanitizeString($_POST['website']);
    unset($_POST['website']);
}   
if($when != null)
{
	$query = "SELECT 'LocationID', 'When', 'Website' FROM Excursion WHERE 'When' = '$when' AND 'Website' = '$website'";
	$result = mysql_query($query);
    if(!$result){die("Database Access failed: " . mysql_error());}
    $rowCount = mysql_num_rows($result);
    if($rowCount == 0)
    {
		$query = "INSERT INTO `Excursion` (`LocationID`, `When`, `Website`) VALUES ('$location', '$when', '$website')";
		$result = mysql_query($query);
		if(!$result) die("Database Access failed: " . mysql_error());
    }
}
echo <<<_MAINCODE
	<tr>
		<td width="896" height="41" align="center" valign="top">
            <h1>Add A New Location</h1>
          		<div id="map1" style="width:1000px;height:600px" align="center"/>
       </td>
  </tr>
<tr>
    	<td>
    	<table align="center">
_MAINCODE;
            if($ip == '209.6.244.224')
			{
            echo <<<_FORM
          <form name="mainForm" method="post">
       	  <tr>
          <tr align="left">
                    <input type="hidden" name="lng"/>
                    <input type="hidden" name="lat"/>
        		<td>
        			When: 
                </td>
                <td>
                <input type="text" name="when" />
                </td>
                </tr>
                <tr align="left">
                <td>
                	Website: 
                </td>
                <td>
                <input type="text" name="website"/>
                </td>
                </tr>
                <tr>
                <td>
                	<input type="button" value="Add Excursion" onclick="AddExcursion()" />
                    <td>
                </tr>
_FORM;
			}

        echo <<<_MAP
          </tr>
          <tr>
		  </tr>
          </tr>
        </table>
        </td>
        </tr>
        <tr>
        <td>
_MAP;

   //     $table="Location";
   		$query = "SELECT * FROM Location";
        $result = mysql_query($query);
		if(!$result) die("Database Access failed: " . mysql_error());
		$rowCount = mysql_num_rows($result);
		if($rowCount > 0)
		{
			echo '<table align="center" border="1" >';
            if($ip == '209.6.244.224')
			{
            	echo '<th></th>';
			}
			echo '<th>City</th>';
			echo '<th>Longitude</th>';
			echo '<th>Latitude</th>';
            $index = 1;
			while($row=mysql_fetch_row($result))
        	{
            
				echo '<tr>';
				if($ip == '209.6.244.224')
				{
                	echo '<td align="center"><input type="radio" name="location" value="';
                    echo $row[0];
                    echo '"';
                    if($index == 1)
                    {
                    	echo ' checked="checked"';
                    }
                    echo '/></td>';
                }
				echo <<<MYFETCH
				<td align="center">$row[1]</td>
				<td align="center">$row[2]</td>
				<td align="center">$row[3]</td>
				</tr>
MYFETCH;
                $index++;
			}
           	if($ip == '209.6.244.224')
			{
               	echo '<td align="center"><input type="radio" name="location" value="new" /></td>';
                echo '<td align="center"><input type="text" name="city"/></td>';
                echo '<td align="center"><input type="button" value="Add Location" onclick="Retrieve()"/></td>';
                echo '</form>';
            }
			echo '</table>';
		}
		mysql_close($db_server);	
        echo <<<END
        </td>
    </tr>    
</table></body>
</html>
END;
function sanitizeString($var)
{
	$var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}
?>