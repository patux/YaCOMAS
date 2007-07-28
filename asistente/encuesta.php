<?php
include_once "../includes/conf.inc.php";
include_once "../includes/lib.php";
beginSession('A');
$idasistente=$_SESSION['YACOMASVARS']['asiid'];
imprimeEncabezado();

print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['asilogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop('100','Encuestas'); 
retorno();
include_once "poll.php";
retorno();
print '<center>';
print '<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/asistente/menuasistente.php">
</center>';
imprimeCajaBottom();
retorno();
retorno();
imprimePie();
?>
