<?php
  require_once "./config.php";

  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  
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
      "link" => $row['link']
    ));
  }
  echo json_encode($data);
?>