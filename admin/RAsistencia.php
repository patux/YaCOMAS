<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';

imprimeCajaTop("100","Control de Asistencia");
	print	'
		<FORM method="POST" action="'.$_SERVER['REQUEST_URI'].'">
		<table width=100%>
		<tr>
		<td class="name">Apellidos: </td>
		<td class="input">
		<input TYPE="text" name="apellidos" size="30">
		&nbsp;&nbsp;
		<input type="submit" name="submit" value="Buscar">&nbsp;&nbsp;
		</td>
		<td>Vacio para listar todos los asistentes 
		</td>
		</tr>
		</table>
		<br>
		<br>
		<br>
		</form>';
function busca($patron){
include "../includes/conf.inc.php";
$userQueryA = 'SELECT 	A.id, A.login,A.nombrep, A.apellidos, 
			A.reg_time,  E.descr AS estado,  
			TA.descr AS tasistente,A.asistencia 
		FROM 	asistente AS A, estado AS E, 
			tasistente AS TA 
		WHERE 	A.id_estado=E.id AND 
			A.id_tasistente=TA.id AND
			A.apellidos like "'.$patron.'%"
		ORDER BY A.id,A.reg_time';
$userRecordsA = mysql_query($userQueryA) or err("No se pudo listar Asistentes".mysql_errno($userRecordsA));
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Login</b></td>
	<td bgcolor='.$colortitle.'><b>Estado</b></td>
	<td bgcolor='.$colortitle.'><b>Tipo Asistente</b></td>
	<td bgcolor='.$colortitle.'><b>Asistio</b></td>
	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vasistente.php?vopc='.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['apellidos'].' '.$fila['nombrep'].'</td>';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['login'];
		print '<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['tasistente'];
		print '</td><td bgcolor='.$bgcolor.' align=center>';
		if ($fila['asistencia']==1)
			print '<img src="'.$fslpath.$rootpath.'/images/checkmark.gif" border=0>';
		else 
			print  'No';
		print '</td>';
		print '<td bgcolor='.$bgcolor.'><a class="verde" href="act_asistencia.php?casistencia='.$fila['id'].' '.$fila['asistencia'].' '.$patron.'">Asistencia</td>';
		print '<td bgcolor='.$bgcolor.'><a class="precaucion" href="eli_asistente.php?basistente='.$fila['id'].' '.$patron.'">Eliminar</td>';
		print '</tr>';
	}
	print '</table>';
}
if (isset($_GET['letra']) && isset($_POST['submit']))
	unset ($_GET['letra']);
if (isset($_GET['letra'])) {
	$patron=$_GET['letra'];
	busca($patron);
}
if (isset($_POST['submit']) && $_POST['submit'] == "Buscar") 
	{
	$patron=$_POST['apellidos'];
	busca($patron);
	}
retorno();
retorno();
	print '<center>
	<input type="button" value="Volver a menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#eventos">
	</center>';
imprimeCajaBottom();
imprimePie();?>
