<?php
/********* SIMPLE PHP POLL v1.0 ************ 
Copyright 2004, Cgixp Team 
You have to leave the copyright link. 
E-mail: mali75850@yahoo.com
Website: http://www.cgixp.tk
**********************************************/ 
$link=conectaBD();
// seleccionar la encuesta y la bd
$id_encuesta=1;
$id_encuesta=1;
$QuestionQuery= 'SELECT pregunta FROM encuesta WHERE id="'.$id_encuesta.'"';
$resultQuestion=mysql_query($QuestionQuery);
$Qfila=mysql_fetch_array($resultQuestion);
$QUESTION = $Qfila["pregunta"]; 
mysql_free_result($resultQuestion);
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);
function print_top_Table($long) {
	if (!$long)
		echo "<TABLE  align=center border=1 borderColor=#001199 cellPadding=2 class=normaltext
	style=\"BORDER-BOTTOM-COLOR: #001199; BORDER-COLLAPSE: collapse; BORDER-LEFT-COLOR: #001199; 
	BORDER-RIGHT-COLOR: #001199; BORDER-TOP-COLOR: #001199\" >\n";
	else
		echo "<TABLE  align=center border=1 borderColor=#001199 cellPadding=2 
		class=normaltext style=\"BORDER-BOTTOM-COLOR: #001199; 
		BORDER-COLLAPSE: collapse; BORDER-LEFT-COLOR: #001199; 
		BORDER-RIGHT-COLOR: #001199; BORDER-TOP-COLOR: #001199\" width=".$long."%>\n";
		
}
function print_graph($encuesta,$pregunta) {
	// Sacamos el total de votos que lleva la encuesta
	$VotosEncuesta= 'SELECT count(*) AS tot_votos
			 FROM encuesta_voto 
			WHERE	id_encuesta="'.$encuesta.'"
			';
	$resultEncuesta=mysql_query($VotosEncuesta);
	$Qfila0=mysql_fetch_array($resultEncuesta);
	$tot=$Qfila0['tot_votos'];
	mysql_free_result($resultEncuesta);
	////////////////////////////////////////
	print '<p class="yacomas_msg">'.$pregunta.'</p>';
	echo "<tr><th>Opciones</th><th>Porcentaje</th><th>Votos</th></tr>";
	$tp=0;

	$OpcionQuery= 'SELECT id,opcion FROM encuesta_opc WHERE id_encuesta="'.$encuesta.'"';
	$resultOpcion=mysql_query($OpcionQuery);
	$Nopciones=mysql_num_rows($resultOpcion);
	while($Qfila=mysql_fetch_array($resultOpcion)){
		$VotosOpcion= 'SELECT count(*) AS tot_opc
				 FROM encuesta_voto 
				 WHERE id_opcion="'.$Qfila['id'].'" AND
					id_encuesta="'.$encuesta.'"
				';
		$resultVotos=mysql_query($VotosOpcion);
		$Qfila2=mysql_fetch_array($resultVotos);
		if ($tot == 0)
			$percent = 0;
		else
			$percent =  $Qfila2['tot_opc'] * 100 / $tot;
		$percent_int = floor($percent);
		$percent_float = number_format($percent, 1);
		$tp += $percent_float;
		if($percent_int>=75){
			$color="blue";
		}
		elseif($percent_int>=50){
			$color="green";
		}
		elseif($percent_int>=25){
			$color="orange";
		}
		elseif($percent_int<25){
			$color="red";
		}
		$votosA=$Qfila2['tot_opc'];
		echo "<tr><td>".$Qfila['opcion']."</td><td>
		<table cellpadding=1 cellspacing=0 width=100% border=0 bgcolor=black>
		<tr><td><table cellpadding=0 cellspacing=0 border=0 width=100%>";
		echo "<tr>
		<td bgcolor=$color width=$percent_int% height=10 style=border:0 >
		<spacer type=block width=2 height=8>
		</td>
		<td bgcolor=white width=91% height=10 style=border:0 >
		<spacer type=block width=2 height=8>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>$percent_float%</td><td>$votosA</td></tr>";

	}
 	mysql_free_result($resultOpcion);
	$tv=$tot;
	echo "<td>Total Votos: $tv</td><td>&nbsp;</td><td>&nbsp;</td></TABLE>";

}
// Aqui inicia el programa
// Verificar que ese usuario no haya votado ya anteriormente
$VotoQuery= '	SELECT id_encuesta,id_asistente 
		FROM 	encuesta_voto 
		WHERE 	id_encuesta="'.$id_encuesta.'" AND
		id_asistente="'.$idasistente.'"
		';
$resultVoto=mysql_query($VotoQuery);
$registros=mysql_num_rows($resultVoto);
retorno();
if ($registros > 0 ) {
	echo "<font color=red size=1><div align=center>Tu ya votaste anteriormente</div></font>";
	print_top_Table('');
	print_graph($id_encuesta,$QUESTION);
}
else {
	if (empty ($_POST['answer']) && !empty($_POST['vote']))
		print '<p class="yacomas_error">Por favor seleccione una opcion</p>';
	if (!empty ($_POST['result'])) {
		print_top_Table('');
		print_graph($id_encuesta,$QUESTION);
	}
	if (empty($_POST['answer']) && empty($_POST['result'])){
		print_Top_Table('');
		echo '<FORM METHOD="POST" action="'.$_SERVER['REQUEST_URI'].'">';
		echo "<TR><TH>$QUESTION</TH></TR>";
		$OpcionQuery= 'SELECT id,opcion FROM encuesta_opc WHERE id_encuesta="'.$id_encuesta.'"';
		$resultOpcion=mysql_query($OpcionQuery);
		while($Qfila=mysql_fetch_array($resultOpcion)){
			echo '<TR><TD align="left">
			<INPUT TYPE="radio" NAME="answer" VALUE="'.$Qfila['id'].'">'.$Qfila['opcion'].'</TD></TR>';
		}
		mysql_free_result($resultOpcion);
		echo "</TABLE>";
		echo "<center>";
		echo '<INPUT TYPE="Submit" NAME="vote" VALUE=" Votar ">';
		echo '
		<INPUT TYPE="Submit" NAME="result" VALUE="Resultados"></form>';
		echo "</center>";
	}
	if (!empty ($_POST['answer']) && !empty($_POST['vote'])){
		$InsertVotoQ='	INSERT INTO encuesta_voto 
				(id_encuesta,id_opcion,id_asistente) 	
				VALUES ('.$id_encuesta.','.$_POST['answer'].','.$idasistente.')';
		$result = mysql_query($InsertVotoQ) or err("No se puede insertar el voto".mysql_errno($result));
		print '<p class="yacomas_error">Voto guardado</p>';
		print_top_Table('');
		print_graph($id_encuesta,$QUESTION);
	}
}
?>
