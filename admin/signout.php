<?
include "../includes/lib.php";

beginSession('R');
session_unset();
session_destroy();

imprimeEncabezado();
aplicaEstilo();
imprimeCajaTop("50","Salida de sesion Administrador");

print '<p>Usted ha sido desconectado del sistema.</p>'; 
imprimeCajaBottom();
imprimePie();
?>
