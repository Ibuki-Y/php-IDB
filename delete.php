<?php
session_start();

require('library.php');

// ログインしているか判定
if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
    $name = $_SESSION['name'];
    $id = $_SESSION['id'];
} else {
    // ログイン画面へ戻す
    header('Location: login.php');
    exit();
}

// 削除するpostのidをURLパラメターから取得
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// postのidが渡っていなければいindex.phpに戻す
if (!$post_id) {
    header('Location: index.php');
    exit();
}

$db = dbconnect();
// 事故を防ぐためlimit 1，member_idが一致しないと消せない
$stmt = $db->prepare('delete from posts where id=? and member_id=? limit 1');
if (!$stmt) {
    die($db->error);
}
$stmt->bind_param('ii', $post_id, $id);
$success = $stmt->execute();
if (!$success) {
    die($db->error);
}

header('Location: index.php');
exit();
