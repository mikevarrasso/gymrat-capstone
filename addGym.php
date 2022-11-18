<?php
// This page will present the admin with a form that allows them to adda new gym to the map on the home screen.
// This page is only accessible to admin accounts. 
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/addGym.php");

// Verify there is an active user session 
if (isset($_SESSION['gymrat_userid']) && is_numeric($_SESSION['gymrat_userid'])) {
    $id = $_SESSION['gymrat_userid'];
    $login = new Login();
    $result = $login->verify_login($id);
    $gym_result = "";

    if ($result) {
        //Get users type (admin or user)
        $user = new UserData();
        $user_data = $user->get_data($id);
        $user_type = $user_data['user_type'];

        // If there is an error fetching the users data or the user is not an admin
        if (!$user_data || $user_type != 2) {
            // Redirect user to login page 
            header("Location: login.php");
        }
    } else {
        // If the login credentials couldnt be verified, send user back to login page
        header("Location: login.php");
        die;
    }
} else {
    // If there is no active session, send user to login page
    header("Location: login.php");
    die;
}

// A post is used to add the form information to the gyms database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new Gym object and add the info to gyms database
    $addGym = new AddGym();
    $gym_result = $addGym->evaluate_data($_POST); // Evaluate data will check that the gym info is valid and then adds the gym to database

    if ($gym_result == "") {
        // If the gym has been added without errors, send the admin to the home page where they can see their new gym on the map
        header("Location: home.php");
    }
}

?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="Images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Add a gym</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="Images/Rat.png" height="60px" /></div>
    </div>

    <!-- Register user information input -->
    <div id="addGymBlock">
        <div id="blockTitle">
            <h1>Add Gym</h1>
        </div>
        <form method="post" action="">
            <input required name="gym_name" type="text" id="text" placeholder="Gym name" /><br />
            <!-- <input required name="gym_type" type="text" id="text" placeholder="Type (MMA or Fitness)" /><br /> -->
            <select required name="gym_type" type="text" id="text" style="background-color: white">
                <option>Fitness</option>
                <option>MMA</option>
            </select><br />
            <input required name="opening_time" type="text" id="addGymtext" placeholder="Opening time" autocomplete="off" />
            <input required name="closing_time" type="text" id="addGymtext" placeholder="Closing time" autocomplete="off" /><br />
            <input required name="phone_number" type="text" id="addGymtext" placeholder="Phone number" autocomplete="off" />
            <input required name="website_link" type="text" id="addGymtext" placeholder="Website link" autocomplete="off" /><br />
            <input required name="province" type="text" id="addGymtext" placeholder="Province" autocomplete="off" />
            <input required name="city" type="text" id="addGymtext" placeholder="City" autocomplete="off" /><br />
            <input required name="address" type="text" id="text" placeholder="Street address" autocomplete="off" /><br />
            <input required name="latitude" type="text" id="addGymtext" placeholder="Latitude" autocomplete="off" />
            <input required name="longitude" type="text" id="addGymtext" placeholder="Longitude" autocomplete="off" /><br />
            <p id="latLongInfo">*Latitude and longitude can be found by right clicking on the gym via Google Maps*</p>
            <p> <?php if ($gym_result != "") {
                    echo "<div style='text-align:left;font-size:10px;margin-left:100px;margin-top:-20px;margin-bottom:10px;color:#a6032c;'>";
                    echo $gym_result;
                    echo "</div>";
                } ?></p>
            <input type="submit" id="button" value="Add gym" />
        </form>
    </div>
    <!-- Back button at the bottom of page -->
    <a id="backButton" title="Profile" href="adminProfile.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>
</body>

</html>