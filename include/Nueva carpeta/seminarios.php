<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');

session_start();

$t = new TemplatePower("seminarios.html");
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
$id_seminario=$_GET["id2"];

if($id_seminario=="")
{
	$db = new DB_Sql;
	$db->query("SELECT SEM_ID,SEM_TITULO,SEM_CERTIFICACION,SEM_FECHA,SEM_CIUDAD,SEM_STATUS FROM CALENDARIO_SEMINARIO WHERE SEM_STATUS=1 ORDER BY SEM_FECHA");
	while($db->next_record())
	{
		list( $day, $month, $year ) = split( '[/.-]', $db->Record['SEM_FECHA']);
		
		$t->newBlock("seminarios");
		$t->assign("titulo",$db->Record['SEM_TITULO']);
		$t->assign("certificacion",$year.'-'.$month.'-'.$day);
		$t->assign("fecha",$db->Record['SEM_CERTIFICACION']);	
		$t->assign("ciudad",$db->Record['SEM_CIUDAD']);
	}
	
	$db = new DB_Sql;
	$db->query("SELECT SEM_ID,SEM_TITULO,SEM_CIUDAD,SEM_CUERPO,SEM_IMAGEN,SEM_CODIGO FROM CALENDARIO_SEMINARIO WHERE SEM_STATUS=1 ORDER BY SEM_FECHA");
	while($db->next_record())
	{
		$t->newBlock("seminarios2");
		$t->assign("titulo2",$db->Record['SEM_TITULO']);
		$t->assign("ciudad2",$db->Record['SEM_CIUDAD']);
		$t->assign("cuerpo2",str_replace("\n", "<br>", $db->Record['SEM_CUERPO']));
		$t->assign("codigo",$db->Record['SEM_CODIGO']);
		$t->assign("imagen","mantenedor/imagenes_seminarios/".$db->Record['SEM_IMAGEN']);
	}
}else
{
		$db = new DB_Sql;
		$db->query("SELECT SEM_ID,SEM_TITULO,SEM_CERTIFICACION,SEM_FECHA,SEM_CIUDAD,SEM_STATUS FROM CALENDARIO_SEMINARIO WHERE SEM_STATUS=1 AND SEM_ID=$id_seminario");
		while($db->next_record())
		{
		list( $day, $month, $year ) = split( '[/.-]', $db->Record['SEM_FECHA']);
		
		$t->newBlock("seminarios");
		$t->assign("titulo",$db->Record['SEM_TITULO']);
		$t->assign("certificacion",$year.'-'.$month.'-'.$day);
		$t->assign("fecha",$db->Record['SEM_CERTIFICACION']);	
		$t->assign("ciudad",$db->Record['SEM_CIUDAD']);
		}
		$db = new DB_Sql;
		$db->query("SELECT SEM_ID,SEM_TITULO,SEM_CIUDAD,SEM_CUERPO,SEM_IMAGEN,SEM_CODIGO FROM CALENDARIO_SEMINARIO WHERE SEM_STATUS=1 AND SEM_ID=$id_seminario");
		while($db->next_record())
		{
		$t->newBlock("seminarios2");
		$t->assign("titulo2",$db->Record['SEM_TITULO']);
		$t->assign("ciudad2",$db->Record['SEM_CIUDAD']);
		$t->assign("cuerpo2",str_replace("\n", "<br>", $db->Record['SEM_CUERPO']));
		$t->assign("codigo",$db->Record['SEM_CODIGO']);
		$t->assign("imagen","mantenedor/imagenes_seminarios/".$db->Record['SEM_IMAGEN']);
		}
}
//print the result
$t->printToScreen();
?>