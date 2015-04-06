<?php
	require_once('../include/db_mysql.inc');

	$db = new DB_Sql;
	$db->query('SELECT tipi_id, tipi_nombre FROM tiposincidencias WHERE clientes_id = ' . $_GET['svalue'] . ' order by tipi_nombre');
	echo '<option value="" selected disabled>Seleccione la Incidencia</option>';
	while($db->next_record())
	{
		echo '<option value="'.$db->Record['tipi_id'].'">' . $db->Record['tipi_nombre'] . "</option>";
	}
?>