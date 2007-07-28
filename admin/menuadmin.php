<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQuery = 'SELECT nombrep,apellidos FROM administrador WHERE id="'.$idadmin.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);
$msg=$conference_name.'<br>Bienvenido Administrador<br><small>'.$p['nombrep'].' '.$p['apellidos'].'</small><hr>';
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100",$msg);
print '<font size=+1 color=#000000 face=arial>
<a NAME="admin"><a>
<H2>Administraci&oacute;n</H2>
';
if ($_SESSION['YACOMASVARS']['rootlevel']==1) {
	print '<a href="admin.php?opc=C">Configuraci&oacute;n</a> <br><br>';
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
	<H2>Lugares y Fechas</H2>
	<a href="admin.php?opc=4">Registrar Lugar para ponencias</a> <br><br>
	<a href="admin.php?opc=5">Listado de Lugares para ponencias</a> <br><br>
	<a href="admin.php?opc=11">Registrar fecha </a> <br><br>
	<a href="admin.php?opc=12">Listado de Fechas </a> <br><br>
	<hr>';
print '
<a NAME="ponencias"><a>
<H2>Ponencias y Ponentes</H2>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3)
	print '
		<a href="admin.php?opc=16">Agregar Ponente</a> <br><br>
		<a href="admin.php?opc=17">Agregar Ponencia</a> <br><br>
		';
	print '
<a href="admin.php?opc=6">Listado de Ponentes</a> <br><br>
<a href="admin.php?opc=7">Listado de Ponencias</a> <br><br>
<hr>
<a NAME="eventos"><a>
<H2>Eventos y Asistentes</H2>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3) 
	print '<a href="admin.php?opc=8">Registro de Evento</a> <br><br>';
print '<a href="admin.php?opc=9">Listado de Eventos</a> <br><br>';
if ($_SESSION['YACOMASVARS']['rootlevel']<3){ 
	print '<a href="admin.php?opc=18">Inscripcion de Asistente a talleres y tutoriales</a> <br><br>';
	print '<a href="admin.php?opc=19">Baja Talleres y Tutoriales de un Asistente</a> <br><br>';
	print '<a href="admin.php?opc=13">Listado de Asistentes</a> <br><br>';
	}
print '<a href="admin.php?opc=15">Control de Asistencias</a> <br><br>';
print '</font>';
imprimeCajaBottom();
retorno_esp();
retorno_esp();
imprimePie();
?>
