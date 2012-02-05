<?php 
	include_once "includes/conf.inc.php";
	include_once "includes/lib.php";
	imprimeEncabezado();
    imprimeCajaTop("100","Registro $conference_name<hr>");


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
 print '">Pre-registro de asistentes
 </a>
 &nbsp;
 <a href="'.$fslpath.$rootpath.'/asistente/">Accede a tu cuenta
 </a>
 <br/>
 Es necesario tu pre-registro, mediante el cual podr&aacute;s realizar preinscripci&oacute;n al al congreso y  talleres
 ademas de mantenerte informado del evento.</p>
 <p><a href="'.$fslpath.$rootpath.'/lista/">Lista preliminar de propuestas</a>
 <br/>
 Aqu&iacute; ver&aacute;s las propuestas ponencias que han sido enviadas, y el status en el que se encuentran dichas ponencias.</p>';
 print '<p><a href="'.$fslpath.$rootpath.'/aceptadas/">Lista de ponencias aceptadas</a>
 <br/>
 Aqu&iacute; ver&aacute;s las ponencias que han sido aceptadas, y que formaran parte del programa final del FSL Vallarta 2010.</p>
 ';
 print '<p><a href="'.$fslpath.$rootpath.'/programa/">Agenda de actividades</a>
 <br/>
 Aqu&iacute; ver&aacute;s los eventos y ponencias con las que cuenta el '.$conference_name.' </p>';
 print '
 <p><a href="'.$fslpath.$rootpath.'/speakers/">Ponentes que participan en '.$conference_name.'</a>
 <br />
 Aqu&iacute; ver&aacute;s los ponentes que formarn parte del FSL Vallarta 2010.</p>
 ';
 print '
 <p><a href="'.$fslpath.$rootpath.'/modalidades/">Modalidades de participacion en la peticion de ponencias</a>
 Modalidades de las ponencias que encontraras en el evento!
 <br />
 ';

imprimeCajaBottom(); 
 ?>
 
<?php imprimePie();?> 
