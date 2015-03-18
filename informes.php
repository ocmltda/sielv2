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

	$t = new TemplatePower("pla_informes.html");
	$t->prepare();

	$db = new DB_Sql;

	$db->query('SELECT i.*, DATE_FORMAT(i.fecha_publicacion,\'%d-%m-%Y\') as fecpublic FROM informes i');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("informes");
			$t->assign("id",$db->Record['informes_id']);
			$t->assign("informe",$db->Record['nombre']);
			$t->assign("periodo",$db->Record['periodo']);
			$t->assign("fecpublic",$db->Record['fecpublic']);
			$t->assign("estado",$db->Record['estado']);
		}
	}
	else
	{
		$t->newBlock("informes");
	}

	//print the result
	$t->printToScreen();
//}
?>