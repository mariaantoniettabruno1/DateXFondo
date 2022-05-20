<?php

namespace dateXFondoPlugin;
class DuplicateOldTemplate
{

    public function getOldData($ente, $anno_precedente)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $ente = 'Comune di Chivasso';
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE ente LIKE ? AND anno=2018 AND attivo=1";//Ho inserito questo anno per filtrare i dati perchè sono troppi
        // dopo eliminare
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $ente);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }

    public function getCurrentData($ente, $anno, $fondo)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE ente LIKE ? AND anno=? AND fondo=? AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $ente, $anno, $fondo);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }
}