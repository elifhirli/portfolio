<?php

include "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "METHOD_NOT_ALLOWED";
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $email === "" || $subject === "" || $message === "") {
    http_response_code(400);
    echo "MESSAGE_FIELDS_REQUIRED";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "EMAIL_ADDRESS_INVALID";
    exit;
}

$query = "INSERT INTO messages (name,email,subject,message) VALUES (?,?,?,?)";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    http_response_code(500);
    echo "DATABASE_PREPARE_FAILED";
    exit;
}

mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo "MESSAGE_TRANSMISSION_FAILED";
    exit;
}

$to = "elifnurhirli@gmail.com";
$safeName = str_replace(["\r", "\n"], "", $name);
$safeEmail = str_replace(["\r", "\n"], "", $email);
$safeSubject = str_replace(["\r", "\n"], "", $subject);
$mailSubject = "Portfolio Contact: " . $safeSubject;
$mailBody = "New portfolio message\n\n"
    . "Name: " . $name . "\n"
    . "Email: " . $email . "\n"
    . "Subject: " . $subject . "\n\n"
    . "Message:\n" . $message . "\n";

$headers = [
    "From: Portfolio Contact <no-reply@localhost>",
    "Reply-To: " . $safeName . " <" . $safeEmail . ">",
    "Content-Type: text/plain; charset=UTF-8",
    "X-Mailer: PHP/" . phpversion()
];

$mailSent = @mail($to, $mailSubject, $mailBody, implode("\r\n", $headers));

if ($mailSent) {
    echo "MESSAGE_SENT_SUCCESSFULLY";
} else {
    echo "MESSAGE_SENT_SUCCESSFULLY";
}
