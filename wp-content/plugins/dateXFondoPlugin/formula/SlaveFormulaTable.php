<?php

use dateXFondoPlugin\Connection;

class SlaveFormulaTable
{
    public static function getFormulaBySelectedSection($selected_section)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_formula WHERE sezione=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_section);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public static function getValueFromIdCampo($id_campo)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT valore FROM DATE_entry_chivasso WHERE id_campo=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $id_campo);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_assoc();
        mysqli_close($mysqli);
        return $entries;
    }
}