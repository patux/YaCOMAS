<?php 
global $rootpath,$bgcolor,$conference_name,$general_mail,$copyright_author,$copyright_year,$link_author,$conference_logo, $colorBorder;
// rootpath es usada para decirle a yacomas 
// cual es el directorio raiz de el sitio web
// en el que estamos instalando yacomas
// Estos paths siempre son con respecto al DocumentRoot de apache
// No al path completo como /home/sitio/ y /home/sitio/yacomas
// Mas info en Doc
global $archivos;
// Variable that is unusefull now do not modify it should be removed soon
$fslpath='';
// rootpath - This is the root directory for yacomas, starting from the DocumentRoot from Apache
// ex. httpd.conf has DocumentRoot = /var/www/
//     yacomas is stored into /var/www/yacomas/
//     $rootpath="/yacomas";
$rootpath='/yacomas';
// The place where the files from the speakers will be stored   
// The directory must be created and give the specific permissions in order to apache can write inside that directory
$archivos = "/www/yacomas/documentos/";
// The mail to provide users who have PROBLEMS, WARNINGS with Yacomas.
$adminmail='admin@yourdomain';
// The mail to provide to all the users, and the mail that will be used to send mails for New account or reset account.
$general_mail='noreply@yourdomain';
// The name for the conference where yacomas is used
$conference_name="Your conference name";
// Copyright information customized for the admin of yacomas and the conference that uses it
$copyright_author="Your copytright author";
// Year for the custom copyright information  
$copyright_year="Your copyright year";
// Link for the admin of yacomas and the conference that uses it
$link_author="link author";
// Link for the conference 
$conference_link="http://yourhost/yacomas";
// Image to show at the top-center for yacomas that image should exist into yacomas/images/ directory
$conference_logo="yacomas.png";
// Workshops max limit to be used 
$limite=100; // 
// Start and End time for the events 24hrs format 
$def_hora_ini=8;
$def_hora_fin=22;
// Max of inscription to workshops and tutorials 
$max_inscripcionTA=2;
$max_inscripcionTU=3;
/////
// Colors and fashion
/////
// Background color for the webpage this will be used in all the pages
//$bgcolor='#ffffff';
$bgcolor='#EFEFEF';
// Colores de las tablas en los listados
//$colortitle='#ff9900';
//$color_renglon1='#ffcc66';
//$color_renglon2='#ffff99';
//$colortitle='#7e7ebc';
$colorBorder='#39708a';
$colortitle='#39708a';
$color_renglon1='#cce0ea';
$color_renglon2='#9dc4d7';
$color_especiales='#98AFC7';

///////
// Image Uploader Stuff
///////
$image_ponente_allow      = true;
$image_ponente_max_width  = '240';
$image_ponente_dest       = '../fotos/ponentes/';
$image_ponente_default    = '../images/missing_ponente.png';

///////
//Aceptadas/Detalles Requeridos/No aceptada/ Por Aceptar 
///////

$prop_detalles_requeridos =             
    "Your speach to notify the speaker that a conference need details to be properly evaluated,
    add as many lines as you want
    \n\n";
$prop_rechazada=             
    "Your speach to notify the speaker that a conference was not accepted,
    add as many lines as you want
    \n\n";
$prop_por_aceptar=             
    "Your speach to notify the speaker that a conference is about to be acceptet but need something(your call),
    add as many lines as you want
    \n\n";
$prop_aceptada =             
    "Your speach to notify the speaker that a conference was accepted,
    add as many lines as you want
    \n\n";
$prop_cancelada=             
    "Your speach to notify the speaker that a conference was canceled by an admin because your given reason, 
    add as many lines as you want
    \n\n";

///////
//Mail stuff
///////
// The mail that should be used for send emails IP or domain name is valid
$smtp="your.smpt.domain";
// This constant will be used to enable or disable the feature to send mails(spam?) patux@patux.net
define (SEND_MAIL,0); // Disabled by default  (0 Disable, 1 Enable)

/******
 * CONSTANTES DE ENTORNO
 */
//Para index.php defino el estado 1 como nuevo asistente
define (NASISTENTE,1);
//para asistente/asistente.php defino los enteros por su estado
define (MASISTENTE,1);
define (LEVENTOS,2);
define (LTALLERES,3);
define (LTALLERESREG,4);
define (ENCUESTA,5);
define (HOJAREGISTRO,6);
// Para index.php defino el estado 1 como nuevo ponente
define (NPONENTE, 1);
// Para ponente/ponente.php
define (NPONENCIA,1);
define (PROPUESTAENV,2);
define (MPONENTE,3);
define (MPONENTEFOTO,4);
// Nomenclatura de ficheros
define (CARACTERSEPARADOR,"-"); //Cada fichero tendrá la siguiente nomenclatura
					   // <ruta><idusuario><CARACTERSEPARADOR><nombrefichero>
					   // Ejemplo /var/www/yacomas/documentos/1-prueba.pdf
					   
if ( defined('TO_ROOT') ) {
	include_once TO_ROOT . "/includes/Autoloader.php";
	Autoloader::registerAutoload();
}
?>
