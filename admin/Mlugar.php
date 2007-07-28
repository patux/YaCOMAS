<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	$idlugar=$_GET['idlugar'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Modificar lugares para para ponencias");
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
		'.$_POST['I_cupo'].'
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver al Listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=5">
		</center>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Actualizar") {
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
	elseif ($_POST['I_cupo'] < 5 ) 
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
      	$p = mysql_fetch_array($userRecords);
	if ($p['id'] != $idlugar) 
	{ 
        	$errmsg .= "<li>El nombre del lugar que elegiste ya ha sido dado de alta; por favor elige otro";
	}
      	
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
	if (empty($_POST['I_cupo']))
	{
		$_POST['I_cupo']=99999;
	}
  		$query = "UPDATE lugar SET nombre_lug="."'".$uppname."',
				   ubicacion="."'".mysql_real_escape_string(stripslashes($_POST['S_ubicacion']))."',
				   cupo="."'".$_POST['I_cupo']."'
				   WHERE id=$idlugar";
//		print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Lugar para evento actualizado,
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
	$userQuery ='SELECT nombre_lug,ubicacion,cupo FROM lugar WHERE id="'.$idlugar.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar el lugar".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_nombre_lug']=$p['nombre_lug'];
	$_POST['S_ubicacion']=$p['ubicacion'];
	$_POST['I_cupo']=$p['cupo'];

}
	print'
		<FORM method="POST" action="'.$REQUEST_URI.'">
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
		</tr>';

		if ($_POST['I_cupo']!=99999) {
			print'	
				<tr>
				<td class="name">Cupo: </td>
				<td class="input">
				<select name="I_cupo">';

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
				</td>
				</tr>';
		}
	print '					
		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Actualizar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=5">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
