<?php  
	include "../includes/lib.php";
	include "../includes/conf.inc.php";
	imprimeEncabezado();
	
    #imprimeCajaTop("100","Lista de propuestas enviadas");
?>
    <center><font face='Arial, Helvetica, sans-serif' size=6>Lista de propuestas enviadas</font></center>
<?php     
	print '<hr>';

$link=conectaBD();
$QSquery = 'SELECT * FROM prop_status ORDER BY ID'; 
$resultQS=mysql_query($QSquery);
$indice=0;
while ($QSfila=mysql_fetch_array($resultQS)) {
	$stat_array[$indice]['id']=$QSfila['id'];
	$stat_array[$indice]['descr']=$QSfila['descr'];
	$indice++;
}
mysql_free_result($resultQS);

//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados ni esten programados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP ='	SELECT 	P.id AS id_ponencia, 
			P.nombre AS ponencia,
			P.id_prop_tipo, 
			P.id_ponente, 
			PO.nombrep, 
			PO.apellidos, 
			PT.descr AS prop_tipo,
			S.descr AS status 
		FROM 	propuesta AS P, 
			ponente AS PO, 
			prop_status AS S,
			prop_tipo AS PT
		WHERE 	P.id_ponente=PO.id AND 
			P.id_status=S.id AND 
			P.id_prop_tipo=PT.id AND
			id_status != 7 
		ORDER BY P.id_prop_tipo,P.id_ponente,P.reg_time';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar propuestas".mysql_errno($userRecordsP));
// Inicio datos de Ponencias
print '
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><font color=white><b>Ponencia</b></font></td><td bgcolor='.$colortitle.'><font color=white><b>Tipo</b></font>
	</td><td bgcolor='.$colortitle.'><font color=white><b>Status</b></font></td>
	</tr>';
	

	$color=1;
    $propuestas=0;
	while ($fila = mysql_fetch_array($userRecordsP))
	{
		if ($color==1) 
		{
			$bgcolor=$color_renglon1;
			$color=2;
		}
		else 
		{
			$bgcolor=$color_renglon2;
			$color=1;
		}
		print '<tr>
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vponencia.php?vopc='.$fila['id_ponente'].' '.$fila['id_ponencia'].' '.$_SERVER['REQUEST_URI'].'">'.$fila["ponencia"].'</a>';
		 print '<br><small><a class="ponente" href="Vponente.php?vopc='.$fila['id_ponente'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['nombrep'].' '.$fila['apellidos'].'</a></small>';
		
	
		print '</td><td bgcolor='.$bgcolor.'>';
		print $fila['prop_tipo'];
		print '</td><td bgcolor='.$bgcolor.'>';
		print '<small>'.$fila['status'].'</small>';
	
		// Una vez que la ponencia fue aceptada (id 5)
		// La ponencia no se le puede modificar el status ni eliminar 
		// A menos que sea el administrador principal 
		print '</td>';
		print '</tr>';
        $propuestas++;
		
	}
    retorno();
    print '<tr><td align=right>';
?>
    <font face='Arial, Helvetica, sans-serif' size=4><?php echo $propuestas ?> propuestas enviadas</font></center>
<?php    
    print '</td></tr>';
	print '</table>';
    retorno();
    retorno();
?>
<center><input type="button" value="Regresar" onClick=location.href="../"></center>


<?php 	imprimePie(); ?> 
