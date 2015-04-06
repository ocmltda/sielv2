<?php
	require_once('../include/db_mysql.inc');

	/*caturamos nuestros datos que fueron enviados desde el formulario mediante el metodo POST
	**y los almacenamos en variables.*/
	$usuario = trim($_GET["usern"]);
	$password = $_GET["passw"];

	$db = new DB_Sql;

	/*Consulta de mysql con la que indicamos que necesitamos que seleccione
	**solo los campos que tenga como nombre_administrador el que el formulario
	**le ha enviado*/
	$db->query("SELECT U.usuario, U.nombre, U.id, U.tipos_usuarios_id, U.`password`, EU.clientes_id FROM usuarios AS U LEFT JOIN empusu AS EU ON U.id = EU.usuarios_id WHERE U.usuario = '" . $usuario . "' and U.tipos_usuarios_id = 3");
	 
	//Validamos si el nombre del administrador existe en la base de datos o es correcto
	if ($db->nf() > 0)
	{
		while($db->next_record())
		{
			//Si el usuario es correcto ahora validamos su contraseña
			if($db->Record['password'] == $password)
			{
				//Creamos sesión
				@session_start(); 
				//Almacenamos el nombre de usuario en una variable de sesión usuario
				$_SESSION['sulogin'] = $usuario;
				$_SESSION['suname'] = $db->Record['nombre'];
				$_SESSION['suid'] = $db->Record['id'];
				/*if (!isset($_SESSION['empids']))
					$_SESSION['empids'] = $db->Record['clientes_id'];
				else
					$_SESSION['empids'] .= ',' . $db->Record['clientes_id'];*/
				echo 'SI';
			}
			else
			{
				echo 'Contraseña Incorrecta';
			}
		}
	}
	else
	{
		echo 'El nombre de usuario es incorrecto!';
	}
?>