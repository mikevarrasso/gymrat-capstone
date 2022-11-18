<?php
// This class is used by admins to get all site data
class AdminData
{
    //This function is used to retrieve all of the users from the database
    public function get_all_users()
    {
        $query = "select * from gymrat_users";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    // This function is used to retrieve the reviews from the database
    public function get_all_reviews()
    {
        $query = "select * from gymrat_reviews join gymrat_gyms on gymrat_reviews.gym_id=gymrat_gyms.gym_id join gymrat_users on gymrat_users.user_id=gymrat_reviews.user_id";
        $DB = new Database();
        $result = $DB->read_db($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    // This function is used to retrieve all the gyms from the database
    public function get_all_gyms()
    {
        $query = "select * from gymrat_gyms";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
