<?
global $rootpath;
// fslpath es usada para decirle a yacomas 
// cual es el directorio raiz de el sitio web
// en el que estamos instalando yacomas
// Estos paths siempre son con respecto al DocumentRoot de apache
// No al path completo como /home/sitio/ y /home/sitio/yacomas
// Mas info en Doc
$fslpath='';
$rootpath='/yacomasdemo';
$adminmail='patux@glo.org.mx';
// Limite de cupo de los talleres
$limite=80;
// Limite de horas para los eventos 
$def_hora_ini=8;
$def_hora_fin=22;
// Limite de talleres a elegir por asistente
$max_inscripcionTA=2;
$max_inscripcionTU=3;
// Colores de las tablas en los listados
//$colortitle='#ff9900';
//$color_renglon1='#ffcc66';
//$color_renglon2='#ffff99';
//$colortitle='#7e7ebc';
$colortitle='#7e7ebc';
$color_renglon1='#b4b4de';
$color_renglon2='#ccdff9';
$color_especiales='#66ccff';
///////
$costo_playera=70;
////
$smtp="your_smpt_name";
?>
