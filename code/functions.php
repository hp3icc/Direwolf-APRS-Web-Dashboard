<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

/* log structure:
0:chan
1:utime
2:isotime
3:source
4:heard
5:level
6:error
7:dti
8:name
9:symbol
10:latitude
11:longitude
12:speed
13:course
14:altitude
15:frequency
16:offset
17:tone
18:system
19:status
20:telemetry
21:comment
*/

function cmp($a, $b) { // custom sorting function
	if ($a[1] == $b[1]) {
		return 0;
	}
	return ($a[1] > $b[1]) ? -1 : 1;
}

function utilcpu() {
        $cpu1 = str_getcsv(shell_exec("cat /proc/stat | grep 'cpu '")," ",escape: "\\");
        sleep(2); // wait for two seconds to create difference in cpu utilization metrics
        $cpu2 = str_getcsv(shell_exec("cat /proc/stat | grep 'cpu '")," ",escape: "\\");
	// idle = idle + iowait
	// busy = user + nice + system + irq + softirq + steal
        $idle1 = $cpu1[5]+$cpu1[6];
        $busy1 = $cpu1[2]+$cpu1[3]+$cpu1[4]+$cpu1[7]+$cpu1[8]+$cpu1[9];
        $idle2 = $cpu2[5]+$cpu2[6];
        $busy2 = $cpu2[2]+$cpu2[3]+$cpu2[4]+$cpu2[7]+$cpu2[8]+$cpu2[9];
        $idledelta  = $idle2 - $idle1;
        $totaldelta = ($busy2+$idle2) - ($busy1+$idle1);
        return (intval(100 - ($idledelta / $totaldelta) *100));
}

function initializelog() { // function for initializing logfile
	global $logpath; //input
        global $logname; //input
	global $fixedlogname; //input
	global $newlogname; //output
	global $logfile; //output
        global $log; //output
	if (!isset($_SESSION['daysback'])) { $_SESSION['daysback']=0; } // go to today
        if ($fixedlogname!="") {
                $log=$logpath.$fixedlogname;
        } else { // change to new logfile and if not exists return empty array
		$daysback = date("d") - $_SESSION['daysback'];
		$newlogname=date("Y-m-d",mktime(0,0,0,NULL,NULL+$daysback,NULL)).'.log';
		$log=$logpath.$newlogname;
		if (is_file($log)) $logfile = file($log); else $logfile = [];
        }
}

function stationparse($frame) { //function for parsing station information
	global $stationcall;
	global $receivedstations;
	global $staticstations;
	global $movingstations;
	global $otherstations;
	global $viastations; //stations received via digi
	global $directstations; //stations received directly
	global $callraw;
	global $time;
	global $distance;
	global $bearing;
	global $if;
	global $framesoninterface;

	if($frame[0]==$if) //if frame received on selected radio interface
	{
		$framesoninterface++;
		$frame=str_getcsv($frame,",",escape: "\\");
		$utime = $frame[1];
		if($utime > $time) { //if frame was received in time range
			$stationcall = strtoupper($frame[8]);
			if(array_key_exists($stationcall, $receivedstations)) { //if this callsign is already on stations list
				$receivedstations[$stationcall][0]++; //increment the number of frames from this station
			} else { //if this callsign is not on the list
				$receivedstations[$stationcall][0] = 1; //add callsign to the list
			}
			$receivedstations[$stationcall][1] = $utime; //add last time
	        	if(($frame[10] !=="") and ($frame[11] !== "")) { //if it's a frame with position
				haversine($frame);
				$receivedstations[$stationcall][2] = $distance; //add last distance
				$receivedstations[$stationcall][3] = $bearing; //add last bearing
			}
			if($frame[12]==NULL) { //if speed is not null, it's a static station
				if(!in_array($stationcall, $staticstations)) {
					$staticstations[] = $stationcall;
				}
			} else {
				$movingstations[] = $stationcall;
			}
		}

		if($frame[3]==$frame[4]) { //if source=heard condition, the frame was heard directly
			if(!in_array($stationcall, $directstations)) {
				$directstations[] = $stationcall;
			}
		} else {
			if(!in_array($stationcall, $viastations)) {
				$viastations[] = $stationcall;
			}
		return;
		}
	} //closes if received on seleceted interface
}

function haversine($frame) { // function for calculating distance between own and remote stations
	global $stationlat;
	global $stationlon;
	global $distance;
	global $bearing;
	global $declat;
	global $declon;

	$declat = $frame[10];
	$declon = $frame[11];

	//haversine formula for distance calculation
	$latFrom = deg2rad($stationlat);
	$lonFrom = deg2rad($stationlon);
	$latTo = deg2rad($declat);
	$lonTo = deg2rad($declon);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$bearing = rad2deg(atan2(sin($lonDelta)*cos($latTo), cos($latFrom)*sin($latTo)-sin($latFrom)*cos($latTo)*cos($latDelta)));
	if($bearing < 0) $bearing += 360;
	$bearing = round($bearing, 1);

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
	cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	$distance = round($angle * 6371, 2); //gives result in km rounded to 2 digits after comma
}

function secondsToTime($seconds) { // converts an amount of seconds to a time difference
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%d:%H:%I:%S');
}

?>
