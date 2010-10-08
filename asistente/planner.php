<?php
include "../includes/lib.php";
include "../includes/conf.inc.php";
include "../includes/Planner.inc.php";

$link = conectaBD();

$fechaQueryE = 'SELECT * FROM fecha_evento ORDER BY fecha';
$fechaRecords = mysql_query($fechaQueryE) or err("No se pudo listar fechas de eventos ".mysql_errno($fechaRecords));

imprimeEncabezado();

$msg="Programa oficial de ponencias $conference_name
  <br><small><small>*Programa sujeto a cambios*</small></small>";
print '<center><h1>'.$msg.'</h1></center>';
print '<p class="yacomas_msg">Para ver informacion adicional de la ponencia o del ponente haz click en cualquiera de ellos</a><hr>';
retorno();

$Planner = new Planner($link);
$Planner->load();
echo $Planner->createLegend();
retorno();
$dates = $Planner->getDates();
foreach ($dates AS $date_id => $date) {
  print '<center>';
  print '<H1>'.strftime_caste("%A %d de %B",strtotime($date['date'])).'</H1>';
  if (!empty($date['descr']))
    print '<H3> Dia de: '.$date['descr'].'</H3>';
  print '</center>';
  echo $Planner->createCalendar($date_id);
}
retorno();
echo $Planner->createLegend();
retorno();
retorno();
print '<center>';
print '<input type="button" value="Volver al menu" onClick=location.href="'.$fslpath.$rootpath.'/asistente/menuasistente.php">
</center>';
retorno();
retorno();
imprimePie();
?>
