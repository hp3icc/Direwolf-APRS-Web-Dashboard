<?php
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

include 'initialize.php';

report_aprsisserver($aprsisserverip);

include('menu.php');
?>
<div class="page">
<center>
<div>
<BR>
<?php
if (strlen($aprsisserverip)>0) {
	echo('Direwolf is currently connected to APRS-IS server ');
	echo('<b><a href="http://'.$aprsisserverip.':'.$aprsiswebserverport.'" target="_new">'.$aprsisserverip.'</a></b><BR><BR>');

	// object in HTTP can only be displayed on page when page itself is also loaded via HTTP
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=="on") {
		echo('Since'. $dashboarddescription .'is opened in <B>HTTPS</B> and since the APRS-IS webserver only supports <B>HTTP</B>,<BR><BR>');
		echo('the APRS-IS Server Page cannot be opened as an object on this page and has to be opened manually by clicking on the IP address.');
	} else {
		echo('<object class="aprsisobject" data="http://'. $aprsisserverip.':'.$aprsiswebserverport .'"></object></center>');
	}
} else {
	echo('Direwolf is currenty not connected to any APRS-IS server');
}
?>
</center>
</div>
</body>
</html>
