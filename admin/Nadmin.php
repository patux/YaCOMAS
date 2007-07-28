<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Registro de Administradores");
	$link=conectaBD();

function imprime_valoresOk() {
	include "../includes/conf.inc.php";
    print '
     		<table width=100%>
		<tr>
		<td class="name">Administrador Login: * </td>
		<td class="resultado">
		'.$_POST['S_login'].'
		</td>
		</tr>

		<tr>
		<td class="name">Nombre(s): * </td>
		<td class="resultado">
		'.stripslashes($_POST['S_nombrep']).'
		</td>
		</tr>

		<tr>
		<td class="name">Apellidos: * </td>
		<td class="resultado">
		'.stripslashes($_POST['S_apellidos']).'
		</td>
		</tr>
		
		<tr>
		<td class="name">Correo Electr&oacute;nico: *</td>
		<td class="resultado">
		'.$_POST['S_mail'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Tipo de administrador: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM tadmin WHERE id="'.$_POST['I_id_tadmin'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver al Menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#admin">
		</center>';

}

// Inicializar variables
if (empty ($_POST['submit']))
{
	$_POST['S_login']='';
	$_POST['S_nombrep']='';
	$_POST['S_apellidos']='';
	$_POST['S_mail']='';
	$_POST['I_id_tadmin']='';
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset($_POST['submit']) && $_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['S_login']) || empty($_POST['S_nombrep']) || empty($_POST['S_apellidos']) || empty($_POST['I_id_tadmin'])) {
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (!preg_match("/.+\@.+\..+/",$_POST['S_mail'])) {     		
  	$errmsg .= "<li>El correo electronico tecleado no es valido";
  }
  // Verifica que el login sea de al menos 4 caracteres
  if (!preg_match("/^\w{4,15}$/",$_POST['S_login'])) {
        $errmsg .= "<li>El login que elijas debe tener entre 4 y 15 caracteres";
  }
  // Verifica que el password sea de al menos 6 caracteres
  if (!preg_match("/^.{6,15}$/",$_POST['S_passwd'])) {
        $errmsg .= "<li>El password debe tener entre 6 y 15 caracteres";
  }
  // Verifica que el password usado no sea igual al login introducido por seguridad
  if ($_POST['S_passwd'] == $_POST['S_login']) {
        $errmsg .= "<li>El password no debe ser igual al login del administrador";
  }
  // Verifica que los password esten escritos correctamente para verificar que
  // la persona introducjo correcamente el password que eligio.
  if ($_POST['S_passwd'] != $_POST['S_passwd2']) {
        $errmsg .= "<li>Los passwords no concuerdan";
  }
  // Si no hay errores verifica que el login no este ya dado de alta en la tabla
  if (empty($errmsg)) {
      $lowlogin = strtolower($_POST['S_login']);
      $userQuery = 'SELECT * FROM administrador WHERE login="'.$lowlogin.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>El login que elegiste ya ha sido dado de alta; por favor elige otro";
      }
  }
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
   	print '<p class="yacomas_error">Note que los campos de password han sido borrados<p>';
  }
// Si todo esta bien vamos a darlo de alta
else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta

// Funcion comentada para no agregar los datos de prueba, una vez que este en produccion hay que descomentarla

  	$query = "INSERT INTO administrador (login,passwd,nombrep,apellidos,mail,id_tadmin) VALUES (".
		"'".$lowlogin."',".
	        "'".md5(stripslashes($_POST['S_passwd']))."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_nombrep']))."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_apellidos']))."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_mail']))."',".
		"'".$_POST['I_id_tadmin']."'".
		")";
//		print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Administrador agregado, ahora ya podra utilizar la cuenta.
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

		<td class="name">Administrador Login: * </td>
		<td class="input">
		<input TYPE="text" name="S_login" size="15" 
		value="'.$_POST['S_login'].'"></td>
		<td> 4 a 15 caracteres
		</td>
		</tr>

		<tr>
		<td class="name">Contrase&ntilde;a: * </td>
		<td class="input">
		<input type="password" name="S_passwd" size="15" 
		value=""></td>
		<td> 6 a 15 caracteres
		</td>
		</tr>

		<tr>
		<td class="name">Confirmaci&oacute;n de Contrase&ntilde;a: * </td>
		<td class="input"><input type="password" name="S_passwd2" size="15"
		value=""></td>
		<td> 

		</td>
		</tr>

		<tr>
		<td class="name">Nombre(s): * </td>
		<td class="input"><input type="text" name="S_nombrep" size="30"
		value="'.stripslashes($_POST['S_nombrep']).'"></td>

		<td> 
		</td>
		</tr>

		<tr>
		<td class="name">Apellidos: * </td>
		<td class="input"><input type="text" name="S_apellidos" size="30"
		value="'.stripslashes($_POST['S_apellidos']).'"></td>
		<td>
		</td>
		</tr>

		<tr>
		<td class="name">Correo Electr&oacute;nico: *</td>
		<td class="input"><input type="text" name="S_mail" size="15"
		value="'.$_POST['S_mail'].'"></td>
		<td>
		</td>
		</tr>
		
		<tr>
		<td class="name">Tipo de administrador: * </td>
		<td class="input">
		<select name="I_id_tadmin">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_tadmin'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from tadmin order by id");
	 	while($fila=mysql_fetch_array($result)) {
			
			print '<option value='.$fila["id"];
			if ($_POST['I_id_tadmin']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#admin">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
