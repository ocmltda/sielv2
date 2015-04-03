<?php
	require_once('../include/db_mysql.inc');

	$db = new DB_Sql;
	$db->query('SELECT CA.id, concat(CA.nombre, \' - \', CA.direccion) AS nomdir FROM locales AS CA INNER JOIN clientes AS PI ON CA.clientes_id = PI.id WHERE PI.id = ' . $_GET['svalue'] . ' order by CA.nombre');
	echo '<option value="" selected disabled>Seleccione el Local</option>';
	while($db->next_record())
	{
		echo '<option value="'.$db->Record['id'].'">' . $db->Record['nomdir'] . "</option>";
	}
?>