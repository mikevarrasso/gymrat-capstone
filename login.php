<?php
session_start();
// This is the login page
include("classes/connect.php");
include("classes/login.php");

$email = "";
$password = "";
$result = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = new Login();
    $result = $login->evaluate_data($_POST);

    if ($result == "") {
        header("Location: home.php");
        die;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
}
?>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Log in</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="images/Rat.png" height="60px" /></div>
    </div>

    <div id="loginBlock">
        <form method="post">
            <div id="blockTitle">
                <h1>Log in</h1>
            </div>
            <input name="email" type="text" id="text" placeholder="Email" required /><br />
            <input name="password" type="password" id="text" placeholder="Password" /><br />
            <p> <?php if ($result != "") {
                    echo "<div style='text-align:left;font-size:10px;margin-left:100px;margin-top:-20px;margin-bottom:10px;color:#a6032c;'>";
                    echo $result;
                    echo "</div>";
                } ?></p>
            <input type="submit" id="button" value="Log In" /><br />
            <p id="loginPrompt">Not a member?<a id="loginPromptLink" href="register.php"> Register</a></p>
        </form>
    </div>
</body>

</html>