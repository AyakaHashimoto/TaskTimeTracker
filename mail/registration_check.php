<?php
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

session_start();
header("Content-type: text/html; charset=utf-8");
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

if(!empty($_SESSION['email'])){
    //データを変数に入れる
	$mail = isset($_SESSION['email']) ? $_SESSION['email'] : NULL;
}
if (!is_null($mail) && is_null($errors)){
	
	$urltoken = hash('sha256',uniqid(rand(),1));
	$url = "https://tasktimekeeper.herokuapp.com/join/index.php"."?urltoken=".$urltoken;


    require('../dbconnect.php');
	try{
        //例外処理
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $db->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,now() )");
        $statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
        $statement->bindValue(':mail', $mail, PDO::PARAM_STR);
        $statement->execute();
            
        //データベース接続切断
        $db = null;	
    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }
    //メールの宛先
	$mailTo = $mail;
 
	//Return-Pathに指定するメールアドレス
	$returnMail = 'annassfdc@gmail.com';
 
	$name = "TASK TIME KEEPER メール登録";
	$mail = 'annassfdc@gmail.com';
	$subject = "【TASK TIME KEEPER】会員登録用URLのお知らせ";
 
$body = <<< EOM
24時間以内に下記のURLからご登録下さい。
{$url}
EOM;
 
	mb_language('ja');
	mb_internal_encoding('UTF-8');
 
	//Fromヘッダーを作成
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';
 
	if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {
	
	 	//セッション変数を全て解除
		$_SESSION = array();
	
		//クッキーの削除
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//セッションを破棄する
 		session_destroy();
 	
 		$message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
 	
	} else {
		$errors = "メールの送信に失敗しました。";
	}	

}   
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <title>Task Time Keeper</title>
</head>
<body>
<main>
        <h2 class="text-center text-info my-4">TASK TIME KEEPER</h2>
        <h1>メール確認</h1>
        <div class="card">
            <div class="card-body">
             <?php if (!is_null($mail)&& is_null($errors)): ?>
                <p><?=$message?></p>
 
                <!-- <p>↓このURLが記載されたメールが届きます。</p>
                <a href="<?=$url?>"><?=$url?></a> -->
                
                <?php elseif(!is_null($errors)): ?>
                    <p><?php print($errors); ?></p>
                    <div class="mt-3">
                    <a class="btn btn-outline-primary my-2" href="registration.php">
                    戻る</a>
                    </div>
                    
                <?php endif; ?>
            </div>
        </div>
        
    </main>
</body>