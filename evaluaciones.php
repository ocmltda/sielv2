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

	$t = new TemplatePower("pla_evaluaciones.html");
	$t->prepare();

	$db = new DB_Sql;

	$db->query('SELECT v.id,DATE_FORMAT(v.fecha_visita,\'%d-%m-%Y\') as fecvisita,lo.nombre,p.servicio,lo.direccion FROM visitas AS v INNER JOIN locales AS lo ON lo.id = v.locales_id INNER JOIN planillas AS p ON p.id = v.planillas_id ORDER BY v.fecha_visita DESC');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("evaluaciones");
			$t->assign("id",$db->Record['id']);
			$t->assign("fecvisita",$db->Record['fecvisita']);
			$t->assign("local",$db->Record['nombre']);
			$t->assign("servicio",$db->Record['servicio']);
			$t->assign("direccion",$db->Record['direccion']);

			$db2 = new DB_Sql;

			$db2->query('SELECT Sum(res.puntaje_obtenido) AS puntaje FROM visitas AS v INNER JOIN respuestas AS res ON v.id = res.visitas_id WHERE v.id = ' . $db->Record['id']);
			if ($db2->nf() > 0)
			{
				$db2->next_record();
				$t->assign("puntaje",$db2->Record['puntaje']);
			}

			//$t->assign("estado",$db->Record['estado']);
		}
	}
	else
	{
		$t->newBlock("evaluaciones");
	}

	//print the result
	$t->printToScreen();
//}
?>