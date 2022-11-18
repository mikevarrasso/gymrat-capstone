<?php
// This is the page where the admin can see all website data like all gyms, users and reviews.
// This page is only accessible to admin accounts
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/adminData.php");
include("classes/review.php");

//Verify the users id exists and is a number to prevent malicious software being entered 
if (isset($_SESSION['gymrat_userid']) && is_numeric($_SESSION['gymrat_userid'])) {
    $id = $_SESSION['gymrat_userid'];
    $login = new Login();
    $result = $login->verify_login($id);

    // Get this users information
    $user = new UserData();
    $user_data = $user->get_data($id);
    $user_type = $user_data['user_type'];

    // Verify that the user is logged in and is of type admin
    if ($result && $user_type == 2) {
        // Get database information
        $admin = new AdminData();
        $all_users = $admin->get_all_users();
        $all_gyms = $admin->get_all_gyms();
        $all_reviews = $admin->get_all_reviews();

        if (!$user_data) {
            //Send back to login page if there is no data for that user
            header("Location: login.php");
        }
    } else {
        //Send back to login page
        header("Location: login.php");
        die;
    }
} else {
    //Send back to login page
    header("Location: login.php");
    die;
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="Images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Admin Data</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="Images/Rat.png" height="60px" /></div>
    </div>

    <!-- Here is where the  -->
    <div id="adminPage">
        <div id="topRow">
            <div id="adminUsersSection" class="adminCard">
                <div id="formTitle">Users</div>
                <div id="allUsers" class="adminDataSection"></div>
            </div>
            <div id="adminGymsSection" class="adminCard">
                <div id="formTitle">Gyms</div>
                <div id="allGyms" class="adminDataSection"></div>
            </div>
        </div>
        <div id="bottomRow">
            <div id="adminReviewsSection" class="adminCard">
                <div id="formTitle">Reviews</div>
                <div id="allReviews" class="adminDataSection"></div>
            </div>
            <div id="adminReviewsSection" class="adminCard">
                <div id="formTitle">Data</div>
                <h3 class="dataHeader">Users: </h3>
                <p id="numberOfUsers" class="num"></p>

                <h3 class="dataHeader">Gyms: </h3>
                <p id="numberOfGyms" class="num"></p>

                <h3 class="dataHeader">Reviews: </h3>
                <p id="numberOfReviews" class="num"></p>
            </div>
        </div>
    </div>

    <!-- Back button on bottom of page -->
    <a id="backButton" title="Profile" href="adminProfile.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>

    <script>
        var all_users = <?php echo json_encode($all_users); ?>;
        var all_gyms = <?php echo json_encode($all_gyms); ?>;
        var all_reviews = <?php echo json_encode($all_reviews); ?>;

        document.getElementById("numberOfUsers").innerHTML = all_users.length;
        document.getElementById("numberOfGyms").innerHTML = all_gyms.length;
        document.getElementById("numberOfReviews").innerHTML = all_reviews.length;

        // Fill the users section with the array of users 
        for (i = 0; i < all_users.length; i++) {
            let rev = document.getElementById('allUsers');
            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;margin-bottom:-10px;text-align:left">' +
                all_users[i]['first_name'] + " " + all_users[i]['last_name'] + '</h3><h3 style="margin:10px;text-align:left;font-size:15px;margin-top: 5px;color:grey;margin-bottom:10px">' +
                all_users[i]['email'] + '</h3><a href="adminDeleteUser.php?id=' +
                all_users[i]['user_id'] + '"style="color:#951717;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Delete</a>' + '<p style="margin:10px;margin-bottom:10px;float:right;font-size:10px;color:grey">' +
                all_users[i]['date'].slice(0, 10) + '</p></br></div>';
        }

        // Fill the gyms section with the array of gyms 
        for (i = 0; i < all_gyms.length; i++) {
            let rev = document.getElementById('allGyms');
            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;margin-bottom:-10px;text-align:left">' +
                all_gyms[i]['gym_name'] + '</h3><h3 style="margin:10px;text-align:left;font-size:15px;margin-top: 5px;color:grey;margin-bottom:10px">' +
                all_gyms[i]['gym_type'] + '</h3><a href="adminDeleteGym.php?id=' +
                all_gyms[i]['gym_id'] + '"style="color:#951717;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Delete</a>' + '<p style="margin:10px;margin-bottom:10px;float:right;font-size:10px;color:grey">' +
                all_gyms[i]['date'].slice(0, 10) + '</p></br></div>';
        }

        // Fill the reviews section with the array of reviews 
        for (i = 0; i < all_reviews.length; i++) {
            let rev = document.getElementById('allReviews');
            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;margin-bottom:10px;text-align:left">' +
                all_reviews[i]['gym_name'] + '</h3>' + '<h3 style="margin:10px;margin-bottom:-10px;text-align:left;font-size:15px;margin-top:-15px;color:grey;margin-bottom:10px">By: ' +
                all_reviews[i]['first_name'] + " " +
                all_reviews[i]['last_name'] + '</h3><p style="margin:10px;text-align:left;font-size:15px">' +
                all_reviews[i]['review'] + '</p><a href="adminDeleteReview.php?id=' +
                all_reviews[i]['review_id'] + '"style="color:#951717;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Delete</a>' + '<p style="margin:10px;margin-bottom:10px;float:right;font-size:10px;color:grey">' +
                all_reviews[i]['review_date'].slice(0, 10) + '</p></br></div>';
        }
    </script>
</body>

</html>