<?php
  include("security.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
  </head>

  <body>
    <noscript>Enable JavaScript</noscript>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      "use strict";
      var sent = false;
      function messageType(){
        var textArea = document.getElementById("message");
        var length = textArea.value.length;
        var counter = document.getElementById("messageHelp");
        counter.innerText = length+"/160";
        if(length>=150){
          if(length<160){
            textArea.style.boxShadow = "0px 0px 10px rgb(244, 223, 66)";
          }else{
            textArea.style.boxShadow = "0px 0px 5px rgb(244, 20, 20)";
          }
        }else{
          textArea.style.boxShadow = null;
        }
        setButton();
      }

      function setButton(){
        var button = document.getElementById("button");
        var textArea = document.getElementById("message");
        if(numberIsValid() && !sent){
          if(textArea.value.length>0){
            button.style="background-color: #f29800; border-color: #f29800;";
            button.style.cursor = "pointer";
            return;
          }
        }
        button.style="background-color: grey;  border-color: grey;";
        button.style.cursor = "default";
      }

      function numberIsValid(){
        const regex = /^\+[0-9][0-9](\s*[0-9]){9}$/g;
        var numberField = document.getElementById("number");
        var number = numberField.value;
        return regex.test(number);
      }

      function numberType(){
        var numberField = document.getElementById("number");
        if(numberIsValid()){
          numberField.style.boxShadow = "0px 0px 5px rgb(10, 223, 10)";
        }else{
          numberField.style.boxShadow = "0px 0px 5px rgba(244, 20, 20, 0.4)";
        }
        setButton();
      }

      function send(){
        var textArea = document.getElementById("message");
        if(numberIsValid() && !sent && textArea.value.length>0){
          var number = document.getElementById("number").value;
          var message = document.getElementById("message").value;
          var data = new FormData();
          data.append('number', number);
          data.append('message', message);
          data.append('CSRF', '<?= $_SESSION['CSRF'] ?>');

          var xhr = new XMLHttpRequest();
          xhr.onload = function(){
            if(xhr.status==200){
              window.location.replace("list.php?sent=ok");
            }else{
              console.log(xhr.responseText);
              if(xhr.responseText.indexOf("<xml")){
                alert("Could not send SMS due to error on sms.php");
              }else{
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xhr.responseText,"text/xml");
                var errorTag = xmlDoc.getElementsByTagName("error")[0];
                var errorMessage = errorTag.getAttribute('type');
                alert("Error: "+errorMessage);
              }
              sent=false;
              setButton();
            }
         }
          xhr.onerror = function(){ alert ("Could not send SMS due to connectivity problem."); }
          xhr.open ("POST", "sms.php", true);
          xhr.send (data);
          sent = true; 
        }
        setButton();
      }
    </script>
    <br>
    <div style="padding-left: 30%; padding-right: 30%; padding-top: 20px;">
      <div style="clear: both;">
        <p style="float:right">
          <a href="list.php">History</a>
        </p>
        <p style="float:left">
          <?=  htmlspecialchars($_SERVER["HTTP_X_LOGIN_NAME"], ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </div>
    <br>
    <hr>
    <br>
    <form>
      <div class="form-group">
        <input type="text" autocomplete="off" class="form-control" id="number" name="number" aria-describedby="numberHelp" oninput="numberType()" autofocus value="+41 " onfocus="var temp_value=this.value; this.value=''; this.value=temp_value">
        <small id="numberHelp" class="form-text text-muted">Format: +41 79 123 45 67.</small>
      </div>
      <div class="form-group">
        <textarea class="form-control" autocomplete="off" rows="6" maxlength="160" id="message" name="message" oninput="messageType()" aria-describedby="messageHelp" placeholder="_"></textarea>
        <small id="messageHelp" class="form-text text-muted">0/160</small>
      </div>
      <button type="button" id="button" style="background-color: grey; border-color: grey; pointer: default;" onclick="send()" class="btn btn-primary">Submit</button>
    </form>
    </div>
  </body>
</html>
