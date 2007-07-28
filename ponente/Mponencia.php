<? 
	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
	beginSession('P');
	imprimeEncabezado();
	
  	$idponente=$_SESSION['YACOMASVARS']['ponid'];
	$idponencia=$_GET['idponencia'];
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Modificar propuesta");
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk() {
	include "../includes/conf.inc.php";

    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponencia: * </td>
		<td class="resultado">
		'.$_POST['S_nombreponencia'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Orientaci&oacute;n: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM orientacion WHERE id="'.$_POST['I_id_orientacion'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		

		<tr>
		<td class="name">Nivel: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$_POST['I_id_nivel'].'"';
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
		
		$query = 'SELECT * FROM prop_tipo WHERE id="'.$_POST['I_id_tipo'].'"';
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
		printf ("%02d Hrs",$_POST['I_duracion']);
	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Resumen: </td>
		<td align=justify class="resultado">
		'.$_POST['S_resumen'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Requisitos tecnicos de la ponencia: </td> 
		<td align=justify class="resultado">
		'.$_POST['S_reqtecnicos'].'
		</td>
		</tr>

		<tr>
		<td class="name">Prerequisitos del Asistente: </td>
		<td align=justify class="resultado">
		'.$_POST['S_reqasistente'].'
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/ponente/ponente.php?opc=2">
		</center>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset($_POST['submit']) && $_POST['submit'] == "Actualizar") {
  # do some basic error checking
  $errmsg = "";
  // Variables para restableser los valores de tipod de propuesta y duracion
  // Dado que la propuesta ya que el status esta en Aceptada/Por Aceptar/Cancelada/Rechazada
  // Es necesario no mover los valores que tenian cuando cambio el status al modificar una propuesta en esos status
  $userQuery = 'SELECT * FROM propuesta WHERE id="'.$idponencia.'" AND id_ponente="'.$idponente.'"';
  $userRecords = mysql_query($userQuery) or err("No se pudo checar las ponencia ".mysql_errno($userRecords));
  $p = mysql_fetch_array($userRecords);
  //if ($p['id_status'] > 2 )
  // Tal vez vayamos a borrar esta madrinola no se si tenga funcionamiento todavia
  if ($p['id_status'] == 3 || $p['id_status'] >4)   
  {
  	$_POST['I_id_tipo']=$p['id_prop_tipo'];
	$_POST['I_duracion']=$p['duracion'];
  }
  // Verificar si todos los campos obligatorios no estan vacios
  $S_trim_resumen=trim($_POST['S_resumen']);
  $S_trim_reqtecnicos=trim($_POST['S_reqtecnicos']);
  $S_trim_reqasistente=trim($_POST['S_reqasistente']);
  if (empty($_POST['S_nombreponencia']) || empty($S_trim_resumen) ||
    	empty($_POST['I_id_tipo']) || empty($_POST['I_id_orientacion']) || 
	empty($_POST['I_duracion'])) { 
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (($_POST['I_duracion'] > 2) && ($_POST['I_id_tipo'] < 50))
  {
  	$errmsg .= "<li>Solo los talleres y tutoriales pueden ser de mas de 2 horas";
  }
  // Verifica que la ponencia no este dada de alta
  $rutaantigua="";
  if (empty($errmsg)) {
	$userQuery = 'SELECT * FROM propuesta WHERE nombre="'.$_POST['S_nombreponencia'].'" and id_ponente="'.$idponente.'"';
 	$userRecords = mysql_query($userQuery) or err("No se pudo checar las ponencia ".mysql_errno($userRecords));
  	$p = mysql_fetch_array($userRecords);
      	if (mysql_num_rows($userRecords) != 0) {
			if ($p['id'] != $idponencia){
  				$p = mysql_fetch_array($userRecords);
        			$errmsg .= "<li>El nombre que elegiste para la ponencia ya lo has dado de alta";
			}else{
				//print "id: ".$p['id'].' '.$ponencia;
				$rutaantigua=$p['dirFile'];
			}
      	}
  }
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
   	print '<p class="yacomas_error">Introduzca correctamente los datos por favor<p>';
  }
  // Si todo esta bien vamos a modificarla 
  else { // Todas las validaciones Ok 

// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla

    $fichero=$_FILES["fichero"];
  	$query = "UPDATE  propuesta SET nombre="."'".mysql_real_escape_string(stripslashes($_POST['S_nombreponencia']))."',
				    resumen="."'".mysql_real_escape_string(stripslashes($S_trim_resumen))."',
				    reqtecnicos="."'".mysql_real_escape_string(stripslashes($S_trim_reqtecnicos))."',
				    reqasistente="."'".mysql_real_escape_string(stripslashes($S_trim_reqasistente))."',
				    id_nivel ="."'".$_POST['I_id_nivel']."',
				    id_prop_tipo="."'".$_POST['I_id_tipo']."',
				    duracion="."'".$_POST['I_duracion']."',
				    id_orientacion="."'".$_POST['I_id_orientacion']."'";
                    if (isset($fichero['name']) && $fichero['name']!=""){
                        // The loginname of the user are registered in the SESSION 
                        $resaux=$_SESSION['YACOMASVARS']['ponlogin'];
                        $rutafilename=$archivos.$resaux.CARACTERSEPARADOR.$fichero["name"];
                        $query.= ",nombreFile='".stripslashes($fichero["name"])."'";
                        $query.= ",tipoFile='".stripslashes($fichero["type"])."'";
                        $query.= ",dirFile='".stripslashes($rutafilename)."'";
                    }
				    $query.=" WHERE id="."'".$idponencia."' AND id_ponente='".$idponente."'";
		// Para debugear querys
		//print $query;
		//
                $result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
		if ((!empty ($fichero['name'])) && (!empty($rutaantigua))){
			if (file_exists($rutaantigua)){
                //echo "Eliminado...";				
                unlink($rutaantigua);
            }
        }
        if (!empty ($fichero['name'])) {
            if (!(move_uploaded_file($fichero["tmp_name"],$rutafilename))){
                die("Imposible copiar fichero");
            };
        }
        print '	Tu propuesta de ponencia ha sido actualizada .
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>';

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
else {
	$userQuery = 
	'SELECT nombre, resumen, reqtecnicos, reqasistente, id_nivel, id_prop_tipo, duracion, id_status, id_orientacion FROM propuesta WHERE id="'.$idponencia.'" AND id_ponente="'.$idponente.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_nombreponencia']=$p['nombre'];
	$_POST['I_id_nivel']=$p['id_nivel'];
	$_POST['S_resumen']=$p['resumen'];
	if (isset ($p['reqtecnicos']))
		$_POST['S_reqtecnicos']=$p['reqtecnicos'];
	else 
		$_POST['S_reqtecnicos']='';
	if (isset ($p['reqasistente']))
		$_POST['S_reqasistente']=$p['reqasistente'];
	else 
		$_POST['S_reqasistente']='';
	$_POST['I_id_tipo']=$p['id_prop_tipo'];
	$_POST['I_duracion']=$p['duracion'];
	$_POST['I_id_orientacion']=$p['id_orientacion'];
	//mysql_free_result($p);
}
	print'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
		<table width=100%>
		<tr>

		<td class="name">Nombre de Ponencia: * </td>
		<td class="input">
		<input TYPE="text" name="S_nombreponencia" size="50" maxlength="150" 
		value="'.$_POST['S_nombreponencia'].'"></td>
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
		</tr>';

		if ($p['id_status'] <=2 || $p['id_status'] == 4) 
		{
			print'	
				<tr>
				<td class="name">Tipo de Propuesta: * </td>
				<td class="input">
				<select name="I_id_tipo">
				<option name="unset" value="0"';
				if (empty($_POST['I_id_tipo'])) 
					echo " selected";
			print '
				></option>';
	
				$result=mysql_query("select * from prop_tipo WHERE id < 100 order by id");
	 			while($fila=mysql_fetch_array($result)) {
			
					print '<option value='.$fila["id"];
					if ($_POST['I_id_tipo']==$fila["id"])
						echo " selected";
					print '>'.$fila["descr"].'</option>';
  				}
				mysql_free_result($result);

			print '	
				</select>
				</td>
				</tr>';
			print '
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
				</tr>';
		}
	print '
		<tr>
		<td class="name">Resumen: *</td>
		<td class="input"><textarea name="S_resumen" cols=60 rows=15>'.stripslashes($_POST['S_resumen']).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Requisitos tecnicos de la ponencia:<br><small>(Estos son los requisitos que Ud. necesita para impartirla)</small> </td>
		<td class="input"><textarea name="S_reqtecnicos" cols=60 rows=5>'.stripslashes($_POST['S_reqtecnicos']).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Prerequisitos para el asistente: *</td>
		<td class="input"><textarea name="S_reqasistente" cols=60 rows=5>'.stripslashes($_POST['S_reqasistente']).'</textarea></td>
		</tr>
		<tr>
		<td class="name">Cambiar archivo: </td>
		<td class="input"><input size="40" type="file" id="fichero" name="fichero"\>
		</tr>		
		<br>
		</table>
		<center>
		<input type="submit" name="submit" value="Actualizar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/ponente/ponente.php?opc=2">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
