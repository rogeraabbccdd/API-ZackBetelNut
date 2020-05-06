<?php
  require_once "./config.php";

  require "vendor/autoload.php";

  use TheIconic\Tracking\GoogleAnalytics\Analytics;

  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  
  $analytics = new Analytics(true);

  $analytics
      ->setProtocolVersion('1')
      ->setTrackingId($gaid)
      ->setClientId($_SERVER['REQUEST_TIME'])
      ->setDocumentPath('/index.php')
      ->setIpOverride($_SERVER['REMOTE_ADDR']);

  $analytics->sendPageview();

  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8",$db_user,$db_password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  $sql = "SELECT * FROM {$db_table}";
  $sth = $pdo->prepare($sql);
  $sth->execute();
  $result = $sth->fetchAll();

  $data = array();
  foreach($result as $row) {
    array_push($data, array(
      "post_id" => $row['post_id'],
      "image" => $row['image'],
      "description" => $row['description'],
      "link" => $row['link'],
      "timestamp " => $row['timestamp']
    ));
  }
  echo json_encode($data);
?>