<?php

use dateXFondoPlugin\Connection;

class FormulaTable
{
    public static function getAllSections()
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT sezione FROM DATE_entry_chivasso";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all();
        mysqli_close($mysqli);
        return $row;
    }

    public static function getAllEntriesFromSection($selected_section)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE sezione=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_section);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public static function saveFormula($sezione, $formula, $fondo, $ente, $anno)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_formula (sezione, formula, fondo, ente, anno) VALUES (?,?,?,?,?) ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssi", $sezione, $formula, $fondo, $ente, $anno);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

}