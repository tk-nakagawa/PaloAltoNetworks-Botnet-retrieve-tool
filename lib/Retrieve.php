<?php
$xmldata = ""; $response = "";

if (($key =="") or ($nwerr==1)) {
  $nwerr=1;
  $msgout ="Error!! Skip retreive Botnet report, due to API key error for Firewall: $pa_ipaddress[$i] or Network problem ";
  Logging::log($msgout, $display);
}
else {

//$url="http://$pa_ipaddress[$i]/api/?type=report&reporttype=predefined&reportname=botnet&?key=$key";
$url="https://$pa_ipaddress[$i]/api/?type=report&reporttype=predefined&reportname=botnet&?key=$key";

$option = array(
  "timeout" => 3 // HTTP request timeout (s)
);

require_once "HTTP/Request.php";
$http = new HTTP_Request($url, $option);
$response = $http->sendRequest();  // send HTTP request to the targeted PA
if (!PEAR::isError($response)) { 
  $code = $http->getResponseCode();
  $response= $http->getResponseBody();
}

$xml = <<<XML
  $response
XML;
$xmldata = new SimpleXMLElement($xml);

if (!isset($code)) { // Not receive HTTP respose case
  $nwerr=1;
  $msgout ="Not received any responses from $pa_ipaddress[$i]";
  Logging::log($msgout, $display);
}
else { // Receive HTTP response case 
  if ($code == "200") { // HTTP response code:200
    $date=date('Y-m-d');
    $archivefile="archive/$date"."_$serial.txt";
    $filename="filtered.txt";
    $fp1=fopen("$archivefile", "a+"); 
    $fp2=fopen("$filename", "a+"); 

    if (!isset($xmldata->result->entry)){
      fwrite($fp1, "No data Recorded \n");
    }
    else {
      foreach ($xmldata->result->entry as $data) { 
        if (isset($data->confidence)) {
          if ($data->confidence >= $cl[$i]) {
            fwrite($fp1, "Confidence Level: $data->confidence \n");
            fwrite($fp2, "Confidence Level: $data->confidence \n");
            fwrite($fp1, "Source Address: $data->src \n");
            fwrite($fp2, "Source Address: $data->src \n");
            fwrite($fp1, "Source User: $data->srcuser \n");
            fwrite($fp2, "Source User: $data->srcuser \n");
            fwrite($fp1, "Vsys: $data->vsys \n");
            fwrite($fp2, "Vsys: $data->vsys \n");
            fwrite($fp1, "Description: $data->description \n");
            fwrite($fp2, "Description: $data->description \n");
            fwrite($fp1, "\n");
            fwrite($fp2, "\n");
            $flag1 = 1;
          }
          elseif ($data->confidence < $cl[$i]) {
            fwrite($fp1, "Confidence Level: $data->confidence \n");
            fwrite($fp1, "Source Address: $data->src \n");
            fwrite($fp1, "Source User: $data->srcuser \n");
            fwrite($fp1, "Vsys: $data->vsys \n");
            fwrite($fp1, "Description: $data->description \n");
            fwrite($fp1, "\n");
            $flag2 = 1;
          }
        }
      } // end of foreach loop
    }
    fclose($fp2);
    $msgout ="Successfully retreive Botnet report from Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
    fclose($fp1);
    Logging::log("Successfully archive Botnet report from Firewall: $pa_ipaddress[$i]", $display);
  } // end of HTTP response code:200
  else { // HTTP response code: except(200)
    $nwerr=1;
    $msgout ="Error!! HTTP response code:$code, failed to retreive botnet report from Firewall: $pa_ipaddress[$i]";
    Logging::log($msgout, $display);
  }	// end of HTTP response code: except(200)
} //end of HTTP response exists

} // end of else
?>
