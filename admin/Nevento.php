<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	$tok = strtok ($_GET['pevento']," ");
	$idponencia=$tok;
	$tok = strtok (" ");
	$idponente=$tok;
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
	$registro['D_reg_time']=$p['reg_time'];
	$registro['D_act_time']=$p['act_time'];
	$registro['I_id_administrador']=$p['id_administrador'];
	
	imprimeCajaTop("100","Registro de Eventos<br><small><small><small>Propuestas de ponencias con status de aceptadas listas para ser asignadas a un lugar y horario</small></small></small>");
	print '<hr>';

function imprime_valoresOk($idponencia,$idponente) {
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
	$registro['I_id_tipo']=$p['id_prop_tipo'];
	$registro['I_duracion']=$p['duracion'];
	$registro['I_id_orientacion']=$p['id_orientacion'];
	$registro['I_id_status']=$p['id_status'];
	$registro['D_reg_time']=$p['reg_time'];
	$registro['D_act_time']=$p['act_time'];
	$registro['I_id_administrador']=$p['id_administrador'];
	$hora_fin= $_POST['I_hora'] + $registro['I_duracion'];
	
    print '	
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponente: </td>
		<td class="resultado">
		'.$ponente_name.'
		</td>
		</tr>
		<tr>
		<td class="name">Nombre de Ponencia: </td>
		<td class="resultado">
		'.$registro['S_nombreponencia'].'
		</td>
		</tr>

		<tr>
		<td class="name">Nivel:  </td>
		<td class="resultado">
		'.stripslashes($registro['I_id_nivel']).'
		</td>
		</tr>


		<tr>
		<td class="name">Tipo de Propuesta: </td>
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
		<td class="name">Orientacion: </td>
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
		<td class="name">Duracion: </td>
		<td class="resultado">';
		printf ("%02d Hrs",$registro['I_duracion']);
	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Fecha evento: </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM fecha_evento WHERE id="'.$_POST['I_id_fecha'].'"';
		$result=mysql_query($query);
		$fila=mysql_fetch_array($result);
		print $fila["fecha"];
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Lugar: </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM lugar WHERE id="'.$_POST['I_id_lugar'].'"';
		$result=mysql_query($query);
		$fila=mysql_fetch_array($result);
		print $fila["nombre_lug"];
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Hora inicio: </td>
		<td class="resultado">'.$_POST['I_hora'].'
		</td>
		</tr>';
	print '
		<tr>
		<td class="name">Hora fin: </td>
		<td class="resultado">'.$hora_fin.'
		</td>
		</tr>
		
		
		</table>
		<center>
		<input type="button" value="Volver al Listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=8">
		</center>';

}
if (empty ($_POST['submit'])) 
{
	$_POST['I_id_fecha']='';
	$_POST['I_id_lugar']='';
	$_POST['I_hora']='';
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['I_id_lugar']) || empty($_POST['I_id_fecha']) || empty($_POST['I_hora'])) {
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  // Si no hay errores verifica que el evento no este ya dado de alta u haya cruces con otros eventos
  // Primero checamos si el evento no ha sido dado de alta
  if (empty($errmsg)) {
      $userQuery = 'SELECT id_propuesta FROM evento WHERE id_propuesta="'.$idponencia.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>La ponencia que elegiste para programar ya ha sido dada de alta;";
      }
  }
   $Ihora_ini= $_POST['I_hora'];
   $Ihora_fin= $Ihora_ini + $registro['I_duracion'];
  // Despues checamos que la fecha del evento la hora y el dia no este ya ocupado 
  if (empty($errmsg)) {
      for ($v_NO_ocupado=$Ihora_ini;$v_NO_ocupado<$Ihora_fin;$v_NO_ocupado++) 
      {  
      		$userQuery = 'SELECT * FROM evento_ocupa 
      		    		WHERE hora="'.$v_NO_ocupado.'"
				AND id_fecha="'.$_POST['I_id_fecha'].'"
				AND id_lugar="'.$_POST['I_id_lugar'].'"';
		//print $userQuery.'<br>';
        	$userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
       		if (mysql_num_rows($userRecords) != 0) 
		{
        		$errmsg .= "<li>La hora la fecha y el lugar que elegiste tiene conflictos con otra ponencia ya programada";
      		}
	}
	if(!empty($errmsg))
		$errmsg .= "<br><small>El numero de conflictos son las veces que aparece este mensaje</small>";
  }	
  // Luego checamos si no hay cruces entre las ponencias
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
  }
// Si todo esta bien vamos a darlo de alta
else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta

// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla

	$date=strftime("%Y%m%d%H%M%S");
	// Insertamos el evento en la tabla evento
	$query = "INSERT INTO evento (id_propuesta,reg_time,id_administrador) VALUES (".
		"'".$idponencia."',".
		"'".$date."',".
		"'".$_SESSION['YACOMASVARS']['rootid']."'".
		")";
	$result = mysql_query($query) or err("No se puede insertar el evento".mysql_errno($result));
	//print $result;
	
	// Seleccionamos el evento registrado para checar el id que se registro 
      	$query= 'SELECT id FROM evento WHERE id_propuesta="'.$idponencia.'"';
      	$result= mysql_query($query) or err("No se pudo checar el evento ya registrado par registrar sus horas".mysql_errno($userRecords));
	$fila=mysql_fetch_array($result);
	$idevento=$fila['id'];
	
	// Insertamos las veces que sean necesarias dependiendo de la longitud de la ponencia
	// Este es un metodo chafa para evitar que inserten ponencias en lugares ocupados 
 	for ($insertar=$Ihora_ini;$insertar<$Ihora_fin;$insertar++) 
	{
		$query = "INSERT INTO evento_ocupa (id_evento,hora,id_fecha,id_lugar) VALUES (".
			"'".$idevento."',".
			"'".$insertar."',".
			"'".$_POST['I_id_fecha']."',".
			"'".$_POST['I_id_lugar']."'".
			")";
		//print $query.'<br>';
		$result = mysql_query($query) or err("No se puede insertar las horas y fechas ".mysql_errno($result));
	}
	// Actualizamos el status de la propuesta para visualizarla programada 
  	$query = "UPDATE propuesta SET id_status=8 WHERE id="."'".$idponencia."'";
//		print $query.'<br>';
	$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));

 	print '	Evento agregado, ahora ya estara disponible para que los asistentes se inscriban (en caso de ser un taller).
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>';

 	imprime_valoresOk($idponencia,$idponente);
 	imprimeCajaBottom(); 
 	imprimePie(); 
//	Necesitamos este exit para salirse ya de este programa y evitar que se imprima la forma porque 
//	los datos ya fueron intruducidos y la transaccion se realizo con exito
	exit;
    }
}
// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada

	

    print '
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponente: </td>
		<td class="resultado">
		'.$ponente_name.'
		</td>
		</tr>
		
		<tr>
		<td class="name">Nombre de Ponencia: </td>
		<td class="resultado">
		'.$registro['S_nombreponencia'].'
		</td>
		</tr>

		<tr>
		<td class="name">Nivel:  </td>
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
		<td class="name">Tipo de Propuesta: </td>
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
		<td class="name">Orientacion: </td>
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
		<td class="name">Duracion: </td>
		<td class="resultado">';
		printf ("%02d Hrs",$registro['I_duracion']);
	print '	
		</td>
		</tr>
		</table>';
	// o para corregir datos que ya hayamos tratado de introducir
	print'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<table width=100%>
		
		<tr>
		<td class="name">Fecha evento: * </td>
		<td class="input">
		<select name="I_id_fecha">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_fecha'])) 
			echo " selected";
	print '
		></option>';
		
		$result=mysql_query("select * from fecha_evento order by id");
	 	while($fila=mysql_fetch_array($result)) {
			print '<option value='.$fila["id"];
			if ($_POST['I_id_fecha']==$fila["id"])
				echo " selected";
			print '>'.$fila["fecha"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		
		<tr>
		</tr>
		<tr>
		<td class="name">Asignar en lugar: * </td>
		<td class="input">
		<select name="I_id_lugar">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_lugar'])) 
			echo " selected";
	print '
		></option>';
		
		if ($registro['I_id_tipo'] < 50 || $registro['I_id_tipo'] >=100)
			$result=mysql_query("select * from lugar where cupo=99999 order by id");
		else 
			$result=mysql_query("select * from lugar where cupo!=99999 order by id");

	 	while($fila=mysql_fetch_array($result)) {
			print '<option value='.$fila["id"];
			if ($_POST['I_id_lugar']==$fila["id"])
				echo " selected";
			print '>'.$fila["nombre_lug"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		<tr>
		</tr>
		
		
		<tr>
		<td class="name">Hora inicio: *</td>
		<td class="input">
		<select name="I_hora">
		<option name="unset" value="0"';
		if (empty($_POST['I_hora'])) 
			echo " selected";
	print '
		></option>';
		for ($Ihora=$def_hora_ini;$Ihora<=$def_hora_fin;$Ihora++){
			printf ("<option value=%02d",$Ihora);
			if ($_POST['I_hora']==$Ihora)
				echo " selected";
			printf (">%02d </option>",$Ihora);
		}
	print '
		</select>
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=8">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
