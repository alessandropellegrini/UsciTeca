<?php

require("config.php");

if($WP_integration) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-blog-header.php');

        if(!is_user_logged_in()) {
                header("Location: /wordpress/wp-login.php");
        }
}

if(!isset($_POST['id']))
	exit(0);

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

$query = "SELECT * FROM markers WHERE `id`='" . $_POST['id'] . "' LIMIT 1";
$result = mysql_query($query);
if (!$result) {
        die('Invalid query: ' . mysql_error());
}

$row = mysql_fetch_assoc($result);

$res = mail($row['contributore'], "Richiesta informazioni su " . $row['nome'], $_POST['messaggio'],
     "From: " . $_POST['sender'] . "\r\n" .
     "X-Mailer: PHP/" . phpversion());

if(!res) {
	$msg = urlencode("Errore durante l'invio dell'email al contatto...");
	header("Location: message.php?t=e&m=" . $msg);
} else {
	$msg = urlencode("Messaggio inviato con successo.");
	header("Location: message.php?t=s&m=" . $msg);
}
	

?>
