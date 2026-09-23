<?php
require_once("data.php");

class myparent {
    protected $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli(SERVER, UID, PWD, DB);
        if ($this->mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $this->mysqli->connect_error);
        }
    }
}
?>