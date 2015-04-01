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

	$t->assign("empresa", $_REQUEST['EMP']);

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
				case 1:
					$estvisita = 'Vigente';
					break;
				case 2:
					$estvisita = 'Realizada';
					break;
				case 3:
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
			$t->assign("boleta", '../siel/webroot/boletas/' . $db->Record['boleta'] . '');
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

	$db->query('SELECT I.item, Sum(IF(PR.puntaje = 0 and PR.tipos_id = 3,7,PR.puntaje)) AS TOTMOM, Sum(R.puntaje_obtenido) AS TOTRESP, I.id FROM visitas AS V INNER JOIN planillas AS P ON P.id = V.planillas_id INNER JOIN items AS I ON P.id = I.planillas_id INNER JOIN preguntas AS PR ON I.id = PR.items_id INNER JOIN respuestas AS R ON PR.id = R.preguntas_id AND V.id = R.visitas_id WHERE V.id = ' . $_REQUEST['IDE'] . ' GROUP BY I.item, I.id ORDER BY I.id ASC');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("items");
			$t->assign("item", $db->Record['item'] . '');
			$perdidos = $db->Record['TOTMOM'] - $db->Record['TOTRESP'];

			if ($perdidos > 0)
			{
				$minmargen = 64;

				$t->assign("perd", 'Perdidos: ' . $perdidos . '');
				/*if ($perdidos > 100 && $perdidos < 170)
					$perdidos = $perdidos * 2;
				if ($perdidos > 50 && $perdidos <= 100)
					$perdidos = $perdidos * 4;
				if ($perdidos > 0 && $perdidos <= 50)
					$perdidos = $perdidos * 5;*/
				$perdidos = $minmargen + $perdidos;
				$sobra = 350 - $perdidos;
				$t->assign("ancho", $perdidos . '');
				$t->assign("margen", $sobra . '');
			}
			else
			{
				$t->assign("perd", '');
				$t->assign("ancho", 0);
				$t->assign("margen", 0);
			}
		}
	}
	else
	{
		$t->newBlock("items");
	}


	//print the result
	$t->printToScreen();
}
?>