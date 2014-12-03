<?php

require("config.php");
require("pdf/pdf.php");


if(!isset($_POST['posticbg']))
	die('Non si puo` accedere direttamente a questa pagina');


$posti = $_POST['posticbg'];


$connection=mysql_connect ($dbhost, $username, $password);
if (!$connection) {
        die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
        die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table
$query = 'SELECT * FROM markers WHERE id IN (' . implode(',', array_map('intval', $posti)) . ')';
$result = mysql_query($query);
if (!$result) {
        die('Invalid query: ' . mysql_error());
}
if (mysql_num_rows($result) == 0) {
        die('Invalid ID ' . $_GET['id']);
}


// Generate the PDF from the retrieved data


$pdf = new PDF("UsciTeca - Zona Cassiopea", 'img/tenda.jpg');

$data = array();
while ($riga = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$tipo = $riga['tipo'];
	$tipo_HTML = "";
	if($tipo == "tenda") {
                $tipo_HTML = 'T';
        } else if ($tipo == "accantonamento") {
                $tipo_HTML = 'A (' . $riga['posti'] . ')';
        } else {
                $tipo_HTML = 'T - A (' . $riga['posti'] . ')';
        }

	$info_img_vector = array();
	if($riga['fuoco'] == 1) array_push($info_img_vector, "img/fire.png");
	if($riga['acqua'] == 1) array_push($info_img_vector, "img/water-drop.png");
	if($riga['campo'] == 1) array_push($info_img_vector, "img/tent.png");
	if($riga['gratis'] == 1) array_push($info_img_vector, "img/no-euro.png");
	if($riga['gioco'] == 1) array_push($info_img_vector, "img/gioco.png");
	if($riga['ombra'] == 1) array_push($info_img_vector, "img/ombra.png");

	$data[] = array($riga['nome'], $riga['indirizzo'], $riga['luogo'], $riga['provincia'], $riga['contatto'], $riga['telefono'], $riga['cellulare'], $riga['email'], $tipo_HTML, $info_img_vector);
}


// Column headings
$header = array('Nome', 'Indirizzo', 'Localita`', 'PR', 'Contatto', 'Telefono', 'Cellulare', 'Email', 'Tipologia','Info');
$width = array(44, 44, 44, 8, 25, 16, 16, 40, 15, 25);

$pdf->SetFont('Times','',8);
$pdf->AddPage("L", "A4");
$pdf->FancyTable($header, $width, $data);
$pdf->Output();

?>
