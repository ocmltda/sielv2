<?php
require_once('include/db_mysql.inc');
  
/*caturamos nuestros datos que fueron enviados desde el formulario mediante el metodo POST
**y los almacenamos en variables.*/
$usuario = trim($_POST["usuario"]);
$password = $_POST["contrasenia"];

$db = new DB_Sql;

/*Consulta de mysql con la que indicamos que necesitamos que seleccione
**solo los campos que tenga como nombre_administrador el que el formulario
**le ha enviado*/
$db->query("SELECT * FROM usuarios WHERE usuario = '$usuario'");
 
//Validamos si el nombre del administrador existe en la base de datos o es correcto
if ($db->nf() > 0)
{
	$db->next_record();

	//Si el usuario es correcto ahora validamos su contraseña
	if($db->Record['password'] == $password)
	{
		//Creamos sesión
		session_start(); 
		//Almacenamos el nombre de usuario en una variable de sesión usuario
		$_SESSION['ulogin'] = $usuario;
		$_SESSION['uname'] = $db->Record['nombre'];
		$_SESSION['uid'] = $db->Record['id'];
		$_SESSION['utus'] = $db->Record['tipos_usuarios_id'];
		//Redireccionamos a la pagina: index.php
		header("Location: index.php");
	}
	else
	{
	//En caso que la contraseña sea incorrecta enviamos un msj y redireccionamos a login.php
?>
<meta charset="utf-8">
	<script languaje="javascript">
	alert("Contraseña Incorrecta");
	location.href = "login.php";
	</script>
<?php
	}
}
else
{
 //en caso que el nombre de administrador es incorrecto enviamos un msj y redireccionamos a login.php
?>
 <script languaje="javascript">
  alert("El nombre de usuario es incorrecto!");
  location.href = "login.php";
 </script>
<?php  
         
}
?>