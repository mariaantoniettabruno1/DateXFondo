<?php

namespace dateXFondoPlugin;

class MasterTemplateRepository
{
    public static function getArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link,editable,version,row_type,heredity FROM DATE_template_fondo WHERE id_articolo IS NOT NULL and attivo=1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function getStoredArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT fondo,anno,descrizione_fondo,editable,version FROM DATE_storico_template_fondo WHERE id_articolo IS NOT NULL and attivo=1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function getDisabledArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link FROM DATE_storico_template_fondo WHERE id_articolo IS NOT NULL and attivo=0";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function edit_header_template($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_template_fondo SET fondo=?,anno=?,descrizione_fondo=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $request['fondo'], $request['anno'], $request['descrizione_fondo']);
        $stmt->execute();
        $mysqli->close();
    }

    public static function edit_row($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_template_fondo SET id_articolo=?,
                               nome_articolo=?,
                               descrizione_articolo=?,
                               sottotitolo_articolo=?,
                               ordinamento=?,
                               nota=?,
                               link=? 
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssissi",
            $request['id_articolo'],
            $request['nome'],
            $request['descrizione'],
            $request['sottotitolo'],
            $request['ordinamento'],
            $request['nota'],
            $request['link'],
            $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;
    }

    public static function set_template_not_editable($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_template_fondo SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "UPDATE DATE_storico_template_fondo SET editable=0 WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['descrizione_fondo'], $request['version']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function active_row($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        //TODO modificare il primo update perchè gli id delle righe potrebbero non coincidere
        $sql = "UPDATE DATE_template_fondo SET attivo=1 WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $sql = "UPDATE DATE_storico_template_fondo SET attivo=1 WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function duplicate_template($request)
    {

    }
//    public static function duplicateTable($year)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT version FROM DATE_template_fondo WHERE anno=? ORDER BY version DESC";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $year);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $prev_version = $res->fetch_assoc()['version'];
//
//        $sql = "SELECT * from DATE_template_fondo WHERE anno=? AND version=? AND attivo=1";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("ii", $year, $prev_version);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $data = $res->fetch_all();
//        $last_version = $prev_version + 1;
//        $sql = "INSERT INTO DATE_template_fondo (fondo,anno,id_campo,sezione,sottosezione,label_campo,descrizione_campo,sottotitolo_campo,valore,valore_anno_precedente,nota,version)
//                                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($data as $entry) {
//            $stmt->bind_param("sisssssssssi", $entry[1], $entry[2], $entry[3], $entry[4], $entry[5], $entry[6], $entry[7], $entry[8], $entry[9], $entry[10], $entry[11], $last_version);
//            $res = $stmt->execute();
//        }
//
//        mysqli_close($mysqli);
//        return $data;
//    }
}