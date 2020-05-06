<?php
  require_once "./config.php";

  require "vendor/autoload.php";
  
  set_time_limit(1500);

  $ids = array();
  $data = array();

  // 業配、檳榔攤分享、看板分享、檳友投稿等非語錄貼文 ID
  $exclude = array(
    '2458246994414530', 
    '2457090317863531', 
    '2421476588091571', 
    '2394359680803262', 
    '2370425569863340', 
    '2362605340645363',
    '2353486984890532',
    '2352910948281469',
    '2351193805119850',
    '2315983945307503',
    '2292799774292587',
    '2280620762177155',
    '2271520886420476',
    '2260357407536824',
    '2209059532666612',
    '2196583540580878',
    '2183904365182129',
    '2099258640313369',
    '2098797907026109',
    '2086963604876206',
    '2076286465943920',
    '2055754431330457',
    '2043351549237412',
    '2039387296300504',
    '2021261871446380',
    '2007323282840239',
    '1959867964252438',
    '1917596298479605',
    '1900554653517103',
    '1881891808716721',
    '1840887456150490',
    '1832101967029039',
    '1824785084427394',
    '1805506889688547',
    '1745326559039914',
    '1740506859521884',
    '1717943755111528',
    '1716994251873145',
    '1675002726072298',
    '1670924666480104',
    '1670018469904057',
    '1665964670309437',
    '1658960634343174',
    '1657199454519292',
    '1649604181945486',
    '1647320885507149',
    '1645320979040473',
    '1640337826205455',
    '1640040152901889',
    '1639879172917987',
    '1637877609784810',
    '1637652049807366',
    '1636234946615743',
    '1635282646710973',
    '1634400546799183',
    '1633901333515771',
    '1632401933665711',
    '1630085627230675',
    '1630005293905375',
    '1629647300607841',
    '1628317817407456',
    '1627615330811038',
    '1626435857595652',
    '1624139534491951',
    '1622337031338868'
  );

  // get all photo id in album
  function fetchAlbum ($cursor) {
    global $ids;

    $headers = array(
      "accept-language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-CN;q=0.6,ja;q=0.5",
      "content-type: application/x-www-form-urlencoded",
      "sec-fetch-mode: cors",
      "sec-fetch-site: same-origin",
      "viewport-width: 277",
    );
    $var = json_encode(array(
      "count"=> 100,
      "cursor"=> $cursor,
      "albumID"=> 1620287888210449
    ));
    $postData = "variables={$var}&doc_id=2101400366588328";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, "https://www.facebook.com/api/graphql/");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    $result = curl_exec($curl);
    curl_close($curl);
    $arr = json_decode($result, true);
    foreach($arr['data']['album']['media']['edges'] as $value) {
      array_push($ids, $value['node']['id']);
    }
    if($arr['data']['album']['media']['page_info']['has_next_page']) fetchAlbum($arr['data']['album']['media']['page_info']['end_cursor']);
  }

  // get photo image and description
  function fetchPost () {
    global $ids;
    global $cookie;
    global $ajaxpipe_token;
    global $fb_dtsg_ag;
    global $user_id;
    global $data;

    $headers = array(
      "sec-fetch-mode: cors",
      "sec-fetch-site: same-origin",
      "viewport-width: 277",
      "referer: https://www.facebook.com/pg/caigezhuanyebinlangtan/photos/?tab=album&album_id=1620287888210449&ref=page_internal",
      "cookie: {$cookie}"
    );

    for($i=0;$i<count($ids);$i+=5) {
      $query = http_build_query(array (
        "ajaxpipe" => 1,
        "ajaxpipe_fetch_stream" => 1,
        "ajaxpipe_token" => $ajaxpipe_token,
        "no_script_path" => 1,
        "data" => "{\"set\" => \"a.1620287888210449\", \"type\" => \"3\", \"fbid\" => \"{$ids[$i]}\"}",
        "__user"=> $user_id,
        "__a"=> 1,
        "__adt"=> 2,
        "fb_dtsg_ag" => $fb_dtsg_ag,
      ));
      $query = urldecode($query);
      $query = str_replace(' => ', ':', $query);
      $query = str_replace('"', '%22', $query);
      $query = str_replace(' ', '', $query);
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36");
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_URL, "https://www.facebook.com/ajax/pagelet/generic.php/PhotoViewerInitPagelet?".$query);
      $result = curl_exec($curl);
      curl_close($curl);

      $result = explode("/*<!-- fetch-stream -->*/", $result);

      foreach($result as $r) {
        $r = json_decode($r, true);
        if (!isset($r['content']['payload']) || !isset($r['content']['payload']['jsmods']) || !isset($r['content']['payload']['jsmods']['require'])) {
          continue;
        }
        $require = $r['content']['payload']['jsmods']['require'];
        foreach($require as $rr) {
          if (isset($rr[1])) {
            if($rr[1] == 'storeFromData' && isset($rr[3][0]['image'])) {
              $image = $rr[3][0]['image'];
              foreach($image as $id => $img) {
                $data[$id]['img'] = $img['url'];
              }
            }
          }
        }
        if(isset($r['content']['payload']['jsmods']['markup'])) {
          $markup = $r['content']['payload']['jsmods']['markup'];
          foreach($markup as $m) {
            if(isset($m[1]['__html'])) {
              $dom = pQuery::parseStr($m[1]['__html']);
              $boxes = $dom->query('.snowliftPayloadRoot');
              if(count($boxes) > 0) {
                foreach($boxes as $box) {
                  $id = $box->query('.sendButton')[0]->href;
                  $id = explode("&amp;",$id)[2];
                  $id = substr($id, 3);

                  $data[$id]['time'] = $box->query('abbr')[0]->attributes['data-utime'];

                  if(count($box->query('.hasCaption')) > 0) {
                    $text = $box->query('.hasCaption')[0]->getPlainText();
                    $data[$id]['text'] = $text;
                  }
                  else {
                    $data[$id]['text'] = '';
                  }
                  
                  $data[$id]['post'] = "https://www.facebook.com/caigezhuanyebinlangtan/photos/a.1620287888210449/{$id}/?type=3&theate";
                }
              }
            }
          }
        }
      }
    }
  }

  function saveDB () {
    global $db_host;
    global $db_name;
    global $db_user;
    global $db_password;
    global $db_table;
    global $data;
    global $exclude;
    
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8",$db_user,$db_password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    foreach($data as $key => $value) {
      if(in_array($key, $exclude)) continue;

      $input = array(
        ":post_id" => $key,
      );
      $sql = "SELECT * FROM {$db_table} WHERE post_id = :post_id";
      $sth = $pdo->prepare($sql);
      $sth->execute($input);
      if($sth->rowCount() == 0) {
        $input = array(
          ":post_id" => $key,
          ":image" => $value["img"],
          ":description" => $value["text"],
          ":link" => $value["post"],
          ":time" => $value["time"], 
        );
        $sql = "INSERT INTO {$db_table} VALUES (null, :post_id, :image, :description, :link, :time)";
        $sth = $pdo->prepare($sql);
        $sth->execute($input);
      }
    }
  }

  fetchAlbum('');

  fetchPost();

  saveDB();
?>