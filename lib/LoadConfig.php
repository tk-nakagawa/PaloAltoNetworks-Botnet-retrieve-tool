<?php
$pa_ipaddress = array();
$admin_user = array();
$password = array();
$cl = array();
$cfgerr = 0;

$fp=fopen("config.txt", "r");
  while ($line = fgets($fp)) {
    if (strpos("$line", "PA IPaddress:") !==false) {
      $line = str_replace("PA IPaddress:", "", $line); 
      $pa_ipaddress[] = trim($line);
    }
    if (strpos("$line", "XML-API admin:") !==false) {
      $line = str_replace("XML-API admin:", "", $line);
      $admin_user[] = trim($line);
    }
    if (strpos("$line", "XML-API password:") !==false) {
      $line = str_replace("XML-API password:", "", $line); 
      $password[] = trim($line);
    }
    if (strpos("$line", "Botnet Report Filter Level:") !==false) {
      $line = str_replace("Botnet Report Filter Level:", "", $line);
      if (($line <= 0) or ($line > 5)) {
        $line = NULL;
      }
      $cl[] = trim($line);
    }
  }
fclose($fp);

for ($i=0; $i<= count($cl)-1; $i++) {
  $j = $i+1;
  $config[$i] = array($pa_ipaddress[$i], $admin_user[$i], $password[$i], $cl[$i]);
  if(in_array("", $config[$i], true)) {
    $msgout = "Error!! Firewall Configuration: $j is something wrong";
    Logging::log($msgout, $display);
    $cfgerr=1;
  }
  else {
    $msgout ="Successfully Firewall Configuration: $j was loaded";
    Logging::log($msgout, $display);
  }
}

if ($cfgerr==1){ 
  $msgout = "Error!! Firewall Configuration Error";
  Logging::log($msgout, $display);
  die; 
} 
else {
  $msgout = "Successfully Configuration \"config.txt\" was loaded";
  Logging::log($msgout, $display);
}

?>