<?php
  // ---------------------------------------------------
  //  Facebook API Settings
  //  對 Facebook 網頁右鍵 -> 檢查原始碼 可以找到 cookie 以外的資料
  //  $cookie = Facebook Cookie，格式為 "sb=xxxxx; datr=xxx; c_user=xxx; xs=xxx; fr=xxx; spin=xxx; presence=xxx; act=xxx%2F2; wd=xxx"
  // ---------------------------------------------------
  
  $cookie = "";
  $ajaxpipe_token = "";
  $fb_dtsg_ag = "";
  $user_id = "";
  
  // ---------------------------------------------------
  //  Database Settings
  //  資料庫設定
  //  $db_host = 資料庫位址
  //  $db_name = 資料庫名稱
  //  $db_user = 資料庫使用者
  //  $db_password = 資料庫密碼
  //  $db_table = 資料表名稱
  // ---------------------------------------------------
  
  $db_host = "localhost";
  $db_name = "zack";
  $db_user = "root";
  $db_password = "";
  $db_table = "zack";
?>