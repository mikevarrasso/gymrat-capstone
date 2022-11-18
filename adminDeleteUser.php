<?php
// This page will present the admin with a prompt to delete the selected user.
// This page is only accessible to admin accounts. 
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/review.php");

// Verify there is an active user session
if (isset($_SESSION['gymrat_userid']) && is_numeric($_SESSION['gymrat_userid'])) {
    $id = $_SESSION['gymrat_userid'];
    $login = new Login();
    $result = $login->verify_login($id);
    $error = "";
    $btn = "confirm";

    // Get the users type
    $user = new UserData();
    $user_data = $user->get_data($id);
    $user_type = $user_data['user_type'];

    // Verify the result, that the ID is set and the user is an admin
    if ($result && isset($_GET['id']) && $user_type == 2) {
        $user_id = $_GET['id'];
    } else {
        // Redirect the user to the login page
        header("Location: login.php");
        die;
    }
} else {
    //Send back to login page
    header("Location: login.php");
    die;
}

// Get the data for the selected user
$user_clicked = new UserData();
$user_clicked_data = $user_clicked->get_data($user_id);

// If there is a problem getting the users data, display an error message to the user
if (!$user_clicked_data) {
    $user_clicked_data['first_name'] = "";
    $user_clicked_data['last_name'] = "";
    $user_clicked_data['email'] = "";
    $error = "Oops... No user found.";
    $btn = "exit";
}

// Deleting a user
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_user = new UserData();
    $new_user->delete_user($user_id);
    header("Location: adminInfo.php");
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="Images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Delete User</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="Images/Rat.png" height="60px" /></div>
    </div>

    <!-- Delete gym prompt block-->
    <div id="deleteBlock">
        <form method="post">
            <div id="blockTitle">
                <h1 style="margin-bottom:-30px">delete user</h1>
            </div>
            <p>Are you sure you want to delete this user?</p></br>
            <h2><?php echo ($user_clicked_data['first_name']);
                echo (" ");
                echo ($user_clicked_data['last_name']) ?></h2>
            <h2 style="font-size: 15px; color:gray"><?php echo ($user_clicked_data['email']) ?></h2></br>
            <p><?php echo ($error) ?></p>
            <input type="submit" id="deleteButton" value="<?php echo ($btn) ?>" />
        </form>
    </div>

    <!-- Back button on bottom of page -->
    <a id="backButton" title="Profile" href="adminInfo.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>
    <script>

    </script>
</body>

</html>