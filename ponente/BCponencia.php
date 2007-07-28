<? 
	include "../includes/lib.php";
	include "../includes/conf.inc";
	beginSession('P');
	imprimeEncabezado();
	aplicaEstilo();
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';

	$link=conectaBD();
	
	$tok = strtok ($vopc," ");
	$ponencia=$tok;
	$tok = strtok (" ");
	$action=$tok;
	if ($action!=7) {
		$verbo1="Cancelar";
		$verbo2="Cancelada";
	}
	else {
		$verbo1="Eliminar";
		$verbo2="Eliminada";
	}
	imprimeCajaTop("100",$verbo1." Ponencia");
	print '<hr>';

function imprime_valoresOk() {
	include "../includes/conf.inc";

    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponencia: * </td>
		<td class="resultado">
		'.$_POST[S_nombreponencia].'
		</td>
		</tr>

		<tr>
		<td class="name">Nivel: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$_POST[I_id_nivel].'"';
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
		
		if ($_POST[C_tpropuesta]=="C")
		    echo "Conferencia";
		else
		    echo "Taller";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Orientacion: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM orientacion WHERE id="'.$_POST[I_id_orientacion].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>
		
		<tr>
		<td class="name">Duracion: * </td>
		<td class="resultado">';
		printf ("%02d Hrs",$_POST[I_duracion]);
	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Resumen: </td>
		<td class="resultado">
		'.$_POST[S_resumen].'
		</td>
		</tr>

		</table>
		<br>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($_POST['submit'] == "Aceptar") {
  # do some basic error checking
  // Si todo esta bien vamos a borrar el registro 
  	if ($action!=7)
  		$query = "UPDATE propuesta SET id_status='$action' WHERE id="."'".$ponencia."'"; 
	else    
		$query = 'DELETE FROM propuesta WHERE id='.$ponencia;
		// Para debugear querys
		//print $query;
		//
		$result = mysql_query($query) or err("No se puede eliminar los datos".mysql_errno($result));
 	print '	Tu propuesta ha sido '.$verbo2.'
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta al 
		 <a href="mailto:patux@glo.org.mx">FSL Developer team</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$rootpath.'/ponente/ponente.php?opc=2">
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
	'SELECT nombre, resumen, id_nivel, tpropuesta, duracion, id_status, id_orientacion FROM propuesta WHERE id="'.$ponencia.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_nombreponencia']=$p['nombre'];
	$_POST['I_id_nivel']=$p['id_nivel'];
	$_POST['S_resumen']=$p['resumen'];
	$_POST['C_tpropuesta']=$p['tpropuesta'];
	$_POST['I_duracion']=$p['duracion'];
	$_POST['I_id_orientacion']=$p['id_orientacion'];
}
	imprime_valoresOk();
	print'<center>
		<FORM method="POST" action="'.$REQUEST_URI.'">
		<input type="submit" name="submit" value="Aceptar">&nbsp;&nbsp;
		<input type="button" value="Volver al Listado" onClick=location.href="'.$rootpath.'/ponente/ponente.php?opc=2">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
