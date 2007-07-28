<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Eliminar Administrador");
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk() {
	include "../includes/conf.inc.php";

    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre del Administrdor: * </td>
		<td class="resultado">
		'.$_POST['S_login'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Nombre del Administrdor: * </td>
		<td class="resultado">
		'.$_POST['S_nombrep'].'
		</td>
		</tr>

		<tr>
		<td class="name">Apellidos:  </td>
		<td class="resultado">
		'.$_POST['S_apellidos'].'
		</td>
		</tr>
		
		<tr>
		<td class="name">Mail:  </td>
		<td class="resultado">
		'.$_POST['S_mail'].'
		</td>
		</tr>
	
	</table>
		<br>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset($_POST['submit']) && $_POST['submit'] == "Eliminar") {
  # do some basic error checking
  // Si todo esta bien vamos a borrar el registro 
  	$query1 = "DELETE FROM administrador WHERE id="."'".$_GET['admin']."'";
	$query2= "UPDATE propuesta SET id_administrador=1 WHERE id="."'".$_GET['admin']."'";
	$query3= "UPDATE evento SET id_administrador=1 WHERE id="."'".$_GET['admin']."'";
		// Para debugear querys
		/* print $query1;
		retorno();
		print $query2;
		retorno();
		print $query3;
		retorno();
		*/
		//
		$result1 = mysql_query($query1) or err("No se puede eliminar los datos".mysql_errno($result1));
		$result2 = mysql_query($query2) or err("No se puede eliminar los datos".mysql_errno($result2));
		$result3 = mysql_query($query3) or err("No se puede eliminar los datos".mysql_errno($result3));
 	print '	El administrador ha sido eliminado.<br>
		<p class="yacomas_msg">Las propuestas que ha autorizado el mismo han sido asignadas al administrador principal</p>
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=1">
		 </center>';

 	imprimeCajaBottom(); 
 	imprimePie(); 
//	Necesitamos este exit para salirse ya de este programa y evitar que se imprima la forma porque 
//	los datos ya fueron intruducidos y la transaccion se realizo con exito
	exit;
}
// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
else {
	$userQuery = 
	'SELECT login, nombrep, apellidos,mail FROM administrador WHERE id="'.$_GET['admin'].'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar el administrador".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_login']=$p['login'];
	$_POST['S_nombrep']=$p['nombrep'];
	$_POST['S_apellidos']=$p['apellidos'];
	$_POST['S_mail']=$p['mail'];
}
	imprime_valoresOk();
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Eliminar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=1">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
