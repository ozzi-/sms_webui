<?php
  class SentDB extends SQLite3{
    function __construct(){
      $this->open('sent.db');
    }
  }

  function openDB(){
    $db = new SentDB();
    $sqlCreateIfNotExists = "CREATE TABLE IF NOT EXISTS sent (id integer primary key asc autoincrement, number string, message string, loginid string, date string)";
    $db->exec($sqlCreateIfNotExists);
    return $db;
  }

  function getSent($db){
    $sql = "SELECT * FROM sent ORDER BY date DESC;";
    $smt = $db->prepare($sql);
    $res = $smt->execute();
    return $res;
  }

  function addSent($db,$number,$message,$loginid){
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO sent (number,message,date,loginid) VALUES (:number,:message,:date,:loginid);";
    $smt = $db->prepare($sql);
    $smt->bindValue(":number", $number);
    $smt->bindValue(":loginid", $loginid);
    $smt->bindValue(":message", $message);
    $smt->bindValue(":date", $date);
    $smt->execute();
  }
?>