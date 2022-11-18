<?php
// This page will present the admin with a prompt to delete the selected gym.
// This page is only accessible to admin accounts. 
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/gymData.php");
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
        $gym_id = $_GET['id']; // The gym id passed from the admin page
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

// Used to display the gym data
$gym = new GymData();
$gym_info = $gym->get_data($gym_id);

// If there is a problem getting the gym data, display an error message
if (!$gym_info) {
    $gym_info['gym_type'] = "";
    $gym_info['gym_name'] = "";
    $error = "Oops... No gym found.";
    $btn = "exit";
}

// When the page is submitted, delete the gym and redirect the admin to the admin page
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $gym_data = new GymData();
    $gym_data->delete_gym($gym_id);
    header("Location: adminInfo.php");
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Delete Gym</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="images/Rat.png" height="60px" /></div>
    </div>

    <!-- Delete gym prompt block-->
    <div id="deleteBlock">
        <form method="post">
            <div id="blockTitle">
                <h1 style="margin-bottom:-30px">delete gym</h1>
            </div>
            <p>Are you sure you want to delete this gym?</p></br>
            <h2><?php echo ($gym_info['gym_name']) ?></h2>
            <p><?php echo ($error) ?></p>
            <h2 style="font-size: 15px; color:gray"><?php echo ($gym_info['gym_type']) ?></h2></br>
            <input type="submit" id="deleteButton" value="<?php echo ($btn) ?>" />
        </form>
    </div>

    <!-- Back button at the bottom of page -->
    <a id="backButton" title="Profile" href="adminInfo.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>
    <script>

    </script>
</body>

</html>