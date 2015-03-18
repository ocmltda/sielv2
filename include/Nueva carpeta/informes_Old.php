<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();

$t = new TemplatePower("informes.html");
$t->prepare();

$db = new DB_Sql;
$db->query("SELECT INGE_CORREO,INGE_PAGINA,INGE_TELEFONO,INGE_DIRECCION,INGE_PIE_PAGINA FROM INGELUBE_CONF");
$db->next_record();
$t->assign("tit_sit",$db->Record['INGE_PAGINA']);
$t->assign("pie_pag",$db->Record['INGE_PIE_PAGINA']);
$t->assign("fono",$db->Record['INGE_TELEFONO']);
$t->assign("direccion",$db->Record['INGE_DIRECCION']);

if($_SESSION["session"]!="" and $_SESSION["session"]!="*error*")
{
	$session=$_SESSION["session"];
	$t->assign("inicoment","<!--");
	$t->assign("fincoment","-->
				<div id='login_user'>
         	<label id='label_usuario'>Usuario Logeado<br />
         	</label><a href='logout.php' title='Cerrar Session'>Cerrar Session</a>
         </div>");
	
$db = new DB_Sql2;
$db->query("select user_empcodigo from lube_users where user_id=$session");
$db->next_record();
$usuario=$db->Record['user_empcodigo'];
//codigo empresa
$_SESSION["empresa"]=$usuario;
$db->query("select E.emp_rsocial,EQ.EQU_ID,EQ.EQU_NOMBRE from lube_empresa E,lube_equipos EQ,lube_users U where	U.user_empcodigo=$usuario and $usuario=E.emp_codigo and E.emp_codigo=EQ.equ_empresa and EQ.equ_parent=0 group by EQ.EQU_NOMBRE order by EQ.EQU_ID");
$cont=1;
		while($db->next_record())
		{	
			$t->assign("nom_empresa",$db->Record['emp_rsocial']);
			$t->newBlock("pdf");
			$t->assign("titulo",$db->Record['EQU_ID']);
			$t->assign("empresa",utf8_encode($db->Record['EQU_NOMBRE']));
			$t->assign("id",$db->Record['EQU_ID']);
			$cont=$cont+1;
		}
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
	$t->assign("nouser"," <div class='contenido_panamarillo'>
	                	<div class='txt_nombreinforme' align='center'>Ingrese usuario y password para ver el contenido.</div></div>");
}
//print the result
$t->printToScreen();
?>