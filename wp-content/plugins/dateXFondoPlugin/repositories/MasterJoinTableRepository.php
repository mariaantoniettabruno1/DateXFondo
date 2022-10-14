<?php

namespace dateXFondoPlugin;

class MasterJoinTableRepository
{
    public static function getJoinedArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link,editable,version,row_type,heredity
                FROM DATE_template_fondo  WHERE attivo =1";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getJoinedIdArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id  FROM DATE_template_fondo  WHERE attivo =1";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getJoinedIdFormula()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id  FROM DATE_formula  WHERE attivo =1";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }


    public static function getJoinedFormulas()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,sezione,sottosezione,nome,descrizione,condizione,formula 
                FROM DATE_formula  WHERE attivo =1 AND visibile = 1";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getJoinedRecords()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT external_id,type,ordinamento FROM DATE_template_formula";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function updateJoinedArticoli($articoli_ids, $formula_ids)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT IGNORE INTO DATE_template_formula (external_id,type) VALUES (?, 0)";
        $stmt = $mysqli->prepare($sql);
        foreach ($articoli_ids[0] as $id) {
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
        }
        $sql = "INSERT IGNORE INTO DATE_template_formula (external_id,type) VALUES (?, 1)";
        $stmt = $mysqli->prepare($sql);
        foreach ($formula_ids[0] as $id) {
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
        }
        mysqli_close($mysqli);

    }


}