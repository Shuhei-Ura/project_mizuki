<?php
// contact.php（メール送信のみ / mail()版）
// 文字化け対策
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// ====== 設定ここだけ変える ======
$admin_to   = "s-ura@respoint-okinawa.com";     // 管理者の受信先（ここを本番の宛先に）
$site_name  = "Lyta";               // サイト名
$redirect   = "../thanks.html";     // 送信後の遷移先（なければコメントアウト）
// =================================

// POST以外は拒否
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  exit("Method Not Allowed");
}

// 入力取得（トリム）
$company = trim($_POST["company"] ?? "");
$name    = trim($_POST["name"] ?? "");
$email   = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

// バリデーション
$errors = [];
if ($name === "") $errors[] = "お名前は必須です。";
if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "メールアドレスが正しくありません。";
if ($message === "") $errors[] = "お問い合わせ内容は必須です。";

if (!empty($errors)) {
  http_response_code(400);
  echo "入力内容に不備があります。\n\n";
  echo implode("\n", $errors);
  exit;
}

// メール本文（管理者宛）
$subject = "【{$site_name}】お問い合わせがありました";
$body_admin = "お問い合わせを受け付けました。\n\n"
  . "【会社名・団体名】\n" . ($company !== "" ? $company : "（未入力）") . "\n\n"
  . "【お名前】\n{$name}\n\n"
  . "【メールアドレス】\n{$email}\n\n"
  . "【お問い合わせ内容】\n{$message}\n";

// From（サーバーによってはここが重要）
$from_address = $admin_to; // ドメインのメールが推奨（info@lyta.co.jp など）
$headers = [];
$headers[] = "From: {$site_name} <{$from_address}>";
$headers[] = "Reply-To: {$name} <{$email}>"; // 返信はユーザー宛に
$headers[] = "Content-Type: text/plain; charset=UTF-8";
$headers_str = implode("\r\n", $headers);

// 送信（管理者宛）
$sent_admin = mb_send_mail($admin_to, $subject, $body_admin, $headers_str);

if (!$sent_admin) {
  http_response_code(500);
  exit("送信に失敗しました。時間をおいて再度お試しください。");
}

// 自動返信（ユーザー宛）※不要ならこのブロックを丸ごと削除OK
$subject_user = "【{$site_name}】お問い合わせありがとうございます";
$body_user = "{$name} 様\n\n"
  . "この度はお問い合わせありがとうございます。\n"
  . "以下の内容で受け付けました。\n\n"
  . "--------------------\n"
  . "会社名・団体名： " . ($company !== "" ? $company : "（未入力）") . "\n"
  . "お名前： {$name}\n"
  . "メール： {$email}\n"
  . "内容：\n{$message}\n"
  . "--------------------\n\n"
  . "担当よりご連絡いたします。\n\n"
  . "{$site_name}\n";

$headers_user = [];
$headers_user[] = "From: {$site_name} <{$from_address}>";
$headers_user[] = "Content-Type: text/plain; charset=UTF-8";
$headers_user_str = implode("\r\n", $headers_user);

mb_send_mail($email, $subject_user, $body_user, $headers_user_str);

// 送信後遷移
header("Location: {$redirect}");
exit;
