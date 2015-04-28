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

	$t = new TemplatePower("pla_incidencia.html");
	$t->prepare();

	$db = new DB_Sql;

	$IDTA = 0;
	$db->query('SELECT a.*, l.nombre, DATE_FORMAT(a.fecha,\'%d-%m-%Y\') as fecvisita, t.*, ti.tipi_nombre FROM alertas a left join tipos_acciones t ON a.tiposacciones_id = t.tipos_acciones_id, locales l, tiposincidencias ti WHERE a.id = ' . $_REQUEST['IA'] . ' and a.locales_id = l.id and a.tiposincidencias_id = ti.tipi_id');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->assign("id",$db->Record['id']);
			$t->assign("fecha", 'Fecha: ' . $db->Record['fecvisita']);
			$t->assign("hora", 'Hora: ' . $db->Record['hora']);
			$t->assign("local",$db->Record['nombre']);
			$t->assign("gps",$db->Record['coordenadas']);
			$t->assign("incidencia",$db->Record['tipi_nombre']);
			$t->assign("comentario",$db->Record['comentarios']);
			//echo '<pre>' . htmlspecialchars('<img src="imgincidencias/' . $db->Record['fotografia'] . '">') . '</pre>';

			$t->assign("foto", '<img src="imgalerta/' . $db->Record['fotografia'] . '" width="240">');
			$IDTA = $db->Record['tipos_acciones_id'];
		}
	}

	$db->query('SELECT * FROM tipos_acciones');
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$t->newBlock("tipos_acciones");
			$t->assign("idta", $db->Record['tipos_acciones_id']);
			$t->assign("tipoaccion",$db->Record['accion']);
			if ($db->Record['tipos_acciones_id'] == $IDTA)
				$t->assign("selected", 'selected');
		}
	}
	else
	{
		$t->newBlock("tipos_acciones");
	}

	//consulto estado de la alerta
	$db->query('SELECT A.estado, L.nombre AS LOCAL, C.nombre AS CLIENTE FROM alertas AS A INNER JOIN clientes AS C ON C.id = A.clientes_id INNER JOIN locales AS L ON L.id = A.locales_id WHERE A.id = ' . $_REQUEST['IA']);
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			$estadoalerta = $db->Record['estado'];
			$nombrecliente = $db->Record['CLIENTE'];
			$localcliente = $db->Record['LOCAL'];
		}
	}

	if ($estadoalerta == 1)
	{
		//actualizo el estado
		$db->query('UPDATE alertas SET estado = 2 WHERE id = ' . $_REQUEST['IA']);

		//envio de mails
		//$to = 'eayalamail@gmail.com';
		$to = 'ocmchile@gmail.com';

		$subject = "ALERTA LOCAL " . $localcliente . " REVISADA POR " . $_SESSION['uname'] . " - " . date('d-m-Y H:i');
				 
		$message = '<html><head> <title>ALERTA REVISADA</title></head><body><p>Informamos que el usuario ' . $_SESSION['uname'] . ' ha revisado la Alerta del Local ' . $localcliente . ', del Cliente ' . $nombrecliente . ' en la fecha ' . date('d-m-Y H:i') . '.</p><br></body></html>';

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";

		$headers .= "From: Tack < no_responder@tack.cl >\r\n";
		//$headers .= "Cc: obarria@chilevision.cl\r\n";
		//$headers .= "Bcc: birthdaycheck@example.com\r\n";

		// and now mail it 
		//echo "$to, $subject, $message, $headers";
		mail($to, $subject, $message, $headers);
	}

	//print the result
	$t->printToScreen();
//}
?>