<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

include 'initialize.php';

if(isset($_GET['if']) and ($_GET['if'] !== "")) {
	// chgif.php is called to change interface
	$_SESSION['if'] = strip_tags(substr($_GET['if'],0,2)); // substr and strip_tags are used to get rid of any malicious input
	if (!isset($_SESSION['urlreferer']) or str_contains($_SESSION['urlreferer'],"chgif.php")) { $_SESSION['urlreferer']="index.php"; } // if no referer or referer is chgif.php then redirect to index.php
	header('Refresh: 0; url='.$_SESSION['urlreferer']);
} else {
	// chgif.php is called to display available interfaces
	include('menu.php');
	echo('<div class="page"><center><b>Select the Direwolf Radio Interface to be viewed</b><br><br><br>');
	if (isset($_SERVER['HTTP_REFERER'])) $_SESSION['urlreferer']=$_SERVER['HTTP_REFERER'];
	echo('<form action="chgif.php" method="get">Interface: <select name="if">');
	$i=0;
	for ($i=0;$i<=sizeof($interfaces)-1;$i++) {
		if ($intdesc[$i]!="") {
			if ($_SESSION['if']==$i) { 
				echo("<option selected value=".$interfaces[$i].">".$interfaces[$i]." - ".$intdesc[$i]." (currently selected)</option>");
			} else {
				echo("<option value=".$interfaces[$i].">".$interfaces[$i]." - ".$intdesc[$i]."</option>");
			}
		}
	}
	echo('</select><br><br><br><input type="submit" value="OK"></form>');
	echo('</center></div></body></html>');
}
?>
