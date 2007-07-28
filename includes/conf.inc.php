<?
global $rootpath,$bgcolor,$conference_name,$general_mail,$copyright_author,$copyright_year,$link_author,$conference_logo;
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
$archivos = "/var/www/yacomas/documentos/";
// The mail to provide users who have PROBLEMS, WARNINGS with Yacomas.
$adminmail='admin@nomail.com';
// The mail to provide to all the users, and the mail that will be used to send mails for New account or reset account.
$general_mail='noreply@nomail.com';
// The name for the conference where yacomas is used
$conference_name="Example conference";
// Copyright information customized for the admin of yacomas and the conference that uses it
$copyright_author="Yacomas author";
// Year for the custom copyright information  
$copyright_year="2005";
// Link for the admin of yacomas and the conference that uses it
$link_author="http://patux.glo.org.mx";
// Image to show at the top-center for yacomas that image should exist into yacomas/images/ directory
$conference_logo="yacomas.png";
// Workshops max limit to be used 
$limite=20; // 
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
$bgcolor='#d2e0ee';
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
//Mail stuff
///////
// The mail that should be used for send emails IP or domain name is valid
$smtp="your_smpt_name";
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
// Nomenclatura de ficheros
define (CARACTERSEPARADOR,"-"); //Cada fichero tendrá la siguiente nomenclatura
					   // <ruta><idusuario><CARACTERSEPARADOR><nombrefichero>
					   // Ejemplo /var/www/yacomas/documentos/1-prueba.pdf

?>
