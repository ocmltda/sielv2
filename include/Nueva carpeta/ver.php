<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();

$t = new TemplatePower("ver.html");
$t->prepare();

$db1 = new DB_Sql;
$db1->query("SELECT INGE_CORREO,INGE_PAGINA,INGE_TELEFONO,INGE_DIRECCION,INGE_PIE_PAGINA FROM INGELUBE_CONF");
$db1->next_record();
$t->assign("tit_sit",$db1->Record['INGE_PAGINA']);
$t->assign("pie_pag",$db1->Record['INGE_PIE_PAGINA']);
$t->assign("fono",$db1->Record['INGE_TELEFONO']);
$t->assign("direccion",$db1->Record['INGE_DIRECCION']);

if($_SESSION["session"]!="" and $_SESSION["session"]!="*error*")
{	
	$session=$_SESSION["session"];
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario Logeado<br />
         	</label><a href='logout.php' title='Cerrar Session'>Cerrar Session</a>
         </div>");
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
}
$id=$_REQUEST["id"];
$emp=$_SESSION["empresa"];
$db = new DB_Sql2;
//$db->query("select EQ.EQU_ID,EQ.EQU_NOMBRE from lube_equipos EQ where EQ.equ_parent=$id");
$db->query("select EQU_ID,EQU_PARENT,EQU_NOMBRE,EQU_EMPRESA from lube_equipos WHERE EQU_EMPRESA=$emp AND EQU_PARENT = $id order by EQU_ID");
//echo $db->nf();
$cant=$db->nf();
if($cant>0)
{
	while($db->next_record())
	{
				$t->newBlock("pdf");
				$t->assign("id",$db->Record['EQU_ID']."  ");
				$ide=$db->Record['EQU_ID'];
				$t->assign("titulo",utf8_encode($db->Record['EQU_NOMBRE']));
				$t->assign("id","<a href='ver.php?id=$ide' title='Siguiente'>Siguiente</a><br>");
	}
}else{

		$t->assign("mostrar","<a href='ver2.php?ide=$id' title='ver informe'>Ver Informe</a><br>");
		$t->assign("nolink","<!--");
		$t->assign("nolink2","-->");
		$t->assign("otrolink","<a href='javascript:history.back();'>Volver Atras</a>");
}
$t->printToScreen();
?>