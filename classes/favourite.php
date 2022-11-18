<?php
// This class is used to create/remove a favourite 
class Favourite
{
    private $error = "";
    // This function will be used to send the users post 
    public function create_favourite($id, $data)
    {
        if (!empty($data['favouriteGymId'])) {  
            $favouriteId = $this->create_favouriteid();
            $gymid = $data['favouriteGymId'];

            $query = "insert into gymrat_favourites (favourite_id,user_id,gym_id) values ('$favouriteId','$id','$gymid')";
            $db = new Database();
            $db->save_db($query);
        } else {
            $this->error = "Nothing to favourite here...</br>";
            echo ($this->error);
        }
        return $this->error;
    }

    // This function will delete a favourite from the database
    public function delete_favourite($favourite_id)
    {
        $query = "delete from gymrat_favourites where favourite_id=$favourite_id";
        $db = new Database();
        $db->save_db($query);
    }

    // Randomly generates a favourite ID
    private function create_favouriteid()
    {
        $number = "";
        $length = 3;

        for ($i = 0; $i < $length; $i++) {
            $newRand = rand(0, 9);
            $number = $number . $newRand;
        }
        return $number;
    }

    // This function is used to retrieve a single favourite
    public function get_fav($id)
    {
        // Used to prevent sql injections 
        $id = addslashes($id);
        $query = "select * from gymrat_favourites inner join gymrat_gyms on gymrat_favourites.gym_id=gymrat_gyms.gym_id where gymrat_favourites.favourite_id = $id";
        $DB = new Database();
        $result = $DB->read_db($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
