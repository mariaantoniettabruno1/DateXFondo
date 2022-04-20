<?php

namespace dateXFondoPlugin;
class DuplicateOldTemplate
{
    public function getOldData($ente)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_entry_chivasso WHERE ente LIKE ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $ente);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_all();
        mysqli_close($mysqli);
        return $entries;
    }
}