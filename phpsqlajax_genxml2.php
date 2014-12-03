<?php
require("config.php");

function parseToXML($htmlStr) {
	$xmlStr=str_replace('<','&lt;',$htmlStr);
	$xmlStr=str_replace('>','&gt;',$xmlStr);
	$xmlStr=str_replace('"','&quot;',$xmlStr);
	$xmlStr=str_replace("'",'&#39;',$xmlStr);
	$xmlStr=str_replace("&",'&amp;',$xmlStr);
	return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysql_connect ($dbhost, $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}


// Purge unconfirmed places, which are older that 24 hours
$query = "DELETE FROM `markers` WHERE `pending` = '1' AND `inserimento` < ADDDATE(NOW(), INTERVAL -24HOUR)";
mysql_query($query);

// Select all the rows in the markers table
$query = "SELECT * FROM markers WHERE `pending`='0'";
$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
	// ADD TO XML DOCUMENT NODE
	echo '<marker ';
  	echo 'id="' . $row['id'] . '" ';
  	echo 'lat="' . $row['lat'] . '" ';
  	echo 'lng="' . $row['lng'] . '" ';
  	echo 'nome="' . parseToXML($row['nome']) . '" ';
  	echo 'indirizzo="' . parseToXML($row['indirizzo']) . '" ';
  	echo 'luogo="' . parseToXML($row['luogo']) . '" ';
  	echo 'provincia="' . parseToXML($row['provincia']) . '" ';
  	echo 'contatto="' . parseToXML($row['contatto']) . '" ';
  	echo 'telefono="' . $row['telefono'] . '" ';
  	echo 'cellulare="' . $row['cellulare'] . '" ';
  	echo 'email="' . parseToXML($row['email']) . '" ';
  	echo 'tipo="' . $row['tipo'] . '" ';
  	echo 'posti="' . $row['posti'] . '" ';
  	echo 'fuoco="' . $row['fuoco'] . '" ';
  	echo 'acqua="' . $row['acqua'] . '" ';
  	echo 'campo="' . $row['campo'] . '" ';
  	echo 'gratis="' . $row['gratis'] . '" ';
  	echo 'gioco="' . $row['gioco'] . '" ';
  	echo 'ombra="' . $row['ombra'] . '" ';
  	echo '/>';
}

// End XML file
echo '</markers>';

?>
