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

	$t = new TemplatePower("pla_programacion.html");
	$t->prepare();

	$db = new DB_Sql;

	$db->query('SELECT v.*, l.nombre, l.direccion, p.servicio, DATE_FORMAT(v.fecha_visita,\'%d-%m-%Y\') as fecvisita FROM visitas v, locales l, planillas p WHERE v.locales_id = l.id and v.planillas_id = p.id');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("programaciones");
			$t->assign("id",$db->Record['id']);
			$t->assign("locales",$db->Record['nombre']);
			$t->assign("servicio",$db->Record['servicio']);
			$t->assign("direccion",$db->Record['direccion']);
			$t->assign("fecvisita",$db->Record['fecvisita']);
			$estvisita = $db->Record['estado_visita'];
			$estrevision = $db->Record['estado_revision'];

			switch ($estvisita) {
				case 1:
					$estvisita = 'Vigente';
					break;
				case 1:
					$estvisita = 'Realizada';
					break;
				case 2:
					$estvisita = 'Atrasada';
					break;
				default:
				   $estvisita = 'S/I';
			}
			
			switch ($estrevision) {
				case 0:
					$estrevision = 'En Curso';
					break;
				case 1:
					$estrevision = 'Sin Revisar';
					break;
				case 2:
					$estrevision = 'Revisada';
					break;
				default:
				   $estrevision = 'S/I';
			}

			$t->assign("estvisita",$estvisita);
			$t->assign("estrevision",$estrevision);
		}
	}
	else
	{
		$t->newBlock("programaciones");
	}

	//print the result
	$t->printToScreen();
//}
?>