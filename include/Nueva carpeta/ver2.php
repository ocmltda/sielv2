<?php
require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

session_start();
$t = new TemplatePower("ver2.html");

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
$id=$_GET["ide"];
$db = new DB_Sql2;
$db->query("select equ_nombre,equ_empresa from lube_equipos where equ_codigo=$id");
$db->next_record();
$empresa_id=$_SESSION["empresa"];;
$nombre_equipo=$db->Record['equ_nombre'];
//numero equipo
//echo "numero de equipo ".$id."<br>";
//numero empresa
//echo "numero de empresa ".$empresa_id."<br>";

$db->query("select EQ.equ_serie,EQ.equ_niso,EQ.equ_faena, A.AC_CODIGO,A.AC_NOMBRE, EQ.equ_componente,  E.emp_rsocial,U.user_firstname,U.user_lastname,E.emp_email,E.emp_tel1,E.emp_direccion,P.PA_NOMBRE,C.CI_NOMBRE,E.emp_soli 
		   from lube_empresa E,lube_users U,lube_pais P,lube_ciudad C,lube_equipos EQ, lube_aceite A, lube_componente B 
		   where  E.emp_codigo=$empresa_id and U.user_empcodigo=$empresa_id and E.emp_pais=P.pa_codigo and E.emp_ciudad=C.ci_codigo and 		EQ.equ_aceite=A.AC_CODIGO and B.CO_CODIGO=EQ.equ_componente");
$db->next_record();

$t->assign("empresa",utf8_encode($db->Record['emp_rsocial']));
$t->assign("contacto",utf8_encode($db->Record['user_firstname']));
$t->assign("contacto2"," ".utf8_encode($db->Record['user_lastname']));
$t->assign("e-mail"," ".utf8_encode($db->Record['emp_email']));
$t->assign("fono2"," ".utf8_encode($db->Record['emp_tel1']));
$t->assign("direccion2"," ".utf8_encode($db->Record['emp_direccion']));
$t->assign("pais"," ".utf8_encode($db->Record['PA_NOMBRE']));
$t->assign("ciudad"," ".utf8_encode($db->Record['CI_NOMBRE']));
$t->assign("solicitado"," ".utf8_encode($db->Record['emp_soli']));
$t->assign("equipo",utf8_encode($nombre_equipo));
$t->assign("iso"," ".utf8_encode($db->Record['equ_niso']));
$t->assign("faena"," ".utf8_encode($db->Record['equ_faena']));
$t->assign("serie"," ".utf8_encode($db->Record['equ_serie']));
//capacidad
$db->query("select equ_capacidad from lube_equipos where equ_codigo=$id");
$db->next_record();
$t->assign("capacidad"," ".utf8_encode($db->Record['equ_capacidad']));
//nombre del aceite
$db->query("select AC_NOMBRE,AC_MUESTRA from lube_aceite,lube_equipos where AC_CODIGO=equ_aceite and equ_empresa=$empresa_id and equ_codigo=$id");
$db->next_record();
//muestra aceite
$muestra_aceite=$db->Record['AC_MUESTRA'];
//echo "numero de muestra aceite ".$muestra_aceite."<br>";
$t->assign("aceite"," ".utf8_encode($db->Record['AC_NOMBRE']));
//aplicacion
$db->query("Select C.CO_NOMBRE From lube_componente C,lube_equipos E Where E.equ_codigo=$id and C.CO_CODIGO=E.equ_componente");
$db->next_record();
$t->assign("aplicacion"," ".$db->Record['CO_NOMBRE']);
$db->query("select mu_numero,mu_codigo from lube_muestra where mu_equipo=$id order by mu_numero desc limit 6");
//$cant=$db->nf();
//echo $cant;

//relleno

//$t->assign("obs0","as");
//$t->assign("obs1","as");
//$t->assign("obs2","as");
//$t->assign("obs3","as");
//$t->assign("obs4","as");
//$t->assign("obs5","as");
//$t->assign("obs6","as");
//
//$t->assign("n0","as");
//$t->assign("n1","as");
//$t->assign("n2","as");
//$t->assign("n3","as");
//$t->assign("n4","as");
//$t->assign("n5","as");
//$t->assign("n6","as");

//relleno
$cont=0;
while($db->next_record())
{
	$codigo[$cont]=$db->Record['mu_codigo'];
	$cont=$cont+1;
	echo $codigo[$cont];
}
////////////////////////////////////////////////////////DATOS DE LOS ELEMENTOS///////////////////////////////////////////////////////////
if($muestra_aceite!=0)
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_codigo=$muestra_aceite");
//	 B.mu_emp=$empresa_id and B.mu_equipo=$id and 
	$db->next_record();
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues0",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam0",$day."-".$month."-".$year);
	$t->assign("acu0",$db->Record['mu_relleno']);
	$t->assign("hrs0",$db->Record['mu_haceite']);
	$t->assign("n0",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs0",$db->Record['mu_obs']);
	}else{
		$t->assign("obs0","N/A");
	}

	
	$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$muestra_aceite and D.SPAC_CODIGO=$muestra_aceite and E.SPDC_CODIGO=$muestra_aceite and R.RO_CODIGO=$muestra_aceite",$db);
