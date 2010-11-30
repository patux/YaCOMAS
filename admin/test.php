<?php
define('TO_ROOT', '..');
include TO_ROOT . "/includes/conf.inc.php";
include TO_ROOT. "/includes/xajax_server.php";

$DbConnection = DbConnection::getInstance();

$sql = "SELECT * FROM evento;";
$eventos = $DbConnection->getAll($sql); 
?>
<html>
<head>
<?php $xajax->printJavascript(); ?>
</head>
<body>
<div id="target"></div>
<ul>
<?php foreach($eventos AS $evento)
	echo '<li><a href="#" onclick="xajax_test(\''.$evento['id'].'\')">Evento '.$evento['id'].'</a></li>';
?>
</ul>
</body>
</html>