<?php
// This class is used to retrieve the data from a given gym
class GymData
{

    // This function will get the reviwes for a gym
    public function get_reviews()
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

    // This function gets the gyms data from the database
    public function get_data($id)
    {
        $query = "select * from gymrat_gyms where gym_id = $id limit 1";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }

    // This function can only be used by admins to delete a gym from the database
    public function delete_gym($gym_id)
    {
        $query = "delete from gymrat_gyms where gym_id=$gym_id";
        $db = new Database();
        $db->save_db($query);
    }
}
