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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "applyloan")) {
    $tableName = $_SESSION['MM_Username'] . "_" . mt_rand(100000, 999999) . "_" . $_POST['loanType'] . "_repayment";
    $tableName = str_replace(" ", "_", $tableName);

    $insertSQL = sprintf(
        "INSERT INTO loan (memberId, loanType, income, amount, intereset, payment_term, total_paid, emi_per_month, security, posting_date,status, balance) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_SESSION['MM_Username'], "int"),
        GetSQLValueString($_POST['loanType'], "text"),
        GetSQLValueString($_POST['income'], "int"),
        GetSQLValueString($_POST['amount'], "int"),
        GetSQLValueString($_POST['intereset'], "text"),
        GetSQLValueString($_POST['payment_term'], "int"),
        GetSQLValueString($_POST['total_paid'], "int"),
        GetSQLValueString($_POST['emi_per_month'], "int"),
        GetSQLValueString($_POST['security'], "text"),
        GetSQLValueString($_POST['posting_date'], "date"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['income'], "int")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysql_error());


    $createTable = "CREATE TABLE `" . $tableName . "` (
        `id` int(255) NOT NULL,
        `paymentId` int(255) NOT NULL,
        `memberId` int(255) NOT NULL,
        `amount` float NOT NULL,
        `loanName` text NOT NULL,
        `status` text NOT NULL DEFAULT 'Unpaid'
      )";
    $memberId = $_SESSION['MM_Username'];
    $loanType = $_POST['loanType'];
    $appliedLoan = "INSERT INTO `applied_loans` (`memberId`, `loanTableName`, `loanName`) VALUES($memberId, '$tableName', '$loanType')";

    $result = mysqli_query($mlms, $createTable);
    $result2 = mysqli_query($mlms, $appliedLoan);

    $paymentTerm = $_POST['payment_term'];
    $memberId = $_SESSION['MM_Username'];
    $amount = $_POST['emi_per_month'];

    for ($index = 1; $index <= $paymentTerm * 12; $index++) {
        $sqlQuery = "INSERT INTO `" . $tableName . "` (`id`, `paymentId`, `memberId`, `amount`, `loanName`, `status`) VALUES($index, $index, $memberId, $amount, '$loanType', 'Unpaid');";
        $result22 = mysqli_query($mlms, $sqlQuery);
    }

    $insertGoTo = "applyloan.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    echo "<script>alert('Applied Successfully'); window.location = '" . $insertGoTo . "'</script>";
    // header(sprintf("Location: %s", $insertGoTo));
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
    $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysqli_select_db($mlms, $database_mlms);
$query_Recordset1 = "SELECT * FROM loantype ORDER BY id DESC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($mlms, $query_limit_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
    $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
    $all_Recordset1 = mysqli_query($mlms, $query_Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1 / $maxRows_Recordset1) - 1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (
            stristr($param, "pageNum_Recordset1") == false &&
            stristr($param, "totalRows_Recordset1") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Member</title>
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

        input,
        select {
            margin-bottom: 10px;
            height: 30px;
            width: 100%
        }
    </style>


</head>
<body>

    <?php include 'header/user.php'; ?>
    <p></p>
    <div class="main">
        <h3 class="llTl">Apply loan</h3>
        <form action="<?php echo $editFormAction; ?>" method="POST" name="applyloan">

            <label for="loanType"><b>Loan type:
                </b></label><br />
            <select name="loanType" autocomplete="off">
                <option value="">--- Select Loan ---</option>
                <?php do { ?>
                    <option>
                        <?php echo $row_Recordset1['loanType']; ?>
                    </option>

                <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                <?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a
                        href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>">First</a>
                <?php } // Show if not first page ?>
                <?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a
                        href=" <?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Previous</a>
                <?php } // Show if not first page ?>
                <?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a
                        href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Next</a>
                <?php } // Show if not last page ?>
                <?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a
                        href=" <?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Last</a>
                <?php } // Show if not last page ?>
                Records
                <?php echo ($startRow_Recordset1 + 1) ?> to
                <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?>
                of
                <?php echo $totalRows_Recordset1 ?>
            </select><br />

            <label for="income"><b>Monthly income:
                </b></label><br />
            <input type="number" name="income" pattern="[0-9]*" required />
            <br />
            <script>
                function loanamount() {
                    var original = document.getElementById("original").value;
                    var interest = document.getElementById("interest").value;
                    var year = document.getElementById("payment_term").value;

                    var interest1 = (Number(original) * Number(interest) * Number(year)) / 100;
                    var total = Number(original) + Number(interest1);

                    var emi = total / (year * 12);
                    document.getElementById("total_paid").value = total;
                    document.getElementById("emi_per_month").value = emi;

                }
            </script>

            <label for="amount"><b>Loan amount:
                </b></label><br />
            <input type="number" id="original" name="amount" pattern="[0-9]*" required /><br />


            <label for="intereset"><b>Loan
                    interest(%):</b></label><br />
            <input type="text" name="intereset" id="interest" value="7" pattern="[0-9]*" readonly="true" required><br />


            <label for="payment_term"><b>Payment term(in years):</b></label><br />
            <select onchange="loanamount()" name="payment_term" pattern="[0-9]*" id="payment_term" required>
                <option value="">Term of Payment</option>
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    echo "<option value='" . $i . "'>" . $i . "</option>";
                }
                ?>
            </select><br />


            <label for="total_paid"><b>Total
                    Amount:</b></label><br />
            <input type="text" id="total_paid" name="total_paid" pattern="[0-9]*" readonly /><br />


            <label for="emi_per_month"><b>Monthly payment:</b></label><br />
            <input type="text" id="emi_per_month" pattern="[0-9]*" name="emi_per_month" readonly /><br />


            <!-- <label for="bankStatementPhoto"><b>Statement Photo:</b></label><br />
            <input type="file" name="bankStatementPhoto" autocomplete="off" required /><br />


            <label for="security"><b>Loan Security:</b></label><br />
            <input type="file" name="security" autocomplete="off" required /><br /> -->


            <label for="posting_date"><b>Application
                    date:</b></label><br />
            <input type="date" name="posting_date" min="2019-05-24" required /><br />


            <label for="memberId"><b>Member ID :</b></label><br />
            <input type="text" name="memberId" autocomplete="off" pattern="[0-9]*"
                value="<?php echo $_SESSION['MM_Username']; ?>" placeholder="Enter member id" required readonly
                disabled /><br />

            <input type="hidden" name="MM_insert" value="applyloan" />
            <input type="hidden" name="status" value="Pending" readonly="true" required />

            <button class="frmBtn" type="submit" name="apply" id="apply" class="">Apply</button>

        </form>
        <p></p>
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
            dropdown[i].addEventListener("click", function () {
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