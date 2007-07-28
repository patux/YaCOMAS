<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
imprimeEncabezado();


$tok = strtok ($_GET['vopc']," ");
$idevento=$tok;
$tok = strtok (" ");
$regresa='';
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

$link=conectaBD();
$tallerQuery='
	SELECT P.nombre AS taller,P.duracion, EO.hora, F.fecha, L.nombre_lug AS lugar 
	FROM lugar AS L, fecha_evento AS F, evento_ocupa AS EO, evento AS E, propuesta as P 
	WHERE 
	E.id_propuesta=P.id AND 
	E.id=EO.id_evento AND 
	EO.id_fecha=F.id AND 
	EO.id_lugar=L.id AND 
	E.id='.$idevento.' 
	ORDER BY F.fecha, EO.hora ASC LIMIT 1';

$taller_record= mysql_query($tallerQuery) or err("No se pudo listar Asistentes".mysql_errno($taller_record));
$filaT= mysql_fetch_array($taller_record);
$hfin=$filaT["hora"]+$filaT["duracion"]-1;
$msg="Taller: ".$filaT['taller']."<br><small>
".strftime_caste("%A %d de %B",strtotime($filaT['fecha']))."<br><small>".$filaT['hora'].":00 - ".$hfin.":50<br>Lugar: ".$filaT['lugar']."</small>";
mysql_free_result($taller_record);
$userQueryA =' 
 	SELECT 	I.id_asistente, I.reg_time, A.nombrep, A.apellidos, E.descr as estado, ES.descr AS estudios
 	FROM 	inscribe AS I, evento_ocupa AS EO, asistente AS A, estado AS E, estudios AS ES
 	WHERE
 		I.id_evento=EO.id_evento AND
 		I.id_asistente=A.id AND
 		A.id_estado=E.id AND
 		A.id_estudios=ES.id AND
 		I.id_evento='.$idevento.'
 	GROUP BY
 		I.id_asistente
 	ORDER BY I.reg_time, I.id_asistente';
$userRecordsA = mysql_query($userQueryA) or err("No se pudo listar Asistentes".mysql_errno($userRecordsA));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Ciudad</b></td>
	<td bgcolor='.$colortitle.'><b>Estudios</b></td>
	<td bgcolor='.$colortitle.'><b>Registro</b></td>';
	if ($_SESSION['YACOMASVARS']['rootlevel']==1)
		print '	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>';
	print '
	</tr>';
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsA))
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vasistente.php?vopc='.$fila['id_asistente'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].' '.$fila['apellidos'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['estudios'];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['reg_time'].'</td>';
		if ($_SESSION['YACOMASVARS']['rootlevel']==1)
			print '<td bgcolor='.$bgcolor.'><a class="precaucion" href="Basistente.php?idasistente='.$fila['id_asistente'].'">Eliminar</td>';
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
		<br><big><a class="boton" href="'.$regresa.'" onMouseOver="window.status=\'Volver\';return true" onFocus="window.status=\'Volver\';return true" onMouseOut="window.status=\'\';return true">[ Volver ]</a></big>
	</center>';
imprimeCajaBottom();
imprimePie();?>
