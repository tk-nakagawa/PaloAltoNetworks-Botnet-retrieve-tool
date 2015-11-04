<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
//header( "Content-Type: text/html; Charset=utf-8" );
//header( "pragma: no-cache" );
//header( "Cache-control: no-cache" );

require_once("Mail.php");
require_once("Mail/mime.php");
mb_language("ja");
mb_internal_encoding("UTF-8");

$debug = false; // true => show debugging messages for SMTP communication (true or false)
$params = array(   // SMTP setting 
  "host"    => "smtp_server_IP_or_FQDN", // smtp server
  "port"    => "smtp_port",              // smtp port
  "auth"    => true,                     // authentication on smtp server
  "username"  => "user_account",         // user accout on smtp server
  "password"  => "password",             // password for user account
  //"debug"   => $debug                  // debug smtp communication
);

$sender = "sender_email_address";
$recipients = "recipients_email_address"; 
// recipients' address (use , as delimiter for multiple recipients)
// [sample]  $recipients = "user1@abc.co.jp, user2@abc.cp.jp";


// email header setting 
if ($key =="") {
  $subject = "Fail to retrive Botnet Report [".date('Y-m-d')."]" ;
}
else {
  $subject = "Botnet Report Summary: $hostname [".date('Y-m-d')."]" ;
}

$subject = mb_encode_mimeheader( mb_convert_encoding($subject,"iso-2022-jp") );
$to =  mb_encode_mimeheader( mb_convert_encoding("","iso-2022-jp") ) . " <{$recipients}>";
$from =  mb_encode_mimeheader( mb_convert_encoding("","iso-2022-jp") ) . " <{$sender}>";

$headers = array(
  "To"    => $to,
  "From"    => $from,
  "Subject" => $subject
);

if ($key=="") {
  // email body for Network Problem
  $body = "------------------------------------------------- \n";
  $body .= "ip address: $pa_ipaddress[$i] \n ";
  $body .= "------------------------------------------------- \n";
  $body .= "Result: $msgout \n \n";
}
else {
  // email body for retrieve Botnet report
  $body = "------------------------------------------------- \n";
  $body .= "hostname: $hostname \n ";
  $body .= "ip address: $pa_ipaddress[$i] \n ";
  $body .= "serial: $serial \n ";
  $body .= "sw version: $sw_version  \n ";
  $body .= "------------------------------------------------- \n";
  $body .= "Result: $msgout \n \n";
  $chkfile = "./filtered.txt";
  if (file_exists($chkfile)) {
    $body .= file_get_contents('filtered.txt', FILE_USE_INCLUDE_PATH);
  }
} // end of else

$body = mb_convert_encoding($body,"iso-2022-jp");

$cnt=0;
do {
  $smtp = Mail::factory("smtp", $params);
  $result = $smtp->send($recipients, $headers, $body);
  if (PEAR::isError($result)) {
    $error = $result->getMessage();
    $msgout2 = "Error!! Botnet Summary Report Mail fail to send : $error";
    Logging::log($msgout2, $display);
    $cnt++;
    if ($cnt!==3){
      $msgout2 = "Botnet Summary Report Mail fail to send : $cnt time, retry to send ";
      Logging::log($msgout2, $display);
      usleep(1000000);
    }
    elseif ($cnt==3){
      $msgout2 = "Botnet Summary Report Mail fail to send : $cnt time ";
      Logging::log($msgout2, $display);
    }
  }
  else {
    if ($nwerr==0) {
      $msgout2 = "Botnet Summary Report Mail was sent";
      Logging::log($msgout2, $display);
      break;
    }
    elseif ($nwerr==1) {
      $msgout2 = "Botnet Report Error Mail was sent";
      Logging::log($msgout2, $display);
      break;
    }
  }
} while ($cnt<=2);
?>