<? 
include "../includes/lib.php";
include "../includes/conf.inc";
beginSession('R');
imprimeEncabezado();
aplicaEstilo();

$tok = strtok ($vopc," ");
$idasistente=$tok;
$tok = strtok (" ");
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

$link=conectaBD();
$userQuery = 'SELECT * FROM asistente WHERE id="'.$idasistente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP='	SELECT 	AI.reg_time, 
			F.fecha, 
			N.descr AS nivel, 
			PO.nombrep, 
			PO.apellidos, 
			P.nombre AS taller, 
			O.descr AS orientacion, 
			EO.hora, 
			P.duracion, 
			L.nombre_lug 
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
$msg='Datos de asistente <br><small>-- '.$p['nombrep'].' '.$p['apellidos'].' --</small><hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);

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
		
		if ($p[sexo]=="M")
		    echo "Masculino";
		else
		    echo "Femenino";
		    
	print '
		</td>
		</tr>

		<tr>
		<td class="name">Organización: </td>
		<td class="resultado">
		'.stripslashes($p[org]).'
		</td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="resultado">';
		
		$query = 'SELECT * FROM estudios WHERE id="'.$p[id_estudios].'"';
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
		'.$p[ciudad].'
		</td>
		</tr>

		<tr>
		<td class="name">Estado: * </td>
		<td class="resultado">';
		
		$query= "select * from estado where id='".$p[id_estado]."'";
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
	<td bgcolor='.$colortitle.'><b>Fecha Inscripcion</b></td>
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
		$hfin=$fila["hora"]+$fila["duracion"];
		print $hfin.':00';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["nombre_lug"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["reg_time"];
		print '</td></tr>';
		
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
		<br><big><a class="boton" href="'.$regresa.'" onMouseOver="window.status=\'Volver\';return true" onFocus="window.status=\'Volver\';return true" onMouseOut="window.status=\'\';return true">[ Volver ]</a></big>
	</center>';
imprimeCajaBottom();
imprimePie();?>
