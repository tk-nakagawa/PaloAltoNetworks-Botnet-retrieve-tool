<?php
date_default_timezone_set('Asia/Tokyo'); // timezone setting for log timestamp
$display=0; // $display=1 ->display logs, $display=0 -> not display logs

if (!is_dir('archive/')){ exec ('mkdir archive'); }
if (!is_dir('log/')){ exec ('mkdir log'); }

include ('lib/Logging.php');
include ('lib/test_LoadConfig.php');


for ($i=0; $i<= count($cl)-1; $i++) {
  $starttime = microtime(true);
  $nwerr=0; $flag1 = 0; $flag2 = 0; 
  include ('lib/KeyGen.php');
  include ('lib/ShowSystemInfo.php');
  include ('lib/Retrieve.php');
  include ('lib/Output.php');
  include ('lib/Mailout.php');
  
  exec ("rm filtered.txt"); // for Mac
//  exec ("del filtered.txt"); // for Windows

  $endtime = microtime(true) - $starttime;
  if ($endtime <= 1.0) {
    usleep(500000);
    $endtime = microtime(true) - $starttime;
  }
  $msgout = "Elapse Time : $endtime (s)";
  Logging::log($msgout, $display);
}


?>
