<?php



$servidor = "srv-sql01";
$baseDeDatos = "BD_FSocial_Test";
$usuario = "sql_fsocial";
$contraseña = "Fsgj98!fg.QA";

try {
    // Crear la conexión utilizando SQL Server y PDO
    $conexion = new PDO("sqlsrv:server=$servidor;Database=$baseDeDatos", $usuario, $contraseña);

    // Establecer el modo de error en excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // echo "Conexión realizada exitosamente";
} catch (Exception $error) {
    echo "Error de conexión: " . $error->getMessage();
}





/*
$servidor="localhost";
$baseDeDatos="ficha_social_familiar";
$usuario="root";
$contraseña="";
try{

    $conexion=new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$contraseña);
    //echo "conexion realizada";


}catch(Exception $error){
    echo $error->getMessage();
}


*/

?>