<?php
//creamos la sesion
session_start();

$mesesAnio=array(1=>'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
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

	$t = new TemplatePower("pla_evaluaciones.html");
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
			while($db2->next_record())
			{
				$t->newBlock("empresas");
				$t->assign("empresa",$db2->Record['nombre']);
				$t->assign('periodosel', $_REQUEST['anio'] . '-' . $_REQUEST['mes'] . '');

				$t->assign("nommes", $mesesAnio[($_REQUEST['mes']*1)]);

				$db->query('SELECT v.id,DATE_FORMAT(v.fecha_visita,\'%d-%m-%Y\') as fecvisita,lo.nombre,p.servicio,lo.direccion FROM visitas AS v INNER JOIN locales AS lo ON lo.id = v.locales_id INNER JOIN planillas AS p ON p.id = v.planillas_id WHERE lo.clientes_id = ' . $db2->Record['id'] . ' and (month(v.fecha_visita) = ' . $_REQUEST['mes'] . ' and year(v.fecha_visita) = ' . $_REQUEST['anio'] . ') and v.estado_visita = 2 and v.estado_revision = 2 ORDER BY v.fecha_visita DESC');
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
						$t->assign("empresa2",$db2->Record['nombre'] . '');
						$t->assign("local2",$db->Record['nombre'] . '');

						$db3 = new DB_Sql;

						$db3->query('SELECT Sum(res.puntaje_obtenido) AS puntaje FROM visitas AS v INNER JOIN respuestas AS res ON v.id = res.visitas_id WHERE v.id = ' . $db->Record['id']);
						if ($db3->nf() > 0)
						{
							$db3->next_record();
							$t->assign("puntaje",$db3->Record['puntaje']);
						}

						//$t->assign("estado",$db->Record['estado']);
					}
				}
				else
				{
					//$t->newBlock("evaluaciones");
				}
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