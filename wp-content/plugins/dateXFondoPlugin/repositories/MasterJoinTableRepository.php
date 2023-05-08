<?php

namespace dateXFondoPlugin;

class MasterJoinTableRepository
{
    public static function getJoinedArticoli($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_template_fondo  WHERE attivo =1 AND template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $template_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getJoinedFormulas($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,sezione,sottosezione,nome,descrizione,condizione,formula,text_type 
                FROM DATE_formula  WHERE attivo =1 AND visibile = 1 AND formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $template_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getJoinedRecords()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id, external_id, type, ordinamento FROM DATE_template_formula";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function updateJoinedIndex($id, $ordinamento)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_template_formula SET ordinamento = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $ordinamento, $id);
        $stmt->execute();
        mysqli_close($mysqli);
        return $stmt->affected_rows;
    }

    public static function insertJoinedIndex($external_id, $type, $ordinamento)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_template_formula (external_id, type, ordinamento) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $external_id, $type, $ordinamento);
        $stmt->execute();
        $id = $mysqli->insert_id;
        mysqli_close($mysqli);
        return $id;
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

    public static function getHistoryArticles($fondo, $anno, $descrizione, $version, $template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,sottotitolo_articolo,descrizione_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name
FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=? AND template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisis", $fondo, $anno, $descrizione, $version, $template_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;

    }

    public static function getHistoryFormulas($template_name,$anno )
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,sezione,sottosezione,nome,descrizione,condizione,formula,text_type 
                FROM DATE_storico_formula  WHERE anno=? AND visibile = 1 AND formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("is", $anno, $template_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getHistoryJoinedRecords($anno)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id, external_id, type, ordinamento FROM DATE_template_formula_storico WHERE anno=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $anno);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }


}