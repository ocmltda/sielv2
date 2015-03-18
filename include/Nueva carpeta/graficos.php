<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');


session_start();

$t = new TemplatePower("graficos.html");
$t->prepare();

$db = new DB_Sql;
$db->query("SELECT INGE_CORREO,INGE_PAGINA,INGE_TELEFONO,INGE_DIRECCION,INGE_PIE_PAGINA FROM INGELUBE_CONF");

$t->assign("tit_sit",$db->Record['INGE_PAGINA']);
$t->assign("pie_pag",$db->Record['INGE_PIE_PAGINA']);
$t->assign("fono",$db->Record['INGE_TELEFONO']);
$t->assign("direccion",$db->Record['INGE_DIRECCION']);

if($_SESSION["session"]!="")
{	
	$session=$_SESSION["session"];
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","--><br><br><div align='center'><label>$session</label><a href='logout.php' title='Cerrar Session'>Cerrar Session</a></div></br>");
}
$db = new DB_Sql2;

//print the result
$t->printToScreen();
?>