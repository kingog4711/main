<!-- filepath: d:\WebSite\練習用\メールフォーム＝PHP\index.php -->
<?php
// 初期化
$errors = [];
$name = $email = $phone = $message = "";
$colors = ['red', 'blue', 'yellow'];

// 初回表示時にランダム色を生成
$randomColor = $colors[array_rand($colors)];

// フォーム送信時の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 名前のバリデーション
    if (empty($_POST["name"])) {
        $errors['name'] = "名前を入力してください。";
    } else {
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
    }

    // メールのバリデーション
    if (empty($_POST["email"])) {
        $errors['email'] = "メールアドレスを入力してください。";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "正しいメールアドレスを入力してください。";
    } else {
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
    }

    // 電話番号のバリデーション
    if (empty($_POST["phone"])) {
        $errors['phone'] = "電話番号を入力してください。";
    } elseif (!preg_match('/^\d{10,11}$/', $_POST["phone"])) {
        $errors['phone'] = "電話番号は10～11桁の数字で入力してください。";
    } else {
        $phone = htmlspecialchars($_POST["phone"], ENT_QUOTES, 'UTF-8');
    }

    // お問い合わせ内容のバリデーション
    if (empty($_POST["message"])) {
        $errors['message'] = "お問い合わせ内容を入力してください。";
    } else {
        $message = htmlspecialchars($_POST["message"], ENT_QUOTES, 'UTF-8');
    }

    // セキュリティチェックのバリデーション
    if (empty($_POST["color"]) || $_POST["color"] !== $_POST["randomColor"]) {
        $errors['color'] = "正しい色を選択してください。";
    }

    // エラーがなければメール送信
    if (empty($errors)) {
        $to = "kingof4711@gmail.com";
        $subject = "お問い合わせがありました";
        $body = "名前: $name\nメールアドレス: $email\n電話番号: $phone\n\nお問い合わせ内容:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            $successMessage = "お問い合わせありがとうございました、$name 様！";
            // 送信成功後に新しいランダム色を生成
            $randomColor = $colors[array_rand($colors)];
        } else {
            $errors['mail'] = "メール送信に失敗しました。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center;}
        .container { max-width: 500px; margin: auto; }
        .error { color: red; font-size: 14px; }
        .success { color: red; font-size: 18px;font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        input[type="submit"] { background-color: yellow; color: white; border: none; padding: 10px; cursor: pointer; }
        input[type="submit"]:hover { background-color: red; }
        .color-box { width: 100px; height: 50px; margin: 10px auto;  }
        .color-options { display: flex; justify-content: center; gap: 10px; }
        .color-option { display: flex; align-items: center; }
        .color-option input { display: none; }
        .color-option label { padding: 10px; border: 1px solid #ccc; cursor: pointer; }
        .color-option input:checked + label { border-color: #000; }
        .color-option label:hover { color: #fff; }
        .color-option input#color-red + label:hover { background-color: red; }
        .color-option input#color-blue + label:hover { background-color: blue; }
        .color-option input#color-yellow + label:hover { background-color: yellow; color: #000; }
    </style>
    <script>
        function updateColor(event) {
            const color = event.target.value;
            document.querySelectorAll('.color-option label').forEach(label => {
                label.style.backgroundColor = '';
                label.style.color = '';
            });
            const selectedLabel = document.querySelector(`label[for="color-${color}"]`);
            selectedLabel.style.backgroundColor = color;
            selectedLabel.style.color = '#fff';
        }
    </script>
</head>
<body>

<div class="container">
    <h2>お問い合わせフォーム</h2>

    <form action="" method="post">
        <label>名前:</label>
        <input type="text" name="name" placeholder="例: 坂本 太郎" value="<?= htmlspecialchars($name ?? '', ENT_QUOTES); ?>">
        <p class="error"><?= $errors['name'] ?? ''; ?></p>

        <label>メールアドレス:</label>
        <input type="email" name="email" placeholder="例: example@gmail.com" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES); ?>">
        <p class="error"><?= $errors['email'] ?? ''; ?></p>

        <label>電話番号:</label>
        <input type="text" name="phone" placeholder="例: 09012345678" value="<?= htmlspecialchars($phone ?? '', ENT_QUOTES); ?>">
        <p class="error"><?= $errors['phone'] ?? ''; ?></p>

        <label>お問い合わせ内容:</label>
        <textarea name="message" placeholder="お問い合わせ内容を入力してください。"><?= htmlspecialchars($message ?? '', ENT_QUOTES); ?></textarea>
        <p class="error"><?= $errors['message'] ?? ''; ?></p>

        <label>以下の色を選択してください:</label>
        <div class="color-box" style="background-color: <?= $randomColor; ?>;"></div>
        <input type="hidden" name="randomColor" value="<?= $randomColor; ?>">
        <div class="color-options">
            <div class="color-option">
                <input type="radio" name="color" value="red" id="color-red" onchange="updateColor(event)">
                <label for="color-red">赤</label>
            </div>
            <div class="color-option">
                <input type="radio" name="color" value="blue" id="color-blue" onchange="updateColor(event)">
                <label for="color-blue">青</label>
            </div>
            <div class="color-option">
                <input type="radio" name="color" value="yellow" id="color-yellow" onchange="updateColor(event)">
                <label for="color-yellow">黄</label>
            </div>
        </div>
        <p class="error"><?= $errors['color'] ?? ''; ?></p>

        <input type="submit" value="送信">

        <?php if (!empty($successMessage)) : ?>
        <p class="success"><?= $successMessage; ?></p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>