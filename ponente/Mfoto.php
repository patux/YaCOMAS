<?php
  include_once "../includes/lib.php";
  include_once "../includes/conf.inc.php";
  include_once "../includes/class.upload/class.upload.php";
  
  if ( !$image_ponente_allow ) {
    header( "location: menuponente.php");
  }
  
  beginSession('P');
  imprimeEncabezado();
  
  print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['ponlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
  imprimeCajaTop("100","Subir/Cambiar mi Foto");
  
  $id_ponente=$_SESSION['YACOMASVARS']['ponid'];
  $random = '';
  if ( isset($_POST['submit']) && $_POST['submit']=='Subir') {
    $handle = new Upload($_FILES['mi_foto']);
    if ($handle->uploaded) {
      $handle->file_new_name_body      = "foto_$id_ponente";
      $handle->image_convert           = "jpeg";
      $handle->allowed                 = array("image/*");
      $handle->mime_check              = true;
      $handle->file_overwrite          = true;
      $handle->image_resize            = true;
      $handle->image_ratio_y           = true;
      $handle->image_x                 = $image_ponente_max_width;

      $handle->Process($image_ponente_dest);
      if ($handle->processed) {
           echo "<p id='message' class='error'>Su nueva foto ha sido subida con exito.</p>";
           $random = "?r=" . rand(1,1000);
      } else {
            echo "<p id='message' class='error'>Error: ".$handle->error."</p>";
      }
      $handle-> Clean();
    }
  }
  $foto = (file_exists("{$image_ponente_dest}foto_{$id_ponente}.jpeg"))?"{$image_ponente_dest}foto_$id_ponente.jpeg":$image_ponente_default;
  ?>
  
  <p id="foto_frame" style="float:left">
  <img src="<?php echo $foto . $random ?>" alt="Foto"/>
  </p>
  
  <form name="image_upload" enctype="multipart/form-data" action="ponente.php?opc=<?php echo MPONENTEFOTO ?>" method="post">
    <p>
      <label for="foto">Selecciona la foto para subir:</label>
      <input type="file" size="32" name="mi_foto" id="mi_foto" value=""/><br/>
      La foto se convertir&aacute; a jpeg y se escalar&aacute; a un ancho de <?php echo $image_ponente_max_width ?>px.
      <input type="submit" name="submit" value="Subir"><br/>
    </p>
   </form>
<?php
  retorno();
  retorno();
  print '<center>
   <input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/ponente/menuponente.php">
  </center>';
  imprimeCajaBottom(); 
  imprimePie(); 
  
