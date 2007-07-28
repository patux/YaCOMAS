<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryL = 'SELECT * FROM fecha_evento ORDER BY fecha';
$userRecordsL = mysql_query($userQueryL) or err("No se pudo listar fechas".mysql_errno($userRecordsL));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de fechas para eventos");
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Fecha</b></td><td bgcolor='.$colortitle.'><b>Descripcion</b></td>
	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>';
	
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
		<td bgcolor='.$bgcolor.'><a class="verde" href="Mfecha.php?idfecha='.$fila['id'].'">'.$fila['fecha'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['descr'].'</td>
		<td bgcolor='.$bgcolor.'><a class="azul" href="Leventos-fecha.php?idfecha='.$fila['id'].'">Eventos registrados</td>';
		
		if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) 
		{
			print '</td><td bgcolor='.$bgcolor.'><small><a class="precaucion" href="Bfecha.php?idfecha='.$fila['id'].'" onMouseOver="window.status=\'Eliminar fecha\';return true" onFocus="window.status=\'Eliminar fecha\';return true" onMouseOut="window.status=\'\';return true">Eliminar</a>';
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
