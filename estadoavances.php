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

	if (!isset($_REQUEST['mes']) && !isset($_REQUEST['anio']))
	{
		$_REQUEST['mes'] = date('m');
		$_REQUEST['anio'] = date('Y');
	}
	else
	{
		if (!$_REQUEST['mes'] && !$_REQUEST['anio'])
		{
			$_REQUEST['mes'] = date('m');
			$_REQUEST['anio'] = date('Y');
		}
	}

	if ($_SESSION['empids'])
	{
		$db2 = new DB_Sql;
		$db2->query('SELECT c.* FROM clientes c WHERE c.id IN (' . $_SESSION['empids'] . ') ORDER BY c.nombre');
		if ($db2->nf() > 0)
		{
			$G1 = 1;
			$G2 = 2;
			$G3 = 3;
			$G4 = 4;

			$ordencolor = '\'#5B9CD6\',\'#D3CECB\'';
			while($db2->next_record())
			{
				$t->newBlock("empresas");
				$t->assign('G1', $G1 . '');
				$t->assign('G2', $G2 . '');
				$t->assign('G3', $G3 . '');
				$t->assign('G4', $G4 . '');
				$t->assign("empresa", strtoupper($db2->Record['nombre']) . '');
				$t->assign('periodosel', $_REQUEST['anio'] . '-' . $_REQUEST['mes'] . '');
				$t->assign('CLI', $db2->Record['id'] . '');
				$t->assign('MES', $_REQUEST['mes'] . '');
				$t->assign('ANIO', $_REQUEST['anio'] . '');

				//total evaluaciones programadas
				$db->query('SELECT Count(V.fecha_visita) AS TOTPRG FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND (V.estado_visita = 1 OR V.estado_visita = 2 OR V.estado_visita = 3)');
				$db->next_record();
				$t->assign("tep", $db->Record['TOTPRG'] . '');

				//total evaluaciones realizadas
				$db->query('SELECT Count(V.fecha_visita) AS TOTREA FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND V.estado_visita = 2');
				$db->next_record();
				$t->assign("ter", $db->Record['TOTREA'] . '');

				//total evaluaciones en curso
				$db->query('SELECT Count(V.fecha_visita) AS TOTENC FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND (V.estado_visita = 1 OR V.estado_visita = 3)');
				$db->next_record();
				$t->assign("tec", $db->Record['TOTENC'] . '');

				//LAS ZONAS TOTAL
				$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND (V.estado_visita = 1 OR V.estado_visita = 2 OR V.estado_visita = 3) GROUP BY L.promotor ORDER BY ZONA ASC');
				if ($db->nf() > 0)
				{
					$t->assign("tzn", '0');
					$t->assign("tzc", '0');
					$t->assign("tzs", '0');

					while($db->next_record())
					{
						if (trim(strtolower($db->Record['ZONA'])) == 'norte')
							$t->assign("tzn", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'centro')
							$t->assign("tzc", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'sur')
							$t->assign("tzs", $db->Record['TOTZONA'] . '');
					}
				}
				else
				{
					$t->assign("tzn", '0');
					$t->assign("tzc", '0');
					$t->assign("tzs", '0');
				}

				//LAS ZONAS REALIZADAS
				$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND V.estado_visita = 2 GROUP BY L.promotor ORDER BY ZONA ASC');
				if ($db->nf() > 0)
				{
					$t->assign("rzn", '0');
					$t->assign("rzc", '0');
					$t->assign("rzs", '0');

					while($db->next_record())
					{
						if (trim(strtolower($db->Record['ZONA'])) == 'norte')
							$t->assign("rzn", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'centro')
							$t->assign("rzc", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'sur')
							$t->assign("rzs", $db->Record['TOTZONA'] . '');
					}
				}
				else
				{
					$t->assign("rzn", '0');
					$t->assign("rzc", '0');
					$t->assign("rzs", '0');
				}

				//LAS ZONAS EN CURSO
				$db->query('SELECT L.promotor AS ZONA, Count(L.promotor) AS TOTZONA FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND LOWER(L.promotor) IN (\'norte\', \'sur\', \'centro\') AND (V.estado_visita = 1 OR V.estado_visita = 3) GROUP BY L.promotor ORDER BY ZONA ASC');
				if ($db->nf() > 0)
				{
					$t->assign("czn", '0');
					$t->assign("czc", '0');
					$t->assign("czs", '0');

					while($db->next_record())
					{
						if (trim(strtolower($db->Record['ZONA'])) == 'norte')
							$t->assign("czn", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'centro')
							$t->assign("czc", $db->Record['TOTZONA'] . '');
						if (trim(strtolower($db->Record['ZONA'])) == 'sur')
							$t->assign("czs", $db->Record['TOTZONA'] . '');
					}
				}
				else
				{
					$t->assign("czn", '0');
					$t->assign("czc", '0');
					$t->assign("czs", '0');
				}

				//LAS ZONAS REALIZADAS Y EN CURSO PARA EL GRAFICO
				$db->query('SELECT L.promotor AS ZONA, COUNT(IF(V.estado_visita = 2,1,NULL))AS TOTREAL, COUNT(IF(V.estado_visita = 1 or V.estado_visita = 3,1,NULL))AS TOTCURSO FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' AND LOWER(L.promotor)IN(\'norte\', \'sur\', \'centro\') GROUP BY L.promotor ORDER BY ZONA ASC');
				if ($db->nf() > 0)
				{
					$tf = 1;
					while($db->next_record())
					{
						$t->newBlock("zonas");
						$t->assign("zona7",$db->Record['ZONA']);
						$t->assign("totreal",$db->Record['TOTREAL']);
						$t->assign("totcurso",$db->Record['TOTCURSO']);
						if ($tf < $db->nf())
							$t->assign("coma7", ',');
						$tf++;
					}
				}
				else
				{
					$t->newBlock("zonas");
					$t->assign("zona7",'-');
					$t->assign("totreal",0);
					$t->assign("totcurso",0);
				}

				//$db->query('SELECT DAY(V.fecha_visita) AS DIA, Count(V.fecha_visita) AS TOTDIA FROM visitas AS V WHERE V.fecha_visita BETWEEN \'2015-03-01\' AND \'2015-03-31\' GROUP BY V.fecha_visita ORDER BY V.fecha_visita ASC');
				$db->query('SELECT DAY(V.fecha_visita) AS DIA, Count(V.fecha_visita) AS TOTDIA FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' and V.estado_visita = 2 GROUP BY V.fecha_visita ORDER BY V.fecha_visita ASC');
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
					$t->assign("dia",0);
					$t->assign("valdia",0);
				}

				//$db->query('SELECT V.estado_visita AS ESTADO, Count(V.estado_visita) AS TOTESTADO FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' GROUP BY V.estado_visita ORDER BY ESTADO ASC');
				$db->query('SELECT IF(V.estado_visita = 3,1,V.estado_visita) AS ESTADO,Count(IF(V.estado_visita = 3,1,V.estado_visita))AS TOTESTADO FROM visitas AS V INNER JOIN locales AS L ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' GROUP BY IF(V.estado_visita = 3,1,V.estado_visita) ORDER BY ESTADO DESC');
				if ($db->nf() > 0)
				{
					$tf = 1;
					$estvisita = '';
					$primerestado = '';
					while($db->next_record())
					{
						$t->newBlock("estados");
						if ($db->Record['ESTADO'] == 2)
							$estvisita = 'Realizada';
						if ($db->Record['ESTADO'] == 1 || $db->Record['ESTADO'] == 3)
							$estvisita = 'En Curso';
						if ($tf == 1)
						{
							if ($estvisita == 'Realizada')
								$ordencolor = '\'#5B9CD6\',\'#D3CECB\'';
							else
								$ordencolor = '\'#D3CECB\',\'#5B9CD6\'';
						}
						
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
					$t->assign("estado",'En Curso');
					$t->assign("total",0);
				}

				//franquicias
				$db->query('SELECT L.retail, COUNT(IF(V.estado_visita = 2,1,NULL)) AS TOTREAL, COUNT(IF(V.estado_visita = 1 or V.estado_visita = 3,1,NULL)) AS TOTCURSO FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $db2->Record['id'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['anio'] . ' and month(V.fecha_visita)= ' . $_REQUEST['mes'] . ' GROUP BY L.retail ORDER BY L.retail ASC LIMIT 0,5');
				if ($db->nf() > 0)
				{
					$tf = 1;
					while($db->next_record())
					{
						$t->newBlock("franquicias");
						$t->assign("franquicia",$db->Record['retail']);
						$t->assign("totreal4",$db->Record['TOTREAL']);
						$t->assign("totcurso4",$db->Record['TOTCURSO']);
						if ($tf < $db->nf())
							$t->assign("coma4", ',');
						$tf++;
					}
				}
				else
				{
					$t->newBlock("franquicias");
					$t->assign("franquicia",0);
					$t->assign("totreal4",0);
					$t->assign("totcurso4",0);
				}

				$t->gotoBlock("empresas");
				$t->assign("ordencolor", $ordencolor);

				$G1 = $G4 + 1;
				$G2 = $G4 + 2;
				$G3 = $G4 + 3;
				$G4 = $G4 + 4;
			}
		}
		else
		{
			$t->newBlock("empresas");
		}
	}
	else
	{
		$t->assign("sinempresas", '<br><br><br><br><strong>USUARIO NO TIENE EMPRESAS ASOCIADAS.</strong>');
	}

	//print the result
	$t->printToScreen();
}
?>