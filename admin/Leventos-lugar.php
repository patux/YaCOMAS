<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$idlugar=$_GET['idlugar'];
$idponente=$_SESSION['YACOMASVARS']['rootid'];

$link=conectaBD();
$lugarQueryE='SELECT * FROM lugar WHERE id='.$idlugar;
$lugarRecords = mysql_query($lugarQueryE) or err("No se pudo listar lugar de eventos ".mysql_errno($lugarRecords));

imprimeEncabezado();

print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
$msg="Listado de eventos por lugar<br>";

imprimeCajaTop("100",$msg);
// Inicio datos de Ponencias
// Ordenadas por dia 
print '<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
while ($Ql_evento = mysql_fetch_array($lugarRecords))
	{
		print '<center>';
		print '<H1>'.$Ql_evento['nombre_lug'].'</H1>';
		if (!empty($Ql_evento['ubicacion']))
			print '<H3>Ubicacion: '.$Ql_evento['ubicacion'].'</H3>';
		if ($Ql_evento['cupo'] > $limite) 
			print '<H2> Salon para Conferencias</H2>';
		else
			print '<H2> Aula para Talleres y/o Tutoriales</H2>';
		print '</center>';
		// Comienzo de detalle de ponencias para este dia
		print '
			<table border=0 align=center width=100%>
			<tr>
			<td bgcolor='.$colortitle.'><b>Ponencia</b></td>
			</td></td><td bgcolor='.$colortitle.'><b>Fecha</b>
			</td></td><td bgcolor='.$colortitle.'><b>Hora</b>
			</td><td bgcolor='.$colortitle.'><b>Disp</b></td>
			</td><td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
			</tr>';
		$Qehs='
			SELECT  E.id_propuesta, P.id_ponente, P.duracion, L.cupo, EO.id_evento, 
				EO.hora, F.fecha, P.nombre, PO.nombrep, PO.apellidos,P.id_prop_tipo,PT.descr AS prop_tipo
			FROM 	ponente AS PO, propuesta AS P, prop_tipo AS PT, 
				evento AS E, lugar AS L, evento_ocupa AS EO,fecha_evento AS F  
			WHERE 
				EO.id_lugar=L.id AND 
				EO.id_fecha=F.id AND 
				EO.id_evento=E.id AND 
				E.id_propuesta=P.id AND 
				P.id_ponente=PO.id AND
				P.id_prop_tipo=PT.id AND 
				L.id='.$idlugar.'
				GROUP BY id_evento 
				ORDER BY F.fecha,EO.hora'; 
		$eventoRecords= mysql_query($Qehs) or err("No se pudo listar eventos de este lugar".mysql_errno($eventoRecords));
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
			print '<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$Qf_event['id_ponente'].' '.$Qf_event['id_propuesta'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombre"].'</a>';
			retorno();
			print '<small><a class="ponente" href="Vponente.php?vopc='.$Qf_event['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$Qf_event["nombrep"].' '.$Qf_event["apellidos"].'</a></small>';
			print '</td><td bgcolor='.$bgcolor.'>'.strftime_caste("%A %d de %B",strtotime($Qf_event['fecha']));
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["hora"].':00 - ';
			$hfin=$Qf_event["hora"]+$Qf_event["duracion"]-1;
			print $hfin.':50';
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
				
			print '</td></tr>';
			
		}
		mysql_free_result($eventoRecords);
		print '</table>';	
	}
	mysql_free_result($lugarRecords);
	retorno();
	retorno();
	print '<center>';
	print '<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=5">
	</center>';
imprimeCajaBottom();
imprimePie();
?>
