<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	imprimeEncabezado();
	
	$tok = strtok ($_GET['vopc']," ");
	$idponente=$tok;
	$tok = strtok (" ");
	$idponencia=$tok;
	$tok = strtok (" ");
	$regresa='';	
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

	$link=conectaBD();
	$userQuery = 
	'SELECT nombrep, apellidos FROM ponente  
		WHERE id="'.$idponente.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar el ponente".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$ponente_name=$p['nombrep'].' '.$p['apellidos'];
	$userQuery =
	'SELECT * FROM propuesta 
		WHERE id="'.$idponencia.'" AND id_ponente="'.$idponente.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$registro['S_nombreponencia']=$p['nombre'];
	$registro['I_id_nivel']=$p['id_nivel'];
	$registro['S_resumen']=$p['resumen'];
	$registro['S_reqtecnicos']=$p['reqtecnicos'];
	$registro['S_reqasistente']=$p['reqasistente'];
	$registro['I_id_tipo']=$p['id_prop_tipo'];
	$registro['I_duracion']=$p['duracion'];
	$registro['I_id_orientacion']=$p['id_orientacion'];
	$registro['I_id_status']=$p['id_status'];
	$registro['I_id_administrador']=$p['id_administrador'];

	
	$msg='Ponencia de: <small>'.$ponente_name.'</small>';
	imprimeCajaTop("100",$msg);
	print '<hr>';
    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponencia: * </td>
		<td class="resultado">
		'.$registro['S_nombreponencia'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Nivel: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$registro['I_id_nivel'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Tipo de Propuesta: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_tipo WHERE id="'.$registro['I_id_tipo'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>


		<tr>
		<td class="name">Orientacion: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM orientacion WHERE id="'.$registro['I_id_orientacion'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Duracion: * </td>
		<td class="resultado">';
		printf ("%02d Hrs",$registro['I_duracion']);
	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Status: * </td>
		<td class="resultado">';
		
		$query = 'SELECT descr FROM prop_status WHERE id="'.$registro['I_id_status'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("<b>%s</b>",$fila["descr"]);
  		}
		mysql_free_result($result);
	print '	
		</td>
		</tr>';
	// Si la ponencia ya esta programada mostrar el lugar, la hora y la fecha donde esta programada
	if  ($registro['I_id_status']==8) 
	{
		// Selecciona el evento que este programado 
		$queryE = 'SELECT id FROM evento WHERE id_propuesta="'.$idponencia.'"';
		$resultE=mysql_query($queryE);
	 	$filaE=mysql_fetch_array($resultE);
		$idevento=$filaE['id'];
		mysql_free_result($resultE);
		
		// Selecciona la fecha, hora, y lugar
		$queryEO = 'SELECT * FROM evento_ocupa WHERE id_evento="'.$idevento.'" GROUP BY id_evento';
		$resultEO=mysql_query($queryEO);
	 	$detalle_EO=mysql_fetch_array($resultEO);
		$idfecha=$detalle_EO['id_fecha'];
		$idlugar=$detalle_EO['id_lugar'];

	print '
		<tr>
		<td class="name">Fecha: * </td>
		<td class="resultado">';
		$query = 'SELECT fecha FROM fecha_evento WHERE id="'.$idfecha.'"';
		$result=mysql_query($query);
	 	$fila=mysql_fetch_array($result);
		print $fila['fecha'];
		mysql_free_result($result);

	print '
		</td>
		</tr>
		
		<tr>
		<td class="name">Lugar: * </td>
		<td class="resultado">';
		$query = 'SELECT nombre_lug FROM lugar WHERE id="'.$idlugar.'"';
		$result=mysql_query($query);
	 	$fila=mysql_fetch_array($result);
		print $fila['nombre_lug'];
		mysql_free_result($result);

	print '
		</td>
		</tr>
		
		<tr>
		<td class="name">Hora: * </td>
		<td class="resultado">';
		$hfin=$detalle_EO['hora'] + $registro['I_duracion']-1;		
		print $detalle_EO['hora'].':00 - '.$hfin.':50';
	print '
		</td>
		</tr>';
		
		mysql_free_result($resultEO);
		
	}
	print	'</table>';
		
	// Aqui comienzan los resumenes
	print'
		<hr>
     		<table width=100%>
		<tr>
		<td class="name">Resumen: </td>
		<td align=justify class="resultado">
		'.$registro['S_resumen'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Prerequisitos del Asistente: </td>
		<td align=justify class="resultado">
		'.$registro['S_reqasistente'].'
		</td>
		</tr>
		</table>
		<br>
		<center>
		<br><big><a class="boton" href="'.$regresa.'" onMouseOver="window.status=\'Volver\';return true" onFocus="window.status=\'Volver\';return true" onMouseOut="window.status=\'\';return true">[ Volver ]</a></big>
		</center>';

imprimeCajaBottom(); 
imprimePie(); 
?>
