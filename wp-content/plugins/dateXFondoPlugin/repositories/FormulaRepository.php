<?php

use dateXFondoPlugin\Connection;

class FormulaRepository
{

    public static function getArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id_articolo, nome_articolo, sottotitolo_articolo, sezione, sottosezione FROM DATE_template_fondo WHERE id_articolo IS NOT NULL";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }


    public static function getFormule()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_formula";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function create_formula($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_formula (sezione,sottosezione,nome,descrizione,condizione,formula,visibile) VALUES (?,?,?,?,?,?,?) ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssisi",
            $request['sezione'],
            $request['sottosezione'],
            $request['nome'],
            $request['descrizione'],
            $request['condizione'],
            $request['formula'],
            $request['visibile']);
        $stmt->execute();
        mysqli_close($mysqli);
        return $stmt->insert_id;
    }
}