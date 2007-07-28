<?	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
	$link=conectaBD();

$errmsg = "";
if (empty($_POST['submit']))
{
	//Inicializa variables
	$_POST['S_login']='';	
}
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && ($_POST['submit'] == "Iniciar")) {
  if (!preg_match("/^\w{4,15}$/",$_POST['S_login']) || empty($_POST['S_passwd'])) {
  	$errmsg .= "<li>Usuario y/o password no validos. Por favor trate de nuevo";
  }
  else {
      $lowlogin = strtolower($_POST['S_login']);
      $userQuery = 'SELECT id,login,passwd FROM asistente WHERE login="'.$lowlogin.'"';
   
      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));

      $rnum=mysql_num_rows($userRecords);
      if ($rnum == 0) { 
      	$errmsg = '<span class="error">No existe ese usuario.  Trate de nuevo.';
      }
      else {	
      	$p = mysql_fetch_array($userRecords);
      	//Checar el password
       	if ($p['passwd'] != substr(md5($_POST['S_passwd']),0,32)) {
        	$errmsg =  ' <span class="err">Usuario y/o password incorrectos. 
	       	Por favor intente de nuevo o <a href="reset.php"><br>Presiona aqui para resetear tu password</a>.</span>
	       	<p><br>';
	}
	else {  # We have a winner!
	        # begin session
		session_start();
		session_register("YACOMASVARS");
		$_SESSION['YACOMASVARS']['asilogin'] = $lowlogin;
		$_SESSION['YACOMASVARS']['asiid'] = $p['id'];
		$_SESSION['YACOMASVARS']['asilast'] = time();
		# re-route user
		header('Location: menuasistente.php');
		exit;
	     }
	}
    } 
}
// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
imprimeEncabezado();

imprimeCajaTop("50","Inicio de Sesion Asistente");
if (!empty($errmsg)) {
        print $errmsg;
}
elseif (isset($_GET['e']) && ($_GET['e'] == "exp")) { print '<span class="err">Su session ha caducado o no inicio session correctamente.  Por favor trate de nuevo.</span><p>'; }
	print'
		<FORM method="POST" action="'.$_SERVER['PHP_SELF'].'">
		<center>
		<p class="yacomas_error">Las Cookies deben ser habilitadas pasado este punto.</p>
		<table width=50%>
		<tr>

		<td class="name">Nombre de Usuario: </td>
		<td class="input">
		<input TYPE="text" name="S_login" size="15" 
		value="'.$_POST['S_login'].'"></td>
		</tr>

		<tr>
		<td class="name">Contrase&ntilde;a: </td>
		<td class="input">
		<input type="password" name="S_passwd" size="15" 
		value=""></td>
		</tr>
		<br>
		</table>
		<br></br>
		<input type="submit" name="submit" value="Iniciar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="../">
		</center>
		</form>
		<span class="note">Su sesi&oacute;n caducara despu&eacute;s de 1 hora de inactividad</span>
		</center>
		';
imprimeCajaBottom(); 
imprimePie();
?>
