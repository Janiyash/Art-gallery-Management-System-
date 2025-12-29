<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";  // PHPMailer autoload

// =======================================================
// GLOBAL MAILER SETTINGS (USED FOR ALL EMAILS)
// =======================================================
function setupMailer() {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;

        // ⚠️ YOUR GMAIL + APP PASSWORD
        $mail->Username   = "janiyash0911@gmail.com";
        $mail->Password   = "whan rhdr xqcy hxkx";

        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;

        // MUST MATCH your Gmail account
        $mail->setFrom("janiyash0911@gmail.com", "Artify Gallery");

        return $mail;

    } catch (Exception $e) {
        echo "Mailer Setup Error: " . $e->getMessage();
        return false;
    }
}


function sendOrderMail($to, $title, $price, $order_id) {
    $mail = setupMailer();
    if (!$mail) return false;

    $html = "
        <h2 style='color:#e11d22;'>Order Confirmation</h2>
        <p>Thank you for your purchase!</p>

        <p><strong>Artwork:</strong> $title</p>
        <p><strong>Amount Paid:</strong> ₹$price</p>
        <p><strong>Order ID:</strong> #$order_id</p>

        <br><br>
        <p style='color:#555;'>Artify Gallery</p>
    ";

    try {
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = "Your Artify Order #$order_id";
        $mail->Body    = $html;

        return $mail->send();

    } catch (Exception $e) {
        echo "Order Mail Error: " . $mail->ErrorInfo;
        return false;
    }
}
function sendContactMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Setup
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // YOUR Gmail + App Password
        $mail->Username   = 'janiyash0911@gmail.com';
        $mail->Password   = 'whan rhdr xqcy hxkx';  

        // SSL (465) — Correct for SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // MUST MATCH your Gmail account
        $mail->setFrom('janiyash0911@gmail.com', 'Artify Gallery');

        // Receiver
        $mail->addAddress($to);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        return $mail->send();

    } catch (Exception $e) {
        echo "<b>Contact Mail Error:</b> " . $mail->ErrorInfo;
        return false;
    }
}

?>
