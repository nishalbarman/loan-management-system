<?php require_once('Connections/mlms.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    //to fully log out a visitor we need to clear the session varialbles
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);

    $logoutGoTo = "../index.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysqli_escape_string(mysqli_connect("localhost", "root", "", "lms"), $theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
    $_SESSION["memberID"] = mt_rand(10000, 99999);
    $insertSQL = sprintf(
        "INSERT INTO member (memberId, fName, lName, gender, phone, occupation, email, password, address, county, district, location, photo, dob, regDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_SESSION["memberID"], "int"),
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['Gender'], "text"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['occupation'], "text"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString($_POST['password'], "text"),
        GetSQLValueString($_POST['address'], "text"),
        GetSQLValueString($_POST['county'], "text"),
        GetSQLValueString($_POST['district'], "text"),
        GetSQLValueString($_POST['location'], "text"),
        GetSQLValueString($_POST['photo'], "text"),
        GetSQLValueString($_POST['dob'], "date"),
        GetSQLValueString($_POST['regDate'], "date")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysqli_error($mlms));
    $insertGoTo = "addmember.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    echo "<script>alert('Registration successfull, User ID : " . $_SESSION['memberID'] . ", Please copy the user id');window.location = '" . $insertGoTo . "'</script>";
    // header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sign UP</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
    <style>
    form {
        position: absolute;
        left: 50%;
        transform: translate(-50%);
        display: inline-block;
        padding: 25px;
        width: 60%;
        border: 1px solid green;
        border-radius: 10px;

    }

    label {

        float: left;

    }

    input,
    select {
        margin-bottom: 10px;
        height: 30px;
        width: 100%
    }
    </style>
</head>
<body>
    <?php include 'header/login.php'; ?>
    <p></p>
    <center>
        <div class="mainn">
            <h3>Register</h3>
            <form id="regForm" name="form" method="POST" class="form" action="<?php echo $editFormAction; ?>">

                <!-- <label for="memberId"><b>Member ID</b></label>
                <input type="text" placeholder="Enter Member id" pattern="[0-9]*" name="memberId" maxlength="20"
                    value="<?php echo $_SESSION["memberID"]; ?>" readonly disabled required> -->


                <label for="fName"><b>First name: </b></label>
                <input type="text" placeholder="Enter First name" pattern="[a-z A-Z]*" name="fName" maxlength="20"
                    required>


                <label for="lName"><b>Second name: </b></label>
                <input type="text" placeholder="Enter second Name" pattern="[a-z A-Z]*" name="lName" maxlength="20"
                    required>

                <div class="flex">
                    <label for="gender"><b>Gender:&nbsp;&nbsp;</b></label>
                    <input style="height: auto;" type="radio" name="Gender" value="M"
                        checked="checked" /><b>&nbsp;Male</b>&nbsp;&nbsp;
                    <input style="height: auto;" type="radio" name="Gender" value="F"
                        maxlength="20" /><b>&nbsp;Female</b>
                </div>


                <label for="phone"><b>Phone Number:</b></label>
                <input type="text" placeholder="Enter Phone number" name="phone" required maxlength="20" />


                <label for="occupation"><b>Occupation:</b></label>
                <select name="occupation" maxlength="20" />
                <option value="farmer">Farmer</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="other">Other</option>
                </select>


                <label for="email"><b>Email address: </b></label>
                <input type="address" placeholder="Enter Email" name="email" required />


                <label for="password"><b>Password: </b></label>
                <input type="password" placeholder="Enter Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    name="password" required maxlength="20">


                <label for="address"><b>Address: </b></label>
                <input type="text" placeholder="Enter address" name="address" required required="required">


                <label for="county"><b>County: </b></label>
                <input type="text" placeholder="Enter Country" name="county" required maxlength="20"
                    required="required">
                <!-- <select name="county" maxlength="20">
                    <option value="mombasa">Mombasa</option>
                    <option value="bungoma">Bungoma</option>
                    <option value="nyeri">Nyeri</option>
                    <option value="uasin gishu">Uasin Gishu</option>
                    <option value="nakuru">Nakuru</option>
                    <option value="murang'a">Murang'a</option>
                    <option value="kiambu">Kiambu</option>
                </select> -->


                <label for="district"><b>District: </b></label>
                <input type="text" placeholder="Enter district" name="district" required maxlength="20"
                    required="required">
                <!-- <select name="district" maxlength="20">
                    <option value="Kangema">Kangema</option>
                    <option value="kanduyi">Kanduyi</option>
                    <option value="Mwiki">Mwiki</option>
                    <option value="naivasha">Naivasha</option>
                    <option value="nakuru">Mathira</option>
                    <option value="mathioya">Mathioya</option>
                    <option value="kesses">Kesses</option>
                </select> -->

                <label for="location"><b>State: </b></label>
                <input type="text" placeholder="Enter state" name="location" required maxlength="20"
                    required="required">
                <!-- <select name="location" maxlength="20">
                    <option value="githi">Githi</option>
                    <option value="kibabii">Kibabii</option>
                    <option value="butieli">Butieli</option>
                    <option value="konyu">Konyu</option>
                    <option value="free area">Free area</option>
                    <option value="zimmerman">Zimmerman</option>
                    <option value="kirigiti">Kirigiti</option>
                </select> -->


                <label for="dob"><b>Date of birth: </b></label>
                <input type="date" name="dob" required maxlength="20" required="required">


                <label for="photo"><b>Upload photo: </b></label>
                <input type="file" name="photo" value="" size="32" required="required">


                <label for="regDate"><b>Registration date: </b></label>
                <input type="date" name="regDate" min="2019-05-18" required maxlength="20" />

                <button class="frmBtn" type="submit">Register</button>

                <button class="frmBtn" style="background-color: red; color: white" type="reset">Reset</button>
                <br />
                <a href="index.php" style="margin-top: 10px;">Sign in</a><br />
                <input type="hidden" name="MM_insert" value="form" />
            </form>
        </div>
    </center>
    <script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }
    </script>
    <script>
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
    </script>


</body>
</html>