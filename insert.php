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

$title = 'inserimento di un nuovo posto';
$nome = "";
$indirizzo = "";
$luogo = "";
$provincia = "";
$contatto = "";
$telefono = "";
$cellulare = "";
$email = "";
$is_tenda = "";
$is_accantonamento = "";
$is_fuoco = "";
$is_acqua = "";
$is_campo = "";
$is_gratis = "";
$is_gioco = "";
$is_ombra = "";
$posti = "numero posti";
$posti_enabled = "disabled";


if(isset($_GET['update'])) {
	if(!isset($_GET['id'])) {
		$err_str = urlencode("Id per l'aggiornamento non specificato");
		header("Location: message.php?t=e&m=$err_str");
		exit();
	}

	// Opens a connection to a MySQL server
	$connection=mysql_connect ($dbhost, $username, $password);
	if (!$connection) {
		$err_str = urlencode("Non connesso:" . mysql_error());
		header("Location: message.php?t=e&m=$err_str");
		exit();
	}
	
	// Set the active MySQL database
	$db_selected = mysql_select_db($database, $connection);
	if (!$db_selected) {
		$err_str = urlencode("Impossibile utilizzare il db: " . mysql_error());
		header("Location: message.php?t=e&m=$err_str");
		exit();
	}
	
	// Select all the rows in the markers table
	$query = "SELECT * FROM markers WHERE id = '" . $_GET['id'] . "'";
	$result = mysql_query($query);
	if (!$result) {
		$err_str = urlencode("Query non valida: " . mysql_error());
		header("Location: message.php?t=e&m=$err_str");
		exit();
	}
	if (mysql_num_rows($result) == 0) {
		$err_str = urlencode("ID non valido: " . $_GET['id']);
		header("Location: message.php?t=e&m=$err_str");
		exit();
	}

	// C'è solo una riga qui!
	$row = mysql_fetch_array($result);

	$nome = $row['nome'];
	$indirizzo = $row['indirizzo'];
	$luogo = $row['luogo'];
	$provincia = $row['provincia'];
	$contatto = $row['contatto'];
	$telefono = $row['telefono'];
	$cellulare = $row['cellulare'];
	$email = $row['email'];
	if($row['tipo'] == 'tenda') {
		$is_tenda = 'checked';
	} else if($row['tipo'] == 'accantonamento') {
		$is_accantonamento = 'checked';
		$posti_enabled = '';
		$posti = $row['posti'];
	} else if($row['tipo'] == 'entrambi') {
		$is_tenda = 'checked';
		$is_accantonamento = 'checked';
		$posti_enabled = '';
		$posti = $row['posti'];
	}

	if($row['fuoco'] == 1) $is_fuoco = 'checked';
	if($row['acqua'] == 1) $is_acqua = 'checked';
	if($row['campo'] == 1) $is_campo = 'checked';
	if($row['gratis'] == 1) $is_gratis = 'checked';
	if($row['gioco'] == 1) $is_gioco = 'checked';
	if($row['ombra'] == 1) $is_ombra = 'checked';

	mysql_close($connection);

	$title = "aggiornamento di <em>$nome</em>";

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>UsciTeca - Zona Cassiopea</title>
    <link href="style2.css" rel="stylesheet" type="text/css" media="screen" />

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
        var map;
        var markersArray = [];
	var geocoder;

        function initMap()
        {
	    geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(41.90,12.45);
            var myOptions = {
                zoom: 9,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	    <?php
		if(isset($_GET['update']))  {
		// This is placed here only if we are updating
			echo "var updatePosition = new google.maps.LatLng(" . $row['lat'] . ", " . $row['lng'] . ");\n";
			echo "placeMarker(updatePosition);\n";
			echo "document.getElementById(\"latFld\").value = updatePosition.lat();\n";
			echo "document.getElementById(\"lngFld\").value = updatePosition.lng();\n";
		}
	    ?>

            // add a click event handler to the map object
            google.maps.event.addListener(map, "click", function(event)
            {
                // place a marker
                placeMarker(event.latLng);

                // display the lat/lng in your form's lat/lng fields
                document.getElementById("latFld").value = event.latLng.lat();
                document.getElementById("lngFld").value = event.latLng.lng();

		// Reverse geocode
		reverse(event.latLng);
            });
        }
        function placeMarker(location) {
            // first remove all markers if there are any
            deleteOverlays();

            var marker = new google.maps.Marker({
                position: location, 
                map: map
            });

            // add marker in markers array
            markersArray.push(marker);

            //map.setCenter(location);
        }

        // Deletes all markers in the array by removing references to them
        function deleteOverlays() {
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
            markersArray.length = 0;
            }
        }

	// Reverse geocode
	function reverse(latlng) {
	  var newLuogo = "";
	  var provincia = "";
	  var indirizzo = "";
	  var civico = "";

	  geocoder.geocode({'latLng': latlng}, function(results, status) {
	    if (status == google.maps.GeocoderStatus.OK) {

	      // Tenta di riconoscere la località
	      if (results[1]) {
		newLuogo = results[1].formatted_address;

	        // Tenta di riempire anche la provincia
		for (i = 0; i < results[1].address_components.length; i++) {
		    if(results[1].address_components[i].types[0] == "administrative_area_level_2") {
		       provincia = results[1].address_components[i].short_name;
		    }
		}
		document.getElementById("provincia").value = provincia;

		// Tenta di riempire anche l'indirizzo
		for (j = 0; j < results.length; j++) {
			for (i = 0; i < results[j].address_components.length; i++) {
			    if(results[j].address_components[i].types[0] == "route") {
			       indirizzo = results[j].address_components[i].long_name;
			       if(indirizzo == "Unnamed Road")
					indirizzo = "Strada Senza Nome";
			    }
			    if(results[j].address_components[i].types[0] == "street_number") {
			       civico = ", " + results[j].address_components[i].long_name;
			    }
			}
		}
		document.getElementById("indirizzo").value = indirizzo + civico;

	      } else {
	        newLuogo = "Località Sconosciuta";
	      }
	    } else {
	      newLuogo = "Errore: " + status;
	    }

		// Update the field
		document.getElementById("luogo").value = newLuogo;
	  });
	}

	// Center map on a user's query result, update marker and update information in the form
	function findPlace() {
	  var address = document.getElementById('address').value;
	  geocoder.geocode( { 'address': address}, function(results, status) {
	    if (status == google.maps.GeocoderStatus.OK) {
	      map.setCenter(results[0].geometry.location);
	      placeMarker(results[0].geometry.location);
	      document.getElementById("latFld").value = results[0].geometry.location.lat();
	      document.getElementById("lngFld").value = results[0].geometry.location.lng();
	      reverse(results[0].geometry.location);
	    } else {
	      alert('Errore nel recupero della località: ' + status);
	    }
	  });
	}

	// Lasciamo inserire i posti solo se il luogo è in accantonamento
	function enable_posti(status) {
	    status = (status) ? false : true;
	    document.getElementById("acc_posti").disabled = status;
	    if(!status) {
		 document.getElementById("acc_posti").value = "";
		 document.getElementById("acc_posti").focus();
	    } else {
		  document.getElementById("acc_posti").value = "numero posti";
	    }
	}

        // Prevalidazione del form
	function validateForm() {
	    var latFld = document.forms["informazioni"]["latFld"].value;
	    var lngFld = document.forms["informazioni"]["lngFld"].value;
	    var contatto = document.forms["informazioni"]["contatto"].value;
	    var telefono = document.forms["informazioni"]["telefono"].value;
	    var cellulare = document.forms["informazioni"]["cellulare"].value;
	    var email = document.forms["informazioni"]["email"].value;
	    var tenda = document.forms["informazioni"]["tenda"].checked;
	    var accantonamento = document.forms["informazioni"]["accantonamento"].checked;
	    var acc_posti = document.forms["informazioni"]["acc_posti"].value;

	    var correct = true;

	    // Reset dei messaggi di errore
		document.getElementById("err_indirizzo").innerHTML = "";
		document.getElementById("err_contatto").innerHTML = "";
		document.getElementById("err_telefono").innerHTML = "";
		document.getElementById("err_cellulare").innerHTML = "";
		document.getElementById("err_email").innerHTML = "";
		document.getElementById("err_tipologia").innerHTML = "";
		document.getElementById("err_tipologia").innerHTML = "";

<?php
if(!isset($_GET['update'])) {
?>
		document.getElementById("err_mittente").innerHTML = "";
		var mittente = document.forms["informazioni"]["mittente"].value;
		// Valida l'email del mittente
            if(mittente == null || mittente == "") {
                    var atpos = mittente.indexOf("@");
                    var dotpos = mittente.lastIndexOf(".");
                    if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=mittente.length) {
                        document.getElementById("err_mittente").innerHTML = "Indirizzo email non valido";
                        correct = false;
                    }
            }
<?php
}
?>

	    // Si deve cliccare sulla mappa!
            if(latFld == null || latFld == "" || lngFld == null || lngFld == "") {
		document.getElementById("err_indirizzo").innerHTML = "&Egrave; necessario cliccare sulla mappa per individuare il luogo";
		correct = false;
	    }

	    // Il contatto è necessario
	    if (contatto == null || contatto == "") {
		document.getElementById("err_contatto").innerHTML = "&Egrave; necessario inserire la persona di contatto";
	        correct = false;
	    }

            // Almeno uno tra email, telefono e cellulare
	    if ((telefono == null || telefono == "") && (cellulare == null || cellulare == "") && (email == null || email == "")) {
		document.getElementById("err_telefono").innerHTML = "&Egrave; necessario inserire almeno un contatto";
		document.getElementById("err_cellulare").innerHTML = "&Egrave; necessario inserire almeno un contatto";
		document.getElementById("err_email").innerHTML = "&Egrave; necessario inserire almeno un contatto";
	        correct = false;
	    }

	    // Il numero di telefono deve contenere solo numeri
	    if(telefono != null && telefono != "" && !telefono.match(/^\d+$/)) {
	        document.getElementById("err_telefono").innerHTML = "Il campo pu&ograve; contenere solo numeri";
		correct = false;
	    }

	    // Il cellulare pure deve contenere solo numeri
	    if(cellulare != null && cellulare != "" && !cellulare.match(/^\d+$/)) {
	        document.getElementById("err_cellulare").innerHTML = "Il campo pu&ograve; contenere solo numeri";
	        correct = false;
	    }

	    // Almeno o in tenda o accantonamento, sennò che me lo mandi a fare?!
            if (!tenda && !accantonamento) {
		document.getElementById("err_tipologia").innerHTML = "Il posto inviato deve essere o in tenda o in accantonamento";
		correct = false;
	    }

	    // Se è in accantonamento, è necessario inserire il numero di posti disponibili
            if (accantonamento && (acc_posti == null || acc_posti == "" || acc_posti == "numero posti")) {
		document.getElementById("err_tipologia").innerHTML = "&Egrave; necessario specificare il numero di posti in accantonamento";
		correct = false;
	    }

	    // Valida l'email del posto
	    if(email != null && email != "") {
		    var atpos = email.indexOf("@");
		    var dotpos = email.lastIndexOf(".");
		    if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=email.length) {
			document.getElementById("err_email").innerHTML = "Indirizzo email non valido";
	        	correct = false;
		    }
	    }

	    return correct;
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

