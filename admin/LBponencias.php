<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
 

imprimeEncabezado();

// Seleccionamos los status disponibles
$QSquery = 'SELECT * FROM prop_status ORDER BY ID'; 
$resultQS=mysql_query($QSquery);
$indice=0;
while ($QSfila=mysql_fetch_array($resultQS)) {
	$stat_array[$indice]['id']=$QSfila['id'];
	$stat_array[$indice]['descr']=$QSfila['descr'];
	$indice++;
}
mysql_free_result($resultQS);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = 'SELECT 	P.act_time, A.login, P.id AS id_ponencia, P.nombre AS ponencia,
			P.id_prop_tipo, PT.descr,P.id_ponente, PO.nombrep, S.descr AS status 
		FROM 	administrador AS A, 
			propuesta AS P, 
			ponente AS PO, 
			prop_status AS S, 
			prop_tipo AS PT
		WHERE 	P.id_administrador=A.id AND 
			P.id_ponente=PO.id AND 
			P.id_status=S.id AND 
			P.id_prop_tipo=PT.id AND
			id_status=7 
		ORDER BY P.id_ponente,P.act_time';

$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));
print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de ponencias eliminadas");

// Inicio datos de Ponencias
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Modificado por</b></td>
	<td bgcolor='.$colortitle.'><b>Fecha de Modif</b></td></td><td bgcolor='.$colortitle.'><b>Tipo</b></td>
	<td bgcolor='.$colortitle.'><b>Ponente</b></td></td>
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$fila['id_ponente'].' '.$fila['id_ponencia'].' '.$_SERVER['REQUEST_URI'].'">'.$fila["ponencia"].'</a></td>';
		print '<td bgcolor='.$bgcolor.'>'.$fila['login'];

		print '</td><td bgcolor='.$bgcolor.'>'.$fila['act_time'];
	
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['descr'];
		print '</td><td bgcolor='.$bgcolor.'>';
		
		print '<a class="azul" href="Vponente.php?vopc='.$fila['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].'</a>';
		print '</td></tr><tr><td>'; 
		
		print '<small>';
		for ($i=0;$i<$indice;$i++)
		{
			if ($stat_array[$i]['id'] < 7 )
				print '| <a class="verde" href="act_ponencia.php?vact='.$fila['id_ponencia'].' '.$stat_array[$i]['id'].' '.$_SERVER['REQUEST_URI'].'" onMouseOver="window.status=\''.$stat_array[$i]['descr'].'\';return true" onFocus="window.status=\''.$stat_array[$i]['descr'].'\';return true" onMouseOut="window.status=\'\';return true">'.$stat_array[$i]['descr'].' |</a>';
		}
		print '</small>';
		print '</td>';
		print '</tr>';
		
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#admin">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
