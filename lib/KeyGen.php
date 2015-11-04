<?php
$key = ""; $tmp = ""; $response ="";
//$url = "http://$pa_ipaddress[$i]/api/?type=keygen&user=$admin_user[$i]&password=$password[$i]"; // for debug
$url = "https://$pa_ipaddress[$i]/api/?type=keygen&user=$admin_user[$i]&password=$password[$i]";

$option = array(
  "timeout" => 3 // HTTP request timeout (s)
);
require_once "HTTP/Request.php"; 
$http = new HTTP_Request($url, $option); // initialize HTTP_Request and build HTTP request for keygen
$response = $http->sendRequest(); // send HTTP request to retrive the API key

if (!PEAR::isError($response)) { 
  $code = $http->getResponseCode();
  $response = $http->getResponseBody();
}

if ($response == "Operation timed out"){
  $nwerr=1;
  $msgout = "Error!! TCP time-out occurs when accessing to Firewall: $pa_ipaddress[$i], possible network problem...";
  Logging::log($msgout, $display);
}
elseif ($response == "Connection refused"){
  $nwerr=1;
  $msgout = "Error!! TCP:RST received from Firewall: $pa_ipaddress[$i] ";
  Logging::log($msgout, $display);
}
else {
$tmp = <<<XML
  $response
XML;

if (!isset($code)) {
  $msgout = "Not received any response from Firewall: $pa_ipaddress[$i], possible network problem... or Firewall does not respond XML-API";
  Logging::log($msgout, $display);
}   //end of $code non-exist case
else {
  if ($code == "200") {
    $tmp = new SimpleXMLElement($tmp);
    $status = $tmp['status'];
    if ($status == "success") {
      $msg = $tmp->result->key;
      $key = strip_tags($msg, '');
      $msgout ="Successfully API key is generated for Firewall: $pa_ipaddress[$i]";
      Logging::log($msgout, $display);
    } 
    elseif ($status == "error"){
      $msg = $tmp->result->msg;
      $msgout ="Error!! Error message: \"$msg\" from Firewall: $pa_ipaddress[$i] ";
      Logging::log($msgout, $display);
    }
  }   // end of $code "200" check 
  else {
    $msgout ="Error!! HTTP response code: $code, failed to retreive API key from Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  }   // end of except $code "200" check
}   // end of $code exist case
} 
?>