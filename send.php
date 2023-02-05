<?php
$to = $_GET['email'];
$otp = random_int(100000, 999999);

$subject = "Authentication Code";
$txt = "Your OTP is : " . $otp;
$headers = "From: loan@gmail.com";

if (mail($to, $subject, $txt, $headers)) {
    $data = array('status' => true, "kht" => $otp);
    print_r(json_encode($data));
} else {
    $data = array('status' => false);
    print_r(json_encode($data));
}

?>