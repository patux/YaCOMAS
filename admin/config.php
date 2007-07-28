<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryP = 'SELECT * FROM config';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar configuracion".mysql_errno($userRecords));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Configuracion de Yacomas");
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td><td bgcolor='.$colortitle.'><b>Estado</b></td>
	<td bgcolor='.$colortitle.'>&nbsp;</td>
	</tr>';
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsP))
	{
		if ($color==1) 
		{
			$bgcolor=$color_renglon1;
			$color=2;
		}
		else 
		{
			$bgcolor=$color_renglon2;
			$color=1;
		}
		print '<tr>
		<td bgcolor='.$bgcolor.'>'.$fila['descr'].'</td>
		<td bgcolor='.$bgcolor.'>';
		if ($fila['status']==1)
		{
			print "Abierto";
			print '<td bgcolor='.$bgcolor.'>';
			print '<a class="precaucion" href="act_conf.php?vconf='.$fila['id'].' 0" onMouseOver="window.status=\'Cerrar\';return true" onFocus="window.status=\'Cerrar\';return true" onMouseOut="window.status=\'\';return true">Cerrar</a>';
		}
		else
		{
			print "Cerrado";
			print '<td bgcolor='.$bgcolor.'>';
			print '<a class="verde" href="act_conf.php?vconf='.$fila['id'].' 1" onMouseOver="window.status=\'Abrir\';return true" onFocus="window.status=\'Abrir\';return true" onMouseOut="window.status=\'\';return true">Abrir</a>';
		}
	}
	print '</td></tr></table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php">
	</center>';
imprimeCajaBottom();
imprimePie();?>
