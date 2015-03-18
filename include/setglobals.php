<?php
	//para evitar el cache del navegador
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
	header("Cache-Control: post-check=0, pre-check=0", false); 
	header("Cache-Control: private");
	header("Pragma: no-cache"); // HTTP/1.0
?>
<?php
ini_set("register_globals","1");
ini_set("display_errors","1");
?>