<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	$idasistente=$_GET['idasistente'];
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Eliminar Asistente");
	print '<p class="yacomas_error">Esta accion eliminara el asistente y todas sus inscripciones a talleres</p>';
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk($idasistente) {
	include "../includes/conf.inc.php";

$link=conectaBD();
$userQuery = 'SELECT * FROM asistente WHERE id="'.$idasistente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP='	SELECT 	F.fecha, N.descr AS nivel, PO.nombrep, PO.apellidos, 
			P.nombre AS taller, O.descr AS orientacion, 
			EO.hora, P.duracion, L.nombre_lug 
		FROM 	fecha_evento AS F, 
			ponente AS PO, 
			lugar AS L, 
			orientacion AS O, 
			inscribe AS AI, 
			evento AS E, 
			propuesta AS P, 
			evento_ocupa AS EO, 
			prop_nivel AS N  
		WHERE 	EO.id_fecha=F.id AND 
			AI.id_evento=E.id AND 
			E.id_propuesta=P.id AND 
			AI.id_evento=EO.id_evento AND 
			P.id_orientacion=O.id AND 
			EO.id_lugar=L.id AND 
			P.id_ponente=PO.id AND 
			P.id_nivel=N.id AND 
			AI.id_asistente="'.$idasistente.'" 
		GROUP BY AI.id_evento 
		ORDER BY F.fecha, EO.hora';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar talleres del asistente".mysql_errno($userRecords));
// Inicio datos de Ponencias
    print '
     		<table width=100%>
		<tr>
		<td class="name">Correo Electrónico: *</td>
		<td class="resultado">
		'.$p["mail"].'
		</td>
		</tr>

		<tr>
		<td class="name">Sexo: * </td>
		<td class="resultado">';
		
		if ($p['sexo']=="M")
		    echo "Masculino";
		else
		    echo "Femenino";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Organización: </td>
		<td class="resultado">
		'.stripslashes($p['org']).'
		</td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM estudios WHERE id="'.$p['id_estudios'].'"';
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
		'.$p['ciudad'].'
		</td>
		</tr>

		<tr>
		<td class="name">Estado: * </td>
		<td class="resultado">';
		
		$query= "select * from estado where id='".$p['id_estado']."'";
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
		print $p['fecha_nac'];
	print '	
		</td>
		</tr>

		</table>
		<br>
		<hr>';
// Fin datos de usuario
// Inicio datos de Talleres inscritos 
print '<p class="yacomas_error">Talleres Inscritos</p>';
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Taller</b></td>
	<td bgcolor='.$colortitle.'><b>Orientacion</b></td>
	<td bgcolor='.$colortitle.'><b>Fecha</b></td>
	<td bgcolor='.$colortitle.'><b>Hora</b></td>
	<td bgcolor='.$colortitle.'><b>Lugar</b></td>
	</tr>';

	$color=1;
	while ($fila = mysql_fetch_array($userRecordsP))
	{
		if ($color==1) 
		{
			$bgcolor=$color_renglon1;
			$color=2;
		}
		else 
		{
			$bgcolor=$color_renglon2;
			$color=1;
		}
		print '<tr>';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["taller"];
		print '<br><small>'.$fila["nombrep"].' '.$fila["apellidos"].'</small>';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["orientacion"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["fecha"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["hora"].':00 - ';
		$hfin=$fila["hora"]+$fila["duracion"]-1;
		print $hfin.':50';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["nombre_lug"];
		print '</td></tr>';
	}
	print '</table>';
	retorno();
	retorno();
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset($_POST['submit']) && $_POST['submit'] == "Eliminar") {
  # do some basic error checking
  // Si todo esta bien vamos a borrar el registro 
  	$query1 = "DELETE FROM asistente WHERE id="."'".$idasistente."'";
	$query2=  "DELETE FROM inscribe WHERE id_asistente="."'".$idasistente."'";
		// Para debugear querys
		/* print $query1;
		retorno();
		print $query2;
		retorno();
		print $query3;
		retorno();
		*/
		//
		$result1 = mysql_query($query1) or err("No se puede eliminar los datos de asistente".mysql_errno($result1));
		$result2 = mysql_query($query2) or err("No se puede eliminar los datos de talleres inscritos del asistente".mysql_errno($result2));
 	print '	El asistente ha sido eliminado.<br>
		<p class="yacomas_msg">Los espacios que ocupaba en los talleres a los que estaba inscrito han sido liberados </p>
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=13">
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

	imprime_valoresOk($idasistente);
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Eliminar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=13">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
