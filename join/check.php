<?php
// index.phpからsessionでform情報を取得
session_start();
// var_dump($_SESSION['form']);

require('../library.php');

// sessionの情報があるとき取得
if (isset($_SESSION['form'])) {
    $form = $_SESSION['form'];
} else {
    // sessionがない状態(直接URL検索されたなど) => indexに戻す
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = dbconnect();
    // インサートクエリ
    $stmt = $db->prepare('insert into members (name, email, password, picture) values (?, ?, ?, ?)');
    // クエリエラー
    if (!$stmt) {
        die($db->error);
    }
    // dbにパスワードを渡すため，暗号化
    $password = password_hash($form['password'], PASSWORD_DEFAULT);
    $stmt->bind_param('ssss', $form['name'], $form['email'], $password, $form['image']);
    $success = $stmt->execute();
    // 実行できないとき
    if (!$success) {
        die($db->error);
    }

    // sessionを切る
    unset($_SESSION['form']);
    // thanks.phpに移動
    header('Location: thanks.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>

    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>会員登録</h1>
        </div>

        <div id="content">
            <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
            <form action="" method="post">
                <dl>
                    <dt>ニックネーム</dt>
                    <dd><?php echo h($form['name']); ?></dd>

                    <dt>メールアドレス</dt>
                    <dd><?php echo h($form['email']); ?></dd>

                    <dt>パスワード</dt>
                    <dd>
                        【表示されません】
                    </dd>

                    <dt>写真</dt>
                    <dd>
                        <img src="../member_picture/<?php echo h($form['image']); ?>" width="100" alt="" />
                    </dd>
                </dl>

                <div>
                    <!-- URLに?action=rewriteを渡す -->
                    <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>