<? 
include "../includes/lib.php";
include "../includes/conf.inc";
beginSession('A');
$idasistente=$_SESSION['YACOMASVARS']['asiid'];

$link=conectaBD();
$userQuery = 'SELECT nombrep FROM asistente 
		WHERE id="'.$idasistente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$asistente_name=$p['nombrep'];
$msg="<br><small>".$asistente_name." Solo puedes tener ".$max_inscripcion." inscripciones a talleres talleres maximo</small>";

imprimeEncabezado();
aplicaEstilo();
print '<p class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['asilogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado/Inscripcion a talleres".$msg);


// Checamos si el registro a nuevos talleres esta abierto
$configQuery= 'SELECT status FROM config WHERE id=4';
$resultCQ=mysql_query($configQuery);
$CQfila=mysql_fetch_array($resultCQ);
$stat_array=$CQfila["status"];
mysql_free_result($resultCQ);
if ($stat_array==0) 
	{
		retorno();
		retorno();
		print '<p class="yacomas_error">La inscripcion a talleres se encuentra cerrada.. </p>';
		retorno();
		retorno();
		print ' <center>
		<input type="button" value="Continuar" onClick=location.href="'.$rootpath.'/asistente/menuasistente.php">
		</center>';
		retorno();
		exit;
	}	

