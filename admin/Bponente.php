<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Eliminar Ponente");
	print '<p class="yacomas_error">
	Esta accion eliminara el ponente y todos sus Ponencias/Eventos registrados 
	<br>
	Las inscripciones de los asistentes a los eventos que el ponente pudiera tener ya programadas seran eliminadas 
	</p>';
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk($idponente,$regresa) {
	include "../includes/conf.inc.php";

$link=conectaBD();

$userQuery = 'SELECT * FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el ponente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = 'SELECT * FROM propuesta WHERE id_ponente="'.$idponente.'" AND id_status!=7';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));

// Inicio datos de Ponencias
    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponente: *</td>
		<td class="resultado">
		'.$p['nombrep'].' '.$p['apellidos'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Correo Electrónico: *</td>
		<td class="resultado">
		'.$p["mail"].'
		</td>
		</tr>

		<tr>
		<td class="name">Sexo: * </td>
		<td class="resultado">';
		
		if ($p['sexo']=="M")
		    echo "Masculino";
		else
		    echo "Femenino";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Organización: </td>
		<td class="resultado">
		'.stripslashes($p['org']).'
		</td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM estudios WHERE id="'.$p['id_estudios'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Titulo: * </td>
		<td class="resultado">
		'.stripslashes($p['titulo']).'
		</td>
		</tr>

		<tr>
		<td class="name">Domicilio: </td>
		<td class="resultado">
		'.$p['domicilio'].'
		</td>
		</tr>

		<tr>
		<td class="name">Telefono: </td>
		<td class="resultado">
		'.chunk_split ($p['telefono'], 2).'
		</td>
		</tr>

		<tr>
		<td class="name">Ciudad: </td>
		<td class="resultado">
		'.$p['ciudad'].'
		</td>
		</tr>

		<tr>
		<td class="name">Estado: * </td>
		<td class="resultado">';
		
		$query= "select * from estado where id='".$p['id_estado']."'";
		$result=mysql_query($query);
 		while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Fecha de Nacimiento: </td>
		<td class="resultado">';
		print $p['fecha_nac'];
	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Resumen Curricular: </td>
		<td align=justify class="resultado">
		'.$p['resume'].'
		</td>
		</tr>

		</table>
		<br>
		<hr>';
// Fin datos de usuario
// Inicio datos de Ponencias
print '<p class="yacomas_error">Ponencias registradas</p>';
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Tipo</b></td>
	<td bgcolor='.$colortitle.'><b>Status</b></td>
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$idponente.' '.$fila['id'].' '.$regresa.'">'.$fila["nombre"].'</a>';
	
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_tipo WHERE id="'.$fila['id_prop_tipo'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print $fstatus['descr'];
		mysql_free_result($result);
		
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_status WHERE id="'.$fila['id_status'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print $fstatus['descr'];
		mysql_free_result($result);
		print '</td></tr>';
		
	}
	print '</table>';
	retorno();
	retorno();
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Eliminar") {
  # do some basic error checking
  // Si todos esta bien vamos a borrar el registro 

  	// Seleccionamos las propuestas que estan registrados por el ponente 
  	$QB_selectEL='SELECT id AS id_propuesta FROM propuesta WHERE id_ponente='.$idponente;
	// Debug
	// print $QB_selectEL;
	// retorno();

	$result_SPL=  mysql_query($QB_selectEL) or err("No se pueden seleccionar propuestas del ponente".mysql_errno($result_SPL));
	$num_prop=0;
	while ($fila = mysql_fetch_array($result_SPL)) 
	{
		$propuesta[$num_prop]['id_propuesta']=$fila['id_propuesta'];
		$num_prop++;
	}
	mysql_free_result($result_SPL);

	for ($i=0; $i < $num_prop; $i++ )
	{
  		// Seleccionamos los eventos las que se refieren las propuestas 
  		$query_selectE='SELECT id FROM evento WHERE id_propuesta='.$propuesta[$i]['id_propuesta'];
		$result_SE=  mysql_query($query_selectE) or 
			err("No se pueden seleccionar los eventos relacionadas la propuesta para eliminarlos".mysql_errno($result_SE));
      		if (mysql_num_rows($result_SE) != 0) 
		{
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
		}
		// Debug
	/*	print $query_selectP;
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
	}
	// Finalmente borra sus propuestas 
  	$QB_propuestas= "DELETE FROM propuesta WHERE id_ponente="."'".$idponente."'";
	$result_BP=  mysql_query($QB_propuestas) or err("No se puede eliminar propuestas".mysql_errno($result_BP));
	// Finalmente borra ponente  
  	$QB_ponente= "DELETE FROM ponente WHERE id="."'".$idponente."'";
	$result_BPO=  mysql_query($QB_ponente) or err("No se puede eliminar ponente".mysql_errno($result_BPO));
	
 	print '	El ponente ha sido eliminado.<br>
		<p class="yacomas_msg">
		Los espacios que ocupaban en los talleres los asistentes que estaban inscritos han sido liberados 
		Las ponencias registradas han sido cambiado en status de Aceptadas en espera de nueva asignacion de lugar y fecha
		para que los asistentes puedan inscribirse a ella
		</p>
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=6">
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

	imprime_valoresOk($_GET['idponente'],$_SERVER['REQUEST_URI']);
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Eliminar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=6">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
