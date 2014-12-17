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


if(!isset($_POST) && !isset($_POST['latFld']))
	die('Non si può accedere direttamente a questa pagina');

$lat = $_POST['latFld'];
$lng = $_POST['lngFld'];
$nome = $_POST['nome'];
$indirizzo = $_POST['indirizzo'];
$luogo = $_POST['luogo'];
$provincia = $_POST['provincia'];
$contatto = $_POST['contatto'];
$telefono = $_POST['telefono'];
$cellulare = $_POST['cellulare'];
$email = $_POST['email'];
$tipo = "";
if(isset($_POST['acc_posti'])) {
	$posti = $_POST['acc_posti'];
} else {
	$posti = 0;
}

$fuoco=0;
$acqua=0;
$campo=0;
$gratis=0;
$gioco=0;
$ombra=0;

/*
 * Conferma effettuata: esegui l'inserimento nel database
 */
if(isset($_POST['seed']) && isset($_GET['confirm']) && $_POST['seed'] == $_GET['confirm']) {


	if($_POST['tenda']  == "1" && $_POST['accantonamento'] == "") {
	        $tipo = 'tenda';
	} else if($_POST['tenda']  == "" && $_POST['accantonamento'] == "1") {
	        $tipo = 'accantonamento';
	} else {
	        $tipo = 'entrambi';
	}

	if($_POST['fuoco'] == 1) $fuoco = 1;
	if($_POST['acqua'] == 1) $acqua = 1;
	if($_POST['campo'] == 1) $campo = 1;
	if($_POST['gratis'] == 1) $gratis = 1;
	if($_POST['gioco'] == 1) $gioco = 1;
	if($_POST['ombra'] == 1) $ombra = 1;


	// Create connection
	$conn = new mysqli($dbhost, $username, $password, $database);
	// Check connection
	if ($conn->connect_error) {
		$err_str = urlencode("Connessione fallita: " . $conn->connect_error);
		header("Location: message.php?t=e&m=$err_str");
		exit();
	} 

	if($_POST['aggiornamento'] == "") {
		$sql = "INSERT INTO `markers` (`lat`, `lng`, `nome`, `indirizzo`, `luogo`, `provincia`, `contatto`, `telefono`, `cellulare`, `email`, `tipo`, `posti`, `fuoco`, `acqua`, `campo`, `gratis`, `gioco`, `ombra`, `pending`, `inserimento`, `aggiornamento`, `contributore`) VALUES ('" . $_POST['latFld'] . "','" . $_POST['lngFld'] . "','" . $nome . "','" . $indirizzo . "','" . $luogo . "','" . $provincia ."','" . $contatto . "','" . $telefono . "','" . $cellulare . "','" . $email . "','" . $tipo . "','". $posti . "','" . $fuoco  . "','" . $acqua . "','" . $campo . "','" . $gratis ."','" . $gioco . "','" . $ombra . "', '0', null, null, '" . $_POST['mittente'] . "')";
	} else {
		$sql = "UPDATE `markers` SET `lat`='" . $_POST['latFld'] . "', `lng`='" . $_POST['lngFld'] . "', `nome`='" . $nome . "', `indirizzo`='" . $indirizzo . "', `luogo`='" . $luogo . "', `provincia`='" . $provincia ."', `contatto`='" . $contatto . "', `telefono`='" . $telefono . "', `cellulare`='" . $cellulare . "', `email`='" . $email . "', `tipo`='" . $tipo . "', `posti`='". $posti . "', `fuoco`='" . $fuoco . "', `acqua`='".$acqua."', `campo`='".$campo."', `gratis`='".$gratis."', `gioco`='".$gioco."', `ombra`='" .$ombra . "' WHERE id='" . $_POST['id'] . "';";
	}
	
	if ($conn->query($sql) === TRUE) {
	    if($_POST['aggiornamento'] == "") {

		$msg_str = urlencode("Nuovo posto per uscite aggiunto con successo.");
                header("Location: message.php?t=s&m=$msg_str");
                exit();
	    } else {
		$msg_str = urlencode("Aggiornamento del database eseguito con successo.");
                header("Location: message.php?t=s&m=$msg_str");
                exit();
	    }
	} else {
		$msg_str = urlencode("Errore:" . $sql . "<br/>" . $conn->error);
                header("Location: message.php?t=e&m=$msg_str");
                exit();
	}
	
	$conn->close();
	exit();
}



/*
 * Mostra il modulo di conferma
 */
if(!isset($_POST['finale']) || $_POST['finale'] != "")
	die('Stai facendo qualcosa di male...');

