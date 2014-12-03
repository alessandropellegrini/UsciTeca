<?php

require("config.php");

if(isset($_GET['id'])) {

	// Create connection
	$conn = new mysqli($dbhost, $username, $password, $database);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "UPDATE `markers` SET `pending`='0' WHERE `id`='" . $_GET['id'] . "'";

	if ($conn->query($sql) === TRUE) {
		$err_str = urlencode("Posto verificato con successo. Grazie!");
		header("Location: message.php?t=s&m=$err_str");
	} else {
		$err_str = urlencode("Si &egrave; verificato un errore nella conferma. Per favore, contatta gli amministratori del sito!<br/><br/>Informazioni sull'errore: " . $conn->error);
		header("Location: message.php?t=e&m=$err_str");
	}
	
	$conn->close();
	
}

?>
