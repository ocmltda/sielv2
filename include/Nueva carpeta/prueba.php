<?php

require_once('includeM/setglobals.php');
require_once('includeM/class.TemplatePower.inc.php');
require_once('includeM/db_mysql.inc');
require_once('includeM/db_mysql2.inc');

$t = new TemplatePower("prueba.html");

$t->prepare();

$db = new DB_Sql2;
$db->query("select EQU_ID,EQU_PARENT,EQU_NOMBRE from lube_equipos WHERE EQU_EMPRESA=254 order by EQU_ID ",$db);


while($db->next_record())
{
$t->newBlock("DATOS");
$t->assign("usuario",$db->Record['EQU_ID']);
$t->assign("empresa",$db->Record['EQU_NOMBRE']);

}


$t->printToScreen();


?>