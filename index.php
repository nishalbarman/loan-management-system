<?php require_once('Connections/mlms.php');
session_start();
?>

<?php
if (!isset($_SESSION)) {
    session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
    $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['memberId'])) {
    $loginUsername = $_POST['memberId'];
    $password = $_POST['password'];
    $MM_fldUserAuthorization = "";
    $MM_redirectLoginSuccess = "loanhistory.php";
    $MM_redirectLoginFailed = "index.php";
    $MM_redirecttoReferrer = false;
    mysqli_select_db($mlms, $database_mlms);

    // $LoginRS__query = "SELECT `memberId`, `password` FROM `member` WHERE `memberId`=$loginUsername AND `password`='$password'";
    $sql = "SELECT `memberId` FROM `member` WHERE `memberId`=$loginUsername AND `password`='$password'";

    $LoginRS = $mlms->query($sql);
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

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home page</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
    <style>
        .regForm {
            padding: 25px;
            width: 50%;
            border: 1px solid green;
            border-radius: 10px;

        }

        input {

            height: 30px;
            width: 100%
        }
    </style>
</head>

<body>
    <?php include 'header/login.php'; ?>
    <p></p>

    <div class="main">
        <h3 id="main-title" class="llTl">Member sign in</h3>
        <div id="part1">

            <form id="formSubmit" class="regForm" METHOD="POST" action="<?php echo $loginFormAction; ?>" class="fom"
                onsubmit="return false">
                <label for="memberId"><b>ID No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></label>
                <input type="text" autocomplete="off" placeholder="Enter member id" name="memberId" edit-me required>
                <br /> <br />
                <label for="password"><b>Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></label>
                <input type="password" id="psw" name="password" placeholder="Enter password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    edit-me required>
                <br />
                <br />
                <input id="loginBtn" class="frmBtn" type="submit" name="login" value="Login">
                <p><i>Dont have an account?<a href="addmember.php">Register.</a></i></p>
            </form>
        </div>
        <div id="part2" style="display: none;">
            <form class="regForm" onsubmit="return false">

                <div class="captcha-container">
                    <div class="captcha-question">What is <span class="captcha-number">
                            <?php $_SESSION['first'] = rand(1, 9);
                            echo $_SESSION['first']; ?>
                        </span> +
                        <span class="captcha-number">
                            <?php $_SESSION['second'] = rand(1, 9);
                            echo $_SESSION['second']; ?>
                        </span>?
                    </div>
                    <input type="number" class="captcha-input" id="captcha-value" edit-me-c>
                    <input id="captchaBtn" class="frmBtn" type="submit" name="verify" value="Verify">
                </div>
            </form>
        </div>

    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }

        loginBtn.addEventListener('click', () => {
            document.querySelectorAll('[edit-me]').forEach(element => {

                if (element.value === "") {
                    alert("Roll No and Password both are Required Bro! Kya kar raha hain tu??");
                    return;
                } else {
                    document.getElementById('part1').style.display = "none";
                    document.getElementById('part2').style.display = "block";
                    document.getElementById('main-title').textContent = "Solve this Captcha";
                }
            });
        });

        captchaBtn.addEventListener('click', () => {
            document.querySelectorAll('[edit-me-c]').forEach(element => {

                if (element.value === "") {
                    alert("Robot hai kya tu, Captcha to daal BHAII!!");
                    return;
                } else {
                    let num1 = '<?php echo $_SESSION['first']; ?>';
                    let num2 = '<?php echo $_SESSION['second']; ?>';
                    let third = parseInt(num1) + parseInt(num2);
                    console.log(third);
                    console.log(element.value);
                    if (parseInt(element.value) === third) {
                        document.getElementById('formSubmit').submit();
                    } else {
                        alert("BHAII tu robot lagta hain, Captcha galat hai reee...");
                    }
                }
            });
        });
    </script>
    <!-- <script>
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
    </script> -->


</body>
</html>