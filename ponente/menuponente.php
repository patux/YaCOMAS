<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('P');
imprimeEncabezado();
aplicaEstilo();
$link=conectaBD();
$idponente=$_SESSION['YACOMASVARS']['ponid'];
$userQuery = 'SELECT nombrep,apellidos FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$msg='Ponentes<br><small>Bienvenido '.$p['nombrep'].' '.$p['apellidos'].'</small>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '<hr>';
retorno();
print '<font size=+1 color=#000000 face=arial>
<a href="ponente.php?opc=1">Enviar propuesta de ponencia</a> <br><br>
<a href="ponente.php?opc=2">Listar propuestas enviadas</a> <br><br>
<a href="ponente.php?opc=3">Modificar mis datos</a> <br><br>
</font>';

imprimeCajaBottom();
imprimePie();?>
