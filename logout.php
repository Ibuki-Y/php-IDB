<?php
session_start();

// sessionを切る
unset($_SESSION['id']);
unset($_SESSION['name']);

header('Location: login.php');
exit();
