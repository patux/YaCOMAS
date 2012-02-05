<?php  
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryCP = 'SELECT 	count(*) AS totPagos FROM pago'; 
$userRecordsCP = mysql_query($userQueryCP) or err("No se pudo listar Total de Pagos".mysql_errno($userRecordsCP));
$fila1=mysql_fetch_array ($userRecordsCP);
$I_totPagos=$fila1['totPagos'];
mysql_free_result ($userRecordsCP);
$userQueryP = '	SELECT 	P.id, concat(A.nombrep,\' \', A.apellidos) AS nombre, P.id_responsable, P.tpago, P.monto_cuotas,P.monto_hospedaje, P.monto_neto, P.porc_descuento, P.fecha_pago, P.pagado 
				FROM 	pago AS P, 
				asistente AS A
				WHERE 	P.id_responsable=A.id
				ORDER BY P.id';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar Pagos".mysql_errno($userRecordsP));

print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de Recibos Generados");
print '<b>'.$I_totPagos.' Recibos </b>';
retorno();
retorno();
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Pago</b></td>
	<td bgcolor='.$colortitle.'><b>Responsable</b></td>
	<td bgcolor='.$colortitle.'><b>Tipo</b></td>
	<td bgcolor='.$colortitle.'><b>Inscripcion</b></td>
    	<td bgcolor='.$colortitle.'><b>Descuento</b></td>
	<td bgcolor='.$colortitle.'><b>Hospedaje</b></td>
	<td bgcolor='.$colortitle.'><b>Total</b></td>
    	<td bgcolor='.$colortitle.'><b>Fecha</b></td>
    	<td bgcolor='.$colortitle.'><b>¿Pagado?</b></td>
	</tr>';
	$color=1;
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
		$total = $fila['monto_neto'] + ($fila['monto_cuotas'] -($fila['monto_cuotas']*$fila['porc_descuento']/100));
        if ($fila['pagado'] == 0) {
           $pagado = "No pagado";  
           $pago_color = "yellow";
        }
        else {
            $pagado = "Pagado";
           $pago_color = $bgcolor;
        }
	if ($total == 0) {
           $total_color= "yellow";
        }
        else {
           $total_color = $bgcolor;
        }

		print '<tr>
		<td align="right" bgcolor='.$bgcolor.'>'.$fila['id'].'</td>
		<td bgcolor='.$bgcolor.'><a class="azul" href="admin.php?opc=21&tab=1&id='.$fila['id_responsable'].'">'.$fila['nombre'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['tpago'].'</td>
  		<td align="right" bgcolor='.$bgcolor.'>'.number_format($fila['monto_cuotas'],2).'</td>
      	<td align="right" bgcolor='.$bgcolor.'>'.$fila['porc_descuento'].'%</td>
  		<td align="right" bgcolor='.$bgcolor.'>'.number_format($fila['monto_neto'],2).'</td>
      	<td align="right" bgcolor='.$total_color.'>'.number_format($total,2).'</td>
      	<td bgcolor='.$bgcolor.'>'.$fila['fecha_pago'].'</td>
      	<td bgcolor='.$pago_color.'>'.$pagado.'</td>
		</tr>';
	}
	print '</table>';
	retorno();
	retorno();
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
	</center>';
imprimeCajaBottom();
imprimePie();?>
