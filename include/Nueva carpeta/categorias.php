<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/funciones.php');

$regXpag = 3;
if (!isset($_GET["pag"]))
    $pag = 1;
else
    $pag = $_GET["pag"];

session_start();

$t = new TemplatePower("categorias.html");
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
$id=$_GET["id"];
$id_categoria=$_GET["id2"];
if($id_categoria=="")
{
		if($id!="")
		{
			$_SESSION["id_reg"]=$id;	
		}
		if($_SESSION["id_reg"]==1)
		{
			//equipos
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_productos.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_equipos.jpg' width='628' height='131'/>");

		}
		if($_SESSION["id_reg"]==2)
		{
			//laboratorios
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_laboratorio.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_laboratorios.jpg' width='628' height='131'/>");
		}
		if($_SESSION["id_reg"]==3)
		{
			//noticias
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_blogynoticias.jpg");
		}
		if($_SESSION["id_reg"]==4)
		{
			//termografia
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_termografia.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_termografia.jpg' width='628' height='131'/>");
		}
		if($_SESSION["id_reg"]==5)
		{
			//Asesoria en Lubricacion
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_asesorias.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_asesorias.jpg' width='628' height='131'/>");
		}
	$id_register=$_SESSION["id_reg"];
	$db = new DB_Sql;
	$db->query("SELECT count(*) as total FROM TEMA WHERE CATE_ID=$id_register");
	$db->next_record();
	$totReg = $db->Record["total"];
	$db->query("SELECT TEM_TITULO,TEM_DESCRIPCION,TEM_IMAGEN FROM TEMA WHERE CATE_ID=$id_register LIMIT " . (($pag - 1) * $regXpag) . "," . ($regXpag));
		while($db->next_record())
		{
			$t->newBlock("categorias");
			$t->assign("titulo",$db->Record['TEM_TITULO']);
			$t->assign("imagen","mantenedor/imagenes_temas/".$db->Record['TEM_IMAGEN']);
			$t->assign("descripcion",str_replace("\n", "<br>", $db->Record['TEM_DESCRIPCION']));	
		}
}else{
		$db->query("SELECT CATE_ID FROM TEMA WHERE TEM_ID=$id_categoria");
		$db->next_record();
		$id_registro=$db->Record["CATE_ID"];
		
		if(id_registro==1)
		{
			//equipos
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_productos.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_equipos.jpg' width='628' height='131'/>");

		}
		if(id_registro==2)
		{
			//laboratorios
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_laboratorio.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_laboratorios.jpg' width='628' height='131'/>");
		}
		if(id_registro==3)
		{
			//noticias
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_blogynoticias.jpg");
		}
		if(id_registro==4)
		{
			//termografia
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_termografia.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_termografia.jpg' width='628' height='131'/>");
		}
		if(id_registro==5)
		{
			//Asesoria en Lubricacion
			$t->assign("_ROOT.titulo_panel","imagenes/Panel_contenido/titulopanel_asesorias.jpg");
			$t->assign("_ROOT.titulo_imagen","<img src='imagenes/Panel_contenido/Panelcabecera_asesorias.jpg' width='628' height='131'/>");
		}
		$db->query("SELECT TEM_TITULO,TEM_DESCRIPCION,TEM_IMAGEN FROM TEMA WHERE TEM_ID=$id_categoria");
		while($db->next_record())
		{
			$t->newBlock("categorias");
			$t->assign("titulo",$db->Record['TEM_TITULO']);
			$t->assign("imagen","mantenedor/imagenes_temas/".$db->Record['TEM_IMAGEN']);
			$t->assign("descripcion",str_replace("\n", "<br>", $db->Record['TEM_DESCRIPCION']));	
		}
}
paginar($regXpag, $pag, $getVars, $totReg);
//print the result
$t->printToScreen();
?>