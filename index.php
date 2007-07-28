<?
	include_once "includes/conf.inc.php";
	include_once "includes/lib.php";
	imprimeEncabezado();
    #imprimeCajaTop("100","Registro");


print " 
<p>Gracias por tu inter&eacute;s en $conference_name</p>
";
print '
 
 <p>
 <a href="'.$fslpath.$rootpath.'/ponente/index.php?opc=';
 print NPONENTE;
 print '">Registro de ponentes 
 </a>
 &nbsp;
 <a href="'.$fslpath.$rootpath.'/ponente/">Accede a tu cuenta
 </a>

 <br/>
 Es necesario tu registro, mediante el cual podr&aacute;s enviar
 ponencias y estar informado del evento.</p>
 <p>
 <a href="'.$fslpath.$rootpath.'/asistente/index.php?opc=';
 print NASISTENTE;
 print '">Registro de asistentes
 </a>
 &nbsp;
 <a href="'.$fslpath.$rootpath.'/asistente/">Accede a tu cuenta
 </a>
 <br/>
 Es necesario tu registro, mediante el cual podr&aacute;s realizar preinscripci&oacute;n al al congreso y  talleres
 ademas de mantenerte informado del evento.</p>
 ';
// <p><a href="'.$fslpath.$rootpath.'/lista/">Lista preliminar de ponencias</a>
// <br/>
// Aqu&iacute; ver&aacute;s las propuestas ponencias que han sido enviadas, y el status en el que se encuentran dichas ponencias.</p>
 print '
<p><a href="'.$fslpath.$rootpath.'/modalidades/">Modalidades de participacion en la peticion de ponencias</a>
 Modalidades de las ponencias que encontraras en el evento!
 <br />
 ';

 #imprimeCajaBottom(); 
 ?>
 
<?imprimePie();?> 
