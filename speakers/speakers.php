<?php 
include "../includes/lib.php";
include "../includes/conf.inc.php";
imprimeEncabezado();
?>

  <style type="text/css">
  .fsl-speaker
  {
    clear: both;
    
  }

  .fsl-speaker img
  {
    align: right; /* doesn't seem to work, though align=right in the img does. murrayc */
    margin-left: 0.4em; /* Put some space between the text and the image. */
  }
  </style>


<?php 
$msg=$conference_name." Ponentes<hr>"; 
imprimeCajaTop("100",$msg);
?>
<div class="fsl-speaker">
<h2>Ponentes Magistrales</h2>
</div>

<?php
$link=conectaBD();
// Magistral speakers
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
for ($i = 0; $i < count($ponente_array); $i++)
	{
		$login = $ponente_array[$i]['login'];
		$idponente = $ponente_array[$i]['id'];
		$ponente_name = $ponente_array[$i]['ponente_name'];		
		$ciudad = $ponente_array[$i]['ciudad'];
		$estado = $ponente_array[$i]['estado'];
		$resume = $ponente_array[$i]['resume'];
		$foto = (file_exists("{$image_ponente_dest}foto_{$idponente}.jpeg"))?"{$image_ponente_dest}foto_$idponente.jpeg":$image_ponente_default;	
		?>
		<div class="fsl-speaker">
		<a name="<?php echo $login ?>"></a>
		<h2><?php echo $ponente_name ?>
		<br /><small><?php echo $ciudad.' '.$estado ?></small></h2>
		<img src="<?php echo $foto ?>" align="right" alt="<?php echo $ponente_name ?>"/>		
		<p class="ponente"><?php print $resume ?></p>
		</div>
		<?php
	}

?>

<div class="fsl-speaker">
<h2>Otros Ponentes</h2>
</div>

<?php
// Normal speakers
$userQueryP = 'SELECT 	e.descr as estado, p.id, p.ciudad,
					p.nombrep, p.apellidos, p.resume
			FROM 	ponente as p, estado as e 
			WHERE p.id IN (SELECT DISTINCT id_ponente 
					FROM propuesta 
					WHERE id_status=8 
                            AND  id_prop_tipo<100) 
                       AND p.id NOT IN 
                          (SELECT DISTINCT id_ponente 
                            FROM propuesta
                            WHERE id_status=8
                            AND id_prop_tipo=100)
			AND p.id_estado = e.id';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponentes".mysql_errno($userRecordsP));
$i = 0;
while ($fila = mysql_fetch_array($userRecordsP))
	{
		$ponente_array[$i]['login'] = $fila['login'];
		$ponente_array[$i]['id'] = $fila['id'];
		$ponente_array[$i]['ponente_name'] = $fila['nombrep'] . ' ' . $fila['apellidos'];
		$ponente_array[$i]['ciudad'] = $fila['ciudad'];
		$ponente_array[$i]['estado'] = $fila['estado'];
		$ponente_array[$i]['resume'] = $fila['resume'];
		$i++;
	}
mysql_free_result($userRecordsP);

if (!empty($ponente_array))
    shuffle($ponente_array);
for ($i = 0; $i < count($ponente_array); $i++)
	{
		$login = $ponente_array[$i]['login'];
		$idponente = $ponente_array[$i]['id'];
		$ponente_name = $ponente_array[$i]['ponente_name'];		
		$ciudad = $ponente_array[$i]['ciudad'];
		$estado = $ponente_array[$i]['estado'];
		$resume = $ponente_array[$i]['resume'];
		$foto = (file_exists("{$image_ponente_dest}foto_{$idponente}.jpeg"))?"{$image_ponente_dest}foto_$idponente.jpeg":$image_ponente_default;	
		?>
		<div class="fsl-speaker">
		<a name="<?php echo $login ?>"></a>

		<h2><?php echo $ponente_name ?>
		<br /><small><?php echo $ciudad.' '.$estado ?></small></h2>
		<img src="<?php echo $foto ?>" align="right" alt="<?php echo $ponente_name ?>"/>		
		<p class="ponente"><?php print $resume ?></p>
		</div>
		<?php
	}
imprimeCajaBottom();
imprimePie();
?>
