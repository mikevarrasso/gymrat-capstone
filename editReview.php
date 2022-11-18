<?php
// This page will present the user with a prompt to edit the selected review.
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
    $error = "";
    $user = new UserData();
    $user_data = $user->get_data($id);
    $user_type = $user_data['user_type'];

    if (isset($_GET['id'])) {
        $review_id = $_GET['id'];
    } else {
        $error = "No such review found... :(";
        echo ($error);
    }

    if ($result) {
        //Get users data
        $user = new UserData();
        $user_data = $user->get_data($id);

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

// Display the review
$this_review = new Review();
$rev = $this_review->get_review($review_id);

// Deleting a post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // echo($_POST['review']);
    $review = new Review();
    $review->edit_review($review_id, $_POST['review']);
    if ($user_type == 1)
        header("Location: profile.php");
    else if ($user_type == 2)
        header("Location: adminProfile.php");
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Edit Review</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="images/Rat.png" height="60px" /></div>
    </div>

    <div id="deleteBlock">
        <form method="post">
            <div id="blockTitle">
                <h1 style="margin-bottom:-30px">edit review</h1>
            </div>
            <h2><?php echo ($rev['gym_name']) ?></h2>
            <h2 style="font-size: 15px; color:gray"><?php echo ($rev['gym_type']) ?></h2></br>
            <input id="editReviewField" type="text" name="review" value="<?php echo ($rev['review']) ?>" />
            <input type="submit" id="deleteButton" value="Save" />
        </form>
    </div>

    <a id="backButton" title="Profile" href="javascript:history.back()"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="15 6 9 12 15 18"></polyline>
        </svg></a>
    <script>

    </script>
</body>

</html>