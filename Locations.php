<?php
        require_once('login.php');
        $db_server = mysql_pconnect( $db_hostname, $db_username, $db_password);
		mysql_select_db($db_database);
        if(!$db_server)
        {
            die("Unable to connect");
        }
		$results = retrieve();
		print implode("\n", $results);
	
	function retrieve()
	{
   		$query = "SELECT * FROM Location";
        $result = mysql_query($query);
		if(!$result) die("Database Access failed: " . mysql_error());
		$rowCount = mysql_num_rows($result);
		$xml[] ="<locations>";
		
	//	$xmlDoc = document.implementation.createDocument("","",null);
		
		while($row=mysql_fetch_row($result))
       	{
			$xml[] .= "<Location>";
			$xml[] .= "<LocationID>$row[0]";
			$xml[] .= "</LocationID>";			
			$xml[] .= "<Place>$row[1]";
			$xml[] .= "</Place>";
			$xml[] .= "<Lng>$row[2]";
			$xml[] .= "</Lng>";
			$xml[] .= "<Lat>$row[3]";
			$xml[] .= "</Lat>";
			$xml[] .= "</Location>";
		}
		$xml[] .="</locations>";
		return $xml;
	}
		
?>