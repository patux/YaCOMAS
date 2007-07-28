<?
include "../includes/lib.php";
include "../includes/conf.inc.php";
include_once "Mail.php";
beginSession('R');
$link=conectaBD();
	$tok = strtok ($_GET['vact']," ");
	$idponencia=$tok;
	$tok = strtok (" ");
	$idstatus=$tok;
	$tok = strtok (" ");
	$regresa=$tok;
	$Query_actualiza= "UPDATE propuesta SET id_status="."'".$idstatus."',
				  id_administrador="."'".$_SESSION['YACOMASVARS']['rootid']."'
			   WHERE id="."'".$idponencia."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la ponencia".mysql_errno($actualiza_registro));
    $QUERY="SELECT P.id AS id_ponencia, P.nombre AS ponencia, PT.descr AS prop_tipo, P.id_ponente, PO.nombrep, PO.apellidos, S.descr AS
        STATUS , P.id_status, PO.mail
        FROM propuesta AS P, ponente AS PO, prop_status AS S, prop_tipo AS PT
        WHERE P.id_ponente = PO.id
        AND P.id_status = S.id
        AND P.id_prop_tipo = PT.id
        AND P.id =$idponencia";
    $result= mysql_query($QUERY) or err("No se pudo accesar a los datos del ponente para enviar correo automatico".mysql_errno($userRecordsP));
	$p = mysql_fetch_array($result);
    $S_nombreponencia=$p['ponencia'];
    $S_prop_tipo=$p['prop_tipo'];
    $S_nombreponente=$p['nombrep'];
    $S_apellidos=$p['apellidos'];
    $S_status=$p['STATUS'];
    $S_mail=$p['mail'];
    mysql_free_result($result);
    $PONENTE="$S_nombreponente $S_apellidos";
    $PONENCIA=$S_nombreponencia;
    $STATUS=$S_status;

    switch ($idstatus) 
    {
        // Detalles requeridos
	    case 2: $additional = "Your personal message \n\n";
            break;
        // Rechazada
	    case 3: $additional = "Your personal message \n\n";
            break;
        // Por aceptar
	    case 4: $additional = "Your personal message \n\n";
            break;
        // Aceptada
	    case 5: $additional = "Your personal message \n\n";
            break;
        // Cancelada
	    case 6: $additional = "Your personal message \n\n";
            break;
    }		
	/////////////////////
	// Envia el correo:
	/////////////////////
	$mail_user = $S_mail;
	$recipients = $mail_user;

	$headers["From"]    = "$general_mail";
	$headers["To"]      = $mail_user;
	$headers["Subject"] = "Revision de propuesta enviada, $conference_name";
    $message ="
Hola $PONENTE

El motivo de este correo (generado automaticamente) es notificarte que el Comite Academico reviso la propuesta de ponencia que enviaste con el titulo:

\"$PONENCIA\"

El estado actual de esta ponencia es:

*$STATUS*

$additional

Cualquier comentario, por favor dirgelo al remitente de este correo,
$general_mail

Muchas gracias por tu participacion!

------------------------------
Comite Academico
$conference_name
$conference_link
";
	$params["host"] = $smtp; 
	$params["port"] = "25";
	$params["auth"] = false;
    if (isset($additional)) {
        // Added a verification to check if SEND_MAIL constant is enable patux@patux.net
        // TODO:
        // We need to wrap a function in include/lib.php to send emails in a generic way
        // This function must validate if SEND_MAIL is enable or disable
        if (SEND_MAIL == 1) // If is enable we will send the mail
        {
	        // Create the mail object using the Mail::factory method
	        $mail_object =& Mail::factory("smtp", $params);
	        $mail_object->send($recipients, $headers, $message);
        }
    }
	$regresar='Location: '.$regresa;
	header($regresar);
?>
