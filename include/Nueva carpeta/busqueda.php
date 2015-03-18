<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');
require_once('includeM/funciones.php');

session_start();

$t = new TemplatePower("busqueda.html");
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
$buscar=$_POST["buscar"];
$cont=0;

if($buscar!="")
{
	$db->query("SELECT SEM_ID,SEM_TITULO FROM CALENDARIO_SEMINARIO WHERE SEM_STATUS=1 AND SEM_TITULO LIKE '%$buscar%' OR SEM_CUERPO LIKE '%$buscar%'");
	while($db->next_record())
	{	
		$t->newBlock("buscar");
		$t->assign("titulo","<a href=seminarios.php?id2=".$db->Record['SEM_ID'].">".$db->Record['SEM_TITULO']."</a>"." Seminario<br>");
		$cont=1;
	}
	$db->query("SELECT ID_CER,TITULO_CER FROM CERTIFICACION_INGELUBE WHERE TITULO_CER LIKE '%$buscar%'");
	while($db->next_record())
	{	
		$t->newBlock("buscar");
		$t->assign("titulo","<a href=certificacion.php?id2=".$db->Record['ID_CER'].">".$db->Record['TITULO_CER']."</a>"." Certificacion<br>");
		$cont=1;
	}
	$db->query("SELECT ENSA_ID,ENSA_NOMBRE FROM ENSAYO WHERE ENSA_NOMBRE LIKE '%$buscar%' OR ENSA_NORMA LIKE '%$buscar%'");
	while($db->next_record())
	{	
		$t->newBlock("buscar");
		$t->assign("titulo","<a href=ensayos.php?id2=".$db->Record['ENSA_ID'].">".$db->Record['ENSA_NOMBRE']."</a>"." Ensayo<br>");
		$cont=1;
	}
	$db->query("SELECT TEM_ID,TEM_TITULO FROM TEMA WHERE TEM_TITULO LIKE '%$buscar%' OR TEM_DESCRIPCION LIKE '%$buscar%'");
	while($db->next_record())
	{	
		$t->newBlock("buscar");
		$t->assign("titulo","<a href=categorias.php?id2=".$db->Record['TEM_ID'].">".$db->Record['TEM_TITULO']."</a>"." Categorias Varias<br>");
		$cont=1;
	}
}else{
	$t->assign("mensaje","<div align='center'>Campo buscar en blanco</div>");
}
if($cont==0)
{
	$t->assign("mensaje","<div align='center'>No existen coincidencias</div>");
}
//print the result
$t->printToScreen();
?>
