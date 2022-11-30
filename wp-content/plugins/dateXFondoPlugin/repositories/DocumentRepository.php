<?php

use dateXFondoPlugin\Connection;

class DocumentRepository
{
    public static function getArticoli($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id,sezione,sottosezione,ordinamento,nome_articolo,preventivo,document_name,editable FROM DATE_documento_modello_fondo WHERE  attivo=1 and document_name=? ORDER BY ordinamento ASC";
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
    public static function getIdsArticoli($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id_articolo FROM DATE_template_fondo WHERE id_articolo IS NOT NULL and attivo=1 and template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $template_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all();
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getSezioni($template_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $template_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getFormulas($document_name){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT nome FROM DATE_formula WHERE formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $document_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all();
        mysqli_close($mysqli);
        return $rows;
    }
    public static function edit_document_row($request){

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_documento_modello_fondo SET 
                               nome_articolo=?,
                               ordinamento=?,
                               preventivo=?
                               
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi",
            $request['nome_articolo'],
            $request['ordinamento'],
            $request['preventivo'],
            $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;

    }

    public static function delete_document_row($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
    }

    public static function set_modello_document_not_editable($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "INSERT INTO DATE_documento_modello_fondo_storico 
                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,anno,
                     attivo,editable)
                        SELECT  ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,anno,
                     attivo,editable
FROM DATE_documento_modello_fondo WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['document_name']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_documento_modello_fondo WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['document_name']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function edit_modello_document_header($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET document_name=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss",  $request['document_name'], $request['old_document_name']);
        $stmt->execute();
        $mysqli->close();
    }
}