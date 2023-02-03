<?php
session_start();

$subject = $_POST['subject'];
$to = $_POST['subject'];
$message = $_POST['message'];

$headers = "From: nischalbarman1@gmail.com";

if (mail($to, $subject, $message, $headers)) {
    $data = array('success' => true, 'message' => "Email sent successfuly.");
    print_r(json_encode($data));
} else {
    $data = array('success' => false, 'message' => "Email not sent.");
    print_r(json_encode($data));
}

?>