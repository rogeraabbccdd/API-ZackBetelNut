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
      ->setDocumentPath('/index.php')
      ->setIpOverride($_SERVER['REMOTE_ADDR']);

  $analytics->sendPageview();

  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8",$db_user,$db_password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  if(isset($_GET["randimg"])) {
    header("Content-Type: image/jpeg");

    $sql = "SELECT image FROM {$db_table} ORDER BY RAND() LIMIT 1";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $imgurl = $sth->fetchAll()[0][0];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $imgurl);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
    $res = curl_exec($curl);
    $rescode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
    curl_close($curl) ;
    echo $res;
  }
  elseif(isset($_GET["rand"])) {
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
      "timestamp " => $row['timestamp']
    );
    echo json_encode($data);
  }
  else {
    header('Content-Type: application/json; charset=utf-8');

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
  }
?>