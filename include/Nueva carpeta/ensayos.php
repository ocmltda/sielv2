<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/funciones.php');

$regXpag = 3;
if (!isset($_GET["pag"]))
    $pag = 1;
else
    $pag = $_GET["pag"];

session_start();

$t = new TemplatePower("ensayos.html");
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
$id_ensayo=$_GET["id2"];
if($id_ensayo=="")
{
	$db->query("SELECT count(*) as total FROM ENSAYO");
	$db->next_record();
	$totReg = $db->Record["total"];
	$db->query("SELECT ENSA_ID,ENSA_NOMBRE,ENSA_NORMA,ENSA_DESCRIPCION,ENSA_IMAGENOP FROM ENSAYO LIMIT " . (($pag - 1) * $regXpag) . "," . ($regXpag));
	 
	while($db->next_record())
	{
		$t->newBlock("ensayo");
		$t->assign("analisis",$db->Record['ENSA_NOMBRE']);
		$t->assign("norma",$db->Record['ENSA_NORMA']);
		$t->assign("descrip",str_replace("\n", "<br>", $db->Record['ENSA_DESCRIPCION']));	
		$t->assign("imagen1","mantenedor/imagenes_ensayo/".$db->Record['ENSA_IMAGENOP']);
	}
}else{
	$db->query("SELECT ENSA_ID,ENSA_NOMBRE,ENSA_NORMA,ENSA_DESCRIPCION,ENSA_IMAGENOP FROM ENSAYO WHERE ENSA_ID=$id_ensayo");
	while($db->next_record())
	{
		$t->newBlock("ensayo");
		$t->assign("analisis",$db->Record['ENSA_NOMBRE']);
		$t->assign("norma",$db->Record['ENSA_NORMA']);
		$t->assign("descrip",str_replace("\n", "<br>", $db->Record['ENSA_DESCRIPCION']));	
		$t->assign("imagen1","mantenedor/imagenes_ensayo/".$db->Record['ENSA_IMAGENOP']);
	}
}
paginar($regXpag, $pag, $getVars, $totReg);

//print the result
$t->printToScreen();
?>