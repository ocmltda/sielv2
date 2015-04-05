<?php
	require_once('../include/class.TemplatePower.inc.php');

	$t = new TemplatePower("pla_okalerta.html");
	$t->prepare();

	//print the result
	$t->printToScreen();
?>