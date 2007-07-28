<? 
	include_once "../includes/lib.php";
	include_once "../includes/conf.inc.php";
	beginSession('P');
	imprimeEncabezado();
	aplicaEstilo();
  	$idponente=$_SESSION['YACOMASVARS']['ponid'];
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';

	$link=conectaBD();
	
	$tok = strtok ($_GET['vopc']," ");
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
	imprimeCajaTop("100",$verbo1." Propuesta de Ponencia");
	print '<hr>';

function imprime_valoresOk() {
	include "../includes/conf.inc.php";

    print '
     		<table width=100%>
		<tr>
		<td class="name">Nombre de Ponencia: * </td>
		<td class="resultado">
		'.$_POST['S_nombreponencia'].'
		</td>
		</tr>

		<tr>
		<td class="name">Nivel: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM prop_nivel WHERE id="'.$_POST['I_id_nivel'].'"';
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
		
		$query = 'SELECT * FROM prop_tipo WHERE id="'.$_POST['I_id_tipo'].'"';
		$result=mysql_query($query);
	 	while($fila=mysql_fetch_array($result)) {
			printf ("%s",$fila["descr"]);
  		}
		mysql_free_result($result);

	print '	
		</td>
		</tr>


		<tr>
		<td class="name">Orientaci&oacute;n: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM orientacion WHERE id="'.$_POST['I_id_orientacion'].'"';
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
		printf ("%02d Hrs",$_POST['I_duracion']);
	print '	
		</td>
		</tr>

		<tr>
		<td class="name">Resumen: </td>
		<td class="resultado">
		'.$_POST['S_resumen'].'
		</td>
		</tr>

		</table>
		<br>';

}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Aceptar") {
  # do some basic error checking
  // Si todo esta bien vamos a borrar el registro 
  	if ($action!=7){
  		$eliminar=0;
  		$query = "UPDATE propuesta SET id_status='$action' WHERE id="."'".$ponencia."' AND id_ponente='".$idponente."'"; 
  	}else{
		$eliminar=1;
		//Extraemos primero la ruta a eliminar 
		$rutaSQL="SELECT dirFile from propuesta where (id=".$ponencia.")";
		$rutaarchivo="";
		if ($resultaux= mysql_query($rutaSQL)){
			if ($row=mysql_fetch_row($resultaux)){
				$rutaarchivo=$row[0];
			}
		}
		$query = 'DELETE FROM propuesta WHERE id='.$ponencia.' AND id_ponente="'.$idponente.'"';
	}
		// Para debugear querys
		//print $query;
		//
		$result = mysql_query($query) or err("No se puede eliminar los datos".mysql_errno($result));
		if (($eliminar==1)&& ($rutaarchivo!="")){
			if (file_exists($rutaarchivo)){
//echo "Eliminado...";				
				unlink($rutaarchivo);
			}
		}
 	print '	Tu propuesta ha sido '.$verbo2.'
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/ponente/ponente.php?opc=2">
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
	'SELECT nombre, resumen, id_nivel, id_prop_tipo, duracion, id_status, id_orientacion FROM propuesta WHERE id="'.$ponencia.'" AND id_ponente="'.$idponente.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar la propuesta".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$_POST['S_nombreponencia']=$p['nombre'];
	$_POST['I_id_nivel']=$p['id_nivel'];
	$_POST['S_resumen']=$p['resumen'];
	$_POST['I_id_tipo']=$p['id_prop_tipo'];
	$_POST['I_duracion']=$p['duracion'];
	$_POST['I_id_orientacion']=$p['id_orientacion'];
}
	imprime_valoresOk();
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Aceptar">&nbsp;&nbsp;
		<input type="button" value="Volver al Listado" onClick=location.href="'.$fslpath.$rootpath.'/ponente/ponente.php?opc=2">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
