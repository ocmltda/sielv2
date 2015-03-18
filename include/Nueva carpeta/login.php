<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();

$t = new TemplatePower("login.html");
$t->prepare();

$db = new DB_Sql2;

$user=$_POST["usuario"];
$pass=$_POST["pass"];

			if($user!="" and $pass!="")
			{
			//Selec o consultar a la base de datos para ver si ya existe el correo asociado a la persona
		   $db->query("select count(*) as tot,user_id from lube_users where username='$user' and user_password='$pass' group by user_id");//pregunta
		   $db->next_record();//recorre
		   $cont = $db->Record["tot"];//si encuentra cont se le suma 1*/
		   
			   if($cont==1)
			   {
					$_SESSION["session"]=$db->Record['user_id'];			   
					header('Location: home_inge.php');
			   }else{
    			  $_SESSION["session"]="*error*";			   
       			  header('Location: home_inge.php');
			   }
		   }else{
			    $_SESSION["session"]="*error*";	
				 header('Location: home_inge.php');
		   }
//print the result
$t->printToScreen();
?>