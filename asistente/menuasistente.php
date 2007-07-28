<? 
include_once "../includes/lib.php";
include_once "../includes/conf.inc.php";
beginSession('A');
imprimeEncabezado();

$link=conectaBD();
$idponente=$_SESSION['YACOMASVARS']['asiid'];
$userQuery = 'SELECT nombrep,apellidos FROM asistente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$msg='Asistentes<br><small>Bienvenido '.stripslashes($p['nombrep']).' '.stripslashes($p['apellidos']).'</small>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['asilogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '<hr>';
retorno();
print '<font size=+1 color=#000000 face=arial>
<a href="asistente.php?opc='.MASISTENTE.'">Modificar mis datos</a> <br><br>
<a href="asistente.php?opc='.LEVENTOS.'">Listar eventos programados</a> <br><br>
';
//<a href="asistente.php?opc='.LTALLERES.'">Listar/Inscribirme a talleres y tutoriales</a> <br><br>
print ' 
<a href="asistente.php?opc='.LTALLERESREG.'">Listar/Darme de baja de talleres y tutoriales registrados</a> <br><br>
<a href="asistente.php?opc='.HOJAREGISTRO.'">Imprimir hoja de registro</a><br><br>
';
//print '<hr>';
//print '<a href="asistente.php?opc='.ENCUESTA.'">Encuestas </a> <br><br>';
//print '</font>';

imprimeCajaBottom();
imprimePie();?>
