<?php
function db_connect(){
  define('DB_DSN', 'mysql:dbname=paisen1_bbs;host=mysql708.db.sakura.ne.jp;charset=utf8');
  define('DB_USER', 'paisen1');
  define('DB_PASS', 'rude1979g');

  try {
      $dbh = new PDO(
          DB_DSN,
          DB_USER,
          DB_PASS,
          [
              PDO::ATTR_EMULATE_PREPARES => false,
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          ]
      );
      return $dbh;

  } catch (PDOException $e) {

        echo "エラー発生： " . h($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
        die();
  }
}
