<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
 

imprimeEncabezado();

//
// Status 5 es Aceptado 
// Seleccionamos solo las propuestas que hayan sido previamente aceptadas y que no esten programadas 
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = 'SELECT * FROM propuesta WHERE id_status=5 ORDER BY id_ponente';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));
print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de ponencias aceptadas y listas para ser programadas");

// Inicio datos de Ponencias
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Tipo</b>
	</td><td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$fila['id_ponente'].' '.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila["nombre"].'</a>';
		$query = 'SELECT id,nombrep,apellidos FROM ponente WHERE id="'.$fila['id_ponente'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print '<br><small><a class="ponente" href="Vponente.php?vopc='.$fstatus['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fstatus['nombrep'].' '.$fstatus['apellidos'].'</small></a>';
		mysql_free_result($result);
		
	
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_tipo WHERE id="'.$fila['id_prop_tipo'].'"';
		$result=mysql_query($query);
	 	$ftipo=mysql_fetch_array($result);
		print $ftipo['descr'];
		mysql_free_result($result);
		
		
		// Una vez que la ponencia fue aceptada (id 5)
		// La ponencia no se le puede modificar el status ni eliminar 
		// A menos que sea el administrador principal 
		print '</td><td bgcolor='.$bgcolor.'><a class="verde" href="Nevento.php?pevento='.$fila['id'].' '.$fila['id_ponente'].' '.$_SERVER['REQUEST_URI'].'" onMouseOver="window.status=\'Asignar lugar\';return true" onFocus="window.status=\'Asignar lugar\';return true" onMouseOut="window.status=\'\';return true">Asignar lugar</a>';
		print '</td></tr>'; 
		print '</td>';
		print '</tr>';
		
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#eventos">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
