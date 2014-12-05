<?php
require("config.php");

if($WP_integration) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-blog-header.php');

	if(!is_user_logged_in()) {
		header("Location: /wordpress/wp-login.php");
	}
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
$query = "SELECT * FROM markers WHERE `id`='" . $_GET['id'] . "'";
$result = mysql_query($query);
if (!$result) {
        die('Invalid query: ' . mysql_error());
}

$row = mysql_fetch_assoc($result);

$tipo = '';
if(isset($row['tenda']) && !isset($row['accantonamento'])) {
        $tipo = 'T';
} else if(!isset($row['tenda']) && isset($row['accantonamento'])) {
        $tipo = 'A (' . $row['posti'] . ')';
} else {
        $tipo = "T - A (" . $row['posti'] . ")";
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>UsciTeca - Zona Cassiopea</title>
    <link href="style2.css" rel="stylesheet" type="text/css" media="screen" />

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
        function initMap()
        {
	    var geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(<?= $row['lat'] ?>,<?= $row['lng'] ?>);
            var myOptions = {
                zoom: 9,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            var marker = new google.maps.Marker({
                position: latlng, 
                map: map
            });
        }
  </script>
  </head>
  <body onload="initMap()">
        <main>
            <header>
                <h1>
                <img src="img/tenda.jpg" height="150"/>
                UsciTeca - Zona Cassiopea
                <img src="img/tenda.jpg" height="150"/>
                </h1>
            </header>
    <div id="central">
    <div id="content">
    <h1>Scheda Posto <em><?= $row['nome'] ?></em></h1>
    <div id="map_canvas"></div>
    <h2>Informazioni:</h2>
    <table>
    <tr><td>Nome:</td><td><?= $row['nome'] ?></td></tr>
    <tr><td>Indirizzo:</td><td><?= $row['indirizzo'] ?></td></tr>
    <tr><td>Localit&agrave;:</td><td><?= $row['luogo'] ?></td></tr>
    <tr><td>Provincia:</td><td><?= $row['provincia'] ?></td></tr>
    <tr><td>Contatto:</td></td><td><?= $row['contatto'] ?></td></tr>
    <tr><td>Telefono:</td><td><?= $row['telefono'] ?></td></tr>
    <tr><td>Cellulare:</td><td><?= $row['cellulare'] ?></td></tr>
    <tr><td>Email:</td><td><a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a></td></tr>
    <tr><td>Tipologia:</td><td><?= $tipo ?></td></tr>
    <tr><td>Informazioni:</td><td>
	<?php
		if(isset($row['fuoco'])) echo '<img src="img/fire.png" />';
		if(isset($row['acqua'])) echo '<img src="img/water-drop.png" />';
		if(isset($row['campo'])) echo '<img src="img/tent.png" />';
		if(isset($row['gratis'])) echo '<img src="img/no-euro.png" />';
		if(isset($row['gioco'])) echo '<img src="img/gioco.png" />';
		if(isset($row['ombra'])) echo '<img src="img/ombra.png" />';
	?>
    </td></tr>
    <tr><td colspan="2" align="center"><input type="button" value="Torna Indietro" onclick="window.history.back()"/></td></tr>
    </table>
    <input type="hidden" name="tenda" value="<?= isset($row['tenda']) ?>">
    <input type="hidden" name="accantonamento" value="<?= isset($row['accantonamento']) ?>">
    <input type="hidden" name="acc_posti" value="<?= $posti ?>">
    <input type="hidden" name="aggiornamento" value="<?= $row['aggiornamento'] ?>">
    <input type="hidden" name="fuoco" value="<?= isset($row['fuoco']) ?>">
    <input type="hidden" name="acqua" value="<?= isset($row['acqua']) ?>">
    <input type="hidden" name="campo" value="<?= isset($row['campo']) ?>">
    <input type="hidden" name="gratis" value="<?= isset($row['gratis']) ?>">
    <input type="hidden" name="gioco" value="<?= isset($row['gioco']) ?>">
    <input type="hidden" name="ombra" value="<?= isset($row['ombra']) ?>">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    </div>
    </div>
    </main>
<footer>
         Copyright &copy; <?= date("Y") ?> - <a href="http://www.pellegrini.tk" target="_blank"> Alessandro Pellegrini </a> - <a href="https://github.com/alessandropellegrini/UsciTeca">sources</a> on GitHub
</footer>

  </body>
</html>
