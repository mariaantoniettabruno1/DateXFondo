<?php

namespace dateXFondoPlugin;

class RegioniDocumentRepository
{
    public static function getArticoli($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,sezione,sottosezione,ordinamento,titolo_documento,titolo_tabella,nome_articolo,codice,importo,nota,document_name,editable,anno FROM DATE_documento_regioni_autonomie_locali WHERE  attivo=1 and document_name=? ORDER BY ordinamento ASC";
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

    public static function edit_regioni_document_header($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_regioni_autonomie_locali SET document_name=?, anno=?, titolo_documento=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("siss", $request['document_name'], $request['anno'],$request['titolo_documento'], $request['old_document_name']);
        $stmt->execute();
        $mysqli->close();
    }
    public static function delete_regioni_row($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_regioni_autonomie_locali SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
    }

    public static function edit_regioni_document($request){
        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_documento_regioni_autonomie_locali SET 
                               nome_articolo=?,
                               ordinamento=?,
                               codice=?,
                               importo=?,                                                   
                               nota=?
                               
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisssi",
            $request['nome_articolo'],
            $request['ordinamento'],
            $request['codice'],
            $request['importo'],
            $request['nota'],
            $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;
    }

    public static function set_regioni_document_not_editable($request){

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_regioni_autonomie_locali SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali_storico 
                    (ordinamento,titolo_documento,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,document_name,anno,
                     attivo,editable)
                        SELECT  ordinamento,titolo_documento,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,document_name,anno,
                     attivo,editable
FROM DATE_documento_regioni_autonomie_locali WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['document_name']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_documento_regioni_autonomie_locali WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['document_name']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;
    }

    public static function create_new_row_regioni($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali
                    (ordinamento,titolo_documento,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,document_name,anno) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isssssssssi", $request['ordinamento'],$request['titolo_documento'],$request['titolo_tabella'], $request['sezione'], $request['sottosezione'], $request['nome_articolo'],
            $request['codice'], $request['importo'], $request['nota'], $request['document_name'], $request['anno']);
        $stmt->execute();
        $mysqli->close();
    }
}