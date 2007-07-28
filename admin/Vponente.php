<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();


$tok = strtok ($_GET['vopc']," ");
$idponente=$tok;
$tok = strtok (" ");
$regresa='';
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

$link=conectaBD();
$userQuery = 'SELECT * FROM ponente WHERE id="'.$idponente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el ponente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = '	SELECT 	* 
		FROM 	propuesta 
		WHERE 	id_ponente="'.$idponente.'" AND 
			id_status!=7';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));
$msg='Datos de ponente <br><small>-- '.$p['nombrep'].' '.$p['apellidos'].' --</small><hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);

// Inicio datos de Ponencias
    print '
     		<table width=100%>
		<tr>
		<td class="name">Correo Electr&oacute;nico: *</td>
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
		<td class="name">Organizaci&oacute;n: </td>
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
		<td class="name">Titulo: * </td>
		<td class="resultado">
		'.stripslashes($p['titulo']).'
		</td>
		</tr>

		<tr>
		<td class="name">Domicilio: </td>
		<td class="resultado">
		'.$p['domicilio'].'
		</td>
		</tr>

		<tr>
		<td class="name">Telefono: </td>
		<td class="resultado">
		'.chunk_split ($p['telefono'], 2).'
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

		<tr>
		<td class="name">Resumen Curricular: </td>
		<td align=justify class="resultado">
		'.$p['resume'].'
		</td>
		</tr>

		</table>
		<br>
		<hr>';
// Fin datos de usuario
// Inicio datos de Ponencias
print '<p class="yacomas_error">Ponencias registradas</p>';
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Tipo</b></td>
	<td bgcolor='.$colortitle.'><b>Status</b></td>
	<td bgcolor='.$colortitle.'><b>Archivo</b></td>
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
		print '<tr>
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$idponente.' '.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila["nombre"].'</a>';
	
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_tipo WHERE id="'.$fila['id_prop_tipo'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print $fstatus['descr'];
		mysql_free_result($result);
		
		print '</td><td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM prop_status WHERE id="'.$fila['id_status'].'"';
		$result=mysql_query($query);
	 	$fstatus=mysql_fetch_array($result);
		print $fstatus['descr'];
		mysql_free_result($result);

		print '</td><td bgcolor='.$bgcolor.'>';
		if (! empty ($fila['nombreFile']))
                  print '<img src="'.$fslpath.$rootpath.'/images/checkmark.gif" border=0>';
                else
                  print '<small>No</small>
        </td></tr>';
		
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
		<br><big><a class="boton" href="'.$regresa.'" onMouseOver="window.status=\'Volver\';return true" onFocus="window.status=\'Volver\';return true" onMouseOut="window.status=\'\';return true">[ Volver ]</a></big>
	</center>';
imprimeCajaBottom();
imprimePie();?>
