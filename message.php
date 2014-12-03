<?php

if(!isset($_GET['t']))
	exit();

if($_GET['t'] == "e")
	$t = "error";
else if($_GET['t'] == "s")
	$t = "success";
else
	exit();

if(!isset($_GET['m']))
	exit();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>UsciTeca - Zona Cassiopea</title>
    <link href="style2.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript">
	var secondi = 6;
	function countdown() {
		secondi = secondi - 1;
		document.getElementById("countdown").innerHTML = secondi;

		if(secondi > 0)
			setTimeout("countdown()",1000);
		else
			window.location.replace("index.php");
	}
  </script>
  </head>
  <body onload="countdown();">
	<main>
            <header>
                <h1>
		<img src="img/tenda.jpg" height="150"/>
		UsciTeca - Zona Cassiopea	
		<img src="img/tenda.jpg" height="150"/>
		</h1>
            </header>
    <div style="margin-left:25%;margin-right:25%;padding-top:15pt;">
	<div class="<?= $t ?>">
		<p><?= $_GET['m'] ?></p>
		<p>Verrai reindirizzato alla <a href="index.php">pagina principale</a> tra <span id="countdown"></span> secondi...</p>
	</div>
    </div>
    </main>
<footer>
         Copyright &copy; <?= date("Y") ?> - <a href="http://www.pellegrini.tk" target="_blank"> Alessandro Pellegrini </a>
</footer>
  </body>
</html>
