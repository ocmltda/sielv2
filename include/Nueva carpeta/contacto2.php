<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');

session_start();

$t = new TemplatePower("contacto2.html");
$t->prepare();

$db = new DB_Sql;
$db->query("SELECT INGE_CORREO,INGE_PAGINA,INGE_TELEFONO,INGE_DIRECCION,INGE_PIE_PAGINA FROM INGELUBE_CONF");
$db->next_record(); 
$t->assign("tit_sit",$db->Record['INGE_PAGINA']);
$t->assign("pie_pag",$db->Record['INGE_PIE_PAGINA']);
$t->assign("fono",$db->Record['INGE_TELEFONO']);
$t->assign("direccion",$db->Record['INGE_DIRECCION']);
$t->assign("correo",$db->Record['INGE_CORREO']);

if($_SESSION["session"]!="" and $_SESSION["session"]!="*error*")
{	
	$session=$_SESSION["session"];
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario Logeado<br />
         	</label><a href='logout.php' title='Cerrar Session'>Cerrar Session</a>
         </div>");
}else{
	if($_SESSION["session"]=="*error*")
	{
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario no existe<br />
         	</label><a href='volver.php' title='volver'>Volver</a>
         </div>");
	}
}
echo $nom=$_POST["nom"];
echo $ape=$_POST["ape"];
echo $emp=$_POST["emp"];
echo $car=$_POST["car"];
echo $pais=$_POST["pais"];
echo $fono=$_POST["fono"];
echo $email=$_POST["email"];
echo $sitio=$_POST["sitio"];
echo $men=$_POST["men"];

if(email_valido($email)){

	if($nom!="" && $ape!="" && $emp!="" &&$car!=""&& $pais!="" && $fono!="" && $email!="" && $men!="")
	{
	$headers  = "Formulario enviado\n\n";
	$headers .= "Nombre: " .$nom . "\n". "\n";
	$headers .= "Apellido: " .$ape . "\n". "\n";
	$headers .= "Empresa: " .$emp . "\n". "\n";
	$headers .= "Cargo: " .$car . "\n". "\n";
	$headers .= "Pais: " .$pais . "\n". "\n";
	$headers .= "Fono: " .$fono. "\n". "\n";
	$headers .= "mail: " .$email . "\n". "\n";
	$headers .= "Sitio: " .$sitio . "\n". "\n";
	$headers .= "Mensaje: ". "\n" .$men . "\n". "\n";
	
	$correo=$db->Record['INGE_CORREO'];
	$asunto="nuevo contacto desde la web ingelube";
	/*$mensaje="Nombre:"."   ".$nom."Apellido:"."    ".$ape."  "."Empresa:"."    ".$emp."  "."Cargo"."    ".$car."  "."Pais:"."    ".$pais."  "."Fono"."    ".$fono."   "."Email"."    ".$email."   "."Sitio"."    ".$sitio."    "."Mensaje"."    ".$men;
	*/
	
	mail($correo,$asunto,$headers); 
	
	$t->assign("mensaje","mail entregado con exito");
	}else{	
		$t->assign("mensaje","faltan ingresar datos");
	}//fin else
}else{
	$t->assign("mensaje","correo no valido");
}
function email_valido($email)
{
	if (preg_match('!^[a-z0-9.+-_]+@([a-z0-9-]+(?:.[a-z0-9-]+)+)$!i',$email,$partes))
	{
	// Comprobar que el dominio es correcto
	if(!checkdnsrr($partes[1]))
	return FALSE;
	else
	return TRUE;
	}
	else
	return FALSE;
}
//print the result
$t->printToScreen();
?>