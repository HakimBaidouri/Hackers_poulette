<?php
require "DB_connection.php";

$name = $_POST['name'];
$firstName = $_POST['firstName'];
$mail = $_POST['mail'];
$url = $_POST['url'];
$description = $_POST['description'];

$userQuery = 'INSERT INTO user (name, firstname, mail) VALUES (:name, :firstname, :mail)';
$fileQuery = 'INSERT INTO file (user_id, url, description) VALUES (:user_id, :url, :description)';
$getUserIdQuery = 'SELECT id FROM user WHERE (name=:name AND firstname=:firstname AND mail=:mail)';

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
}
