<?php
	require_once('../include/class.TemplatePower.inc.php');
	require_once('../include/db_mysql.inc');

	if (isset($_POST['enviar']))
	{
		$db = new DB_Sql;
		$new_file_name = date('Ymd_His').'_'.strtolower($_FILES['foto']['name']);
		$db->query('INSERT INTO alertas (fecha, hora, locales_id, coordenadas, incidencia, comentarios, tiposacciones_id, fotografia, clientes_id, tiposincidencias_id) VALUES (\'' . date('Y-m-d') . '\', \'' . date('H:i:s') . '\', ' . $_POST['local'] . ', \'' . $_POST['lat'] . ',' . $_POST['lon'] . '\', \'\', \'' . $_POST['comentario'] . '\', null, \'' . $new_file_name . '\', ' . $_POST['cliente'] . ', ' . $_POST['incidencia'] . ')');

		if($_FILES['foto']['name'])
		{
			//if no errors...
			if(!$_FILES['foto']['error'])
			{
				//now is the time to modify the future file name and validate the file
				 //rename file
				$valid_file = true;
				/*if($_FILES['foto']['size'] > (1024000)) //can't be larger than 1 MB
				{
					$valid_file = false;
					$message = 'Oops!  Your file\'s size is to large.';
				}*/
				
				//if the file has passed the test
				if($valid_file)
				{
					//move it to where we want it to be
					move_uploaded_file($_FILES['foto']['tmp_name'], '../imgalerta/'.$new_file_name);
					$message = 'Congratulations!  Your file was accepted.';
				}
			}
			//if there is an error...
			else
			{
				//set that to be the returned message
				$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['foto']['error'];
			}
		}

		echo '<meta http-equiv="refresh" content="0; url=okalerta.php" />';
	}
	else
	{

		$t = new TemplatePower("pla_addalerta.html");
		$t->prepare();

		$db = new DB_Sql;
		$db->query('SELECT id, nombre FROM clientes order by nombre');
		while($db->next_record())
		{
			$t->newBlock('clientes');
			$t->assign('idcli', $db->Record['id'] . '');
			$t->assign('nomcli', $db->Record['nombre'] . '');
		}

		$db->query('SELECT tipi_id, tipi_nombre FROM tiposincidencias order by tipi_nombre');
		while($db->next_record())
		{
			$t->newBlock('incidencias');
			$t->assign('idinc', $db->Record['tipi_id'] . '');
			$t->assign('nominc', $db->Record['tipi_nombre'] . '');
		}

		//print the result
		$t->printToScreen();
	}
?>