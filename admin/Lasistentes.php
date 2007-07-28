<? 
include "../includes/lib.php";
include "../includes/conf.inc";
beginSession('R');
imprimeEncabezado();
aplicaEstilo();
$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryA = 'SELECT A.id, A.nombrep, A.apellidos, A.reg_time,  E.descr AS estado,  ES.descr AS estudios FROM asistente AS A, estado AS E, estudios AS ES WHERE A.id_estado=E.id AND A.id_estudios=ES.id ORDER BY E.estado,A.id';
$userRecordsA = mysql_query($userQueryA) or err("No se pudo listar Asistentes".mysql_errno($userRecordsA));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de Asistentes registrados");
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vasistente.php?vopc='.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].' '.$fila['apellidos'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['estudios'];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['reg_time'].'</td>';
		if ($_SESSION['YACOMASVARS']['rootlevel']==1)
			print '<td bgcolor='.$bgcolor.'><a class="rojo" href="Basistente.php?idasistente='.$fila['id'].'">Eliminar</td>';
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$rootpath.'/admin/menuadmin.php#ponencias">
	</center>';
imprimeCajaBottom();
imprimePie();?>
