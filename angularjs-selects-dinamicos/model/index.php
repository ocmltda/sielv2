<?php
/*****************************/
/***DESARROLLO HIDROCALIDO****/
/*****************************/
require 'connector.php';
// TOMAMOS NUESTRO JSON RECIBIDO DESDE LA PETICION DE ANGULAR JS Y LO LEEMOS
$JSON       = file_get_contents("php://input");
$request    = json_decode($JSON);
$metodo     = $request->metodo; 
if( $metodo == 'obtenerCategorias' ){
  obtenerCategorias();
}else if( $metodo == 'obtenerIncidencias' ){
  obtenerIncidencias();
}else if ($metodo == 'obtenerPistos') {
  $idCategoria = $request->idCategoria;  
  obtenerPistos($idCategoria);
}

function obtenerPistos($idCategoria){
    $sql ="SELECT CA.id, concat(CA.nombre, ' - ', CA.direccion) AS nomdir FROM locales AS CA INNER JOIN clientes AS PI ON CA.clientes_id = PI.id WHERE PI.id = '$idCategoria' order by CA.nombre"; 
    try {
        $db = getConnection();
		//$stmt = $db->query("SET NAMES 'latin1'");
        $stmt = $db->query($sql);  
        $detalle = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"pistos": ' . json_encode($detalle) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerCategorias(){
    $sql ="SELECT id, nombre FROM clientes order by nombre"; 
    try {
        $db = getConnection();
        //$stmt = $db->query("SET NAMES 'latin1'");  
        $stmt = $db->query($sql);  
        $detalle = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"categorias2": ' . json_encode($detalle) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerIncidencias(){
    $sql ="SELECT tipi_id, tipi_nombre FROM tiposincidencias order by tipi_nombre"; 
    try {
        $db = getConnection();
        //$stmt = $db->query("SET NAMES 'latin1'");  
        $stmt = $db->query($sql);  
        $detalle = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"incids": ' . json_encode($detalle) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

?>