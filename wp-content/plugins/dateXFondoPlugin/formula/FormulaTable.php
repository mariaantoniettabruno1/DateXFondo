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

    public static function getAllIdsCampo($selected_section, $selected_subsection)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        if(isset($selected_section)){
            $sql = "SELECT DISTINCT id_articolo FROM DATE_template_fondo WHERE sezione=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $selected_section);
            $res = $stmt->execute();
            $res = $stmt->get_result();
            $entries = $res->fetch_all();
            mysqli_close($mysqli);
            return $entries;
        }
        else if (isset($selected_section) && isset($selected_subsection)){
            $sql = "SELECT DISTINCT id_articolo FROM DATE_template_fondo WHERE sezione=? AND sottosezione=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $selected_section,$selected_subsection);
            $res = $stmt->execute();
            $res = $stmt->get_result();
            $entries = $res->fetch_all();
            mysqli_close($mysqli);
            return $entries;
        }
        else{
            $conn = new Connection();
            $mysqli = $conn->connect();
            $sql = "SELECT DISTINCT id_articolo FROM DATE_template_fondo";
            $result = $mysqli->query($sql);
            $entries = $result->fetch_all();
            mysqli_close($mysqli);
            return $entries;
        }


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
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT * FROM DATE_template_fondo WHERE sezione=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("s", $selected_section);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $entries = $res->fetch_all();
//        mysqli_close($mysqli);
//        return $entries;
    }

    public static function saveFormula($sezione, $sottosezione,$formula_name, $formula_description, $formulaCondition, $formula)
    {
       $conn = new Connection();
       $mysqli = $conn->connect();
       $sql = "INSERT INTO DATE_formula (sezione,sottosezione,nome_formula,descrizione_formula,condizione, formula) VALUES (?,?,?,?,?,?) ";
       $stmt = $mysqli->prepare($sql);
       $stmt->bind_param("ssssss", $sezione, $sottosezione, $formula_description,$formula_name, $formulaCondition, $formula);
       $res = $stmt->execute();
       mysqli_close($mysqli);
    }

    public static function getAllFormulasBySection($selected_section)
    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT * FROM DATE_formula WHERE sezione=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("s", $selected_section);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $entries = $res->fetch_all();
//        mysqli_close($mysqli);
//        return $entries;
    }
}