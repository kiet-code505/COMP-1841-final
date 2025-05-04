<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? 'No subject';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phamgiakiet1911@gmail.com';
        $mail->Password   = 'drvwuzlbhmkolckk';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Người gửi là user
        $mail->setFrom($email, $name);
        $mail->addAddress('phamgiakiet1911@gmail.com', 'Admin');

        // Nội dung email
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br(htmlspecialchars($message));
        $mail->AltBody = $message;

        $mail->send();

        // Gửi thành công, redirect về dashboard với thông báo
        $_SESSION['email_status'] = '✅ Gửi email thành công!';
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['email_status'] = "❌ Lỗi gửi email: {$mail->ErrorInfo}";
        header('Location: dashboard.php');
        exit;
    }
} else {
    $_SESSION['email_status'] = '❗ Truy cập không hợp lệ.';
    header('Location: dashboard.php');
    exit;
}




