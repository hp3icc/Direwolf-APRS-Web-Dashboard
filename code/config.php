<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

// for header.php: two stylesheets are provided by default: stylesheet1.css (white style) and stylesheet2.css (green style)
$stylesheet = "stylesheet1.css";

// for index.php and igate.php: time zone for time/date display and calculations
$timezone = "Europe/Amsterdam";

// for all pages with dynamic content: refresh rate of dynamic content in ms; should probably not go below 5000
$refresh = 5000;

// for header.php and about.php: displays description in title of all pages
$dashboarddescription = "Direwolf APRS Web Dashboard";

// for header.php and about.php: displays version of the APRS dashboard web interface
$dashboardversion = "20250314 beta";

// for system.php: if direwolf version cannot be determined via installed apt package, manually enter direwolf version here
$direwolfversion = "1.7";

// for system.php: displays link to QRZ page of system operator
$sysopcallsign = "yourcallsign";

// for system.php: website URL for displaying information about clients connected
$clientiplookuphost = "https://www.iplocation.net/?query=";

// for aprsis.php and for system.php: IP Port of APRS-IS server that is used by Direwolf, default = 14580
$aprsisserverport = 14580;

// for aprsis.php: IP port of APRS-IS status information web interface, default = 14501
$aprsiswebserverport = 14501;

// for functions.php: path of the direwolf log directory, without file name
$logpath = "/var/log/direwolf/";

// for functions.php: log file name, the standard Y-m-d.log file name (generated every day by direwolf) will be used if empty
$fixedlogname = "";

//for functions.php: station position data lat/long in decimal degrees for calculating distance from received station
$stationlat = 52.00000;
$stationlon = 04.00000;

// for chgif.php: interface indexes as declared in direwolf.conf file, don't skip intermediate non-defined interface indexes
$interfaces = array(0,1,2,3,4,5,6,7,8,9,10);

// for chgif.php: one interface description per defined interface index, unused intermediate interfaces should be added, empty descriptions will not be displayed in list
$intdesc = array("RTL-SDR","","Shari SA818","","ICOM IC-9700","","AIOC","","Jabra MS40","","ICOM IC-7300");

// for initialize.php: when 1 do not show interface selection at startup
$static_if = 1;

// for initialize.php: direwolf interface number that will be initially shown when $static_if=1
$static_if_index = 0;

// for menu.php: logo path,with file name, shown on the top of the page
$logourl = "direwolflogo.png";

// for index.php: displays distance in miles when 1 or in km when 0
$miles = 0; 

// for traffic.php: amount of last rows to be displayed when starting traffic monitorr
$startrows = 10;

// for traffic.php: show traffic from all channels instead of only traffic of the selected channel
$displayallchannels = 1;

// for igate.php: timeout in seconds for status to change from running to not running
$timeout = 3600;

// for igate.php: stations to observe, declare with callsign and SSID if applicable
$stationsquery = "PE4KH-10,PI1DRV,PA1JRN-10,PD3RFR-10,PD0CL-10,PA1RBZ,PI1UTR-13,PD2BAS";

// for igate.php: SysOps for observed stations, one sysop per station
$sysops = "PE4KH,N0CALL,PA1JRN,PD3RFR,PD0CL,PA1RBZ,PI1UTR,PD2BAS";

// for igate.php: API key from aprs.fi for retrieving last heard beacon information
$apikey = "xxxxxxxxxxxxxxxxxxxxxxxx";

// for viewer.php: directory to Direwolf console logfile
$consolelog = "/var/log/direwolf/console.log";

// for viewer.php: display or hide timestamp announcement of updates of console logfile, default 1
$display_datestamps = 1;

// for viewer.php: max bytes to read from console logfile during first and each next update cycle, default 10000
$maxreadbytes = 10000;

// for initialize.php: indicates which files use which kind of AJAX upate: html or append, values should not be changed
$ajaxupdatehtml = array("index.php","system.php","frames.php","igate.php");
$ajaxupdateappend = array("viewer.php","traffic.php");

/*
Example startup command for Direwolf with console output redirection to console.log file: /usr/bin/direwolf -c /etc/direwolf.conf -l /var/log/direwolf -daknpwtoihfxd - > /var/log/direwolf/console.log

Depending on debug options turned on, console.log can become very big over time! Restarting direwolf empties console.log so implementing scheduled restarts and/or limiting debug options is suggested

Direwolf debug options (from Direwolf manual):

    -d x   Debug options.  Specify one or more of the following in place of x.
                     a = AGWPE network protocol client.
                     k = KISS serial port client.
                     n = Network KISS client.
                     u = Display non-ASCII text in hexadecimal.
                     p = Packet dump in hexadecimal.
                     g = GPS interface.
                     W = Waypoints for position or object reports.
                     t = Tracker beacon.
                     o = Output controls such as PTT and DCD.
                     i = IGate
                     h = Hamlib verbose level.  Repeat for more.
                     m = Monitor heard station list.
                     f = Packet filtering.
                     x = FX.25 increase verbose level.
*/

?>
