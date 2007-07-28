<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";

$link=conectaBD();

$fechaQueryE='SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

imprimeEncabezado();

$msg="Programa oficial de ponencias<br>$conference_name
	<br><small><small>*Programa sujeto a cambios*</small></small>";
imprimeCajaTop("100",$msg);
// Inicio datos de Ponencias
// Ordenadas por dia 
print '<p class="yacomas_msg">Para ver informacion adicional de la ponencia o del ponente haz click en cualquiera de ellos</a><hr>';
retorno();
print '<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
while ($Qf_evento = mysql_fetch_array($fechaRecords))
	{
		print '<center>';
		print '<H1>'.strftime_caste("%A %d de %B",strtotime($Qf_evento['fecha'])).'</H1>';
		if (!empty($Qf_evento['descr']))
			print '<H3> Dia de: '.$Qf_evento['descr'].'</H3>';
		print '</center>';
		// Comienzo de detalle de ponencias para este dia
		print '
			<table border=0 align=center width=100%>
			<tr>
			<td bgcolor='.$colortitle.'><b>Ponencia</b></td><td bgcolor='.$colortitle.'><b>Tipo</b>
			</td></td><td bgcolor='.$colortitle.'><b>Hora</b>
			</td></td><td bgcolor='.$colortitle.'><b>Lugar</b>
			</td></tr>';
		$Qehs= 'SELECT 	EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento, 
				E.id_propuesta, P.nombre, P.id_prop_tipo, PT.descr AS prop_tipo, EO.hora, 
				P.duracion, P.id_ponente, PO.nombrep, PO.apellidos, L.nombre_lug,PO.org,ES.descr 				 AS nestado 
			FROM 	evento AS E, 
				propuesta AS P, 
				evento_ocupa AS EO, 
				ponente AS PO, 
				lugar AS L,
				prop_tipo AS PT,
				estado AS ES
			WHERE 	E.id_propuesta=P.id AND 
				E.id=EO.id_evento AND 
				P.id_ponente=PO.id AND 
				EO.id_lugar=L.id AND 
				P.id_prop_tipo=PT.id AND
				PO.id_estado=ES.id AND
				EO.id_fecha="'.$Qf_evento['id'].'" 
			GROUP BY id_evento 
			ORDER BY EO.id_fecha,EO.hora';
		$eventoRecords= mysql_query($Qehs) or err("No se pudo listar eventos de esta fecha".mysql_errno($eventoRecords));
		$color=1;
		while ($Qf_event= mysql_fetch_array($eventoRecords))
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
		// El registro 101 de la tabla prop_tipo
		// Es un registro que agregaron los organizadores del FSL
		// Llamado Evento especial organizadores
		// El registro que eligieron 101 .. 
		// Puede ser otro.. si se desea
			if ($Qf_event['id_prop_tipo']==101){
				$bgcolor=$color_especiales;
				if ($color==1)
					$color==2;
				else
					$color=1;
				}
			print '<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$Qf_event['id_ponente'].' '.$Qf_event['id_propuesta'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombre"].'</a>';
			retorno();
			print '<small><a class="ponente" href="Vponente.php?vopc='.$Qf_event['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombrep"].' '.$Qf_event["apellidos"].'</a></small>';
			print '<br>'.$Qf_event["org"].'<br>'.$Qf_event["nestado"].'</small>';
			print '</td><td bgcolor='.$bgcolor.'>';
			print $Qf_event['prop_tipo'];
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["hora"].':00 - ';
			$hfin=$Qf_event["hora"]+$Qf_event["duracion"]-1;
			print $hfin.':50';
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["nombre_lug"];
			print '</td>';
			print '</tr>';
			
		}
		mysql_free_result($eventoRecords);
		print '</table>';	
	}
	mysql_free_result($fechaRecords);
	retorno();
	retorno();
imprimeCajaBottom();
imprimePie();
?>
