<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$idponente=$_SESSION['YACOMASVARS']['rootid'];

$link=conectaBD();

$fechaQueryE='SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

imprimeEncabezado();

print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
$msg="Listado de eventos<br>";

imprimeCajaTop("100",$msg);
// Inicio datos de Ponencias
// Ordenadas por dia 
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
			</td><td bgcolor='.$colortitle.'><b>Disp</b></td>
			</td><td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
			</td><td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
			</tr>';
		$Qehs= 'SELECT 	EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento, 
				E.id_propuesta, P.nombre, P.id_prop_tipo, PT.descr AS prop_tipo, EO.hora, 
				P.duracion, P.id_ponente, PO.nombrep, PO.apellidos, L.nombre_lug 
			FROM 	evento AS E, 
				propuesta AS P, 
				evento_ocupa AS EO, 
				ponente AS PO, 
				lugar AS L,
				prop_tipo AS PT
			WHERE 	E.id_propuesta=P.id AND 
				E.id=EO.id_evento AND 
				P.id_ponente=PO.id AND 
				EO.id_lugar=L.id AND 
				P.id_prop_tipo=PT.id AND
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
			print '</td><td bgcolor='.$bgcolor.'>';
			print $Qf_event['prop_tipo'];
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["hora"].':00 - ';
			$hfin=$Qf_event["hora"]+$Qf_event["duracion"]-1;
			print $hfin.':50';
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["nombre_lug"];
			print '</td><td bgcolor='.$bgcolor.'>';
			if ($Qf_event['id_prop_tipo']>=50 && $Qf_event['id_prop_tipo'] < 100)
			{
				// Checamos cuanta gente esta inscrita a este taller 
				// Para sacar el total de espacios disponibles todavia para el taller
				$Qinscritos='SELECT count(*) FROM inscribe WHERE id_evento="'.$Qf_event["id_evento"].'"';
				$insritosRecord= mysql_query($Qinscritos) or err("No se pudo listar inscritos evento".mysql_errno($inscritosRecord));
				//print $Qinscritos;
				$tot_reg=mysql_fetch_array($insritosRecord);
				$ins_taller=$tot_reg["count(*)"];
				$cup_disp=$Qf_event["cupo"] - $ins_taller;
				print $cup_disp;	
				print '</td><td bgcolor='.$bgcolor.'>';
				print '<small><a class="verde" href="Lasistentes-reg.php?vopc='.$Qf_event['id_evento'].' '.$_SERVER['REQUEST_URI'].'" onMouseOver="window.status=\'Asistentes registrados\';return true" onFocus="window.status=\'Asistentes registrados\';return true" onMouseOut="window.status=\'\';return true" >Asistentes</a>';
			}
			else 
				print '</td><td bgcolor='.$bgcolor.'>';
				
			if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) 
				print '</td><td bgcolor='.$bgcolor.'><small><a class="precaucion" href="Cevento.php?vact='.$Qf_event['id_ponente'].' '.$Qf_event['id_propuesta'].'" onMouseOver="window.status=\'Cancelar evento\';return true" onFocus="window.status=\'Cancelar evento\';return true" onMouseOut="window.status=\'\';return true">Cancelar</a>';
			print '</td></tr>';
			
		}
		mysql_free_result($eventoRecords);
		print '</table>';	
	}
	mysql_free_result($fechaRecords);
	retorno();
	retorno();
	print '<center>';
	print '<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#eventos">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
