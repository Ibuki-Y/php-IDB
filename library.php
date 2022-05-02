<?php
// htmlspecialchars
function h($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

// データベース接続
function dbconnect() {
    // データベース接続
    $db = new mysqli('localhost', 'root', 'root', 'min_bbs');
    // 接続できないとき
    if (!$db) {
        die($db->error);
    }

    return $db;
}
