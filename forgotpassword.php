<?php require_once('Connections/mlms.php');

if (isset($_POST['submit'])) {
    $mail = $_POST['email'];

    $sql = "SELECT `password` FROM `member` WHERE `email`='$mail'";
    $result = mysqli_query($mlms, $sql);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $email = $row['email'];
            $password = $row['password'];
        }

        $subject = "Your password for Loan Management System";
        $message = "Password is : " . $password;
        echo "<script>let formData = new FormData();
        formData.append('subject', '" . $subject . "');
        formData.append('to', '" . $email . "');
        formData.append('message', '" . $message . "');
        fetch('./mail/sendmail', {
            method: 'POST',
            body: formData,
        }).then(res=>res.json().then(data => {if(data.success === 'true'){alert('Password Sent on your registered email address.');}  });</script>";
    }

}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home page</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
    <style>
    form {
        display: inline-block;
        padding: 20px;
        border: 1px solid green;
        border-radius: 10px;
    }

    input {
        height: 30px;
        margin-top: 7px;
        margin-bottom: 7px;
        border-radius: 6px;
        border: 0.5px solid grey;
        padding-left: 7px;
        padding-right: 7px;
        width: 95%;
        float: left;
    }

    label {
        float: left;
    }
    </style>
</head>

<body>
    <?php include 'header/login.php'; ?>
    <p></p>

    <center>
        <div class="mainn">
            <div class="container">
                <h3 class="llTl">Password Recovery</h3>
                <form METHOD="POST" action="" class="fom">
                    <label for="email"><b>Email address</b></label>
                    <input type="text" autocomplete="off" placeholder="Enter your email address" name="email" required>
                    <br />
                    <input class="frmBtn" type="submit" value="Send" name="submit"><br />

                </form>

            </div>
        </div>
    </center>

    <script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0px";
        document.getElementById("main").style.marginLeft = "0px";
    }
    </script>

    <script>
    var myInput = document.getElementById("psw");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    // When the user clicks on the password field, show the message box
    myInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function() {
        document.getElementById("message").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }

        // Validate length
        if (myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");

        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
    }
    </script>


</body>
</html>