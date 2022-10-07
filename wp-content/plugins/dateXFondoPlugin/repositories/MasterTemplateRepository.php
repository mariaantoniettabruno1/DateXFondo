<?php

namespace dateXFondoPlugin;

class MasterTemplateRepository
{
    public static function getArticoli()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,fondo,anno,descrizione_fondo,ordinamento,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link FROM DATE_template_fondo WHERE id_articolo IS NOT NULL and attivo=1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }


    public static function edit_header_template($request)
    {
//
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "UPDATE DATE_template_fondo SET fondo=?,anno=?,descrizione_fondo=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("sss", $request['fondo'],$request['anno'],$request['descrizione_fondo']);
//        $stmt->execute();
//        mysqli_close($mysqli);
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
            $input['nome'],
            $input['descrizione'],
            $input['sottitotolo'],
            $input['ordinamento'],
            $input['nota'],
            $input['link'],
            $input['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;
    }
//    public function getAnno(){
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT anno FROM DATE_template_fondo  ORDER BY id DESC LIMIT 1";
//        $result = $mysqli->query($sql);
//        $anno = $result->fetch_assoc()['anno'];
//        mysqli_close($mysqli);
//        return $anno;
//    }
//    public function getFondo($anno)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT fondo FROM DATE_template_fondo WHERE anno=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $anno);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $fondo = $res->fetch_assoc()['fondo'];
//        mysqli_close($mysqli);
//        return $fondo;
//    }
//
//    public function getOldData($anno_precedente)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT * FROM DATE_template_fondo WHERE  anno=? AND attivo=1";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $anno_precedente);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $entries = $res->fetch_all();
//        mysqli_close($mysqli);
//        return $entries;
//    }

//    public function getCurrentDataBySubsections($anno, $fondo, $section, $subsection)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT version FROM DATE_template_fondo WHERE anno=? ORDER BY version DESC";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $anno);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $last_version = $res->fetch_assoc()['version'];
//        $sql = "SELECT * FROM DATE_template_fondo WHERE anno=? AND fondo=? AND sezione=? AND sottosezione=? AND attivo=1 AND version=? ORDER BY ordinamento";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("isssi", $anno, $fondo, $section, $subsection, $last_version);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $entries = $res->fetch_all();
//        mysqli_close($mysqli);
//        return $entries;
//    }
//
//    public function getAllSections($fondo, $anno)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT version FROM DATE_template_fondo WHERE anno=? ORDER BY version DESC";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $anno);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $last_version = $res->fetch_assoc()['version'];
//        $sql = "SELECT DISTINCT sezione FROM DATE_template_fondo WHERE anno=? AND fondo=? AND attivo=1 AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("isi", $anno, $fondo, $last_version);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $entries = $res->fetch_all();
//        mysqli_close($mysqli);
//        return $entries;
//    }
//
//    public static function getTableNotEditable($year)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "INSERT INTO DATE_submitted_years (anno) VALUES (?) ";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $year);
//        $res = $stmt->execute();
//        mysqli_close($mysqli);
//    }
//
//    public static function isReadOnly($year)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT anno FROM DATE_submitted_years WHERE anno=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $year);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        mysqli_close($mysqli);
//        return $res->num_rows;
//    }
//
//    public static function deleteReadOnly($year)
//    {
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "DELETE FROM DATE_submitted_years WHERE anno=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("i", $year);
//        $res = $stmt->execute();
//        mysqli_close($mysqli);
//    }
//
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