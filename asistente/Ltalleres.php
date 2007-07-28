<? 
include_once "../includes/lib.php";
include_once "../includes/conf.inc.php";
beginSession('A');
$idasistente=$_SESSION['YACOMASVARS']['asiid'];

$link=conectaBD();
$userQuery = 'SELECT nombrep FROM asistente 
		WHERE id="'.$idasistente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$asistente_name=stripslashes($p['nombrep']);
$msg="<br><small>".stripslashes($asistente_name)." Solo puedes tener <br>".$max_inscripcionTA." inscripciones a talleres maximo y <br>".$max_inscripcionTU." inscripciones a tutoriales maximo</small>";

imprimeEncabezado();

print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['asilogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado/Inscripcion a talleres y tutoriales".$msg);


// Checamos si el registro a nuevos talleres esta abierto
$configQuery= 'SELECT status FROM config WHERE id='.LTALLERESREG;
$resultCQ=mysql_query($configQuery);
$CQfila=mysql_fetch_array($resultCQ);
$stat_array=$CQfila["status"];
mysql_free_result($resultCQ);
if ($stat_array==0) 
	{
		retorno();
		retorno();
		print '<p class="yacomas_error">La inscripci&oacute;n a talleres se encuentra cerrada.. </p>';
		retorno();
		retorno();
		print ' <center>
		<input type="button" value="Continuar" onClick=location.href="'.$fslpath.$rootpath.'/asistente/menuasistente.php">
		</center>';
		retorno();
		exit;
	}	

if (isset ($_POST['submit']) && $_POST['submit'] == "Inscribirme") {
  $errmsg = "";
  $errmsg2 = "";
// Checamos que los valores introducidos sean validos....

// Primero checamos si los valores elegidos solo son los requeridos 
	for ($talleres=$tutoriales=$eventos=0, $i=0; $i < $_POST['EventosOfertados']; $i++) {
		if (!empty ($_POST['EventoElegido'.$i]))
		{
			$campos=$_POST['EventoElegido'.$i];
			// Separamos los campos para realizar la matriz
			$tok = strtok ($campos,",");
			$evento[$eventos]['id_prop_tipo']=$tok;
			$tok = strtok (",");
			$evento[$eventos]['id_fecha']=$tok;
			$tok = strtok (",");
			$evento[$eventos]['id_evento']=$tok;
			$tok = strtok (",");
			$evento[$eventos]['hora']=$tok;
			$tok = strtok (",");
			$evento[$eventos]['duracion']=$tok;
			$tok = strtok (",");
			$evento[$eventos]['nombre']=$tok;
			$evento[$eventos]['horafin']= $evento[$eventos]['hora'] + $evento[$eventos]['duracion'];
			if ($evento[$eventos]['id_prop_tipo']==50)
				$talleres++;
			elseif ($evento[$eventos]['id_prop_tipo']==51)	
				$tutoriales++;
			$eventos++;
		}
			
	}
	if ($talleres > $_POST['InscripTALibres']) {
		$errmsg="<li>Solo puede elegir $_POST[InscripTALibres] talleres maximo";
	}
	if ($tutoriales > $_POST['InscripTULibres']) {
		$errmsg="<li>Solo puede elegir $_POST[InscripTULibres] tutoriales maximo";
	}
	if ($eventos < 1 ) {
		$errmsg="<li>Tienes que elegir al menos un taller o un tutorial para inscribir";
	}
	// Si solo eligio los talleres suficientes consultamos cuales talleres 
	// ya esta inscrito y cuantas horas tiene ocupadas
	// El asistente, para comenzar a realizar la matriz de inscripcion
	if ($eventos > 0)
	{
		// Checamos que no existan cruces entre los talleres elegidos
		// Creamos los indices
		for ($r=0,$i=0; $i < $eventos; $i++) 
			for ($genera=$evento[$i]['hora']; $genera <  $evento[$i]['horafin']; $genera++,$r++)
			{
				$llave[$r]=$evento[$i]['id_fecha'].$genera;
				$nomt[$r]=$evento[$i]['nombre'];
			}

		if ($_POST['EventosInscritos'] > 0 ) 
		{
			// Realizamos la matriz de talleres inscritos
			for ($i=0; $i < $_POST['EventosInscritos']; $i++) {
				$campos=$_POST['EventoInscrito'.$i];
				// Separamos los campos para realizar la matriz
				$tok = strtok ($campos,",");
				$eventoI[$i]['id_prop_tipo']=$tok;
				$tok = strtok (",");
				$eventoI[$i]['id_fecha']=$tok;
				$tok = strtok (",");
				$eventoI[$i]['id_evento']=$tok;
				$tok = strtok (",");
				$eventoI[$i]['hora']=$tok;
				$tok = strtok (",");
				$eventoI[$i]['duracion']=$tok;
				$tok = strtok (",");
				$eventoI[$i]['nombre']=$tok;
				$eventoI[$i]['horafin']= $eventoI[$i]['hora'] + $eventoI[$i]['duracion'];
			}
			// Checamos que no existan cruces entre los talleres elegidos
			// y los talleres ya inscritos
			// Creamos los indices de los talleres inscritos
			for ($z=0; $z < $i; $z++) 
			{ 
				for ($genera=$eventoI[$z]['hora']; $genera <  $eventoI[$z]['horafin']; $genera++,$r++)
				{
					$llave[$r]=$eventoI[$z]['id_fecha'].$genera;
					$nomt[$r]=$eventoI[$z]['nombre'];
				}
			}
		}
		// Comenzamos las comparaciones		
		$conflicto=0;
		for ($comparado=0,$i=0; $i < $r; $i++,$comparado++) 
		{
			for ($x=0; $x < $comparado; $x++) 
			{
				if ($llave[$i]==$horaocup[$x]) 
				{
					$conflicto=1;
					$errmsg2.='<br>
					<p class="yacomas_error">Los conflictos son entre: '.$nomt[$x].' y '.$nomt[$i].'</p>'; 
				}
			}
			if ($conflicto!=1)
				$horaocup[$comparado]=$llave[$i];
			else	
			{
				$errmsg='<li>Existen conflictos de cruce de horarios entre los talleres a los que intentas inscribirte y/o los talleres que tienes inscritos'.$errmsg2;
				break;
			}
		}
	}
	// Si existe un error lo mostramos	
	if (!empty($errmsg)) 
	{
	      showError($errmsg);
	}
	else 
	{
		print '<table width=100%><tr><td>';
		print '<p class="yacomas_msg">Te inscribiste a:</p>';
		$date=strftime("%Y%m%d%H%M%S");
		for ($i=0; $i < $eventos;$i++)
		{
  			$queryI = 'INSERT INTO inscribe(id_asistente,id_evento,reg_time) 
			  		VALUES ('.
					$idasistente.','.
					$evento[$i]['id_evento'].','.
					$date.')';
			$resultI = mysql_query($queryI) 
				or err("No se puede insertar taller".mysql_errno($resultI));
			print '<tr><td class="resultado">'.
				$evento[$i]['nombre'].'</tr>';
			retorno();

		}
		print '</table><hr>';
		
	}
	// Checamos si Tallerelegido es igual a alguna de TallerOfertado
	// En caso correcto actualiza el status de el Taller elegido a 1 
}


$fechaQueryE='SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

// Sacamos el total de talleres inscritos del asistente 
// Para evaluar si lo dejamos o no inscribirse a mas
$QTAinscritos='	SELECT 	count(*) 
		FROM	inscribe AS I,
        		evento AS E,
	    		propuesta AS P,
	        	prop_tipo AS PT
		WHERE	I.id_evento=E.id AND
			E.id_propuesta=P.id AND
			P.id_prop_tipo=PT.id AND
			P.id_prop_tipo=50 AND
			I.id_asistente="'.$idasistente.'"';
$inscritosTARecord= mysql_query($QTAinscritos) or err("No se pudo listar talleres inscritos evento".mysql_errno($inscritosTARecord));
//print $QTAinscritos;
$tot_TA_reg=mysql_fetch_array($inscritosTARecord);
$TAinscritos=$tot_TA_reg["count(*)"];
// Inicio datos de Talleres 
// Ordenadas por dia 
$inscrip_TA_libres=$max_inscripcionTA-$TAinscritos;
// Sacamos el total de tutoriales inscritos del ponente
// Para evaluar si lo dejamos o no inscribirse a mas
$QTUinscritos='	SELECT 	count(*) 
		FROM	inscribe AS I,
        		evento AS E,
	    		propuesta AS P,
	        	prop_tipo AS PT
		WHERE	I.id_evento=E.id AND
			E.id_propuesta=P.id AND
			P.id_prop_tipo=PT.id AND
			P.id_prop_tipo=51 AND
			I.id_asistente="'.$idasistente.'"';
$inscritosTURecord= mysql_query($QTUinscritos) or err("No se pudo listar talleres inscritos evento".mysql_errno($inscritosTURecord));
//print $QTUinscritos;
$tot_TU_reg=mysql_fetch_array($inscritosTURecord);
$TUinscritos=$tot_TU_reg["count(*)"];
// Inicio datos de Talleres 
// Ordenadas por dia 
$inscrip_TA_libres=$max_inscripcionTA-$TAinscritos;
$inscrip_TU_libres=$max_inscripcionTU-$TUinscritos;
$msg0='-- Recuerda cumplir los prerequisitos para ingresar a talleres y tutoriales -- <br>Para ver informaci&oacute;n sobre algun tema o ponente haz clik en el el nombre de taller o tutorial que sea de tu interes ';
$msg1='Actualmente estas inscrito en '.$TAinscritos.' talleres y '.$TUinscritos.' tutoriales';
retorno();
$msg1.='<br>Puedes inscribirte a '.$inscrip_TA_libres.' talleres y '.$inscrip_TU_libres.' tutoriales mas';
$msg2='El n&uacute;mero a la derecha del taller indica los lugares diponibles.<br>Si este n&uacute;mero es 0 indica cupo lleno, si este n&uacute;mero es negativo indica "sobrecupo".<br>En Ambos casos el checkbox para permitir la inscripci&oacute;n al mismo no aparecera';
print '<p class="yacomas_msg">'.$msg0.'<br></p>';
print '<p class="yacomas_error">'.$msg2.'<br><br></p>';
retorno();
print '<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
// Variable para los indizar los talleres
$evento=0;
$eventoI=0;
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
			<td bgcolor='.$colortitle.'><b>Taller/Tutorial</b>
			</td></td><td bgcolor='.$colortitle.'><b>Orientaci&oacute;n</b>
			</td></td><td bgcolor='.$colortitle.'><b>Hora</b>
			</td></td><td bgcolor='.$colortitle.'><b>Lugar</b>
			</td><td bgcolor='.$colortitle.'><b>Disp</b></td>
			</td><td bgcolor='.$colortitle.'><b>Inscrito</b></td>
			</tr>';
		$Qehs= 'SELECT 	EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento, 
				E.id_propuesta, P.nombre, O.descr, EO.hora, 
				P.duracion, P.id_ponente, PO.nombrep, PO.apellidos, 
				L.nombre_lug,PT.descr AS prop_tipo,P.id_prop_tipo 
			FROM 	evento AS E, 
				propuesta AS P, 
				evento_ocupa AS EO, 
				ponente AS PO, 
				lugar AS L, 
				prop_tipo AS PT,
				orientacion AS O 
			WHERE 	E.id_propuesta=P.id AND 
				E.id=EO.id_evento AND 
				P.id_ponente=PO.id AND 
				EO.id_lugar=L.id AND 
				P.id_orientacion=O.id AND 
				P.id_prop_tipo=PT.id AND
				EO.id_fecha="'.$Qf_evento['id'].'"  AND
				P.id_prop_tipo>=50 AND
				P.id_prop_tipo<100
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
			// Checamos cuanta gente esta inscrita a este taller/tutorial 
			// Para sacar el total de espacios disponibles todavia para el taller
			$Qinscritos='SELECT count(*) FROM inscribe WHERE id_evento="'.$Qf_event["id_evento"].'"';
			$inscritosEVRecord= mysql_query($Qinscritos) or err("No se pudo listar inscritos evento".mysql_errno($inscritosEVRecord));
			//print $Qinscritos;
			$tot_EV_reg=mysql_fetch_array($inscritosEVRecord);
			$ins_evento=$tot_EV_reg["count(*)"];
			$cup_disp=$Qf_event["cupo"] - $ins_evento;
			print '</td><td bgcolor='.$bgcolor.'>'.$cup_disp;	
			print '</td><td bgcolor='.$bgcolor.'>';
			// Checar si el asistente ya esta inscrito a esta ponencia
			$Qesta_inscrito='SELECT id_evento 
					 FROM inscribe 
					 WHERE id_asistente="'.$idasistente.'" AND
						id_evento="'.$Qf_event['id_evento'].'"';
      			$inscritoRecords= mysql_query($Qesta_inscrito) or 
				err("No se pudo checar el login".mysql_errno($inscrito_recors));
			if (mysql_num_rows($inscritoRecords) !=0) 
			{
				print '<img src="'.$fslpath.$rootpath.'/images/checkmark.gif" border=0>';

				$Icampos=$Qf_event['id_prop_tipo'].',';
				$Icampos.=$Qf_event['id_fecha'].',';
				$Icampos.=$Qf_event['id_evento'].',';
				$Icampos.=$Qf_event['hora'].',';
				$Icampos.=$Qf_event['duracion'].',';
				$Icampos.=$Qf_event['nombre'];
				print '<input type="hidden" name="EventoInscrito'.$eventoI.'" value="'.$Icampos.'">';
				$eventoI++;
			}
			elseif (($Qf_event["id_prop_tipo"]==50) && ($TAinscritos < $max_inscripcionTA))
			{
				// Checar si el asistente ya tiene MAXEVENT_TA inscritos
				// Si los tiene ya no tiene que aparecer la opcion para inscribirse a mas
				// Checar si hay cupo
				// Cambiar la line orignal por esta
				// Esto fue para que solo se inscriba la gente que no tiene inscritos ningun taller
				//if ($ins_evento < $Qf_event["cupo"] && $TAinscritos == 0 && $TUinscritos ==0 )
				if ($ins_evento < $Qf_event["cupo"]) 
				{
					$Tcampos=$Qf_event['id_prop_tipo'].',';
					$Tcampos.=$Qf_event['id_fecha'].',';
					$Tcampos.=$Qf_event['id_evento'].',';
					$Tcampos.=$Qf_event['hora'].',';
					$Tcampos.=$Qf_event['duracion'].',';
					$Tcampos.=$Qf_event['nombre'];
					print '<input type="checkbox" name="EventoElegido'.$evento.'" value="'.$Tcampos.'">';
					$evento++;
				}
			}
			elseif (($Qf_event["id_prop_tipo"]==51) && ($TUinscritos < $max_inscripcionTU))
			{
				// Checar si el asistente ya tiene MAXEVENT_TU inscritos
				// Si los tiene ya no tiene que aparecer la opcion para inscribirse a mas
				// Checar si hay cupo
				if ($ins_evento < $Qf_event["cupo"])
				{
					$Tcampos=$Qf_event['id_prop_tipo'].',';
					$Tcampos.=$Qf_event['id_fecha'].',';
					$Tcampos.=$Qf_event['id_evento'].',';
					$Tcampos.=$Qf_event['hora'].',';
					$Tcampos.=$Qf_event['duracion'].',';
					$Tcampos.=$Qf_event['nombre'];
					print '<input type="checkbox" name="EventoElegido'.$evento.'" value="'.$Tcampos.'">';
					$evento++;
				}
			}
			print '</td></tr>';
			
		}
		mysql_free_result($eventoRecords);
		print '</table>';	
	}
	mysql_free_result($fechaRecords);
	retorno();
	retorno();
	print '<center>';
	if (($TAinscritos < $max_inscripcionTA) || ($TUinscritos < $max_inscripcionTU))
	{
		retorno();
		retorno();
		print '<input type="hidden" name="InscripTALibres" value="'.$inscrip_TA_libres.'">&nbsp;&nbsp;';
		print '<input type="hidden" name="InscripTULibres" value="'.$inscrip_TU_libres.'">&nbsp;&nbsp;';
		print '<input type="hidden" name="EventosOfertados" value="'.$evento.'">&nbsp;&nbsp;';
		print '<input type="hidden" name="EventosInscritos" value="'.$eventoI.'">&nbsp;&nbsp;';
		print '<input type="submit" name="submit" value="Inscribirme">&nbsp;&nbsp;';
	}
	print '<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/asistente/menuasistente.php">
	</center></form>';
imprimeCajaBottom();
imprimePie();
?>
