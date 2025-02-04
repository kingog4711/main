<?php
// 必要な PHPMailer クラスを読み込む
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer を使ってインストールした場合
require 'vendor/autoload.php';

// Gmail SMTP サーバーの設定
$mail = new PHPMailer(true);

try {
    // サーバーの設定
    $mail->isSMTP();                      // SMTP を使用
    $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP サーバー
    $mail->SMTPAuth   = true;             // SMTP 認証を有効化
    $mail->Username   = 'your_email@gmail.com'; // あなたの Gmail アドレス
    $mail->Password   = 'your_app_password';   // Gmail のアプリパスワード
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 暗号化
    $mail->Port       = 587; // Gmail の SMTP ポート

    // 送信元 (自分のメールアドレス)
    $mail->setFrom('your_email@gmail.com', 'Your Name');
    
    // 送信先
    $mail->addAddress('kingofd4711@gmail.com', 'King MF'); 

    // フォームからデータを取得
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES, "UTF-8");

        // メールの件名
        $mail->Subject = "応募フォームからの新規応募";
        
        // メール本文
        $mail->Body = "名前: " . $name . "\nメールアドレス: " . $email;
        
        // メール送信
        $mail->send();
        echo "メールが送信されました。";
    } else {
        echo "不正なアクセスです。";
    }
} catch (Exception $e) {
    echo "メールの送信に失敗しました。エラー: {$mail->ErrorInfo}";
}
?>
