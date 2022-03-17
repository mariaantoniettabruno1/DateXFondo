<?php
namespace dateXFondoPlugin;
use mysqli;

/**
classe per la connesione al db di dateXFondo
 */

class Connection
{
    private $url;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        $this->url = DB_HOST . ":" . DB_PORT . "/";
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->dbname = c1DATEXFONDO;
    }

    function connect()
    {
        $conn = new mysqli($this->url, $this->username, $this->password, $this->dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }



}