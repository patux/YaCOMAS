<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Registro de Lugares para ponencias");
	$link=conectaBD();

function imprime_valoresOk() {
	include "../includes/conf.inc.php";
    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre: * </td>
		<td class="resultado">
		'.strtoupper($_POST['S_nombre_lug']).'
		</td>
		</tr>

		<tr>
		<td class="name">Ubicacion: * </td>
		<td class="resultado">
		'.stripslashes($_POST['S_ubicacion']).'
		</td>
		</tr>

		<tr>
		<td class="name">Cupo: * </td>
		<td class="resultado">
		'.stripslashes($_POST['I_cupo']).'
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver al Menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#lugares">
		</center>';

}
if (empty ($_POST['submit']))
{
	$_POST['S_nombre_lug']='';
	$_POST['S_ubicacion']='';
	$_POST['I_cupo']='';
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['S_nombre_lug']) || empty($_POST['S_ubicacion'])) {
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (!empty($_POST['I_cupo'])) {
  	if ($_POST['I_cupo'] > $limite) 
	{
		$errmsg .= "<li>El cupo del lugar no debe sobrepasar ".$limite;
	}
	elseif ($_POST['I_cupo'] < 5) 
	{
		$errmsg .= "<li>El cupo del lugar no debe ser menor a 5";
	}
  }
  // Si no hay errores verifica que el lugar no este ya dado de alta en la tabla
  if (empty($errmsg)) {
      $uppname= strtoupper($_POST['S_nombre_lug']);
      $userQuery = 'SELECT * FROM lugar WHERE nombre_lug="'.$uppname.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>El nombre del lugar que elegiste ya ha sido dado de alta; por favor elige otro";
      }
  }
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
  }
// Si todo esta bien vamos a darlo de alta
else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta

// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla

	if (!empty($_POST['I_cupo'])) 
	{
  		$query = "INSERT INTO lugar (nombre_lug,ubicacion,cupo) VALUES (".
		"'".$uppname."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_ubicacion']))."',".
		"'".$_POST['I_cupo']."'".
		")";
	}
  	else
	{
		$query = "INSERT INTO lugar (nombre_lug,ubicacion) VALUES (".
		"'".$uppname."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_ubicacion']))."'".
		")";
	}
//		print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Lugar para evento agregado, ahora ya podra asignarlo a cualquier propuesta aceptada.
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
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
		<table width=100%>

		<tr>
		<td class="name">Nombre: * </td>
		<td class="input">
		<input TYPE="text" name="S_nombre_lug" size="50" 
		value="'.$_POST['S_nombre_lug'].'"></td>
		<td> 
		</td>
		</tr>
		
		<tr>
		<td class="name">Ubicacion: * </td>
		<td class="input">
		<input TYPE="text" name="S_ubicacion" size="50" 
		value="'.$_POST['S_ubicacion'].'"></td>
		<td> 
		</td>
		</tr>
		
		<tr>
		<td class="name">Cupo: </td>
		<td class="input">
		<select name="I_cupo">

		<option name="unset" value=""';
		if (empty($_POST['I_cupo'])) 
			echo " selected";
	print '
		></option>';
		for ($Icupo=$limite;$Icupo>=5;$Icupo--){
			printf ("<option value=%02d",$Icupo);
			if ($_POST['I_cupo']==$Icupo)
				echo " selected";
			printf (">%02d </option>",$Icupo);
		}
	print '
		</select>
		</td>
		<td>
		Dejar vacio si es lugar para conferencias
		</td>
		</tr>
					
		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#lugares">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
