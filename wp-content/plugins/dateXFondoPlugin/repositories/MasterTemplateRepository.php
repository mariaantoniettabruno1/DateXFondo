<?php

namespace dateXFondoPlugin;

class MasterTemplateRepository
{
    public static function getArticoli($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link,editable,version,row_type,heredity,template_name FROM DATE_template_fondo WHERE id_articolo IS NOT NULL and attivo=1 and template_name=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $template_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getStoredArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT fondo,anno,descrizione_fondo,editable,version,template_name FROM DATE_storico_template_fondo WHERE id_articolo IS NOT NULL and attivo=1 ORDER BY ordinamento ASC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function getDisabledArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link,version FROM DATE_template_fondo WHERE attivo = 0 ORDER BY ordinamento ASC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }
    public static function getAllTemplate()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT fondo,anno,descrizione_fondo,template_name FROM DATE_template_fondo  ORDER BY ordinamento ASC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public static function edit_header_template($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_template_fondo SET fondo=?,anno=?,descrizione_fondo=?,template_name=? WHERE template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssss", $request['fondo'], $request['anno'], $request['descrizione_fondo'], $request['template_name'], $request['old_template_name']);
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
                               nota=?,
                               link=?,
                               heredity=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssis",
            $request['id_articolo'],
            $request['nome'],
            $request['descrizione'],
            $request['sottotitolo'],
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
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name)
                        SELECT  fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name 
FROM DATE_template_fondo WHERE template_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $request['template_name'],$request['version']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_template_fondo WHERE template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['template_name']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function active_row($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_template_fondo SET attivo=1 WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $sql = "UPDATE DATE_storico_template_fondo SET attivo=1 WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['descrizione'], $request['version']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function duplicate_template($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,heredity,template_name
FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=? AND id_articolo IS NOT NULL AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['descrizione'], $request['version']);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        $sql = "INSERT INTO DATE_template_fondo 
                    (fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,heredity,template_name) 
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $version = $rows[0]['version'] + 1;
        foreach ($rows as $entry) {
            $stmt->bind_param("sisissssssiissiisis", $entry['fondo'], $entry['anno'], $entry['descrizione_fondo'], $entry['ordinamento'], $entry['id_articolo'],
                $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'], $entry['descrizione_articolo'], $entry['sottotitolo_articolo'], $entry['valore'],
                $entry['valore_anno_precedente'], $entry['nota'], $entry['link'], $entry['attivo'], $version, $entry['row_type'], $entry['heredity'],$entry['template_name']);
            $res = $stmt->execute();
        }
        mysqli_close($mysqli);
        return $res;
    }

    public static function visualize_template($fondo, $anno, $descrizione, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity
FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND descrizione_fondo=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $fondo, $anno, $descrizione, $version);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;

    }


}