$db->next_record();
//PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A0",$db->Record['AV_HOLLIN']);
	$t->assign("B0",$db->Record['AV_OXIDACION']);
	$t->assign("C0",$db->Record['AV_NITRACION']);
	$t->assign("D0",$db->Record['AV_SULFATACION']);
	$t->assign("E0",$db->Record['AV_DILUCION']);
	$t->assign("F0",$db->Record['AV_AGUA']);
	$t->assign("G0",$db->Record['AV_GLICOL']);
	$t->assign("H0",$db->Record['AV_ADITIVO']);
	$t->assign("I0",$db->Record['AV_VIS40']);
	$t->assign("J0",$db->Record['AV_VIS100']);
	$t->assign("K0",$db->Record['AV_INDVI']);
	$t->assign("L0",$db->Record['AV_TBN']);
	$t->assign("M0",$db->Record['AV_TAN']);
	$t->assign("N0",$db->Record['AV_GA']);
	$t->assign("O0",$db->Record['AV_IPEN']);
	$t->assign("P0",$db->Record['AV_GCONT']);
	$t->assign("Q0",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	
	$t->assign("10",$db->Record['SPAC_FE']);
	$t->assign("20",$db->Record['SPAC_CR']);
	$t->assign("30",$db->Record['SPAC_PB']);
	$t->assign("40",$db->Record['SPAC_CU']);
	$t->assign("50",$db->Record['SPAC_SN']);
	$t->assign("60",$db->Record['SPAC_AL']);
	$t->assign("70",$db->Record['SPAC_NI']);
	$t->assign("80",$db->Record['SPAC_AG']);
	$t->assign("90",$db->Record['SPAC_SI']);
	$t->assign("100",$db->Record['SPAC_B']);
	$t->assign("110",$db->Record['SPAC_NA']);
	$t->assign("120",$db->Record['SPAC_MG']);
	$t->assign("130",$db->Record['SPAC_CA']);
	$t->assign("140",$db->Record['SPAC_BA']);
	$t->assign("150",$db->Record['SPAC_P']);
	$t->assign("160",$db->Record['SPAC_ZN']);
	$t->assign("170",$db->Record['SPAC_MO']);
	$t->assign("180",$db->Record['SPAC_TI']);
	$t->assign("190",$db->Record['SPAC_V']);
	$t->assign("200",$db->Record['SPAC_K']);
	//fin espectroemision fine(ppm)//
	
	//ESPECTROEMISION COARSE (PPM)//
	
	$t->assign("AA0",$db->Record['SPDC_FE']);
	$t->assign("AB0",$db->Record['SPDC_CR']);
	$t->assign("AC0",$db->Record['SPDC_PB']);
	$t->assign("AD0",$db->Record['SPDC_CU']);
	$t->assign("AE0",$db->Record['SPDC_SN']);
	$t->assign("AF0",$db->Record['SPDC_AL']);
	$t->assign("AG0",$db->Record['SPDC_NI']);
	$t->assign("AH0",$db->Record['SPDC_AG']);
	$t->assign("AI0",$db->Record['SPDC_SI']);
	$t->assign("AJ0",$db->Record['SPDC_B']);
	$t->assign("AK0",$db->Record['SPDC_MO']);
	$t->assign("AL0",$db->Record['SPDC_TI']);
	
	//Particulas
	
	$t->assign("1P0",$db->Record['RO_2_5']);
	$t->assign("2P0",$db->Record['RO_5_15']);
	$t->assign("3P0",$db->Record['RO_15_25']);
	$t->assign("4P0",$db->Record['RO_25_50']);
	$t->assign("5P0",$db->Record['RO_50_100']);
	$t->assign("6P0",$db->Record['RO_MAS100']);
	$t->assign("7P0",$db->Record['RO_ISO']);
	$t->assign("8P0",$db->Record['RO_IA']);
}
if($codigo[5]!="")
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and B.mu_equipo=$id and B.mu_codigo=$codigo[5]");
	$db->next_record();
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues1",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam1",$day."-".$month."-".$year);
	$t->assign("acu1",$db->Record['mu_relleno']);
	$t->assign("hrs1",$db->Record['mu_haceite']);
	$t->assign("n1",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs1",$db->Record['mu_obs']);
	}else{
		$t->assign("obs1","N/A");
	}
	
