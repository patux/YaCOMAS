<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryP = 'SELECT * FROM administrador WHERE id!="'.$idadmin.'" AND id!="1" ORDER BY id';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar administradores".mysql_errno($userRecords));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de administradores");
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Login</b></td><td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Apellidos</b></td><td bgcolor='.$colortitle.'><b>Correo</b></td>
	<td bgcolor='.$colortitle.'><b>Tipo admin</b></td>
	<td bgcolor='.$colortitle.'>&nbsp;</td>
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
		<td bgcolor='.$bgcolor.'>'.$fila['login'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['nombrep'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['apellidos'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['mail'].'</td>
		<td bgcolor='.$bgcolor.'>';
		$query = 'SELECT descr FROM tadmin WHERE id="'.$fila['id_tadmin'].'"';
		$result=mysql_query($query);
	 	$ftadmin=mysql_fetch_array($result);
		print $ftadmin['descr'];
		mysql_free_result($result);
		
		print '</td><td bgcolor='.$bgcolor.'><a class="precaucion" href="Badmin.php?admin='.$fila['id'].'">Eliminar</td>';
		print '</tr><tr><td>'; 
		$QSquery = 'SELECT * FROM tadmin ORDER BY ID'; 
		$resultQS=mysql_query($QSquery);
		print '<small>';
		while ($QSfila=mysql_fetch_array($resultQS)) 
		{
			print '| <a class="verde" href="act_admin.php?vact='.$fila['id'].' '.$QSfila['id'].' '.$_SERVER['REQUEST_URI'].'" onMouseOver="window.status=\''.$QSfila['descr'].'\';return true" onFocus="window.status=\''.$QSfila['descr'].'\';return true" onMouseOut="window.status=\'\';return true">'.$QSfila['descr'].' |</a>';
		}
		mysql_free_result($resultQS);
		print '</small></td></tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php">
	</center>';
imprimeCajaBottom();
imprimePie();?>
