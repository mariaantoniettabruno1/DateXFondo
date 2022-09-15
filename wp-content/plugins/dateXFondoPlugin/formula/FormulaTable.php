<?php

use dateXFondoPlugin\Connection;

class FormulaTable
{
    public static function getAllSections()
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT sezione FROM DATE_template_fondo";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all();
        mysqli_close($mysqli);
        return $row;
    }

    public static function getAllIdsCampo($selected_section)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT id_campo FROM DATE_template_fondo WHERE sezione=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_section);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }
    public static function getAllSubsections($selected_section)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT sottosezione FROM DATE_template_fondo WHERE sezione=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_section);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public static function getAllEntriesFromSection($selected_section)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_template_fondo WHERE sezione=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_section);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public static function saveFormula($sezione, $sottosezione, $label, $formulaCondition, $formula)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_formula (sezione,sottosezione,label_descrittiva,condizione, formula) VALUES (?,?,?,?,?) ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssss", $sezione, $sottosezione, $label, $formulaCondition, $formula);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

    public static function getAllFormulasBySection($selected_section)
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
}