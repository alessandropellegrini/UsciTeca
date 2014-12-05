<?php
require("config.php");

if($WP_integration) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-blog-header.php');

        if(!is_user_logged_in()) {
                header("Location: /wordpress/wp-login.php");
        }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>UsciTeca - Zona Cassiopea</title>
    <link href="style2.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
    var customIcons = {
      tenda: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_green.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      accantonamento: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_yellow.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      entrambi: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

    // Per impostare bene lo zoom
    var markerBounds;

    // Tiene traccia dei marker scaricati (serve per costruire la tabella)
    var markers;

    // Tiene traccia della mappa (per sapere quali marker sono attualmente visibili)
    var map;
    
    function load() {
      markerBounds = new google.maps.LatLngBounds();
      map = new google.maps.Map(document.getElementById("map_canvas"), {
        center: new google.maps.LatLng(41.90,12.45),
        zoom: 9,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      downloadUrl("phpsqlajax_genxml2.php", function(data) {
        var xml = data.responseXML;
        markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var nome = markers[i].getAttribute("nome");
          var luogo = markers[i].getAttribute("luogo");
          var tipo = markers[i].getAttribute("tipo");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var icon = customIcons[tipo] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });

	  var tipologia = '';
	  if(markers[i].getAttribute("tipo") == "tenda") tipologia = "T";
	  if(markers[i].getAttribute("tipo") == "accantonamento") tipologia = "A (" + markers[i].getAttribute("posti") + ")";
	  if(markers[i].getAttribute("tipo") == "entrambi") tipologia = "T - A (" + markers[i].getAttribute("posti") + ")";

	  var informazioni = '';
	  if(markers[i].getAttribute("fuoco") == 1) informazioni += '<img src="img/fire.png" />';
	  if(markers[i].getAttribute("acqua") == 1) informazioni += '<img src="img/water-drop.png" />';
	  if(markers[i].getAttribute("campo") == 1) informazioni += '<img src="img/tent.png" />';
	  if(markers[i].getAttribute("gratis") == 1) informazioni += '<img src="img/no-euro.png" />';
	  if(markers[i].getAttribute("gioco") == 1) informazioni += '<img src="img/gioco.png" />';
	  if(markers[i].getAttribute("ombra") == 1) informazioni += '<img src="img/ombra.png" />';
	  if(informazioni == '') informazioni = 'Nessuna informazione aggiuntiva';

          var html = "<div style=\"overflow:hidden;line-height:1.35;min-width:400px;\"><b>" + nome + "</b> <br/>" +
                     "Indirizzo: " + markers[i].getAttribute("indirizzo") + "<br/>" +
                     "Localit&agrave;: " + luogo + "<br/>" +
                     "Provincia: " + markers[i].getAttribute("provincia") + "<br/>" +
                     "Contatto: " + markers[i].getAttribute("contatto") + "<br/>" +
                     "Telefono: " + markers[i].getAttribute("telefono") + "<br/>" +
                     "Cellulare: " + markers[i].getAttribute("cellulare") + "<br/>" +
                     "Email: <a href=\"mailto:" + markers[i].getAttribute("email") + "\">" + markers[i].getAttribute("email") + "</a><br/>" +
                     "Tipologia: " + tipologia + "<br/>" +
                     "Informazioni: " + informazioni + "<br/>" +
		     "</div>";

          bindInfoWindow(marker, map, infoWindow, html);
	  markerBounds.extend(marker.position);
        }

        // Correctly center and zoom
        map.fitBounds(markerBounds);

	// Add the trigger to rebuild the table
	google.maps.event.addListener(map, 'bounds_changed', updateTable);
      });
    }


    // To toggle checkboxes
    function togglecheckboxes(master,group){
	var cbarray = document.getElementsByClassName(group);
	for(var i = 0; i < cbarray.length; i++){
		var cb = document.getElementById(cbarray[i].id);
		cb.checked = master.checked;
	}
    }


    function updateTable() {

        // Get old tbody and create a new one
        var old_tbody = document.getElementById("elenco-posti").getElementsByTagName('tbody')[0];
	var new_tbody = document.createElement('tbody');

	// Populate the new tbody
        for (var i = 0; i < markers.length; i++) {

		// If the current marker is not visible in the current zoom, we simply skip it
		if(!map.getBounds().contains(new google.maps.LatLng(markers[i].getAttribute("lat"), markers[i].getAttribute("lng"))))
			continue;

		// Check on filters
		if(document.forms["filtri"]["filtro_fuoco"].checked && markers[i].getAttribute("fuoco") != "1")
			continue;
		if(document.forms["filtri"]["filtro_campo"].checked && markers[i].getAttribute("campo") != "1")
			continue;
		if(document.forms["filtri"]["filtro_acqua"].checked && markers[i].getAttribute("acqua") != "1")
			continue;
		if(document.forms["filtri"]["filtro_gratis"].checked && markers[i].getAttribute("gratis") != "1")
			continue;
		if(document.forms["filtri"]["filtro_gioco"].checked && markers[i].getAttribute("gioco") != "1")
			continue;
		if(document.forms["filtri"]["filtro_ombra"].checked && markers[i].getAttribute("ombra") != "1")
			continue;
		if(document.forms["filtri"]["filtro_tenda"].checked && (markers[i].getAttribute("tipo") != "tenda" && (markers[i].getAttribute("tipo") != "entrambi")))
			continue;
		if(document.forms["filtri"]["filtro_accantonamento"].checked && (markers[i].getAttribute("tipo") != "accantonamento" && (markers[i].getAttribute("tipo") != "entrambi")))
			continue;

		var new_row = new_tbody.insertRow(new_tbody.rows.length);

		var new_cell = new_row.insertCell(0);
		new_cell.innerHTML = '<input type="checkbox" id="cb_' + i + '" class="posticb" name="posticbg[]" value="' + markers[i].getAttribute("id") + '" checked/>';

		var new_cell = new_row.insertCell(1);
                new_cell.innerHTML = '<a href="place.php?id=' + markers[i].getAttribute("id") + '">' + markers[i].getAttribute("nome") + '</a>';

		var new_cell = new_row.insertCell(2);
                new_cell.innerHTML = markers[i].getAttribute("indirizzo");

		var new_cell = new_row.insertCell(3);
                new_cell.innerHTML = markers[i].getAttribute("luogo");

		var new_cell = new_row.insertCell(4);
                new_cell.innerHTML = markers[i].getAttribute("provincia");

		var new_cell = new_row.insertCell(5);
                new_cell.innerHTML = markers[i].getAttribute("contatto");

		var new_cell = new_row.insertCell(6);
                new_cell.innerHTML = markers[i].getAttribute("telefono");

		var new_cell = new_row.insertCell(7);
                new_cell.innerHTML = markers[i].getAttribute("cellulare");

		var new_cell = new_row.insertCell(8);
                new_cell.innerHTML = '<a href="mailto:' + markers[i].getAttribute("email") + '">' + markers[i].getAttribute("email") + '</a>';

		// Il tipo ha una formattazione leggermente più complessa
		var tipo = markers[i].getAttribute("tipo");
                var tipo_HTML;
		if(tipo == "tenda") {
			tipo_HTML = 'T';
		} else if (tipo == "accantonamento") {
			tipo_HTML = 'A (' + markers[i].getAttribute("posti") + ')';
		} else {
			tipo_HTML = 'T - A (' + markers[i].getAttribute("posti") + ')';
		}
		var new_cell = new_row.insertCell(9);
		new_cell.setAttribute('style','min-width:80px');
                new_cell.innerHTML = tipo_HTML;

		// Nella colonna informazioni mostriamo, se necessario, le icone
		var new_cell = new_row.insertCell(10);
		var info = ''
		if(markers[i].getAttribute("fuoco") == "1")
			info += '<img src="img/fire.png" title="Possibilit&agrave; di accendere il fuoco"/>';
		if(markers[i].getAttribute("acqua") == "1")
			info += '<img src="img/water-drop.png" title="Disponibilit&agrave; di acqua potabile"/>';
		if(markers[i].getAttribute("campo") == "1")
			info += '<img src="img/tent.png" title="Posto valido anche per un campo"/>';
		if(markers[i].getAttribute("gratis") == "1")
			info += '<img src="img/no-euro.png" title="Posto gratuito"/>';
		if(markers[i].getAttribute("gioco") == "1")
			info += '<img src="img/gioco.png" title="Spazio per gioco"/>';
		if(markers[i].getAttribute("ombra") == "1")
			info += '<img src="img/ombra.png" title="Spazio all\'ombra"/>';
		new_cell.innerHTML = info;

		var new_cell = new_row.insertCell(11);
                new_cell.innerHTML = '<a href="insert.php?update=1&id=' + markers[i].getAttribute("id") + '"><img src="img/exclamation.png" title="Proponi modifche" alt="Proponi modifiche" /></a> <a href="delete.php?id=' + markers[i].getAttribute("id") + '" onclick="return window.confirm(\'Sei sicuro di voler eliminare ' + markers[i].getAttribute("nome") + '?\')"><img src="img/minus.png" title="Rimuovi posto" alt="Rimuovi posto" /></a>';
	}
	
	// Replace old tbody with new one
	old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;

          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

  </script>
  </head>

  <body onload="load()">
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
    <div><p align="center"><h3><a href="insert.php">Aggiungi un nuovo posto <img src="img/plus.png" style="vertical-align:middle" /></a></h3></p></div>
    <div id="map_canvas"></div>
    <div>
	<table align="center">
	<thead>
	<tr><th colspan="4"><strong>Legenda</strong></th></tr>
	</thead>
	<tbody>
	<tr>
		<td><img src="http://labs.google.com/ridefinder/images/mm_20_green.png" /></td><td>Posto in Tenda</td><
		<td><img src="http://labs.google.com/ridefinder/images/mm_20_yellow.png" /></td><td>Posto in Accantonamento</td>
	</tr>
	<tr>
		<td><img src="http://labs.google.com/ridefinder/images/mm_20_blue.png" /></td><td>Posto sia in Tenda, sia in Accantonamento</td>
		<td><img src="img/fire.png" /></td><td>Possibilit&agrave; di accendere fuochi</td>
	</tr>
	<tr>
		<td><img src="img/tent.png" /></td><td>Posto valido per campi</td>
		<td><img src="img/water-drop.png" /></td><td>Acqua potabile</td>
	</tr>
	<tr>
		<td><img src="img/no-euro.png" /></td><td>Posto gratuito</td>
		<td><img src="img/gioco.png" /></td><td>Spazio per gioco</td>
	</tr>
	<tr>
		<td><img src="img/ombra.png" /></td><td>Spazio all'ombra</td>
	</tr>
	</tbody>
	</table>
	<p><hr></p>
	<p align="center">
	<form name="filtri">
	<strong>Filtri di selezione:</strong>
	<input type="checkbox" id="filtro_tenda" class="filtricb" onchange="updateTable()" /><img src="http://labs.google.com/ridefinder/images/mm_20_green.png" />&nbsp;
	<input type="checkbox" id="filtro_accantonamento" class="filtricb" onchange="updateTable()" /><img src="http://labs.google.com/ridefinder/images/mm_20_yellow.png" />&nbsp;
	<input type="checkbox" id="filtro_fuoco" class="filtricb" onchange="updateTable()" /><img src="img/fire.png" />&nbsp;
	<input type="checkbox" id="filtro_campo" class="filtricb" onchange="updateTable()" /><img src="img/tent.png" />&nbsp;
	<input type="checkbox" id="filtro_acqua" class="filtricb" onchange="updateTable()" /><img src="img/water-drop.png" />&nbsp;
	<input type="checkbox" id="filtro_gratis" class="filtricb" onchange="updateTable()" /><img src="img/no-euro.png" />&nbsp;
	<input type="checkbox" id="filtro_gioco" class="filtricb" onchange="updateTable()" /><img src="img/gioco.png" />&nbsp;
	<input type="checkbox" id="filtro_ombra" class="filtricb" onchange="updateTable()" /><img src="img/ombra.png" />&nbsp;
	</form>
	</p>
	<p><hr></p>
    </div>
    <form name="stampa-elenco" action="export_pdf.php" method="post">
    <center><button type="submit">Esporta gli elementi selezionati in PDF <img height="32px" style="vertical-align:middle" src="img/pdf.png" /></button></center>
    <table id="elenco-posti">
    <thead>
      <tr>
	   <th><input type="checkbox" id="cbgroup1_master" onchange="togglecheckboxes(this,'posticb')" checked/></th>
           <th>Nome</th>
	   <th>Indirizzo</th>
	   <th>Localit&agrave;</th>
	   <th>Provincia</th>
	   <th>Contatto</th>
	   <th>Telefono</th>
	   <th>Cellulare</th>
	   <th>Email</th>
	   <th>Tipo</th>
	   <th style="min-width:103px;">Informazioni</th>
	   <th>Azione</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
    </form>
    <div><p>&nbsp;</p></div>
    </div>
    </div>
    </main>
<footer>
         Copyright &copy; <?= date("Y") ?> - <a href="http://www.pellegrini.tk" target="_blank"> Alessandro Pellegrini </a>
</footer>
  </body>
</html>
