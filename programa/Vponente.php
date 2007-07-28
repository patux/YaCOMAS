<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
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
$userRecords = mysql_query($userQuery) or err("No se pudo checar el ponente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);

$msg='Datos de ponente <br><small>-- '.$p['nombrep'].' '.$p['apellidos'].' --</small><hr>';
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
