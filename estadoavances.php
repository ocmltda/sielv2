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

	//total evaluaciones programadas
	$db->query('SELECT Count(V.fecha_visita) AS TOTPRG FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' AND (V.estado_visita = 0 OR V.estado_visita = 1)');
	$db->next_record();
	$t->assign("_ROOT.tep", $db->Record['TOTPRG'] . '');

	//total evaluaciones realizadas
	$db->query('SELECT Count(V.fecha_visita) AS TOTREA FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' AND V.estado_visita = 1');
	$db->next_record();
	$t->assign("_ROOT.ter", $db->Record['TOTREA'] . '');

	//total evaluaciones en curso
	$db->query('SELECT Count(V.fecha_visita) AS TOTENC FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' AND V.estado_visita = 0');
	$db->next_record();
	$t->assign("_ROOT.tec", $db->Record['TOTENC'] . '');

	//LAS ZONAS TOTAL
	$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND (V.estado_visita = 0 OR V.estado_visita = 1) GROUP BY L.promotor ORDER BY ZONA ASC');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			if (trim(strtolower($db->Record['ZONA'])) == 'norte')
				$t->assign("_ROOT.tzn", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'centro')
				$t->assign("_ROOT.tzc", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'sur')
				$t->assign("_ROOT.tzs", $db->Record['TOTZONA'] . '');
		}
	}
	else
	{
		$t->assign("_ROOT.tzn", '0');
		$t->assign("_ROOT.tzc", '0');
		$t->assign("_ROOT.tzs", '0');
	}

	//LAS ZONAS REALIZADAS
	$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND V.estado_visita = 1 GROUP BY L.promotor ORDER BY ZONA ASC');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			if (trim(strtolower($db->Record['ZONA'])) == 'norte')
				$t->assign("_ROOT.rzn", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'centro')
				$t->assign("_ROOT.rzc", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'sur')
				$t->assign("_ROOT.rzs", $db->Record['TOTZONA'] . '');
		}
	}
	else
	{
		$t->assign("_ROOT.rzn", '0');
		$t->assign("_ROOT.rzc", '0');
		$t->assign("_ROOT.rzs", '0');
	}

	//LAS ZONAS EN CURSO
	$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND V.estado_visita = 0 GROUP BY L.promotor ORDER BY ZONA ASC');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			if (trim(strtolower($db->Record['ZONA'])) == 'norte')
				$t->assign("_ROOT.czn", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'centro')
				$t->assign("_ROOT.czc", $db->Record['TOTZONA'] . '');
			if (trim(strtolower($db->Record['ZONA'])) == 'sur')
				$t->assign("_ROOT.czs", $db->Record['TOTZONA'] . '');
		}
	}
	else
	{
		$t->assign("_ROOT.czn", '0');
		$t->assign("_ROOT.czc", '0');
		$t->assign("_ROOT.czs", '0');
	}

	//print the result
	$t->printToScreen();
}
?>