<?php

// I, Mike Varrasso, student number 000812269, certify that this material is my original work.
// No other person's work has been used without due acknowledgment 
// and I have not made my work available to anyone else.

// This is the main page of the application.
session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/userData.php");
include("classes/addGym.php");
include("classes/gymData.php");
include("classes/review.php");
include("classes/favourite.php");

//Verify the users id exists and is a number to prevent malicious software being entered 
if (isset($_SESSION['gymrat_userid']) && is_numeric($_SESSION['gymrat_userid'])) {
    $id = $_SESSION['gymrat_userid'];
    $login = new Login();
    $result = $login->verify_login($id);

    //Get the gyms from database
    $gym = new AddGym();
    $gyms = $gym->get_gyms();

    $gym_data = new GymData();
    $gym_reviews = $gym_data->get_reviews();

    if ($result) {
        //Get users data
        $user = new UserData();
        $user_data = $user->get_data($id);
        $user_type = $user_data['user_type'];

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

// Leaving a review on a gym
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Verify the user id
    $id = $_SESSION['gymrat_userid'];

    if (array_key_exists('favouriteGymId', $_POST)) {
        $favourite = new Favourite();
        $favourite->create_favourite($id, $_POST);
    } else {
        $review = new Review();
        $review->create_review($id, $_POST);
    }
    header("Location: home.php");
}
?>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gymrat.css">
    <link rel="icon" href="Images/Rat.png" type="image/x-icon" />
    <title>Gym Rat | Home</title>
</head>

<body>
    <!-- Page header -->
    <div id="bar">
        <div id="barTitle">Gym Rat</div>
        <div id="logo"><img src="Images/Rat.png" height="60px" /></div>
        <div id="profileSection">
            <a id="profileLink" href="profile.php" title="Profile"><?php echo $user_data['first_name'] ?> </a>
        </div>
    </div>

    <div id="homePage">
        <!-- Div encapsulating that map and controls -->
        <div id="mapCard">
            <!-- Empty div for map to be displayed in -->
            <div id="map"></div>
            <!-- Map controls/buttons -->
            <div id="mapControls">
                <div id="locate">
                    <button id="geolocate" class="btn" title="Find my location">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-current-location" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            <circle cx="12" cy="12" r="8"></circle>
                            <line x1="12" y1="2" x2="12" y2="4"></line>
                            <line x1="12" y1="20" x2="12" y2="22"></line>
                            <line x1="20" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="12" x2="4" y2="12"></line>
                        </svg>
                    </button>
                    <input id="geocode" type="text" placeholder="Enter an address" />
                </div>
                <div id="belowMap">
                    <button id="all" class="btn">All</button>
                    <button id="filter1" class="btn">Health & Fitness</button>
                    <button id="filter2" class="btn">Martial arts</button>

                    <!-- <h1 id="locatorSubTitle">directions</h1> -->
                    <button id="carDirections" class="btn" title="Directions by car">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-car" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="7" cy="17" r="2"></circle>
                            <circle cx="17" cy="17" r="2"></circle>
                            <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                        </svg>
                    </button>
                    <button id="bikeDirections" class="btn" title="Directions by bicycle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bike" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="5" cy="18" r="3"></circle>
                            <circle cx="19" cy="18" r="3"></circle>
                            <polyline points="12 19 12 15 9 12 14 8 16 11 19 11"></polyline>
                            <circle cx="17" cy="5" r="1"></circle>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Div encapsulating the reviews section -->
        <div id="formSection">
            <div id="reviewsCard">
                <div id="formTitle">Gym Reviews</div>
                <!-- Put the posts in this div -->
                <div id="reviews">
                </div>
            </div>
            <div id="formInput">
                <form method="post">
                    <input name="review" id="formTextField" type="text" placeholder="Leave review here..." />
                    <input name="reviewGymId" id="idTextField" type="hidden" />
                    <button class="btn" id="reviewButton" title="Post comment">Post</button>
                </form>
            </div>
        </div>

        <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbsua4EskTMpwMIvO5vkvTgw35rhCwTfs&callback=initMap"></script>
        <script>
            var map;
            var markers = [];
            var destination = [];
            var origin;
            var new_icon;
            var gyms = <?php echo json_encode($gyms); ?>;
            var gym_reviews = <?php echo json_encode($gym_reviews); ?>;
            var user_type = <?php echo $user_type; ?>;

            // Change the profile page link if the user is an admin
            if (user_type == 2) {
                console.log("This is an admin...");
                document.getElementById("profileSection").innerHTML = '<a id="profileLink" href="adminProfile.php" title="Profile"><?php echo $user_data['first_name'] ?> </a>';
            } else if (user_type == 1) {
                console.log("This is a user...");
            }

            // Initiate the map object on page 
            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: 43.229,
                        lng: -79.8134
                    },
                    zoom: 11,
                });

                geocoder = new google.maps.Geocoder();
                infowindow = new google.maps.InfoWindow();
                directionsRenderer = new google.maps.DirectionsRenderer();

                // The info window shows the user gym information when a pin is clicked 
                marker_clicked = function() {
                    infowindow.setContent(
                        "<h2>" + this.gym_name + "</h2><b>" + this.gym_type + "</b><br/><br/>" +
                        this.address + "<br/>" + this.city + ", " + this.province +
                        "</br></br>Open: <b>" + this.opening_time +
                        "</b>&ensp; Close: <b>" + this.closing_time + "</b></br>" +
                        '<a href=" ' + this.website_link + '">' + this.website_link + "</a></br></br>" +
                        '<form method="post"><button>Add to favourites</button>' +
                        '<input name="favouriteGymId" id="gymIdTextField" type="hidden" value=' + this.gym_id + '></form>'
                    );
                    content = infowindow.open(map, this);
                    destination = {
                        lat: parseFloat(this.latitude),
                        lng: parseFloat(this.longitude)
                    };

                    // Pull up gym reviews
                    gymid = this.gym_id;
                    let rev = document.getElementById('reviews');
                    rev.innerHTML = ""; // Reset the reviews column
                    reviewcount = 0; // Reset the reviews counter
                    document.getElementById('idTextField').value = this.gym_id;

                    for (i = 0; i < gym_reviews.length; i++) {
                        if (gym_reviews[i]['gym_id'] == gymid) {
                            reviewcount++;
                            rev.innerHTML += '<div style="box-shadow: 0 3px 7px -2px grey;"><h3 style="margin:10px;text-align:left;margin-bottom:-30px">' +
                                gym_reviews[i]['first_name'] + " " +
                                gym_reviews[i]['last_name'] + '</h3></br><p style="margin:10px;margin-bottom:-5px;text-align:left;font-size:15px">' +
                                gym_reviews[i]['review'] + '</p></br></br><p style="padding-bottom:2px;margin:10px;text-align:right;font-size:10px;color:grey;margin-top:-30px">' +
                                gym_reviews[i]['review_date'].slice(0, 10) + '</p>' + '</div>';
                        }
                    }
                    // If their are no reviews for the gym, display to user
                    if (reviewcount == 0) {
                        console.log("No reviews yet :(")
                        rev.innerHTML = '<p style="margin:10px;margin-bottom:-5px;text-align:left;font-size:15px">No reviews here yet...</p>';
                    }

                };

                // Direction services
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer();

                // Place markers on the map for all the gyms
                for (i = 0; i < gyms.length; i++) {
                    // Set the icon based on the category of the gym
                    if (gyms[i].gym_type == "MMA")
                        new_icon =
                        "Images/mma.png";
                    else if (gyms[i].gym_type == "Fitness")
                        new_icon =
                        "Images/weights.png";

                    // Create the marker based on the array in the Gyms.js file
                    new_marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(gyms[i].latitude),
                            lng: parseFloat(gyms[i].longitude)
                        },
                        title: gyms[i].gym_name,
                        icon: new_icon,
                    });

                    // Place the marker onto the map
                    new_marker.setMap(map);

                    // Save the properties of the marker object
                    new_marker.gym_id = gyms[i].gym_id;
                    new_marker.gym_name = gyms[i].gym_name;
                    new_marker.gym_type = gyms[i].gym_type;
                    new_marker.address = gyms[i].address;
                    new_marker.province = gyms[i].province;
                    new_marker.city = gyms[i].city;
                    new_marker.latitude = gyms[i].latitude;
                    new_marker.longitude = gyms[i].longitude;
                    new_marker.opening_time = gyms[i].opening_time;
                    new_marker.closing_time = gyms[i].closing_time;
                    new_marker.website_link = gyms[i].website_link;

                    // Open info window when the marker is clicked
                    new_marker.addListener("click", marker_clicked);
                    markers.push(new_marker);
                }
            }

            // Find the users location based on the address they enter (geocoding)
            function codeAddress() {
                var address = document.getElementById("geocode").value;
                console.log(address)
                if (address !== "") {
                    geocoder.geocode({
                        address: address
                    }, function(results, status) {
                        if (status == "OK") {
                            //Place a marker on the map at the position
                            var marker = new google.maps.Marker({
                                map: map,
                                position: results[0].geometry.location,
                                icon: "Images/home.png",
                                title: address,
                            });
                            origin = marker.position;
                        } else {
                            alert(
                                "Geocode was not successful... \nEnter a valid address in the text field"
                            );
                        }
                    });
                }
            }

            // Get the directions to the desired gym
            function getDrivingDirections() {
                directionsService = new google.maps.DirectionsService();

                directionsRenderer.setMap(map);
                request = {
                    origin: origin,
                    destination: destination,
                    travelMode: "DRIVING",
                };

                directionsService.route(request, function(result, status) {
                    if ((status = "OK")) {
                        directionsRenderer.setDirections(result);
                        console.log(result);
                    } else {
                        alert("Cannot find directions here.. Please try again");
                    }
                });
            }

            // Get the directions to the desired gym
            function getBikeDirections() {
                directionsService = new google.maps.DirectionsService();

                directionsRenderer.setMap(map);
                request = {
                    origin: origin,
                    destination: destination,
                    travelMode: "BICYCLING",
                };

                directionsService.route(request, function(result, status) {
                    if ((status = "OK")) {
                        directionsRenderer.setDirections(result);
                        console.log(result);
                    } else {
                        alert("Cannot find directions here.. Please try again");
                    }
                });
            }

            // Listen for the user to enter an address
            geocodeBox = document.getElementById("geocode");
            geocodeBox.addEventListener("keypress", function(event) {
                // If the user presses the "Enter" key on the keyboard
                if (event.key === "Enter") {
                    console.log("hello");
                    codeAddress();
                }
            });

            // This function will get the users position on the map via geolocation
            function userPosition(position) {
                // Create a marker on the users location
                user_location = new google.maps.Marker({
                    position: {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    },
                    title: "Current location",
                    icon: "Images/person.png",
                });

                // Place the marker on the map
                user_location.setMap(map);
                origin = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
            }

            // Call userPosition after finding the user's current location
            document.getElementById("geolocate").onclick = function() {
                navigator.geolocation.getCurrentPosition(userPosition);
            };

            // Filter for health and fitness gyms
            function filterHealthandFitness() {
                document.getElementById("filter2").disabled = false;
                document.getElementById("filter1").disabled = true;
                document.getElementById("all").disabled = false;
                for (i = 0; i < markers.length; i++) {
                    if (markers[i].gym_type == "Fitness") {
                        markers[i].setMap(map);
                    } else {
                        markers[i].setMap(null);
                    }
                }
            }

            // Filter for mma gyms
            function filterMartialArts() {
                document.getElementById("filter2").disabled = true;
                document.getElementById("filter1").disabled = false;
                document.getElementById("all").disabled = false;
                for (i = 0; i < markers.length; i++) {
                    if (markers[i].gym_type == "MMA") {
                        markers[i].setMap(map);
                    } else {
                        markers[i].setMap(null);
                    }
                }
            }

            // Display all markers
            function displayAll() {
                document.getElementById("filter2").disabled = false;
                document.getElementById("filter1").disabled = false;
                document.getElementById("all").disabled = true;
                for (i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            // Default filter set to all (grayed out background)
            document.getElementById("all").disabled = true;
            // When a button is clicked, filter the markers to the users desire
            document.getElementById("all").onclick = displayAll;
            document.getElementById("filter1").onclick = filterHealthandFitness;
            document.getElementById("filter2").onclick = filterMartialArts;
            document.getElementById("carDirections").onclick = getDrivingDirections;
            document.getElementById("bikeDirections").onclick = getBikeDirections;
        </script>
</body>

</html>