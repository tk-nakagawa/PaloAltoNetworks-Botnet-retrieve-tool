<?php
class Logging {
  function log($msgout, $display=0) {
    $fp=fopen("./log/system.log", "a+");
      $logtime = date('Y-m-d H:i:s');
      fwrite($fp, "$logtime	$msgout \n");
    fclose($fp);
    if ($display == 1){
      echo "$msgout <br>";
    }
  }
}
?>