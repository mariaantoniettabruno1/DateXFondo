<?php

namespace dateXFondoPlugin;
class DuplicateOldTemplate
{

    public function getOldData($ente, $anno_precedente)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE ente LIKE ? AND anno=? AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $ente, $anno_precedente);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public function getCurrentData($ente, $anno, $fondo, $startRecord, $limit)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT version FROM DATE_entry_chivasso WHERE anno=? ORDER BY version DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $anno);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $last_version = $res->fetch_assoc()['version'];
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE ente LIKE ? AND anno=? AND fondo=? AND attivo=1 AND version=? LIMIT ? OFFSET ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisiii", $ente, $anno, $fondo,$last_version, $limit, $startRecord);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public function getCurrentDataCount($ente, $anno, $fondo){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT count(*) FROM DATE_entry_chivasso WHERE ente LIKE ? AND anno=? AND fondo=? AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $ente, $anno, $fondo);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries[0][0];
    }

    public function getLastRowID()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT MAX(id) FROM DATE_entry_chivasso";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        mysqli_close($mysqli);
        return $row['MAX(id)'];

    }

    public static function getTableNotEditable($year)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_submitted_years (anno) VALUES (?) ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $year);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

    public static function isReadOnly($year)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT anno FROM DATE_submitted_years WHERE anno=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $year);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        mysqli_close($mysqli);
        return $res->num_rows;
    }
    public static function deleteReadOnly($year)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "DELETE FROM DATE_submitted_years WHERE anno=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $year);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

    public static function duplicateTable($year)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT version FROM DATE_entry_chivasso WHERE anno=? ORDER BY version DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $year);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $prev_version = $res->fetch_assoc()['version'];

        $sql = "SELECT * from DATE_entry_chivasso WHERE anno=? AND version=? AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $year, $prev_version);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_all();
        $last_version = $prev_version + 1;
        $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno,id_campo,sezione,label_campo,descrizione_campo,sottotitolo_campo,valore,valore_anno_precedente,nota,version)
                                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($data as $entry) {
            $stmt->bind_param("ssissssssssi", $entry[1], $entry[2], $entry[3], $entry[4], $entry[5], $entry[6], $entry[7], $entry[8], $entry[9], $entry[10], $entry[11], $last_version);
            $res = $stmt->execute();
        }

        mysqli_close($mysqli);
        return $data;
    }
}