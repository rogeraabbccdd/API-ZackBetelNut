<?php
  require_once "./config.php";

  require "vendor/autoload.php";

  use TheIconic\Tracking\GoogleAnalytics\Analytics;

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  
  $analytics = new Analytics(true);

  $analytics
      ->setProtocolVersion('1')
      ->setTrackingId($gaid)
      ->setClientId($_SERVER['REQUEST_TIME'])
      ->setIpOverride($_SERVER['REMOTE_ADDR']);

  $analytics->sendPageview();

  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8",$db_user,$db_password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  if(isset($_GET["randimg"])) {
    $analytics->setDocumentPath('/index.php?randimg');
    
    $sql = "SELECT image FROM {$db_table} ORDER BY RAND() LIMIT 1";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $imgurl = $sth->fetchAll()[0][0];
    
    header("location:".$imgurl);
  }
  elseif(isset($_GET["rand"])) {
    $analytics->setDocumentPath('/index.php?rand');
    
    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT * FROM {$db_table} ORDER BY RAND() LIMIT 1";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $row = $sth->fetchAll()[0];

    $data = array(
      "post_id" => $row['post_id'],
      "image" => $row['image'],
      "description" => $row['description'],
      "link" => $row['link'],
      "timestamp" => $row['timestamp']
    );
    echo json_encode($data);
  }
  else {
    $analytics->setDocumentPath('/index.php');

    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT * FROM {$db_table} ORDER BY timestamp DESC";
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
        "timestamp" => $row['timestamp']
      ));
    }

    echo json_encode($data);
  }

  $analytics->sendPageview();
?>