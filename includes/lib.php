<?
include "conf.inc";
function err ($errmsg) {
  print "<p><span class=\"err\">Se han encontrado problemas : <i>$errmsg</i>.<p>Por favor conctacte al <a href=\"mailto:".$adminmail."?subject=Problema con Yacomas- $errmsg\">Administrador</a>.</span><p>";
  exit;
}
//--------------------------------
function showError($errmsg) {
  print "<p><span class=\"err\">Por favor verifique lo siguiente:<ul>$errmsg</ul></span><p><hr><p>\n";
}
//--------------------------------
//--------------------------------
function imprimeEncabezado()
{
global $confName;
require "header.inc";
retorno();
alinearIzq("center"); 
print '<img src="'.$rootpath.'/images/yacomas.png","237","83" border="0">';
alinearFin();
retorno();
print ("<table border=0 cellpading=0 cellspacing=5 width=100% align=center>");
print ("<tr></td><td valign=top>");
retorno();
}
//--------------------------------
//--------------------------------
function muestraImagen($path,$ancho,$alto)
{
 print ("<img src=$path width=$ancho heigth=$alto>");
//--------------------------------
}

//--------------------------------
function alinearIzq($pos)
{
 print ("<div align=$pos>");
}
//--------------------------------

function alinearFin()
{
 print ("</div>");
}
//--------------------------------

//--------------------------------
function imprimeTitulo($titulo)
{
  print ("<center><font size=+3 color=#000000 face=arial>$titulo</font></center>");
}
//--------------------------------

//--------------------------------
function retorno()
{
 print("<br>");
}
//--------------------------------
//--------------------------------
function retorno_esp()
{
 print("<br>&nbsp;");
}

//--------------------------------

function aplicaEstilo() 
{
	print '<link rel=stylesheet href='.$rootpath.'/style1.css type=text/css>';
}
//--------------------------------
function imprimePie()
{
	include "footer.inc";
}
//--------------------------------
function imprimeCaja($percent, $titulo,$texto)
{

 print ("<table border=0 cellpading=0 cellspacing=0 width=$percent% align=center>");
   print ("<tr><td bgcolor=#911f43>");
      print ("<table border=0 cellpading=1 cellspacing=1 width=100%>");
          print ("<tr><td bgcolor=#FFFFFF><center><font face=arial size=6>$titulo</font></center>
	<font face=arial size2=><div algin=center><p>$texto</p></td></tr>");
      print ("</table>");
   print("</td></tr>");
print("</table>");
}
//--------------------------------
//--------------------------------
function imprimeCajaTop($percent, $titulo)
{
 print ("<table border=0 cellpading=0 cellspacing=0 width=$percent% align=center>");
   print ("<tr><td bgcolor=#911f43>");
      print ("<table border=0 cellpading=1 cellspacing=1 width=100%>");
          print ("<tr><td bgcolor=#FFFFFF><center><font face=arial size=6>$titulo</font></center>");
	  retorno();
}
//--------------------------------
//--------------------------------
function imprimeCajaBottom()
{
	retorno();
      print ("</table>");
   print("</td></tr>");
print("</table>");

}
//--------------------------------
//-------------------------------
function conectaBD()
{
include "db.inc";
  if(!($link=mysql_pconnect($dbhost,$dbuser,$dbpwd)))
   {
    print("No se puede hacer la conexion a la Base de Datos");
    exit();
   }
   mysql_select_db($dbname) or die (mysql_error());

}
//--------------------------------
function beginSessionP() {
        session_start();
	session_register("YACOMASVARS");
	if (empty($_SESSION['YACOMASVARS']['ponlogin']) || empty($_SESSION['YACOMASVARS']['ponid']) || 
	   ((time() - $_SESSION['YACOMASVARS']['ponlast']) > (60*60))) {    # 1 hour exp.
		header("Location: signin.php?e=exp");
		exit;
	}
	$_SESSION['YACOMASVARS']['ponlast'] = time();
}
function beginSession($tipo) {
        session_start();
	session_register("YACOMASVARS");
	switch ($tipo)
	{
		case 'P': 
			  $login='ponlogin';
			  $id='ponid';
			  $last='ponlast';
			  break;
		case 'A':
			  $login='asilogin';
			  $id='asiid';
			  $last='asilast';
			  break;
		case 'R':
			  $login='rootlogin';
			  $id='rootid';
			  $last='rootlast';
			  $level='rootlevel';
			  break;
	}
	if ($tipo=='R')
	{
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
		    empty($_SESSION['YACOMASVARS'][$login]) ||
	           ((time() - $_SESSION['YACOMASVARS'][$last]) > (60*60)))

		{    # 1 hour exp.
			header("Location: signin.php?e=exp");
			exit;
			$_SESSION['YACOMASVARS'][$last] = time();
		}
	}
	else 
	{
		
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
	           ((time() - $_SESSION['YACOMASVARS'][$last]) > (60*60))) 
		{    # 1 hour exp.
			header("Location: signin.php?e=exp");
			exit;
			$_SESSION['YACOMASVARS'][$last] = time();
		}
	}
}
?>
