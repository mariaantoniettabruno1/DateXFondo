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
                               link=?,
                               heredity=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssissii",
            $request['id_articolo'],
            $request['nome'],
            $request['descrizione'],
            $request['sottotitolo'],
            $request['ordinamento'],
            $request['nota'],
            $request['link'],
            $request['heredity'],
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
        $sql = "INSERT INTO DATE_storico_template_fondo 
                    (fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity)
                        SELECT  fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity
FROM DATE_template_fondo";
        $stmt = $mysqli->prepare($sql);
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
        $sql = "UPDATE DATE_storico_template_fondo SET attivo=1 WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'],$request['anno'],$request['descrizione'],$request['version']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function duplicate_template($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
//        $sql = " TRUNCATE table DATE_template_fondo";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->execute();
        $sql = "SELECT ALL FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=? AND id_articolo IS NOT NULL AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['descrizione'], $request['version']);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        //inserire insert nella table DATE_storico_template con la version++ rispetto alla precedente
        mysqli_close($mysqli);
        return $rows;
    }

    public static function visualize_template($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT ALL FROM DATE_storico_template_fondo WHERE attivo=1 AND fondo=? AND anno=? AND descrizione_fondo=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'],$request['anno'],$request['descrizione'],$request['version']);
        $res = $stmt->execute();
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
}