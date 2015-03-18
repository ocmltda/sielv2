<?php

require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

$t = new TemplatePower("datos.html");
session_start();
$t->prepare();


$id1=$_REQUEST["id1"];
$id2=$_REQUEST["id2"];
$id3=$_REQUEST["id3"];
$id4=$_REQUEST["id4"];
$id5=$_REQUEST["id5"];
$id6=$_REQUEST["id6"];


///////////////////////////////////////////////////////////////////DATOS DE LA EMPRESA///////////////////////////////////////////////////
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
	$t->assign("nouser"," <div class='pan_amarillo_abrir'></div>
              	<div class='pan_amarillo_central'>
           	    <div class='contenido_titulo'>Informes Laboratorio</div>
               		<div class='contenido_panamarillo'>
	                	<div class='txt_nombreinforme' align='center'>Ingrese usuario y password para ver el contenido.</div>
                    </div>
                    </div>
                <div class='pan_amarillo_cierre'></div>");
}
$db = new DB_Sql2;
$db->query("SELECT E.emp_id,E.emp_rsocial, E.emp_tel1,E.emp_fax,E.emp_direccion,E.emp_email,E.emp_soli,E.emp_comuna,
E.emp_ciudad,E.emp_pais,U.user_firstname,U.user_lastname,P.PA_CODIGO,P.PA_NOMBRE,E.emp_ciudad,C.CI_CODIGO,C.CI_NOMBRE,Q.EQU_ID,Q.EQU_PARENT,Q.EQU_NOMBRE
FROM lube_empresa E,lube_users U,lube_pais P, lube_ciudad C,lube_equipos Q
where $session=U.user_id and $session=E.emp_parent and P.PA_CODIGO=E.emp_pais and E.emp_ciudad=C.CI_CODIGO and Q.EQU_EMPRESA=E.emp_id");
$db->next_record();

$t->assign("empresa",$db->Record['emp_rsocial']);
$t->assign("contacto",$db->Record['user_firstname']);
$t->assign("contacto2"," ".$db->Record['user_lastname']);
$t->assign("e-mail"," ".$db->Record['emp_email']);
$t->assign("fono2"," ".$db->Record['emp_tel1']);
$t->assign("direccion2"," ".$db->Record['emp_direccion']);
$t->assign("pais"," ".$db->Record['PA_NOMBRE']);
$t->assign("ciudad"," ".$db->Record['CI_NOMBRE']);
$t->assign("solicitado"," ".$db->Record['emp_soli']);
$t->assign("equipo"," ".$db->Record['EQU_NOMBRE']);

////////////////////////////////////////////////////////DATOS DE LOS ELEMENTOS///////////////////////////////////////////////////////////
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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id1 and C.AV_CODIGO=$id1 and D.SPAC_CODIGO=$id1 and E.SPDC_CODIGO=$id1 and R.RO_CODIGO=$id1 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//
$t->assign("ver",$db->Record['mu_codigo']);
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


//FIN PROPIEDADES FISICO QUIMICOS//

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


////////////////////////////////////////////////////////////////fin id 1///////////////////////////////////////////////////////////////////

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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id2 and C.AV_CODIGO=$id2 and D.SPAC_CODIGO=$id2 and E.SPDC_CODIGO=$id2 and R.RO_CODIGO=$id2 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//

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


//FIN PROPIEDADES FISICO QUIMICOS//

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
//fin espectroemision fine(ppm)//

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
////////////////////////////////////////////////////////////////fin id 2////////////////////////////////////////////////////////

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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id3 and C.AV_CODIGO=$id3 and D.SPAC_CODIGO=$id3 and E.SPDC_CODIGO=$id3 and R.RO_CODIGO=$id3 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//

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


//FIN PROPIEDADES FISICO QUIMICOS//

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
//fin espectroemision fine(ppm)//

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
/////////////////////////////////////////////////////////////////////////////////FIN D3////////////////////////////////////////////////////

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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id4 and C.AV_CODIGO=$id4 and D.SPAC_CODIGO=$id4 and E.SPDC_CODIGO=$id4 and R.RO_CODIGO=$id4 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//

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


//FIN PROPIEDADES FISICO QUIMICOS//

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
//fin espectroemision fine(ppm)//

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
////////////////////////////////////////////////////////////////FIN D4/////////////////////////////////////////////////////////////////////

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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id5 and C.AV_CODIGO=$id5 and D.SPAC_CODIGO=$id5 and E.SPDC_CODIGO=$id4 and R.RO_CODIGO=$id4 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//

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


//FIN PROPIEDADES FISICO QUIMICOS//

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
//fin espectroemision fine(ppm)//

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
/////////////////////////////////////////////////////////////////////FIN ID5/////////////////////////////////////////////////////////////


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
from  lube_muestra B,lube_avatar C,lube_spac D,lube_spdc E, lube_royco R
where B.mu_codigo=$id6 and C.AV_CODIGO=$id6 and D.SPAC_CODIGO=$id6 and E.SPDC_CODIGO=$id6 and R.RO_CODIGO=$id6 group by C.AV_CODIGO",$db);
$db->next_record();

//PROPIEDADES FISICOS QUIMICAS//

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


//FIN PROPIEDADES FISICO QUIMICOS//

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
//fin espectroemision fine(ppm)//

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

$t->printToScreen();


?>