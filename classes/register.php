<?php
// This class is used to register a new user
class Register
{
    private $error = "";
    // Validate the users input
    public function evaluate_data($data)
    {
        $password1 = "";
        $password2 = "";

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->error = $this->error . $key . " is empty.<br>";
            }

            // Validate email
            if ($key == 'email') {
                if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
                    $this->error = $this->error . "Invalid email address format<br>";
                }
            }

            // Validate first name
            if ($key == 'first_name') {
                if (is_numeric($value)) {
                    $this->error = $this->error . "First name cant be number<br>";
                }
                if (strstr($value, " ")) {
                    $this->error = $this->error . "First name cant contain spaces<br>";
                }
            }

            // Validate last name
            if ($key == 'last_name') {
                if (is_numeric($value)) {
                    $this->error = $this->error . "Last name cant be number<br>";
                }
                if (strstr($value, " ")) {
                    $this->error = $this->error . "Last name cant contain spaces<br>";
                }
            }

            // Check strength of password
            if ($key == 'password') {
                $password1 = $value;
                // Validate password strength
                $uppercase = preg_match('@[A-Z]@', $value);
                $lowercase = preg_match('@[a-z]@', $value);
                $number    = preg_match('@[0-9]@', $value);
                $specialChars = preg_match('@[^\w]@', $value);
                // Doesnt count _ as special characters

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($value) < 8) {
                    $this->error = $this->error . 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
                }
            }

            // Check if passwords match
            if ($key == 'password2') {
                $password2 = $value;
                if ($password1 != $password2) {
                    $this->error = $this->error . 'Passwords do not match.';
                }
            }
        }
        // If there are no errors, create the new user
        if ($this->error == "") {
            $this->create_user($data);
        } else {
            return $this->error;
        }
    }

    // This function will enter the users data into the database
    public function create_user($data)
    {
        $user_type = 1;
        $user_id = $this->create_userid();
        $first_name = ucfirst($data['first_name']);
        $last_name = ucfirst($data['last_name']);
        $email = $data['email'];
        $password = $this->hash_func($data['password']);
        $url_address = strtolower($first_name) . "." . strtolower($last_name);

        $query = "insert into gymrat_users (user_type,user_id,first_name,last_name,email,password,url_address) 
        values ('$user_type','$user_id','$first_name','$last_name','$email','$password','$url_address')";

        $DB = new Database();
        $DB->save_db($query);
    }

    // This function generates a random userID
    private function create_userid()
    {
        $number = "";
        // $length = rand(4, 19);
        $length = 5;

        for ($i = 0; $i < $length; $i++) {
            $newRand = rand(0, 9);
            $number = $number . $newRand;
        }
        return $number;
    }

    // Used to hash the new users password
    private function hash_func($data)
    {
        $data = hash("sha1", $data);
        return $data;
    }
}
