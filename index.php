<?
	include "includes/conf.inc.php";
        include "includes/lib.php";
	imprimeEncabezado();
	imprimeCajaTop("100","Registro");
	aplicaEstilo();


print '
<p>¡Gracias por tu interés en el Congreso!</p>
 
 <p>
 <a href="'.$fslpath.$rootpath.'/ponente/index.php?opc=1">Regístro de ponentes 
 </a>
 &nbsp;
 <a href="'.$fslpath.$rootpath.'/ponente/">Accesa a tu cuenta
 </a>

 <br/>
 Es necesario tu registro, mediante el cual podrás enviar
 ponencias y estar informado del evento.</p>
 <p>
 <a href="'.$fslpath.$rootpath.'/asistente/index.php?opc=1">Regístro de asistentes
 </a>
 &nbsp;
 <a href="'.$fslpath.$rootpath.'/asistente/">Accesa a tu cuenta
 </a>
 <br/>
 Es necesario tu registro, mediante el cual podrás realizar preinscripcion al al congreso y  talleres
 ademas de mantenerte informado del evento.</p>
 <p><a href="'.$fslpath.$rootpath.'/lista/">Lista preliminar de ponencias</a>
 <br/>
 Aquí verás las propuestas ponencias que han sido enviadas, y el status en el que se encuentran dichas ponencias.</p>
 <p><a href="'.$fslpath.$rootpath.'/modalidades/">Modalidades de participacion en la peticion de ponencias</a>
 Modalidades de las ponencias que encontraras en el evento 
 <br />
 ';

imprimeCajaBottom(); 
imprimePie(); 
?>
