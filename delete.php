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
    echo '<html><head><meta http-equiv="refresh" content="5; url=index.php" /></head><body>Eliminazione eseguita con successo. Verrai reindirizzato alla <a href="index.php">pagina principale</a> tra 5 secondi...</body></html>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
