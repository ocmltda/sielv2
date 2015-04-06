<?php
//creamos la sesion
session_start();
	
//validamos si se ha hecho o no el inicio de sesion correctamente
	
//si no se ha hecho la sesion nos regresará a login.php
if(!isset($_SESSION['sulogin']))
{
	header('Location: login.php');
	exit();
}
else
{
	header('Location: addalerta.php');
}
?>