<?
include "../includes/lib.php";

beginSession('P');
session_unset();
session_destroy();

imprimeEncabezado();
aplicaEstilo();
imprimeCajaTop("50","Salida de sesion Ponente");

print '<p>Usted ha sido desconectado del sistema.</p>'; 
imprimeCajaBottom();
imprimePie();
?>
