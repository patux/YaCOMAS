<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	$idadmin=$_SESSION['YACOMASVARS']['rootid'];
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Registro de fechas para el Congreso");
	$link=conectaBD();

function imprime_valoresOk() {
	include "../includes/conf.inc.php";
    print '
     		<table width=100%>
		<tr>
		<td class="name">Fecha evento: * </td>
		<td class="resultado">';
		printf ("%02d-%02d-%04d",$_POST['I_e_day'],$_POST['I_e_month'],$_POST['I_e_year']);
    print '     </td>
		</tr>

		<tr>
		<td class="name">Descripcion: </td>
		<td class="resultado">
		'.stripslashes($_POST['S_descr']).'
		</td>
		</tr>

		</table>
		<br>
		<center>
		<input type="button" value="Volver al Menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#lugares">
		</center>';

}
if (empty ($_POST['I_e_day']))
{
	$_POST['I_e_day']='';
	$_POST['I_e_month']='';
	$_POST['I_e_year']='';
	$_POST['S_descr']='';
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Registrar") {
  # do some basic error checking
  $errmsg = "";
  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($_POST['I_e_day']) || empty($_POST['I_e_month']) || empty($_POST['I_e_year'])) {
	$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
  }
  // Si no hay errores verifica que la fecha no este ya dado de alta en la tabla
  $f_evento=$_POST['I_e_year'].'-'.$_POST['I_e_month'].'-'.$_POST['I_e_day'];
  if (empty($errmsg)) {
      $userQuery = 'SELECT * FROM fecha_evento WHERE fecha="'.$f_evento.'"';
      $userRecords = mysql_query($userQuery) or err("No se pudo checar la fecha".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) != 0) {
        $errmsg .= "<li>La fecha que elegiste ya sido dado de alta; por favor elige otra";
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

  		$query = "INSERT INTO fecha_evento (fecha,descr) VALUES (".
		"'".$f_evento."',".
		"'".mysql_real_escape_string(stripslashes($_POST['S_descr']))."'".
		")";
//		print $query;
		$result = mysql_query($query) or err("No se puede insertar los datos".mysql_errno($result));
 	print '	Fecha para evento agregado, ahora ya podra asignarlo a cualquier propuesta aceptada.
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
		$startyear=strftime("%Y");
	print'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<p><i>Campos marcados con un asterisco son obligatorios</i>
		<br><small>En caso de que el congreso tenga descripciones especiales para cada dia entonces llenar el campo descripci&oacute;n</small>
		</p>
		<table width=100%>
		
		<tr>
		<td class="name">Fecha evento: *</td>
		<td class="input">
		Dia: 
		<select name="I_e_day">
		<option name="unset" value="0"';
		if (empty($_POST['I_e_day'])) 
			echo " selected";
	print '
		></option>';
		for ($Idia=1;$Idia<=31;$Idia++){
			printf ("<option value=%02d",$Idia);
			if ($_POST['I_e_day']==$Idia)
				echo " selected";
			printf (">%02d </option>",$Idia);
		}
	print '
		</select>
		Mes:
		<select name="I_e_month">
		<option name="unset" value="0"';
		if (empty($_POST['I_e_month'])) 
			echo " selected";
	print '
		></option>';
		for ($Imes=1;$Imes<=12;$Imes++){
			printf ("<option value=%02d",$Imes);
			if ($_POST['I_e_month']==$Imes)
				echo " selected";
			printf (">%02d </option>",$Imes);
		}
	print '

		</select>

		A&ntilde;o:
		<select name="I_e_year">
		<option name="unset" value="0"';
		if (empty($_POST['I_e_year'])) 
			echo " selected";
	print '
		></option>';
		for ($Ianio=$startyear;$Ianio<=$startyear+1;$Ianio++){
			print '<option value='.$Ianio;
			if ($_POST['I_e_year']==$Ianio)
				echo " selected";
			print '>'.$Ianio.'</option>';
		}
	print '
		</select></td>
		<td>&nbsp;</td>
		</tr>
		
		<tr>
		<td class="name">Descripcion: </td>
		<td class="input">
		<input TYPE="text" name="S_descr" size="50" 
		value="'.$_POST['S_descr'].'"></td>
		<td> 
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
