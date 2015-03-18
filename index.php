<?php
require_once('include/class.TemplatePower.inc.php');
$t = new TemplatePower("pla_login.html");
$t->prepare();
//print the result
$t->printToScreen();
?>