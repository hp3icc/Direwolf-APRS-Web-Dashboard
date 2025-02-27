<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

include 'initialize.php';

if (isset($_SESSION['timevalue'])) $timevalue = $_SESSION['timevalue'];
$time = 0; //start of the time from which to read data from log in Unix timestamp type

if(!isset($timevalue) or ($timevalue == "")) { //if time range not specified
	$time = 0;
        } elseif($timevalue == "e") { //if whole log
        	$time = 0;
        } else { //else if the time range is choosen
        	$time = time() - ($timevalue * 3600); //convert hours to seconds
}

$receivedstations = array();
$staticstations = array();
$movingstations = array();
$otherstations = array();
$directstations = array(); //stations received directly
$viastations = array(); //stations received via digi
$lines = 0;
$framesoninterface = 0;
$linesinlog = count($logfile);

if ($miles==0): $distanceunit="(km)"; else: $distanceunit="(miles)"; endif;

while ($lines < $linesinlog) { // read line by line
	$line = $logfile[$lines];
        stationparse($line); // build received stations table
        $lines++;
}

uasort($receivedstations, 'cmp');

if(isset($_GET['ajax'])) {

	if ($fixedlogname=="") echo("<a href='?daysback=".($_SESSION['daysback']+1)."'>Earlier</a> | <a href='?daysback=0'>	Today</a> | <a href='?daysback=".($_SESSION['daysback']-1)."'>Later</a> | ");
        echo('<B>'.(max(count($logfile)-1,0)).'</B> frames in logfile: <B>'.$newlogname.'</B> | <B>'.$framesoninterface.'</B> frames on Interface <B>'.$intdesc[$if].'</B><BR>');
	echo('<script src="table-sort.min.js"></script><BR>');

	    if(count($logfile)>0) { 
		echo('<table class="normaltable indextable table-sort table-arrows"><thead><tr>');
		echo('<th>Station</b></th><th>Frames</b></th><th>Position</b></th><th>Received via</b></th><th class="onload-sort order-by-desc">Last Heard</b></th><th>Distance '.$distanceunit.'</b></th><th>Bearing</b></th></tr></thead>');
		echo('<tbody>');
		foreach($receivedstations as $c=>$nm) {
			echo('<tr>');
			echo('<td class="station"><a href="https://aprs.fi/?call='.$c.'" target="_blank">'.$c.'</a></td>');
			echo('<td class="frames"><a href="frames.php?getcall='.$c.'">'.$nm[0].'</a>');

			echo('</td><td>');

                        if (in_array($c, $staticstations)) echo '<div class="static">STATIC</div>';
                        elseif (in_array($c, $movingstations)) echo '<div class="moving">MOVING</div>';
                        else echo "OTHER";

			echo('</td><td>');

                        if ((in_array($c, $directstations))&&(in_array($c, $viastations))) echo '<div class="digidirect">DIGI+DIRECT</div>';
                        elseif (in_array($c, $directstations)) echo '<div class="direct">DIRECT</div>';
                        else if (in_array($c, $viastations)) echo '<div class="digi">DIGI</div>';

                      	echo('</td><td>');

	                $date = new DateTimeImmutable();
	                $date = $date->setTimestamp($nm[1]);
        	        $date = $date->setTimezone(new DateTimeZone($timezone));
                	echo($date->format('H:i:s d-m-Y'));

			echo('</td><td>');

			if (isset($nm[2])) {
                       		if ($miles == 0) { echo(number_format($nm[2],2)); } else { echo(number_format($nm[2]*0.6214,2)); }
			} else {
                        	echo("N/A");
			}

			echo('</td><td>');

 	                if (isset($nm[3])) { echo $nm[3]."   "; } else { echo("N/A"); }

			echo('</td></tr>');
		}
                echo('</tbody>');
		echo('</table>');
	} else {
		echo('Logfile is empty or does not exist');
	}
	exit();
}
include('menu.php');
?>
<div class="page">
<center>
<?php
        echo('<form action="index.php" method="GET">');
        echo('<input id="refresh" name="refresh" type="checkbox" checked> Refresh every <B>'.$refresh.' msec</B> | ');
        echo('Limit view to time frame: <select name="time">');
        ?>
        <option value="1"  <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 1))  echo 'selected="selected"'?>>last hour</option>
        <option value="2"  <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 2))  echo 'selected="selected"'?>>last 2 hours</option>
        <option value="4"  <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 4))  echo 'selected="selected"'?>>last 4 hours</option>
        <option value="6"  <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 6))  echo 'selected="selected"'?>>last 6 hours</option>
        <option value="12" <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 12)) echo 'selected="selected"'?>>last 12 hours</option>
        <option value="e"  <?php if(isset($_SESSION['timevalue'])&&($_SESSION['timevalue'] == 'e') or (!isset($_SESSION['timevalue']))) echo 'selected="selected"'?>>Whole day</option>
        <?php
        echo('</select> ');
        echo('<input type="submit" value="Refresh"></form><BR>');
?>
<div id="ajaxcontent"></div>
</center>
</div>
</body>
</html>
