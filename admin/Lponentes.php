<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryCP = 'SELECT 	count(*) AS totPonentes FROM ponente'; 
$userRecordsCP = mysql_query($userQueryCP) or err("No se pudo listar Total de Ponentes".mysql_errno($userRecordsCP));
$fila1=mysql_fetch_array ($userRecordsCP);
$I_totPonentes=$fila1['totPonentes'];
mysql_free_result ($userRecordsCP);
$userQueryP = '	SELECT 	P.id, P.login, P.nombrep, P.apellidos, P.reg_time,  E.descr AS estado,  
			ES.descr AS estudios 
		FROM 	ponente AS P, 
			estado AS E, 
			estudios AS ES 
		WHERE 	P.id_estado=E.id AND 
			P.id_estudios=ES.id 
		ORDER BY P.id,P.reg_time';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar Ponentes".mysql_errno($userRecordsP));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de ponentes registrados");
print '<b>'.$I_totPonentes.' Ponentes Registrados </b>';
retorno();
retorno();
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Login</b></td>
	<td bgcolor='.$colortitle.'><b>Estado</b></td>
	<td bgcolor='.$colortitle.'><b>Estudios</b></td>
	<td bgcolor='.$colortitle.'><b>Registro</b></td>';
	if ($_SESSION['YACOMASVARS']['rootlevel']==1)
		print '	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>';
	print '
	</tr>';
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsP))
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponente.php?vopc='.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].' '.$fila['apellidos'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['login'].'
		<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['estudios'];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['reg_time'].'</td>';
		if ($_SESSION['YACOMASVARS']['rootlevel']==1)
			print '<td bgcolor='.$bgcolor.'><a class="precaucion" href="Bponente.php?idponente='.$fila['id'].'">Eliminar</td>';
		print '</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
	</center>';
imprimeCajaBottom();
imprimePie();?>
