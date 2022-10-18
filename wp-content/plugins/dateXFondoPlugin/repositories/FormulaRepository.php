<?php

use dateXFondoPlugin\Connection;

class FormulaRepository
{

    public static function getArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id, id_articolo, nome_articolo, sottotitolo_articolo, sezione, sottosezione, row_type, link FROM DATE_template_fondo WHERE id_articolo IS NOT NULL AND attivo=1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }


    public static function getFormule()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_formula WHERE attivo = 1";
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

    public static function update_formula($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_formula SET sezione = ?, sottosezione = ?, nome = ?, descrizione = ?, condizione = ?, formula = ?, visibile = ? WHERE ID = ?;";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssisii",
            $request['sezione'],
            $request['sottosezione'],
            $request['nome'],
            $request['descrizione'],
            $request['condizione'],
            $request['formula'],
            $request['visibile'],
            $request['id']);
        $stmt->execute();
        mysqli_close($mysqli);
        return $stmt->affected_rows;
    }
    public static function delete_formula($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_formula SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;
    }
}