<? 
	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Registro de Propuesta de Ponencias/Talleres");
	$link=conectaBD();
function imprime_valoresOk() {
	include "../includes/conf.inc.php";

    print '
     <table width=100%>
		<tr>
		<td class="name">Ponente: * </td>
		<td class="resultado">
		'.$_POST['S_login'].'
		</td>
		</tr>
		
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
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$_POST['I_id_nivel'].' ORDER BY id"';
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
		<td class="name">Duraci&oacute;n: * </td>
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
		<td class="name">Requisitos t&eacute;cnicos de la ponencia: </td> 
		<td align=justify class="resultado">
		'.$_POST['S_reqtecnicos'].'
		</td>
		</tr>

		<tr>
		<td class="name">Prerequisitos del Asistente: </td>
		<td align=justify class="resultado">
		'.$_POST['S_reqasistente'].'
		</td>
		</tr>';
		if ($_FILES["fichero"]["name"]!=""){
			print '<tr>' .
			'<td class="name">'.
			'Fichero enviado: </td>' .
			'<td align=justify class="resultado">'.$_FILES["fichero"]["name"].''.
			'</td></tr>';
		}
		print'</table>
		<br>
		<center>
		<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
		</center>';

}
// Inicializa variables
if (empty ($_POST['submit']))
{
$_POST['S_login']='';
$_POST['S_nombreponencia']='';
$_POST['I_id_nivel']='';
$_POST['I_id_tipo']='';
$_POST['I_id_orientacion']='';
$_POST['I_duracion']='';
$_POST['S_resumen']='';
$_POST['S_reqtecnicos']='';
$_POST['S_reqasistente']='';

	
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  $S_trim_resumen=trim($_POST['S_resumen']);
  $S_trim_reqtecnicos=trim($_POST['S_reqtecnicos']);
  $S_trim_reqasistente=trim($_POST['S_reqasistente']);
  if (empty($_POST['S_nombreponencia']) || empty($S_trim_resumen) ||
    	empty($_POST['I_id_tipo']) || empty($_POST['I_id_orientacion']) || 
	empty($_POST['I_duracion']) || empty($_POST['I_id_nivel'])) { 
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (($_POST['I_duracion'] > 2) && ($_POST['I_id_tipo'] < 50))
  {
  	$errmsg .= "<li>Solo los talleres y tutoriales pueden ser de mas de 2 horas";
  }
  // Verifica que el ponente exista
      $lowlogin = strtolower($_POST['S_login']);
      $userQuery = 'SELECT id, login FROM ponente WHERE login="'.$lowlogin.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) == 0) {
        $errmsg .= "<li>El ponente que elegiste no existe, elige otro";
      }
      else {
      	$fila=mysql_fetch_array($userRecords);
      	$idponente=$fila['id'];
      }
  // Verifica que la ponencia no este dada de alta
  if (empty($errmsg)) {
      $userQuery = 'SELECT * FROM propuesta WHERE nombre="'.$_POST['S_nombreponencia'].'" and id_ponente="'.$idponente.'"';
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

// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla
	
	$date=strftime("%Y%m%d%H%M%S");
  	$query = "INSERT INTO propuesta (nombre,resumen,reqtecnicos,reqasistente,id_nivel,duracion,reg_time,id_ponente,id_prop_tipo,id_orientacion";
  	$fichero=$_FILES["fichero"];
	if (isset($fichero["name"]) && $fichero["name"]!=""){
		$query.= ",nombreFile";
		$query.= ",tipoFile";
		$query.=",dirFile";
	}
	$query.= ") ".
	"VALUES (".
		"'".mysql_real_escape_string(stripslashes($_POST['S_nombreponencia']))."',".
		"'".mysql_real_escape_string(stripslashes($S_trim_resumen))."',".
		"'".mysql_real_escape_string(stripslashes($S_trim_reqtecnicos))."',".
		"'".mysql_real_escape_string(stripslashes($S_trim_reqasistente))."',".
		"'".$_POST['I_id_nivel']."',".
		"'".$_POST['I_duracion']."',".
		"'".$date."',".
		"'".$idponente."',".
		"'".$_POST['I_id_tipo']."',".
		"'".$_POST['I_id_orientacion']."'";
		//Cambio el nombre del archivo a usuario!nombrefile con eso consigo tener un unico archivo
		//Si es el mismo nombre es que es de la misma ponencia y usuario...la machaca.
 

        $resaux=$_POST['S_login'];
        //Ok todo correcto recogo el login para aÃadir al fichero
        $rutafilename=$archivos.$resaux.CARACTERSEPARADOR.$fichero["name"];
		if (isset($fichero["name"]) && $fichero["name"]!=""){
			$query.= ",'".stripslashes($fichero["name"])."'";
			$query.= ",'".stripslashes($fichero["type"])."'";
			$query.= ",'".stripslashes($rutafilename)."'";
		}
		$query.=")";
		//
		// Para debugear querys
		// print $query;
		//
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
		if (isset($fichero["name"]) && $fichero["name"]!=""){
            if (!(move_uploaded_file($fichero["tmp_name"],$rutafilename))){
                die("Imposible copiar fichero");
            }
        }
 	print 'Propuesta de ponencia ha sido registrada .
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
	print'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
		<table width=100%>
		<tr>

		<td class="name">Login ponente: * </td>
		<td class="input">
		<input TYPE="text" name="S_login" size="15" 
		value="'.$_POST['S_login'].'"></td>
		</tr>

		<td class="name">Nombre de Ponencia: * </td>
		<td class="input">
		<input TYPE="text" name="S_nombreponencia" size="50" maxlength="150"
		value="'.$_POST['S_nombreponencia'].'"></td>
		</tr>
		
		<tr>
		<td class="name">Orientaci&oacute;n : * </td>
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
		<select name="I_id_tipo">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_prop_tipo'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from prop_tipo order by id");
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
		</tr>

		<td class="name">Duraci&oacute;n (hrs): * </td>
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
		<td class="input"><textarea name="S_resumen" cols=60 rows=15>'.stripslashes($_POST['S_resumen']).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Requisitos t&eacute;cnicos de la ponencia:<br><small>(Estos son los requisitos que Ud. necesita para impartirla)</small> </td>
		<td class="input"><textarea name="S_reqtecnicos" cols=60 rows=5>'.stripslashes($_POST['S_reqtecnicos']).'</textarea></td>
		</tr>
		
		<tr>
		<td class="name">Prerequisitos para el asistente: </td>
		<td class="input"><textarea name="S_reqasistente" cols=60 rows=5>'.stripslashes($_POST['S_reqasistente']).'</textarea></td>
		</tr>

		<tr>
		<td class="name">Enviar archivo: </td>
		<td class="input"><input size="40" type="file" id="fichero" name="fichero"\>
		</tr>
		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrar">&nbsp;&nbsp;
		<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