<h1>Proponi l'<?= $title ?></h1>
<div>
<p align="center">
Effettua una ricerca:
<input id="address" type="textbox" value="Roma">
<input type="button" value="Trova sulla Mappa" onclick="findPlace()"><br/>
</p>
</div>
<div id="map_canvas"></div>
<form name="informazioni" action="confirm.php" method="post" onsubmit="return validateForm()">
<input type="hidden" id="latFld" name="latFld">
<input type="hidden" id="lngFld" name="lngFld">
<h2>Informazioni:</h2>
<table class="insert-table">
<tr><td>Nome:</td><td><input maxlength="30" size="70" type="text" id="nome" name="nome" value="<?= $nome ?>"></td><td class="err" id="err_nome"></td></tr>
<tr><td>Indirizzo:</td><td><input maxlength="30" size="70" type="text" id="indirizzo" name="indirizzo" value="<?= $indirizzo ?>"\></td><td class="err" id="err_indirizzo"></td></tr>
<tr><td>Localit&agrave;:</td><td><input maxlength="30" size="70" type="text" id="luogo" name="luogo" value="<?= $luogo ?>"></td><td class="err" id="err_luogo"></td></tr>
<tr><td>Provincia:</td><td><input maxlength="2" size="70" type="text" id="provincia" name="provincia" value="<?= $provincia ?>"></td><td class="err" id="err_provincia"></td></tr>
<tr><td>Contatto:</td><td><input maxlength="30" size="70" type="text" id="contatto" name="contatto" value="<?= $contatto ?>"></td><td class="err" id="err_contatto"></td></tr>
<tr><td>Telefono:</td><td><input maxlength="10" size="70" type="text" id="telefono" name="telefono" value="<?= $telefono ?>"></td><td class="err" id="err_telefono"></td></tr>
<tr><td>Cellulare:</td><td><input maxlength="10" size="70" type="text" id="cellulare" name="cellulare" value="<?= $cellulare ?>"></td><td class="err" id="err_cellulare"></td></tr>
<tr><td>Email:</td><td><input maxlength="30" size="70" type="text" id="email" name="email" value="<?= $email ?>"></td><td class="err" id="err_email"></td></tr>
<tr><td>Tipologia:</td><td><input type="checkbox" name="tenda" value="1" <?= $is_tenda ?>/>T -
		       <input type="checkbox" name="accantonamento" value="1" onclick="enable_posti(this.checked)" <?= $is_accantonamento ?>/>A
		       <input type="text" id="acc_posti" maxlength="3" name="acc_posti" value="<?= $posti ?>" <?= $posti_enabled ?>> </td><td class="err" id="err_tipologia"></td></tr>
