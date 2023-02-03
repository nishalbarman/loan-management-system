<?php require_once('Connections/mlms.php'); ?>
<?php
session_start();

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
    $updateSQL = sprintf(
        "UPDATE member SET password=%s WHERE memberId=%s",
        GetSQLValueString($_POST['password'], "text"),
        GetSQLValueString($_SESSION['MM_Username'], "int")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "changepass.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_Recordset1 = "SELECT memberId, password FROM member";
$Recordset1 = mysqli_query($mlms, $query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Member</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
    <style>
    .regForm {
        /* position: absolute;
        left: 50%;
        transform: translate(-50%);
        display: inline-block;
        padding: 25px; */
        width: 60%;
        padding: 25px;
        /* border: 1px solid green;
        border-radius: 10px; */

    }

    input,
    select {
        margin-bottom: 10px;
        height: 30px;
        width: 100%
    }

    label {
        text-align: center;
    }
    </style>
</head>
<body>

    <?php include 'header/user.php'; ?>
    <p></p>

    <div class="mainn">
        <h3 class="llTl">Change Password</h3>
        <form class="regForm" name="form" method="POST" action="<?php echo $editFormAction; ?>" class="fom">


            <label for="memberId"><b>Member
                    Id:</b></label>
            <input type="number" placeholder="" value="<?php echo $_SESSION['MM_Username']; ?>" pattern="[0-9]*"
                size="8" name="newpassword" disabled readonly>

            <label for="password"><b>Enter new password:</b></label>
            <input type="password" placeholder="Enter new password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                name="password">

            <input class="frmBtn" type="submit" value="Change password">
            <input type="hidden" name="MM_update" value="form" />
        </form>
    </div>

    <script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
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
<?php
mysqli_free_result($Recordset1);
?>