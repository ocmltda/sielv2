<?php
//creamos la sesion
session_start();
	
//validamos si se ha hecho o no el inicio de sesion correctamente
	
//si no se ha hecho la sesion nos regresará a login.php
if(!isset($_SESSION['ulogin']))
{
	header('Location: login.php');
	exit();
}
else
{
	require_once('include/class.TemplatePower.inc.php');
	require_once('include/db_mysql.inc');

	$t = new TemplatePower("pla_verevaluacion01.html");
	$t->prepare();

	$t->assign("usrlogin", $_SESSION['uname']);

	$db = new DB_Sql;
	$db->query('SELECT MN.men_id, MN.men_link, MN.men_nombre FROM percat AS PC INNER JOIN menu AS MN ON MN.men_id = PC.men_id WHERE PC.tipos_usuarios_id = ' .$_SESSION['utus']. ' ORDER BY MN.men_orden ASC');
	$menutop = '';
	while($db->next_record())
	{
		if ($menutop)
			$menutop = $menutop . ' | <a href="' . $db->Record['men_link'] . '" style="color:white">' . $db->Record['men_nombre'] . '</a>';
		else
			$menutop = '<a href="' . $db->Record['men_link'] . '" style="color:white">' . $db->Record['men_nombre'] . '</a>';
	}
	if ($menutop)
		$t->assign("menutop", '<a href="index.php" style="color:white">Inicio</a> | ' . $menutop . ' | <a href="logout.php" style="color:white">Cerrar Sesión</a>');
	else
		$t->assign("menutop", '<a href="index.php" style="color:white">Inicio</a> | <a href="logout.php" style="color:white">Cerrar Sesión</a>');

	$t->assign("IDE", $_REQUEST['IDE'] . '');

	$db = new DB_Sql;
	$db2 = new DB_Sql;

	//encabezado y detalle
	$db->query('SELECT V.id, V.fecha_visita, V.estado_visita, V.estado_revision, V.boleta, V.observaciones, V.fechas_disponibles FROM visitas AS V WHERE V.id = ' . $_REQUEST['IDE']);
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$estvisita = $db->Record['estado_visita'];
			$estrevision = $db->Record['estado_revision'];

			switch ($estvisita) {
				case 0:
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

			$t->assign("fecvisita", $db->Record['fecha_visita'] . '');
			$t->assign("estvisita", $estvisita . '');
			$t->assign("estrev", $estrevision . '');
			$t->assign("boleta", '../boletas/' . $db->Record['boleta'] . '');
			$t->assign("fecdispo", $db->Record['fechas_disponibles'] . '');
			$t->assign("detalles", $db->Record['observaciones'] . '');

			$db2->query('SELECT Sum(res.puntaje_obtenido) AS puntaje FROM visitas AS v INNER JOIN respuestas AS res ON v.id = res.visitas_id WHERE v.id = ' . $db->Record['id']);
			if ($db2->nf() > 0)
			{
				$db2->next_record();
				$t->assign("puntaje",$db2->Record['puntaje']);
			}
		}
	}

	//datos generales
	$db->query('SELECT V.fecha_visita, V.estado_visita, V.estado_revision, V.boleta, V.observaciones, V.fechas_disponibles, P.servicio, G.nombre  AS PREGUG, R.respuesta, R.puntaje_obtenido FROM visitas AS V INNER JOIN planillas AS P ON P.id = V.planillas_id INNER JOIN generales AS G ON P.id = G.planillas_id INNER JOIN respuestas AS R ON G.id = R.generales_id AND V.id = R.visitas_id WHERE V.id = ' . $_REQUEST['IDE']);
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("generales");
			$t->assign("pregunta",$db->Record['PREGUG']);
			$t->assign("respuesta",$db->Record['respuesta']);
		}
	}
	else
	{
		$t->newBlock("generales");
	}

	//print the result
	$t->printToScreen();
}
?>