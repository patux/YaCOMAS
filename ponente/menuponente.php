<? 
include_once "../includes/lib.php";
include_once "../includes/conf.inc.php";
beginSession('P');
imprimeEncabezado();

$link=conectaBD();
$idponente=$_SESSION['YACOMASVARS']['ponid'];
$userQuery = 'SELECT nombrep,apellidos FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$msg='Ponentes<br><small>Bienvenido '.stripslashes($p['nombrep']).' '.stripslashes($p['apellidos']).'</small>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '<hr>';
retorno();
print '<font size=+1 color=#000000 face=arial>
<a href="ponente.php?opc='.NPONENCIA.'">Enviar propuesta de ponencia</a> <br><br>
<a href="ponente.php?opc='.PROPUESTAENV.'">Listar propuestas enviadas</a> <br><br>
<a href="ponente.php?opc='.MPONENTE.'">Modificar mis datos</a> <br><br>
</font>';

imprimeCajaBottom();
imprimePie();?>
