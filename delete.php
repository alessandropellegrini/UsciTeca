<?php
require("config.php");

if($WP_integration) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-blog-header.php');

        if(!is_user_logged_in()) {
                header("Location: /wordpress/wp-login.php");
        }
}
?>
<?php

// Create connection
$conn = new mysqli($dbhost, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM `markers` WHERE `id`='" . $_GET['id'] . "';";

if ($conn->query($sql) === TRUE) {
	$err_str = urlencode("Eliminazione eseguita con successo.");
	header("Location: message.php?t=s&m=$err_str");
} else {
	$err_str = urlencode("Si &egrave; verificato un errore durante l'eliminazione. Per favore, contatta gli amministratori del sito!<br/><br/>Informazioni sull'errore: " . $conn->error);
	header("Location: message.php?t=e&m=$err_str");
}

$conn->close();

?>
