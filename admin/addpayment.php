<?php require_once('../Connections/mlms.php'); ?>
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
    $memberId = $_POST['memberId'];

    $pyId = $_POST['paymentId'];
    $sqlQuery = "UPDATE `" . $tableName . "` SET `status`='Paid' WHERE `id`=$pyId";
    $res = mysqli_query($mlms, $sqlQuery);

    $insertSQL = sprintf(
        "INSERT INTO payment (paymentId, memberId, fName, lName, amount, phone, payment_date, loanType) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($pyId, "text"),
        GetSQLValueString($memberId, "int"),
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['amount'], "int"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['payment_date'], "date"),
        GetSQLValueString($_POST['loanType'], "text")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysqli_error($mlms));

    // $sql = "SELECT * FROM " . $tableName . " WHERE status='Unpaid'";
    // $res = mysqli_query($mlms, $sql);
    // $pyId = mysqli_num_rows($res);
    // // $pyId = $_POST['paymentId'];
    // $sqlQuery = "UPDATE " . $tableName . " SET `status`='Unpaid' WHERE `id`=$pyId";
    // $res = mysqli_query($mlms, $sqlQuery);

    $insertGoTo = "addpayment.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    echo "<script>alert('Payment Added'); window.location = '" . $insertGoTo . "'</script>";
    // header(sprintf("Location: %s", ));
}



?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
<link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
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
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <h3 class="llTl">Add payment</h3>
                <form name="form" method="POST" class="form" action="<?php echo $editFormAction; ?>">
                    <table>
                        <tr>
                            <td style=" text-align: left;">
                                <label for="memberId"><b>Member ID:</b></label>
                                <input type="text" placeholder="Enter Member id" pattern="[0-9]*" name="memberId"
                                    value="" maxlength="20" onchange="populateLoan(this)">
                            </td>
                            <td></td>
                            <td id="selectionContaner" style="text-align: left; display: none;">
                                <label for="paymentId"><b>Select Loan:</b></label>
                                <div id="cont">

                                    <!-- <select id="loantypeSel" name="loanType">
                                        <option>Choose</option>
                                        <?php if (!empty($response)) {
                                            foreach ($response as $sel): ?>
                                                                                                                                                                                                                        <option value=" <?php echo $sel["loanType"]; ?>">
                                                                                                                                                                                                                            <?php echo $sel["loanType"]; ?>
                                                                                                                                                                                                                        </option>
                                                                                                                                <?php endforeach;
                                        } ?>
                                    </select> -->
                                </div>

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
                                    required>
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
                fetch("../getid.php?loan=" + value).then(res => res.json()).then(data => {
                    console.log(data);
                    document.getElementById("pymnt").style.display = "";
                    const container = document.getElementById('pyidDiv');

                    const input = document.createElement("input");
                    input.type = "text";
                    input.value = data.result + 1;
                    input.setAttribute("name", "paymentId")
                    input.setAttribute("value", data.result + 1)
                    container.appendChild(input);

                    document.getElementById('amount').value = data.amount;

                });

            }

            function populateLoan(target) {
                let value = target.value;
                fetch("getLoans.php?member=" + value).then(res => res.json()).then(data => {
                    const container = document.getElementById('cont');
                    container.innerHTML = "";
                    const select = document.createElement('select');
                    select.setAttribute("name", "loanType");
                    select.setAttribute("onchange", "selectionFunction(this)");
                    select.options[select.options.length] = new Option("--- Select ---", "");


                    document.getElementById('selectionContaner').style.display = "";

                    data.forEach(element => {
                        console.log(element);
                        select.options[select.options.length] = new Option(element.loanName, element
                            .loanTableName);

                    });
                    container.appendChild(select);
                })
            }
        </script>

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
            dropdown[i].addEventListener("click", func tion() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if(dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
    </script>


</body>
</html>