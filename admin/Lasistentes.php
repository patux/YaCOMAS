<? 
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
imprimeEncabezado();

$link=conectaBD();
$idadmin=$_SESSION['YACOMASVARS']['rootid'];
$userQueryCA = 'SELECT 	count(*) AS totAsistentes FROM asistente'; 
$userRecordsCA = mysql_query($userQueryCA) or err("No se pudo listar Total de  Asistentes".mysql_errno($userRecordsCA));
$fila1=mysql_fetch_array ($userRecordsCA);
$I_totAsistentes=$fila1['totAsistentes'];
mysql_free_result ($userRecordsCA);
define("REG_POR_PAGINA",100);
if (empty($_POST['desde'])) {
	$desde=0;
}
$userQueryA = 'SELECT 	A.login,A.id, A.nombrep, A.apellidos, 
			A.reg_time,  E.descr AS estado,  
			ES.descr AS estudios 
		FROM 	asistente AS A, estado AS E, 
			estudios AS ES 
		WHERE 	A.id_estado=E.id AND 
			A.id_estudios=ES.id 
		ORDER BY A.id, A.reg_time
		LIMIT '.$desde.','.REG_POR_PAGINA;
//		ORDER BY A.apellidos';
//		ORDER BY A.id,A.reg_time';
//print $userQueryA;
//retorno();
$userRecordsA = mysql_query($userQueryA) or err("No se pudo listar Asistentes".mysql_errno($userRecordsA));
print '<P class="yacomas_login">Login: '.$_SESSION['YACOMASVARS']['rootlogin'].'&nbsp;<a class="precaucion" href=signout.php>Desconectarme</a></P>';
imprimeCajaTop("100","Listado de Asistentes registrados");
print '<b>'.$I_totAsistentes.' Asistentes Registrados </b>';
retorno();
print '<b>Asistentes: ';
print $desde+1;
print ' a ';
if (($desde+REG_POR_PAGINA) < $I_totAsistentes) 
	print $desde+REG_POR_PAGINA;
else
	print $I_totAsistentes;
print '</b>';
retorno();
retorno();
?>
	<!-- Flechas de página -->
	<center>
	<?   
	print '<INPUT type="hidden" name="desde" value="0">';
	if( $I_totAsistentes > REG_POR_PAGINA ){
		if( $desde > 0 ){   ?>
			<a href="" onmouseover="window.status='Primera p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=0; marcarFrm.submit(); return false;"  
			onmouseout="window.status='';return true;"><img src="../images/first.gif" border=0></a>
			<a href="" onmouseover="window.status='P&aacute;gina anterior'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? print $desde - REG_POR_PAGINA; ?>;
			marcarFrm.submit(); return false;" onmouseout="window.status=''; return true;"><img src="../images/prev.gif" border=0></a>
		<?       }  ?>
<?        	if( ($I_totAsistentes-$desde) > REG_POR_PAGINA ) {   ?>
			<a href="" onmouseover="window.status='Siguiente p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? print $desde + REG_POR_PAGINA; ?>; 
			marcarFrm.submit(); return false;" onmouseout="window.status=''; return true;"><img src="../images/next.gif" border=0></a>
			<a href="" onmouseover="window.status='&Uacute;ltima p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? 
							if ($I_totAsistentes > REG_POR_PAGINA)
								echo floor(($I_totAsistentes/REG_POR_PAGINA)) * 100; 
							else
								echo 0;
							?>; marcarFrm.submit(); 
			return false;"><img src="../images/last.gif" onmouseout="window.status=''; return true;" border=0></a>
		<?      }   
	   }  
	?>
	</center>
	<?
print'
	<table border=0 align=center width=100%>
	<tr>
	<td bgcolor='.$colortitle.'><b>Nombre</b></td>
	<td bgcolor='.$colortitle.'><b>Login</b></td>
	<td bgcolor='.$colortitle.'><b>Estado</b></td>
	<td bgcolor='.$colortitle.'><b>Estudios</b></td>
	<td bgcolor='.$colortitle.'><b>Registro</b></td>';
	if ($_SESSION['YACOMASVARS']['rootlevel']==1)
		print '	<td bgcolor='.$colortitle.'><b>&nbsp;</b></td>';
	print '
	</tr>';
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsA))
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
		<td bgcolor='.$bgcolor.'><a class="azul" href="Vasistente.php?vopc='.$fila['id'].' '.$_SERVER['REQUEST_URI'].'">'.$fila['apellidos'].' '.$fila['nombrep'].'</td>
		<td bgcolor='.$bgcolor.'>'.$fila['login'].'
		<td bgcolor='.$bgcolor.'>'.$fila['estado'];
		
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['estudios'];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila['reg_time'].'</td>';
		if ($_SESSION['YACOMASVARS']['rootlevel']==1)
			print '<td bgcolor='.$bgcolor.'><a class="precaucion" href="Basistente.php?idasistente='.$fila['id'].'">Eliminar</td>';
		print '</tr>';
	}
	print '</table>';
	?>
	<!-- Flechas de página -->
	<center>
	<?   
	print '<FORM name="marcarFrm" action="'.$_SERVER['REQUEST_URI'].'" method="POST">';
	print '<INPUT type="hidden" name="desde" value="0">';
	if( $I_totAsistentes > REG_POR_PAGINA ){
		if( $desde > 0 ){   ?>
			<a href="" onmouseover="window.status='Primera p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=0; marcarFrm.submit(); return false;"  
			onmouseout="window.status='';return true;"><img src="../images/first.gif" border=0></a>
			<a href="" onmouseover="window.status='P&aacute;gina anterior'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? print $desde - REG_POR_PAGINA; ?>;
			marcarFrm.submit(); return false;" onmouseout="window.status=''; return true;"><img src="../images/prev.gif" border=0></a>
		<?       }  ?>
<?        	if( ($I_totAsistentes-$desde) > REG_POR_PAGINA ) {   ?>
			<a href="" onmouseover="window.status='Siguiente p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? print $desde + REG_POR_PAGINA; ?>; 
			marcarFrm.submit(); return false;" onmouseout="window.status=''; return true;"><img src="../images/next.gif" border=0></a>
			<a href="" onmouseover="window.status='&Uacute;ltima p&aacute;gina'; return true;"
			onClick="document.forms[0].elements['desde'].value=<? 
							if ($I_totAsistentes > REG_POR_PAGINA)
								echo floor(($I_totAsistentes/REG_POR_PAGINA)) * 100; 
							else
								echo 0;
							?>; marcarFrm.submit(); 
			return false;"><img src="../images/last.gif" onmouseout="window.status=''; return true;" border=0></a>
		<?      }   
	   }  
	?>
	</form>
	</center>
	<?
	print '<center>
	<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/admin/menuadmin.php#ponencias">
	</center>';
imprimeCajaBottom();
IMPRImePie();?>
