<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');

$t = new TemplatePower("detalles.html");
$t->prepare();

$db = new DB_Sql;

$id=$_REQUEST["id"];

$db->query("SELECT INGE_PAGINA,INGE_CORREO,INGE_DIRECCION,INGE_TELEFONO,INGE_PIE_PAGINA FROM INGELUBE_CONF WHERE INGE_ID=$id");
$db->next_record(); 

$t->assign("pagina",$db->Record['INGE_PAGINA']);
$t->assign("correo",$db->Record['INGE_CORREO']);
$t->assign("direccion",$db->Record['INGE_DIRECCION']);
$t->assign("telefono",$db->Record['INGE_TELEFONO']);
$t->assign("pie_pagina",$db->Record['INGE_PIE_PAGINA']);
$t->assign("_ROOT.id",$id);

//print the result
$t->printToScreen();
?>