<?php
$tmp=""; $response="";

if (($key =="") or ($nwerr==1)) {
  $nwerr=1;
  $msgout ="Error!! Skip SN check, due to API key error for Firewall: $pa_ipaddress[$i] or Network problem";
  Logging::log($msgout, $display);
}
else {

//$url = "http://$pa_ipaddress[$i]/api/?type=op&cmd=<show><system><info></info></system></show>&key=$key"; // for debug
$url = "https://$pa_ipaddress[$i]/api/?type=op&cmd=<show><system><info></info></system></show>&key=$key"; // for debug

$option = array(
  "timeout" => 3 // HTTP request timeout (s)
);

require_once "HTTP/Request.php";
$http = new HTTP_Request($url, $option);
$response = $http->sendRequest();

if (!PEAR::isError($response)) { 
    $code = $http->getResponseCode();
    $response = $http->getResponseBody();
} 

$tmp = <<<XML
  $response
XML;

if (!isset($code)) { // Not receive HTTP respose case
  $nwerr=1;
  $msgout ="Not received any responses from $pa_ipaddress[$i]";
  Logging::log($msgout, $display);
}
else { // Receive HTTP response case 
  if ($code == "200") { // HTTP response code:200
    $tmp = new SimpleXMLElement($tmp);
    $status = $tmp['status'];
    if ($status == "success") {
      foreach ($tmp->result->system as $data) { 
        $serial = $data->serial;
        $serial = strip_tags($serial, '');
        $sw_version = $data->{'sw-version'};
        $sw_version = strip_tags($sw_version, '');
        $hostname = $data->hostname;
        $hostname = strip_tags($hostname, '');
      } // end of foreach loop
      $msgout ="Successfully retrieve SN from Firewall: $pa_ipaddress[$i]";
      Logging::log($msgout, $display);
    }
    elseif ($status == "error") {
      $code = $tmp['code'];
      $msg = $tmp->result->msg;
      $msgout ="Error!! Error message: \"$msg\" from Firewall: $pa_ipaddress[$i]";
      Logging::log($msgout, $display);
    }
  }   // end of $code "200" check 
  else {
    $msgout ="Error!! HTTP response code:$code, failed to retreive system info from Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  }   // end of except $code "200" check
}   // end of $code exist case


} // end of else
?>
