<?php
//creamos la sesion
session_start();
	
//validamos si se ha hecho o no el inicio de sesion correctamente
	
//si no se ha hecho la sesion nos regresará a login.php
/*if(!isset($_SESSION['ulogin']))
{
	header('Location: login.php');
	exit();
}
else
{*/
	require_once('include/class.TemplatePower.inc.php');
	require_once('include/db_mysql.inc');

	$t = new TemplatePower("pla_alertas.html");
	$t->prepare();

	$db = new DB_Sql;

	$db->query('SELECT a.*, l.nombre, DATE_FORMAT(a.fecha,\'%d-%m-%Y\') as fecvisita FROM alertas a, locales l WHERE a.locales_id = l.id');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("alertas");
			$t->assign("id",$db->Record['id']);
			$t->assign("fecha",$db->Record['fecvisita']);
			$t->assign("hora",$db->Record['hora']);
			$t->assign("tienda",$db->Record['nombre']);
			$t->assign("gps",$db->Record['coordenadas']);
			$t->assign("incidencia",$db->Record['incidencia']);
			$t->assign("comentario",$db->Record['comentarios']);
		}
	}
	else
	{
		$t->newBlock("alertas");
	}

	//print the result
	$t->printToScreen();
//}
?>