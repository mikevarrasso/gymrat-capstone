<?php
// This class is used to connect to the database
class Database
{
    // Connection Variables
    private $host = "us-cdbr-east-06.cleardb.net";
    private $username = "bddcfa07a2ccae";
    private $password = "73888856";
    private $db = "heroku_802bf58c831e79c";

    // This function connects to the databse using a connection string
    function connect_db()
    {
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $connection;
    }

    // This function will read from the databse 
    function read_db($query)
    {
        $con = $this->connect_db();
        $result = mysqli_query($con, $query);

        // Verify result
        if ($result == false) {
            return false;
        } else {

            $data = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            return $data;
        }
    }

    // This function will save the database
    function save_db($query)
    {
        $con = $this->connect_db();
        $result = mysqli_query($con, $query);

        // Verify result
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }
}
