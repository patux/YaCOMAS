<? 
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	beginSession('R');
	imprimeEncabezado();
	
	print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
	imprimeCajaTop("100","Eliminar Fecha");
	print '<p class="yacomas_error">Esta accion eliminara la fecha del programa de eventos y todos los eventos registrados en esta fecha<br>
	Los eventos regresaran a status de Ponencia Aceptada, las inscripciones de los asistentes al evento seran eliminadas 
	</p>';
	print '<hr>';
	$link=conectaBD();

function imprime_valoresOk($idfecha, $regresa) {
	include "../includes/conf.inc.php";

$link=conectaBD();

$fechaQueryE='SELECT * FROM fecha_evento WHERE id='.$_GET['idfecha'];
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

// Inicio datos de Ponencias
// Ordenadas por dia 
while ($Qf_evento = mysql_fetch_array($fechaRecords))
	{
		print '<center>';
		print '<H1>'.$Qf_evento['fecha'].'</H1>';
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
			</tr>';
		$Qehs= 'SELECT 	EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento, 
				E.id_propuesta, P.nombre, P.id_prop_tipo, PT.descr AS prop_tipo, 
				EO.hora, P.duracion, P.id_ponente, PO.nombrep, 
				PO.apellidos, L.nombre_lug 
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
			GROUP BY id_evento ORDER BY EO.id_fecha,EO.hora';
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
			print '<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$Qf_event['id_ponente'].' '.$Qf_event['id_propuesta'].' '.$regresa.'">'.$Qf_event["nombre"].'</a>';
			retorno();
			print '<small><a class="ponente" href="Vponente.php?vopc='.$Qf_event['id_ponente'].' '.$regresa.'">'.$Qf_event["nombrep"].' '.$Qf_event["apellidos"].'</a></small>';
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
				print '<small><a class="verde" href="Lasistentes-reg.php?vopc='.$Qf_event['id_evento'].' '.$regresa.'" onMouseOver="window.status=\'Asistentes registrados\';return true" onFocus="window.status=\'Asistentes registrados\';return true" onMouseOut="window.status=\'\';return true" >Asistentes</a>';
			}
			else 
				print '</td><td bgcolor='.$bgcolor.'>';
				
			print '</td></tr>';
			
		}
		mysql_free_result($eventoRecords);
		print '</table>';	
	}
	mysql_free_result($fechaRecords);
	retorno();
	retorno();
}
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (isset ($_POST['submit']) && $_POST['submit'] == "Eliminar") {
  # do some basic error checking
  // Si todos esta bien vamos a borrar el registro 

  	// Seleccionamos los eventos que estan registrados en este lugar
  	$QB_selectEL='SELECT id_evento FROM evento_ocupa WHERE id_fecha='.$_GET['idfecha'].' GROUP BY id_evento';
	// Debug
	// print $QB_selectEL;
	// retorno();

	$result_SEL=  mysql_query($QB_selectEL) or err("No se puede seleccionar los evento_ocupa del evento".mysql_errno($result_SEL));
	$num_event=0;
	while ($fila = mysql_fetch_array($result_SEL)) 
	{
		$evento[$num_event]['id_evento']=$fila['id_evento'];
		$num_event++;
	}
	mysql_free_result($result_SEL);
	
	// Borra asisgnaciones de evento en lugar 
	$QB_evento_ocupa=  "DELETE FROM evento_ocupa WHERE id_fecha="."'".$_GET['idfecha']."'";
	// Debug
	// print $QB_evento_ocupa;
	// retorno();
	$result_BEO= mysql_query($QB_evento_ocupa) or err("No se puede eliminar las inscripciones al eventos de este lugar".mysql_errno($result_BEO));

	for ($i=0; $i < $num_event; $i++ )
	{
  		// Seleccionamos las propuestas a las que se refieren los eventos 
  		$query_selectP='SELECT id_propuesta FROM evento WHERE id='.$evento[$i]['id_evento'];
		$result_SP=  mysql_query($query_selectP) or err("No se pueden seleccionar las propuestas relacionadas al evento para actualizar".mysql_errno($result_SP));
      		$registrosSP=mysql_num_rows($result_SP);
		$prop= mysql_fetch_array($result_SP);
  		// Actualizamos el status de la propuesta a Aceptada 
  		$query_actP='UPDATE propuesta SET id_status=5 WHERE id='.$prop['id_propuesta'];
		$result_AP=  mysql_query($query_actP) or err("No se puede actualizar el status de la propuesta ".mysql_errno($result_AP));
		// Borra evento
		$QB_evento =  "DELETE FROM evento WHERE id="."'".$evento[$i]['id_evento']."'";
		$result_BE=  mysql_query($QB_evento) or err("No se puede eliminar evento ".mysql_errno($result_BE));
		// Borra inscripcion a el evento 
		$QB_inscribe =  "DELETE FROM inscribe WHERE id_evento="."'".$evento[$i]['id_evento']."'";
		$result_BI=  mysql_query($QB_inscribe) or err("No se puede eliminar inscripcion al evento".mysql_errno($result_BI));
		// Debug
	/*	print $query_selectP;
		retorno();
		print $query_actP;
		retorno();
		print $QB_evento;
		retorno();
		print $QB_inscribe;
		retorno();
		print $QB_hinscripcion;
		retorno();
	*/
      	if ($registrosSP != 0) 
		mysql_free_result($result_SP);
	}
	// Finalmente la fecha 
  	$QB_lugar= "DELETE FROM fecha_evento WHERE id="."'".$_GET['idfecha']."'";
	$result_BL=  mysql_query($QB_lugar) or err("No se puede eliminar fecha".mysql_errno($result_BL));
	
 	print '	La fecha ha sido eliminada del programa.<br>
		<p class="yacomas_msg">
		Los espacios que ocupaban en los talleres los asistentes que estaban inscritos han sido liberados 
		Las ponencias registradas han sido cambiado en status de Aceptadas en espera de nueva asignacion de lugar y fecha
		para que los asistentes puedan inscribirse a ella
		</p>
 		<p>
		 Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
		 <a href="mailto:'.$adminmail.'">Administraci&oacute;n '.$conference_name.'</a><br><br>
		 <center>
		 <input type="button" value="Volver a listado" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=12">
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

	imprime_valoresOk($_GET['idfecha'],$_SERVER['REQUEST_URI']);
	print'<center>
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="submit" name="submit" value="Eliminar">&nbsp;&nbsp;
		<input type="button" value="Cancelar" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin.php?opc=12">
		</center>
		</form>';

imprimeCajaBottom(); 
imprimePie(); 
?>
