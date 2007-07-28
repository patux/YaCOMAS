<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Modificar Datos de Administrador");
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
		<TD Class="name">Nombre(s): * </td>
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
		<td class="name">Correo electronico: * </td>
		<td class="resultado">
		'.$_POST['S_mail'].'
		</td>
		</tr>
		
		</table>
		<br>
		<center>
		<input type="button" value="Volver al Menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#admin">
		</center>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Modificar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['S_login']) || empty($_POST['S_nombrep']) || empty($_POST['S_apellidos'])) {
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  // Verifica que el login sea de al menos 4 caracteres
  if (!preg_match("/^\w{4,15}$/",$_POST['S_login'])) {
        $errmsg .= "<li>El login que elijas debe tener entre 4 y 15 caracteres";
  }
  if (!preg_match("/.+\@.+\..+/",$_POST['S_mail'])) {     		
  	$errmsg .= "<li>El correo electronico tecleado no es valido";
  }
  // Verifica que el password sea de al menos 6 caracteres
  if (!empty($_POST['S_passwd'])) 
     {
  	if (!preg_match("/^.{6,15}$/",$_POST['S_passwd'])) {
        	$errmsg .= "<li>El password debe tener entre 6 y 15 caracteres";
  	}
  	// Verifica que el password usado no sea igual al login introducido por seguridad
  	if ($_POST['S_passwd'] == $_POST['S_login']) {
        	$errmsg .= "<li>El password no debe ser igual a tu login";
  	}
  	// Verifica que los password esten escritos correctamente para verificar que
  	// la persona introducjo correcamente el password que eligio.
  	if ($_POST['S_passwd'] != $_POST['S_passwd2']) {
        	$errmsg .= "<li>Los passwords no concuerdan";
  	}
    }
  // Si no hay errores verifica que el login no este ya dado de alta en la tabla
  if (empty($errmsg)) {
      $lowlogin = strtolower($_POST['S_login']);
      $userQuery = 'SELECT * FROM administrador WHERE login="'.$lowlogin.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
      	$p = mysql_fetch_array($userRecords);
	if ($p['id'] != $idadmin) 
	{ 
        	$errmsg .= "<li>El login que elegiste ya ha sido dado de alta; por favor elige otro";
	}
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
	if (!empty($_POST['S_passwd']))
		{
  			$query = "UPDATE administrador SET login="."'".$lowlogin."',
				   passwd="."'".md5(stripslashes($_POST['S_passwd']))."',
				   nombrep="."'".mysql_real_escape_string(stripslashes($_POST['S_nombrep']))."',
				   apellidos="."'".mysql_real_escape_string(stripslashes($_POST['S_apellidos']))."',
				   mail="."'".$_POST['S_mail']."'
				   WHERE id="."'".$idadmin."'";
		}
	else 
		{
  			$query = "UPDATE administrador SET login="."'".$lowlogin."',
				   nombrep="."'".mysql_real_escape_string(stripslashes($_POST['S_nombrep']))."',
				   apellidos="."'".mysql_real_escape_string(stripslashes($_POST['S_apellidos']))."',
				   mail="."'".$_POST['S_mail']."'
				   WHERE id="."'".$idadmin."'";
		}
	    
//		print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Administrador modificado.
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
	$userQuery ='SELECT * FROM administrador WHERE id="'.$idadmin.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_login']=$p['login'];
	$_POST['S_nombrep']=$p['nombrep'];
	$_POST['S_apellidos']=$p['apellidos'];
	$_POST['S_mail']=$p['mail'];
     }
	print'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<p><i>Campos marcados con un asterisco son obligatorios</i></p>
   		<p class="yacomas_msg">Si deja los campos de contraseña vacia seguira usando su misma contraseña</p>
		<table width=100%>
		
		<tr>
		<td class="name">Administrador Login: * </td>
		<td class="input">
		<input TYPE="text" name="S_login" size="15"';
	//	if ($idadmin==1)
	//		print 'readonly ';
		print 'value="'.$_POST['S_login'].'"></td>
		<td> 4 a 15 caracteres
		</td>
		</tr>

		<tr>
		<td class="name">Contrase&ntilde;a: * </td>
		<td class="input">
		<input type="password" name="S_passwd" size="15"'; 
	//	if ($idadmin==1)
	//		print 'readonly ';
		print 'value=""></td>
		<td> 6 a 15 caracteres
		</td>
		</tr>

		<tr>
		<td class="name">Confirmaci&oacute;n de Contrase&ntilde;a: * </td>
		<td class="input">
		<input type="password" name="S_passwd2" size="15"'; 
	//	if ($idadmin==1)
	//		print 'readonly ';
		print 'value=""></td>
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
		<td class="name">Correo electronico: * </td>
		<td class="input">
		<input TYPE="text" name="S_mail" size="15" 
		value="'.$_POST['S_mail'].'"></td>
		</td>
		</tr>
		
		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Modificar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#admin">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
