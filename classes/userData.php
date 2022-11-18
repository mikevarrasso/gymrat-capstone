<?php
// This class is used to retrieve a users data
class UserData
{
    //This function is used to retrieve the users data
    public function get_data($id)
    {
        //Used to prevent sql injections 
        $id = addslashes($id);

        $query = "select * from gymrat_users where user_id = $id limit 1";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }

    // This function is used to retrieve the users reviews from the database
    public function get_reviews($id)
    {
        // Used to prevent sql injections 
        $id = addslashes($id);
        $query = "select * from gymrat_reviews inner join gymrat_gyms on gymrat_reviews.gym_id=gymrat_gyms.gym_id where gymrat_reviews.user_id = $id";
        $DB = new Database();
        $result = $DB->read_db($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    // This function is used to retrieve the users favourites from the database
    public function get_favs($id)
    {
        // Used to prevent sql injections 
        $id = addslashes($id);
        $query = "select * from gymrat_favourites inner join gymrat_gyms on gymrat_favourites.gym_id=gymrat_gyms.gym_id where gymrat_favourites.user_id = $id";
        $DB = new Database();
        $result = $DB->read_db($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    // This function can only be used by admins to delete another user from the database
    public function delete_user($user_id)
    {
        $query = "delete from gymrat_users where user_id=$user_id";
        $db = new Database();
        $db->save_db($query);
    }
}
