<?
include_once "../includes/lib.php";

beginSession('A');
session_unset();
session_destroy();

imprimeEncabezado();

#imprimeCajaTop("50","<font face='Arial, Helvetica, sans-serif'>Salida de sesion Asistente</font>");
?>

<center><font face='Arial, Helvetica, sans-serif' size=6>Salida de sesion Asistente</font></center>

<?
print '<p><center>Usted ha sido desconectado del sistema.</center></p>'; 
#imprimeCajaBottom();
?>
<center><input type="button" value="Regresar" onClick=location.href="../"></center>
<? imprimePie();?>
