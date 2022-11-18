<?php
// This file will be used to log a signed in member out of their account.
session_start();
if (isset($_SESSION['gymrat_userid'])) {
    $_SESSION['gymrat_userid'] = NULL;
    unset($_SESSION['gymrat_userid']);
}
header("Location: login.php");
die;
