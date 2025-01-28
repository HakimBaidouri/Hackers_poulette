<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "DB_connection.php";
require 'vendor/autoload.php';

require 'vendor/autoload.php'; // Autoload de Composer
use SendGrid\Mail\Mail;

// Récupérer la réponse hCaptcha envoyée par le formulaire
$hCaptchaResponse = $_POST['h-captcha-response'];
$hCaptchaSecretKey = "e65674e6-46d4-4b52-960c-64a4e81c3350"; // Remplace par ta clé secrète

// Vérifier la réponse hCaptcha avec l'API hCaptcha
$verifyUrl = "https://hcaptcha.com/siteverify";
$response = file_get_contents($verifyUrl . "?secret=" . $hCaptchaSecretKey . "&response=" . $hCaptchaResponse);
$responseKeys = json_decode($response, true);

// Si la validation échoue mais on ne va pas le faire car on travaille en local
// if (intval($responseKeys["success"]) !== 1) {
//     print_r($responseKeys);
//     echo "CAPTCHA validation failed. Please try again.";
//     exit;
// }

$name = $_POST['name'];
$firstName = $_POST['firstName'];
$mail = $_POST['mail'];
$url = $_POST['url'];
$description = $_POST['description'];

$userQuery = 'INSERT INTO user (name, firstname, mail) VALUES (:name, :firstname, :mail)';
$fileQuery = 'INSERT INTO file (user_id, url, description) VALUES (:user_id, :url, :description)';
$getUserIdQuery = 'SELECT id FROM user WHERE (name=:name AND firstname=:firstname AND mail=:mail)';
$getUserMail = 'SELECT mail FROM user WHERE (id=:userId)';

if (empty($name) || strlen($name) < 2 || strlen($name) > 255) {
    echo "Name must be between 2 and 255 characters.";
    exit("Invalide");
}

if (empty($firstName) || strlen($firstName) < 2 || strlen($firstName) > 255) {
    echo "Firstname must be between 2 and 255 characters.";
    exit("Invalide");
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit("Invalide");
} else {
    $prep = $pdo->prepare($userQuery);
    $prep->bindParam(':name', $name);
    $prep->bindParam(':firstname', $firstName);
    $prep->bindParam(':mail', $mail);
    $prep->execute();
    $prep->closeCursor();
    $prep = NULL;
}

if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo "Invalid URL.";
    exit("Invalide");
}

if(empty($description) || strlen($description) < 2 || strlen($description) > 1000){
    echo("Invalid description");
    exit("Invalid");
} else {
    $userId = "";
    $prep = $pdo->prepare($getUserIdQuery);
    $prep->bindParam(':name', $name);
    $prep->bindParam(':firstname', $firstName);
    $prep->bindParam(':mail', $mail);
    $prep->execute();
    $user = $prep->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $userId = $user['id'];
    } else {
        echo "User not found.";
        exit("Invalide");
    }
    $prep->closeCursor();
    $prep = NULL;

    $prep = $pdo->prepare($fileQuery);
    $prep->bindParam(':user_id', $userId);
    $prep->bindParam(':url', $url);
    $prep->bindParam(':description', $description);
    $prep->execute();
    $prep->closeCursor();
    $prep = NULL;
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $sendgridApiKey = $_ENV['SENDGRID_API_KEY'];
    $mailFromAddress = $_ENV['MAIL_FROM_ADDRESS'];
    $mailFromName = $_ENV['MAIL_FROM_NAME'];
    $mailtoName = $_ENV['MAIL_TO_NAME'];

    $prep = $pdo->prepare($getUserMail);
    $prep->bindParam(':userId', $userId);
    $prep->execute();
    $userMailData = $prep->fetch(PDO::FETCH_ASSOC);
    if ($userMailData) {
        $userMail = $userMailData['mail'];
    } else {
        echo "Mail not found.";
        exit("Invalide");
    }

    $email = new Mail();

    $email->setFrom($mailFromAddress, $mailFromName);
    $email->setSubject("Test avec DB");
    $email->addTo($userMail, $mailtoName);
    $email->addContent(
        "text/plain",
        "Voici un email simple envoyé via SendGrid."
    );
    $email->addContent(
        "text/html",
        "<strong>Nous avons bien pris en compte votre demande. Nous reviendrons vers vous incessamment.</strong>"
    );

    $sendgrid = new \SendGrid($sendgridApiKey);

    try {
        $response = $sendgrid->send($email);
        echo "Email envoyé avec succès !<br>";
        echo "Statut : " . $response->statusCode();
        echo "<pre>" . print_r($response->headers(), true) . "</pre>";
        echo "<pre>" . $response->body() . "</pre>";
    } catch (Exception $e) {
        echo "Échec de l'envoi de l'email. Erreur : " . $e->getMessage();
    }
    
    
    // $mail = new PHPMailer(true);
    
    // try {
    //     // Configuration du serveur SMTP
    //     $mail->isSMTP();
    //     $mail->Host = $mailHost;
    //     $mail->SMTPAuth = true;
    //     $mail->Username = $mailUsername;
    //     $mail->Password = $mailPassword;
    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //     $mail->Port = $mailPort;
    
    //     // Expéditeur et destinataire
    //     $mail->setFrom($mailFromAddress, $mailFromName);
    
    //     $prep = $pdo->prepare($getUserMail);
    //     $prep->bindParam(':userId', $userId);
    //     $prep->execute();
    //     $userMailData = $prep->fetch(PDO::FETCH_ASSOC);
    //     if ($userMailData) {
    //         $userMail = $userMailData['mail'];
    //     } else {
    //         echo "Mail not found.";
    //         exit("Invalide");
    //     }
    //     print_r($userMail);

    //     $mail->addAddress($userMail, 'Client'); // Ajouter un destinataire
    
    //     // Contenu de l'email
    //     $mail->isHTML(true);
    //     $mail->Subject = 'Test Email';
    //     $mail->Body    = '<b>This is a test email sent using PHPMailer with Outlook SMTP</b>';
    //     $mail->AltBody = 'This is a test email sent using PHPMailer with Outlook SMTP';
    
    //     // Envoi de l'email
    //     $mail->send();
    //     echo 'Message has been sent successfully';
    // } catch (Exception $e) {
    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    // }
}
