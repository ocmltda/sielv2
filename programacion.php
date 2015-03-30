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

	$t = new TemplatePower("pla_programacion.html");
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

	$db2 = new DB_Sql;
	$db2->query('SELECT c.* FROM clientes c WHERE c.id IN (' . $_SESSION['empids'] . ') ORDER BY c.nombre');
	if ($db2->nf() > 0)
	{
		while($db2->next_record())
		{
			$t->newBlock("empresas");
			$t->assign("empresa",$db2->Record['nombre']);
			$t->assign('periodosel', $_REQUEST['anio'] . '-' . $_REQUEST['mes'] . '');
			
			$db->query('SELECT v.*, l.nombre, l.direccion, p.servicio, DATE_FORMAT(v.fecha_visita,\'%d-%m-%Y\') as fecvisita FROM visitas v, locales l, planillas p WHERE l.clientes_id = ' . $db2->Record['id'] . ' and v.locales_id = l.id and v.planillas_id = p.id and (month(v.fecha_visita) = ' . $_REQUEST['mes'] . ' and year(v.fecha_visita) = ' . $_REQUEST['anio'] . ')');
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

					$t->assign("estvisita",$estvisita);
					$t->assign("estrevision",$estrevision);
				}
			}
			else
			{
				//$t->newBlock("programaciones");
				//$t->assign("direccion", 'Sin información');
			}
		}
	}
	else
	{
		$t->newBlock("empresas");
	}

	//print the result
	$t->printToScreen();
}
?>