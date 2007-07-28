<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	$tok = strtok ($_GET['vact']," ");
	$idponente=$tok;
	$tok = strtok (" ");
	$idponencia=$tok;
	$tok = strtok (" ");

	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Cancelar Ponencia");
	print '<p class="yacomas_error">
	Esta accion liberara el espacio ocupado en el programa por este evento y le asignara el status de Cancelada
	<br>
	Las inscripciones de los asistentes que pudieran tener este evento seran eliminadas 
	</p>';
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk($idponente,$idponencia){
	include "../includes/conf.inc.php";

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
	$registro['I_id_prop_tipo']=$p['id_prop_tipo'];
	$registro['I_duracion']=$p['duracion'];
	$registro['I_id_orientacion']=$p['id_orientacion'];
	$registro['I_id_status']=$p['id_status'];
	$registro['D_reg_time']=$p['reg_time'];
	$registro['D_act_time']=$p['act_time'];
	$registro['I_id_administrador']=$p['id_administrador'];
	
	$msg='Ponencia de: <small>'.$ponente_name.'</small>';
	print '<center><H3>'.$msg.'</center></H3>';
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
		
		$query = 'SELECT * FROM prop_tipo WHERE id="'.$registro['I_id_prop_tipo'].'"';
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
		</tr>
	
		<tr>
		<td class="name">Fecha de registro: * </td>
		<td class="resultado">'.$registro['D_reg_time'].'
		</td>
		</tr>';
		$acttime=substr($registro['D_act_time'],0,4);	
		$acttime.='-'.substr($registro['D_act_time'],4,2);
		$acttime.='-'.substr($registro['D_act_time'],6,2);
		$acttime.=' '.substr($registro['D_act_time'],8,2);
		$acttime.=':'.substr($registro['D_act_time'],10,2);
		$acttime.=':'.substr($registro['D_act_time'],12,2);
	print'
		<tr>
		<td class="name">Fecha de actualizacion: * </td>
		<td class="resultado">'.$acttime.'
		</td>
		</tr>
		
		<tr>
		<td class="name">Actualizado por: * </td>
		<td class="resultado">';
		
		$query = 'SELECT login FROM administrador WHERE id="'.$registro['I_id_administrador'].'"';
		$result=mysql_query($query);
      		if (mysql_num_rows($result) == 0)
			print 'Usuario';
		else 
		{
	 		$fila=mysql_fetch_array($result); 
			printf ("<b>%s</b>",$fila["login"]);
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
		<td class="name">Requisitos tecnicos del taller: </td>
		<td align=justify class="resultado">
		'.$registro['S_reqtecnicos'].'
		</td>
		</tr>

		<tr>
		<td class="name">Prerequisitos del Asistente: </td>
		<td align=justify class="resultado">
		'.$registro['S_reqasistente'].'
		</td>
		</tr>
		</table>

		<br>';
		retorno();
		retorno();
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Cancelar") {
  # do some basic error checking
  // Si todos esta bien vamos a borrar el registro 

  	// Seleccionamos las propuestas que estan registrados por el ponente 
  	// Seleccionamos los eventos las que se refieren las propuestas 
  	$query_selectE='SELECT id FROM evento WHERE id_propuesta='.$idponencia;
	$result_SE=  mysql_query($query_selectE) or 
		err("No se pueden seleccionar los eventos relacionadas la propuesta para eliminarlos".mysql_errno($result_SE));
	$event= mysql_fetch_array($result_SE);
	// Borra asisgnaciones de evento en lugar 
	$QB_evento_ocupa=  "DELETE FROM evento_ocupa WHERE id_evento="."'".$event['id']."'";
	$result_BEO= mysql_query($QB_evento_ocupa) or 
		err("No se puede eliminar las inscripciones al eventos de este lugar".mysql_errno($result_BEO));
	// Borra inscripcion a el evento 
	$QB_inscribe =  "DELETE FROM inscribe WHERE id_evento="."'".$event['id']."'";
	$result_BI=  mysql_query($QB_inscribe) or 
		err("No se puede eliminar inscripcion al evento".mysql_errno($result_BI));
	// Borra evento
	$QB_evento =  "DELETE FROM evento WHERE id="."'".$event['id']."'";
	$result_BE=  mysql_query($QB_evento) or 
		err("No se puede eliminar evento ".mysql_errno($result_BE));
	mysql_free_result($result_SE);
		
		// Debug
	/*	print $QB_selectEL;
		retorno();
		print $query_actP;
		retorno();
		print $QB_evento;
		retorno();
		print $QB_inscribe;
		retorno();
		print $QB_hinscripcion;
		retorno();
		print $QB_evento_ocupa;
		retorno();
	*/
	// Finalmente borra sus propuestas 
  	$QA_propuesta= "UPDATE propuesta SET id_status=6 WHERE id="."'".$idponencia."'";
	$result_AP=  mysql_query($QA_propuesta) or err("No se puede eliminar propuestas".mysql_errno($result_AP));
	
 	print '	La propuesta ha sido cancelada.<br>
		<p class="yacomas_msg">
		Los espacios que ocupaban en los talleres los asistentes que estaban inscritos han sido liberados 
		Las ponencias registradas han sido cambiado en status de Cancelada
		</p>
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=9">
		 </center>';
	
 	imprimeCajaBottom(); 
 	imprimePie(); 
//	Necesitamos este exit para salirse ya de este programa y evitar que se imprima la forma porque 
//	los datos ya fueron intruducidos y la transaccion se realizo con exito
	exit;
}
// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
	
	imprime_valoresOk($idponente,$idponencia);
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Cancelar">&nbsp;&nbsp;
		<input type="button" value="Volver" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=9">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
