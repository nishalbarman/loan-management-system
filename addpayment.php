<?php require_once('Connections/mlms.php'); ?>
<?php

session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./index.php");
    exit;
}


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
    $tableName = $_POST['loanType'];
    $insertSQL = sprintf(
        "INSERT INTO payment (paymentId, memberId, fName, lName, amount, phone, payment_date, loanType) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_POST['paymentId'], "text"),
        GetSQLValueString($_SESSION['MM_Username'], "int"),
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['amount'], "int"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['payment_date'], "date"),
        GetSQLValueString($_POST['loanType'], "text")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysqli_error($mlms));
    $pyId = $_POST['paymentId'];
    $sqlQuery = "UPDATE " . $tableName . " SET `status`='Paid' WHERE `id`=$pyId";
    $res = mysqli_query($mlms, $sqlQuery);

    $insertGoTo = "addpayment.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

$memberId = $_SESSION['MM_Username'];

$sql = "SELECT * FROM payment WHERE memberId=$memberId";
$res = mysqli_query($mlms, $sql);
$payments_count = mysqli_num_rows($res);
?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
<link rel="stylesheet" type="text/css" href="Assets/css/style.css">
<style>
td {
    font-size: 20px;
}

form {
    padding: 30px;
    border: 1px solid greenyellow;
    display: inline-block;
    border-radius: 20px;
}
</style>
</head>
<body>
    <?php include 'header/user.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <h3 class="llTl">Add payment</h3>
                <form name="form" method="POST" class="form" action="<?php echo $editFormAction; ?>">
                    <table>
                        <tr>
                            <td style="text-align: left;">
                                <label for="paymentId"><b>Select Loan:</b></label>
                                <?php
                                $sql = "select `loanName`, `loanTableName` from applied_loans where memberId=$memberId";
                                $response = mysqli_query($mlms, $sql);
                                $response = mysqli_fetch_all($response, MYSQLI_ASSOC);
                                ?>
                                <select id="loantypeSel" name="loanType" onchange="selectionFunction(this)">
                                    <option>Choose</option>
                                    <?php if (!empty($response)) {
                                        foreach ($response as $sel): ?>
                                    <option value="<?php echo $sel["loanTableName"]; ?>">
                                        <?php echo $sel["loanName"]; ?>
                                    </option>
                                    <?php endforeach;
                                    } ?>
                                </select>

                            </td>
                            <td></td>
                            <td style=" text-align: left;">
                                <label for="memberId"><b>Member ID:</b></label>
                                <input type="text" placeholder="Enter Member id" pattern="[0-9]*" name="memberId"
                                    value="<?php echo $memberId; ?>" maxlength="20" disabled readonly>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">

                                <label for="fName"><b>First name:</b></label>
                                <input type="text" placeholder="Enter First name" pattern="[a-z A-Z]*" name="fName"
                                    maxlength="20" required>
                            </td>
                            <td></td>
                            <td style="text-align: left;">
                                <label for="lName"><b>Second name: </b></label>
                                <input type="text" placeholder="Enter second Name" pattern="[a-z A-Z]*" name="lName"
                                    maxlength="20" required>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <label for="amount"><b>Amount:</b></label>
                                <input id="amount" type="text" placeholder="Enter amount" name="amount" maxlength="20"
                                    required readonly>
                            </td>
                            <td></td>
                            <td style="text-align: left;">

                                <label for="phone"><b>Phone Number:</b></label>
                                <input type="text" placeholder="Enter Phone number" name="phone" required maxlength="20"
                                    required="required">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <label for="payment_date"><b>Payment date:</b></label>
                                <input type="date" name="payment_date" maxlength="20" required>
                            </td>
                            <td></td>
                            <td id="pymnt" style="text-align: left; display: none;">
                                <label for="paymentId"><b>Payment Id:</b></label>
                                <div id="pyidDiv">
                                    <!-- <input id="pymntValue" name="paymentId" type="text" placeholder="Reselect the loan"
                                    value="" required disabled> -->
                                </div>

                            </td>
                        </tr>

                        <input type="hidden" name="MM_insert" value="form" />
                    </table>
                    <button class="frmBtn" type="submit" class="registerbtn">Add payment</button>
                    <button class="frmBtn" type="reset" class="registerbtn"
                        style="background-color: red; color: white;">Reset</button>
                </form>

            </div>

        </div>
        <script>
        function selectionFunction(target) {
            let value = target.value;
            console.log(target.value);
            fetch("getid.php?loan=" + value).then(res => res.json()).then(data => {
                console.log(data);
                document.getElementById("pymnt").style.display = "";
                const container = document.getElementById('pyidDiv');
                container.innerHTML = "";

                const input = document.createElement("input");
                input.type = "text";
                input.value = data.result + 1;
                input.setAttribute("name", "paymentId")
                input.setAttribute("readonly", "true")
                input.setAttribute("value", data.result + 1)
                container.appendChild(input);

                document.getElementById('amount').value = data.amount;

            });

        }
        </script>

    </center>
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