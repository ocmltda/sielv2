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

	$t = new TemplatePower("pla_verevaluacion02.html");
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

	//hitos o momentos
	$db->query('SELECT I.id, I.item FROM visitas AS V INNER JOIN planillas AS P ON P.id = V.planillas_id INNER JOIN items AS I ON P.id = I.planillas_id WHERE V.id = ' . $_REQUEST['IDE']);
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("hitos");
			$t->assign("tithito",$db->Record['item']);

			$db2->query('SELECT I.item, PR.pregunta, R.respuesta, tipos.tipo, I.validacion, PR.puntaje, R.puntaje_obtenido FROM visitas AS V INNER JOIN planillas AS P ON P.id = V.planillas_id INNER JOIN items AS I ON P.id = I.planillas_id INNER JOIN preguntas AS PR ON I.id = PR.items_id INNER JOIN respuestas AS R ON PR.id = R.preguntas_id AND V.id = R.visitas_id INNER JOIN tipos ON tipos.id = PR.tipos_id WHERE V.id = ' . $_REQUEST['IDE'] . ' and PR.items_id = ' . $db->Record['id']);
			if ($db2->nf() > 0)
			{
				while($db2->next_record())
				{
					$t->newBlock("preguntas");
					$t->assign("pregunta",$db2->Record['pregunta']);
					if ($db2->Record['tipo'] == 'Si/No')
					{
						if ($db2->Record['respuesta'] == '1')
							$t->assign("respuesta", 'Si');
						if ($db2->Record['respuesta'] == '2')
							$t->assign("respuesta", 'No');
					}

					if ($db2->Record['tipo'] == '0-7')
					{
						$t->assign("respuesta", $db2->Record['respuesta'] . '');
					}
				}
			}
			else
			{
				$t->newBlock("preguntas");
			}
		}
	}
	else
	{
		$t->newBlock("hitos");
	}

	//print the result
	$t->printToScreen();
}
?>