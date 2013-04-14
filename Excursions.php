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
		$locID = intval(sanitizeString($_REQUEST["LocationID"]));
   		$query = "SELECT `When`, `Website` FROM Excursion WHERE LocationID='$locID' ORDER BY ExcursionID";
        $result = mysql_query($query);
		if(!$result) die("Database Access failed: " . mysql_error());
		$rowCount = mysql_num_rows($result);
		$xml[] ="<Excursions>";
				
		while($row=mysql_fetch_row($result))
       	{
			$xml[] ="\t<Excursion>";
			$xml[] .= "\t\t<When>$row[0]";
			$xml[] .= "\t\t</When>";
			$xml[] .= "\t\t<Website>$row[1]";
			$xml[] .= "\t\t</Website>";
			$xml[] ="\t</Excursion>";
		}
		$xml[] .="</Excursions>";
		return $xml;
	}
function sanitizeString($var)
{
	$var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}
?>
