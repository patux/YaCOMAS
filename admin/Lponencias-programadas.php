<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
 

imprimeEncabezado();

//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados ni esten programados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP ='	SELECT 	P.id AS id_ponencia, P.nombre AS ponencia,PT.descr AS prop_tipo, 
			P.id_ponente, PO.nombrep, PO.apellidos, S.descr AS status, P.id_status,
			P.id_administrador,P.nombreFile
		FROM 	propuesta AS P, 
			ponente AS PO, 
			prop_status AS S, 
			prop_tipo AS PT
		WHERE 	P.id_ponente=PO.id AND 
			P.id_status=S.id AND 
			P.id_prop_tipo=PT.id AND
			id_status = 8 
		ORDER BY P.id_prop_tipo,P.id_ponente,P.reg_time';
		//ORDER BY P.id_ponente,P.reg_time';

$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecordsP));
print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de ponencias");
retorno();
// Inicio datos de Ponencias
print '
  	<table border=0 align=center width=100%>
  	<tr>
  	</td><td bgcolor='.$colortitle.'><b>Ponencia</b>
        </td><td bgcolor='.$colortitle.'><b>Tipo</b>
  	</td><td bgcolor='.$colortitle.'><b>Status</b></td>
        </td><td bgcolor='.$colortitle.'><b>Archivo</b></td>
  	</td><td bgcolor='.$colortitle.'><b>Asignado</b></td>
	</td><td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$fila['id_ponente'].' '.$fila['id_ponencia'].' '.$_SERVER['REQUEST_URI'].'">'.$fila["ponencia"].'</a>';
		print '<br><small><a class="ponente" href="Vponente.php?vopc='.$fila['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].' '.$fila['apellidos'].'</a></small>';
		
	
		print '</td><td bgcolor='.$bgcolor.'>';
		print '<small>'.$fila['prop_tipo'].'</small>';
		
		print '</td><td bgcolor='.$bgcolor.'>';
		print '<small>'.$fila['status'].'</small>';

		print '</td><td bgcolor='.$bgcolor.'>';
		if (! empty ($fila['nombreFile']))
                  print '<img src="'.$fslpath.$rootpath.'/images/checkmark.gif" border=0>';
                else
                  print '<small>No</small>';
	
		// Una vez que la ponencia fue programada (id 8)
		// La ponencia no se le puede modificar el status ni eliminar 
		// A menos que sea el administrador principal 
		if ($fila['id_administrador'] !=0) 
		{
			$userQueryA ='SELECT login FROM administrador WHERE id="'.$fila['id_administrador'].'"';
			$userRecordsA = mysql_query($userQueryA) or err("No se ver admin".mysql_errno($userRecordsA));
			$ponente=mysql_fetch_array($userRecordsA);
			print '</td><td bgcolor='.$bgcolor.'>'.$ponente['login'].'</td>';
			mysql_free_result($userRecordsA);
		}
		else 
			print '</td><td bgcolor='.$bgcolor.'>Ninguno</td>';
			
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
