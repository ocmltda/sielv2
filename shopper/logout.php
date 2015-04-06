<?php
//ob_start();
//Crear sesión
session_start();
//Vaciar sesión
$_SESSION = array();
//Destruir Sesión
session_destroy();
//Redireccionar a login.php
header("Location: login.php");
//echo "<script>window.location.href='index.php'</script>";
//die();
//exit();
//echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';    
//exit;
?>