<?
include "conf.inc.php";
function send_mail($myname, $myemail, $contactname, $contactemail, $subject, $message, $bcc) {
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From: \"".$myname."\" <".$myemail.">\n";
	if ($bcc != "")
		$headers .= "Bcc: ".$bcc."\n";   
	$output = $message;                $output = wordwrap($output, 72);
	return(mail("\"".$contactname."\" <".$contactemail.">", $subject, $output, $headers));
}
//--------------------------------
function generatePassword() {

    $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    srand((double)microtime()*1000000);  
    $i = 0;
    while ($i < 15) {  // change for other length
        $num = rand() % 33;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
//--------------------------------
function strftime_caste($formato, $fecha){
// strftime por Marcos A. Botta
// $fromato: como se quiere mostrar la fecha
// $fecha: tiemestamp correspondiente a la fecha y hora que se quiere mostrar
$salida = strftime($formato,  $fecha);
	    // reemplazo meses  
	    $salida = ereg_replace("January","Enero",$salida);
	    $salida = ereg_replace("February","Febrero",$salida);
	    $salida = ereg_replace("March","Marzo",$salida);
	    $salida = ereg_replace("April","Abril",$salida);
	    $salida = ereg_replace("May","Mayo",$salida);
	    $salida = ereg_replace("June","Junio",$salida);
	    $salida = ereg_replace("July","Julio",$salida);
	    $salida = ereg_replace("August","Agosto",$salida);
	    $salida = ereg_replace("September","Septiembre",$salida);
	    $salida = ereg_replace("October","Octubre",$salida);
	    $salida = ereg_replace("November","Noviembre",$salida);
	    $salida = ereg_replace("December","Diciembre",$salida);
            // reemplazo meses cortos
	    $salida = ereg_replace("Jan","ene",$salida);
	    $salida = ereg_replace("Apr","abr",$salida);
	    $salida = ereg_replace("Aug","ago",$salida);
	    $salida = ereg_replace("Dec","dic",$salida);
	    // reemplazo di'as
	    $salida = ereg_replace("Monday","Lunes",$salida);
	    $salida = ereg_replace("Tuesday","Martes",$salida);
	    $salida = ereg_replace("Wednesday","Miercoles",$salida);
	    $salida = ereg_replace("Thursday","Jueves",$salida);
	    $salida = ereg_replace("Friday","Viernes",$salida);
	    $salida = ereg_replace("Saturday","Sábado",$salida);
	    $salida = ereg_replace("Sunday","Domingo",$salida);
	    // reemplazo dias cortos
	    $salida = ereg_replace("Mon","Lun",$salida);
	    $salida = ereg_replace("Tue","Mar",$salida);
	    $salida = ereg_replace("Wed","Mié",$salida);
	    $salida = ereg_replace("Thu","Jue",$salida);
	    $salida = ereg_replace("Fri","Vie",$salida);
	    $salida = ereg_replace("Sat","Sáb",$salida);
	    $salida = ereg_replace("Sun","Dom",$salida);
	    // reemplazo cuando es 1 de algun mes
	    $salida = ereg_replace(" 01 de "," 1&deg; de ",$salida);
	    return $salida;
	    } // fin strftime_caste
//--------------------------------
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
require "header.inc.php";
retorno();
alinearIzq("center"); 
print '<img src="'.$rootpath.'/images/yacomas.png","287","91" border="0">';
alinearFin();
retorno();
print ("<table border=0 cellpading=0 cellspacing=5 width=100% align=center>");
print ("<tr></td><td valign=top>");
retorno();
}
//--------------------------------
//--------------------------------
function imprimeEncabezadoR()
{
require "header.inc.php";
retorno();
alinearIzq("center"); 
print '<table border=0 width=100%>';
print '<tr><td width=10%>&nbsp;</td>';
print '<td width=80% align="center">';
muestraImagen("$rootpath/images/yacomas.png","287","91");
print '<td width=10% valign="bottom">';
print '</tr></table>';
alinearFin();
retorno();
print ("<table border=0 cellpading=0 cellspacing=5 width=100% align=center>");
print ("<td valign=top>");
retorno();
}
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
	global $rootpath;
	print '<link rel=stylesheet href='.$rootpath.'/style1.css type=text/css>';
}
//--------------------------------
function imprimePie()
{
	include "footer.inc.php";
}
//--------------------------------
//--------------------------------
function imprimeCaja($percent, $titulo,$texto)
{

 print ("<table border=0 cellpading=0 cellspacing=0 width=$percent% align=center>");
   #print ("<tr><td bgcolor=#911f43>");
   print ("<tr><td bgcolor=#5F6B8B>");
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
   print ("<tr><td bgcolor=#5F6B8B>");
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
//--------------------------------
function imprimeCajaTop1($percent, $titulo)
{
 print ("<table border=0 cellpading=0 cellspacing=0 width=$percent% align=center>");
   print ("<tr><td bgcolor=#5F6B8B>");
      print ("<table border=0 cellpading=1 cellspacing=1 width=100%>");
          print ("<tr><td bgcolor=#FFFFFF><center><font face=arial size=6>$titulo</font></center>");
      retorno();
}
//--------------------------------
function imprimeCajaBottom1()
{
	retorno();
      print ("</td></tr></table>");
   print("</td></tr>");
print("</table>");
}
//-------------------------------
//-------------------------------
function conectaBD()
{
include "db.inc.php";
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
	$t_transcurrido=(time() - $_SESSION['YACOMASVARS'][$last]);
	$hora=3600;
	if ($tipo=='R')
	{
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
		    empty($_SESSION['YACOMASVARS'][$level]) ||
	            ($t_transcurrido > $hora))

		{    # 1 hour exp.
			header("Location: signin.php?e=exp");
			exit;
		}
	}
	else 
	{
		
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
	            ($t_transcurrido > $hora))
		{    # 1 hour exp.
			header("Location: signin.php?e=exp");
			exit;
		}
	}
	$_SESSION['YACOMASVARS'][$last] = time();
}
?>
