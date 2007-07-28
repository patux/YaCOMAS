<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Control de Asistencia");
retorno();
retorno();
$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryA = 'SELECT 	A.id, A.nombrep, A.apellidos, 
			A.reg_time,  E.descr AS estado,  
			ES.descr AS estudios, TA.descr AS tasistente,A.asistencia 
		FROM 	asistente AS A, estado AS E, 
			estudios AS ES,tasistente AS TA 
		WHERE 	A.id_estado=E.id AND 
			A.id_estudios=ES.id AND
			A.id_tasistente=TA.id AND
			A.apellidos like "'.$_GET['apellidos'].'%"
		ORDER BY A.id,A.reg_time';
$userRecordsA = mysql_query($userQueryA) or err("No se pudo listar Asistentes".mysql_errno($userRecordsA));
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Estado</b></td>
	<td bgcolor='.$colortitle.'><b>Estudios</b></td>
	<td bgcolor='.$colortitle.'><b>Tipo Asistente</b></td>
	<td bgcolor='.$colortitle.'><b>Asistio</b></td>
	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>';
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vasistente.php?vopc='.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['apellidos'].' '.$fila['nombrep'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['estudios'];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['tasistente'];
		print '</td><td bgcolor='.$bgcolor.' align=center>';
		if ($fila['asistencia']==1)
			print '<img src="'.$fslpath.$rootpath.'/images/checkmark.gif" border=0>';
		else 
			print  'No';
		print '</td>';
		if ($_SESSION['YACOMASVARS']['rootlevel']==1)
			print '<td bgcolor='.$bgcolor.'><a class="precaucion" href="act_asistencia.php?casistencia='.$fila['id'].' '.$fila['asistencia'].'">Asistencia</td>';
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Buscar mas" onClick=location.href="'.$fslpath.$rootpath.'/admin/admin?opc=15">
	<input type="button" value="Volver a menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#eventos">
	</center>';
	retorno();
	retorno();
imprimeCajaBottom();
imprimePie();?>
