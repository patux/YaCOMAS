<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryL = 'SELECT * FROM lugar ORDER BY id';
$userRecordsL = mysql_query($userQueryL) or err("No se pudo listar lugares".mysql_errno($userRecordsL));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de lugares para eventos");
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td><td bgcolor='.$colortitle.'><b>Ubicacion</b></td>
	<td bgcolor='.$colortitle.'><b>Cupo</b></td><td bgcolor='.$colortitle.'><b>Eventos</b></td>';
	if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) 
		print '<td bgcolor='.$colortitle.'>&nbsp;</td>';
	print '</tr>';
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsL))
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
		<td bgcolor='.$bgcolor.'><a class="verde" href="Mlugar.php?idlugar='.$fila['id'].'">'.$fila['nombre_lug'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['ubicacion'].'</td>
		<td bgcolor='.$bgcolor.'>';
		if ($fila['cupo']!=99999)
			print $fila['cupo'];
		print '</td>
		<td bgcolor='.$bgcolor.'><a class="azul" href="Leventos-lugar.php?idlugar='.$fila['id'].'">Eventos registrados</td>';
		
		if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) 
		{
			print '</td><td bgcolor='.$bgcolor.'><small><a class="precaucion" href="Blugar.php?idlugar='.$fila['id'].'" onMouseOver="window.status=\'Eliminar lugar\';return true" onFocus="window.status=\'Eliminar lugar\';return true" onMouseOut="window.status=\'\';return true">Eliminar</a>';
		}
			print '</td></tr><tr><td>'; 
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#lugares">
	</center>';
imprimeCajaBottom();
imprimePie();?>
