<?
	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
/*	Debes tener PEAR instalado http://pear.php.net
	y el modulo basico de Mail
	http://pear.php.net/package/Mail/download
*/
	include_once "Mail.php";
	$link=conectaBD();
	imprimeEncabezado();
	
	imprimeCajaTop("50","Resetear contrase&ntilde;a");
// Inicializar variables
if (empty ($_POST['submit']))
{
	$_POST['S_login']='';
	$_POST['S_mail']='';
}

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Reset") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['S_login'])) { 
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  if (!preg_match("/.+\@.+\..+/",$_POST['S_mail'])) {     		
  	$errmsg .= "<li>El correo electronico tecleado no es valido";
  }
  // Si no hay errores verifica que el login ya dado de alta en la tabla y el mail introducido
  // Sea igual al que esta en la tabla para mandar el nuevo contrasenia
  if (empty($errmsg)) 
  	{
      	$lowlogin = strtolower($_POST['S_login']);
      	$userQuery = 'SELECT mail FROM ponente WHERE login="'.$lowlogin.'"';
      	$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
      	if (mysql_num_rows($userRecords) != 0) 
		{
		// Verifica que el correo sea el mismo que introdujo en la forma
      		$usuario=mysql_fetch_array($userRecords);
		$mail_user=$_POST['S_mail'];
		if ($usuario['mail'] != $mail_user) 
			{
			$errmsg .= "<li>El correo electronico tecleado no concuerda con el registrado por el usuario";
			}
      		}
	else
		{
		$errmsg .= "<li>El usuario tecleado no existe";
		}
  }
  mysql_free_result($userRecords);
  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
  }
  // Si todo esta bien vamos a actualizar el contrasenia y a mandar el correo 
  else 	{ // Todas las validaciones Ok 
 	 // vamos actualizar el correo de alta
	$user=$_POST['S_login'];
	$npasswd=generatePassword();
	/////////////////////
	// Envia el correo:
	/////////////////////
	$recipients = $mail_user;

	$headers["From"]    = $general_mail;
	$headers["To"]      = $mail_user;
	$headers["Subject"] = "$conference_name Cambio de contrasenia ponente";
	$message  = "";
	$message .= "Has solicitado cambio de contrasenia para el usuario: $user\n";
	$message .= "La nueva contrasenia es: $npasswd\n\n";
    $message .= "--------------------------------------------------------------\n";
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

  	$query = 'UPDATE ponente SET passwd="'.md5($npasswd).'" 
			WHERE login="'.$user.'" AND mail="'.$mail_user.'"';
	// print $npasswd;
	// retorno();
	// Para debugear
	// print $query;
	// retorno();
	$result = mysql_query($query) or err("No se puede actualizar los datos".mysql_errno($result));
	print 	'Se ha actualizado la contrase&ntilde;a del usuario <b>'.$user.'</b>';
	retorno();
	print 	'La nueva contrase&ntilde;a del usuario '.$user.' ha sido enviado a la direcci&oacute;n de correo: <b>'.$mail_user.'</b>'; 
retorno();
	print 	'<p class="yacomas_msg">Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX</p>';
	/////////////////////
	retorno();
	retorno();
	print	'
		<center>
		<input type="button" value="Continuar" onClick=location.href="../">
		</center>
		 ';
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
	retorno();
	print'
		<FORM method="POST" action="'.$_SERVER['PHP_SELF'].'">
		<center>
		<table width=60%>
		<tr>
		<td class="name">Nombre de Usuario: * </td>
		<td class="input">
		<input TYPE="text" name="S_login" size="15" 
		value="'.$_POST['S_login'].'"></td>
		</tr>

		<tr>
		<td class="name">Correo Electr&oacute;nico: *</td>
		<td class="input"><input type="text" name="S_mail" size="15"
		value="'.$_POST['S_mail'].'"></td>
		<td>
		<br>
		</table>
		<br></br>
		<br></br>
		<input type="submit" name="submit" value="Reset">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="../">
		</center>
		</form>
		</center>
		';
	imprimeCajaBottom();
	imprimePie();
?>
