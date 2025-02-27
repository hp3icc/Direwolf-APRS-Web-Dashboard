<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

include 'initialize.php';

// example output of 'netstat -n | grep 14580' ==> 'tcp        0      0 192.168.30.127:49122    85.90.180.26:14580      ESTABLISHED'
$aprsisserver = shell_exec('netstat -n | grep '.$aprsisserverport.' | grep ESTABLISHED | head -n 1 | tr -s " " | cut -f 5 -d " " | cut -f 1 -d ":" | tr -d "\n"');
$aprsisserverandport = $aprsisserver.':'.$aprsiswebserverport;

include('menu.php');
?>
<div class="page">
<center>
<div>
<BR>
Direwolf is currently connected to APRS-IS server 
<b><a href="http://<?php echo $aprsisserverandport ?>" target="_new"><?php echo $aprsisserver ?></a></b><BR><BR>
<?php
// object in HTTP can only be displayed on page when page itself is also loaded via HTTP
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=="on") { ?>
	Since <?php echo $dashboarddescription ?> is opened in <B>HTTPS</B> and since the APRS-IS webserver only supports <B>HTTP</B>,<BR><BR>
	the APRS-IS Server Page cannot be opened as an object on this page and has to be opened manually by clicking on the IP address.
<?php } else { ?>
	<object class="aprsisobject" data="http://<?php echo $aprsisserverandport ?>"></object></center>
<?php } ?>
</center>
</div>
</body>
</html>
