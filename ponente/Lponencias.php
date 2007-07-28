<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('P');
imprimeEncabezado();

$link=conectaBD();
$idponente=$_SESSION['YACOMASVARS']['ponid'];
$userQuery = 'SELECT nombrep,apellidos FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = '	SELECT 	P.id,P.id_ponente,P.nombre,P.id_status, PT.descr 
		FROM 	propuesta AS P,
			prop_tipo AS PT
		WHERE 	P.id_ponente="'.$idponente.'" AND 
			P.id_prop_tipo=PT.id AND
			P.id_status!=7';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));
$msg='Propuestas de '.$p['nombrep'].' '.$p['apellidos'].'<hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Tipo</b></td>
	<td bgcolor='.$colortitle.'><b>Status</b></td>
	<td bgcolor='.$colortitle.'>&nbsp;</td>
	<td bgcolor='.$colortitle.'>&nbsp;</td>';
	print '</tr>';

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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?idponencia='.$fila['id'].'">'.$fila["nombre"].'</a>';
		
	
		print '</td><td bgcolor='.$bgcolor.'>';
		echo $fila['descr'];
		
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_status WHERE id="'.$fila['id_status'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print $fstatus['descr'];
		mysql_free_result($result);
		
		print '</td>';
		// El ponente solo puede cancelar propeustas no borradas,canceladas,aceptadas o programadas
		if ($fila['id_status'] < 5)  
			    print '<td width=50 bgcolor='.$bgcolor.'><a class="precaucion" href="BCponencia.php?vopc='.$fila['id'].' 7">Eliminar</td>';
            // Tampoco puede modificar ponencias rechazadas
            if ($fila['id_status'] != 3 || $fila['id_status'] !=6 ) { 
			    print '<td width=50 bgcolor='.$bgcolor.'><a class="verde" href="Mponencia.php?idponencia='.$fila['id'].'">Modificar</td>';
		    }
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/ponente/menuponente.php">
	</center>';
imprimeCajaBottom();
imprimePie();?>
