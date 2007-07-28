<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';

imprimeCajaTop("100","Registro de Asistentes a Talleres y Tutoriales");
	print	'
		<FORM method="GET" action="Reg_Asistente.php">
		<table width=100%>
		<tr>
		<td class="name">Usuario del Asistente: </td>
		<td class="input">
		<input TYPE="text" name="login" size="15">
		&nbsp;&nbsp;
		<input type="submit" name="submit" value="Inscribir">&nbsp;&nbsp;
		</td>
		<td>Vacio para listar todos los asistentes 
		</td>
		</tr>
		</table>
		<br>
		<br>
		<br>
		</form>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver a menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#eventos">
	</center>';
imprimeCajaBottom();
imprimePie();?>
