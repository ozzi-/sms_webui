<?php

  include("security.php");
  include("db.php");

  $db = openDB();

  if( isset($_POST["number"]) && isset($_POST["message"]) && isset($_POST["CSRF"]) && hash_equals($_POST["CSRF"],$_SESSION["CSRF"]) ){
    $url = "https://______/sms/xml";
    $username = "____";
    $pw = "_____";
    $number = str_replace(' ','',$_POST["number"]);
    $message = htmlspecialchars($_POST["message"], ENT_XML1);

    $request = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
                <SMSBoxXMLRequest>
                  <username>".$username."</username>
                  <password>".$pw."</password>
                  <command>WEBSEND</command>
                  <parameters>
                  <receiver>".$number."</receiver>
                  <service>______</service>
                  <text>".$message."</text>
                  <guessOperator/>
                  </parameters>
                </SMSBoxXMLRequest>";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    if(!$result){
      echo("Error occured:".curl_error($ch));
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      http_response_code($httpcode);
      die();
    }
    curl_close($ch);

    if (strpos(strtolower($result), "status=\"ok\"") !== false) {
      http_response_code(200);
      addSent($db,$number,$message,$_SERVER["HTTP_X_LOGIN_NAME"]);
      die("sent");
    }else{
      http_response_code(409);
      echo($result);
    }
  }else{
    http_response_code(400);
    die("nope");
  }
?>