$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[5] and D.SPAC_CODIGO=$codigo[5] and E.SPDC_CODIGO=$codigo[5] and R.RO_CODIGO=$codigo[5]",$db);
$db->next_record();
//PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A",$db->Record['AV_HOLLIN']);
	$t->assign("B",$db->Record['AV_OXIDACION']);
	$t->assign("C",$db->Record['AV_NITRACION']);
	$t->assign("D",$db->Record['AV_SULFATACION']);
	$t->assign("E",$db->Record['AV_DILUCION']);
	$t->assign("F",$db->Record['AV_AGUA']);
	$t->assign("G",$db->Record['AV_GLICOL']);
	$t->assign("H",$db->Record['AV_ADITIVO']);
	$t->assign("I",$db->Record['AV_VIS40']);
	$t->assign("J",$db->Record['AV_VIS100']);
	$t->assign("K",$db->Record['AV_INDVI']);
	$t->assign("L",$db->Record['AV_TBN']);
	$t->assign("M",$db->Record['AV_TAN']);
	$t->assign("N",$db->Record['AV_GA']);
	$t->assign("O",$db->Record['AV_IPEN']);
	$t->assign("P",$db->Record['AV_GCONT']);
	$t->assign("Q",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	
	$t->assign("1",$db->Record['SPAC_FE']);
	$t->assign("2",$db->Record['SPAC_CR']);
	$t->assign("3",$db->Record['SPAC_PB']);
	$t->assign("4",$db->Record['SPAC_CU']);
	$t->assign("5",$db->Record['SPAC_SN']);
	$t->assign("6",$db->Record['SPAC_AL']);
	$t->assign("7",$db->Record['SPAC_NI']);
	$t->assign("8",$db->Record['SPAC_AG']);
	$t->assign("9",$db->Record['SPAC_SI']);
	$t->assign("10",$db->Record['SPAC_B']);
	$t->assign("11",$db->Record['SPAC_NA']);
	$t->assign("12",$db->Record['SPAC_MG']);
	$t->assign("13",$db->Record['SPAC_CA']);
	$t->assign("14",$db->Record['SPAC_BA']);
	$t->assign("15",$db->Record['SPAC_P']);
	$t->assign("16",$db->Record['SPAC_ZN']);
	$t->assign("17",$db->Record['SPAC_MO']);
	$t->assign("18",$db->Record['SPAC_TI']);
	$t->assign("19",$db->Record['SPAC_V']);
	$t->assign("20",$db->Record['SPAC_K']);
	//fin espectroemision fine(ppm)//
	
	//ESPECTROEMISION COARSE (PPM)//
	
	$t->assign("AA",$db->Record['SPDC_FE']);
	$t->assign("AB",$db->Record['SPDC_CR']);
	$t->assign("AC",$db->Record['SPDC_PB']);
	$t->assign("AD",$db->Record['SPDC_CU']);
	$t->assign("AE",$db->Record['SPDC_SN']);
	$t->assign("AF",$db->Record['SPDC_AL']);
	$t->assign("AG",$db->Record['SPDC_NI']);
	$t->assign("AH",$db->Record['SPDC_AG']);
	$t->assign("AI",$db->Record['SPDC_SI']);
	$t->assign("AJ",$db->Record['SPDC_B']);
	$t->assign("AK",$db->Record['SPDC_MO']);
	$t->assign("AL",$db->Record['SPDC_TI']);
	
	//Particulas
	
	$t->assign("1P",$db->Record['RO_2_5']);
	$t->assign("2P",$db->Record['RO_5_15']);
	$t->assign("3P",$db->Record['RO_15_25']);
	$t->assign("4P",$db->Record['RO_25_50']);
	$t->assign("5P",$db->Record['RO_50_100']);
	$t->assign("6P",$db->Record['RO_MAS100']);
	$t->assign("7P",$db->Record['RO_ISO']);
	$t->assign("8P",$db->Record['RO_IA']);
	
}
if($codigo[4]!="")
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and 	B.mu_equipo=$id and B.mu_codigo=$codigo[4]");
	$db->next_record();	
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues2",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam2",$day."-".$month."-".$year);
	$t->assign("acu2",$db->Record['mu_relleno']);
	$t->assign("hrs2",$db->Record['mu_haceite']);
	$t->assign("n2",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs2",$db->Record['mu_obs']);
	}else{
		$t->assign("obs2","N/A");
	}	
	
