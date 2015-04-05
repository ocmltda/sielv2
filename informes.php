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

	$t = new TemplatePower("pla_informes.html");
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

				$db->query('SELECT i.*, DATE_FORMAT(i.periodo,\'%Y-%m\') as periodo, DATE_FORMAT(i.fecha_publicacion,\'%d-%m-%Y\') as fecpublic FROM informes i WHERE i.clientes_id = ' . $db2->Record['id'] . ' and DATE_FORMAT(i.periodo,\'%Y-%m\') = \'' . $_REQUEST['anio'] . '-' . $_REQUEST['mes'] . '\' order by DATE_FORMAT(i.periodo,\'%Y-%m\') desc');
				if ($db->nf() > 0)
				{
					while($db->next_record())
					{
						$t->newBlock("informes");
						$t->assign("id",$db->Record['informes_id']);
						$t->assign("informe",$db->Record['nombre']);
						$t->assign("periodo",$db->Record['periodo']);
						$t->assign("fecpublic",$db->Record['fecpublic']);

						$estado = $db->Record['estado'];
						switch ($estado) {
							case 1:
								$estado = 'En Proceso';
								break;
							case 2:
								$estado = 'Terminado';
								break;
							default:
							   $estado = 'En Proceso';
						}
						$t->assign("estado", $estado . '');

						$archivo = trim($db->Record['archivo']);

						if ($archivo)
							$t->assign("acciones", '<a href="informes/' . $archivo . '">Descargar</a>');
						else
							$t->assign("acciones", '-');
					}
				}
				else
				{
					//$t->newBlock("informes");
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