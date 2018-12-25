<?php

if (isset($_GET['id'])) {

    //データベース接続
    require_once("db.php");
    $dbh = db_connect();

    try {

        $stmt = $dbh->prepare('SELECT type, raw_data FROM recipes WHERE id = ? LIMIT 1');
        $stmt->bindValue(1, $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        if (!$row = $stmt->fetch()) {
            throw new RuntimeException('該当する画像は存在しません', 404);
        }
        header('X-Content-Type-Options: nosniff');
        header('Content-Type: ' . image_type_to_mime_type($row['type']));
        echo $row['raw_data'];
        exit;

    } catch (RuntimeException $e) {

        http_response_code($e instanceof PDOException ? 500 : $e->getCode());
        $msgs[] = ['red', $e->getMessage()];

    }

}
