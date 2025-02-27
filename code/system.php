<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

include 'initialize.php';

if(isset($_GET['ajax'])) {

        $sysver      = NULL;
        $kernelver   = NULL;
        $direwolfver = NULL;
        $cputemp     = NULL;
        $cpufreq     = NULL;
        $uptime      = NULL;
        $direwolfsts = NULL;
        $sysver = shell_exec ("cat /etc/os-release | grep PRETTY_NAME |cut -d '=' -f 2");
        $kernelver = shell_exec ("uname -r");
	if ($direwolfversion=="") {
	        $direwolfver = shell_exec ("apt-cache policy direwolf | grep -m 1 'Installed' | cut -d ' ' -f 4");
	} else {
		$direwolfver = $direwolfversion;
	}
        if (file_exists ("/sys/class/thermal/thermal_zone0/temp")) {
         	exec("cat /sys/class/thermal/thermal_zone0/temp", $cputemp);
             	$cputemp = $cputemp[0] / 1000;
        }
        if (file_exists ("/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq")) {
                	exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq", $cpufreq);
        	       	$cpufreq = $cpufreq[0] / 1000;
        }
        $uptime = shell_exec('uptime -p');
        $direwolfsts = shell_exec('systemctl is-active direwolf');

	$clientips = array_unique(explode("\n",substr(shell_exec('/usr/bin/ss -t -a | grep ESTAB | tr -s " " | cut -f 2 -d ":" | grep http | cut -f 2 -d " "'),0,-1)));
	$clientipshtml="";
	foreach ($clientips as $clientip) { $clientipshtml.='<a href="'.$clientiplookuphost.$clientip.'" target="_new">'.$clientip."</a><br>"; }

	$memoryinfo = str_getcsv(shell_exec('/usr/bin/free -m | grep "Mem:" | tr -s " "')," ");

	$aprsisserverip = shell_exec('netstat -n | grep '.$aprsisserverport.' | grep ESTABLISHED | head -n 1 | tr -s " " | cut -f 5 -d " " | cut -f 1 -d ":" | tr -d "\n"');

	echo('<br><center><table class="normaltable systemtable"><tbody>');
	echo('<tr><th colspan="2">System Status</th></tr>');
	echo('<tr><td><b>Operating System</b></td><td>'.$sysver.'</td></tr>');
	echo('<tr><td><b>Kernel Version</b></td><td>'.$kernelver.'</td></tr>');
	echo('<tr><td><b>Direwolf Version</b></td><td>'.$direwolfver.'</td></tr>');
	echo('<tr><td><b>System Uptime</b></td><td>'.$uptime.'</td></tr>');
	echo('<tr><td><b>CPU Temperature</b></td><td>'.$cputemp.' C </td></tr>');
	echo('<tr><td><b>CPU Frequency</b></td><td>'.$cpufreq.' MHz </td></tr>');
	echo('<tr><td><b>CPU Utilization</b></td><td>'.utilcpu().'% </td></tr>');
	echo('<tr><td><b>Memory Usage</b></td><td>'.$memoryinfo[2].'MB of total '.$memoryinfo[1].' MB </td></tr>');
	echo('<tr><td><B>Connected APRS-IS server</b></td><td><a href="http://'.$aprsisserverip.':14501" target="_new">'.$aprsisserverip.'</a></td></tr>');
	echo('<tr><td><b>Direwolf Status</b></td><td>');
	if(str_starts_with($direwolfsts, "active")) echo('<span class="running">Running</span>'); else echo('<span class="notrunning">Not running</span>');
	echo('<tr><td><b>iGate Operator</b></td><td class="station"><a href="https://qrz.com/db/'.$sysopcallsign.'" target="_new">'.$sysopcallsign.'</a></td></tr>');
	echo('<tr><td><b>Dashboard Version</b></td><td>'.$dashboardversion.'</td></tr>');
        echo('<tr><td><b>Dashboard Users</b></td><td>'.$clientipshtml.'</td></tr>');
        echo('</td></tr></tbody></table><BR><BR>');
	exit();
}
include('menu.php');
?>
<div class="page">
<center>
<div id="ajaxcontent"><BR><BR><B><I>Please wait while first System Status is being prepared</I></B></div>
<BR><BR>
<?php if (!str_contains($logourl,"direwolflogo.png")) echo('<img src="direwolflogo.png" width=100>'); ?>
</center>
</div>
</body>
</html>
