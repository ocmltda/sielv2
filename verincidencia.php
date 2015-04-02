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

	$t = new TemplatePower("pla_incidencia.html");
	$t->prepare();

	$db = new DB_Sql;

	$db->query('SELECT a.*, l.nombre, DATE_FORMAT(a.fecha,\'%d-%m-%Y\') as fecvisita, t.*, ti.tipi_nombre FROM alertas a, tipos_acciones t, locales l, tiposincidencias ti WHERE a.id = ' . $_REQUEST['IA'] . ' and a.locales_id = l.id and a.tiposacciones_id = t.tipos_acciones_id and a.tiposincidencias_id = ti.tipi_id');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->assign("id",$db->Record['id']);
			$t->assign("fecha", 'Fecha: ' . $db->Record['fecvisita']);
			$t->assign("hora", 'Hora: ' . $db->Record['hora']);
			$t->assign("local",$db->Record['nombre']);
			$t->assign("gps",$db->Record['coordenadas']);
			$t->assign("incidencia",$db->Record['tipi_nombre']);
			$t->assign("comentario",$db->Record['comentarios']);
			//echo '<pre>' . htmlspecialchars('<img src="imgincidencias/' . $db->Record['fotografia'] . '">') . '</pre>';

			$t->assign("foto", '<img src="imgincidencias/' . $db->Record['fotografia'] . '" width="240">');
			$IDTA = $db->Record['tipos_acciones_id'];
		}
	}

	$db->query('SELECT * FROM tipos_acciones');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("tipos_acciones");
			$t->assign("idta", $db->Record['tipos_acciones_id']);
			$t->assign("tipoaccion",$db->Record['accion']);
			if ($db->Record['tipos_acciones_id'] == $IDTA)
				$t->assign("selected", 'selected');
		}
	}
	else
	{
		$t->newBlock("tipos_acciones");
	}

	//print the result
	$t->printToScreen();
//}
?>