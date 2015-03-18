<?php require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();
$t = new TemplatePower("grafico.html");

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
$btn1=$_POST["graficar1"];
$btn2=$_POST["graficar2"];
$btn3=$_POST["graficar3"];
$btn4=$_POST["graficar4"];
if($btn1!="")
{
	$t->assign("grafico","Grafico Propiedades Fisico Quimicas.");
	$ho=$_POST["ho"];
	$ox=$_POST["ox"];
	$ni=$_POST["ni"];
	$su=$_POST["su"];
	$di=$_POST["di"];
	$ag=$_POST["ag"];
	$gl=$_POST["gl"];
	$ad=$_POST["ad"];
	$vi40=$_POST["vi40"];
	$vi100=$_POST["vi100"];
	$indpi=$_POST["indpi"];
	$tb=$_POST["tb"];
	$ta=$_POST["ta"];
	$ga=$_POST["ga"];
	$ip=$_POST["ip"];
	$gc=$_POST["gc"];
	$indpu=$_POST["indpu"];
		
			if($ho!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ho);echo $mu1." ".$mu2." ".$mu3." ".$mu4." ".$mu5." ".$mu6."<br>";}
			if($ox!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ox);}
			if($ni!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ni);}
			if($su!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$su);}
			if($di!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$di);}
			if($ag!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ag);}
			if($gl!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$gl);}
			if($ad!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ad);}
			if($vi40!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$vi40);}
			if($vi100!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$vi100);}
			if($indpi!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$indpi);}
			if($tb!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$tb);}
			if($ta!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ta);}
			if($ga!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ga);}
			if($ip!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ip);}
			if($gc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$gc);}
			if($indpu!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$indpu);}
			//echo $mu1." ".$mu2." ".$mu3." ".$mu4." ".$mu5." ".$mu6."<br>";
		
		//muestro el grafico
		$pfq = $_POST["pfq"];
		for ($k=0; $k<count($pfq); $k++)
		{
			$t->newBlock("items");
			$valoresLinea = explode("|", $pfq[$k]);
			$valoresLinea[1] = str_replace("..", ".", $valoresLinea[1]);
			$valoresLinea[1] = str_replace(";", ",", $valoresLinea[1]);
			$t->assign("nombre",$valoresLinea[0] . "");
			$t->assign("datos", $valoresLinea[1] . "");
		}

}
if($btn2!="")
{
	$t->assign("grafico","Grafico Espectroemision fine(PPM).");
	$fe=$_POST["fe"];
	$cr=$_POST["cr"];
	$pb=$_POST["pb"];
	$cu=$_POST["cu"];
	$sn=$_POST["sn"];
	$al=$_POST["al"];
	$ni=$_POST["ni"];
	$ag=$_POST["ag"];
	$si=$_POST["si"];
	$b=$_POST["b"];
	$na=$_POST["na"];
	$mg=$_POST["mg"];
	$ca=$_POST["ca"];
	$ba=$_POST["ba"];
	$p=$_POST["p"];
	$n=$_POST["n"];
	$o=$_POST["o"];
	$ti=$_POST["ti"];
	$v=$_POST["v"];
	$k=$_POST["k"];

	if($fe!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$fe);}
	if($cr!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$cr);}
	if($pb!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$pb);}
	if($cu!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$cu);}
	if($sn!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$sn);}
	if($al!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$al);}
	if($ni!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ni);}
	if($ag!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ag);}
	if($si!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$si);}
	if($b!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$b);}
	if($na!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$na);}
	if($mg!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$mg);}
	if($ca!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ca);}
	if($ba!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ba);}
	if($p!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$p);}
	if($n!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$n);}
	if($o!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$o);}
	if($ti!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ti);}
	if($v!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$v);}
	if($k!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$k);}

	//muestro el grafico
	$ef = $_POST["ef"];
	for ($k=0; $k<count($ef); $k++)
	{
		$t->newBlock("items");
		$valoresLinea = explode("|", $ef[$k]);
		$valoresLinea[1] = str_replace("..", ".", $valoresLinea[1]);
		$valoresLinea[1] = str_replace(";", ",", $valoresLinea[1]);
		$t->assign("nombre",$valoresLinea[0] . "");
		$t->assign("datos", $valoresLinea[1] . "");
	}

}
if($btn3!="")
{
	$t->assign("grafico","Grafico Espectroemision Coarse(PPM).");
	$fec=$_POST["fec"];
	$crc=$_POST["crc"];
	$pbc=$_POST["pbc"];
	$cuc=$_POST["cuc"];
	$snc=$_POST["snc"];
	$alc=$_POST["alc"];
	$nic=$_POST["nic"];
	$agc=$_POST["agc"];
	$sic=$_POST["sic"];
	$bc=$_POST["bc"];
	$moc=$_POST["moc"];
	$tic=$_POST["tic"];
	
	if($fec!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$fec);}
	if($crc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$crc);}
	if($pbc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$pbc);}
	if($cuc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$cuc);}
	if($snc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$snc);}
	if($alc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$alc);}
	if($nic!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$nic);}
	if($agc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$agc);}
	if($sic!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$sic);}
	if($bc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$bc);}
	if($moc!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$moc);}
	if($tic!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$tic);}


	//muestro el grafico
	$ec = $_POST["ec"];
	for ($k=0; $k<count($ec); $k++)
	{
		$t->newBlock("items");
		$valoresLinea = explode("|", $ec[$k]);
		$valoresLinea[1] = str_replace("..", ".", $valoresLinea[1]);
		$valoresLinea[1] = str_replace(";", ",", $valoresLinea[1]);
		$t->assign("nombre",$valoresLinea[0] . "");
		$t->assign("datos", $valoresLinea[1] . "");
	}
	
}

if($btn4!="")
{
	$t->assign("grafico","Grafico Particulas(PPM).");
	$uno=$_POST["uno"];
	$dos=$_POST["dos"];
	$tres=$_POST["tres"];
	$cuatro=$_POST["cuatro"];
	$cinco=$_POST["cinco"];
	$seis=$_POST["seis"];
	$siete=$_POST["siete"];
	$ocho=$_POST["ocho"];

	if($uno!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$uno);}
	if($dos!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$dos);}
	if($tres!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$tres);}
	if($cuatro!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$cuatro);}
	if($cinco!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$cinco);}
	if($seis!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$seis);}
	if($siete!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$siete);}
	if($ocho!=""){list ($mu1,$mu2,$mu3,$mu4,$mu5,$mu6) = explode(";",$ocho);}


	//muestro el grafico
	$par = $_POST["par"];
	for ($k=0; $k<count($par); $k++)
	{
		$t->newBlock("items");
		$valoresLinea = explode("|", $par[$k]);
		$valoresLinea[1] = str_replace("..", ".", $valoresLinea[1]);
		$valoresLinea[1] = str_replace(";", ",", $valoresLinea[1]);
		$t->assign("nombre",$valoresLinea[0] . "");
		$t->assign("datos", $valoresLinea[1] . "");
	}
	
}

$t->printToScreen();
?>