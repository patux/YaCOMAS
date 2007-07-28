<?
include "../includes/lib.php";

beginSession('A');
session_unset();
session_destroy();

imprimeEncabezado();
aplicaEstilo();
imprimeCajaTop("50","Salida de sesion Asistente");

print '<p>Usted ha sido desconectado del sistema.</p>'; 
imprimeCajaBottom();
imprimePie();
?>
