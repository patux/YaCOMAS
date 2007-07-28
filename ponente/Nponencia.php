<? 
	include "../includes/lib.php";
	include "../includes/conf.inc";
	beginSession('P');
	imprimeEncabezado();
	aplicaEstilo();
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Registro de Ponencias/Talleres");
	$link=conectaBD();
	$configQuery= 'SELECT status FROM config WHERE id=3';
	$resultCQ=mysql_query($configQuery);
	$CQfila=mysql_fetch_array($resultCQ);
	$stat_array=$CQfila["status"];
	mysql_free_result($resultCQ);
	if ($stat_array==0) {
		retorno();
		retorno();
		print '<p class="yacomas_error">El registro de nuevas ponencias se encuentra cerrado.. </p>';
		retorno();
		retorno();
		print ' <center>
			<input type="button" value="Continuar" onClick=location.href="'.$rootpath.'/ponente/menuponente.php">
	        	</center>';
		retorno();
		exit;
	}

function imprime_valoresOk() {
	include "../includes/conf.inc";

    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponencia: * </td>
		<td class="resultado">
		'.$_POST[S_nombreponencia].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Nivel: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$_POST[I_id_nivel].'"';
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
		
		if ($_POST[C_tpropuesta]=="C")
		    echo "Conferencia";
		else
		    echo "Taller";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Orientacion: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM orientacion WHERE id="'.$_POST[I_id_orientacion].'"';
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
		printf ("%02d Hrs",$_POST[I_duracion]);
	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Resumen: </td>
		<td align=justify class="resultado">
		'.$_POST[S_resumen].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Requisitos tecnicos de la ponencia: </td> 
		<td align=justify class="resultado">
		'.$_POST[S_reqtecnicos].'
		</td>
		</tr>

		<tr>
		<td class="name">Prerequisitos del Asistente: </td>
		<td align=justify class="resultado">
		'.$_POST[S_reqasistente].'
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver al menu" onClick=location.href="'.$rootpath.'/ponente/menuponente.php">
		</center>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  $S_trim_resumen=trim($_POST['S_resumen']);
  $S_trim_reqtecnicos=trim($_POST['S_reqtecnicos']);
  $S_trim_reqasistente=trim($_POST['S_reqasistente']);
  if (empty($_POST['S_nombreponencia']) || empty($S_trim_resumen) ||
    	empty($_POST['C_tpropuesta']) || empty($_POST['I_id_orientacion']) || 
	empty($_POST['I_duracion']) || empty($_POST['I_id_nivel'])) { 
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (($_POST['I_duracion'] > 2) && ($_POST['C_tpropuesta']=="C"))
  {
  	$errmsg .= "<li>No puedes registrar una conferencia mayor de 2 horas";
  }
  // Verifica que la ponencia no este dada de alta
  if (empty($errmsg)) {
      $userQuery = 'SELECT * FROM propuesta WHERE nombre="'.$S_nombreponencia.'" and id_ponente="'.$idponente.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar las ponencia ".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>El nombre que elegiste para la ponencia ya lo has dado de alta";
      }
  }
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
   	print '<p class="yacomas_error">Introduzca correctamente los datos por favor<p>';
  }
  // Si todo esta bien vamos a darlo de alta
  else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta

	$idponente=$_SESSION['YACOMASVARS']['ponid'];
// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla
	
	$date=strftime("%Y%m%d%H%M%S");
  	$query = "INSERT INTO propuesta (nombre,resumen,reqtecnicos,reqasistente,id_nivel,tpropuesta,duracion,reg_time,id_ponente,id_orientacion)
	VALUES (".
		"'".mysql_escape_string(stripslashes($_POST['S_nombreponencia']))."',".
		"'".mysql_escape_string(stripslashes($S_trim_resumen))."',".
		"'".mysql_escape_string(stripslashes($S_trim_reqtecnicos))."',".
		"'".mysql_escape_string(stripslashes($S_trim_reqasistente))."',".
		"'".$_POST['I_id_nivel']."',".
		"'".$_POST['C_tpropuesta']."',".
		"'".$_POST['I_duracion']."',".
		"'".$date."',".
		"'".$idponente."',".
		"'".$_POST['I_id_orientacion']."'".
		")";
		//
		// Para debugear querys
		// print $query;
		//
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Tu propuesta de ponencia ha sido registrada .
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta al 
		 <a href="mailto:patux@glo.org.mx">FSL Developer team</a><br><br>';

 	imprime_valoresOk();
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
// o para corregir datos que ya hayamos tratado de introducir
	print'
		<FORM method="POST" action="'.$REQUEST_URI.'">
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
		<table width=100%>
		<tr>

		<td class="name">Nombre de Ponencia: * </td>
		<td class="input">
		<input TYPE="text" name="S_nombreponencia" size="50" 
		value="'.$_POST['S_nombreponencia'].'"></td>
		</tr>

		<tr>
		<td class="name">Nivel: * </td>
		<td class="input">
		<select name="I_id_nivel">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_nivel'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from prop_nivel order by id");
	 	while($fila=mysql_fetch_array($result)) {
			
			print '<option value='.$fila["id"];
			if ($_POST['I_id_nivel']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		</tr>

		<tr>
		<td class="name">Tipo de propuesta: * </td>
		<td class="input">
		<select name="C_tpropuesta">
		<option name="unset" value="" ';
		
		if (empty($_POST['C_tpropuesta'])) 
			echo "selected";
		
	print '
		></option>"
		<option value="C"';
		if ($_POST['C_tpropuesta']=="C")
			echo "selected";
	
	print '
		>Conferencia</option>"
		<option value="T"';
		if ($_POST['C_tpropuesta']=="T") 
			echo "selected";
	print '
		>Taller</option>"
		</select>
		</td>
		</tr>

		<tr>
		<td class="name">Orientacion : * </td>
		<td class="input">
		<select name="I_id_orientacion">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_orientacion'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from orientacion order by id");
	 	while($fila=mysql_fetch_array($result)) {
			
			print '<option value='.$fila["id"];
			if ($_POST['I_id_orientacion']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		</tr>
		
		<tr>
		<td class="name">Duracion (hrs): * </td>
		<td class="input">
		<select name="I_duracion">

		<option name="unset" value="0"';
		if (empty($_POST['I_duracion'])) 
			echo " selected";
	print '
		></option>';
		for ($Idur=1;$Idur<=4;$Idur++){
			printf ("<option value=%02d",$Idur);
			if ($_POST['I_duracion']==$Idur)
				echo " selected";
			printf (">%02d </option>",$Idur);
		}
	print '
		</select>
		</td>
		</tr>

		<tr>
		<td class="name">Resumen: *</td>
		<td class="input"><textarea tabindex=0 name="S_resumen" cols=60 rows=15> 
		'.stripslashes($_POST[S_resumen]).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Requisitos tecnicos de la ponencia:<br><small>(Estos son los requisitos que Ud. necesita para impartirla)</small> </td>
		<td class="input"><textarea tabindex=0 rows=5 name="S_reqtecnicos" cols=60 rows=15> 
		'.stripslashes($_POST[S_reqtecnicos]).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Prerequisitos para el asistente: </td>
		<td class="input"><textarea tabindex=0 rows=5 name="S_reqasistente" cols=60 rows=15> 
		'.stripslashes($_POST[S_reqasistente]).'</textarea></td>
		</tr>

		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrar">&nbsp;&nbsp;
		<input type="button" value="Volver a menu" onClick=location.href="'.$rootpath.'/ponente/menuponente.php">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
