<? 
	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
/*	Debes tener PEAR instalado http://pear.php.net
	y el modulo basico de Mail
	http://pear.php.net/package/Mail/download
*/
	include_once "Mail.php";

	imprimeEncabezado();
	
	imprimeCajaTop("100","Registro de Asistentes");
	$link=conectaBD();
	$configQuery= 'SELECT status FROM config WHERE id=2';
	$resultCQ=mysql_query($configQuery);
	$CQfila=mysql_fetch_array($resultCQ);
	$stat_array=$CQfila["status"];
	mysql_free_result($resultCQ);
	if ($stat_array==0) {
		retorno();
		retorno();
		print '<p class="yacomas_error">El registro para asistentes se encuentra cerrado';
		retorno();
		retorno();
		print ' <center>
			<input type="button" value="Continuar" onClick=location.href="../">
	        	</center>';
		retorno();
		exit;
	}

function imprime_valoresOk() {
	include "../includes/conf.inc.php";

    print '
     	<table width=100%>
		<tr>
		<td class="name">Nombre de Usuario: * </td>
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
		<td class="name">Sexo: * </td>
		<td class="resultado">';
		if ($_POST['C_sexo']=="M")
		    echo "Masculino";
		else
		    echo "Femenino";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Organizaci&oacute;n: </td>
		<td class="resultado">
		'.stripslashes($_POST['S_org']).'
		</td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM estudios WHERE id="'.$_POST['I_id_estudios'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Tipo de Asistente: *</td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM tasistente WHERE id="'.$_POST['I_id_tasistente'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);
		
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Ciudad: </td>
		<td class="resultado">
		'.$_POST['S_ciudad'].'
		</td>
		</tr>


		<tr>
		<td class="name">Estado: * </td>
		<td class="resultado">';
		
		$query= "select * from estado where id='".$_POST['I_id_estado']."'";
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
		printf ("%02d-%02d-%04d",$_POST['I_b_day'],$_POST['I_b_month'],$_POST['I_b_year']);
	print '	
		</td>
		</tr>
		</table>
		<br>
		<center>
		<input type="button" value="Continuar" onClick=location.href="../">
		</center>';

}
// Inicializar variables
if (empty ($_POST['submit']))
{
	$_POST['S_login']='';
	$_POST['S_nombrep']='';
	$_POST['S_apellidos']='';
	$_POST['S_mail']='';
	$_POST['C_sexo']='';
	$_POST['S_org']='';
	$_POST['I_id_estudios']='';
	$_POST['S_ciudad']='';
	$_POST['I_id_estado']='';
	$_POST['I_id_tasistente']='';
	$_POST['I_b_day']='';
	$_POST['I_b_month']='';
	$_POST['I_b_year']='';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Registrarme") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['S_login']) || empty($_POST['S_nombrep']) || empty($_POST['S_apellidos']) ||
    	empty($_POST['C_sexo']) || empty($_POST['I_id_estudios']) || empty($_POST['I_id_tasistente']) || 
	empty($_POST['I_id_estado'])) { 
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
  elseif ($_POST['S_passwd'] == $_POST['S_login']) {
        $errmsg .= "<li>El password no debe ser igual a tu login";
  }
  // Verifica que los password esten escritos correctamente para verificar que
  // la persona introducjo correcamente el password que eligio.
  if ($_POST['S_passwd'] != $_POST['S_passwd2']) {
        $errmsg .= "<li>Los passwords no concuerdan";
  }
  // Si no hay errores verifica que el login no este ya dado de alta en la tabla
  if (empty($errmsg)) {
      $lowlogin = strtolower($_POST['S_login']);
      $userQuery = 'SELECT * FROM asistente WHERE login="'.$lowlogin.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>El usuario que elegiste ya ha sido tomado; por favor elige otro";
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

   	$f_nac=$_POST['I_b_year'].'-'.$_POST['I_b_month'].'-'.$_POST['I_b_day'];
	$date=strftime("%Y%m%d%H%M%S");
  	$query = "INSERT INTO asistente (login,passwd,nombrep,apellidos,sexo,mail,ciudad,org,fecha_nac,reg_time,id_estudios,id_tasistente,id_estado) VALUES (".
		"'".$lowlogin."',".
	    "'".md5(addslashes($_POST['S_passwd']))."',".
		"'".mysql_real_escape_string(addslashes($_POST['S_nombrep']))."',".
		"'".mysql_real_escape_string(addslashes($_POST['S_apellidos']))."',".
		"'".$_POST['C_sexo']."',".
		"'".$_POST['S_mail']."',".
		"'".$_POST['S_ciudad']."',".
		"'".addslashes($_POST['S_org'])."',".
		"'".$f_nac."',".
		"'".$date."',".
		"'".$_POST['I_id_estudios']."',".
		"'".$_POST['I_id_tasistente']."',".
		"'".$_POST['I_id_estado']."'".
		")";
	//	print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
	/////////////////////
	// Envia el correo:
	/////////////////////
	$user=$_POST['S_login']; 
	$passwd_user = $_POST['S_passwd']; 
	$mail_user = $_POST['S_mail'];
	$recipients = $mail_user;

	$headers["From"]    = $general_mail;
	$headers["To"]      = $mail_user;
	$headers["Subject"] = "Registro de asistente";
	$message  = "";
	$message .= "Te has registrado como asistente al $conference_name\n";
	$message .= "Usuario: $user\n";
	$message .= "Contrase&ntilde;a: $passwd_user\n\n";
	$message .= "Puedes inicar sesion en: http://$URL/yacomas/asistente/\n\n\n";
	$message .= "---------------------------------------\n";
	$message .= "$conference_link\n";
	$params["host"] = $smtp;
	$params["port"] = "25";
	$params["auth"] = false;
    // Added a verification to check if SEND_MAIL constant is enable patux@patux.net
    // TODO:
    // We need to wrap a function in include/lib.php to send emails in a generic way
    // This function must validate if SEND_MAIL is enable or disable
    if (SEND_MAIL == 1) // If is enable we will send the mail
    {
	    // Create the mail object using the Mail::factory method
	    $mail_object =& Mail::factory("smtp", $params);
	    $mail_object->send($recipients, $headers, $message);
    }
 	print '	Gracias por darte de alta, ahora ya podras accesar a tu cuenta.<br>
		Los datos de tu usuario y password han sido enviados al correo que registraste';
retorno();
	print ' Por razones de seguridad deshabilitamos el enviacute;o de correo ';
retorno();
	print 	'<p class="yacomas_msg">Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX</p>';
	print '
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la p&aacute;gina, por favor contacta a 
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
        <p class="yacomas_error">Asegurate de escribir bien tus datos personales ya que estos ser&aacute;n tomados para tu constancia de participaci&oacute;n</p>
		<table width=100%>
		<tr>

		<td class="name">Nombre de Usuario: * </td>
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
		<td class="name">Correo Electr&ntilde;nico: *</td>
		<td class="input"><input type="text" name="S_mail" size="15"
		value="'.$_POST['S_mail'].'"></td>
		<td>

		</td>
		</tr>


		<tr>
		<td class="name">Sexo: * </td>
		<td class="input">
		<select name="C_sexo">
		<option name="unset" value="" ';
		
		if (empty($_POST['C_sexo'])) 
			echo "selected";
		
	print '
		></option>"
		<option value="M"';
		if ($_POST['C_sexo']=="M")
			echo "selected";
	
	print '
		>Masculino</option>"
		<option value="F"';
		if ($_POST['C_sexo']=="F") 
			echo "selected";
	print '
		>Femenino</option>"
		</select>
		</td>
		</tr>

		<tr>
		<td class="name">Organizaci&oacute;n: </td>
		<td class="input"><input type="text" name="S_org" size="15"
		value="'.stripslashes($_POST['S_org']).'"></td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="input">
		<select name="I_id_estudios">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_estudios'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from estudios order by id");
	 	while($fila=mysql_fetch_array($result)) {
			
			print '<option value='.$fila["id"];
			if ($_POST['I_id_estudios']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);

	print '	
		</select>
		</td>
		</tr>

		<tr>
		<td class="name">Tipo de Asistente: *</td>
		<td class="input">
		<select name="I_id_tasistente">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_tasistente'])) 
			echo " selected";
	print '
		></option>';
	
		$result=mysql_query("select * from tasistente order by id");
	 	while($fila=mysql_fetch_array($result)) {
			print '<option value='.$fila["id"];
			if ($_POST['I_id_tasistente']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);
	print '	
		</select></td>
		<td> 

		</td>
		</tr>

		<tr>
		<td class="name">Ciudad: </td>
		<td class="input"><input type="text" name="S_ciudad" size="10"
		value="'.stripslashes($_POST['S_ciudad']).'"></td>
		</tr>


		<tr>
		<td class="name">Estado: * </td>

		<td class="input">
		<select name="I_id_estado">
		<option name="unset" value="0"';
		if (empty($_POST['I_id_estado'])) 
			echo " selected";
	print '
		></option>';
		$result=mysql_query("select * from estado order by id");
 		while($fila=mysql_fetch_array($result)) {
			print '<option value='.$fila["id"];
			if ($_POST['I_id_estado']==$fila["id"])
				echo " selected";
			print '>'.$fila["descr"].'</option>';
  		}
		mysql_free_result($result);
	print '
		</select></td>
		<td>

		</td>
		</tr>

		<tr>
		<td class="name">Fecha de Nacimiento: </td>
		<td class="input">
		Dia: 
		<select name="I_b_day">
		<option name="unset" value="0"';
		if (empty($_POST['I_b_day'])) 
			echo " selected";
	print '
		></option>';
		for ($Idia=1;$Idia<=31;$Idia++){
			printf ("<option value=%02d",$Idia);
			if ($_POST['I_b_day']==$Idia)
				echo " selected";
			printf (">%02d </option>",$Idia);
		}
	print '
		</select>
		Mes:
		<select name="I_b_month">
		<option name="unset" value="0"';
		if (empty($_POST['I_b_month'])) 
			echo " selected";
	print '
		></option>';
		for ($Imes=1;$Imes<=12;$Imes++){
			printf ("<option value=%02d",$Imes);
			if ($_POST['I_b_month']==$Imes)
				echo " selected";
			printf (">%02d </option>",$Imes);
		}
	print '

		</select>

		A&ntilde;o:
		<select name="I_b_year">
		<option name="unset" value="0"';
		if (empty($_POST['I_b_year'])) 
			echo " selected";
	print '
		></option>';
		for ($Ianio=1999;$Ianio>=1950;$Ianio--){
			print '<option value='.$Ianio;
			if ($_POST['I_b_year']==$Ianio)
				echo " selected";
			print '>'.$Ianio.'</option>';
		}
	print '
		</select></td>
		<td class="error">



		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="submit" name="submit" value="Registrarme">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="../">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
