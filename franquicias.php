<?php
//creamos la sesion
session_start();
	
//validamos si se ha hecho o no el inicio de sesion correctamente
	
//si no se ha hecho la sesion nos regresará a login.php
/*if(!isset($_SESSION['ulogin']))
{
	header('Location: login.php');
	exit();
}
else
{*/
	require_once('include/class.TemplatePower.inc.php');
	require_once('include/db_mysql.inc');

	$t = new TemplatePower("pla_franquicia.html");
	$t->prepare();

	$db = new DB_Sql;

	//franquicias
	$db->query('SELECT L.retail, COUNT(IF(V.estado_visita = 2,1,NULL)) AS TOTREAL, COUNT(IF(V.estado_visita = 1 or V.estado_visita = 3,1,NULL)) AS TOTCURSO FROM locales AS L INNER JOIN visitas AS V ON L.id = V.locales_id WHERE L.clientes_id = ' . $_REQUEST['CLI'] . ' AND year(V.fecha_visita) = ' . $_REQUEST['ANIO'] . ' and month(V.fecha_visita)= ' . $_REQUEST['MES'] . ' GROUP BY L.retail ORDER BY L.retail ASC');
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

	//print the result
	$t->printToScreen();
//}
?>