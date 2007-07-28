<? 
include_once "../includes/lib.php";
include_once "../includes/conf.inc.php";
beginSession('A');
imprimeEncabezado();


$tok = strtok ($_GET['vopc']," ");
$idponente=$tok;
$tok = strtok (" ");
$regresa='';
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

$link=conectaBD();
$userQuery = 'SELECT nombrep, apellidos, resume FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo comprobar el ponente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);

$msg='Datos de ponente <br><small>-- '.$p['nombrep'].' '.$p['apellidos'].' --</small><hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['asilogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);

// Inicio datos de Ponencias
    print '
     		<table width=100%>
		<tr>
		<td align=justify class="resultado">
		'.$p['resume'].'
		</td>
		</tr>

		</table>';
	retorno();
	retorno();
	print '<center>
		<br><big><a class="boton" href="'.$regresa.'" onMouseOver="window.status=\'Volver\';return true" onFocus="window.status=\'Volver\';return true" onMouseOut="window.status=\'\';return true">[ Volver ]</a></big>
	</center>';
imprimeCajaBottom();
imprimePie();?>
