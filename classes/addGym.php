<?php
// This class is used for creating gyms
class AddGym
{
    private $error = "";
    // This function will verify the gym data is valid
    public function evaluate_data($data)
    {
        foreach ($data as $key => $value) {
            // Check that field is not blank
            if (empty($value)) {
                $this->error = $this->error . $key . " is empty.<br>";
            }
            // Check that the long/lat are numbers
            if ($key == 'latitude' || $key == 'longitude') {
                if (!is_numeric($value)) {
                    $this->error = "Latitude/longitude must be a number. ";
                }
            }
        }
        // If there is no error, create the gym
        if ($this->error == "") {
            $this->create_gym($data);
        } else { // Display the error
            return $this->error;
        }
    }


    // This function will create a query which inserts the gym into the database
    public function create_gym($data)
    {
        $gym_id = $this->create_gym_id();
        $gym_name = ucfirst($data['gym_name']);
        $gym_type = ucfirst($data['gym_type']);
        $opening_time = $data['opening_time'];
        $closing_time = $data['closing_time'];
        $phone_number = $data['phone_number'];
        $website_link = $data['website_link'];
        $province = $data['province'];
        $city = $data['city'];
        $address = $data['address'];
        $longitude = $data['longitude'];
        $latitude = $data['latitude'];

        $query = "insert into gymrat_gyms (gym_id,gym_name,gym_type,opening_time,closing_time,phone_number,website_link,province,city,address,longitude,latitude) 
        values ('$gym_id','$gym_name','$gym_type','$opening_time','$closing_time','$phone_number','$website_link','$province','$city','$address','$longitude','$latitude')";

        $DB = new Database();
        $DB->save_db($query);
    }

    // This function will randomly generate the gym id...
    private function create_gym_id()
    {
        $number = "";
        $length = 4;

        for ($i = 0; $i < $length; $i++) {
            $newRand = rand(0, 9);
            $number = $number . $newRand;
        }
        return $number;
    }

    // This function will get all of the information from the gym database...
    public function get_gyms()
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
