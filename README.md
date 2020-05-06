# 財哥語錄 API
擷取 [財哥專業檳榔攤](https://www.facebook.com/caigezhuanyebinlangtan) 的 [動態時報相片](https://www.facebook.com/caigezhuanyebinlangtan/media_set/?set=a.1620287888210449) 內語錄的圖片、說明以及貼文連結、ID  並存入 MySQL 資料庫  
**這個 API 並不需要 Facebook 開發人員帳號及申請應用程式驗證**  

![screenshot](cover.jpg)

## 線上 API
### 所有資料
- 語錄清單 - `GET` - [https://api.kento520.tw/zack/](https://api.kento520.tw/zack/)
- 隨機一則語錄資料 - `GET` - [https://api.kento520.tw/zack/?rand](https://api.kento520.tw/zack/?rand)
- 隨機一張語錄圖 - `GET` - [https://api.kento520.tw/zack/?randimg](https://api.kento520.tw/zack/?randimg)

## 自架 API
### 系統需求
- PHP 7.0 以上，開發環境為 PHP 7.1.18
- MySQL 資料庫
- [Composer](https://getcomposer.org/)

### 安裝
- 將 `zack.sql` 匯入資料庫
- 使用 `composer install` 安裝套件
- 設定 `config.php`

### 使用
- `index.php` 為 API 網址
- 可設定排程執行 `cornjob.php`，抓取相簿圖片並存入資料庫，執行時間約 15 到 20 分鐘

### 欄位說明
- `post_id`: 貼文 ID
- `image`: 圖片網址
- `description`: 圖片說明
- `link`: 貼文網址
- `timestamp`: UNIX TIMESTAMP
