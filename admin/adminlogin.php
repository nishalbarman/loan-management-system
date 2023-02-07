<?php require_once('../Connections/mlms.php');

session_start();
if ((isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./dashboard.php");
    exit;
}

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
    $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['userName'])) {
    $loginUsername = $_POST['userName'];
    $password = $_POST['password'];
    $MM_fldUserAuthorization = "";
    $MM_redirectLoginSuccess = "dashboard.php";
    $MM_redirectLoginFailed = "adminlogin.php";
    $MM_redirecttoReferrer = false;
    mysqli_select_db($mlms, $database_mlms);

    $LoginRS__query = sprintf(
        "SELECT userName, password FROM `admin` WHERE userName='$loginUsername' AND password='$password'",
    );

    $LoginRS = mysqli_query($mlms, $LoginRS__query) or die(mysql_error());
    $loginFoundUser = mysqli_num_rows($LoginRS);
    if ($loginFoundUser) {
        $loginStrGroup = "";
        session_regenerate_id(true);
        //declare two session variables and assign them
        $_SESSION['MM_Username'] = $loginUsername;
        $_SESSION['MM_UserGroup'] = $loginStrGroup;

        header("Location: " . $MM_redirectLoginSuccess);
    } else {
        header("Location: " . $MM_redirectLoginFailed);
    }
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <!-- <link rel="stylesheet" type="text/css" href="../Assets/css/style.css"> -->
    <style>
        .regForm {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: inline-block;
            width: auto;
            padding: 30px;
            /* border: 1px solid black; */
            background-color: white;
            /* height: 200px; */
            border-radius: 10px;
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
        }

        .frmBtn {
            align-self: center;
            background-color: greenyellow;
            border: none;
            border-radius: 10px;
            height: 37px;
            width: 100%;
            color: black;
            font-size: 17px;
            font-weight: bold;
            margin-top: 10px;
            cursor: pointer;
        }

        input {
            height: 30px;
        }
    </style>
</head>
<body background="../Photos/mountains2.jpg">
    <center>

        <div class="main">
            <div class="regForm">
                <h3 class="llTl">Admin login</h3>
                <form METHOD="POST" action="<?php echo $loginFormAction; ?>" class="fom">
                    <label for="userName"><b>User name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></label>
                    <input type="text" placeholder="Enter User name" name="userName" required>
                    <br /> <br />
                    <label for="password"><b>Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></label>
                    <input type="password" id="psw" name="password" placeholder="Enter password"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                        required>
                    <br />

                    <br />
                    <input class="frmBtn" type="submit" value="Login">
                </form>
            </div>
        </div>
        <script>
            var myInput = document.getElementById("psw");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var length = document.getElementById("length");

            // When the user clicks on the password field, show the message box
            myInput.onfocus = function () {
                document.getElementById("message").style.display = "block";
            }

            // When the user clicks outside of the password field, hide the message box
            myInput.onblur = function () {
                document.getElementById("message").style.display = "none";
            }

            // When the user starts to type something inside the password field
            myInput.onkeyup = function () {
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
    </center>
</body>
</html>