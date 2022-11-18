<?php
// This is the profile page for a user
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/review.php");

//Verify the users id exists and is a number to prevent malicious software being entered 
if (isset($_SESSION['gymrat_userid']) && is_numeric($_SESSION['gymrat_userid'])) {
    $id = $_SESSION['gymrat_userid'];
    $login = new Login();
    $result = $login->verify_login($id);
    $user = new UserData();
    $user_data = $user->get_data($id);
    $user_type = $user_data['user_type'];

    if ($result && $user_type == 1) {
        //Get users data
        $user_reviews = $user->get_reviews($id);
        $user_favourites = $user->get_favs($id);

        if (!$user_data) {
            //Send back to login page
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
    <link rel="icon" href="images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Profile</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="images/Rat.png" height="60px" /></div>
        <a id="logoutButton" href="logout.php" onclick="return confirm('Are you sure to logout?');"><span title="Logout" id="logout"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="44" height="44" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                    <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                </svg></span></a>
    </div>

    <!-- Profile Info -->
    <div style="margin:auto">
        <div style="text-align:center;">
            <img src="images/banner.png" style="width: 100%; height:350px;">
            <div id="profileCard">
                <img id="profileImage" src="images/dp.png">
                <p id="profileName"><?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?></p>
                <p id="profileSubheader">Member</p>
                <div id="profileReviewsSection">
                    <div id="formTitle">reviews</div>
                    <div id="reviewsProfile"></div>
                </div>
                <div id="profileFavouritesSection">
                    <div id="formTitle">favourites</div>
                    <div id="favouritesProfile"></div>
                </div>
            </div>
        </div>
    </div>

    <a id="backButton" title="Home" href="home.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>

    <script>
        var user_reviews = <?php echo json_encode($user_reviews); ?>;
        var user_favourites = <?php echo json_encode($user_favourites); ?>;
        //Display the user reviews on their profile
        for (i = 0; i < user_reviews.length; i++) {
            let rev = document.getElementById('reviewsProfile');
            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;margin-bottom:-10px;text-align:left">' +
                user_reviews[i]['gym_name'] + '</h3></br><h3 style="margin:10px;margin-bottom:-10px;text-align:left;font-size:15px;margin-top:-15px;color:grey;margin-bottom:10px">' +
                user_reviews[i]['gym_type'] + '</h3><p style="margin:10px;text-align:left;font-size:15px">' +
                user_reviews[i]['review'] + '</p>' + '<a href="deleteReview.php?id=' +
                user_reviews[i]['review_id'] + '"style="color:#951717;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Delete</a>' +
                '<a href="editReview.php?id=' + user_reviews[i]['review_id'] + '" style="color:#0788f2;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Edit</a>' + '<p style="margin:10px;margin-bottom:10px;float:right;font-size:10px;color:grey">' +
                user_reviews[i]['review_date'].slice(0, 10) + '</p></br></div>';
        }
        // Display the users favourites on their profile
        for (i = 0; i < user_favourites.length; i++) {
            let rev = document.getElementById('favouritesProfile');
            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;margin-bottom:10px;text-align:left">' +
                user_favourites[i]['gym_name'] + '</h3>' + '<h3 style="margin:10px;margin-bottom:-10px;text-align:left;font-size:15px;margin-top:-15px;color:grey;margin-bottom:10px">' +
                user_favourites[i]['gym_type'] + '</h3><a href="deleteFavourite.php?id=' +
                user_favourites[i]['favourite_id'] + '"style="color:#951717;text-decoration:none;margin-left:10px;margin-top:7px;float:left;font-size:12px;">Delete</a>' + '<p style="margin:10px;margin-bottom:10px;float:right;font-size:10px;color:grey">' +
                user_favourites[i]['favourite_date'].slice(0, 10) + '</p></br></div>';
        }
    </script>
</body>

</html>