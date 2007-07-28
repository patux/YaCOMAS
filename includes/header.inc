<?
include "conf.inc";
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
        <title>Yacomas (Yet Another Conference Management System)</title>
        <meta HTTP-EQUIV="Pragma" content="no-cache">
        <meta NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
        <meta HTTP-EQUIV="Content-Language" CONTENT="ES">
        <meta HTTP-EQUIV="Content-Type" content="text/html; charset=ISO-8859-1">
        <meta NAME="description" CONTENT="Yacomas (Yet Another Conference Management System)">
        <meta NAME="keywords" CONTENT="YACOMAS,yacomas,festival,software,libre,software libre,festival de software libre,2004,FSL,linux,gnu,gpl,openbsd,freebsd,netbsd,gnu/linux">
        <meta NAME="author" CONTENT="Patux)">
        <meta NAME="copyright" CONTENT="Copyrigth Geronimo Orozco (Patux)">
        <meta NAME="audience" CONTENT="All">
        <meta NAME="distribution" content="Global">
        <meta NAME="rating" content="General">
        <meta HTTP-EQUIV="Reply-to" CONTENT="patux@glo.org.mx">
        <meta NAME="revisit-after" CONTENT="1 days">
        <link rel="SHORTCUT ICON" href="'.$rootpath.'/images/favicon.ico"> 
        <link rel="icon" href="'.$rootpath.'/images/favicon.ico"">

</head>

<link rel="stylesheet" href="'.$rootpath.'/style1.css" type="text/css">

';
//print ' <body bgcolor="#f1f8ff" link="#a6a141"> ';
print ' <body onLoad="putFocus(0,0);" bgcolor="#d2e0ee" link="#a6a141"> ';

/*print '
<div id="top" onClick="javascript:window.location=index.php;">
        <div id="cintilla">
	<script src="
	http://cintilla.rednacional.org.mx/cintilla?tema=simple&limite=5
	">
	</script>
	<noscript>
        	<a href="http://www.rnsl.org">RNSL</a>
	</noscript>
        </div>
</div>
'; 
*/
/*
<script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="FSL Development Team!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("alert(message);return false")

// --> 
</script>

</head>
*/
?>
