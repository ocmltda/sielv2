<?php
//creamos la sesion
//session_start();
	
//validamos si se ha hecho o no el inicio de sesion correctamente
	
//si no se ha hecho la sesion nos regresará a login.php
/*if(!isset($_SESSION['ulogin']))
{
	header('Location: login.php');
	exit();
}
else
{*/
	/*$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	@$cliente = $request->selCategorias;
	echo $cliente;
exit();*/
	print_r($_POST);
exit();
	$_POSTT = json_decode(file_get_contents('php://input'), true);
//print_r($_POSTT);
	echo $_POSTT['selCategorias'];

	require_once('include/class.TemplatePower.inc.php');
	require_once('include/db_mysql.inc');

	echo 'INSERT INTO alertas (fecha, hora, locales_id, coordenadas, incidencia, comentarios, tiposacciones_id, fotografia, clientes_id, tiposincidencias_id) VALUES (\'' . date('Y-m-d') . '\', \'' . date('H:i:s') . '\', ' . $_POST['local'] . ', \'\', \'\', \'' . $_POST['comentario'] . '\', null, \'' . $_FILES['foto']['name'] . '\', ' . $_POST['cliente'] . ', ' . $_POST['incidencia'] . ')';
	exit();

	$db = new DB_Sql;
	$db->query('INSERT INTO alertas (fecha, hora, locales_id, coordenadas, incidencia, comentarios, tiposacciones_id, fotografia, clientes_id, tiposincidencias_id) VALUES (\'' . date('Y-m-d') . '\', \'' . date('H:i:s') . '\', ' . $_POST['local'] . ', \'\', \'\', \'' . $_POST['comentario'] . '\', null, \'' . $_FILES['foto']['name'] . '\', ' . $_POST['cliente'] . ', ' . $_POST['incidencia'] . ')');

	if($_FILES['foto']['name'])
	{
		//if no errors...
		if(!$_FILES['foto']['error'])
		{
			//now is the time to modify the future file name and validate the file
			$new_file_name = strtolower($_FILES['foto']['name']); //rename file
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
				move_uploaded_file($_FILES['foto']['tmp_name'], 'imgalerta/'.$new_file_name);
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
//}
?>