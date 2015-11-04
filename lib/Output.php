<?php
if ($nwerr == 0) {
  if ($flag1 == 1) {
    $msgout = "Botnet is detected and filtered with the Configured Filter Level: $cl[$i], Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  } 
  elseif (($flag1 == 0) && ($flag2 == 1)) {
    $msgout = "Botnet is detected but Confidence Level is lower than the Configured Filter Level: $cl[$i], Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  }
  elseif (($flag1 == 0) && ($flag2 == 0)) {
    $msgout = "Botnet is not reported on the previous day, Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  }
}
elseif ($nwerr == 1){
  $msgout = "Error!! Fail to Access Firewall: $pa_ipaddress[$i], due to Network problem or API key error";
  Logging::log($msgout, $display);
}
?>