$seed = rand();


if(isset($_POST['tenda']) && !isset($_POST['accantonamento'])) {
        $tipo = 'T';
} else if(!isset($_POST['tenda']) && isset($_POST['accantonamento'])) {
        $tipo = 'A (' . $_POST['acc_posti'] . ')';
} else {
        $tipo = "T - A (" . $_POST['acc_posti'] . ")";
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
            var latlng = new google.maps.LatLng(<?= $_POST['latFld'] ?>,<?= $_POST['lngFld'] ?>);
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
    <h1>Rivedi e Conferma</h1>
    <div id="map_canvas"></div>
    <form name="informazioni" action="confirm.php?confirm=<?= $seed ?>" method="post">
    <input type="hidden" name="seed" value="<?= $seed ?>">
    <input type="hidden" name="latFld" value="<?= $lat ?>">
    <input type="hidden" name="lngFld" value="<?= $lng ?>">
    <h2>Informazioni:</h2>
    <table>
    <tr><td>Nome:</td><td><input type="hidden" name="nome" value="<?= $nome ?>"><?= $nome ?></td></tr>
    <tr><td>Indirizzo:</td><td><input type="hidden" name="indirizzo" value="<?= $indirizzo ?>"><?= $indirizzo ?></td></tr>
    <tr><td>Localit&agrave;:</td><td><input type="hidden" name="luogo" value="<?= $luogo ?>"><?= $luogo ?></td></tr>
    <tr><td>Provincia:</td><td><input type="hidden" name="provincia" value="<?= $provincia ?>"><?= $provincia ?></td></tr>
    <tr><td>Contatto:</td></td><td><input type="hidden" name="contatto" value="<?= $contatto ?>"><?=  $contatto ?></td></tr>
    <tr><td>Telefono:</td><td><input type="hidden" name="telefono" value="<?= $telefono ?>"><?= $telefono ?></td></tr>
    <tr><td>Cellulare:</td><td><input type="hidden" name="cellulare" value="<?= $cellulare ?>"><?= $cellulare ?></td></tr>
    <tr><td>Email:</td><td><input type="hidden" name="email" value="<?= $email ?>"><?= $email ?></td></tr>
    <tr><td>Tipologia:</td><td><?= $tipo ?></td></tr>
    <tr><td>Informazioni:</td><td>
	<?php
		if(isset($_POST['fuoco'])) echo '<img src="img/fire.png" />';
		if(isset($_POST['acqua'])) echo '<img src="img/water-drop.png" />';
		if(isset($_POST['campo'])) echo '<img src="img/tent.png" />';
		if(isset($_POST['gratis'])) echo '<img src="img/no-euro.png" />';
		if(isset($_POST['gioco'])) echo '<img src="img/gioco.png" />';
		if(isset($_POST['ombra'])) echo '<img src="img/ombra.png" />';
	?>
    </td></tr>
    <?php if($_POST['aggiornamento'] == "") { ?>
    <tr><td>Email Mittente:</td><td><input type="hidden" name="mittente" value="<?= $_POST['mittente'] ?>"><?= $_POST['mittente'] ?></td></tr>
    <?php } ?>
    <tr><td colspan="2" align="center"><input type="button" value="Annulla" onclick="window.history.back()"/><input type="submit" value="Conferma"></td></tr>
    </table>
    <input type="hidden" name="tenda" value="<?= isset($_POST['tenda']) ?>">
    <input type="hidden" name="accantonamento" value="<?= isset($_POST['accantonamento']) ?>">
    <input type="hidden" name="acc_posti" value="<?= $posti ?>">
    <input type="hidden" name="aggiornamento" value="<?= $_POST['aggiornamento'] ?>">
    <input type="hidden" name="fuoco" value="<?= isset($_POST['fuoco']) ?>">
    <input type="hidden" name="acqua" value="<?= isset($_POST['acqua']) ?>">
    <input type="hidden" name="campo" value="<?= isset($_POST['campo']) ?>">
    <input type="hidden" name="gratis" value="<?= isset($_POST['gratis']) ?>">
    <input type="hidden" name="gioco" value="<?= isset($_POST['gioco']) ?>">
    <input type="hidden" name="ombra" value="<?= isset($_POST['ombra']) ?>">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    </form>
    </div>
    </div>
    </main>
<footer>
         Copyright &copy; <?= date("Y") ?> - <a href="http://www.pellegrini.tk" target="_blank"> Alessandro Pellegrini </a> - <a href="https://github.com/alessandropellegrini/UsciTeca">sources</a> on GitHub
</footer>

  </body>
</html>
