<?php

namespace dateXFondoPlugin;

class CustomTable
{
    public static function getAllYears()
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT anno FROM DATE_entry_chivasso";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all();
        mysqli_close($mysqli);
        return $row;
    }

    public static function getAllEntries($selected_year){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE anno=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $selected_year);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

}