<?php 
include "conf.inc.php";
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
        <title>'.$conference_name.'</title>
        <meta HTTP-EQUIV="Pragma" content="no-cache">
        <meta NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
        <meta HTTP-EQUIV="Content-Language" CONTENT="ES">
        <meta HTTP-EQUIV="Content-Type" content="text/html; charset=ISO-8859-1">
        <meta NAME="description" CONTENT="'.$conference_name.'">
        <meta NAME="keywords" CONTENT="Hispalinux,hipalinux,festival,software,libre,software libre,festival de software libre,2010,FSL,linux,gnu,gpl,openbsd,freebsd,netbsd,gnu/linux">
        <meta NAME="author" CONTENT="Patux)">
        <meta NAME="copyright" CONTENT="Copyrigth Geronimo Orozco (Patux)">
        <meta NAME="audience" CONTENT="All">
        <meta NAME="distribution" content="Global">
        <meta NAME="rating" content="General">
        <meta HTTP-EQUIV="Reply-to" CONTENT="'.$general_mail.'">
        <meta NAME="revisit-after" CONTENT="1 days">
        <link rel="SHORTCUT ICON" href="'.$rootpath.'/images/favicon.ico"> 
        <link rel="icon" href="'.$rootpath.'/images/favicon.ico"">';
		?><script type="text/javascript" src="<?php echo $rootpath.'/includes/functions.js' ?>"></script><?php
if( defined('TO_ROOT') ) {
	global $xajax;
	if( isset($xajax) ) {
		$xajax->printJavascript(TO_ROOT."/vendors/xajax/");
	}
}
print '</head>

<link rel="stylesheet" href="'.$rootpath.'/style1.css" type="text/css">

';
print ' <body bgcolor="'.$bgcolor.'" link="#a6a141" onload="focus_on_first()"> ';
?>