$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[4] and D.SPAC_CODIGO=$codigo[4] and E.SPDC_CODIGO=$codigo[4] and R.RO_CODIGO=$codigo[4]",$db);
$db->next_record();
 //PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A2",$db->Record['AV_HOLLIN']);
	$t->assign("B2",$db->Record['AV_OXIDACION']);
	$t->assign("C2",$db->Record['AV_NITRACION']);
	$t->assign("D2",$db->Record['AV_SULFATACION']);
	$t->assign("E2",$db->Record['AV_DILUCION']);
	$t->assign("F2",$db->Record['AV_AGUA']);
	$t->assign("G2",$db->Record['AV_GLICOL']);
	$t->assign("H2",$db->Record['AV_ADITIVO']);
	$t->assign("I2",$db->Record['AV_VIS40']);
	$t->assign("J2",$db->Record['AV_VIS100']);
	$t->assign("K2",$db->Record['AV_INDVI']);
	$t->assign("L2",$db->Record['AV_TBN']);
	$t->assign("M2",$db->Record['AV_TAN']);
	$t->assign("N2",$db->Record['AV_GA']);
	$t->assign("O2",$db->Record['AV_IPEN']);
	$t->assign("P2",$db->Record['AV_GCONT']);
	$t->assign("Q2",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	$t->assign("1A",$db->Record['SPAC_FE']);
	$t->assign("2A",$db->Record['SPAC_CR']);
	$t->assign("3A",$db->Record['SPAC_PB']);
	$t->assign("4A",$db->Record['SPAC_CU']);
	$t->assign("5A",$db->Record['SPAC_SN']);
	$t->assign("6A",$db->Record['SPAC_AL']);
	$t->assign("7A",$db->Record['SPAC_NI']);
	$t->assign("8A",$db->Record['SPAC_AG']);
	$t->assign("9A",$db->Record['SPAC_SI']);
	$t->assign("10A",$db->Record['SPAC_B']);
	$t->assign("11A",$db->Record['SPAC_NA']);
	$t->assign("12A",$db->Record['SPAC_MG']);
	$t->assign("13A",$db->Record['SPAC_CA']);
	$t->assign("14A",$db->Record['SPAC_BA']);
	$t->assign("15A",$db->Record['SPAC_P']);
	$t->assign("16A",$db->Record['SPAC_ZN']);
	$t->assign("17A",$db->Record['SPAC_MO']);
	$t->assign("18A",$db->Record['SPAC_TI']);
	$t->assign("19A",$db->Record['SPAC_V']);
	$t->assign("20A",$db->Record['SPAC_K']);
	
	//ESPECTROEMISION COARSE (PPM)//
	$t->assign("BA",$db->Record['SPDC_FE']);
	$t->assign("BB",$db->Record['SPDC_CR']);
	$t->assign("BC",$db->Record['SPDC_PB']);
	$t->assign("BD",$db->Record['SPDC_CU']);
	$t->assign("BE",$db->Record['SPDC_SN']);
	$t->assign("BF",$db->Record['SPDC_AL']);
	$t->assign("BG",$db->Record['SPDC_NI']);
	$t->assign("BH",$db->Record['SPDC_AG']);
	$t->assign("BI",$db->Record['SPDC_SI']);
	$t->assign("BJ",$db->Record['SPDC_B']);
	$t->assign("BK",$db->Record['SPDC_MO']);
	$t->assign("BL",$db->Record['SPDC_TI']);
	
	//Particulas
	$t->assign("1PA",$db->Record['RO_2_5']);
	$t->assign("2PA",$db->Record['RO_5_15']);
	$t->assign("3PA",$db->Record['RO_15_25']);
	$t->assign("4PA",$db->Record['RO_25_50']);
	$t->assign("5PA",$db->Record['RO_50_100']);
	$t->assign("6PA",$db->Record['RO_MAS100']);
	$t->assign("7PA",$db->Record['RO_ISO']);
	$t->assign("8PA",$db->Record['RO_IA']);
}
if($codigo[3]!="")
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and 	B.mu_equipo=$id and B.mu_codigo=$codigo[3]");
	$db->next_record();
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues3",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam3",$day."-".$month."-".$year);
	$t->assign("acu3",$db->Record['mu_relleno']);
	$t->assign("hrs3",$db->Record['mu_haceite']);
	$t->assign("n3",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs3",$db->Record['mu_obs']);
	}else{
		$t->assign("obs3","N/A");
	}
	
