<? 
include "../includes/lib.php";
include "../includes/conf.inc";
beginSession('R');
imprimeEncabezado();
aplicaEstilo();
$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQuery = 'SELECT nombrep,apellidos FROM administrador WHERE id="'.$idadmin.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$msg='Bienvenido Administrador<br><small>'.$p['nombrep'].' '.$p['apellidos'].'</small><hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="rojo" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '<font size=+1 color=#000000 face=arial>
<a NAME="admin"><a>
<H2>Yacomas Administracion</H2>
';
if ($_SESSION['YACOMASVARS']['rootlevel']==1) {
	print '<a href="admin.php?opc=C">Yacomas Configuracion</a> <br><br>';
	print '<a href="admin.php?opc=0">Agregar administrador</a> <br><br>';
	print '<a href="admin.php?opc=1">Listar administradores</a> <br><br>';
	print '<a href="admin.php?opc=2">Listar ponencias eliminadas</a> <br><br>';
}
print '
<a href="admin.php?opc=3">Modificar mis datos</a> <br><br>
<hr>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3)
	print '
	<a NAME="lugares"><a>
	<H2>Yacomas Lugares y Fechas</H2>
	<a href="admin.php?opc=4">Registrar Lugar para ponencias</a> <br><br>
	<a href="admin.php?opc=5">Listado de Lugares para ponencias</a> <br><br>
	<a href="admin.php?opc=11">Registrar fecha </a> <br><br>
	<a href="admin.php?opc=12">Listado de Fechas </a> <br><br>
	<hr>';
print '
<a NAME="ponencias"><a>
<H2>Yacomas Ponencias y Ponentes</H2>
<a href="admin.php?opc=6">Listado de Ponentes</a> <br><br>
<a href="admin.php?opc=7">Listado de Ponencias</a> <br><br>
<hr>
<a NAME="eventos"><a>
<H2>Yacomas Eventos y Asistentes</H2>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3) 
	print '<a href="admin.php?opc=8">Registro de Evento</a> <br><br>';
print '<a href="admin.php?opc=9">Listado de Eventos</a> <br><br>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3) 
	print '<a href="admin.php?opc=13">Listado de Asistentes</a> <br><br>';
print '</font>';
imprimeCajaBottom();
retorno_esp();
retorno_esp();
imprimePie();
?>
