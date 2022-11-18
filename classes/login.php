<?php
// This class is used log a user in
class Login
{
    private $error = "";

    // Evaluate the users login data
    public function evaluate_data($data)
    {
        // Addslashes for security
        $email = addslashes($data['email']);
        $password = addslashes($data['password']);

        $query = "select * from gymrat_users where email = '$email' limit 1";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            $row = $result[0];
            if ($this->hash_func($password) == $row['password']) {
                //Create session for user
                $_SESSION['gymrat_userid'] = $row['user_id'];
            } else {
                $this->error .= "Incorrect password.<br/>";
            }
        } else {
            $this->error .= "No such email was found<br/>";
        }

        return $this->error;
    }

    // Used to make sure a user exists. Often called at the start of other files to ensure
    // that there is a user currently logged in.
    public function verify_login($id)
    {
        $query = "select user_id from gymrat_users where user_id = '$id' limit 1";
        $DB = new Database();
        $result = $DB->read_db($query);

        if ($result) {
            return true;
        }

        return false;
    }

    // Used to hash a users password
    private function hash_func($data)
    {
        $data = hash("sha1", $data);
        return $data;
    }
}