$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[3] and D.SPAC_CODIGO=$codigo[3] and E.SPDC_CODIGO=$codigo[3] and R.RO_CODIGO=$codigo[3]",$db);
$db->next_record();
	//PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A3",$db->Record['AV_HOLLIN']);
	$t->assign("B3",$db->Record['AV_OXIDACION']);
	$t->assign("C3",$db->Record['AV_NITRACION']);
	$t->assign("D3",$db->Record['AV_SULFATACION']);
	$t->assign("E3",$db->Record['AV_DILUCION']);
	$t->assign("F3",$db->Record['AV_AGUA']);
	$t->assign("G3",$db->Record['AV_GLICOL']);
	$t->assign("H3",$db->Record['AV_ADITIVO']);
	$t->assign("I3",$db->Record['AV_VIS40']);
	$t->assign("J3",$db->Record['AV_VIS100']);
	$t->assign("K3",$db->Record['AV_INDVI']);
	$t->assign("L3",$db->Record['AV_TBN']);
	$t->assign("M3",$db->Record['AV_TAN']);
	$t->assign("N3",$db->Record['AV_GA']);
	$t->assign("O3",$db->Record['AV_IPEN']);
	$t->assign("P3",$db->Record['AV_GCONT']);
	$t->assign("Q3",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	$t->assign("1B",$db->Record['SPAC_FE']);
	$t->assign("2B",$db->Record['SPAC_CR']);
	$t->assign("3B",$db->Record['SPAC_PB']);
	$t->assign("4B",$db->Record['SPAC_CU']);
	$t->assign("5B",$db->Record['SPAC_SN']);
	$t->assign("6B",$db->Record['SPAC_AL']);
	$t->assign("7B",$db->Record['SPAC_NI']);
	$t->assign("8B",$db->Record['SPAC_AG']);
	$t->assign("9B",$db->Record['SPAC_SI']);
	$t->assign("10B",$db->Record['SPAC_B']);
	$t->assign("11B",$db->Record['SPAC_NA']);
	$t->assign("12B",$db->Record['SPAC_MG']);
	$t->assign("13B",$db->Record['SPAC_CA']);
	$t->assign("14B",$db->Record['SPAC_BA']);
	$t->assign("15B",$db->Record['SPAC_P']);
	$t->assign("16B",$db->Record['SPAC_ZN']);
	$t->assign("17B",$db->Record['SPAC_MO']);
	$t->assign("18B",$db->Record['SPAC_TI']);
	$t->assign("19B",$db->Record['SPAC_V']);
	$t->assign("20B",$db->Record['SPAC_K']);
	
	//ESPECTROEMISION COARSE (PPM)//
	$t->assign("CA",$db->Record['SPDC_FE']);
	$t->assign("CB",$db->Record['SPDC_CR']);
	$t->assign("CC",$db->Record['SPDC_PB']);
	$t->assign("CD",$db->Record['SPDC_CU']);
	$t->assign("CE",$db->Record['SPDC_SN']);
	$t->assign("CF",$db->Record['SPDC_AL']);
	$t->assign("CG",$db->Record['SPDC_NI']);
	$t->assign("CH",$db->Record['SPDC_AG']);
	$t->assign("CI",$db->Record['SPDC_SI']);
	$t->assign("CJ",$db->Record['SPDC_B']);
	$t->assign("CK",$db->Record['SPDC_MO']);
	$t->assign("CL",$db->Record['SPDC_TI']);
	
	//Particulas
	$t->assign("1PB",$db->Record['RO_2_5']);
	$t->assign("2PB",$db->Record['RO_5_15']);
	$t->assign("3PB",$db->Record['RO_15_25']);
	$t->assign("4PB",$db->Record['RO_25_50']);
	$t->assign("5PB",$db->Record['RO_50_100']);
	$t->assign("6PB",$db->Record['RO_MAS100']);
	$t->assign("7PB",$db->Record['RO_ISO']);
	$t->assign("8PB",$db->Record['RO_IA']);
}
if($codigo[2]!="")
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and 	B.mu_equipo=$id and B.mu_codigo=$codigo[2]");
	$db->next_record();	
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues4",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam4",$day."-".$month."-".$year);
	$t->assign("acu4",$db->Record['mu_relleno']);
	$t->assign("hrs4",$db->Record['mu_haceite']);
	$t->assign("n4",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs4",$db->Record['mu_obs']);
	}else{
		$t->assign("obs4","N/A");
	}	
	
