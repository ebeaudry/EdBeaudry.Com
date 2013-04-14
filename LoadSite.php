<?php
if( isset($_POST['Website']))
{
	$site = $_POST['Website'];
	echo '<Site>'
	echo '<iframe src="$site" style="width:100%;height:100%" frameborder=0 style="border:none"/>';
	echo '</Site>'
}

function setIFrameContent( contentSource )
{
	document.getElementById("content").src = contentSource;
}
?>