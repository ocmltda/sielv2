<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();

$t = new TemplatePower("informes.html");
$t->prepare();

$db = new DB_Sql;
$db->query("SELECT INGE_CORREO,INGE_PAGINA,INGE_TELEFONO,INGE_DIRECCION,INGE_PIE_PAGINA FROM INGELUBE_CONF");
$db->next_record();
$t->assign("tit_sit",$db->Record['INGE_PAGINA']);
$t->assign("pie_pag",$db->Record['INGE_PIE_PAGINA']);
$t->assign("fono",$db->Record['INGE_TELEFONO']);
$t->assign("direccion",$db->Record['INGE_DIRECCION']);

if($_SESSION["session"]!="" and $_SESSION["session"]!="*error*")
{
	$session=$_SESSION["session"];
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario Logeado<br />
         	</label><a href='logout.php' title='Cerrar Session'>Cerrar Session</a>
         </div>");
	
$db = new DB_Sql2;
$db->query("select user_empcodigo from lube_users where user_id=$session");
$db->next_record();
$usuario=$db->Record['user_empcodigo'];
//codigo empresa
$_SESSION["empresa"]=$usuario;
//listo el arbol
$db = new DB_Sql2;
$db->query("select EQU_ID,EQU_PARENT,EQU_NOMBRE from lube_equipos WHERE EQU_EMPRESA=$usuario order by EQU_ID");
	while($db->next_record())
	{	
		$t->newBlock("itemstree");
		$t->assign("numcorr",$db->Record['EQU_ID'] . "");
		$t->assign("padre",$db->Record['EQU_PARENT'] . "");
		$t->assign("nomitemmenu", $db->Record['EQU_NOMBRE'] . "");
		$t->assign("linkitemmenu", "ver2.php?ide=" . $db->Record['EQU_ID']  . "");
	}
}else{
	if($_SESSION["session"]=="*error*")
	{
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario no existe<br />
         	</label><a href='volver.php' title='volver'>Volver</a>
         </div>");
	}
	$t->assign("nouser"," <div class='contenido_panamarillo'>
	                	<div class='txt_nombreinforme' align='center'>Ingrese usuario y password para ver el contenido.</div></div>");
}

//print the result
$t->printToScreen();
?>