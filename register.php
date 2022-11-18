<?php
// This is the page used to register a user account
include("classes/connect.php");
include("classes/register.php");

$first_name = "";
$last_name = "";
$email = "";
$result = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $register = new Register();
    $result = $register->evaluate_data($_POST);

    if ($result != "") {
        // echo "<div style='text-align:center;font-size:20px'>";
        // echo "These errors occured...<br>";
        // echo $result;
        // echo "</div>";
    } else {
        //Redirect user to profile page
        header("Location: login.php");
        die;
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="Images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Register</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="Images/Rat.png" height="60px" /></div>
    </div>

    <!-- Register user information input -->
    <div id="registerBlock">
        <div id="blockTitle">
            <h1>Register</h1>
        </div>
        <form method="post" action="">
            <input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First name" required /><br />
            <input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last name" required /><br />
            <input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email" autocomplete="off" required /><br />
            <input name="password" type="password" id="text" placeholder="Password" autocomplete="off" required /><br />
            <input name="password2" type="password" id="text" placeholder="Confirm Password" required /><br />
            <p> <?php if ($result != "") {
                    echo "<div style='text-align:left;font-size:10px;margin-left:100px;margin-top:-20px;margin-bottom:10px;color:#a6032c;'>";
                    echo $result;
                    echo "</div>";
                } ?></p>
            <input type="submit" id="button" value="Register" required />
            <p id="loginPrompt">Already a member?<a id="loginPromptLink" href="login.php"> Login</a></p>
        </form>
    </div>
</body>

</html>