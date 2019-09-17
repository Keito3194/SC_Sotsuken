<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>学生ログイン</title>
        <link rel="stylesheet" href="style.css">
        
        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <!-- jQuery読み込み -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- BootstrapのJS読み込み -->
        <script src="js/bootstrap.min.js"></script>
        
    </head>
    <body>
        <div class="col-lg-6 col-xs-12 col-lg-offset-3">

        <form method="post">
            
        <div class="form-group">
            <input type="text"  class="form-control" name="text" placeholder="学籍番号" required />
            </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="パスワード" required />
        </div>
        <button type="submit" class="btn btn-default" name="login">ログイン</button>
        <button type="button" class="btn btn-default" name="back" onClick="history.go(-1);">戻る</button>
        <a href="new-account">会員登録はこちら</a>
        </form>

        </div>
    </body>
</html>

<?php
ob_start();
session_start();
if( isset($_SESSION['wp_userdata']) != "") {
  header("Location: mypage");
}
include_once 'dbconnect.php';

// ログインボタンがクリックされたときに下記を実行
if(isset($_POST['login'])) {

  $email = $mysqli->real_escape_string($_POST['email']);
  $password = $mysqli->real_escape_string($_POST['password']);

  // クエリの実行
  $query = "SELECT * FROM wp_userdata WHERE email='$email'";
  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    echo 'ログインに失敗しました';
    header("Location: login");
    exit();
  }

  // パスワード(暗号化済み）とユーザーIDの取り出し
  while ($row = $result->fetch_assoc()) {
    $db_hashed_pwd = $row['password'];
    $user_id = $row['user_id'];
  }

  // データベースの切断
  $result->close();

  // ハッシュ化されたパスワードがマッチするかどうかを確認
  if (password_verify($password, $db_hashed_pwd)) {
    $_SESSION['wp_userdata'] = $user_id;
    header("Location: mypage");
    exit;
  } else { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" id="alertfadeout">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>メールアドレスとパスワードが一致しません。
    </div>
  <?php }
}
?>
<script>
/* bootstrap alertをx秒後に消す */
$(document).ready(function()
{
  $(window).load(function()
  {
    window.setTimeout("$('#alertfadeout').fadeOut()", 0);
  });
});
</script>