if ($_POST['submit'] == "Inscribirme") {
  $errmsg = "";
// Checamos que los valores introducidos sean validos....

// Primero checamos si los valores elegidos solo son los requeridos 
	for ($talleres=0, $i=0; $i < $_POST[TalleresOfertados]; $i++) {
		if (!empty ($_POST[TallerElegido.$i]))
		{
			$campos=$_POST[TallerElegido.$i];
			// Separamos los campos para realizar la matriz
			$tok = strtok ($campos,",");
			$taller[$talleres][id_fecha]=$tok;
			$tok = strtok (",");
			$taller[$talleres][id_evento]=$tok;
			$tok = strtok (",");
			$taller[$talleres][hora]=$tok;
			$tok = strtok (",");
			$taller[$talleres][duracion]=$tok;
			$tok = strtok (",");
			$taller[$talleres][nombre]=$tok;
			$taller[$talleres][horafin]= $taller[$talleres][hora] + $taller[$talleres][duracion];
			$talleres++;
		}
			
	}
	if ($talleres > $_POST[InscripLibres]) {
		$errmsg="<li>Solo puede elegir $_POST[InscripLibres] talleres maximo";
	}
	if ($talleres < 1 ) {
		$errmsg="<li>Tienes que elegir al menos un taller para poder inscribirte";
	}
	// Si solo eligio los talleres suficientes consultamos cuales talleres 
	// ya esta inscrito y cuantas horas tiene ocupadas
	// El asistente, para comenzar a realizar la matriz de inscripcion
	if ($talleres > 0)
	{
		// Checamos que no existan cruces entre los talleres elegidos
		// Creamos los indices
		for ($r=0,$i=0; $i < $talleres; $i++) 
			for ($genera=$taller[$i][hora]; $genera <  $taller[$i][horafin]; $genera++,$r++)
			{
				$llave[$r]=$taller[$i][id_fecha].$genera;
				$nomt[$r]=$taller[$i][nombre];
			}

		if ($_POST[TalleresInscritos] > 0 ) 
		{
			// Realizamos la matriz de talleres inscritos
			for ($i=0; $i < $_POST[TalleresInscritos]; $i++) {
				$campos=$_POST[TallerInscrito.$i];
				// Separamos los campos para realizar la matriz
				$tok = strtok ($campos,",");
				$tallerI[$i][id_fecha]=$tok;
				$tok = strtok (",");
				$tallerI[$i][id_evento]=$tok;
				$tok = strtok (",");
				$tallerI[$i][hora]=$tok;
				$tok = strtok (",");
				$tallerI[$i][duracion]=$tok;
				$tok = strtok (",");
				$tallerI[$i][nombre]=$tok;
				$tallerI[$i][horafin]= $tallerI[$i][hora] + $tallerI[$i][duracion];
			}
			// Checamos que no existan cruces entre los talleres elegidos
			// y los talleres ya inscritos
			// Creamos los indices de los talleres inscritos
			for ($z=0; $z < $i; $z++) 
			{ 
				for ($genera=$tallerI[$z][hora]; $genera <  $tallerI[$z][horafin]; $genera++,$r++)
				{
					$llave[$r]=$tallerI[$z][id_fecha].$genera;
					$nomt[$r]=$tallerI[$z][nombre];
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
		for ($i=0; $i < $talleres;$i++)
		{
  			$queryI = 'INSERT INTO inscribe(id_asistente,id_evento,reg_time) 
			  		VALUES ('.
					$idasistente.','.
					$taller[$i][id_evento].','.
					$date.')';
			$resultI = mysql_query($queryI) 
				or err("No se puede insertar taller".mysql_errno($resultI));
			print '<tr><td class="resultado">'.
				$taller[$i][nombre].'</tr>';
			retorno();

		}
		print '</table><hr>';
		
	}
	// Checamos si Tallerelegido es igual a alguna de TallerOfertado
	// En caso correcto actualiza el status de el Taller elegido a 1 
}


$fechaQueryE='SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

// Sacamos el total de talleres inscritos del ponente
// Para evaluar si lo dejamos o no inscribirse a mas
$QAinscritos='SELECT count(*) FROM inscribe WHERE id_asistente="'.$idasistente.'"';
$insritosRecord= mysql_query($QAinscritos) or err("No se pudo listar inscritos evento".mysql_errno($inscritosRecord));
//print $QAinscritos;
$tot_reg=mysql_fetch_array($insritosRecord);
$Ainscritos=$tot_reg["count(*)"];
// Inicio datos de Talleres 
// Ordenadas por dia 
$inscrip_libres=$max_inscripcion-$Ainscritos;
$msg1='Actualmente estas inscrito en '.$Ainscritos.' talleres';
retorno();
$msg2='Puedes inscribirte a '.$inscrip_libres.' talleres mas';
print '<p class="yacomas_error">'.$msg1.'<br>'.$msg2.'</p>';

retorno();
print '<FORM method="POST" action="'.$REQUEST_URI.'">';
// Variable para los indizar los talleres
$taller=0;
$tallerI=0;
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
			<td bgcolor='.$colortitle.'><b>Taller</b>
			</td></td><td bgcolor='.$colortitle.'><b>Orientacion</b>
			</td></td><td bgcolor='.$colortitle.'><b>Hora</b>
			</td></td><td bgcolor='.$colortitle.'><b>Lugar</b>
			</td><td bgcolor='.$colortitle.'><b>Disp</b></td>
			</td><td bgcolor='.$colortitle.'><b>Inscrito</b></td>
			</tr>';
		$Qehs= 'SELECT 	EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento, 
				E.id_propuesta, P.nombre, O.descr, EO.hora, 
				P.duracion, P.id_ponente, PO.nombrep, PO.apellidos, 
				L.nombre_lug 
			FROM 	evento AS E, 
				propuesta AS P, 
				evento_ocupa AS EO, 
				ponente AS PO, 
				lugar AS L, 
				orientacion AS O 
			WHERE 	E.id_propuesta=P.id AND 
				E.id=EO.id_evento AND 
				P.id_ponente=PO.id AND 
				EO.id_lugar=L.id AND 
				P.id_orientacion=O.id AND 
				EO.id_fecha="'.$Qf_evento[id].'" 
				AND P.tpropuesta="T" 
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
			print '<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$Qf_event[id_ponente].' '.$Qf_event[id_propuesta].' '.$REQUEST_URI.'">'.$Qf_event["nombre"].'</a>';
			retorno();
			print '<small><a class="rojo" href="Vponente.php?vopc='.$Qf_event[id_ponente].' '.$REQUEST_URI.'">'.$Qf_event["nombrep"].' '.$Qf_event["apellidos"].'</a></small>';
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["descr"];
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["hora"].':00 - ';
			$hfin=$Qf_event["hora"]+$Qf_event["duracion"];
			print $hfin.':00';
			print '</td><td bgcolor='.$bgcolor.'>'.$Qf_event["nombre_lug"];
			// Checamos cuanta gente esta inscrita a este taller 
			// Para sacar el total de espacios disponibles todavia para el taller
			$Qinscritos='SELECT count(*) FROM inscribe WHERE id_evento="'.$Qf_event["id_evento"].'"';
			$insritosRecord= mysql_query($Qinscritos) or err("No se pudo listar inscritos evento".mysql_errno($inscritosRecord));
			//print $Qinscritos;
			$tot_reg=mysql_fetch_array($insritosRecord);
			$ins_taller=$tot_reg["count(*)"];
			$cup_disp=$Qf_event["cupo"] - $ins_taller;
			print '</td><td bgcolor='.$bgcolor.'>'.$cup_disp;	
			print '</td><td bgcolor='.$bgcolor.'>';
			// Checar si el asistente ya esta inscrito a esta ponencia
			$Qesta_inscrito='SELECT id_evento 
					 FROM inscribe 
					 WHERE id_asistente="'.$idasistente.'" AND
						id_evento="'.$Qf_event[id_evento].'"';
      			$inscritoRecords= mysql_query($Qesta_inscrito) or 
				err("No se pudo checar el login".mysql_errno($inscrito_recors));
			if (mysql_num_rows($inscritoRecords) !=0) 
			{
				print '<img src="'.$rootpath.'/images/checkmark.gif" border=0>';

				$Icampos=$Qf_event[id_fecha].',';
				$Icampos.=$Qf_event[id_evento].',';
				$Icampos.=$Qf_event[hora].',';
				$Icampos.=$Qf_event[duracion].',';
				$Icampos.=$Qf_event[nombre];
				print '<input type="hidden" name="TallerInscrito'.$tallerI.'" value="'.$Icampos.'">';
				$tallerI++;
			}
			elseif ($Ainscritos < $max_inscripcion)
			{
				// Checar si el asistente ya tiene MAXEVENT inscritos
				// Si los tiene ya no tiene que aparecer la opcion para inscribirse a mas
				// Checar si hay cupo
				if ($ins_taller < $Qf_event["cupo"])
				{
					$Tcampos=$Qf_event[id_fecha].',';
					$Tcampos.=$Qf_event[id_evento].',';
					$Tcampos.=$Qf_event[hora].',';
					$Tcampos.=$Qf_event[duracion].',';
					$Tcampos.=$Qf_event[nombre];
					print '<input type="checkbox" name="TallerElegido'.$taller.'" value="'.$Tcampos.'">';
					$taller++;
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
	if ($Ainscritos < $max_inscripcion)
	{
		retorno();
		retorno();
		print '<input type="hidden" name="InscripLibres" value="'.$inscrip_libres.'">&nbsp;&nbsp;';
		print '<input type="hidden" name="TalleresOfertados" value="'.$taller.'">&nbsp;&nbsp;';
		print '<input type="hidden" name="TalleresInscritos" value="'.$tallerI.'">&nbsp;&nbsp;';
		print '<input type="submit" name="submit" value="Inscribirme">&nbsp;&nbsp;';
	}
	print '<input type="button" value="Volver al menu" onClick=location.href="'.$rootpath.'/asistente/menuasistente.php">
	</center></form>';
imprimeCajaBottom();
imprimePie();
?>
