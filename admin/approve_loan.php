<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
require_once __DIR__ . '/../includes/classes/Admin.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loanId = intval($_POST['loan_id']);
    try {
        $user->approveLoan($loanId);
        header("Location: loans.php");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
