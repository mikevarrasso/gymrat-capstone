<?php
// This page will present the admin with a prompt to delete the selected review.
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
        $review_id = $_GET['id']; // The review id passed from the admin page
    } else {
        // Redirect the user to the login page
        header("Location: login.php");
        die;
    }
} else {
    // Send back to login page
    header("Location: login.php");
    die;
}

// Display the review
$this_review = new Review();
$rev = $this_review->get_review($review_id);

// If there is a problem getting the review, display an error message
if (!$rev) {
    $rev['gym_type'] = "";
    $rev['gym_name'] = "";
    $rev['review'] = "";
    $error = "Oops... No review found.";
    $btn = "exit";
}

// Deleting a review
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $review = new Review();
    $review->delete_review($review_id);
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
    <title>Gym Rat | Delete Review</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="images/Rat.png" height="60px" /></div>
    </div>

    <!-- Delete review prompt block-->
    <div id="deleteBlock">
        <form method="post">
            <div id="blockTitle">
                <h1 style="margin-bottom:-30px">delete review</h1>
            </div>
            <p>Are you sure you want to delete this review?</p></br>
            <h2><?php echo ($rev['gym_name']) ?></h2>
            <h2 style="font-size: 15px; color:gray"><?php echo ($rev['gym_type']) ?></h2></br>
            <p><?php echo ($rev['review']) ?></p>
            <p><?php echo ($error) ?></p>
            <input type="submit" id="deleteButton" value="<?php echo ($btn) ?>" />
        </form>
    </div>

    <!-- Back button at bottom of page -->
    <a id="backButton" title="Profile" href="adminInfo.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>
    <script>

    </script>
</body>

</html>