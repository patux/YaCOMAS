<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
 
$link=conectaBD();
$userQuery = 'SELECT id FROM asistente 
		WHERE login="'.$_GET['login'].'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar ID del asistente".mysql_errno($userRecords));
      if (mysql_num_rows($userRecords) == 0) {
	imprimeEncabezado();
	
	imprimeCajaTop('100','-- Error --');
      	print '<p class="yacomas_error">El login del asistente no existe, vuelve a intentarlo</p>';
	print '<center>';
	print '<input type="button" value="Volver" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=19">
	</center></form>';
	imprimeCajaBottom();
	exit;
      }
      else {
	$p = mysql_fetch_array($userRecords);
	$idasistente=$p['id'];
	}
mysql_free_result($userRecords);

$userQuery = 'SELECT nombrep FROM asistente 
		WHERE id="'.$idasistente.'"';
	$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
	$p = mysql_fetch_array($userRecords);
	$asistente_name=$p['nombrep'];

$fechaQueryE='SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

imprimeEncabezado();

print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
$msg="Listado de eventos<br><small>".$asistente_name." Estos son tus talleres registrados</small>";

imprimeCajaTop("100",$msg);

// Inicio datos de Talleres 
// Ordenadas por dia 
while ($Qf_evento = mysql_fetch_array($fechaRecords))
	{
		print '<center>';
		print '<H1>'.strftime_caste("%A %d de %B",strtotime($Qf_evento['fecha'])).'</H1>';
		if (!empty($Qf_evento['descr']))
			print '<H3> Dia de: '.$Qf_evento['descr'].'</H3>';
		print '</center>';
		// Comienzo de detalle de talleres registrados para este dia
		print '
			<table border=0 align=center width=100%>
			<tr>
			<td bgcolor='.$colortitle.'><b>Taller/Tutorial</b>
			</td></td><td bgcolor='.$colortitle.'><b>Orientacion</b>
			</td></td><td bgcolor='.$colortitle.'><b>Hora</b>
			</td></td><td bgcolor='.$colortitle.'><b>Lugar</b>
			</td><td bgcolor='.$colortitle.'>&nbsp;</td>
			</tr>';
		$QAins='SELECT 	E.id_propuesta, P.duracion, P.id_ponente, EO.id_lugar, 
				EO.id_fecha, E.id_propuesta, AI.id_evento, PO.nombrep, 
				PO.apellidos, P.nombre, O.descr, EO.hora, L.nombre_lug, 
				P.id_prop_tipo,PT.descr AS prop_tipo
			FROM 	ponente AS PO, 
				lugar AS L, 
				orientacion AS O, 
				inscribe AS AI, 
				evento AS E, 
				propuesta AS P, 
				prop_tipo AS PT,
				evento_ocupa AS EO  
			WHERE 	AI.id_evento=E.id AND 
				E.id_propuesta=P.id AND 
				AI.id_evento=EO.id_evento AND 
				P.id_orientacion=O.id AND 
				EO.id_lugar=L.id AND 
				P.id_ponente=PO.id AND 
				P.id_prop_tipo=PT.id AND
				EO.id_fecha="'.$Qf_evento['id'].'" AND 
				AI.id_asistente="'.$idasistente.'" 
			GROUP BY AI.id_evento ORDER BY EO.hora';

		$AIeventoRecords= mysql_query($QAins) or err("No se pudo listar eventos de esta fecha".mysql_errno($eventoRecords));
		$color=1;
		while ($Qf_event= mysql_fetch_array($AIeventoRecords))
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
			print '<td bgcolor='.$bgcolor.'>';
			print '<a class="azul" href="Vponencia.php?vopc='.$Qf_event['id_ponente'].' '.$Qf_event['id_propuesta'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombre"].'</a>';
			print '<small> ('.$Qf_event["prop_tipo"].')</small>';
			retorno();
			print '<small><a class="ponente" href="Vponente.php?vopc='.$Qf_event['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombrep"].' '.$Qf_event["apellidos"].'</a></small>';
			
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["descr"];
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["hora"].':00 - ';
			$hfin=$Qf_event["hora"]+$Qf_event["duracion"]-1;
			print $hfin.':50';
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["nombre_lug"];
print '</td><td bgcolor='.$bgcolor.'><small><a class="precaucion" href="Btaller.php?vact='.$Qf_event['id_evento'].' '.$idasistente.' '.$_SERVER['REQUEST_URI'].'">
			Dar de baja</a>';
			print '</td></tr>';
			
		}
		mysql_free_result($AIeventoRecords);
		print '</table>';	
	}
	mysql_free_result($fechaRecords);
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=19">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
