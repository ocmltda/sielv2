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

$t = new TemplatePower("certificacion.html");
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
$id_certificacion=$_GET["id2"];

if($id_certificacion=="")
{
$db->query("SELECT count(*) as total FROM CERTIFICACION_INGELUBE");
$db->next_record();
$totReg = $db->Record["total"];
$db->query("SELECT TITULO_CER,NOMBRE_CER,LINK_CER,NOMBRE_CER1,LINK_CER1,NOMBRE_CER2,LINK_CER2,NOMBRE_CER3,LINK_CER3,NOMBRE_CER4,LINK_CER4,IMAGEN_CER FROM CERTIFICACION_INGELUBE LIMIT " . (($pag - 1) * $regXpag) . "," . ($regXpag));
	while($db->next_record())
	{
			$t->newBlock("certificacion");
			$t->assign("titulo",$db->Record['TITULO_CER']);
			$t->assign("imagen1","mantenedor/imagenes_certificacion/".$db->Record['IMAGEN_CER']);
			
				if($db->Record['LINK_CER']!="")
				{
				$t->assign("link","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER1']!="")
				{
				$t->assign("link1","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER1']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER1'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER2']!="")
				{
				$t->assign("link2","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER2']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER2'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER3']!="")
				{
				$t->assign("link3","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER3']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER3'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER4']!="")
				{
				$t->assign("link4","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER4']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER4'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
	}
}else{
		$db->query("SELECT TITULO_CER,NOMBRE_CER,LINK_CER,NOMBRE_CER1,LINK_CER1,NOMBRE_CER2,LINK_CER2,NOMBRE_CER3,LINK_CER3,NOMBRE_CER4,LINK_CER4,IMAGEN_CER FROM CERTIFICACION_INGELUBE WHERE ID_CER=$id_certificacion");
	while($db->next_record())
	{
			$t->newBlock("certificacion");
			$t->assign("titulo",$db->Record['TITULO_CER']);
			$t->assign("imagen1","mantenedor/imagenes_certificacion/".$db->Record['IMAGEN_CER']);
			
				if($db->Record['LINK_CER']!="")
				{
				$t->assign("link","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER1']!="")
				{
				$t->assign("link1","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER1']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER1'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER2']!="")
				{
				$t->assign("link2","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER2']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER2'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER3']!="")
				{
				$t->assign("link3","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER3']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER3'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
				if($db->Record['LINK_CER4']!="")
				{
				$t->assign("link4","<div class='link_representacion'>
								   <div class='iso'></div>
								   <div class='texto_representacion'>".$db->Record['NOMBRE_CER4']."</div>
								   <div class='btn_verficha'>
										<a href=".$db->Record['LINK_CER4'].">
										<img src='imagenes/Panel_contenido/boton_verficha.jpg' width='100' height='15' border='0'/>
										</a>
								   </div>
							  </div>");
				}
	}
}
paginar($regXpag, $pag, $getVars, $totReg);
//print the result
$t->printToScreen();
?>