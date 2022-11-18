<?php
// Class used to create/delete reviews
class Review
{
    private $error = "";
    // This function will be used to send the users post 
    public function create_review($userid, $data)
    {
        if (!empty($data['review']) && !empty($data['reviewGymId'])) {
            $review = addslashes($data['review']);
            $reviewGymId = addslashes($data['reviewGymId']);
            $reviewId = $this->create_reviewid();

            $query = "insert into gymrat_reviews (user_id,review_id,gym_id,review) values ('$userid','$reviewId','$reviewGymId','$review')";
            $db = new Database();
            $db->save_db($query);
        } else {
            $this->error = "Nothing to review here...</br>";
            echo "<script>alert('$this->error')</script>";
        }
        return $this->error;
    }

    // This function will delete a review from the database
    public function delete_review($review_id)
    {
        $query = "delete from gymrat_reviews where review_id=$review_id";
        $db = new Database();
        $db->save_db($query);
    }

    // Randomly generates a review ID
    private function create_reviewid()
    {
        $number = "";
        $length = 4;

        for ($i = 0; $i < $length; $i++) {
            $newRand = rand(0, 9);
            $number = $number . $newRand;
        }
        return $number;
    }

    // This function returns a single review
    public function get_review($review_id)
    {
        $review_id = addslashes($review_id);
        $query = "select * from gymrat_reviews inner join gymrat_gyms on gymrat_reviews.gym_id=gymrat_gyms.gym_id where gymrat_reviews.review_id = $review_id";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }

    public function edit_review($review_id, $new_review)
    {
        $review_id = addslashes($review_id);
        $query = "update gymrat_reviews set review='$new_review' where review_id='$review_id'";
        $DB = new Database();
        $result = $DB->save_db($query);
        echo ($result);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