$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[2] and D.SPAC_CODIGO=$codigo[2] and E.SPDC_CODIGO=$codigo[2] and R.RO_CODIGO=$codigo[2]",$db);
$db->next_record();
     //PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A4",$db->Record['AV_HOLLIN']);
	$t->assign("B4",$db->Record['AV_OXIDACION']);
	$t->assign("C4",$db->Record['AV_NITRACION']);
	$t->assign("D4",$db->Record['AV_SULFATACION']);
	$t->assign("E4",$db->Record['AV_DILUCION']);
	$t->assign("F4",$db->Record['AV_AGUA']);
	$t->assign("G4",$db->Record['AV_GLICOL']);
	$t->assign("H4",$db->Record['AV_ADITIVO']);
	$t->assign("I4",$db->Record['AV_VIS40']);
	$t->assign("J4",$db->Record['AV_VIS100']);
	$t->assign("K4",$db->Record['AV_INDVI']);
	$t->assign("L4",$db->Record['AV_TBN']);
	$t->assign("M4",$db->Record['AV_TAN']);
	$t->assign("N4",$db->Record['AV_GA']);
	$t->assign("O4",$db->Record['AV_IPEN']);
	$t->assign("P4",$db->Record['AV_GCONT']);
	$t->assign("Q4",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	$t->assign("1C",$db->Record['SPAC_FE']);
	$t->assign("2C",$db->Record['SPAC_CR']);
	$t->assign("3C",$db->Record['SPAC_PB']);
	$t->assign("4C",$db->Record['SPAC_CU']);
	$t->assign("5C",$db->Record['SPAC_SN']);
	$t->assign("6C",$db->Record['SPAC_AL']);
	$t->assign("7C",$db->Record['SPAC_NI']);
	$t->assign("8C",$db->Record['SPAC_AG']);
	$t->assign("9C",$db->Record['SPAC_SI']);
	$t->assign("10C",$db->Record['SPAC_B']);
	$t->assign("11C",$db->Record['SPAC_NA']);
	$t->assign("12C",$db->Record['SPAC_MG']);
	$t->assign("13C",$db->Record['SPAC_CA']);
	$t->assign("14C",$db->Record['SPAC_BA']);
	$t->assign("15C",$db->Record['SPAC_P']);
	$t->assign("16C",$db->Record['SPAC_ZN']);
	$t->assign("17C",$db->Record['SPAC_MO']);
	$t->assign("18C",$db->Record['SPAC_TI']);
	$t->assign("19C",$db->Record['SPAC_V']);
	$t->assign("20C",$db->Record['SPAC_K']);
	
	//ESPECTROEMISION COARSE (PPM)//
	$t->assign("DA",$db->Record['SPDC_FE']);
	$t->assign("DB",$db->Record['SPDC_CR']);
	$t->assign("DC",$db->Record['SPDC_PB']);
	$t->assign("DD",$db->Record['SPDC_CU']);
	$t->assign("DE",$db->Record['SPDC_SN']);
	$t->assign("DF",$db->Record['SPDC_AL']);
	$t->assign("DG",$db->Record['SPDC_NI']);
	$t->assign("DH",$db->Record['SPDC_AG']);
	$t->assign("DI",$db->Record['SPDC_SI']);
	$t->assign("DJ",$db->Record['SPDC_B']);
	$t->assign("DK",$db->Record['SPDC_MO']);
	$t->assign("DL",$db->Record['SPDC_TI']);
	
	//Particulas
	$t->assign("1PC",$db->Record['RO_2_5']);
	$t->assign("2PC",$db->Record['RO_5_15']);
	$t->assign("3PC",$db->Record['RO_15_25']);
	$t->assign("4PC",$db->Record['RO_25_50']);
	$t->assign("5PC",$db->Record['RO_50_100']);
	$t->assign("6PC",$db->Record['RO_MAS100']);
	$t->assign("7PC",$db->Record['RO_ISO']);
	$t->assign("8PC",$db->Record['RO_IA']);
}
if($codigo[1]!="")
{
	$db->query("select B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and 	B.mu_equipo=$id and B.mu_codigo=$codigo[1]");
	$db->next_record();
    $mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues5",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam5",$day."-".$month."-".$year);
	$t->assign("acu5",$db->Record['mu_relleno']);
	$t->assign("hrs5",$db->Record['mu_haceite']);
	$t->assign("n5",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs5",$db->Record['mu_obs']);
	}else{
		$t->assign("obs5","N/A");
	}	
	
