<?
include "conf.inc.php";

print '
</td></tr></table><br><br>
<table border=0 cellpading=0 cellspacing=0 width=100%>
<!---<tr><td bgcolor='.$colorBorder.'>!--->
<tr><td bgcolor=#FFFFFF>
<table border=0 cellpading=1 cellspacing=1 width=100%>
<tr><td bgcolor=#FFFFFF>
<center>
<br>
<a href="http://yacomas.sourceforge.net/" target="_blank" title="Created with YACOMAS"><img title="Created with YACOMAS"
alt="GNU" src="'.$rootpath.'/images/buttons/yacomas-micro.png" border="0" /></a>
&nbsp;
<a href="http://www.php.net/" target="blank" title="Pumped through PHP"><img title="Pumped th/rough PHP"
alt="PHP" src="'.$rootpath.'/images/buttons/php.png" border="0" /></a>
&nbsp;
<a href="http://www.mysql.com/products/mysql/" target="blank" title="MySQL under the hood"><img title="MySQL under the hood"
alt="MySQL" src="'.$rootpath.'/images/buttons/mysql2.gif" border="0" /></a>
&nbsp;
<a href="http://www.gnu.org/" target="blank" title="Fueled by GNU software"><img title="Fueled by GNU software"
alt="GNU" src="'.$rootpath.'/images/buttons/gnu-powered.png" border="0" /></a>
<br>
<font face=arial size=-2>
';
if ( !empty ($copyright_author) ) {
    print "Copyrigth (c) $copyright_year, <a href=\"$link_author\">$copyright_author</a>";
}
print '
<br>
Powered by 
<a href="http://yacomas.sourceforge.net/" target="_blank">
Yacomas
</a>
-
<a href="mailto:patux@patux.net">Report a bug</a>
<br>
</font>
</center>
</td></tr>
</table>
</td></tr>
</table>
</body></html>';
?>
