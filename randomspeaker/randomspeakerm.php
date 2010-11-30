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
        <meta name="author" content="Geronimo Orozco" >
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
	font-size:82%;
    
  }
  h2{color:#149A9B; border-bottom:1px solid #FFFF00;}

  .fsl-speaker img
  {
    margin-left: 0.4em; /* Put some space between the text and the image. */
    margin-right: 0.4em; /* Put some space between the text and the image. */
  }
  </style>

<?php
$link=conectaBD();
// Normal speakers
$userQueryPM = 'SELECT 	e.descr as estado, p.id, p.login, p.ciudad,
					p.nombrep, p.apellidos, p.resume
			FROM 	ponente as p, estado as e 
			WHERE p.id IN (SELECT DISTINCT id_ponente 
					FROM propuesta 
 		                        WHERE id_status=8 
                		        AND  id_prop_tipo=100) 
				AND p.id_estado = e.id';
$userRecordsPM = mysql_query($userQueryPM) or err("No se pudo listar ponentes magistrales".mysql_errno($userRecordsPM));
$i = 0;
while ($fila = mysql_fetch_array($userRecordsPM))
	{
		$ponente_array[$i]['login'] = $fila['login'];
		$ponente_array[$i]['id'] = $fila['id'];
		$ponente_array[$i]['ponente_name'] = $fila['nombrep'] . ' ' . $fila['apellidos'];
		$ponente_array[$i]['ciudad'] = $fila['ciudad'];
		$ponente_array[$i]['estado'] = $fila['estado'];
		$ponente_array[$i]['resume'] = $fila['resume'];
		$i++;
	}
mysql_free_result($userRecordsPM);

if (!empty($ponente_array))
    shuffle($ponente_array);

$align = (rand(0,100) > 50) ? "right" : "left";
$resume_words_limit=100;
for ($i = 0; $i < count($ponente_array); $i++)
	{
		$login = $ponente_array[$i]['login'];
		$idponente = $ponente_array[$i]['id'];
		$ponente_name = $ponente_array[$i]['ponente_name'];		
		$ciudad = $ponente_array[$i]['ciudad'];
		$estado = $ponente_array[$i]['estado'];
		$resume_words = str_word_count ($ponente_array[$i]['resume'],1);
		$nwords = count($resume_words);
		$resume = '';	   
		if ( $nwords > $resume_words_limit) 
    		for ($y=0; $y<$resume_words_limit; $y++) 
        		$resume = $resume . $resume_words[$y] . ' ';
		else 
    		$resume = $ponente_array[$i]['resume'];
		$resume = $resume . " <a href=\"http://www.fslvallarta.org/?q=ponentes\" target=\"_parent\">Leer m&aacute;s...</a>";
		$foto = (file_exists("{$image_ponente_dest}foto_{$idponente}.jpeg"))?"{$image_ponente_dest}foto_$idponente.jpeg":$image_ponente_default;	
?>
		<div class="fsl-speaker">
		<a name="<?php echo $login ?>"></a>
		<h2><?php echo $ponente_name ?>
		<br /><small><?php echo $ciudad.' '.$estado ?></small></h2>
		<img src="<?php echo $foto ?>" align="<?php echo $align ?>" height="150" alt="<?php echo $ponente_name ?>"/>		
		<p class="ponente"><?php print $resume ?></p>
		</div>
<?php
   if ( strcmp($align, "left") == 0 ) 
   	$align = "right";
   else 
   	$align = "left";
	}
?>
	</body>
</html>
