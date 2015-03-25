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

	$t = new TemplatePower("pla_avances.html");
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

	$db->query('SELECT DAY(V.fecha_visita) AS DIA, Count(V.fecha_visita) AS TOTDIA FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' GROUP BY V.fecha_visita ORDER BY V.fecha_visita ASC');
	if ($db->nf() > 0)
	{
		$tf = 1;
		while($db->next_record())
		{
			$t->newBlock("diaevaluaciones");
			$t->assign("dia",$db->Record['DIA']);
			$t->assign("valdia",$db->Record['TOTDIA']);
			if ($tf < $db->nf())
				$t->assign("coma", ',');
			$tf++;
		}
	}
	else
	{
		$t->newBlock("diaevaluaciones");
	}

	$db->query('SELECT V.estado_visita AS ESTADO, Count(V.estado_visita) AS TOTESTADO FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' GROUP BY V.estado_visita ORDER BY ESTADO ASC');
	if ($db->nf() > 0)
	{
		$tf = 1;
		while($db->next_record())
		{
			$t->newBlock("estados");
			if ($db->Record['ESTADO'] == 1)
				$estvisita = 'Realizada';
			else
				$estvisita = 'En Curso';
			$t->assign("estado",$estvisita . '');
			$t->assign("total",$db->Record['TOTESTADO']);
			if ($tf < $db->nf())
				$t->assign("coma2", ',');
			$tf++;
		}
	}
	else
	{
		$t->newBlock("estados");
	}

	//print the result
	$t->printToScreen();
}
?>