$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[1] and D.SPAC_CODIGO=$codigo[1] and E.SPDC_CODIGO=$codigo[1] and R.RO_CODIGO=$codigo[1]",$db);
$db->next_record();
  //PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A5",$db->Record['AV_HOLLIN']);
	$t->assign("B5",$db->Record['AV_OXIDACION']);
	$t->assign("C5",$db->Record['AV_NITRACION']);
	$t->assign("D5",$db->Record['AV_SULFATACION']);
	$t->assign("E5",$db->Record['AV_DILUCION']);
	$t->assign("F5",$db->Record['AV_AGUA']);
	$t->assign("G5",$db->Record['AV_GLICOL']);
	$t->assign("H5",$db->Record['AV_ADITIVO']);
	$t->assign("I5",$db->Record['AV_VIS40']);
	$t->assign("J5",$db->Record['AV_VIS100']);
	$t->assign("K5",$db->Record['AV_INDVI']);
	$t->assign("L5",$db->Record['AV_TBN']);
	$t->assign("M5",$db->Record['AV_TAN']);
	$t->assign("N5",$db->Record['AV_GA']);
	$t->assign("O5",$db->Record['AV_IPEN']);
	$t->assign("P5",$db->Record['AV_GCONT']);
	$t->assign("Q5",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	$t->assign("1D",$db->Record['SPAC_FE']);
	$t->assign("2D",$db->Record['SPAC_CR']);
	$t->assign("3D",$db->Record['SPAC_PB']);
	$t->assign("4D",$db->Record['SPAC_CU']);
	$t->assign("5D",$db->Record['SPAC_SN']);
	$t->assign("6D",$db->Record['SPAC_AL']);
	$t->assign("7D",$db->Record['SPAC_NI']);
	$t->assign("8D",$db->Record['SPAC_AG']);
	$t->assign("9D",$db->Record['SPAC_SI']);
	$t->assign("10D",$db->Record['SPAC_B']);
	$t->assign("11D",$db->Record['SPAC_NA']);
	$t->assign("12D",$db->Record['SPAC_MG']);
	$t->assign("13D",$db->Record['SPAC_CA']);
	$t->assign("14D",$db->Record['SPAC_BA']);
	$t->assign("15D",$db->Record['SPAC_P']);
	$t->assign("16D",$db->Record['SPAC_ZN']);
	$t->assign("17D",$db->Record['SPAC_MO']);
	$t->assign("18D",$db->Record['SPAC_TI']);
	$t->assign("19D",$db->Record['SPAC_V']);
	$t->assign("20D",$db->Record['SPAC_K']);

	//ESPECTROEMISION COARSE (PPM)//
	$t->assign("EA",$db->Record['SPDC_FE']);
	$t->assign("EB",$db->Record['SPDC_CR']);
	$t->assign("EC",$db->Record['SPDC_PB']);
	$t->assign("ED",$db->Record['SPDC_CU']);
	$t->assign("EE",$db->Record['SPDC_SN']);
	$t->assign("EF",$db->Record['SPDC_AL']);
	$t->assign("EG",$db->Record['SPDC_NI']);
	$t->assign("EH",$db->Record['SPDC_AG']);
	$t->assign("EI",$db->Record['SPDC_SI']);
	$t->assign("EJ",$db->Record['SPDC_B']);
	$t->assign("EK",$db->Record['SPDC_MO']);
	$t->assign("EL",$db->Record['SPDC_TI']);
	
	//Particulas
	$t->assign("1PD",$db->Record['RO_2_5']);
	$t->assign("2PD",$db->Record['RO_5_15']);
	$t->assign("3PD",$db->Record['RO_15_25']);
	$t->assign("4PD",$db->Record['RO_25_50']);
	$t->assign("5PD",$db->Record['RO_50_100']);
	$t->assign("6PD",$db->Record['RO_MAS100']);
	$t->assign("7PD",$db->Record['RO_ISO']);
	$t->assign("8PD",$db->Record['RO_IA']);
    
}
if($codigo[0]!="")
{
	$db->query("select B.mu_comentarios,B.mu_estado,B.mu_fecha,B.mu_fcambio,B.mu_relleno,B.mu_haceite,B.mu_numero,B.mu_obs from lube_muestra B where B.mu_emp=$empresa_id and B.mu_equipo=$id and B.mu_codigo=$codigo[0]");
	$db->next_record();
	$mues1=$db->Record['mu_fecha'];
	list( $year, $month, $day ) = split( '[/.-]', $mues1);
	$t->assign("mues6",$day."-".$month."-".$year);
	$cam1=$db->Record['mu_fcambio'];
	list( $year, $month, $day ) = split( '[/.-]', $cam1);
	$t->assign("cam6",$day."-".$month."-".$year);
	$t->assign("acu6",$db->Record['mu_relleno']);
	$t->assign("hrs6",$db->Record['mu_haceite']);
	$t->assign("n6",$db->Record['mu_numero']);
	if($db->Record['mu_obs']!="")
	{
		$t->assign("obs6",$db->Record['mu_obs']);
	}else{
		$t->assign("obs6","N/A");
	}
	$t->assign("coment",$db->Record['mu_muestra']);	
	$estado=$db->Record['mu_estado'];
	$db->query("select est_nombre from lube_estado where est_codigo=$estado");
	$t->assign("estado",$db->Record['est_nombre']);	

$db->query("select 
C.AV_CODIGO,C.AV_HOLLIN,C.AV_OXIDACION,C.AV_NITRACION,
C.AV_SULFATACION,C.AV_DILUCION,C.AV_AGUA,C.AV_GLICOL,
C.AV_ADITIVO,C.AV_VIS40,C.AV_VIS100,
C.AV_INDVI,C.AV_TBN,C.AV_TAN,C.AV_GA,
C.AV_IPEN,C.AV_GCONT,C.AV_INDPU,
D.SPAC_CODIGO,D.SPAC_FE,D.SPAC_CR,
D.SPAC_PB,D.SPAC_CU,D.SPAC_SN,
D.SPAC_AL,D.SPAC_NI,D.SPAC_AG,
D.SPAC_V,D.SPAC_TI,D.SPAC_SI,
D.SPAC_B,D.SPAC_NA,D.SPAC_K,D.SPAC_MG,
D.SPAC_CA,D.SPAC_BA,D.SPAC_P,D.SPAC_ZN,
D.SPAC_MO,D.SPAC_H,
R.RO_2_5,R.RO_5_15,R.RO_15_25,R.RO_25_50,R.RO_50_100,R.RO_MAS100,R.RO_ISO,R.RO_IA
from lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E,lube_royco R
where B.mu_codigo=$id and C.AV_CODIGO=$codigo[0] and D.SPAC_CODIGO=$codigo[0] and E.SPDC_CODIGO=$codigo[0] and R.RO_CODIGO=$codigo[0]",$db);
$db->next_record();
	//PROPIEDADES FISICOS QUIMICAS
	//$t->assign("ver",$db->Record['mu_codigo']);
	$t->assign("A6",$db->Record['AV_HOLLIN']);
	$t->assign("B6",$db->Record['AV_OXIDACION']);
	$t->assign("C6",$db->Record['AV_NITRACION']);
	$t->assign("D6",$db->Record['AV_SULFATACION']);
	$t->assign("E6",$db->Record['AV_DILUCION']);
	$t->assign("F6",$db->Record['AV_AGUA']);
	$t->assign("G6",$db->Record['AV_GLICOL']);
	$t->assign("H6",$db->Record['AV_ADITIVO']);
	$t->assign("I6",$db->Record['AV_VIS40']);
	$t->assign("J6",$db->Record['AV_VIS100']);
	$t->assign("K6",$db->Record['AV_INDVI']);
	$t->assign("L6",$db->Record['AV_TBN']);
	$t->assign("M6",$db->Record['AV_TAN']);
	$t->assign("N6",$db->Record['AV_GA']);
	$t->assign("O6",$db->Record['AV_IPEN']);
	$t->assign("P6",$db->Record['AV_GCONT']);
	$t->assign("Q6",$db->Record['AV_INDPU']);
	
	//ESPECTROEMISION FINE(PPM)//
	$t->assign("1E",$db->Record['SPAC_FE']);
	$t->assign("2E",$db->Record['SPAC_CR']);
	$t->assign("3E",$db->Record['SPAC_PB']);
	$t->assign("4E",$db->Record['SPAC_CU']);
	$t->assign("5E",$db->Record['SPAC_SN']);
	$t->assign("6E",$db->Record['SPAC_AL']);
	$t->assign("7E",$db->Record['SPAC_NI']);
	$t->assign("8E",$db->Record['SPAC_AG']);
	$t->assign("9E",$db->Record['SPAC_SI']);
	$t->assign("10E",$db->Record['SPAC_B']);
	$t->assign("11E",$db->Record['SPAC_NA']);
	$t->assign("12E",$db->Record['SPAC_MG']);
	$t->assign("13E",$db->Record['SPAC_CA']);
	$t->assign("14E",$db->Record['SPAC_BA']);
	$t->assign("15E",$db->Record['SPAC_P']);
	$t->assign("16E",$db->Record['SPAC_ZN']);
	$t->assign("17E",$db->Record['SPAC_MO']);
	$t->assign("18E",$db->Record['SPAC_TI']);
	$t->assign("19E",$db->Record['SPAC_V']);
	$t->assign("20E",$db->Record['SPAC_K']);
	
	//ESPECTROEMISION COARSE (PPM)//
	$t->assign("FA",$db->Record['SPDC_FE']);
	$t->assign("FB",$db->Record['SPDC_CR']);
	$t->assign("FC",$db->Record['SPDC_PB']);
	$t->assign("FD",$db->Record['SPDC_CU']);
	$t->assign("FE",$db->Record['SPDC_SN']);
	$t->assign("FF",$db->Record['SPDC_AL']);
	$t->assign("FG",$db->Record['SPDC_NI']);
	$t->assign("FH",$db->Record['SPDC_AG']);
	$t->assign("FI",$db->Record['SPDC_SI']);
	$t->assign("FJ",$db->Record['SPDC_B']);
	$t->assign("FK",$db->Record['SPDC_MO']);
	$t->assign("FL",$db->Record['SPDC_TI']);
	
	//Particulas
	$t->assign("1PE",$db->Record['RO_2_5']);
	$t->assign("2PE",$db->Record['RO_5_15']);
	$t->assign("3PE",$db->Record['RO_15_25']);
	$t->assign("4PE",$db->Record['RO_25_50']);
	$t->assign("5PE",$db->Record['RO_50_100']);
	$t->assign("6PE",$db->Record['RO_MAS100']);
	$t->assign("7PE",$db->Record['RO_ISO']);
	$t->assign("8PE",$db->Record['RO_IA']);
	
	$db->query("select M.mu_comentarios,E.est_nombre from lube_muestra M,lube_estado E where M.mu_codigo=$codigo[0] and M.mu_estado=E.est_codigo");
	$db->next_record();
	$t->assign("coment",utf8_encode($db->Record['mu_comentarios']));
	$t->assign("estado",utf8_encode($db->Record['est_nombre']));
	
}
$t->printToScreen();
?>