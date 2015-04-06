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

	$t = new TemplatePower("pla_alertas.html");
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

				$db->query('SELECT a.*, l.nombre, DATE_FORMAT(a.fecha,\'%d-%m-%Y\') as fecvisita, t.tipi_nombre FROM alertas a, locales l, tiposincidencias t WHERE a.locales_id = l.id AND a.tiposincidencias_id = t.tipi_id AND a.clientes_id = ' . $db2->Record['id'] . ' and YEAR(a.fecha) = ' . $_REQUEST['anio'] . ' AND MONTH(a.fecha) = ' . $_REQUEST['mes'] . ' ORDER BY a.estado ASC, a.fecha DESC, a.hora DESC');
				if ($db->nf() > 0)
				{
					while($db->next_record())
					{
						$t->newBlock("alertas");
						$t->assign("id",$db->Record['id']);
						$t->assign("fecha",$db->Record['fecvisita']);
						$t->assign("hora",$db->Record['hora']);
						$t->assign("tienda",$db->Record['nombre']);
						$t->assign("gps",$db->Record['coordenadas']);
						$t->assign("incidencia",$db->Record['tipi_nombre']);
						$t->assign("comentario",$db->Record['comentarios']);
						$estado = $db->Record['estado'];
						if ($estado == 1)
							$t->assign("estado", 'Informada');
						else
							$t->assign("estado", 'Revisada');
					}
				}
				else
				{
					//$t->newBlock("alertas");
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