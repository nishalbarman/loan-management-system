<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = $_POST['loan_id'];
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];

    // Connect to the database
    $conn = mysqli_connect('host', 'username', 'password', 'database');

    // Insert the repayment into the repayments table
    $sql = "INSERT INTO repayments (loan_id, user_id, amount, status) 
          VALUES ($loan_id, $user_id, $amount, 'paid')";
    mysqli_query($conn, $sql);

    // Close the connection to the database
    mysqli_close($conn);

    echo "Repayment made successfully";
}
?>