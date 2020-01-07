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
    <style>
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
      }
      td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
      }
      tr:nth-child(even) {
        background-color: rgba(220,220,220,0.3);
      }
    </style>
  </head>
  <body>
    <?php
      include("db.php");
      $db = openDB();
      $sent = getSent($db);
    ?>
    <noscript>Enable JavaScript</noscript>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <br>
    <div style="padding-left: 15%; padding-right: 15%; padding-top: 20px;">
    <a href="index.php">Send SMS</a><br><br>
    <table style="table-layout: fixed">
      <tr><th>Date</th><th>Recipient</th><th>Sender</th><th>Message</th></tr>
      <?php
      $i=0;
      while ($row = $sent->fetchArray()) {
        if( $i==0 && isset($_GET["sent"]) ){
          echo("<tr style=\"background-color: #f29800;\">");
        }else{
          echo("<tr>");
        }
        echo("<td>".htmlspecialchars($row["date"], ENT_QUOTES, 'UTF-8')."</td><td>".htmlspecialchars($row["number"], ENT_QUOTES, 'UTF-8')."</td><td>".htmlspecialchars($row["loginid"], ENT_QUOTES, 'UTF-8')."</td><td style=\"word-wrap: break-word;\">".htmlspecialchars($row["message"], ENT_QUOTES, 'UTF-8')."</td></tr>");
        $i++;
      }
      ?>
    </table>
    </div>
  </body>
</html>