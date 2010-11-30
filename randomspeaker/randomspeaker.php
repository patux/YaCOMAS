<?php 
include "../includes/lib.php";
include "../includes/conf.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
        <meta HTTP-EQUIV="Pragma" content="no-cache">
        <meta NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
        <meta HTTP-EQUIV="Content-Language" CONTENT="ES">
        <meta HTTP-EQUIV="Content-Type" content="text/html; charset=ISO-8859-1">
        <meta NAME="description" CONTENT="'.$conference_name.'">
        <meta NAME="keywords" CONTENT="Hispalinux,hipalinux,festival,software,libre,software libre,festival de software libre,2010,FSL,linux,gnu,gpl,openbsd,freebsd,netbsd,gnu/linux">
        <meta NAME="author" CONTENT="Patux">
        <meta NAME="copyright" CONTENT="Copyrigth Geronimo Orozco (Patux)">
        <meta NAME="audience" CONTENT="All">
        <meta NAME="distribution" content="Global">
        <meta NAME="rating" content="General">
        <meta NAME="revisit-after" CONTENT="1 days">
</head>
<body>


  <style type="text/css">
  .fsl-speaker
  {
    clear: both;
	font-family:Arial,Verdana,sans-serif;
	font-size:66%;
    
  }
  h2{color:#149A9B; border-bottom:1px solid #FFFF00;}

  .fsl-speaker img
  {
    align: center; /* doesn't seem to work, though align=right in the img does. murrayc */
    margin-left: 0.4em; /* Put some space between the text and the image. */
  }

  </style>

<?php
$link=conectaBD();
// Normal speakers
$userQueryP = 'SELECT 	e.descr as estado, p.login, p.id, p.ciudad,
					p.nombrep, p.apellidos, p.resume
			FROM 	ponente as p, estado as e 
			WHERE p.id in (SELECT DISTINCT id_ponente 
							FROM propuesta 
							WHERE id_status=8 
                            AND   id_prop_tipo<100) 
                        AND p.id NOT IN (SELECT DISTINCT id_ponente
                             FROM propuesta
                             WHERE id_status=8
                             AND id_prop_tipo=100)
                             AND p.id_estado = e.id 
                        ORDER BY RAND()
                        LIMIT 1';
$userRecordP = mysql_query($userQueryP) or err("No se pudo obtener un ponente al azar".mysql_errno($userRecordP));
$fila = mysql_fetch_row($userRecordP);

$resume_words_limit=100;
$estado = $fila[0];
$login = $fila[1];
$idponente = $fila[2];
$ciudad = $fila[3];
$ponente_name = $fila[4] . ' ' . $fila[5];
$resume_words = str_word_count ($fila[6],1);
$nwords = count($resume_words);
$resume = '';	   
if ( $nwords > $resume_words_limit) 
    for ($y=0; $y<$resume_words_limit; $y++) 
        $resume = $resume . $resume_words[$y] . ' ';
else 
    $resume = $fila[6];
$resume = $resume . " <a href=\"http://www.fslvallarta.org/?q=ponentes\" target=\"_parent\">Leer m&aacute;s...</a>";

$foto = (file_exists("{$image_ponente_dest}foto_{$idponente}.jpeg"))?"{$image_ponente_dest}foto_$idponente.jpeg":$image_ponente_default;

mysql_free_result($userRecordP);
?>
		<div class="fsl-speaker">
		<a name="<?php echo $login ?>"></a>

		<h2><?php echo $ponente_name ?>
		<br /><small><?php echo $ciudad.' '.$estado ?></small></h2>
		<img src="<?php echo $foto ?>" align="center" width="150" alt="<?php echo $ponente_name ?>"/>		
		<p class="ponente" align="justify"><? print $resume ?></p>
		</div>
</body>
</html>