<tr><td>Informazioni:</td><td>
	<input type="checkbox" name="fuoco" value="1" <?= $is_fuoco ?>><img src="img/fire.png" title="Accensione fuoco"/>&nbsp;&nbsp;
	<input type="checkbox" name="acqua" value="1" <?= $is_acqua ?>><img src="img/water-drop.png" title="Acqua potabile"/>&nbsp;&nbsp;
	<input type="checkbox" name="campo" value="1" <?= $is_campo ?>><img src="img/tent.png" title="Per campi"/>&nbsp;&nbsp;
	<input type="checkbox" name="gratis" value="1" <?= $is_gratis ?>><img src="img/no-euro.png" title="Gratuito"/>&nbsp;&nbsp;
	<input type="checkbox" name="gioco" value="1" <?= $is_gioco ?>><img src="img/gioco.png" title="Spazio Gioco"/>&nbsp;&nbsp;
	<input type="checkbox" name="ombra" value="1" <?= $is_ombra ?>><img src="img/ombra.png" title="Spazio Ombra"/>&nbsp;&nbsp;
</td><td class="err" id="err_informazioni"></td></tr>
<?php

if(!isset($_GET['update'])) {

echo '<tr><td>Email Mittente:</td><td><input size="50" type="text" id="mittente" name="mittente" value=""></td><td class="err" id="err_mittente"></td></tr>' . "\n";

}
?>
<tr><td colspan="2" align="center"><input type="submit" value="Invia"></td></tr>
</table>
<div id="finale" style="display:none">
Se vedi questo campo, lascialo vuoto, aggiorna il browser, e dona qualche euro allo sviluppo del CSS nei browser!
<input type="text" name="finale" value="" />
</div>
<input type="hidden" name="aggiornamento" value="<?= isset($_GET['update']) ?>" />
<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
</form>
</div>
    </div>
    </main>
<footer>
         Copyright &copy; <?= date("Y") ?> - <a href="http://www.pellegrini.tk" target="_blank"> Alessandro Pellegrini </a> - <a href="https://github.com/alessandropellegrini/UsciTeca">sources</a> on GitHub
</footer>

</body>
</html>
